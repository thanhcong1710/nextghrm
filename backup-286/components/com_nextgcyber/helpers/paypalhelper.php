<?php

/**
 * @package nextgcyber NextgCyber for Joomla
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://domain.com
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;

JLoader::register('NextgCyberHelper', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/helper.php');
JLoader::register('NextgCyberCurrencyHelper', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/currencyhelper.php');

class NextgCyberPaypalHelper extends NextgCyberHelper {

    protected static function prepareData($invoice_id) {
        $app = JFactory::getApplication();
        $invoiceModel = JModelLegacy::getInstance('Invoice', 'NextgCyberModel');
        $invoice = $invoiceModel->getItem($invoice_id);
        $currency = NextgCyberCurrencyHelper::getCurrency();
        if (empty($currency) || empty($invoice)) {
            return false;
        }
        // Generate data for payment gateway
        $sslCfg = ($app->get('force_ssl') == 2) ? 1 : 2;
        $data = [];
        $data['currency_code'] = $currency->name;
        $data['name'] = $invoice->number;
        $data['amount'] = round($invoice->amount_total, 2);
        $config = JFactory::getConfig();
        $data['signature'] = md5($config->get('secret') . 'NEXTGCYBER-INVOICE' . $invoice_id);
        $data['return_url'] = NextgCyberHelperRoute::removeItemId(JRoute::_('index.php?option=com_nextgcyber&task=paypal.success&id=' . $invoice->id, true, $sslCfg));
        $data['cancel_url'] = NextgCyberHelperRoute::removeItemId(JRoute::_('index.php?option=com_nextgcyber&view=invoice&id=' . $invoice->id, true, $sslCfg));
        $data['notify_url'] = NextgCyberHelperRoute::removeItemId(JRoute::_('index.php?option=com_nextgcyber&task=paypal.getPayment', true, $sslCfg));
        return $data;
    }

    public static function sendRequest($invoice_id) {
        if (empty($invoice_id)) {
            return false;
        }
        $data = static::prepareData($invoice_id);
        $sandbox_mode = NextgCyberHelper::getParam('sandbox_mode');
        if ($sandbox_mode) {
            $account = NextgCyberHelper::getParam('paypal_sandbox_account');
        } else {
            $account = NextgCyberHelper::getParam('paypal_account');
        }

        $querystring = "";
        $querystring .= "?business=" . urlencode($account) . "&";
        $querystring .= "item_name=" . urlencode($data['name']) . "&";
        $querystring .= "amount=" . $data['amount'] . "&";
        $querystring .= "cmd=_xclick&";
        $querystring .= "no_note=1&";
        $querystring .= "lc=UK&";
        $querystring .= "rm=2&";
        $querystring .= "currency_code=" . $data['currency_code'] . "&";
        $querystring .= "bn=PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest&";
        $querystring .= "item_number=" . $invoice_id . "&";
        $querystring .= "custom=" . $data['signature'] . "&";
        $querystring .= "return=" . urlencode(stripslashes($data['return_url'])) . "&";
        $querystring .= "cancel_return=" . urlencode(stripslashes($data['cancel_url'])) . "&";
        $querystring .= "notify_url=" . urlencode($data['notify_url']);

        if ($sandbox_mode) {
            $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr' . $querystring;
        } else {
            $url = 'https://www.paypal.com/cgi-bin/webscr' . $querystring;
        }
        return JStringPunycode::urlToPunycode($url);
    }

    public static function validate() {
        $app = JFactory::getApplication();
        $data = array();
        $data['verified'] = false;
        // Check if paypal request or response
        $jinput = $app->input;
        $post_arr = $jinput->getArray($_POST);
        $data = array();
        $data['verified'] = false;
        $data['cancel'] = false;
        // Response from Paypal
        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';
        foreach ($post_arr as $key => $value) {
            $value = urlencode(stripslashes($value));
            //$value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i', '${1}%0D%0A${3}', $value); // IPN fix
            $req .= "&$key=$value";
        }

        // assign posted variables to local variables
        $data['item_name'] = $jinput->get('item_name');
        $data['item_number'] = $jinput->get('item_number', null);
        $data['payment_status'] = $jinput->get('payment_status');
        $data['amount'] = $jinput->get('mc_gross');
        $data['currency'] = $jinput->get('mc_currency');
        $data['txn_id'] = $jinput->get('txn_id');
        $data['txn_type'] = $jinput->get('txn_type');
        $data['receiver_email'] = $jinput->getString('receiver_email');
        $data['payer_email'] = $jinput->get('payer_email');
        $data['signature'] = $jinput->get('custom');
        $data['payer_id'] = $jinput->get('payer_id');
        $data['receiver_id'] = $jinput->get('receiver_id');

        // User cancel payment
        if ($data['txn_type'] == 'subscr_cancel') {
            $data['cancel'] = true;
        }

        // Status is not completed
        if ($data['payment_status'] != 'Completed') {
            return $data;
        }

        $config = JFactory::getConfig();
        $sign = md5($config->get('secret') . 'NEXTGCYBER-INVOICE' . (int) $data['item_number']);
        if ($data['signature'] != $sign) {
            return $data;
        }

        // Validate Email/Id
        $sandbox_mode = NextgCyberHelper::getParam('sandbox_mode');
        if ($sandbox_mode) {
            $account = NextgCyberHelper::getParam('paypal_sandbox_account');
        } else {
            $account = NextgCyberHelper::getParam('paypal_account');
        }

        if (filter_var($data['receiver_email'], FILTER_VALIDATE_EMAIL)) {
            if ($data['receiver_email'] != $account) {
                return $data;
            }
        } else {
            if ($data['receiver_id'] != $account) {
                return $data;
            }
        }


        // post back to PayPal system to validate
        $header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Host: www.paypal.com\r\n";
        $header .= "Connection: close\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
        $paypal_url = 'paypal.com';
        if ($sandbox_mode) {
            $paypal_url = 'sandbox.' . $paypal_url;
        }

        if (extension_loaded('openssl')) {
            $paypal_url = 'ssl://www.' . $paypal_url;
        } else {
            $paypal_url = 'www.' . $paypal_url;
        }

        $fp = fsockopen($paypal_url, 443, $errno, $errstr, 30);
        if (!$fp) {
            // HTTP ERROR
            $app->enqueueMessage($errno . ': ' . $errstr, 'error');
            return $data;
        } else {
            fputs($fp, $header . $req);
            $verified = false;
            while (!feof($fp)) {
                $res = fgets($fp, 1024);
                $res = trim($res);
                if (strcmp($res, "VERIFIED") == 0) {
                    // Used for debugging
                    //@mail("you@youremail.com", "PAYPAL DEBUGGING", "Verified Response<br />data = <pre>".print_r($post, true)."</pre>");
                    // Validate payment (Check unique txnid & correct price)
                    $verified = true;
                } else if (strcmp($res, "INVALID") == 0) {
                    $app->enqueueMessage(JText::_('COM_NEXTGCYBER_PAYPAL_METHOD_ERROR_PAYMENT_INVALID'), 'error');
                    $verified = false;
                }
            }
            fclose($fp);
            if ($verified) {
                $data['verified'] = $verified;
                return $data;
            }
        }
        return $data;
    }

}

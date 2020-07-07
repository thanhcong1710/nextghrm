<?php

/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2015 NextgCyber . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://nextgerp.com
 * @author NextG-ERP support@nextgerp.com
 */
// No direct access
defined('_JEXEC') or die;
JLoader::register('NextgCyberCustomerHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/profilehelper.php');
JLoader::register('NextgCyberPaypalHelper', JPATH_COMPONENT . '/helpers/paypalhelper.php');

class NextgCyberControllerPaypal extends JControllerLegacy {

    public function getPayment() {
        $verifyData = NextgCyberPaypalHelper::validate();
        if (empty($verifyData)) {
            return false;
        }
        if ($verifyData['verified']) {
            $invoiceModel = NextgCyberHelper::getAdminModel('Invoice');
            $journal_id = NextgCyberHelper::getParam('paypal_journal_id');
            $invoiceModel->registerPayment($verifyData['item_number'], (int) $journal_id, (float) $verifyData['amount'], $type = 'receipt');
        }
    }

    public function success() {
        $app = JFactory::getApplication();
        $invoice_id = $this->input->get('id', null, 'int');
        $app->enqueueMessage(JText::_('COM_NEXTGCYBER_PAYPAL_PAYMENT_SUCCESS'));
        $url = NextgCyberHelperRoute::getInvoiceRoute($invoice_id);
        $app->redirect($url);
        return true;
    }

}

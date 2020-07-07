<?php

/**
 * @package pkg_nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2015 NextgCyber . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://nextgerp.com
 * @author NextG-ERP support@nextgerp.com
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
JLoader::register('NextgCyberModelBaseItem', JPATH_COMPONENT . '/models/baseitem.php');

class NextgCyberModelInvoice extends NextgCyberModelBaseItem {

    /**
     * Model context string.
     *
     * @var    string
     * @since  12.2
     */
    protected $_context = 'com_nextgcyber.account.invoice';
    protected $odoo_model = 'account.invoice';

    /**
     * Method to get invoice state label
     * @param string $state
     * @return string
     * @since 1.0
     * @author Daniel.Vu
     */
    public function getStateLabel($state) {
        switch ($state) {
            case 'deploy':
                $invoice_state_label = JText::_('COM_NEXTGCYBER_INVOICE_DEPLOYED_LABEL');
                break;
            case 'draft':
                $invoice_state_label = JText::_('COM_NEXTGCYBER_INVOICE_DRAFT_LABEL');
                break;
            case 'open':
                $invoice_state_label = JText::_('COM_NEXTGCYBER_INVOICE_OPEN_LABEL');
                break;
            case 'paid':
                $invoice_state_label = JText::_('COM_NEXTGCYBER_INVOICE_PAID_LABEL');
                break;
            case 'cancel':
                $invoice_state_label = JText::_('COM_NEXTGCYBER_INVOICE_CANCEL_LABEL');
                break;
            case 'confirm':
                $invoice_state_label = JText::_('COM_NEXTGCYBER_INVOICE_CONFIRM_LABEL');
                break;
            default:
                $invoice_state_label = "";
                break;
        }
        return $invoice_state_label;
    }

    /**
     * Method to get all invoice lines data of invoice
     * @param integer $invoice_id
     * @return array
     * @since 1.0
     * @author Daniel.Vu
     */
    public function getInvoiceLines($invoice_id) {
        $db = $this->getOdooDB();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('account.invoice.line');
        $query->where(array('invoice_id', '=', $invoice_id));
        $db->setQuery($query);
        $items = $db->loadObjectList();
        foreach ($items as &$item) {
            $item->taxes = $this->getInvoicelineTaxes($item->invoice_line_tax_id);
        }
        unset($item);
        return $items;
    }

    /**
     * Method to get all taxes of invoice line
     * @param array $tax_ids
     * @return array
     * @since 1.0
     * @author Daniel.Vu
     */
    protected function getInvoicelineTaxes($tax_ids) {
        $db = $this->getOdooDB();
        $query = $db->getQuery(true);
        $query->select('id,name,amount,price_include,display_name');
        $query->from('account.tax');
        $query->where(array('id', 'in', $tax_ids));
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    /**
     * Method to get all payments of invoice
     * @param array $payment_ids
     * @return array
     * @since 1.0
     * @author Daniel.Vu
     */
    protected function getPayments($payment_ids) {
        $db = $this->getOdooDB();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('account.move.line');
        $query->where(array('id', 'in', $payment_ids));
        $db->setQuery($query);
        $items = $db->loadObjectList();
        return $items;
    }

    public function getItem($pk = null) {
        $item = parent::getItem($pk);
        if (!empty($item->id)) {
            $item->invoicelines = $this->getInvoiceLines($item->id);
            $item->payments = $this->getPayments($item->payment_ids);
        }
        return $item;
    }

}

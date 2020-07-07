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

class NextgCyberModelOrder extends NextgCyberModelBaseItem {

    /**
     * Model context string.
     *
     * @var    string
     * @since  12.2
     */
    protected $_context = 'com_nextgcyber.sale.order';
    protected $odoo_model = 'sale.order';

    /**
     * Method to get state label
     * @param string $state
     * @return string
     * @since 1.0
     * @author Daniel.Vu
     */
    public function getStateLabel($state) {
        switch ($state) {
            case 'deploy':
                $order_state_label = JText::_('COM_NEXTGCYBER_ORDER_DEPLOYED_LABEL');
                break;
            case 'draft':
                $order_state_label = JText::_('COM_NEXTGCYBER_ORDER_DRAFT_LABEL');
                break;
            case 'done':
                $order_state_label = JText::_('COM_NEXTGCYBER_ORDER_DONE_LABEL');
                break;
            case 'cancel':
                $order_state_label = JText::_('COM_NEXTGCYBER_ORDER_CANCEL_LABEL');
                break;
            case 'progress':
                $order_state_label = JText::_('COM_NEXTGCYBER_ORDER_PROGRESS_LABEL');
                break;
            case 'manual':
                $order_state_label = JText::_('COM_NEXTGCYBER_ORDER_MANUAL_LABEL');
                break;
            case 'confirm':
                $order_state_label = JText::_('COM_NEXTGCYBER_ORDER_CONFIRM_LABEL');
                break;
            default:
                $order_state_label = "";
                break;
        }
        return $order_state_label;
    }

    /**
     * Method to get all invoice lines data of saleorder
     * @param integer $order_id
     * @return array
     * @since 1.0
     * @author Daniel.Vu
     */
    public function getOrderLines($order_id) {
        $db = $this->getOdooDB();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('sale.order.line');
        $query->where(array('order_id', '=', $order_id));
        $db->setQuery($query);
        $items = $db->loadObjectList();
        foreach ($items as &$item) {
            $item->taxes = $this->getOrderlineTaxes($item->tax_id);
        }
        unset($item);
        return $items;
    }

    /**
     * Method to get all taxes of order line
     * @param array $tax_ids
     * @return array
     * @since 1.0
     * @author Daniel.Vu
     */
    protected function getOrderlineTaxes($tax_ids) {
        $db = $this->getOdooDB();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('account.tax');
        $query->where(array('id', 'in', $tax_ids));
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    public function getItem($pk = null) {
        $item = parent::getItem($pk);
        if (!empty($item->id)) {
            $item->orderlines = $this->getOrderLines($item->id);
        }
        return $item;
    }

}

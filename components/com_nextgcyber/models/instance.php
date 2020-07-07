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

class NextgCyberModelInstance extends NextgCyberModelBaseItem {

    /**
     * Model context string.
     *
     * @var    string
     * @since  12.2
     */
    protected $_context = 'com_nextgcyber.nc.instance';
    protected $odoo_model = 'nc.instance';

    /**
     * Method to get instance state label
     * @param string $state
     * @return string
     * @since 1.0
     * @author Daniel.Vu
     */
    public function getStateLabel($state) {
        switch ($state) {
            case 'deploy':
                $deploystate_label = JText::_('COM_NEXTGCYBER_INSTANCE_DEPLOYED_LABEL');
                break;
            case 'draft':
                $deploystate_label = JText::_('COM_NEXTGCYBER_INSTANCE_DRAFT_LABEL');
                break;
            case 'confirm':
                $deploystate_label = JText::_('COM_NEXTGCYBER_INSTANCE_CONFIRM_LABEL');
                break;
            default:
                $deploystate_label = "";
                break;
        }
        return $deploystate_label;
    }

    public function getItem($pk = null) {
        $item = parent::getItem($pk);
        $item = $this->getInfo($item);
        return $item;
    }

    public function getInfo($item) {
        if (!empty($item->id)) {
            $item->fullsubdomain_name = $item->name . '.' . $item->based_domain_id[1];
            $item->orders = $this->getReferenceOrder($item->sale_order_ids);
            $item->odoo_version = $this->getOdooVersion($item->vserver_id[0]);
            $item->apps = $this->getApps($item->allowed_module_ids);
            $item->custom_domains = $this->getCustomDomain($item->customer_domain_ids);
            $item->open_invoice = $this->getOpenInvoice($item->id);
        }
        return $item;
    }

    protected function getOdooVersion($vserver_id) {
        $db = $this->getOdooDB();
        $query = $db->getQuery(true);
        $query->select('odoo_version_id');
        $query->from('nc.vserver');
        $query->where(array('id', '=', $vserver_id));
        $db->setQuery($query);
        $item = $db->loadObject();
        return $item->odoo_version_id[1];
    }

    /**
     * Method to get all reference saleorder with instance
     * @param array $order_ids
     * @return array
     * @since 1.0
     * @author Daniel.Vu
     */
    protected function getReferenceOrder($order_ids) {
        $ordersModel = JModelLegacy::getInstance('Orders', 'NextgCyberModel');
        $ordersModel->clearState();
        $ordersModel->setState('filter.id', $order_ids);
        $items = $ordersModel->getItems();
        return $items;
    }

    protected function getApps($module_ids) {
        $pricingModel = JModelLegacy::getInstance('Pricing', 'NextgCyberModel');
        $pricingModel->clearState();
        $pricingModel->setState('filter.nc_module_id', $module_ids);
        $pricingModel->setState('filter.nc_type', 'odoo_module');
        $items = $pricingModel->getItems();
        return $items;
    }

    protected function getCustomDomain($customer_domain_ids) {
        $db = $this->getOdooDB();
        $query = $db->getQuery(true);
        $query->select('name');
        $query->from('nc.instance.customer.domain');
        $query->where(array('id', 'in', $customer_domain_ids));
        $query->where(array('state', '=', 'verify'));
        $db->setQuery($query);
        $items = $db->loadObjectList();
        return $items;
    }

    protected function getOpenInvoice($instance_id) {
        $invoicesModel = JModelLegacy::getInstance('Invoices', 'NextgCyberModel');
        $invoicesModel->clearState();
        $invoicesModel->setState('filter.instance_id', $instance_id);
        $invoicesModel->setState('filter.state', 'open');
        $items = $invoicesModel->getItems();
        return $items;
    }

}

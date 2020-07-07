<?php

/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://nextgcyber.com
 * @author Daniel.Vu
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
JLoader::register('NextgCyberModelBaseAdmin', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/models/baseadmin.php');
JLoader::register('NextgCyberIPHelper', JPATH_BASE . '/components/com_nextgcyber/helpers/iphelper.php');

/**
 * NextgCyber Model
 */
class NextgCyberModelOrder extends NextgCyberModelBaseAdmin {

    protected $odoo_model = 'sale.order';

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param       type    The table type to instantiate
     * @param       string  A prefix for the table class name. Optional.
     * @param       array   Backupuration array for model. Optional.
     * @return      QATableBank  A database object
     * @since       3.2
     */
    public function getTable($type = 'Order', $prefix = 'NextgCyberTable', $backup = array()) {
        return JTable::getInstance($type, $prefix, $backup);
    }

    public function getForm($data = array(), $loadData = true) {
        $name = $this->getName();
        // Get the form.
        $form = $this->loadForm('com_nextgcyber.' . $name, $name, array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
        $jinput = JFactory::getApplication()->input;

        // The front end calls this model and uses a_id to avoid id clashes so we need to check for that first.
        if ($jinput->get('a_id')) {
            $id = $jinput->get('a_id', 0);
        }
        // The back end uses id so we use that the rest of the time and set it to 0 by default.
        else {
            $id = $jinput->get('id', 0);
        }

        // Determine correct permissions to check.
        if ($this->getState($name . '.id')) {
            $id = $this->getState($name . '.id');
        }

        $user = JFactory::getUser();

        // Modify the form based on Edit State access controls.
        if ($id != 0 && (!$user->authorise('core.edit.state', 'com_nextgcyber.' . $name . '.' . (int) $id)) || ($id == 0 && !$user->authorise('core.edit.state', 'com_nextgcyber'))
        ) {
            // Disable fields for display.
            $form->setFieldAttribute('published', 'disabled', 'true');

            // Disable fields while saving.
            // The controller has already verified this is an bank you can edit.
            $form->setFieldAttribute('published', 'filter', 'unset');
        }

        if ($id != 0) {
            $form->setFieldAttribute('name', 'readonly', 'true');
            $form->setFieldAttribute('partner_id', 'readonly', 'true');
            $form->setFieldAttribute('pricelist_id', 'readonly', 'true');
            $form->setFieldAttribute('active', 'readonly', 'true');
        }

        return $form;
    }

    /**
     *
     * @param array $data
     * @return boolean
     *
     * @since 1.0
     */
    public function save($data) {
        $is_new = false;
        $use_tax = NextgCyberIPHelper::useTax();
        if (empty($data['id'])) {
            $partnerModel = NextgCyberHelper::getAdminModel('Partner');
            $partner = $partnerModel->getItem($data['partner_id']);
            $data['pricelist_id'] = $partner->property_product_pricelist;
            $is_new = true;
            $data = array_merge($this->getDefaultData(), $data);
            $data['name'] = '/';
            $data['partner_id'] = (int) $data['partner_id'];
            $data['nc_type'] = (empty($data['nc_type'])) ? 'odoo_instance' : $data['nc_type'];
            $customerData = $this->onChangePartner($data['partner_id']);
            if (!empty($customerData['value'])) {
                $data = array_merge($data, $customerData['value']);
            }

            $order_line = [];
            if (!empty($data['products'])) {
                foreach ($data['products'] as $product) {
                    $product_data = $this->onChangeProduct($product['id'], $data['pricelist_id'], $product['quantity'], $data['partner_id'], $data['nc_payment_period_id']);
                    if (!empty($product_data['value'])) {
                        $product_item = $product_data['value'];
                        $product_item['product_id'] = $product['id'];
                        $product_item['product_uom_qty'] = $product['quantity'];
                        $product_item['product_uos_qty'] = 1;
                        if (!empty($product_item['tax_id']) && $use_tax) {
                            $tax_item = [];
                            foreach ($product_item['tax_id'] as $tax_id) {
                                $tax_item[] = array(6, 0, array($tax_id));
                            }
                            $product_item['tax_id'] = $tax_item;
                        } else {
                            $product_item['tax_id'] = null;
                        }

                        $order_line[] = array(0, 0, $product_item);
                    }
                }
            }
            $data['order_line'] = $order_line;
        }

        if (parent::save($data)) {
            $id = $this->getState($this->getName() . '.id');
            return $id;
        }
        return false;
    }

    /**
     * Method to call action confirm saleorder
     * @param integer $order_id
     * @return boolean|integer invoice_id if success or false if failed
     * @since 1.0
     * @author Daniel.Vu
     */
    public function confirm($order_id) {
        $conn = $this->getOdooCnn();
        $conn->call_workflow($this->odoo_model, 'order_confirm', $order_id);
        $this->createInvoice($order_id);
        $item = $this->getItem($order_id);
        $name = 0;
        if (!empty($item->invoice_ids->$name)) {
            $invoice_id = (int) $item->invoice_ids->$name;
            $invoiceModel = NextgCyberHelper::getAdminModel('Invoice');
            $invoiceModel->open($invoice_id);
            return $invoice_id;
        }
        return false;
    }

    /**
     * Method to set discount into sales order
     * @param integer $order_id
     * @return boolean
     * @since 1.0
     * @author Daniel.Vu
     */
    public function applyDiscount($order_id) {
        $conn = $this->getOdooCnn();
        return $conn->call($this->odoo_model, 'action_apply_discount', array($order_id), array());
    }

    /**
     * Method get product information when add product into order line
     * @param integer $product_id
     * @param integer $pricelist_id
     * @param integer $qty
     * @param integer $partner_id
     * @param integer $paymentperiod_id
     * @return array
     * @since 1.0
     * @author Daniel.Vu
     */
    protected function onChangeProduct($product_id, $pricelist_id, $qty, $partner_id, $paymentperiod_id) {
        $conn = $this->getOdooCnn();
        return $conn->call('sale.order.line', 'get_product_info', array('pricelist' => (int) $pricelist_id, 'product' => (int) $product_id, 'qty' => (int) $qty, 'partner_id' => (int) $partner_id, 'update_tax' => true, 'payment_period' => (int) $paymentperiod_id), array());
    }

    /**
     * Method to load data with partner
     * @param integer $partner_id
     * @return array
     * @since 1.0
     * @author Daniel.Vu
     */
    protected function onChangePartner($partner_id) {
        $conn = $this->getOdooCnn();
        return $conn->call($this->odoo_model, 'get_partner_info', array($partner_id), array());
    }

    /**
     * Method to get all default data for create new order
     * @return array
     * @since 1.0
     * @author Daniel.Vu
     */
    protected function getDefaultData() {
        $conn = $this->getOdooCnn();
        $args = array("origin", "message_follower_ids", "categ_ids", "order_line", "nc_type", "campaign_id", "currency_id", "invoice_exists", "nc_instance_id", "client_order_ref", "date_order", "partner_id", "message_ids", "amount_tax", "fiscal_position", "payment_term", "company_id", "nc_instnace_based_domain", "note", "state", "pricelist_id", "invoiced", "nc_updated_instance", "portal_payment_options", "section_id", "partner_invoice_id", "amount_untaxed", "nc_instance_vserver_id", "project_id", "name", "partner_shipping_id", "user_id", "nc_instance_subdomain", "medium_id", "source_id", "amount_total");
        return $conn->call($this->odoo_model, 'default_get', $args);
    }

    /**
     * Method to create new invoice
     * @param integer $order_id
     * @return integer|boolean invoice id or false if failed
     * @since 1.0
     * @author Daniel.Vu
     */
    protected function createInvoice($order_id) {
        $conn = $this->getOdooCnn();
        return $conn->call_workflow($this->odoo_model, 'manual_invoice', $order_id);
    }

    /**
     * Method to create new instance
     * @param integer $order_id
     * @param string $name subdomain name
     * @return integer|Boolean instance id or false if failed
     * @since 1.0
     * @author Daniel.Vu
     */
    public function createInstance($order_id, $name) {
        $conn = $this->getOdooCnn();
        $id = $conn->create('nc.sale.order.create.instance', array('order_id' => $order_id, 'name' => $name));
        return $conn->call('nc.sale.order.create.instance', 'action_create_instance', array($id));
    }

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

    /**
     * Method to update instance resources
     * @param integer $order_id
     * @return boolean
     * @since 1.3
     */
    public function updateInstance($order_id) {
        $conn = $this->getOdooCnn();
        return $conn->call($this->odoo_model, 'action_update_instance', array($order_id));
    }

}

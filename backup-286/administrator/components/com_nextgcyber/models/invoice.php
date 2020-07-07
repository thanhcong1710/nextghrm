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

/**
 * NextgCyber Model
 */
class NextgCyberModelInvoice extends NextgCyberModelBaseAdmin {

    protected $odoo_model = 'account.invoice';

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param       type    The table type to instantiate
     * @param       string  A prefix for the table class name. Optional.
     * @param       array   Backupuration array for model. Optional.
     * @return      QATableBank  A database object
     * @since       3.2
     */
    public function getTable($type = 'Invoice', $prefix = 'NextgCyberTable', $backup = array()) {
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

        return $form;
    }

    /**
     *
     * @param type $data
     * @return boolean
     *
     * @since 1.0
     */
    public function save($data) {
        return parent::save($data);
    }

    public function open($invoice_id) {
        $conn = $this->getOdooCnn();
        return $conn->call_workflow($this->odoo_model, 'invoice_open', $invoice_id);
    }

    public function registerPayment($invoice_id, $journal_id, $amount, $type = 'receipt') {
        $conn = $this->getOdooCnn();
        $conn->call('account.invoice', 'nc_register_payment', array((int) $invoice_id), array('journal_id' => (int) $journal_id, 'amount' => (float) $amount, 'type' => $type));
        $invoice = $this->getItem((int) $invoice_id);
        # Register payment for exist instance
        if (!empty($invoice->nc_instance_id)) {
            $order_id = $this->getOrderReference($invoice->id);
            if (!empty($order_id)) {
                $orderModel = NextgCyberHelper::getAdminModel('Order');
                $order = $orderModel->getItem($order_id);
                if (!empty($order->nc_instance_id) && $order->nc_type == 'odoo_addons') {
                    $orderModel->updateInstance($order_id);
                }
            }
        } else {
            # find order id
            $order_id = $this->getOrderReference($invoice->id);
            if (!empty($order_id)) {
                $orderModel = NextgCyberHelper::getAdminModel('Order');
                $order = $orderModel->getItem($order_id);
                if (empty($order->nc_instance_id)) {
                    # create instance
                    # TODO get name from order
                    $subdomain = (!empty($order->nc_instance_subdomain)) ? $order->nc_instance_subdomain : md5(time());
                    $orderModel->createInstance($order_id, $subdomain);
                }
            }
        }
    }

    /**
     * Method to get order id
     * @param type $invoice_id
     * @return boolean
     * @since 1.0
     * @author Daniel.Vu
     */
    protected function getOrderReference($invoice_id) {
        $invoice = $this->getItem((int) $invoice_id);
        $db = $this->getOdooDB();
        $query = $db->getQuery(true);
        $query->select('id');
        $query->from('sale.order');
        $query->where('name = ' . $invoice->reference);
        $db->setQuery($query);
        $result = $db->loadObjectList();
        if (empty($result)) {
            return false;
        }
        return $result[0]->id;
    }

}

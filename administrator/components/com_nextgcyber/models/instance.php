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
JLoader::register('NextgCyberDateTimeHelper', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/datetimehelper.php');
JLoader::register('NextgCyberInstanceHelper', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/instancehelper.php');

/**
 * NextgCyber Model
 */
class NextgCyberModelInstance extends NextgCyberModelBaseAdmin {

    protected $odoo_model = 'nc.instance';

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param       type    The table type to instantiate
     * @param       string  A prefix for the table class name. Optional.
     * @param       array   Backupuration array for model. Optional.
     * @return      QATableBank  A database object
     * @since       3.2
     */
    public function getTable($type = 'Instance', $prefix = 'NextgCyberTable', $backup = array()) {
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
        if (empty($data['id'])) {
            $is_new = true;
        }
        if (parent::save($data)) {
            $id = $this->getState($this->getName() . '.id');
            if (!empty($data['customdomain_id'])) {
                $this->addCustomDomain($id, $data['customdomain_id']);
            }
            if ($is_new) {
                $this->deploy($id);
            }
            return true;
        }
        return false;
    }

    protected function deploy($instance_id) {
        $conn = $this->getOdooCnn();
        return $conn->call_workflow($this->odoo_model, 'instance_deploy', $instance_id);
    }

    /**
     * Method to check current status of instance
     * @param integer $instance_id
     * @return boolean
     * @since 1.4
     */
    public function isReady($instance_id) {
        $conn = $this->getOdooCnn();
        return $conn->call($this->odoo_model, 'action_ready', array($instance_id), array());
    }

    /**
     * Method to get current percenage when install instance
     * @param integer $instance_id
     * @return boolean
     * @since 1.4
     */
    public function getInstallPercentage($instance_id) {
        $conn = $this->getOdooCnn();
        return $conn->call($this->odoo_model, 'action_getInstallPercentage', array($instance_id), array());
    }

    /**
     * Method to get last log of instance
     * @param integer $instance_id
     * @return boolean
     * @since 1.4
     */
    public function getLastLog($instance_id) {
        $conn = $this->getOdooCnn();
        return $conn->call($this->odoo_model, 'action_getLastLog', array($instance_id), array());
    }

    public function getItem($pk = null) {
        $item = parent::getItem($pk);
        return $item;
    }

    /**
     * Method to start odoo instance
     * @param integer $instance_id
     * @return boolean
     * @since 1.0
     * @author Daniel.Vu
     */
    public function start($instance_id) {
        $conn = $this->getOdooCnn();
        if ($conn->call($this->odoo_model, 'action_start', array($instance_id), array())) {
            return true;
        } else {
            $app = JFactory::getApplication();
            $app->enqueueMessage(JText::_('COM_NEXTGCYBER_INSTANCE_CANNOT_START_INSTANCE'), 'error');
            return false;
        }
    }

    /**
     * Method to stop odoo instance
     * @param integer $instance_id
     * @return boolean
     * @since 1.0
     * @author Daniel.Vu
     */
    public function stop($instance_id) {
        $conn = $this->getOdooCnn();
        if ($conn->call($this->odoo_model, 'action_stop', array($instance_id), array())) {
            return true;
        } else {
            $app = JFactory::getApplication();
            $app->enqueueMessage(JText::_('COM_NEXTGCYBER_INSTANCE_CANNOT_STOP_INSTANCE'), 'error');
            return false;
        }
    }

    /**
     * Method to restart odoo instance
     * @param integer $instance_id
     * @return boolean
     * @author Daniel.Vu
     * @since 1.0
     */
    public function restart($instance_id) {
        $conn = $this->getOdooCnn();
        if ($conn->call($this->odoo_model, 'action_restart', array($instance_id), array())) {
            return true;
        } else {
            $app = JFactory::getApplication();
            $app->enqueueMessage(JText::_('COM_NEXTGCYBER_INSTANCE_CANNOT_RESTART_INSTANCE'), 'error');
            return false;
        }
    }

    /**
     * Method to delete odoo instance
     * @param integer $instance_id
     * @return boolean
     * @author Daniel.Vu
     * @since 1.0
     */
    public function revoke($instance_id) {
        $conn = $this->getOdooCnn();
        if ($conn->call($this->odoo_model, 'action_delete', array($instance_id), array())) {
            return true;
        }
        $app = JFactory::getApplication();
        $app->enqueueMessage(JText::_('COM_NEXTGCYBER_INSTANCE_CANNOT_DELETE_INSTANCE'), 'error');
        return false;
    }

    /**
     * Method to redeploy odoo instance
     * @param integer $instance_id
     * @return boolean
     * @author Daniel.Vu
     * @since 1.0
     */
    public function redeploy($instance_id) {
        $conn = $this->getOdooCnn();
        if ($conn->call($this->odoo_model, 'action_redeploy_all', array($instance_id), array())) {
            return true;
        } else {
            $app = JFactory::getApplication();
            $app->enqueueMessage(JText::_('COM_NEXTGCYBER_INSTANCE_CANNOT_REDEPLOY_INSTANCE'), 'error');
            return false;
        }
    }

    /**
     * Method to redeploy proxy
     * @param integer $instance_id
     * @return boolean
     * @author Daniel.Vu
     * @since 1.0
     */
    protected function redeployProxy($instance_id) {
        $conn = $this->getOdooCnn();
        if ($conn->call($this->odoo_model, 'action_redeploy_proxy', array($instance_id), array())) {
            return true;
        } else {
            $app = JFactory::getApplication();
            $app->enqueueMessage(JText::_('COM_NEXTGCYBER_INSTANCE_CANNOT_REDEPLOY_INSTANCE'), 'error');
            return false;
        }
    }

    /**
     * Method to add custom domain to odoo instance
     * @param integer $instance_id
     * @param array $domain_ids
     * @return boolean
     * @author Daniel.Vu
     * @since 1.0
     */
    public function addCustomDomain($instance_id, $domain_ids) {
        if (empty($domain_ids)) {
            return false;
        }

        if (!is_array($domain_ids)) {
            $domain_ids = array($domain_ids);
        }

        $customDomainModel = NextgCyberHelper::getAdminModel('CustomDomain');
        foreach ($domain_ids as $domain_id) {
            $customDomainModel->getState();
            $data = array();
            $data['id'] = $domain_id;
            $data['instance_id'] = $instance_id;
            $customDomainModel->save($data);
            $customDomainModel->setState($customDomainModel->getName() . '.id', null);
        }

//        $conn = $this->getOdooCnn();
//        if ($conn->call($this->odoo_model, 'add_customer_domain', array($instance_id, $domain_ids), array())) {
//            return true;
//        } else {
//            return false;
//        }
    }

    /**
     * Method to create new trial
     * @param string $domain
     * @param array $apps
     * @param integer $customdomain_id
     * @since 1.0
     * @author Daniel.vu
     * @return boolean
     */
    public function createInstance($domain, $partner_id, $apps, $customdomain_id = null, $instance_type = 'trial') {

        $is_trial = ($instance_type == 'trial') ? true : false;

        if (!empty($customdomain_id)) {
            $customDomainModel = NextgCyberHelper::getAdminModel('CustomDomain');
            $customDomain = $customDomainModel->getItem($customdomain_id);
            if (empty($customDomain)) {
                $customdomain_id = null;
            }

            if (!empty($customDomain->instance_id)) {
                $customdomain_id = null;
            }

            if ($customdomain_id) {
                $domain = $customDomain->name;
            }
        }

        if ((empty($domain) && empty($customdomain_id)) || empty($apps)) {
            return false;
        }
        $max_user = 0;
        $max_storage = 0;
        $max_bandwidth = 0;
        $product_ids = array();
        $theme_ids = array();
        foreach ($apps as $app) {
            if ($app['nc_type'] == 'odoo_user') {
                $max_user += $app['quantity'];
            } elseif ($app['nc_type'] == 'odoo_module') {
                $product_ids[] = (int) $app['id'];
            } elseif ($app['nc_type'] == 'odoo_storage') {
                $max_storage += $app['quantity'];
            } elseif ($app['nc_type'] == 'odoo_bandwidth') {
                $max_bandwidth += $app['quantity'];
            }
        }

        $data = array();
        $data['id'] = 0;
        $data['name'] = $domain;
        $data['active'] = 1;
        $data['partner_id'] = (int) $partner_id;

        if ($max_bandwidth < 1) {
            $max_bandwidth = 1;
        }
        $data['max_bandwidth'] = $max_bandwidth;

        if ($max_user < 1) {
            $max_user = 1;
        }
        $data['max_user'] = $max_user;

        if ($max_storage < 1) {
            $max_storage = 1;
        }
        $data['max_storage'] = $max_storage;

        $data['max_backup'] = 0;
        $data['type'] = $instance_type;
        
        $productsModel = NextgCyberHelper::getAdminModel('Products');
        $productsModel->clearState();
        $productsModel->setState('filter.id', $product_ids);
        $productsModel->setState('filter.nc_type', 'odoo_module');
        $productsModel->setState('filter.nc_module_type', 'standard');
        $products = $productsModel->getItems();
        $module_ids = array();
        foreach ($products as $product) {
            if (!empty($product->nc_module_id[0])) {
                $module_ids[] = $product->nc_module_id[0];
            }
        }
        if (!empty($module_ids)) {
            $data['allowed_module_ids'] = array(array(6, 0, $module_ids));
        }
        
        $modulesModel = NextgCyberHelper::getAdminModel('Modules');
        $modulesModel->clearState();
        $modulesModel->setState('filter.product_id', $product_ids);
        $modulesModel->setState('filter.type', 'theme');
        
        $modules = $modulesModel->getItems();
        $nc_module_ids = array();
        foreach ($modules as $module) {
            $nc_module_ids[] = $module->id;
        }
        if (!empty($nc_module_ids)) {
            #$data['custom_module_ids'] = array(array(6, 0, $nc_module_ids));
            $tmp_array = array();
            foreach($nc_module_ids as $nc_module_id){
                $tmp_array[] = array(0, 0, array('module_id' => $nc_module_id));
            }
            $data['custom_module_ids'] = $tmp_array;
        }

        $data['allow_custommodule'] = false;
        $data['allow_ssl'] = false;
        $data['allow_customdomain'] = false;
        $data['allow_use_theme'] = false;
        $data['allow_use_template'] = false;
        $data['customdomain_id'] = $customdomain_id;

        if ($this->save($data)) {
            $instance_id = $this->getState($this->getName() . '.id');
            return $instance_id;
        } else {
            $app = JFactory::getApplication();
            $app->enqueueMessage(JText::_('COM_NEXTGCYBER_INSTANCE_CANNOT_CREATE_TRIAL_INSTANCE'), 'error');
            return false;
        }
    }

    /**
     * Method to create saleorder for instance
     * @param integer $instance_id
     * @param integer $payment_period_id
     * @param string $coupon_code
     * @param boolean $without_tax
     * @return boolean|integer return false if failed or saleorder
     *
     */
    public function upgrade_trial_2_normal($instance_id, $payment_period_id, $coupon_code = '', $without_tax = false) {
        if (empty($instance_id) || empty($payment_period_id)) {
            return false;
        }

        // Remove all draft order exist
        $ordersModel = NextgCyberHelper::getAdminModel('Orders');
        $ordersModel->clearState();
        $ordersModel->setState('filter.state', 'draft');
        $ordersModel->setState('filter.nc_type', 'odoo_instance');
        $ordersModel->setState('filter.nc_instance_id', $instance_id);
        $orders = $ordersModel->getItems();
        if (!empty($orders)) {
            $orderModel = NextgCyberHelper::getAdminModel('Order');
            foreach ($orders as $order) {
                $orderModel->unlink($order->id);
            }
        }

        $conn = $this->getOdooCnn();
        $data['id'] = 0;
        $data['instance_id'] = $instance_id;
        $data['date_order'] = JFactory::getDate()->toSql();
        $data['payment_period_id'] = $payment_period_id;
        $data['generate_mode'] = 'manual';
        $data['coupon_code'] = $coupon_code;
        $data['without_tax'] = $without_tax;
        $id = $conn->create('nc.upgrade.trial.instance.wizard', $data);
        if (empty($id)) {
            return false;
        }
        $order = $conn->call('nc.upgrade.trial.instance.wizard', 'action_upgrade_trial_instance', array($id));
        if (!empty($order)) {
            return $order[0];
        }
        return false;
    }

    /**
     * TODO
     * @param type $instance_id
     * @return boolean
     */
    protected function payment($instance_id) {
        $instance = $this->getItem($instance_id);
        if (!empty($instance) && $instance->type == 'trial') {
            $data = [];
            $data['id'] = $instance->id;
            $data['type'] = 'normal';
            $startdate = JFactory::getDate($instance->publish_up);
            $publish_down = NextgCyberDateTimeHelper::addTime($startdate, '30 day');
            $data['publish_down'] = $publish_down->toSql();
            return $this->save($data);
        }
        return true;
    }

    public function getFullDomain($instance_id) {
        $instance = $this->getItem($instance_id);
        if (empty($instance)) {
            return '';
        }
        return $instance->name . '.' . $instance->based_domain_id_title;
    }

}

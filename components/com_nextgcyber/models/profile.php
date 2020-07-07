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
defined('_JEXEC') or die('Restricted access');
JLoader::register('NextgCyberModelBaseAdmin', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/models/baseadmin.php');
JLoader::register('NextgCyberCustomerHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/customerhelper.php');
JLoader::register('NextgCyberIPHelper', JPATH_COMPONENT . '/helpers/iphelper.php');

/**
 * NextgCyber Model
 */
class NextgCyberModelProfile extends NextgCyberModelBaseAdmin {

    protected $odoo_model = 'res.partner';

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param       type    The table type to instantiate
     * @param       string  A prefix for the table class name. Optional.
     * @param       array   Backupuration array for model. Optional.
     * @return      JTable  A database object
     * @since       3.1
     */
    public function getTable($type = 'Partner', $prefix = 'NextgCyberTable', $backup = array()) {
        return JTable::getInstance($type, $prefix, $backup);
    }

    public function getItem($pk = null) {
        $user = JFactory::getUser();
        $partner_id = NextgCyberCustomerHelper::getPartnerIdByID($user->id);
        return parent::getItem($partner_id);
    }

    /**
     * Method to get the record form.
     *
     * @param   array      $data        Data for the form.
     * @param   boolean    $loadData    True if the form is to load its own data (default case), false if not.
     *
     * @return  mixed  A JForm object on success, false on failure
     * @since   1.0
     */
    public function getForm($data = array(), $loadData = true) {
        $name = $this->getName();
        // Get the form.
        $form = $this->loadForm('com_nextgcyber.' . $name, $name, array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
        $jinput = JFactory::getApplication()->input;

        $id = $jinput->get('id', 0);

        // Determine correct permissions to check.
        if ($this->getState($name . '.id')) {
            $id = $this->getState($name . '.id');
        }
        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     * @since   1.0
     */
    protected function loadFormData() {
        $modelName = $this->getName();
        // Check the session for previously entered form data.
        $app = JFactory::getApplication();
        $data = $app->getUserState('com_nextgcyber.edit.' . $modelName . '.data', array());

        if (empty($data)) {
            $data = $this->getItem();
            // Prime some default values.
        }

        if (!$data) {
            $data = new stdClass();
        }
        $this->preprocessData('com_nextgcyber.' . $modelName, $data);
        return $data;
    }

    public function save($data) {
        $user = JFactory::getUser();
        /* @var $partnerModel NextgCyberModelPartner */
        $partner_id = NextgCyberCustomerHelper::getPartnerIdByID($user->id);
        $data['id'] = $partner_id;
        return parent::save($data);
    }

}

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
class NextgCyberModelCustomDomain extends NextgCyberModelBaseAdmin {

    protected $odoo_model = 'nc.instance.customer.domain';

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param       type    The table type to instantiate
     * @param       string  A prefix for the table class name. Optional.
     * @param       array   Backupuration array for model. Optional.
     * @return      QATableBank  A database object
     * @since       3.2
     */
    public function getTable($type = 'CustomDomain', $prefix = 'NextgCyberTable', $backup = array()) {
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
            $form->setFieldAttribute('active', 'disabled', 'true');

            // Disable fields while saving.
            // The controller has already verified this is an bank you can edit.
            $form->setFieldAttribute('active', 'filter', 'unset');
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

    /**
     * Method to verify customer domain
     * @param integer $domain_id
     * @return boolean
     * @since 1.0
     * @author Daniel.Vu
     */
    public function verify($domain_id) {
        $conn = $this->getOdooCnn();
        if ($conn->call($this->odoo_model, 'action_verify', array($domain_id), array())) {
            return true;
        }
        return false;
    }

    public function isValidDomain($domain_name) {
        if (strripos($domain_name, '.') === false) {
            return false;
        }
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
                && preg_match("/^.{1,253}$/", $domain_name) //overall length check
                && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name) ); //length of each label
    }

    public function isExist($domain, $partner_id) {
        $customDomainsModel = NextgCyberHelper::getAdminModel('CustomDomains');
        $customDomainsModel->clearState();
        $customDomainsModel->setState('filter.name', $domain);
        $customDomainsModel->setState('filter.partner_id', $partner_id);
        $items = $customDomainsModel->getItems();
        if (!empty($items)) {
            return $items[0];
        }
        return false;
    }

}

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
class NextgCyberModelCouponCode extends NextgCyberModelBaseAdmin {

    protected $odoo_model = 'nc.coupon.code';

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param       type    The table type to instantiate
     * @param       string  A prefix for the table class name. Optional.
     * @param       array   Backupuration array for model. Optional.
     * @return      QATableBank  A database object
     * @since       3.2
     */
    public function getTable($type = 'CouponCode', $prefix = 'NextgCyberTable', $backup = array()) {
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
        return true;
    }

    /**
     * Method to validate coupon_code
     * @param string $coupon_code
     * @param integer $order_id
     * @param integer $partner_id
     * @param string $email
     * @return boolean
     * @since 1.0
     * @author Daniel.Vu
     */
    public function validate($coupon_code, $order_id = null, $partner_id = null, $email = null) {
        $conn = $this->getOdooCnn();
        $id = $conn->searchId($this->odoo_model, array(array('name', '=', $coupon_code)));
        if (empty($id)) {
            return false;
        }

        if (empty($order_id)) {
            $order_id = null;
        }

        if (empty($partner_id)) {
            $partner_id = null;
        }

        if (empty($email)) {
            $email = null;
        }

        if ($conn->call($this->odoo_model, 'validate', array($id[0]), array('order_id' => $order_id, 'partner_id' => $partner_id, 'email' => $email))) {
            return $id[0];
        }
        return false;
    }

    public function getItem($pk = null) {
        $item = parent::getItem($pk);
        if (!empty($item->id)) {

        }
        return $item;
    }

}

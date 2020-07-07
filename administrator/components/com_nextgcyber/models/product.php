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
class NextgCyberModelProduct extends NextgCyberModelBaseAdmin {

    protected $odoo_model = 'product.product';

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param       type    The table type to instantiate
     * @param       string  A prefix for the table class name. Optional.
     * @param       array   Backupuration array for model. Optional.
     * @return      QATableBank  A database object
     * @since       3.2
     */
    public function getTable($type = 'Product', $prefix = 'NextgCyberTable', $backup = array()) {
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
        $pk = (!empty($data['id'])) ? $data['id'] : (int) $this->getState($this->getName() . '.id');
        $this->deteleMany2ManyRel($pk);
        if (!empty($data['content_id'])) {
            return $this->addContentId($pk, $data['content_id']);
        }
        return false;
    }

    /**
     * Method to remove all relation of product
     * @param integer $product_id
     * @return boolean
     * @since 1.1
     */
    protected function deteleMany2ManyRel($product_id) {
        $db = JFactory::getDbo();
        $db->transactionStart(true);
        try {
            $query = $db->getQuery(true);
            $query->delete('#__nextgcyber_product_content_rel')
                    ->where('product_id = ' . $product_id);
            $db->setQuery($query);
            $db->execute();
            $db->transactionCommit(true);
            return true;
        } catch (Exception $ex) {
            $db->transactionRollback(true);
            JFactory::getApplication()->enqueueMessage($ex->getMessage(), 'error');
            return false;
        }
    }

    protected function addContentId($product_id, $content_id) {
        if (empty($product_id) || empty($content_id)) {
            return false;
        }

        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->insert('#__nextgcyber_product_content_rel')
                ->columns('product_id, content_id')
                ->values($product_id . ',' . $content_id);
        $db->setQuery($query);
        try {
            $db->execute();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function getItem($pk = null) {
        $item = parent::getItem($pk);
        if (!empty($item->id)) {
            $item->content_id = $this->getContentId($item->id);
        }
        return $item;
    }

    protected function getContentId($product_id) {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select('content_id')->from('#__nextgcyber_product_content_rel')
                ->where('product_id = ' . (int) $product_id);
        $db->setQuery($query);
        return $db->loadResult();
    }

}

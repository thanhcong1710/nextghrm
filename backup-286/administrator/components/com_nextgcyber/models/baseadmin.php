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

require_once JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/helper.php';
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
JLoader::register('NextgCyberOdooDB', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/odoodb.php');

abstract class NextgCyberModelBaseAdmin extends JModelAdmin {

    /**
     * @var        string    The prefix to use with controller messages.
     * @since   1.6
     */
    protected $text_prefix = 'COM_NEXTGCYBER';
    protected $odoo_db = null;
    protected $conn = null;

    /**
     * The type alias for this content type (for example, 'com_nextgcyber.article').
     *
     * @var      string
     * @since    3.2
     */
    public $typeAlias = 'com_nextgcyber.';

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->setTextPrefix();
        $this->setTypeAlias();
    }

    /**
     * Method to get odoo connect
     * @return NextgCyberOdooConnector
     */
    protected function getOdooCnn() {
        if (empty($this->odoo_db)) {
            $this->odoo_db = new NextgCyberOdooDB();
        }
        if (empty($this->conn)) {
            $this->conn = $this->odoo_db->getConn();
        }
        return $this->conn;
    }

    protected function getOdooDB() {
        if (empty($this->odoo_db)) {
            $this->odoo_db = new NextgCyberOdooDB();
        }
        return $this->odoo_db;
    }

    /**
     * Method to change the title & alias.
     *
     * @param   string   $alias        The alias.
     * @param   string   $title        The title.
     *
     * @return	array  Contains the modified title and alias.
     *
     * @since	1.0
     */
    protected function generateNewTitle($alias, $title, $tmp = '') {
        // Alter the title & alias
        $table = $this->getTable();
        while ($table->load(array('alias' => $alias))) {
            $title = JString::increment($title);
            $alias = JString::increment($alias, 'dash');
        }

        return array($title, $alias);
    }

    /**
     * Method to test whether a record can be deleted.
     *
     * @param   object    $record    A record object.
     *
     * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
     * @since   3.0
     */
    protected function canDelete($record) {
        $modelName = $this->getName();
        // check user's authority
        if (!empty($record->id)) {
            $user = JFactory::getUser();
            return $user->authorise('core.delete', 'com_nextgcyber.' . $modelName . '.' . (int) $record->id);
        }
        return false;
    }

    /**
     * @return void
     */
    protected function setTypeAlias() {
        $this->typeAlias .= $this->getName();
    }

    /**
     * @return void
     */
    protected function setTextPrefix() {
        $this->text_prefix .= '_' . strtoupper($this->getName());
    }

    /**
     * Method to test whether a record can have its state edited.
     *
     * @param   object    $record    A record object.
     *
     * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
     * @since   3.0
     */
    protected function canEditState($record) {
        $user = JFactory::getUser();
        $modelName = $this->getName();
        // Check for existing article.
        if (!empty($record->id)) {
            return $user->authorise('core.edit.state', 'com_nextgcyber.' . $modelName . '.' . (int) $record->id);
        }
        // New item, so check against the category (when applicable).
        elseif (isset($record->catid) && !empty($record->catid)) {
            return $user->authorise('core.edit.state', 'com_nextgcyber.category.' . (int) $record->catid);
        }

        // Default to component settings if neither article nor category known.
        else {
            return parent::canEditState('com_nextgcyber');
        }
    }

    /**
     * Method to save the form data.
     *
     * @param   array  $data  The form data.
     *
     * @return  boolean  True on success, False on error.
     *
     * @since   3.0
     */
    public function save($data) {
        $data = NextgCyberHelper::prepareSubmittedData($data);
        if (isset($data['images']) && is_array($data['images'])) {
            $registry = new Joomla\Registry\Registry($data['images']);
            $data['images'] = (string) $registry;
        }

        if (isset($data['urls']) && is_array($data['urls'])) {

            foreach ($data['urls'] as $i => $url) {
                if ($url != false && ($i == 'urla' || $i == 'urlb' || $i == 'urlc')) {
                    $data['urls'][$i] = JStringPunycode::urlToPunycode($url);
                }
            }
            $registry = new Joomla\Registry\Registry($data['urls']);
            $data['urls'] = (string) $registry;
        }

        return parent::save($data);
    }

    /**
     * Prepare and sanitise the table data prior to saving.
     *
     * @param   JTable    A JTable object.
     *
     * @return  void
     * @since   3.0
     */
    protected function prepareTable($table) {
        // Set the publish date to now
        $db = $this->getDbo();
        if (isset($table->publish_up) && $table->published == 1 && (int) $table->publish_up == 0) {
            $table->publish_up = JFactory::getDate()->toSql();
        }

        if (isset($table->publish_down) && $table->published == 1 && intval($table->publish_down) == 0) {
            $table->publish_down = $db->getNullDate();
        }

        // Increment the content version number.
        if (isset($table->version)) {
            $table->version++;
        }

        // Reorder the item within the category so the new item is first
        if (empty($table->id)) {
            if (isset($table->catid)) {
                $sql = 'catid = ' . (int) $table->catid . ' AND published >= 0';
            } else {
                $sql = 'published >= 0';
            }
            if (property_exists($table, 'ordering')) {
                $table->reorder($sql);
            }
        }
    }

    /**
     * Method to get the record form.
     *
     * @param   array      $data        Data for the form.
     * @param   boolean    $loadData    True if the form is to load its own data (default case), false if not.
     *
     * @return  mixed  A JForm object on success, false on failure
     * @since   3.0
     */
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
            // Existing record. Can only edit in selected categories.
            $form->setFieldAttribute('catid', 'action', 'core.edit');
            // Existing record. Can only edit own articles in selected categories.
            $form->setFieldAttribute('catid', 'action', 'core.edit.own');
        } else {
            // New record. Can only create in selected categories.
            $form->setFieldAttribute('catid', 'action', 'core.create');
        }

        $user = JFactory::getUser();

        // Check for existing article.
        // Modify the form based on Edit State access controls.
        if ($id != 0 && (!$user->authorise('core.edit.state', 'com_nextgcyber.' . $name . '.' . (int) $id)) || ($id == 0 && !$user->authorise('core.edit.state', 'com_nextgcyber'))
        ) {
            // Disable fields for display.
            $form->setFieldAttribute('featured', 'disabled', 'true');
            $form->setFieldAttribute('ordering', 'disabled', 'true');
            $form->setFieldAttribute('publish_up', 'disabled', 'true');
            $form->setFieldAttribute('publish_down', 'disabled', 'true');
            $form->setFieldAttribute('published', 'disabled', 'true');

            // Disable fields while saving.
            // The controller has already verified this is an article you can edit.
            $form->setFieldAttribute('featured', 'filter', 'unset');
            $form->setFieldAttribute('ordering', 'filter', 'unset');
            $form->setFieldAttribute('publish_up', 'filter', 'unset');
            $form->setFieldAttribute('publish_down', 'filter', 'unset');
            $form->setFieldAttribute('published', 'filter', 'unset');
        }

        // Prevent messing with article language and category when editing existing article with associations
        $app = JFactory::getApplication();
        $assoc = JLanguageAssociations::isEnabled();

        if ($app->isSite() && $assoc && $this->getState($name . '.id')) {
            $form->setFieldAttribute('language', 'readonly', 'true');
            $form->setFieldAttribute('language', 'filter', 'unset');
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     * @since   3.0
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

        $this->preprocessData('com_nextgcyber.' . $modelName, $data);

        return $data;
    }

    /**
     * Custom clean the cache of com_nextgcyber and its related modules
     *
     * @since   3.0
     */
    protected function cleanCache($group = null, $client_id = 0) {
        parent::cleanCache('com_nextgcyber');
    }

    /**
     * Method return full name of this class
     * @return string
     * @since 1.0.0
     */
    public function getFullName() {
        return get_class($this);
    }

    /**
     * Method to get a single record.
     *
     * @param   integer  $pk  The id of the primary key.
     *
     * @return  mixed    Object on success, false on failure.
     *
     * @since   12.2
     */
    public function getItem($pk = null) {
        $pk = (!empty($pk)) ? (int) $pk : (int) $this->getState($this->getName() . '.id');
        $table = $this->getTable();

        if ($pk > 0) {
            // Attempt to load the row.
            $return = $table->load($pk);

            // Check for a table object error.
            if ($return === false && $table->getError()) {
                $this->setError($table->getError());

                return false;
            }
        }

        // Convert to the JObject before adding other data.
        $properties = $table->getProperties(1);
        $item = JArrayHelper::toObject($properties, 'JObject');
        return $item;
    }

    public function unlink($id) {
        $conn = $this->getOdooCnn();
        if ($conn->call($this->odoo_model, 'unlink', array($id), array())) {
            return true;
        }
        return false;
    }

}

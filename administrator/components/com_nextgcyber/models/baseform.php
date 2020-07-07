<?php

/**
 * @package nextgcyber NextgCyber for Joomla
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://nextgcyber.com
 * @author Daniel.Vu
 */
// No direct access

defined('_JEXEC') or die('Restricted access');

/**
 * Base model from which form-related models extend
 *
 * @author Daniel.Vu
 */
abstract class NextgCyberModelBaseForm extends JModelForm {

    /**
     * @var        string    The prefix to use with controller messages.
     * @since   3.0
     */
    protected $text_prefix = 'COM_NEXTGCYBER_';

    public function __construct($config = array()) {
        $model_name = $this->getName();
        $this->text_prefix .= strtoupper($model_name);
        parent::__construct($config);
    }

    /**
     * Get an item from the database table
     *
     * @param    integer    The id of the item to get.
     * @return JObject|boolean The whole corressponding row with all field data in JObject format on success, otherwise return false
     */
    public function getItem($pk = null) {
        $name = $this->getName();
        $pk = (!empty($pk)) ? $pk : (int) $this->getState($name . '.id');
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

        if (property_exists($item, 'params')) {
            $registry = new \Joomla\Registry\Registry();
            $registry->loadString($item->params);
            $item->params = $registry->toArray();
        }

        return $item;
    }

    /**
     * Method to save the form data.
     *
     * @param array $data   The form data
     * @return boolean  True on success.
     */
    public function save($data) {
        $name = $this->getName();
        $id = (!empty($data['id'])) ? $data['id'] : (int) $this->getState($name . '.id');
        $app = JFactory::getApplication();
        NextgCyberHelper::prepareSubmittedData($data);
        // Get a row instance.
        $table = $this->getTable();

        // Load the row if saving an existing item.
        if ($id > 0) {
            $table->load($id);
        }

        // Bind the data.
        if (!$table->bind($data)) {
            $app->enqueueMessage($table->getError(), 'error');
            return false;
        }

        // Check the data.
        if (!$table->check()) {
            $app->enqueueMessage($table->getError(), 'error');
            return false;
        }

        // Store the data.
        if (!$table->store()) {
            $app->enqueueMessage($table->getError(), 'error');
            return false;
        }

        $this->setState($name . '.id', $table->id);

        // Clean the cache
        $this->cleanCache();

        return true;
    }

    /**
     * Method to test whether a record can be deleted.
     *
     * @param   object  $record  A record object.
     *
     * @return  boolean  True if allowed to delete the record. Defaults to the permission for the component.
     *
     * @since   3.0
     */
    protected function canDelete($record) {
        $user = JFactory::getUser();
        return $user->authorise('core.delete', $this->option);
    }

    /**
     * Method to test whether a record can be deleted.
     *
     * @param   object  $record  A record object.
     *
     * @return  boolean  True if allowed to change the state of the record. Defaults to the permission for the component.
     *
     * @since   3.0
     */
    protected function canEditState($record) {
        $user = JFactory::getUser();
        return $user->authorise('core.edit.state', $this->option);
    }

    /**
     * Method to delete groups.
     *
     * @param array $itemIds  An array of item ids.
     * @return boolean Returns true on success, false on failure.
     */
    public function delete($itemIds) {
        // Sanitize the ids.
        $itemIds = (array) $itemIds;
        JArrayHelper::toInteger($itemIds);

        // Get a group row instance.
        $table = $this->getTable();

        // Iterate the items to delete each one.
        foreach ($itemIds as $itemId) {
            if ($this->canDelete($itemId)) {

                if (!$table->delete($itemId)) {
                    JFactory::getApplication()->enqueueMessage($table->getError(), 'error');
                    return false;
                }
            }
        }

        // Clean the cache
        $this->cleanCache();

        return true;
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion. DON'T do that, pls
     *
     * @since   1.0
     */
    protected function populateState() {
        // Load the User state.
        $app = JFactory::getApplication();
        $name = $this->getName();
        // Load the User state.
        $id = $app->input->getInt('id');
        $this->setState($name . '.id', $id);

        // Load the parameters.
        // $params = JComponentHelper::getParams($this->option);
        // $this->setState('params', $params);
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
        // Get the model name in lowercase
        $name = $this->getName();

        // Get the form.
        $form = $this->loadForm('com_nextgcyber.' . $name, $name, array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
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
        // Get the model name in lowercase
        $name = $this->getName();

        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_nextgcyber.edit.' . $name . '.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        $this->preprocessData('com_nextgcyber.' . $name, $data);

        return $data;
    }

    /**
     * Method to change the published state of one or more records.
     *
     * @param   array    &$pks   A list of the primary keys to change.
     * @param   integer  $value  The value of the published state.
     *
     * @return  boolean  True on success.
     *
     * @since   12.2
     */
    public function publish(&$pks, $value = 1) {
        $dispatcher = JEventDispatcher::getInstance();
        $user = JFactory::getUser();
        $table = $this->getTable();
        $pks = (array) $pks;

        // Include the content plugins for the change of state event.
        JPluginHelper::importPlugin('content');

        // Access checks.
        foreach ($pks as $i => $pk) {
            $table->reset();

            if ($table->load($pk)) {
                if (!$this->canEditState($table)) {
                    // Prune items that you can't change.
                    unset($pks[$i]);
                    JLog::add(JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'), JLog::WARNING, 'jerror');

                    return false;
                }
            }
        }

        // Attempt to change the state of the records.
        if (!$table->publish($pks, $value, $user->get('id'))) {
            $this->setError($table->getError());

            return false;
        }

        $context = $this->option . '.' . $this->name;

        // Trigger the onContentChangeState event.
        $result = $dispatcher->trigger($this->event_change_state, array($context, $pks, $value));

        if (in_array(false, $result, true)) {
            $this->setError($table->getError());

            return false;
        }

        // Clear the component's cache
        $this->cleanCache();

        return true;
    }

}

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
JLoader::register('NextgCyberOdooDB', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/odoodb.php');
JLoader::register('NextgCyberCustomerHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/profilehelper.php');

class NextgCyberModelBaseItem extends JModelItem {

    /**
     * Model context string.
     *
     * @var    string
     * @since  12.2
     */
    protected $_context = 'com_nextgcyber';
    protected $odoo_model = '';
    protected $odoo_db = null;

    public function __construct($config = array()) {
        $this->odoo_db = new NextgCyberOdooDB();
        parent::__construct($config);
    }

    protected function getOdooDB() {
        return $this->odoo_db;
    }

    /**
     * Method to get article data.
     *
     * @param   integer  $pk  The id of the article.
     *
     * @return  mixed  Menu item data object on success, false on failure.
     */
    public function getItem($pk = null) {
        $app = JFactory::getApplication();
        $pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
        $user = JFactory::getUser();
        if ($this->_item === null) {
            $this->_item = array();
        }

        if (!isset($this->_item[$pk])) {
            $db = $this->getOdooDB();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from($this->odoo_model);

            // Only for this user
            $partner_id = NextgCyberCustomerHelper::getPartnerIdByID($user->get('id'));
            $query->where('partner_id.id = ' . (int) $partner_id);
            $query->where('id = ' . (int) $pk);
            $db->setQuery($query);
            $data = $db->loadObject();

            if (empty($data)) {
                return false;
            }
            $this->_item[$pk] = $data;
        }
        return $this->_item[$pk];
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since   1.6
     *
     * @return void
     */
    protected function populateState() {
        $app = JFactory::getApplication('site');
        // Load state from the request.
        $pk = $app->input->getInt('id');
        $this->setState($this->getName() . '.id', $pk);

        // Load the parameters.
        $params = $app->getParams();
        $this->setState('params', $params);
    }

}

<?php

/**
 * @package pkg_nextgcyber
 * @subpackage  com_nextgcyber
 * @copyright Copyright (C) 2015 NextgCyber . All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://nextgerp.com
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;

/**
 * This models supports retrieving lists of article plans.
 *
 * @package pkg_nextgcyber
 * @subpackage  com_nextgcyber
 * @since       1.0
 */
JLoader::register('NextgCyberIPHelper', JPATH_COMPONENT . '/helpers/iphelper.php');
JLoader::register('NextgCyberModelBaseList', JPATH_COMPONENT_ADMINISTRATOR . '/models/baselist.php');
JLoader::register('NextgCyberCustomerHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/profilehelper.php');

class NextgCyberModelInvoices extends NextgCyberModelBaseList {

    protected $odoo_model = 'account.invoice';
    protected $context = 'com_nextgcyber.account.invoice';

    /**
     *
     * @var type
     */
    private $_items = null;

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since   1.0
     */
    protected function populateState($ordering = null, $direction = null) {
        parent::populateState($ordering, $direction);
        $input = JFactory::getApplication()->input;
        $app = JFactory::getApplication();

        // Initialise variables.
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $order = $this->getUserStateFromRequest($this->context . '.list.ordering', 'filter_order');
        if ($order) {
            $this->setState('list.ordering', $order);
        }

        // List state information
        $start = $input->getUInt('limitstart', 0);
        $limitstart = $input->getUInt('start', $start);
        $this->setState('list.start', $limitstart);

        $limit = $app->getUserStateFromRequest($this->context . '.list.limit', 'limit', 10);
        $this->setState('list.limit', $limit);

        $params = $app->getParams();
        $this->setState('params', $params);
    }

    public function clearState() {
        $this->getState();
        $this->setState('filter.published', null);
        $this->setState('filter.search', null);
        $this->setState('filter.id', null);
        $this->setState('filter.instance_id', null);
        $this->setState('filter.state', null);
        $this->setState('list.limit', 0);
        $this->setState('list.start', 0);
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param   string  $id	A prefix for the store id.
     *
     * @return  string  A store id.
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.published');
        return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return  JDatabaseQuery
     *
     * @since   1.0
     */
    protected function getListQuery() {
        $user = JFactory::getUser();
        $partner_id = NextgCyberCustomerHelper::getPartnerIdByID($user->id);
        // Create a new query object.
        $db = $this->getDbo();
        $query = $this->getOdooQuery(true);
        // Select the required fields from the table.
        $query->select('id, number, partner_id, state, reference, date_due, date_invoice, amount_untaxed, amount_tax, amount_total');
        $query->from($this->odoo_model);
        $query->where(array('partner_id.id', '=', $partner_id));
        // Filter by active state
        $published = $this->getState('filter.published');
        if ($published) {
            $query->where('active = ' . filter_var($published, FILTER_VALIDATE_BOOLEAN));
        }

        $instance_id = $this->getState('filter.instance_id');
        if ($instance_id) {
            $query->where('nc_instance_id.id = ' . (int) $instance_id);
        }

        $state = $this->getState('filter.state');
        if ($state) {
            $query->where('state = ' . $state);
        }

        // Filter by search in title.
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('id = ' . (int) substr($search, 3));
            } elseif (stripos($search, 'name:') === 0) {
                $substr = substr($search, 5);
                $query->where('number = ' . $substr);
            } else {
                $query->where('number like ' . $search);
            }
        }

        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering', 'id');
        $orderDirn = $this->state->get('list.direction', 'desc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));
        return $query;
    }

    /**
     * Method to get a list of articles.
     *
     * @return  mixed  An array of objects on success, false on failure.
     */
    public function getItems() {
        if (!empty($this->_items)) {
            return $this->_items;
        }
        $items = parent::getItems();
        $invoiceModel = JModelLegacy::getInstance('Invoice', 'NextgCyberModel');
        foreach ($items as &$item) {
            $item->invoicelines = $invoiceModel->getInvoiceLines($item->id);
        }
        unset($item);
        $this->_items = $items;
        return $this->_items;
    }

}

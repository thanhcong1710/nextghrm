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
JLoader::register('NextgCyberOdooDB', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/odoodb.php');

class NextgCyberModelBaseList extends JModelList {

    protected $odoo_db = null;

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'published', 'a.published',
                'search',
            );
        }
        $this->odoo_db = new NextgCyberOdooDB();
        parent::__construct($config);
    }

    protected function getOdooDB() {
        return $this->odoo_db;
    }

    public function clearState() {
        $this->getState();
        $this->setState('filter.published', null);
        $this->setState('filter.search', null);
        $this->setState('list.limit', 0);
        $this->setState('list.start', 0);
    }

    protected function populateState($ordering = 'id', $direction = 'desc') {
        // Initialise variables.
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
        $this->setState('filter.published', $published);

        // List state information.
        parent::populateState($ordering, $direction);
    }

    protected function getStoreId($id = '') {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.published');
        return parent::getStoreId($id);
    }

    protected function getOdooQuery($new = false) {
        return $this->odoo_db->getQuery($new);
    }

    protected function getListQuery() {
        return false;
    }

    /**
     * Gets an array of objects from the results of database query.
     *
     * @param   string   $query       The query.
     * @param   integer  $limitstart  Offset.
     * @param   integer  $limit       The number of records.
     *
     * @return  array  An array of results.
     *
     * @since   12.2
     * @throws  RuntimeException
     */
    protected function _getList($query, $limitstart = 0, $limit = 0) {
        $this->odoo_db->setQuery($query, $limitstart, $limit);
        $result = $this->odoo_db->loadObjectList();
        return $result;
    }

    public function getItems() {
        // Get a storage key.
        $store = $this->getStoreId();

        // Try to load the data from internal storage.
        if (isset($this->cache[$store])) {
            return $this->cache[$store];
        }

        // Load the list items.
        $query = $this->_getListQuery();

        try {
            $items = $this->_getList($query, $this->getStart(), $this->getState('list.limit'));
        } catch (RuntimeException $e) {
            $this->setError($e->getMessage());

            return false;
        }

        // Add the items to the internal cache.
        $this->cache[$store] = $items;

        return $this->cache[$store];
    }

    /**
     * Returns a record count for the query.
     *
     * @param   JDatabaseQuery|string  $query  The query.
     *
     * @return  integer  Number of rows for query.
     *
     * @since   12.2
     */
    protected function _getListCount($query) {
        return (int) $this->odoo_db->count($query);
    }

}

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
JLoader::register('NextgCyberModelBaseList', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/models/baselist.php');

class NextgCyberModelModules extends NextgCyberModelBaseList {

    protected $odoo_model = 'nc.module';

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'published', 'a.published',
                'search',
            );
        }

        parent::__construct($config);
    }

    public function clearState() {
        $this->getState();
        $this->setState('filter.id', null);
        $this->setState('filter.published', null);
        $this->setState('filter.search', null);
        $this->setState('filter.language', null);
        $this->setState('filter.product_id', null);
        $this->setState('filter.type', null);
        $this->setState('list.limit', 0);
        $this->setState('list.start', 0);
    }

    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
        $this->setState('filter.published', $published);

        $language = $this->getUserStateFromRequest($this->context . '.filter.language', 'filter_language', '');
        $this->setState('filter.language', $language);

        // List state information.
        parent::populateState('id', 'asc');
    }

    protected function getStoreId($id = '') {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.id');
        $id .= ':' . $this->getState('filter.published');
        $id .= ':' . $this->getState('filter.language');
        $id .= ':' . $this->getState('filter.product_id');
        $id .= ':' . $this->getState('filter.type');
        return parent::getStoreId($id);
    }

    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();

        $query = $this->getOdooQuery(true);
        // Select the required fields from the table.
        $query->select('id, name, type');
        $query->from($this->odoo_model);
        

        $ids = $this->getState('filter.id');
        if (is_array($ids)) {
            $query->where(array('id', 'in', $ids));
        } elseif (is_numeric($ids)) {
            $query->where(array('id', '=', $ids));
        }
        
        $query->where(array('state', '=', 'confirm'));
        
        $products = $this->getState('filter.product_id');
        if (is_array($products)) {
            $query->where(array('product_id', 'in', $products));
        } elseif (is_numeric($products)) {
            $query->where(array('product_id', '=', $products));
        }
        
        $type = $this->getState('filter.type');
        if ($type) {
            $query->where(array('type', '=', $type));
        }

        // Filter by search in title.
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('id = ' . (int) substr($search, 3));
            } elseif (stripos($search, 'name:') === 0) {
                $substr = substr($search, 5);
                $query->where('name = ' . $substr);
            } else {
                $query->where('name like ' . $search);
            }
        }

        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering', 'id');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));
        return $query;
    }

    public function getItems() {
        return parent::getItems();
    }

}

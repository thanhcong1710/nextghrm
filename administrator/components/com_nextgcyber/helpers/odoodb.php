<?php

/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;
JLoader::register('NextgCyberHelper', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/helper.php');
JLoader::register('NextgCyberOdooConnector', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/odooconnector.php');
JLoader::register('NextgCyberOdooQuery', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/odooquery.php');

class NextgCyberOdooDB {

    protected $_conn = null;
    protected $_query = null;
    protected $_results = null;

    public function __construct() {
        $host = NextgCyberHelper::getParam('odoo_host');
        $db_name = NextgCyberHelper::getParam('odoo_db');
        $username = NextgCyberHelper::getParam('odoo_user');
        $password = NextgCyberHelper::getParam('odoo_password');
        $this->_conn = new NextgCyberOdooConnector($host, $db_name, $username, $password);
    }

    public function getConn() {
        return $this->_conn;
    }

    /**
     * Method to get NextgCyberOdooQuery instance
     * @param boolean $new
     * @return NextgCyberOdooQuery
     * @since 1.0
     * @author Daniel.Vu
     */
    public function getQuery($new = false) {
        if (empty($this->_query)) {
            $this->_query = new NextgCyberOdooQuery();
        }
        if ($new) {
            $this->_query->clear();
        }
        return $this->_query;
    }

    /**
     * Method to execute query
     * @param NextgCyberOdooQuery $query
     * @param integer $offset
     * @param integer $limit
     * @return boolean
     * @since 1.0
     * @author Daniel.Vu
     */
    public function setQuery($query, $offset = 0, $limit = 0) {
        if (!$query instanceof NextgCyberOdooQuery) {
            return false;
        }
        $select = $query->getSelect();
        $from = $query->getFrom();
        $where = $query->getWhere();
        $order = $query->getOrder();
        if (empty($select) || empty($from)) {
            return false;
        }

        if ($select == '*') {
            $fields = array();
        } else {
            $fields = $select;
        }

        $order = implode(',', $order);
        $this->_results = $this->_conn->searchData($from, $where, $fields, $offset, $limit, $order);
        return true;
    }

    /**
     * Method to count all data row
     * @param NextgCyberOdooQuery $query
     * @return integer|boolean
     * @since 1.0
     * @author Daniel.vu
     */
    public function count($query) {
        $select = $query->getSelect();
        $from = $query->getFrom();
        $where = $query->getWhere();
        if (empty($select) || empty($from)) {
            return false;
        }
        return $this->_conn->search_count($from, $where);
    }

    /**
     * Method to format result
     * @return array
     * @since 1.0
     * @author Daniel.Vu
     */
    public function loadObjectList() {
        if (!empty($this->_results)) {
            $items = array();
            foreach ($this->_results as $value) {
                $items[] = (object) $value;
            }
            return $items;
        }
        return array();
    }

    /**
     * Method to load only first object
     * @return object
     * @since 1.0
     * @author Daniel.Vu
     */
    public function loadObject() {
        if (!empty($this->_results)) {
            $item = (object) $this->_results[0];
            return $item;
        }
        return false;
    }

}

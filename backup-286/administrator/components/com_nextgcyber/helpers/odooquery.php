<?php

/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;
JLoader::register('NextgCyberOdooConnector', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/odooconnector.php');

class NextgCyberOdooQuery {

    protected $_db = null;
    protected $_select = array();
    protected $_where = array();
    protected $_from = null;
    protected $_order = array();

    public function __construct() {
        $this->_db = JFactory::getDbo();
    }

    protected function _get($attr) {
        if (isset($this->$attr)) {
            return $this->$attr;
        }
        return false;
    }

    public function getSelect() {
        return $this->_select;
    }

    public function getWhere() {
        return $this->_where;
    }

    public function getFrom() {
        return $this->_from;
    }

    public function getOrder() {
        return $this->_order;
    }

    /**
     *
     * @param string $columns i.e. title,name,type
     */
    public function select($columns) {
        if ($this->_select == '*') {
            return;
        }

        if ($columns == '*') {
            $this->_select = $columns;
        } else {
            $column = explode(',', $columns);
            foreach ($column as $key => &$value) {
                $value = trim($value);
            }
            unset($value);
            $this->_select = array_merge($this->_select, $column);
        }
    }

    /**
     *
     * @param array $condition 'id = 2'
     */
    public function where($condition) {
        if (is_array($condition)) {
            $this->_where[] = $condition;
        } elseif (is_string($condition)) {
            $item = explode(' ', $condition);
            $this->_where[] = $item;
        }
    }

    public function from($from) {
        $this->_from = $from;
    }

    public function order($order) {
        $this->_order[] = $order;
    }

    public function clear() {
        $this->_select = array();
        $this->_from = null;
        $this->_where = array();
        $this->_order = array();
    }

}

<?php

/**
 * @package     pkg_nextgcyber
 * @subpackage  com_nextgcyber
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JLoader::register('NextgCyberOdooDB', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/odoodb.php');

/**
 * @package     pkg_nextgcyber
 * @subpackage  com_contact
 */
class NextgCyberTableBase {

    protected $object_name = "";
    protected $odoo_db = null;
    protected $conn = null;
    protected $properties = array();
    protected $fields = array();
    protected $fields_type = array();
    protected $toUpdate = array();

    public function __construct() {
        $this->odoo_db = new NextgCyberOdooDB();
        $this->conn = $this->odoo_db->getConn();
        $this->fields = $this->getFields();
    }

    public function getKeyName() {
        return 'id';
    }

    public function load($pk) {
        $item = $this->conn->read($this->object_name, $pk, $this->fields);
        if (empty($item)) {
            return false;
        }
        $item = $item[0];
        $this->bind($item);
        return true;
    }

    /**
     * Method to reformat data before save
     * @param string $key
     * @param string $value
     * @return mixed
     * @since 1.0
     * @author Daniel.Vu
     */
    protected function formatValue($key, $value) {
        switch ($this->fields_type[$key]) {
            case 'char':
                $value = (string) $value;
                break;
            case 'boolean':
                $value = (bool) $value;
                $value = ($value) ? 1 : 0;
                break;
            case 'integer':
                $value = (int) $value;
                break;
            case 'datetime':
                $value = ($value) ? $value : false;
                break;
            case 'one2many':
                $value = $value;
                break;
            default:

                break;
        }
        return $value;
    }

    public function store() {
        $data = [];
        foreach ($this->fields as $field) {
            if (in_array($field, $this->toUpdate)) {
                $data[$field] = $this->formatValue($field, $this->$field);
            }
        }
        if (!empty($this->id)) {
            return $this->conn->update($this->object_name, $this->id, $data);
        } else {
            $id = $this->conn->create($this->object_name, $data);
            $this->id = $id;
            return $id;
        }
    }

    public function bind($sources) {
        $this->toUpdate = array();
        foreach ($sources as $key => $value) {
            if (in_array($key, $this->fields)) {
                if (is_array($value) && empty($value)) {
                    continue;
                }
                $this->toUpdate[] = $key;
                $this->$key = $this->formatValue($key, $value);
            }
        }
        return true;
    }

    public function getError() {
        return false;
    }

    public function check() {
        return true;
    }

    /**
     * Method to get all fields of object
     * @since 1.0
     * @author Daniel.Vu
     * @return array
     *
     */
    protected function getFields() {
        if (empty($this->fields)) {
            $fields = $this->conn->getFields($this->object_name, $attributes = array('type', 'string'));
            foreach ($fields as $key => $value) {
                $this->fields[] = $key;
                $this->fields_type[$key] = $value['type'];
            }
        }
        return $this->fields;
    }

    public function getProperties() {
        $property = array();
        foreach ($this->fields as $field) {
            $value = (isset($this->$field)) ? $this->formatValue($field, $this->$field) : null;
            switch ($this->fields_type[$field]) {
                case 'many2one':
                    $field_title = $field . '_title';
                    $property[$field_title] = $value[1];
                    $value = (isset($value[0])) ? $value[0] : $value;
                    break;

                default:
                    break;
            }
            $property[$field] = $value;
        }
        return $property;
    }

}

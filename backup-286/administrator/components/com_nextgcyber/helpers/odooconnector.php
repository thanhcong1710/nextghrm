<?php

/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;
JLoader::register('ripcord', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/libraries/ripcord-1.1/ripcord.php');

//require_once JPATH_ADMINISTRATOR . '/components/com_nextgcyber/libraries/ripcord-1.1/ripcord.php';

class NextgCyberOdooConnector {

    protected $host = null;
    protected $username = null;
    protected $password = null;
    protected $common = null;
    protected $db_name = null;
    protected $uid = null;
    protected $object = null;
    protected $report = null;

    public function __construct($host, $db_name, $username, $password) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->db_name = $db_name;
        $this->common = $this->getCommon();
        $this->uid = $this->getUID();
        $this->object = $this->getObject();
        $this->report = $this->getReport();
    }

    /**
     * Method to get commmon client
     * @return Ripcord Client
     * @since 1.0
     * @author Daniel.Vu
     */
    private function getCommon() {
        if (empty($this->common)) {
            $this->common = ripcord::client("$this->host/xmlrpc/2/common");
        }
        return $this->common;
    }

    /**
     * Method to get report client
     * @return Ripcord Client
     * @since 1.0
     * @author Daniel.Vu
     */
    private function getReport() {
        if (empty($this->report)) {
            $this->report = ripcord::client("$this->host/xmlrpc/2/report");
        }
        return $this->report;
    }

    /**
     * Method to get user id
     * @return Integer|Boolean
     * @since 1.0
     * @author Daniel.Vu
     */
    private function getUID() {
        $uid = $this->common->authenticate($this->db_name, $this->username, $this->password, array());
        return $uid;
    }

    /**
     * Method to get object client
     * @return Ripcord Client
     * @since 1.0
     * @author Daniel.Vu
     */
    private function getObject() {
        if (empty($this->object)) {
            $this->object = ripcord::client("$this->host/xmlrpc/2/object");
        }
        return $this->object;
    }

    /**
     * Method to execute odoo command
     * @param string $model_name Model name. i.e. res.partner
     * @param string $method_name Method name. i.e. read
     * @param array $params
     * @param array $optional
     * @return mixed
     * @since 1.0
     * @author Daniel.Vu
     */
    private function execute_kw($model_name, $method_name, $params, $optional = array()) {
        $response = $this->object->execute_kw($this->db_name, $this->uid, $this->password, $model_name, $method_name
                , $params
                , $optional
        );
        if (isset($response['faultCode'])) {
            $app = JFactory::getApplication();
            $user = JFactory::getUser();
            $isroot = $user->get('isRoot');
            if ($isroot) {
                $app->enqueueMessage('CODE: ' . $response['faultCode'] . ' - ' . $response['faultString'], 'error');
            }

            return false;
        }
        return $response;
    }

    /**
     * Method allow call odoo method
     * @param string $model_name
     * @param string $method_name
     * @param array $params
     * @param array $optional
     * @return boolean
     * @since 1.0
     * @author Daniel.Vu
     */
    public function call($model_name, $method_name, $params, $optional = array()) {
        return $this->execute_kw($model_name, $method_name, array($params), $optional);
    }

    /**
     * Method to search item id
     * @param string $model_name
     * @param array $conditions
     * @param integer $offset
     * @param integer $limit
     *
     * $conditions = [
     *  ['is_company', '=', true],
     *  ['customer', '=', true]
     * ]
     * @return type
     * @since 1.0
     * @return array|Boolean list id of item or False if failed
     * @author Daniel.Vu
     */
    public function searchId($model_name, $conditions, $offset = 0, $limit = 0) {
        return $this->execute_kw($model_name, 'search', array($conditions), array('offset' => $offset, 'limit' => $limit));
    }

    /**
     * Method to count all record
     * @param string $model_name
     * @param array $conditions
     * @return integer
     * @since 1.0
     * @author Daniel.Vu
     */
    public function search_count($model_name, $conditions) {
        return $this->execute_kw($model_name, 'search_count', array($conditions));
    }

    /**
     * Method to read record item
     * @param string $model_name
     * @param array|integer $ids
     * @param array $fields
     * @return array|Boolean Return False if Failed
     * @since 1.0
     * @author Daniel.Vu
     *
     */
    public function read($model_name, $ids, $fields = array()) {
        if (!is_array($ids)) {
            $ids = array($ids);
        }
        return $this->execute_kw($model_name, 'read', array($ids), array('fields' => $fields));
    }

    /**
     * Method to get all fields of model
     * @param string $model_name
     * @param array $attributes
     * @return array|Boolean False if failed
     * @since 1.0
     * @author Daniel.Vu
     */
    public function getFields($model_name, $attributes = array('type', 'string')) {
        return $this->execute_kw($model_name, 'fields_get', array(), array('attributes' => $attributes));
    }

    /**
     * Method to search and load data
     * @param string $model_name
     * @param array $conditions
     * $conditions = [
     *  ['is_company', '=', true],
     *  ['customer', '=', true]
     * ]
     * @param array $fields .i.e. $fields = ['name', 'active']
     * @param integer $offset
     * @param integer $limit
     * @param string $order
     * @return array|Boolean Return False if failed
     * @since 1.0
     * @author Daniel.Vu
     */
    public function searchData($model_name, $conditions, $fields, $offset = 0, $limit = 0, $order = 'id ASC') {
        $order = str_replace('a.', '', $order);
        return $this->execute_kw($model_name, 'search_read', array($conditions), array('fields' => $fields, 'offset' => (int) $offset, 'limit' => (int) $limit, 'order' => $order));
    }

    /**
     * Method to create new record
     * @param string $model_name
     * @param array $data
     * $data = [
     * 'name' => '',
     * 'active' => ''
     * ]
     * @return integer|Boolean
     * @since 1.0
     * @author Daniel.Vu
     */
    public function create($model_name, $data) {
        return $this->execute_kw($model_name, 'create', array($data));
    }

    /**
     * Method to update record
     * @param string $model_name
     * @param array $ids
     * @param array $data
     * $data = [
     * 'name' => 'new name'
     * ]
     * @return integer|Boolean
     * @author Daniel.Vu
     * @since 1.0
     */
    public function update($model_name, $ids, $data) {
        if (!is_array($ids)) {
            $ids = array($ids);
        }
        return $this->execute_kw($model_name, 'write', array($ids, $data));
    }

    /**
     * Method to remove record
     * @param string $model_name
     * @param array $ids
     * @return Boolean
     * @since 1.0
     * @author Daniel.Vu
     */
    public function delete($model_name, $ids) {
        if (!is_array($ids)) {
            $ids = array($ids);
        }
        return $this->execute_kw($model_name, 'unlink', array($ids));
    }

    /**
     * Metho to call workflow
     * @param string $model_name
     * @param string $method_name
     * @param integer $id
     * @return integer|Boolean
     * @since 1.0
     * @author Daniel.Vu
     */
    public function call_workflow($model_name, $method_name, $id) {
        $response = $this->object->exec_workflow($this->db_name, $this->uid, $this->password, $model_name, $method_name, $id);
        if (isset($response['faultCode'])) {
            $app = JFactory::getApplication();
            $user = JFactory::getUser();
            $isroot = $user->get('isRoot');
            if ($isroot) {
                $app->enqueueMessage('CODE: ' . $response['faultCode'] . ' - ' . $response['faultString'], 'error');
            }

            return false;
        }
        return $response;
    }

    /**
     * Method to get repord data
     * @param string $model_name
     * @param array $ids
     * @return array
     * @since 1.0
     * @author Daniel.Vu
     */
    protected function renderReport($model_name, $ids) {
        if (!is_array($ids)) {
            $ids = array($ids);
        }
        $result = $this->report->render_report($this->db_name, $this->uid, $this->password, $model_name, $ids);
        $reportData = base64_decode($result['result']);
        return $reportData;
    }

    /**
     * Method to check access permission
     * @param string $model_name
     * @param string $permission i.e. read
     * @return Boolean
     * @since 1.0
     * @author Daniel.Vu
     */
    public function checkAccess($model_name, $permission = 'read') {
        return $this->execute_kw($model_name, 'check_access_rights', array($permission), array('raise_exception' => false));
    }

    public function getUser() {
        $user = $this->read('res.users', $this->uid, array('id', 'name', 'company_id'));
        if (!empty($user)) {
            return $user[0];
        }
        return false;
    }

    public function getCompany() {
        $user = $this->getUser();
        if (empty($user)) {
            return false;
        }
        $company = $this->read('res.company', $user['company_id'][0], array('id', 'name', 'currency_id'));
        if (!empty($company)) {
            return $company[0];
        }
        return false;
    }

    public function getCurrency() {
        $company = $this->getCompany();
        if (empty($company)) {
            return false;
        }
        $currency = $this->read('res.currency', $company['currency_id'][0]);
        if (!empty($currency)) {
            return $currency[0];
        }
        return false;
    }

}

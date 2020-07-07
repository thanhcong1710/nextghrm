<?php

/**
 * @package pkg_nextgcyber
 * @subpackage  com_nextgcyber
 *
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://domain.com
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;

/**
 *
 * @package pkg_nextgcyber
 * @subpackage  com_nextgcyber
 * @since       1.0
 */
class NextgCyberModelDashBoard extends JModelLegacy {

    /**
     * Model context string.
     *
     * @var		string
     */
    public $_context = 'com_nextgcyber.dashboard';

    /**
     * The category context (allows other extensions to derived from this model).
     *
     * @var		string
     */
    protected $_extension = 'com_nextgcyber';

    protected function populateState($ordering = null, $direction = null) {
        parent::populateState($ordering, $direction);
        $app = JFactory::getApplication();
        $params = $app->getParams();
        $this->setState('params', $params);
    }

    public function getDashboardData() {
        $instancesModel = JModelLegacy::getInstance('Instances', 'NextgCyberModel');
        $instancesModel->clearState();
        $instancesModel->setState('list.limit', 5);
        $instancesModel->setState('list.ordering', 'id');
        $instancesModel->setState('list.direction', 'desc');
        $data = new stdClass();
        $data->instances = $instancesModel->getItems();

        $ordersModel = JModelLegacy::getInstance('Orders', 'NextgCyberModel');
        $ordersModel->clearState();
        $ordersModel->setState('list.limit', 5);
        $data->orders = $ordersModel->getItems();

        $invoicesModel = JModelLegacy::getInstance('Invoices', 'NextgCyberModel');
        $invoicesModel->clearState();
        $invoicesModel->setState('list.limit', 5);
        $data->invoices = $invoicesModel->getItems();

        return $data;
    }

}

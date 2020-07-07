<?php

/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;

JLoader::register('NextgCyberOdooDB', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/odoodb.php');

class NextgCyberCustomerHelper extends NextgCyberHelper {

    /**
     * Method to get current user time zone from setting
     * @param integer $user_id
     * @return string
     * @since 1.0
     * @author Daniel.Vu
     */
    public static function getUserTimezone($user_id = null) {
        $user = JFactory::getUser($user_id);
        $timezone = $user->getParam('timezone');
        if (!$timezone) {
            $config = JFactory::getConfig();
            $timezone = $config->get('offset');
        }
        return $timezone;
    }

    /**
     * Method to get current odoo partner id of joomla user id
     * @param integer $joomla_user_id
     * @return boolean|integer
     * @since 1.0
     * @author Daniel.Vu
     */
    public static function getPartnerIdByID($joomla_user_id = null) {
        $session = JFactory::getSession();
        if (empty($joomla_user_id)) {
            $user = JFactory::getUser();
            $joomla_user_id = $user->get('id');
        }

        if (empty($joomla_user_id)) {
            return false;
        }

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('odoo_id')
                ->from('#__users')
                ->where('id = ' . (int) $joomla_user_id);
        $db->setQuery($query);
        $partner_id = $db->loadResult();
        if (!empty($partner_id)) {
            $session->set('first_login', false);
            return (int) $partner_id;
        } else {
            # Create new partner

            $user = JFactory::getUser($joomla_user_id);
            $data = array();
            $data['name'] = $user->get('name');
            $data['email'] = $user->get('email');
            $data['customer'] = 1;
            $data['is_company'] = 0;
            $data['active'] = 1;
            $partnerModel = NextgCyberHelper::getAdminModel('Partner');
            $partnerModel->getState();
            if ($partnerModel->save($data)) {
                $partner_id = $partnerModel->getState($partnerModel->getName() . '.id');
                try {
                    $query->clear();
                    $query->update('#__users')
                            ->set('odoo_id = ' . (int) $partner_id)
                            ->where('id = ' . $joomla_user_id);
                    $db->setQuery($query);
                    $db->execute();
                    $session->set('first_login', true);
                    return (int) $partner_id;
                } catch (Exception $ex) {
                    return false;
                }
            }
            return false;
        }
    }

    public static function isFirstLogin() {
        $session = JFactory::getSession();
        return $session->get('first_login', false);
    }

    /**
     * Method to get current state of instance
     * @param integer $instance_id
     * @return string
     * @since 1.0
     * @author Daniel.Vu
     */
    protected static function getInstanceInfo($instance_id) {
        $odoo_db = new NextgCyberOdooDB();
        $query = $odoo_db->getQuery(true);
        $query->select('state, type');
        $query->from('nc.instance');
        $query->where('id = ' . (int) $instance_id);
        $odoo_db->setQuery($query);
        $state = $odoo_db->loadObject();
        return $state;
    }

    /**
     * Method to check permission of partner with instance
     * @param integer $partner_id
     * @param integer $instance_id
     * @return boolean
     * @since 1.0
     * @author Daniel.Vu
     */
    public static function authoriseInstance($partner_id, $instance_id) {
        if (empty($partner_id) || empty($instance_id)) {
            return false;
        }

        $odoo_db = new NextgCyberOdooDB();
        $query = $odoo_db->getQuery(true);
        $query->select('id');
        $query->from('nc.instance');
        $query->where('id = ' . (int) $instance_id);
        $query->where('partner_id.id = ' . (int) $partner_id);
        $odoo_db->setQuery($query);
        $exist = $odoo_db->loadObject();
        if (!empty($exist)) {
            return true;
        }
        return false;
    }

    public static function canStartInstance($instance_id) {
        $instance = static::getInstanceInfo($instance_id);
        if (empty($instance) || $instance->state != 'deploy') {
            return false;
        }
        $partner_id = static::getPartnerIdByID();
        return static::authoriseInstance($partner_id, $instance_id);
    }

    public static function canStopInstance($instance_id) {
        $partner_id = static::getPartnerIdByID();
        return static::authoriseInstance($partner_id, $instance_id);
    }

    public static function canRestartInstance($instance_id) {
        $instance = static::getInstanceInfo($instance_id);
        if (empty($instance) || $instance->state != 'deploy') {
            return false;
        }
        $partner_id = static::getPartnerIdByID();
        return static::authoriseInstance($partner_id, $instance_id);
    }

    public static function canDeleteInstance($instance_id) {
        $partner_id = static::getPartnerIdByID();
        return static::authoriseInstance($partner_id, $instance_id);
    }

    public static function canRedeployInstance($instance_id) {
        $instance = static::getInstanceInfo($instance_id);
        if (empty($instance) || $instance->state != 'deploy') {
            return false;
        }
        $partner_id = static::getPartnerIdByID();
        return static::authoriseInstance($partner_id, $instance_id);
    }

    public static function canAddCustomDomain($instance_id) {
        $partner_id = static::getPartnerIdByID();
        return static::authoriseInstance($partner_id, $instance_id);
    }

    /**
     * Method to get total number instance user can create
     * @return int
     * @since 1.0
     * @author Daniel.Vu
     */
    public static function getTotalNumberFreeInstanceCanCreate() {
        $max_instance = NextgCyberHelper::getParam('free_trial', 1);
        $instancesModel = JModelLegacy::getInstance('Instances', 'NextgCyberModel');
        $instancesModel->clearState();
        $partner_id = static::getPartnerIdByID();
        $instancesModel->setState('filter.partner_id', $partner_id);
        $instancesModel->setState('filter.type', 'trial');
        $instances = $instancesModel->getItems();
        $available = $max_instance - count($instances);
        if ($available && $available > 0) {
            return $available;
        }
        return 0;
    }

    public static function canCreateNewTrial() {
        $available = static::getTotalNumberFreeInstanceCanCreate();
        if (!$available) {
            return false;
        }
        return true;
    }

    public static function canCreateNewPaidInstance() {
        $partner_id = static::getPartnerIdByID();
        if (empty($partner_id)) {
            return false;
        }
        return true;
    }

    public static function canUpgradeInstance($instance_id) {
        $instance = static::getInstanceInfo($instance_id);
        if (empty($instance) || $instance->type != 'trial') {
            return false;
        }
        $partner_id = static::getPartnerIdByID();
        return static::authoriseInstance($partner_id, $instance_id);
    }

    public static function authoriseOrder($partner_id, $order_id) {
        if (empty($partner_id) || empty($order_id)) {
            return false;
        }

        $odoo_db = new NextgCyberOdooDB();
        $query = $odoo_db->getQuery(true);
        $query->select('id');
        $query->from('sale.order');
        $query->where('id = ' . (int) $order_id);
        $query->where('partner_id.id = ' . (int) $partner_id);
        $odoo_db->setQuery($query);
        $exist = $odoo_db->loadObject();
        if (!empty($exist)) {
            return true;
        }
        return false;
    }

    /**
     * Method to get current state of order
     * @param integer $order_id
     * @return string
     * @since 1.0
     * @author Daniel.Vu
     */
    protected static function getOrderInfo($order_id) {
        $odoo_db = new NextgCyberOdooDB();
        $query = $odoo_db->getQuery(true);
        $query->select('state');
        $query->from('sale.order');
        $query->where('id = ' . (int) $order_id);
        $odoo_db->setQuery($query);
        $state = $odoo_db->loadObject();
        return $state;
    }

    public static function canCancelOrder($order_id) {
        $order = static::getOrderInfo($order_id);
        if (empty($order) || $order->state != 'draft') {
            return false;
        }
        $partner_id = static::getPartnerIdByID();
        return static::authoriseOrder($partner_id, $order_id);
    }

    /**
     * Method to check user can confirm order
     * @param integer $order_id
     * @return boolean
     * @since 1.0
     * @author Daniel.Vu
     */
    public static function canConfirmOrder($order_id) {
        $order = static::getOrderInfo($order_id);
        if (empty($order) || $order->state != 'draft') {
            return false;
        }
        $partner_id = static::getPartnerIdByID();
        return static::authoriseOrder($partner_id, $order_id);
    }

    public static function getEmail($partner_id = null) {
        if (empty($partner_id)) {
            $partner_id = static::getPartnerIdByID();
        }
        if (empty($partner_id)) {
            return false;
        }

        $odoo_db = new NextgCyberOdooDB();
        $query = $odoo_db->getQuery(true);
        $query->select('email');
        $query->from('res.partner');
        $query->where('id = ' . (int) $partner_id);
        $odoo_db->setQuery($query);
        $partner = $odoo_db->loadObject();
        if (empty($partner)) {
            return false;
        }
        return $partner->email;
    }

    /**
     * Method to check customer can add new resource into instance
     * @param integer $instance_id
     * @return boolean
     * @since 1.3
     */
    public static function canAddResourceIntoInstance($instance_id) {
        $instance = static::getInstanceInfo($instance_id);
        if (empty($instance) || $instance->type != 'normal') {
            return false;
        }
        $partner_id = static::getPartnerIdByID();
        return static::authoriseInstance($partner_id, $instance_id);
    }

}

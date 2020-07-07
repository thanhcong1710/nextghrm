<?php

/**
 * @package pkg_nextgcyber
 * @subpackage  com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://domain.com
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;

/**
 * @static
 * @package pkg_nextgcyber
 * @subpackage  com_nextgcyber
 * @since       1.0
 */
class NextgCyberMenuHelper {

    /**
     * Method get customer menu
     * @param string $viewName active view
     * @return array
     * @since 1.0
     */
    protected static function getCustomerMenu($viewName) {
        $user = JFactory::getUser();
        $menus = [];
        if (!$user->guest) {
            // Group has one element
            $menus['COM_NEXTGCYBER_DASHBOARD_MENU_GROUP_DASHBOARD_LABEL'][] = [
                'href' => JRoute::_(NextgCyberHelperRoute::getDashboardRoute()),
                'title' => JText::_('COM_NEXTGCYBER_DASHBOARD_MENU_DASHBOARD_LABEL'),
                'view' => ['dashboard'],
                'icon_class' => 'fa fa-dashboard fa-fw'
            ];
            $menus['COM_NEXTGCYBER_DASHBOARD_MENU_GROUP_INSTANCE_LABEL'][] = [
                'href' => JRoute::_(NextgCyberHelperRoute::getInstancesRoute()),
                'title' => JText::_('COM_NEXTGCYBER_DASHBOARD_MENU_GROUP_INSTANCE_LABEL'),
                'view' => ['instances', 'instance'],
                'icon_class' => 'fa fa-tasks fa-fw'
            ];

            $menus['COM_NEXTGCYBER_DASHBOARD_MENU_GROUP_ORDER_LABEL'][] = [
                'href' => JRoute::_(NextgCyberHelperRoute::getOrdersRoute()),
                'title' => JText::_('COM_NEXTGCYBER_DASHBOARD_MENU_GROUP_ORDER_LABEL'),
                'view' => ['orders', 'order'],
                'icon_class' => 'fa fa-shopping-cart fa-fw'
            ];
            $menus['COM_NEXTGCYBER_DASHBOARD_MENU_GROUP_INVOICE_LABEL'][] = [
                'href' => JRoute::_(NextgCyberHelperRoute::getInvoicesRoute()),
                'title' => JText::_('COM_NEXTGCYBER_DASHBOARD_MENU_GROUP_INVOICE_LABEL'),
                'view' => ['invoices', 'invoice'],
                'icon_class' => 'fa fa-support fa-fw'
            ];

            $menus['COM_NEXTGCYBER_DASHBOARD_MENU_GROUP_PROFILE_LABEL'][] = [
                'href' => JRoute::_(NextgCyberHelperRoute::getProfileRoute()),
                'title' => JText::_('COM_NEXTGCYBER_DASHBOARD_MENU_GROUP_PROFILE_LABEL'),
                'view' => ['profile'],
                'icon_class' => 'fa fa-user fa-fw'
            ];

            foreach ($menus as $key => $menuGroup) {
                foreach ($menuGroup as $menu_item_key => $menu_item) {
                    if (in_array($viewName, $menu_item['view'])) {
                        $menus[$key][$menu_item_key]['active'] = 1;
                    }
                }
            }
        }
        return $menus;
    }

    /**
     * Method get manager menu
     * @param string $viewName active view
     * @return array
     * @since 1.0
     */
    protected static function getManagerMenu($viewName) {
        $user = JFactory::getUser();
        $menus = [];
        if (!$user->guest) {
            $menus['COM_NEXTGCYBER_DASHBOARD_MENU_GROUP_AGENT_DASHBOARD_LABEL'][] = [
                'href' => JRoute::_(NextgCyberHelperRoute::getDashboardRoute()),
                'title' => JText::_('COM_NEXTGCYBER_DASHBOARD_MENU_GROUP_AGENT_DASHBOARD_LABEL'),
                'view' => ['agentdashboard'],
                'icon_class' => 'fa fa-dashboard fa-fw'
            ];

            foreach ($menus as $key => $menuGroup) {
                foreach ($menuGroup as $menu_item_key => $menu_item) {
                    if (in_array($viewName, $menu_item['view'])) {
                        $menus[$key][$menu_item_key]['active'] = 1;
                    }
                }
            }
        }
        return $menus;
    }

    /**
     * Method get main menu
     * @param string $viewName active view
     * @return array
     * @since 1.0
     */
    public static function getMenu($type, $viewName) {
        if ($type == 'customer') {
            return static::getCustomerMenu($viewName);
        } elseif ($type == 'manager') {
            return static::getManagerMenu($viewName);
        }
    }

}

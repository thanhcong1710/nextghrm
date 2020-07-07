<?php

/**
 * @package pkg_nextgcyber NextgCyber for Joomla
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://domain.com
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;
JLoader::register('NextgCyberMenuHelper', JPATH_COMPONENT . '/helpers/menuhelper.php');
JLoader::register('NextgCyberIPHelper', JPATH_COMPONENT . '/helpers/iphelper.php');
JLoader::register('NextgCyberCustomerHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/customerhelper.php');
JLoader::register('NextgCyberViewItem', JPATH_COMPONENT . '/views/itemview.php');

class NextgCyberViewDashBoard extends NextgCyberViewItem {

    protected $state = null;

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise a Error object.
     */
    public function display($tpl = null) {
        try {
            $partner_id = NextgCyberCustomerHelper::getPartnerIdByID();
            $first_login = NextgCyberCustomerHelper::isFirstLogin();
            $user = JFactory::getUser();
            if (!$user->guest && empty($partner_id)) {
                JFactory::getApplication()->redirect(NextgCyberHelperRoute::getDashboardRoute());
                return false;
            }

            if (empty($partner_id) || $first_login) {
                JFactory::getApplication()->redirect(NextgCyberHelperRoute::getDashboardRoute());
                return false;
            }
        } catch (Exception $ex) {
            JFactory::getApplication()->redirect(NextgCyberHelperRoute::getDashboardRoute());
            return false;
        }

        $this->dashboardData = $this->get('DashboardData');
        parent::display($tpl);
    }

}

<?php

/**
 * @package nextgcyber NextgCyber for Joomla
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://domain.com
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;

JLoader::register('NextgCyberHelper', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/helper.php');

class NextgCyberSiteHelper extends NextgCyberHelper {

    public static function loadLibrary() {
        JHtml::_('jquery.framework');
        JHTML::_('behavior.formvalidator');
        JHtml::_('formbehavior.chosen', 'select');
        JHtml::stylesheet('com_nextgcyber/site/main.css', false, true, false);
        #JHtml::script('com_nextgcyber/site/nextgcyber-modal.js', false, true, false);
        JHtml::script('com_nextgcyber/site/main.js', false, true, false);
        #JHtml::script('com_nextgcyber/site/jquery.circle-diagram.js', false, true, false);
    }

    public static function getDomainName() {
        return $_SERVER['HTTP_HOST'];
    }

    public static function getProgressClass($percentage) {
        $class = '';
        if ($percentage > 20) {
            $class = ' progress-bar-success';
        }
        if ($percentage > 60) {
            $class = ' progress-bar-info';
        }
        if ($percentage > 80) {
            $class = ' progress-bar-warning';
        }
        if ($percentage > 100) {
            $class = ' progress-bar-danger';
        }
        return $class;
    }

    public static function prepareApp($apps) {
//        # check domain
//        $allowModules = ['account', 'website', 'hr_evaluation', 'account_accountant', 'hr',
//            'gamification', 'hr_timesheet_sheet', 'hr_expense', 'hr_recruitment'];
//
//        # only display module for hrm
//        $db2 = new NextgCyberOdooDB();
//        $query2 = $db2->getQuery(true);
//        // Select the required fields from the table.
//        $query2->select('id');
//        $query2->from('ir.module.module');
//
//        $query2->where(array('name', 'in', $allowModules));
//        $db2->setQuery($query2);
//        $module = $db2->loadObjectList();
//        $module_id = [];
//        if (!empty($module)) {
//            foreach ($module as $key => $value) {
//                $module_id[] = $value->id;
//            }
//        }
//
//        foreach ($apps as $key => $value) {
//            if (!in_array($value->nc_module_id[0], $module_id)){
//                unset($apps[$key]);
//            }
//        }
        
        return $apps;
    }

    public static function prepareCustomApp($apps) {
        $onlyModule = ['hr_vn', 'hr_vn_accident', 'hr_vn_business_trip',
            'hr_vn_contract', 'hr_vn_insurance', 'hr_vn_laudatory', 'hr_vn_payroll',
            'hr_vn_salary_advance', 'hr_vn_salary_payment', 'hr_vn_staffing',
            'hr_vn_timesheet', 'hr_vn_training', 'hr_vn_training'
        ];
        return $apps;
    }
    
    public static function getOdooVersion(){
        # only display module for hrm
        # by Odoo version of vserver
        $db3 = new NextgCyberOdooDB();
        $query3 = $db3->getQuery(true);
        // Select the required fields from the table.
        $query3->select('id, odoo_version_id');
        $query3->from('nc.vserver');
        $db3->setQuery($query3, 0, 1);
        $vserver = $db3->loadObjectList();
        if($vserver){
            return $vserver[0]->odoo_version_id[0];
        } else{
            return false;
        }
    }

}

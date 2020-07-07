<?php
/**
 * @Copyright Copyright (C) 2015 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:     Buruj Solutions
 + Contact:     www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:  May 22, 2015
  ^
  + Project:    JS Tickets
  ^
 */

 defined('_JEXEC') or die('Restricted access');
 
if (!defined('JVERSION')) {
    $version = new JVersion;
    $joomla = $version->getShortVersion();
    $jversion = substr($joomla, 0, 3);
    define('JVERSION', $jversion);
}

jimport('joomla.application.component.model');
jimport('joomla.application.component.view');
jimport('joomla.application.component.controller');

if (JVERSION < 3) {

    abstract class JSSupportTicketController extends JController {
        function __construct() {
            parent::__construct();
        }
        static function getJSModel($model){
            return JSSupportTicketModel::getJSModel($model);
        }
    }
    abstract class JSSupportTicketModel extends JModel {
         function __construct() {
            parent::__construct();
    }
        Static function getJSModel($model) {
            require_once JPATH_COMPONENT_ADMINISTRATOR .'/models/' . strtolower($model) . '.php';
            $modelclass = 'JSSupportTicketModel' . $model;
            $model_object = new $modelclass;
            return $model_object;
        }
    }

    abstract class JSSupportTicketView extends JView {
        function __construct() {
            parent::__construct();
        }
        static function getJSModel($model){
            return JSSupportTicketModel::getJSModel($model);
        }
    }

}else{

    abstract class JSSupportTicketController extends JControllerLegacy {
        function __construct() {
            parent::__construct();
        }
        static function getJSModel($model){
            return JSSupportTicketModel::getJSModel($model);
        }
    }

    abstract class JSSupportTicketModel extends JModelLegacy {
        function __construct() {
            parent::__construct();
        }
        Static function getJSModel($model) {
            require_once JPATH_COMPONENT_ADMINISTRATOR.'/models/' . strtolower($model) . '.php';
            $modelclass = 'JSSupportTicketModel' . $model;
            $model_object = new $modelclass;
            return $model_object;
        }
    }

    abstract class JSSupportTicketView extends JViewLegacy {
        function __construct() {
            parent::__construct();
        }
        static function getJSModel($model){
            return JSSupportTicketModel::getJSModel($model);
        }
    }
}
?>
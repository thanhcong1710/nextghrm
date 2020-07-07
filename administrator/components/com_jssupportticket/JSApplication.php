<?php

/**
 * @Copyright Copyright (C) 2015 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:     Buruj Solutions
  + Contact:    www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:  May 22, 2015
  ^
   + Project:    JS Tickets
  ^
 */

defined('_JEXEC') or die('Restricted access');

if (JVERSION < 3) {
    jimport('joomla.application.component.model');
    jimport('joomla.application.component.view');
    jimport('joomla.application.component.controller');

    if (!class_exists('JSSupportTicketController', false)) {

        abstract class JSSupportTicketController extends JController {

            function __construct() {
                parent::__construct();
            }

            static function getJSModel($model) {
                return JSSupportTicketModel::getJSModel($model);
            }

        }

    }
    if (!class_exists('JSSupportTicketModel', false)) {

        abstract class JSSupportTicketModel extends JModel {

            function __construct() {
                parent::__construct();
            }

            static function getJSModel($model) {
                require_once JPATH_COMPONENT . '/models/' . $model . '.php';
                $modelclass = 'JSSupportticketModel' . $model;
                $model_object = new $modelclass;
                return $model_object;
            }

        }

    }
    if (!class_exists('JSSupportTicketView', false)) {

        abstract class JSSupportTicketView extends JView {

            function __construct() {
                parent::__construct();
            }

            static function getJSModel($model) {
                return JSSupportTicketModel::getJSModel($model);
            }

        }

    }
} else {
    if (!class_exists('JSSupportTicketController', false)) {

        abstract class JSSupportTicketController extends JControllerLegacy {

            function __construct() {
                parent::__construct();
            }

            static function getJSModel($model) {
                return JSSupportTicketModel::getJSModel($model);
            }

        }

    }
    if (!class_exists('JSSupportTicketModel', false)) {

        abstract class JSSupportTicketModel extends JModelLegacy {

            function __construct() {
                parent::__construct();
            }

            static function getJSModel($model) {
                require_once JPATH_COMPONENT . '/models/' . $model . '.php';
                $modelclass = 'JSSupportticketModel' . $model;
                $model_object = new $modelclass;
                return $model_object;
            }

        }

    }
    if (!class_exists('JSView', false)) {

        abstract class JSSupportTicketView extends JViewLegacy {

            function __construct() {
                parent::__construct();
            }

            static function getJSModel($model) {
                return JSSupportTicketModel::getJSModel($model);
            }

        }

    }
}
?>
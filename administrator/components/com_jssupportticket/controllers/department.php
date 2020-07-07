<?php

/**
 * @Copyright Copyright (C) 2015 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	May 22, 2015
  ^
  + Project: 	JS Tickets
  ^
 */
defined('_JEXEC') or die('Not Allowed');

jimport('joomla.application.component.controller');

class JSSupportticketControllerDepartment extends JSSupportTicketController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function savedepartment() {
        $this->storedepartment('saveandclose');
    }

    function savedepartmentsave() {
        $this->storedepartment('save');
    }

    function savedepartmentandnew() {
        $this->storedepartment('saveandnew');
    }

    function storedepartment($callfrom) {
        $data = JRequest::get('post');
        $result = $this->getJSModel('department')->storeDepartment($data);
        if ($result == SAVED) {
            if($callfrom == 'saveandclose') {
                $link = "index.php?option=com_jssupportticket&c=department&layout=departments";
            }elseif ($callfrom == 'save') {
                $link = "index.php?option=com_jssupportticket&c=department&layout=formdepartment&cid[]=" .JSSupportticketMessage::$recordid;
            }elseif ($callfrom == 'saveandnew') {
                $link = "index.php?option=com_jssupportticket&c=department&layout=formdepartment";
            }
        }else{
            $link = 'index.php?option=com_jssupportticket&c=department&layout=formdepartment';
        }
        $msg = JSSupportticketMessage::getMessage($result,'DEPARTMENT');
        $this->setRedirect($link, $msg);
    }

    function deletedepartment() {
        $result = $this->getJSModel('department')->deleteDepartment();
        $msg = JSSupportticketMessage::getMessage($result,'DEPARTMENT');
        $link = "index.php?option=com_jssupportticket&c=department&layout=departments";
        $this->setRedirect($link, $msg);
    }

    function canceldepartment() {
        $link = "index.php?option=com_jssupportticket&c=department&layout=departments";
        $msg = JSSupportticketMessage::getMessage(CANCEL,'DEPARTMENT');
        $this->setRedirect($link, $msg);
    }

    function addnewdepartment() {
        $layoutName = JRequest::setVar('layout', 'formdepartment');
        $this->display();
    }
    function display($cachable = false, $urlparams = false) {
        $document = JFactory::getDocument();
        $viewName = 'department';
        $layoutName = JRequest::getVar('layout', 'department');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>

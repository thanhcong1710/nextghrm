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

class JSSupportticketControllerEmailtemplate extends JSSupportTicketController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function saveemailtemplate() {
        $data = JRequest::get('post');
        $templatefor = $data['templatefor'];
        $result = $this->getJSModel('emailtemplate')->storeEmailTemplate();
        switch ($templatefor) {
            case 'ticket-new' : $tempfor = 'ew-tk';
                break;
            case 'ticket-staff' : $tempfor = 'sntk-tk';
                break;
            case 'department-new' : $tempfor = 'ew-md';
                break;
            case 'group-new' : $tempfor = 'ew-gr';
                break;
            case 'staff-new' : $tempfor = 'ew-sm';
                break;
            case 'helptopic-new' : $tempfor = 'ew-ht';
                break;
            case 'reassign-tk' : $tempfor = 'rs-tk';
                break;
            case 'close-tk' : $tempfor = 'cl-tk';
                break;
            case 'delete-tk' : $tempfor = 'dl-tk';
                break;
            case 'moverdue-tk' : $tempfor = 'mo-tk';
                break;
            case 'banemail-tk' : $tempfor = 'be-tk';
                break;
            case 'banemail-trtk' : $tempfor = 'be-trtk';
                break;
            case 'deptrans-tk' : $tempfor = 'dt-tk';
                break;
            case 'banemailcloseticket-tk' : $tempfor = 'ebct-tk';
                break;
            case 'unbanemail-tk' : $tempfor = 'ube-tk';
                break;
            case 'responce-tk' : $tempfor = 'rsp-tk';
                break;
            case 'reply-tk' : $tempfor = 'rpy-tk';
                break;
            case 'ticket-new-admin' : $tempfor = 'tk-ew-ad';
                break;
            case 'lock-tk' : $tempfor = 'lk-tk';
                break;
            case 'unlock-tk' : $tempfor = 'ulk-tk';
                break;
            case 'markprgs-tk' : $tempfor = 'minp-tk';
                break;
            case 'pchnge-tk' : $tempfor = 'pc-tk';
                break;
            case 'mlnew-tk' : $tempfor = 'ml-ew';
                break;
            case 'mlrep-tk' : $tempfor = 'ml-rp';
                break;
        }
        $link = 'index.php?option=com_jssupportticket&c=emailtemplate&layout=emailtemplate&tf=' . $tempfor;
        $msg = JSSupportticketMessage::getMessage($result,'EMAIL_TEMPLATE');        
        $this->setRedirect($link, $msg);
    }

    function display($cachable = false, $urlparams = false) {
        $document = JFactory::getDocument();
        $viewName = 'emailtemplate';
        $layoutName = JRequest::getVar('layout', 'emailtemplate');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>

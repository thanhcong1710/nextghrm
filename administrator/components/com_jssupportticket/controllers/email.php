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

class JSSupportticketControllerEmail extends JSSupportTicketController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function saveemail() {
        $this->storeemail('saveandclose');
    }

    function saveemailsave() {
        $this->storeemail('save');
    }

    function saveemailandnew() {
        $this->storeemail('saveandnew');
    }

    function storeemail($callfrom) {
        $data = JRequest::get('POST');
        $result = $this->getJSModel('email')->storeEmail($data);
        if ($result == SAVED) {
            if ($callfrom == 'saveandclose') {
                $link = "index.php?option=com_jssupportticket&c=email&layout=emails";
            } elseif ($callfrom == 'save') {
                $link = "index.php?option=com_jssupportticket&c=email&layout=formemail&cid[]=".JSSupportticketMessage::$recordid;
            } elseif ($callfrom == 'saveandnew') {
                $link = "index.php?option=com_jssupportticket&c=email&layout=formemail";
            }
        }else{
            $link = "index.php?option=com_jssupportticket&c=email&layout=formemail";
        }
        $msg = JSSupportticketMessage::getMessage($result,'EMAIL');
        $this->setRedirect($link, $msg);
    }

    function deleteemail() {
        $result = $this->getJSModel('email')->deleteEmail();
        $msg = JSSupportticketMessage::getMessage($result,'EMAIL');
        $link = "index.php?option=com_jssupportticket&c=email&layout=emails";
        $this->setRedirect($link, $msg);
    }

    function addnewemail() {
        $layoutName = JRequest::setVar('layout', 'formemail');
        $this->display();
    }

    function cancelemail() {
        $link = "index.php?option=com_jssupportticket&c=email&layout=emails";
        $msg = JSSupportticketMessage::getMessage(CANCEL,'EMAIL');
        $this->setRedirect($link, $msg);
    }

    function display($cachable = false, $urlparams = false) {
        $document = JFactory::getDocument();
        $viewName = 'email';
        $layoutName = JRequest::getVar('layout', 'email');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>

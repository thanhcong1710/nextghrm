<?php

/**
 * @Copyright Copyright (C) 2015 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:     Buruj Solutions
  + Contact:        www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:  May 22, 2015
  ^
  + Project:    JS Tickets
  ^
 */
defined('_JEXEC') or die('Not Allowed');

jimport('joomla.application.component.controller');

class JSSupportticketControllerUserFields extends JSSupportTicketController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function saveuserfield() {
        $this->storeUserFields('saveandclose');
    }

    function saveuserfieldsave() {
        $this->storeUserFields('save');
    }

    function saveuserfieldandnew() {
        $this->storeUserFields('saveandnew');
    }

    function storeUserFields($callfrom) {
        $result = $this->getJSModel('userfields')->storeUserField();
        if ($result == SAVED) {
            $link = 'index.php?option=com_jssupportticket&c=userfields&layout=userfields';
        }else{
            $link = 'index.php?option=com_jssupportticket&c=userfields&layout=formuserfield';
        }
        $msg = JSSupportticketMessage::getMessage($result,'USER_FIELD');
        $this->setRedirect($link, $msg);
    }

    function fieldpublished() {
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $fieldid = $cid[0];
        $result = $this->getJSModel('userfields')->fieldPublished($fieldid, 1); // published
        $link = 'index.php?option=com_jssupportticket&c=userfields&layout=fieldsordering';
        $msg = JText::_('Field mark as published');
        $this->setRedirect($link, $msg);
    }

    function fieldunpublished() {
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $fieldid = $cid[0];
        $result = $this->getJSModel('userfields')->fieldPublished($fieldid, 0); // unpublished
        $link = 'index.php?option=com_jssupportticket&c=userfields&layout=fieldsordering';
        $msg = JText::_('Field mark as unpublished');
        $this->setRedirect($link, $msg);
    }

    function fieldrequired() {
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $fieldid = $cid[0];
        $result = $this->getJSModel('userfields')->fieldRequired($fieldid, 1); // required
        $link = 'index.php?option=com_jssupportticket&c=userfields&layout=fieldsordering';
        $msg = JText::_('Field mark as required');
        $this->setRedirect($link, $msg);
    }

    function fieldnotrequired() {
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $fieldid = $cid[0];
        $result = $this->getJSModel('userfields')->fieldRequired($fieldid, 0); // not required
        $link = 'index.php?option=com_jssupportticket&c=userfields&layout=fieldsordering';
        $msg = JText::_('Field mark as not required');
        $this->setRedirect($link, $msg);
    }

    function fieldorderingup() {
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $fieldid = $cid[0];
        $result = $this->getJSModel('userfields')->fieldOrderingUp($fieldid);
        $link = 'index.php?option=com_jssupportticket&c=userfields&layout=fieldsordering';
        $msg = JText::_('Field ordering up');
        $this->setRedirect($link, $msg);
    }

    function fieldorderingdown() {
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $fieldid = $cid[0];
        $result = $this->getJSModel('userfields')->fieldOrderingDown($fieldid);
        $link = 'index.php?option=com_jssupportticket&c=userfields&layout=fieldsordering';
        $msg = JText::_('Field mark ordering down');
        $this->setRedirect($link, $msg);
    }

    function adduserfield() {
        $fieldfor = JRequest::getVar('ff',1);
        JRequest::setVar('ff',$fieldfor);
        $layoutName = JRequest::setVar('layout', 'formuserfield');
        $this->display();
    }

    function deleteuserfieldoption() {
        $option_id = JRequest::getVar('id');
        $returnvalue = $this->getJSModel('userfields')->deleteUserFieldOptionValue($option_id);
        echo $returnvalue;
        JFactory::getApplication()->close();
    }

    function removeuserfields() {
        $result = $this->getJSModel('userfields')->deleteUserField();
        $msg = JSSupportticketMessage::getMessage($result,'USER_FIELD');
        if ($result == DELETE_ERROR){
            $msg = JSSupportticketMessage::$recordid. ' ' . $msg;
        }
        $link = 'index.php?option=com_jssupportticket&c=userfields&layout=userfields';
        $this->setRedirect($link, $msg);
    }

    function canceluserfield() {
        $msg = JSSupportticketMessage::getMessage(CANCEL,'USER_FIELD');
        $link = 'index.php?option=com_jssupportticket&c=userfields&layout=userfields';
        $this->setRedirect($link, $msg);
    }

    function display($cachable = false, $urlparams = false) {
        $document = JFactory::getDocument();
        $viewName = 'userfields';
        $layoutName = JRequest::getVar('layout', 'userfields');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $view->setLayout($layoutName);
        $view->display();
    }
}
?>
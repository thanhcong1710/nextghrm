<?php

/**
 * @Copyright Copyright (C) 2015 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:     Buruj Solutions
 + Contact:    www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	May 22, 2015
  ^
  + Project:    JS Tickets
  ^
 */
defined('_JEXEC') or die('Not Allowed');
jimport('joomla.application.component.controller');

class JSSupportticketControllerTicket extends JSSupportTicketController {

    function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    function saveticket() {
        $this->storeticket('saveandclose');
    }

    function saveticketsave() {
        $this->storeticket('save');
    }

    function saveticketandnew() {
        $this->storeticket('saveandnew');
    }

    function storeticket($callfrom) {
        $data = JRequest::get('post');
        $result = $this->getJSModel('ticket')->storeTicket($data);
        if($result == SAVED) {
            switch ($callfrom) {
                case 'save':
                    $link = 'index.php?option=com_jssupportticket&c=ticket&layout=formticket&cid[]='.JSSupportticketMessage::$recordid;
                    break;
                case 'saveandnew':
                    $link = 'index.php?option=com_jssupportticket&c=ticket&layout=formticket';
                    break;
                case 'saveandclose':
                    $link = 'index.php?option=com_jssupportticket&c=ticket&layout=tickets';
                    break;
            }
        }elseif($result == SAVE_ERROR || $result == MESSAGE_EMPTY){
            JFactory::getApplication()->setUserState('com_jssupportticket.data',$data);
            $link = 'index.php?option=com_jssupportticket&c=ticket&layout=formticket';
        }
        $msg = JSSupportticketMessage::getMessage($result,'TICKET');
        $this->setRedirect($link, $msg);
    }

    function actionticket() {
        $data = JRequest::get('POST');
        $action = $data['callfrom'];
        switch ($action) {
            case 'postreply':
                $data['responce'] = JRequest::getVar('responce', '', 'post', 'string', JREQUEST_ALLOWRAW);
                $result = $this->getJSModel('ticketreply')->storeTicketReplies($data['id'],$data['responce'], $data['created'], $data);
                $msg = JSSupportTicketMessage::getMessage($result,'REPLY');
                $link = 'index.php?option=com_jssupportticket&c=ticket&view=ticket&layout=ticketdetails&cid[]=' . $data['id'];
                $this->setRedirect($link, $msg);
                break;
            case 'action':
                switch ($data['callaction']) {
                    case 1://change priority
                        $result = $this->getJSModel('ticket')->changeTicketPriority($data['id'], $data['priorityid'], $data['created']);
                        $msg = JSSupportTicketMessage::getMessage($result,'PRIORITY');
                        $link = 'index.php?option=com_jssupportticket&c=ticket&view=ticket&layout=ticketdetails&cid[]=' . $data['id'];
                        $this->setRedirect($link, $msg);
                        break;
                    case 3: //ticket close
                        $result = $this->getJSModel('ticket')->ticketClose($data['id'], $data['created']);
                        $msg = JSSupportTicketMessage::getMessage($result,'CLOSE');
                        $link = 'index.php?option=com_jssupportticket&c=ticket&view=ticket&layout=ticketdetails&cid[]=' . $data['id'];
                        $this->setRedirect($link, $msg);
                        break;
                    case 8: //reopened ticket
                        $result = $this->getJSModel('ticket')->reopenTicket($data['id'], $data['lastreply']);
                        $msg = JSSupportTicketMessage::getMessage($result,'REOPEN');
                        $link = 'index.php?option=com_jssupportticket&c=ticket&view=ticket&layout=ticketdetails&cid[]=' . $data['id'];
                        $this->setRedirect($link, $msg);
                        break;
                }
                break;
        }
    }
    
    function enforcedelete() {
        $result = $this->getJSModel('ticket')->enforcedeleteTicket();
        $msg = JSSupportticketMessage::getMessage($result,'TICKET');
        $link = "index.php?option=com_jssupportticket&c=ticket&layout=tickets";
        $this->setRedirect($link, $msg);
    }

    function delete() {
        $result = $this->getJSModel('ticket')->deleteTicket();
        $msg = JSSupportticketMessage::getMessage($result,'TICKET');
        $link = "index.php?option=com_jssupportticket&c=ticket&layout=tickets";
        $this->setRedirect($link, $msg);
    }

    function addnewticket() {
        $layoutName = JRequest::setVar('layout', 'formticket');
        $this->display();
    }

    function cancelticket() {
        $msg = JSSupportticketMessage::getMessage(CANCEL,'TICKET');
        $link = "index.php?option=com_jssupportticket&c=ticket&layout=tickets";
        $this->setRedirect($link, $msg);
    }

    function display($cachable = false, $urlparams = false) {
        $document = JFactory::getDocument();
        $viewName = JRequest::getVar('view', 'ticket');
        $layoutName = JRequest::getVar('layout', 'tickets');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $view->setLayout($layoutName);
        $view->display();
    }

    function editresponce() {
        $id = JRequest::getVar('id');
        $returnvalue = $this->getJSModel('ticket')->editResponceAJAX($id);
        echo $returnvalue;
        JFactory::getApplication()->close();
    }

    function saveresponceajax() {
        global $mainframe;
        $mainframe = JFactory::getApplication();

        $id = JRequest::getVar('id');
        $responce = JRequest::getVar('val', '', '', 'string', JREQUEST_ALLOWRAW);
        $returnvalue = $this->getJSModel('ticket')->saveResponceAJAX($id, $responce);
        if ($returnvalue != 1)
            $returnvalue = JText::_('Mail has not been send');
        echo $responce;
        $mainframe->close();
    }

    function deleteresponceajax() {
        $id = JRequest::getVar('id');
        $returnvalue = $this->getJSModel('ticket')->deleteResponceAJAX($id);
        if ($returnvalue == 1)
            $returnvalue = '<font color="green">' . JText::_('Mail has been deleted') . '</font>';
        else
            $returnvalue = '<font color="red">' . JText::_('Mail has not been deleted') . '</font>';
        echo $returnvalue;
        JFactory::getApplication()->close();
    }

}
?>

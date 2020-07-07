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

defined ('_JEXEC') or die('Not Allowed');
jimport('joomla.application.component.controller');

class JSSupportTicketControllerticket extends JSSupportTicketController{
	
	function __construct(){
		parent::__construct();
		$this->registerTask('add', 'edit');
	}

	function saveTicket() {
		$Itemid =  JRequest::getVar('Itemid');
		$data = JRequest::get('post');
		if($data['id'] <> '')
			$id = $data['id'];
		$result = $this->getJSModel('ticket')->storeTicket($data);
		if($result == SAVED){
			$link = 'index.php?option=com_jssupportticket&c=ticket&layout=mytickets&id='.$id.'&Itemid='.$Itemid;
		}elseif($result == SAVE_ERROR || $result == MESSAGE_EMPTY){
			JFactory::getApplication()->setUserState('com_jssupportticket.data',$data);
			$link = 'index.php?option=com_jssupportticket&c=ticket&layout=formticket&Itemid='.$Itemid;
		}		
        $msg = JSSupportTicketMessage::getMessage($result,'TICKET');
        $this->setRedirect(JRoute::_($link), $msg);
    }

    function actionticket() {
		$data = JRequest::get('POST');
		$Itemid =  JRequest::getVar('Itemid');
		$ticketid = $data['ticketid'];
		$action = $data['callfrom'];
		switch($action){
			case 'savemessage':
				$message = JRequest::getVar('message', '', 'post', 'string', JREQUEST_ALLOWRAW);
				$result = $this->getJSModel('ticketreply')->storeTicketReplies($ticketid, $message, $data['created'], $data);
				$msg = JSSupportTicketMessage::getMessage($result,'MESSAGE');
				$link = 'index.php?option=com_jssupportticket&c=ticket&layout=ticketdetail&id='.$ticketid.'&email='.$data['email'].'&Itemid='.$Itemid;
				$this->setRedirect(JRoute::_($link), $msg);
                break;
			case 'action':
				switch ($data['callaction']){
					case 3:
						$result = $this->getJSModel('ticket')->ticketClose($data['ticketid'],$data['created']);
						$msg = JSSupportTicketMessage::getMessage($result,'CLOSE');
						$link = 'index.php?option=com_jssupportticket&c=ticket&layout=ticketdetail&id='.$data['ticketid'].'&email='.$data['email'].'&Itemid'.$Itemid;
						$this->setRedirect(JRoute::_($link), $msg);
						break;
					case 8:
						$result = $this->getJSModel('ticket')->reopenTicket($data['ticketid'],$data['lastreply']);
						$msg = JSSupportTicketMessage::getMessage($result,'REOPEN');
						$link = 'index.php?option=com_jssupportticket&c=ticket&layout=ticketdetail&id='.$data['ticketid'].'&email='.$data['email'].'&Itemid'.$Itemid;
						$this->setRedirect(JRoute::_($link), $msg);
						break;
				}
			break;
		}
    }

	function saveresponceajax()  {
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$val = json_decode(JRequest::getVar('val'),true);
		$id = $val[0];
		$responce = $val[1];
		$result = $this->getJSModel('ticket')->saveResponceAJAX($id,$responce);
		$msg = JSSupportTicketMessage::getMessage($result,'MESSAGE');
		if($result == SAVED){
			$result = 1;
		}else{
			$result = '<font color="red">'.$msg.'</font>';
		}
		echo $result;
		$mainframe->close();
	}

	function editresponce()  {
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$id = JRequest::getVar('id');
		$result = $this->getJSModel('ticket')->editResponceAJAX($id);
		echo $result;
		$mainframe->close();
	}

	function deleteresponceajax() {
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$id = JRequest::getVar('id');
		$result = $this->getJSModel('ticket')->deleteResponceAJAX($id);
		$msg = JSSupportTicketMessage::getMessage($result,'MESSAGE');
		if ($result == DELETED){
			$result = '<font color="green">'.$msg.'</font>';
		}elseif($result == PERMISSION_ERROR){ 
			$result = '<font color="red">'.$msg.'</font>';
		}else{	
			$result = '<font color="red">'.$msg.'</font>';
		}	
		echo $result;
		$mainframe->close();
	}
	
	function getmytickets(){
		$data = JRequest::get('POST');
		$Itemid =  JRequest::getVar('Itemid');
		$email = $data['email'];
		$ticketid = $data['ticketid'];
		$model = $this->getJSModel('ticket');
		$result = $model->checkEmailAndTicketID($email,$ticketid);
	}
	
	function display($cachable = false, $urlparams = false){
		$document = JFactory::getDocument();
		$viewName = 'ticket';
		$layoutName = JRequest::getVar('layout', 'mytickets');
		$viewType = $document->getType();
		$view = $this->getView($viewName, $viewType);
		$view->setLayout($layoutName);
		$view->display();
	}
}
?>

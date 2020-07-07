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

jimport('joomla.application.component.model');
jimport('joomla.html.html');

class JSSupportTicketModelTicketreply extends JSSupportticketModel {

    function __construct() {
        parent::__construct();
    }

    function storeTicketReplies($ticketid, $message, $created, $data2) {
        if (!is_numeric($ticketid))
            return false;
        $eventtype = JText::_('Reply Ticket');
        $user = JSSupportTicketCurrentUser::getInstance();
        $row = $this->getTable('replies');
        $data['ticketid'] = $ticketid;
        $data['staffid'] = is_null($data2['staffid']) ? 0 : $data2['staffid'];
        $data['name'] = $uname;
        $data['message'] = $message;
        $data['status'] = 3;
        $data['created'] = $created;

        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            $return_value = false;
        }
        if (!$row->store()) {
            $this->getJSModel('systemerrors')->updateSystemErrors($row->getErrorMsg());
            $this->setError($this->_db->getErrorMsg());
            $return_value = false;
        }
        if (isset($return_value) && $return_value == false) {
            return POST_ERROR;
        }

        $replyattachmentid = $row->id;
		$ATTACHMENTRESULT = $this->getJSModel('attachments')->storeTicketAttachment($ticketid,$replyattachmentid);        
        if($ATTACHMENTRESULT !== true){
            return $ATTACHMENTRESULT;
        }

        if($user->getIsAdmin()){ // admin reply
            $result = $this->getJSModel('ticket')->updateStatus($ticketid, 3, $data2['created']);
            $this->getJSModel('ticket')->updateIsAnswered($ticketid,1);
            if(isset($data2['replystatus'])){
                $this->getJSModel('ticket')->ticketClose($ticketid ,$data2['created']);
            }
        }elseif(!$user->getIsGuest()){ // user reply
            $result = $this->getJSModel('ticket')->updateStatus($ticketid, 2, $data2['created']);
            $this->getJSModel('ticket')->updateIsAnswered($ticketid,0);
        }
        $this->getJSModel('ticket')->updateTicketLastReply($ticketid,$data2['created']);
        if ($result == false)
            return POST_ERROR;

        if($user->getIsAdmin()){
            $this->getJSModel('email')->sendMail(1,4,$ticketid); // Mailfor,reply,Ticketid [admin/staffmember]
        }else{
            $this->getJSModel('email')->sendMail(1,5,$ticketid); // Mailfor,reply,Ticketid [user reply]
        }

        return POSTED;
    }


}
?>
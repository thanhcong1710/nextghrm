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

jimport('joomla.application.component.model');
jimport('joomla.html.html');

class JSSupportticketModelEmail extends JSSupportTicketModel {

    function __construct(){
        parent::__construct();
    }
    
    function getFormData($id) {

        $db = $this->getDbo();
        $email;
        if (isset($id)) {
            if (!is_numeric($id))
                return False;
            $query = "SELECT * FROM `#__js_ticket_email` WHERE id =" . $id;
            $db->setQuery($query);
            $email = $db->loadObject();
        }
        $priority = $this->getJSModel('priority')->getPrioritiesForCombobx(JText::_('Select Priority'));
        $config = $this->getJSModel('config')->getConfigByFor('default');

        if (isset($email)) {
            $priorityid = $email->priorityid;
            $lists['priority'] = JHTML::_('select.genericList', $priority, 'priorityid', 'class="inputbox" ' . '', 'value', 'text', $priorityid);
        } else {
            $priorityid = '';
            $lists['priority'] = JHTML::_('select.genericList', $priority, 'priorityid', 'class="inputbox" ' . '', 'value', 'text', $config['priority']);
        }

        $result[0] = $email;
        $result[1] = '';
        $result[2] = $lists;

        return $result;
    }

    function storeEmail($data) {
        $row = $this->getTable('emails');
        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return SAVE_ERROR;
        }
        if (!$row->store()) {
            $this->getJSModel('systemerrors')->updateSystemErrors($row->getErrorMsg());
            $this->setError($this->_db->getErrorMsg());
            return SAVE_ERROR;
        }
        JSSupportticketMessage::$recordid = $row->id;
        return SAVED;
    }

    function getAllEmails($searchemail, $searchtype, $limitstart, $limit) {
        $type[] = array('value' => '', 'text' => JText::_('Select Email'));
        $type[] = array('value' => 1, 'text' => JText::_('Yes'));
        $type[] = array('value' => 0, 'text' => JText::_('No'));
        $lists['autoresponcetype'] = JHTML::_('select.genericList', $type, 'filter_autoresponcetype', 'class="inputbox" ' . '', 'value', 'text', $searchtype);
        $db = $this->getDbo();
        //For Total Record
        $query = "SELECT COUNT(id) From `#__js_ticket_email`";
        $db->setQuery($query);
        $total = $db->loadResult();

        $query = "SELECT email.id, email.email, email.autoresponce, email.created, email.update,priority.priority
					FROM `#__js_ticket_email` AS email 
					LEFT JOIN `#__js_ticket_priorities`AS priority ON priority.id=email.priorityid 
					WHERE email.status <> -1 ";
        if (isset($searchemail) && $searchemail <> '')
            $query .= " AND email.email LIKE " . $db->quote('%' . $searchemail . '%');
        if (isset($searchtype) && $searchtype <> '') {
            if (!is_numeric($searchtype))
                return False;
            $query .= " AND email.autoresponce =" . $searchtype;
        }
        $db->setQuery($query, $limitstart, $limit);
        $emails = $db->loadObjectList();
        if ($searchemail)
            $lists['searchemail'] = $searchemail;
        $result[0] = $emails;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function deleteEmail() {
        $row = $this->getTable('emails');
        $c_id = JRequest::getVar('cid', array(0), '', 'array');
        foreach ($c_id as $id) {
            if ($this->emailCanDelete($id) == true) {
                if (!$row->delete($id)) {
                    $this->setError($row->getErrorMsg());
                    return DELETE_ERROR;
                }
            }
        }
        return DELETED;
    }

    function emailCanDelete($id) {
        if (!is_numeric($id))
            return FALSE;
        $db = $this->getDBO();
        $query = "SELECT COUNT(id) FROM `#__js_ticket_email` WHERE id=" . $id;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return true;
        else
            return false;
    }
   
    function getEmailForCombobox($title = ''){
        $db= $this->getDbo();
        $query="SELECT id, email FROM `#__js_ticket_email` WHERE status = 1 ORDER BY email ASC";
        $db->setQuery($query);
        $rows=$db->loadObjectList();
        if ($db->getErrorNum()) {
            $this->getJSModel('systemerrors')->updateSystemErrors($db->getErrorMsg());
            return false;
        }
        $emaillist = array();
        if($title)
            $emaillist[]=array('value'=>'','text'=>$title);
        foreach ($rows as $row) {
            $emaillist[]=array('value'=>$row->id,'text'=>$row->email);
        }
        return $emaillist;
    }

    function sendMail($mailfor, $action, $id = null, $tablename = null) {
        if (!is_numeric($mailfor)) return false;
        if (!is_numeric($action)) return false;
        if ($id != null) if (!is_numeric($id)) return false;
        $config = $this->getJSModel('config')->getConfigs();
        switch ($mailfor) {
            case 1: // Mail For Tickets
                switch ($action) {
                    case 1: // New Ticket Created
                        $ticket = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $username = $ticket->name;
                        $subject = $ticket->subject;
                        $trackingid = $ticket->ticketid;
                        $email = $ticket->email;
                        $message = $ticket->message;
                        $matcharray = array(
                            '{USERNAME}' => $username,
                            '{SUBJECT}' => $subject,
                            '{TRACKINGID}' => $trackingid,
                            '{EMAIL}' => $email,
                            '{MESSAGE}' => $message
                        );                        
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;

                        // New ticket mail to User
                        $template = $this->getTemplateForEmail('ticket-new');
						//Parsing template
						$msgSubject = $template->subject;
						$msgBody = $template->body;
						$link = $this->setGuestUrl($trackingid,$email);
						$matcharray['{TICKETURL}'] = $link;
						$this->replaceMatches($msgSubject, $matcharray);
						$this->replaceMatches($msgBody, $matcharray);
						$msgBody .= '<input type="hidden" name="ticketid:' . $trackingid . '###" />';
						$this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        // New ticket mail to admin
                        if ($config['new_ticket_admin'] == 1) {
                            $adminEmailid = $config['admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $adminName = $this->getJoomlaNameByEmail($adminEmail);
                            $template = $this->getTemplateForEmail('ticket-new-admin');
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $link = $this->setAdminUrl($id);
                            $matcharray['{TICKETURL}'] = $link;
                            $matcharray['{USERNAME}'] = $adminName;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 2: // Close Ticket
                        $ticket = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $username = $ticket->name;
                        $subject = $ticket->subject;
                        $trackingid = $ticket->ticketid;
                        $email = $ticket->email;
                        $message = $ticket->message;
                        $matcharray = array(
                            '{USERNAME}' => $username,
                            '{SUBJECT}' => $subject,
                            '{TRACKINGID}' => $trackingid,
                            '{EMAIL}' => $email,
                            '{MESSAGE}' => $message
                        );
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('close-tk');
                        // Close ticket mail to admin
                        if ($config['ticket_close_admin'] == 1) {
                            $adminEmailid = $config['admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $link = $this->setAdminUrl($id);
                            $matcharray['{TICKETURL}'] = $link;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        if ($config['ticket_close_user'] == 1) {
                            $link = $this->setUserUrl($id);
                            $matcharray['{TICKETURL}'] = $link;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            
                            $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 3: // Delete Ticket
                    	$session = JFactory::getSession();
                        $trackingid = $session->get('ticketid');
                        $email = $session->get('ticketemail');
                        $subject = $session->get('ticketsubject');
                        $matcharray = array(
                            '{TRACKINGID}' => $trackingid,
                            '{SUBJECT}' => $subject
                        );
                        $object = $this->getSenderEmailAndName(null);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('delete-tk');
                        // Delete ticket mail to admin
                        if ($config['ticket_delete_admin'] == 1) {
                            $adminEmailid = $config['admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        if ($config['ticket_delete_user'] == 1) {
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 4: // Reply Ticket (Admin/Staff Member)
                        $ticket = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $username = $ticket->name;
                        $subject = $ticket->subject;
                        $trackingid = $ticket->ticketid;
                        $email = $ticket->email;
                        $message = $this->getJSModel('ticket')->getLatestReplyByTicketId($id);
                        $matcharray = array(
                            '{USERNAME}' => $username,
                            '{SUBJECT}' => $subject,
                            '{TRACKINGID}' => $trackingid,
                            '{EMAIL}' => $email,
                            '{MESSAGE}' => $message
                        );
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('responce-tk');
                        // Reply ticket mail to admin
                        if ($config['ticket_response_staff_admin'] == 1) {
                            $adminEmailid = $config['admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $link = $this->setAdminUrl($id);
                            $matcharray['{TICKETURL}'] = $link;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        if ($config['ticket_response_staff_user'] == 1) {
                            $link = $this->setUserUrl($id);
                            $matcharray['{TICKETURL}'] = $link;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $msgBody .= '<input type="hidden" name="ticketid:' . $trackingid . '###" />';
                            
                            $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 5: // Reply Ticket (Ticket Member)
                        $ticket = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $username = $ticket->name;
                        $subject = $ticket->subject;
                        $trackingid = $ticket->ticketid;
                        $email = $ticket->email;
                        $message = $this->getJSModel('ticket')->getLatestReplyByTicketId($id);
                        $matcharray = array(
                            '{USERNAME}' => $username,
                            '{SUBJECT}' => $subject,
                            '{TRACKINGID}' => $trackingid,
                            '{EMAIL}' => $email,
                            '{MESSAGE}' => $message
                        );
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('reply-tk');
                        // New ticket mail to admin
                        if ($config['ticket_reply_user_admin'] == 1) {
                            $adminEmailid = $config['admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $link = $this->setAdminUrl($id);
                            $matcharray['{TICKETURL}'] = $link;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        if ($config['ticket_reply_user_user'] == 1) {
                            $link = $this->setUserUrl($id);
                            $matcharray['{TICKETURL}'] = $link;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            
                            $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 6: // Lock Ticket 
                        $ticket = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $username = $ticket->name;
                        $subject = $ticket->subject;
                        $trackingid = $ticket->ticketid;
                        $email = $ticket->email;
                        $matcharray = array(
                            '{USERNAME}' => $username,
                            '{SUBJECT}' => $subject,
                            '{TRACKINGID}' => $trackingid,
                            '{EMAIL}' => $email
                        );
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('lock-tk');
                        // New ticket mail to admin
                        if ($config['ticket_lock_admin'] == 1) {
                            $adminEmailid = $config['admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $link = $this->setAdminUrl($id);
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $matcharray['{TICKETURL}'] = $link;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        if ($config['ticket_lock_user'] == 1) {
                            $link = $this->setUserUrl($id);
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $matcharray['{TICKETURL}'] = $link;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            
                            $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 7: // Unlock Ticket 
                        $ticket = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $username = $ticket->name;
                        $subject = $ticket->subject;
                        $trackingid = $ticket->ticketid;
                        $email = $ticket->email;
                        $matcharray = array(
                            '{USERNAME}' => $username,
                            '{SUBJECT}' => $subject,
                            '{TRACKINGID}' => $trackingid,
                            '{EMAIL}' => $email
                        );
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('unlock-tk');
                        // New ticket mail to admin
                        if ($config['ticket_unlock_admin'] == 1) {
                            $adminEmailid = $config['admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $link = $this->setAdminUrl($id);
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $matcharray['{TICKETURL}'] = $link;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        if ($config['ticket_unlock_user'] == 1) {
                            $link = $this->setUserUrl($id);
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $matcharray['{TICKETURL}'] = $link;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            
                            $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 8: // Markoverdue Ticket 
                        $ticket = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $trackingid = $ticket->ticketid;
                        $email = $ticket->email;
                        $subject = $ticket->subject;
                        $matcharray = array(
                            '{TRACKINGID}' => $trackingid,
                            '{SUBJECT}' => $subject
                        );
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('moverdue-tk');
                        // New ticket mail to admin
                        if ($config['ticket_overdue_admin'] == 1) {
                            $adminEmailid = $config['admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $link = $this->setAdminUrl($id);
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $matcharray['{TICKETURL}'] = $link;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        if ($config['ticket_overdue_user'] == 1) {
                            $link = $this->setUserUrl($id);
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $matcharray['{TICKETURL}'] = $link;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            
                            $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 9: // Mark in progress Ticket 
                        $ticket = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $trackingid = $ticket->ticketid;
                        $email = $ticket->email;
                        $subject = $ticket->subject;
                        $matcharray = array(
                            '{TRACKINGID}' => $trackingid,
                            '{SUBJECT}' => $subject
                        );
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('minprogress-tk');
                        // New ticket mail to admin
                        if ($config['ticket_progress_admin'] == 1) {
                            $adminEmailid = $config['admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $link = $this->setAdminUrl($id);
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $matcharray['{TICKETURL}'] = $link;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        if ($config['ticket_progress_user'] == 1) {
                            $link = $this->setUserUrl($id);
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $matcharray['{TICKETURL}'] = $link;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            
                            $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 10: // Ban email and close Ticket 
                        $ticket = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $trackingid = $ticket->ticketid;
                        $email = $ticket->email;
                        $subject = $ticket->subject;
                        $matcharray = array(
                            '{EMAIL_ADDRESS}' => $email,
                            '{SUBJECT}' => $subject,
                            '{TRACKINGID}' => $trackingid
                        );
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('banemailcloseticket-tk');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        // New ticket mail to admin
                        if ($config['ticker_ban_and_close_admin'] == 1) {
                            $adminEmailid = $config['admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        if ($config['ticker_ban_and_close_user'] == 1) {
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            
                            $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 11: // Priority change ticket 
                        $ticket = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $trackingid = $ticket->ticketid;
                        $subject = $ticket->subject;
                        $email = $ticket->email;
                        $Priority = $this->getJSModel('priority')->getPriorityById($ticket->priorityid);
                        $matcharray = array(
                            '{PRIORITY_TITLE}' => $Priority->priority,
                            '{SUBJECT}' => $subject,
                            '{TRACKINGID}' => $trackingid
                        );
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('pchnge-tk');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        // New ticket mail to admin                        
                        if ($config['ticket_priority_admin'] == 1) {
                            $link = $this->setAdminUrl($id);
                            $matcharray['{TICKETURL}'] = $link;
                            $adminEmailid = $config['admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        if ($config['ticket_priority_user'] == 1) {
                            $link = $this->setUserUrl($id);
                            $matcharray['{TICKETURL}'] = $link;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            
                            $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 12: // DEPARTMENT TRANSFER 
                        $ticket = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $trackingid = $ticket->ticketid;
                        $subject = $ticket->subject;
                        $email = $ticket->email;
                        $Department = $this->getJSModel('department')->getDepartmentById($ticket->departmentid);
                        $matcharray = array(
                            '{DEPARTMENT_TITLE}' => $Department,
                            '{SUBJECT}' => $subject,
                            '{TRACKINGID}' => $trackingid
                        );
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('deptrans-tk');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        // New ticket mail to admin
                        if ($config['ticket_department_transfer_admin'] == 1) {
                            $adminEmailid = $config['admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        if ($config['ticket_department_transfer_user'] == 1) {
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            
                            $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                }
                break;
            case 2: // Ban Email
                switch ($action) {
                    case 1: // Ban Email
                        if ($tablename != null)
                            $banemailRecord = $this->getRecordByTablenameAndId($tablename, $id);
                        else
                            $banemailRecord = $this->getRecordByTablenameAndId('js_ticket_email_banlist', $id);
                        $email = $banemailRecord->email;
                        $matcharray = array(
                            '{EMAIL_ADDRESS}' => $email
                        );
                        $object = $this->getDefaultSenderEmailAndName();
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('banemail-tk');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        // New ticket mail to admin
                        if ($config['ticket_ban_email_admin'] == 1) {
                            $adminEmailid = $config['admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        if ($config['ticket_ban_email_user'] == 1) {
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            
                            $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 2: // Unban Email
                        if ($tablename != null)
                            $ticket = $this->getRecordByTablenameAndId($tablename, $id);
                        else
                            $ticket = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $email = $ticket->email;
                        $matcharray = array(
                            '{EMAIL_ADDRESS}' => $email
                        );
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('unbanemail-tk');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        // New ticket mail to admin
                        if ($config['unban_email_admin'] == 1) {
                            $adminEmailid = $config['admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        if ($config['unban_email_user'] == 1) {
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            
                            $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                }
                break;
            case 3: // Sending email alerts on mail system
                switch ($action) {
                    case 1: // Store message
                        $mailRecord = $this->getMailRecordById($id);
                        $matcharray = array(
                            '{STAFF_MEMBER_NAME}' => $mailRecord->sendername,
                            '{SUBJECT}' => $mailRecord->subject,
                            '{MESSAGE}' => $mailRecord->message
                        );
                        $object = $this->getSenderEmailAndName(null);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('mail-new');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $email = $mailRecord->receveremail;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        
                        $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        break;
                    case 2: // Store reply
                        $mailRecord = $this->getMailRecordById($id, 1);
                        $matcharray = array(
                            '{STAFF_MEMBER_NAME}' => $mailRecord->sendername,
                            '{SUBJECT}' => $mailRecord->subject,
                            '{MESSAGE}' => $mailRecord->message
                        );
                        $object = $this->getSenderEmailAndName(null);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('mail-rpy');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $email = $mailRecord->receveremail;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        
                        $this->sendEmail($email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        break;
                }
                break;
        }
    }

    private function getRecordByTablenameAndId($tablename, $id) {
        if (!is_numeric($id))
            return false;
        $db = JFactory::getDBO();
        $query = "SELECT * FROM `#__".$tablename."` WHERE id = " . $id;
        $db->setQuery($query);
        if (!$db->query()) {
            $this->getJSModel('systemerrors')->updateSystemErrors($db->getErrorMsg());
            $db->setError($db->getErrorMsg());
            return false;
        }
        $record = $db->loadObject();
        return $record;
    }

    private function replaceMatches(&$string, $matcharray) {
        foreach ($matcharray AS $find => $replace) {
            $string = str_replace($find, $replace, $string);
        }
    }

    private function getSenderEmailAndName($id) {
        if ($id) {
            if (!is_numeric($id))
                return false;
            $db = JFactory::getDBO();
            $query = "SELECT email.email,email.name
                        FROM `#__js_ticket_tickets` AS ticket 
                        JOIN `#__js_ticket_departments` AS department ON department.id = ticket.departmentid 
                        JOIN `#__js_ticket_email` AS email ON email.id = department.emailid 
                        WHERE ticket.id = " . $id;
            $db->setQuery($query);
            if (!$db->query()) {
                $this->getJSModel('systemerrors')->updateSystemErrors($db->getErrorMsg());
                $db->setError($db->getErrorMsg());
                return false;
            }
            $email = $db->loadObject();
        } else {
            $email = '';
        }
        if (empty($email)) {
            $email = $this->getDefaultSenderEmailAndName();
        }
        return $email;
    }

    private function getDefaultSenderEmailAndName() {
        $config = $this->getJSModel('config')->getConfigByFor('email');
        $emailid = $config['alert_email'];
        $db = JFactory::getDbo();
        $query = "SELECT email,name FROM `#__js_ticket_email` WHERE id = " . $emailid;
        $db->setQuery($query);
        $email = $db->loadObject();
        return $email;
    }

    function getEmailById($id) {
        if (!is_numeric($id))
            return false;
        $db = $this->getDBO();
        $query = "SELECT email  FROM `#__js_ticket_email` WHERE id = " . $id;
        $db->setQuery($query);
        $email = $db->loadResult();
        return $email;
    }

    private function getTemplateForEmail($templatefor) {
        $db = $this->getDBO();
        $query = "SELECT * FROM `#__js_ticket_emailtemplates` WHERE templatefor = '" . $templatefor . "'";
        $db->setQuery($query);
        $template = $db->loadObject();
        return $template;
    }

     private function sendEmail($recevierEmail, $subject, $body, $senderEmail, $senderName, $attachments = '', $action) {
        /*
          $attachments = array( WP_CONTENT_DIR . '/uploads/file_to_attach.zip' );
          $headers = 'From: My Name <myname@example.com>' . "\r\n";
          wp_mail('test@example.org', 'subject', 'message', $headers, $attachments );

          $action
          For which action of $mailfor you want to send the mail
          1 => New Ticket Create
          2 => Close Ticket
          3 => Delete Ticket
          4 => Reply Ticket (Admin/Staff Member)
          5 => Reply Ticket (Ticket member)
         */
        
        // switch ($action) {
        //     case 1:
        //         do_action('jsst-beforeemailticketcreate', $recevierEmail, $subject, $body, $senderEmail);
        //         break;
        //     case 2:
        //         do_action('jsst-beforeemailticketreply', $recevierEmail, $subject, $body, $senderEmail);
        //         break;
        //     case 3:
        //         do_action('jsst-beforeemailticketclose', $recevierEmail, $subject, $body, $senderEmail);
        //         break;
        //     case 4:
        //         do_action('jsst-beforeemailticketdelete', $recevierEmail, $subject, $body, $senderEmail);
        //         break;
        // }
        
        $headers = 'From: ' . $senderName . ' <' . $senderEmail . '>' . "\r\n";
        $body = preg_replace('/\r?\n|\r/', '<br/>', $body);
        $body = str_replace(array("\r\n", "\r", "\n"), "<br/>", $body);
        $body = nl2br($body);
        // echo '<br/>'.$subject.'<br/>';
        // echo $body.'<br/>';
        $message = JFactory::getMailer();
        $message->addRecipient($recevierEmail);
        $message->setSubject($subject);
        $siteAddress = JURI::base();
        //echo 'admin'.$adminEmail.$body;
        $message->setBody($body);
        $sender = array( $senderEmail, $senderName );
        $message->setSender($sender);
        $message->IsHTML(true);
        $sent = $message->send();
        return $sent;
    }

    function getMailRecordById($id, $replyto = null) {
        if (!is_numeric($id))
            return false;
        $db = JFactory::getDBO();
        if ($replyto == null) {
            $query = "SELECT mail.subject,mail.message,CONCAT(staff.name,' ',staff.lastname) AS sendername 
                        FROM `#__js_ticket_mail` AS mail 
                        JOIN `#__js_ticket_staff` AS staff ON staff.id = mail.from 
                        WHERE mail.id = " . $id;
        } else {
            $query = "SELECT mail.subject,reply.message,CONCAT(staff.name,' ',staff.lastname) AS sendername 
                        FROM `#__js_ticket_mail` AS reply 
                        JOIN `#__js_ticket_mail` AS mail ON mail.id = reply.replytoid 
                        JOIN `#__js_ticket_staff` AS staff ON staff.id = reply.from 
                        WHERE reply.id = " . $id;
        }
        $db->setQuery($query);
        if (!$db->query()) {
            $this->getJSModel('systemerrors')->updateSystemErrors($db->getErrorMsg());
            $db->setError($db->getErrorMsg());
            return false;
        }
        $result = $db->loadObject();
        return $result;
    }
	
	function getJoomlaNameByEmail($emailaddress){
		$db = JFactory::getDbo();
		$query = "SELECT name FROM `#__users` WHERE email = '$emailaddress'";
		$db->setQuery($query);
		$name = $db->loadResult();
		return $name;
	}

    function getURL(){
        $url = JURI::root();
        $url .= 'index.php?option=com_jssupportticket';
        return $url;
    }
    function getAdminURL(){
        $url = JURI::root();
        $url .= 'administrator/index.php?option=com_jssupportticket';
        return $url;
    }
    function setGuestUrl($trackingid,$email){
        $url = $this->getURL();
        $url .= '&c=ticket&layout=ticketdetail&ticketid='.$trackingid.'&email='.$email;
        $link = '<a href="'.$url.'" target="_blank">'.JText::_('Ticket Detail').'</a>';
        return $link;
    }
    function setUserUrl($id){
        $url = $this->getURL();
        $url .= '&c=ticket&layout=ticketdetail&id='.$id;
        $link = '<a href="'.$url.'" target="_blank">'.JText::_('Ticket Detail').'</a>';
        return $link;
    }
    function setAdminUrl($id){
        $url = $this->getAdminURL();
        $url .= '&c=ticket&layout=ticketdetails&cid='.$id;
        $link = '<a href="'.$url.'" target="_blank">'.JText::_('Ticket Detail').'</a>';
        return $link;
    }
}?>

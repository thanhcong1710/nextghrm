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

class JSSupportticketModelTicket extends JSSupportTicketModel {

    var $activity_log;

    function __construct() {
        parent::__construct();
    }

    function storeTicket($data){
        $user = JSSupportTicketCurrentUser::getInstance();
        $eventtype = JText::_('New Ticket');

        //for new ticket case
        if (($data['id']) == '')
            $data['ticketid'] = $this->getTicketId();
        $row = $this->getTable('tickets');
        $data['message'] = JRequest::getVar('message', '', 'post', 'string', JREQUEST_ALLOWRAW);
        if(!$user->getIsAdmin())
            $data['uid'] = $user->getId();

        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            $return_value = false;
        }
        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return MESSAGE_EMPTY;
        }
        if (!$row->store()) {
            $this->getJSModel('systemerrors')->updateSystemErrors($row->getErrorMsg());
            $this->setError($this->_db->getErrorMsg());
            $return_value = false;
        }

        if (isset($return_value) && $return_value == false) {
            return SAVE_ERROR;
        }

        $ticketid = $row->id;
        $ATTACHMENTRESULT = $this->getJSModel('attachments')->storeTicketAttachment($ticketid);
        if($ATTACHMENTRESULT !== true){
            return $ATTACHMENTRESULT;
        }

        $this->getJSModel('userfields')->storeUserFieldData($data, $ticketid);
        if ($data['id'] == '')  // only for new ticket
            $this->getJSModel('email')->sendMail(1,1,$row->id); // Mailfor,Create Ticket,Ticketid

        JSSupportTicketMessage::$recordid = $ticketid;
        return SAVED;
    }

    function getAdminMyTickets($searchsubject, $searchfrom, $searchfromemail, $searchticketid, $listtype,$sortby,$limitstart, $limit) {
        $db = $this->getDBO();
        $user = JSSupportTicketCurrentUser::getInstance();
        // $listtype == 1  - open
        // $listtype == 2  - answerd
        // $listtype == 4  - close
        // $listtype == 5  - all tickets

        $query = "SELECT ticket.*, dep.departmentname AS departmentname, priority.priority AS priority, priority.prioritycolour AS prioritycolour
                  FROM `#__js_ticket_tickets` AS ticket
                  JOIN `#__js_ticket_priorities` AS priority ON ticket.priorityid = priority.id
                  LEFT JOIN `#__js_ticket_departments` AS dep ON ticket.departmentid = dep.id
                  WHERE 1=1";
        $wherequery = '';
        if ($searchsubject <> '')
            $wherequery .= " AND ticket.subject LIKE " . $db->quote('%' . $searchsubject . '%');
        if ($searchfrom <> '')
            $wherequery .= " AND ticket.name LIKE " . $db->quote('%' . $searchfrom . '%');
        if ($searchfromemail <> '')
            $wherequery .= " AND ticket.email LIKE " . $db->quote('%' . $searchfromemail . '%');
        if ($searchticketid <> '')
            $wherequery .= " AND ticket.ticketid LIKE " . $db->quote('%' . $searchticketid . '%');
        $query .= $wherequery;
        switch ($listtype) {
            case 1:
                $query .= " AND ticket.status != 4 ";
                break;
            case 2:
                $query .= " AND ticket.status != 4 AND ticket.isanswered = 1 ";
                break;
            case 4:
                $query .= " AND ticket.status = 4  ";
                break;
            case 5: // Admin all tickets
                $query .= " ";
                break;
        }
        $query .= ' ORDER BY '.$sortby;
        $db->setQuery($query, $limitstart, $limit);
        $tickets = $db->loadObjectList(); // Tickets
        $ticketinfo = array();
        $config = $this->getJSModel('config')->getConfigs();
        if($config['show_count_tickets'] == 1){
            $query = "SELECT COUNT(ticket.id) FROM `#__js_ticket_tickets` AS ticket WHERE ticket.status != 4";
            $query .= $wherequery;
            $db->setQuery($query);
            $ticketinfo['open'] = $db->loadResult(); // Open Tickets

            $query = "SELECT COUNT(ticket.id) FROM `#__js_ticket_tickets` AS ticket WHERE ticket.status = 4";
            $query .= $wherequery;
            $db->setQuery($query);
            $ticketinfo['close'] = $db->loadResult(); // Closed Tickets

            $query = "SELECT COUNT(ticket.id) FROM `#__js_ticket_tickets` AS ticket WHERE status != 4 AND isanswered = 1";
            $query .= $wherequery;
            $db->setQuery($query);
            $ticketinfo['isanswered'] = $db->loadResult(); // IsAnswered Tickets

            $query = "SELECT COUNT(ticket.id) FROM `#__js_ticket_tickets` AS ticket WHERE 1 = 1";
            $query .= $wherequery;
            $db->setQuery($query);
            $ticketinfo['mytickets'] = $db->loadResult(); // My Tickets
        }

        $total = 0;
        switch ($listtype) {
            case 1:
                $total = $ticketinfo['open'];    
                break;
            case 2:
                $total = $ticketinfo['isanswered'];
                break;
            case 4:
                $total = $ticketinfo['close'];
                break;
            case 5:
                $total = $ticketinfo['mytickets'];  
                break;
        }

        $lists['searchsubject'] = $searchsubject;
        $lists['searchfrom'] = $searchfrom;
        $lists['searchfromemail'] = $searchfromemail;
        $lists['searchticket'] = $searchticketid;
        $result[0] = $tickets;
        $result[1] = $total;
        $result[2] = $lists;
        $result[3] = $ticketinfo;
        return $result;
    }

    function getusersearchajax() {
        $db = JFactory::getDbo();
        $name = JRequest::getVar('name','');
        $username = JRequest::getVar('username','');
        $emailaddress = JRequest::getVar('emailaddress','');
        $query = "SELECT DISTINCT user.ID AS userid, user.name AS displayname, user.email AS email, user.username AS username
                FROM `#__users` AS user WHERE user.name LIKE '%$name%' AND user.username LIKE '%$username%' AND user.email LIKE '%$emailaddress%'";
        $canloadresult=false;
        if($name!=''){
            $canloadresult=true;
        }
        if($username!=''){
            $canloadresult=true;
        }
        if($emailaddress!=''){
            $canloadresult=true;
        }
        $result='';
        if($canloadresult){
            $db->setQuery($query);
            $users = $db->loadObjectList();
            if(!empty($users)){
                $result ='
                    <div class="js-col-md-2 js-title">'.JText::_('User id').'</div>
                    <div class="js-col-md-3 js-title">'.JText::_('Username').'</div>
                    <div class="js-col-md-4 js-title">'.JText::_('Email address').'</div>
                    <div class="js-col-md-3 js-title">'.JText::_('Name').'</div>
                    <div id="records-inner">';
                foreach ($users AS $user) {
                    $result .='
                        <div class="user-records-wrapper js-value" style="display:inline-block;width:100%;">
                            <div class="js-col-xs-12 js-col-md-2">
                                <span class="js-user-title-xs">'.JText::_('User id').' : </span>'.$user->userid.'
                            </div>                            
                            <div class="js-col-xs-12 js-col-md-3">
                                <span class="js-user-title-xs">'.JText::_('Username').' : </span>';
                                    $result .='<a href="#" class="js-userpopup-link" data-id="'.$user->userid.'" data-email="'.$user->email.'" data-name="'.$user->displayname.'">'.$user->username.'</a> </div>';
                            $result .=
                            '<div class="js-col-xs-12 js-col-md-4">
                                <span class="js-user-title-xs">'.JText::_('Email address').' : </span>'.$user->email.'
                            </div>
                            <div class="js-col-xs-12 js-col-md-3">
                                <span class="js-user-title-xs">'.JText::_('Display name').' : </span>'.$user->displayname.'
                            </div>
                        </div></div>';
                }
            }else{
                $result= messageslayout::getRecordNotFound();
            }
        }else{ // reset button
            $result ='<div class="js-staff-searc-desc">'.JText::_('Use Search Feature To Select The User').'</div>';
        }
        return $result;
    }
    private function ticketMultiSearch($searchkeys){
        $inquery="";
        if(!empty($searchkeys))
            if(isset($searchkeys['filter_ticketsearchkeys']) && !empty($searchkeys['filter_ticketsearchkeys'])){
                $keys = $searchkeys['filter_ticketsearchkeys'];
                if (strlen($keys) == 11)
                    $inquery = " AND ticket.ticketid = '$keys'";
                else if (strpos($keys, '@') && strpos($keys, '.'))
                    $inquery = " AND ticket.email LIKE '%$keys%'";
                else
                    $inquery = " AND ticket.subject LIKE '%$keys%'";
                $result['searchkeys'] = $keys;
            }else{
                if(isset($searchkeys['filter_ticketid']) && !empty($searchkeys['filter_ticketid'])){
                    $inquery =" AND ticket.ticketid = '".$searchkeys['filter_ticketid']."'";
                    $result['ticketid'] = $searchkeys['filter_ticketid'];
                }
                if(isset($searchkeys['filter_from']) && !empty($searchkeys['filter_from'])){
                    $inquery .=" AND ticket.name LIKE '%".$searchkeys['filter_from']."%'";
                    $result['from'] = $searchkeys['filter_from'];
                }
                if(isset($searchkeys['filter_email']) && !empty($searchkeys['filter_email'])){
                    $inquery .=" AND ticket.email LIKE '%".$searchkeys['filter_email']."%'";
                    $result['email'] = $searchkeys['filter_email'];
                }
                if(isset($searchkeys['filter_department']) && !empty($searchkeys['filter_department'])){
                    $inquery .=" AND ticket.departmentid =".$searchkeys['filter_department'];
                    $result['department'] = $searchkeys['filter_department'];
                }
                if(isset($searchkeys['filter_priority']) && !empty($searchkeys['filter_priority'])){
                    $inquery .=" AND ticket.priorityid = ".$searchkeys['filter_priority'];
                    $result['priority'] = $searchkeys['filter_priority'];
                }
                if(isset($searchkeys['filter_subject']) && !empty($searchkeys['filter_subject'])){
                    $inquery .=" AND ticket.subject LIKE '%".$searchkeys['filter_subject']."%'";
                    $result['subject'] = $searchkeys['filter_subject'];
                }
                if(isset($searchkeys['filter_datestart']) && !empty($searchkeys['filter_datestart'])){
                    $inquery .=" AND DATE(ticket.created) >= '".$searchkeys['filter_datestart']."' ";
                    $result['datestart'] = $searchkeys['filter_datestart'];
                }
                if(isset($searchkeys['filter_dateend']) && !empty($searchkeys['filter_dateend'])){
                    $inquery .=" AND DATE(ticket.created) <= '".$searchkeys['filter_dateend']."' ";
                    $result['dateend'] = $searchkeys['filter_dateend'];
                }
                if($inquery=="")
                    $result['iscombinesearch'] = false;
                else
                    $result['iscombinesearch'] = true;

            }
        $result['inquery'] = $inquery;
        return $result;
    }

    function getUserMyTickets($uid,$listtype,$searchticketid,$sortby,$limitstart,$limit) {
        if(!is_numeric($uid)) return false;
        $db = $this->getDBO();
        $query = "SELECT COUNT(id) FROM `#__js_ticket_tickets` AS ticket WHERE ticket.uid = ".$db->quote($uid);
        $wherequery = '';
        $listquery = '';
        if($searchticketid <> ''){
            if (strlen($searchticketid) == 11)
                $wherequery .= " AND ticket.ticketid = '$searchticketid'";
            else if (strpos($searchticketid, '@') && strpos($searchticketid, '.'))
                $wherequery .= " AND ticket.email LIKE '%$searchticketid%'";
            else
                $wherequery .= " AND ticket.subject LIKE '%$searchticketid%'";
        }
        $query .= $wherequery;
        switch ($listtype) {
            case 1:
                $listquery .= " AND ticket.status != 4 ";
            break;
            case 3:
                $listquery .= " AND ticket.status = 3 ";
            break;
            case 4:
                $listquery .= " AND ticket.status = 4";
            break;
            case 5:
                $listquery .= " ";
            break;
        }
        $query .= $listquery;
        $db->setQuery($query);
        $total = $db->loadResult(); //Total Tickets
        $query = "SELECT ticket.*,dep.departmentname AS departmentname, priority.priority AS priority, priority.prioritycolour AS prioritycolour, 
                    (SELECT COUNT(attach.id) From `#__js_ticket_attachments` AS attach WHERE attach.ticketid = ticket.id) AS attachments
                        FROM `#__js_ticket_tickets` AS ticket
                        JOIN `#__js_ticket_priorities` AS priority ON ticket.priorityid = priority.id
                        LEFT JOIN `#__js_ticket_departments` AS dep ON ticket.departmentid = dep.id
                        WHERE ticket.uid =".$uid;
        $query .= $wherequery;
        $searchkeys = JRequest::get('post');
        $multisearchquery = $this->ticketMultiSearch($searchkeys);
        $departments = $this->getJSModel('department')->getDepartmentsForCombobox();
        $priorities = $this->getJSModel('priority')->getPrioritiesForCombobx(JText::_('Select Priority'));
        $departmentid = isset($multisearchquery['department']) ? $multisearchquery['department'] : '';
        $priorityid = isset($multisearchquery['priority']) ? $multisearchquery['priority'] : '';

        $lists['departments'] = JHTML::_('select.genericList', $departments, 'filter_department', '', 'value', 'text',$departmentid);
        $lists['priorities'] = JHTML::_('select.genericList', $priorities, 'filter_priority', '', 'value', 'text',$priorityid);

        $query .= $multisearchquery['inquery'];

        $query .= $listquery;
        $query .= " ORDER BY ".$sortby;

        $db->setQuery($query,$limitstart,$limit);
        $result = $db->loadObjectList(); //Tickets
        $ticketinfo = array();
        $config = $this->getJSModel('config')->getConfigs();
        if($config['show_count_tickets'] == 1){
            $query = "SELECT COUNT(ticket.id) FROM `#__js_ticket_tickets` AS ticket WHERE ticket.status != 4 AND ticket.uid = $uid ";
            $query .= $wherequery;
            $db->setQuery($query);
            $ticketinfo['open'] = $db->loadResult(); // Open Tickets

            $query = "SELECT COUNT(ticket.id) FROM `#__js_ticket_tickets` AS ticket WHERE ticket.status = 4 AND ticket.uid = $uid ";
            $query .= $wherequery;
            $db->setQuery($query);
            $ticketinfo['close'] = $db->loadResult(); // Closed Tickets

            $query = "SELECT COUNT(ticket.id) FROM `#__js_ticket_tickets` AS ticket WHERE status != 4 AND isanswered = 1 AND ticket.uid = $uid ";
            $query .= $wherequery;
            $db->setQuery($query);
            $ticketinfo['isanswered'] = $db->loadResult(); // IsAnswered Tickets

            $query = "SELECT COUNT(ticket.id) FROM `#__js_ticket_tickets` AS ticket WHERE ticket.uid = $uid ";
            $query .= $wherequery;
            $db->setQuery($query);
            $ticketinfo['mytickets'] = $db->loadResult(); // My Tickets
        }
        if($total == '') $total = 0;
        $lists['searchticket'] = $searchticketid;
        $return[0] = $result;
        $return[1] = $total;
        $return[2] = $ticketinfo;
        $return[3] = $lists;        
        $return[4] = $multisearchquery;
        return $return;
    }

    function getFormData($id,$data) {
        if($id) if (!is_numeric($id)) return false;
        $db = $this->getDBO();
        $user = JSSupportTicketCurrentUser::getInstance();

        $departments = $this->getJSModel('department')->getDepartmentsForCombobox();
        $priorities = $this->getJSModel('priority')->getPrioritiesForCombobx();
        if (isset($id) && $id <> '') {
            $query = "SELECT ticket.*,user.username AS uname 
                        FROM `#__js_ticket_tickets` AS ticket 
                        LEFT JOIN `#__users` AS user ON user.id = ticket.uid
                        WHERE ticket.id = " . $db->quote($id);
            $db->setQuery($query);
            $editticket = $db->loadObject();
        }
        if (isset($id) && $id <> '') {
            $lists['departments'] = JHTML::_('select.genericList', $departments, 'departmentid', 'class="inputbox" ' . '', 'value', 'text', $editticket->departmentid);
            $lists['priorities'] = JHTML::_('select.genericList', $priorities, 'priorityid', 'class="inputbox required" ' . '', 'value', 'text', $editticket->priorityid);
        } else {
            $query = "SELECT id FROM `#__js_ticket_priorities` WHERE isdefault = 1";
            $db->setQuery($query);
            $priority = $db->loadObject();
            $departmentid = isset($data['departmentid']) ? $data['departmentid'] : '';
            $priorityid = isset($data['priorityid']) ? $data['priorityid'] : $priority->id;
            $lists['departments'] = JHTML::_('select.genericList', $departments, 'departmentid', 'class="inputbox" ' . '', 'value', 'text', $departmentid);
            $lists['priorities'] = JHTML::_('select.genericList', $priorities, 'priorityid', 'class="inputbox required" ' . '', 'value', 'text', $priorityid);
        }

        $model_userfields = $this->getJSModel('userfields');
        if (isset($editticket))
            $result[0] = $editticket;
        $result[1] = '';
        $result[2] = $lists;
        $result[3] = $model_userfields->getUserFieldsForForm(1, $id);
        $result[4] = $model_userfields->getFieldsOrderingforForm(1);

        return $result;
    }
    function getTicketDetailById($id, $uid = '') {
        if (!is_numeric($id)) return false;
        if($uid) if(!is_numeric($uid)) return false;
        $db = $this->getDbo();
        $result = array();

        $query = "SELECT ticket.*,department.departmentname AS departmentname ,priority.priority AS priority,priority.prioritycolour AS prioritycolour,
            attach.filename,attach.filesize,
            (SELECT COUNT(id) FROM `#__js_ticket_attachments` WHERE ticketid = ticket.id AND replyattachmentid = 0) AS count
            FROM `#__js_ticket_tickets` AS ticket
            JOIN `#__js_ticket_priorities` AS priority ON ticket.priorityid = priority.id
            LEFT JOIN `#__js_ticket_departments` AS department ON ticket.departmentid = department.id
            LEFT JOIN `#__js_ticket_attachments` AS attach ON ticket.id = attach.ticketid AND attach.replyattachmentid = 0
            WHERE ticket.id=" . $id;
        if($uid) $query .= " AND ticket.uid = $uid";            
        $db->setQuery($query);
        $ticketdetails = $db->loadObjectList();
        // in replies staffid used as joomla userid
        $query = "SELECT replies.*, attachment.filename AS filename, attachment.filesize AS filesize, user.name AS name,
                 (SELECT count(id) FROM `#__js_ticket_attachments` WHERE ticketid = replies.ticketid AND replyattachmentid = replies.id) AS count
                 FROM`#__js_ticket_replies` AS replies
                 LEFT JOIN`#__users` AS user ON user.id = replies.staffid
                 LEFT JOIN `#__js_ticket_attachments` AS attachment ON replies.ticketid = attachment.ticketid AND replies.id = attachment.replyattachmentid
                 WHERE replies.ticketid = " . $id . " ORDER BY replies.created ASC";
        $db->setQuery($query);
        $replies = $db->loadObjectList();

        $app = JFactory::getApplication();

        if ($app->isAdmin()){
            $departments = $this->getJSModel('department')->getDepartmentsForCombobox();
            $priorities = $this->getJSModel('priority')->getPrioritiesForCombobx(JText::_("Select Priority"));
            $lists['departments'] = JHTML::_('select.genericList', $departments, 'departmentid', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['priorities'] = JHTML::_('select.genericList', $priorities, 'priorityid', 'class="inputbox"', 'value', 'text', '');
        }

        $model_userfields = $this->getJSModel('userfields');
        $result[0] = $ticketdetails[0];
        $result[2] = $replies;
        if(isset($lists)) $result[3] = $lists;
        $result[6] = $ticketdetails;
        $result[7] = $model_userfields->getUserFieldsForView(1, $id);
        return $result;
    }

    function ticketClose($ticketid ,$created) {
        if (!is_numeric($ticketid))
            return false;
        $user = JSSupportTicketCurrentUser::getInstance();
        if(!$user->getIsAdmin()){
            if($user->getIsGuest()){
                $email = JRequest::getVar('email');
                $userTicket = $this->checkEmailAndTicketID($email,$ticketid);
            }else{
                $uid = $user->getId();
                $userTicket = $this->checkTicketIdAndUid($uid,$ticketid);
            }
            if (!$userTicket) {
                return OTHER_USER_TASK;
            }
        }

        $row = $this->getTable('tickets');
        $data['id'] = $ticketid;
        $data['reopened'] = '';
        $data['status'] = 4;
        $data['closed'] = $created;
        $data['update'] = $created;

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
            return TICKET_ACTION_ERROR;
        }
        $this->getJSModel('email')->sendMail(1,2,$ticketid); // Mailfor,Close Ticket,Ticketid
        
        return TICKET_ACTION_OK;
    }

    function reopenTicket($ticketid, $lastreply) {
        if (!is_numeric($ticketid))
            return false;
        $eventtype = JText::_('Reopen Ticket');
        $user = JSSupportTicketCurrentUser::getInstance();
        if(!$user->getIsAdmin()){
            $canreopen = $this->checkCanReopenTicket($ticketid, $lastreply);
            if ($canreopen == false) {
                return TIME_LIMIT_END;
            }
            if($user->getIsGuest()){
                $email = JRequest::getVar('email');
                $userTicket = $this->checkEmailAndTicketID($email,$ticketid);
            }else{
                $uid = $user->getId();
                $userTicket = $this->checkTicketIdAndUid($uid,$ticketid);
            }
            if (!$userTicket) {
                return OTHER_USER_TASK;
            }
        }

        $row = $this->getTable('tickets');
        $data['id'] = $ticketid;
        $data['status'] = 0;
        $data['isanswered'] = 0;
        $data['reopened'] = date('Y-m-d H:i:s');
        $data['update'] = date('Y-m-d H:i:s');

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
            return TICKET_ACTION_ERROR;
        }
        return TICKET_ACTION_OK;
    }

    function changeTicketPriority($ticketid, $priorityid, $created) {
        if (!is_numeric($ticketid))
            return false;
        if (!is_numeric($priorityid))
            return false;
        $row = $this->getTable('tickets');
        $data['id'] = $ticketid;
        $data['priorityid'] = $priorityid;
        $data['update'] = $created;
        if (!$row->bind($data)) {
            $this->setError($row->_db->getErrorMsg());
            $return_value = false;
        }
        if (!$row->store()) {
            $this->getJSModel('systemerrors')->updateSystemErrors($row->getErrorMsg());
            $this->setError($row->_db->getErrorMsg());
            $return_value = false;
        }
        if (isset($return_value) && $return_value == false) {
            return PRIORITY_CHANGE_ERROR;
        }
        $this->getJSModel('email')->sendMail(1,11,$ticketid); // Mailfor,priority change,Ticketid
        return PRIORITY_CHANGED;
    }

    function checkCanReopenTicket($ticketid, $lastreply) {
        if (!is_numeric($ticketid))
            return false;
        $config_ticket = $this->getJSModel('config')->getConfigByFor('ticket');
        $days = $config_ticket['ticket_reopen_within_days'];
        if (!$lastreply)
            $lastreply = date('Y-m-d H:i:s');
        $date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($lastreply)) . " +" . $days . " day"));
        if ($date < date('Y-m-d H:i:s'))
            return false;
        else
            return true;
    }

    function getTicketUserNameById($ticketid) {
        if (!is_numeric($ticketid))
            return false;
        $db = $this->getDbo();
        $query = "SELECT ticket.name From `#__js_ticket_tickets` AS ticket WHERE ticket.id = " . $ticketid;
        $db->setQuery($query);
        $name = $db->loadResult();
        return $name;
    }

    function updateStatus($id, $status, $created) {
        if (!is_numeric($id))
            return false;
        $row = $this->getTable('tickets');
        $data['id'] = $id;
        $data['status'] = $status; // Ticket Closed
        $data['closed'] = $created;
        $data['update'] = $created;

        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->store()) {
            $this->getJSModel('systemerrors')->updateSystemErrors($row->getErrorMsg());
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        return true;
    }

    function getTicketIdForEmail($id) {
        if (!is_numeric($id))
            return false;
        $db = $this->getDbo();
        $query = "Select ticketid,email from `#__js_ticket_tickets` where id = " . $id;
        $db->setQuery($query);
        $ticket = $db->loadObject();
        return $ticket;
    }

    function enforcedeleteTicket() {
        $id = JRequest::getVar('cid');
        if (!is_numeric($id))
            return false;
        $session = JFactory::getSession();
        $session->set('ticketid',$this->getTrackingIdById($id));
        $session->set('ticketemail',$this->getTicketEmailById($id));
        $session->set('ticketsubject',$this->getTicketSubjectById($id));

        $db = $this->getDBO();
        $query = "DELETE ticket,reply,attach
                        FROM `#__js_ticket_tickets` AS ticket
                        LEFT JOIN `#__js_ticket_replies` AS reply ON reply.ticketid = ticket.id
                        LEFT JOIN `#__js_ticket_attachments` AS attach ON attach.ticketid = ticket.id
                        WHERE ticket.id = " . $id;
        $db->setQuery($query);
        if (!$db->query()) {
            return DELETE_ERROR;
        } else {
            //for email to sure ticket is deleted
            $this->getJSModel('email')->sendMail(1,3,$id); // Mailfor,Delete Ticket,Ticketid
            return DELETED;
        }
    }

    function deleteTicket() {
        $id = JRequest::getVar('cid');
        if (!is_numeric($id))
            return false;
        if($this->canDeleteTicket($id)){
            $db = $this->getDBO();
            $query = "DELETE ticket,attach
                            FROM `#__js_ticket_tickets` AS ticket
                            LEFT JOIN `#__js_ticket_attachments` AS attach ON attach.ticketid = ticket.id
                            WHERE ticket.id = " . $id;
            $db->setQuery($query);
            if (!$db->query()) {
                return DELETE_ERROR;
            } else {
	        //for email to sure ticket is deleted
	        $this->getJSModel('email')->sendMail(1,3,$id); // Mailfor,Delete Ticket,Ticketid
                return DELETED;
            }
        }else{
            return IN_USE;
        }
    }

    function canDeleteTicket($id){
        if(!is_numeric($id)) return false;
        $db = JFactory::getDbo();
        $query = "SELECT COUNT(reply.id) FROM `#__js_ticket_replies` AS reply WHERE reply.ticketid = $id";
        $db->setQuery($query);
        $result = $db->loadResult();
        if($result == 0)
            return true;
        else
            return false;
    }

    function checkEmailAndTicketID($email, $ticketid) {
        $db = $this->getDBO();
        $query = "SELECT COUNT(id) FROM `#__js_ticket_tickets` WHERE email =" . $db->quote($email) . " AND ticketid =" . $db->quote($ticketid);
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    function checkTicketIdAndUid($uid,$ticketid) {
        if(!is_numeric($uid)) return false;
        if(!is_numeric($ticketid)) return false;
        $db = $this->getDBO();
        $query = "SELECT COUNT(id) FROM `#__js_ticket_tickets` WHERE uid =" . $uid . " AND id =" . $ticketid;
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    function getIdFromTrackingId($ticketid) {
        $db = $this->getDBO();
        $query = "SELECT id FROM `#__js_ticket_tickets` WHERE ticketid =" . $db->quote($ticketid);
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    private function sendEmail($id, $uid, $for, $message, $appendsignature, $to) {
        if ($to != '')
            $model_email = $this->getJSModel('email');
        switch ($to) {
            case 'user':
                $model_email->sendMail($id, $uid, 1, $message, '');
                break;
                break;
            case 'admin':
                $model_email->sendMailtoAdmin($id, $uid, 1, $message, '');
                break;
            case 'all':
                $model_email->sendMail($id, $uid, 1, $message, '');
                $model_email->sendMailtoAdmin($id, $uid, 1, $message, '');
                break;
        }
    }

    function getTicketId() {
        $db = $this->getDBO();
        $query = "SELECT ticketid FROM `#__js_ticket_tickets`";
        $match = '';
        do {
            $ticketid = "";
            $length = 13;
            $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
            $maxlength = strlen($possible);
            if ($length > $maxlength) {
                $length = $maxlength;
            }
            $i = 0;
            while ($i < $length) {
                $char = substr($possible, mt_rand(0, $maxlength - 1), 1);
                if (!strstr($ticketid, $char)) {
                    if ($i == 0) {
                        if (ctype_alpha($char)) {
                            $ticketid .= $char;
                            $i++;
                        }
                    } else {
                        $ticketid .= $char;
                        $i++;
                    }
                }
            }
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            foreach ($rows as $row) {
                if ($ticketid == $row->ticketid)
                    $match = 'Y';
                else
                    $match = 'N';
            }
        }while ($match == 'Y');

        return $ticketid;
    }

    function updateIsAnswered($ticketid,$isanswered) {
        if (!is_numeric($ticketid))
            return false;
        if(!is_numeric($isanswered)) return false;
        $db = $this->getDbo();
        $query = "UPDATE `#__js_ticket_tickets` set isanswered = $isanswered WHERE id = " . $ticketid;
        $db->setQuery($query);
        if (!$db->query())
            return false;
        else
            return true;
    }

    function updateTicketLastReply($ticketid, $created) {
        if (!is_numeric($ticketid))
            return false;
        $db = $this->getDbo();
        $query = "UPDATE `#__js_ticket_tickets` set lastreply = " . $db->quote($created) . " WHERE id = " . $ticketid;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        } else {
            return true;
        }
    }

    function getLatestReplyByTicketId($id) {
        if (!is_numeric($id))
            return false;
        $db = JFactory::getDBO();
        $query = "SELECT reply.message FROM `#__js_ticket_replies` AS reply WHERE reply.ticketid = " . $id . " ORDER BY reply.created DESC LIMIT 1";
        $db->setQuery($query);
        $message = $db->loadResult();
        
        return $message;
    }

    function saveResponceAJAX($id,$responce){
        if($id) if(!is_numeric($id)) return false;

        $user = JSSupportTicketCurrentUser::getInstance();
        $per = $user->checkUserPermission('Edit Ticket');
        if ($per == false) return PERMISSION_ERROR;
        $row = $this->getTable('replies');
        $data['id'] = $id;
        //$data['message'] = JRequest::getVar('message', '', 'post', 'string', JREQUEST_ALLOWRAW);
        $data['message'] = $responce;
        
        if (!$row->bind($data)){
            $this->setError($this->_db->getErrorMsg());
            return SAVE_ERROR;
        }
        if (!$row->check()){
            $this->setError($this->_db->getErrorMsg());
            return SAVE_ERROR;
        }
        if (!$row->store()){
            $this->getJSModel('systemerrors')->updateSystemErrors($row->getErrorMsg());
            $this->updateSystemErrors($this->_db->getErrorMsg());
            return SAVE_ERROR;
        }
        return SAVED;
    }
    
    function editResponceAJAX($id){
        $db = $this->getDBO();
        if($id) if(!is_numeric($id)) return false;

        $query = "SELECT message FROM `#__js_ticket_replies` WHERE id = ".$id;
        $db->setQuery( $query );
        $row = $db->loadObject();
        $editor = JFactory::getEditor();
        if(isset($row)){
            $return_value =  $editor->display("editor_responce_$id", $row->message, "600", "400", "80", "15", 1, null, null, null, array('mode' => 'advanced'));
        }else{
            $return_value = $editor->display('editor_responce_'.$id, '', '550', '300', '60', '20', false);    
        }

        $return_value .= '<br /> 
        <input type="button" class="tk_dft_btn" value="'.JText::_('Save').'" onclick="saveResponce('.$id.')">
        <input type="button" class="tk_dft_btn" value="'.JText::_('Close').'" onclick="closeResponce('.$id.')">';      
        return $return_value;
    }
    
    function deleteResponceAJAX($id){
        if($id) if(!is_numeric($id)) return false;
        $user = JSSupportticketCurrentUser::getInstance();
        $per = $user->checkUserPermission('Delete Ticket');
        if ($per == false) return PERMISSION_ERROR;
        $row = $this->getTable('replies');
        if (!$row->delete($id)){
            $this->setError($row->getErrorMsg());
            return DELETE_ERROR;
        }
        return DELETED;
    }
    function getUserListForRegistration() {
        $db = JFactory::getDbo();
        $query = "SELECT DISTINCT user.ID AS userid, user.username AS username, user.email AS useremail, user.name AS userdisplayname
                    FROM `#__users` AS user ORDER BY userdisplayname";
        $db->setQuery($query);
        $users = $db->loadObjectList();
        return $users;
    }
    function getTicketSubjectById($id) {
        if (!is_numeric($id))
            return false;
            $db = JFactory::getDbo();
        $query = "SELECT subject FROM `#__js_ticket_tickets` WHERE id = " . $id;
        $db->setQuery($query);
        $subject = $db->loadResult();
        return $subject;
    }

    function getTrackingIdById($id) {
        if (!is_numeric($id))
            return false;
            $db = JFactory::getDbo();
        $query = "SELECT ticketid FROM `#__js_ticket_tickets` WHERE id = " . $id;
        $db->setQuery($query);
        $ticketid = $db->loadResult();
        return $ticketid;
    }

    function getTicketEmailById($id) {
        if (!is_numeric($id))
            return false;
            $db = JFactory::getDbo();
        $query = "SELECT email FROM `#__js_ticket_tickets` WHERE id = " . $id;
        $db->setQuery($query);
        $ticketemail = $db->LoadResult();
        return $ticketemail;
    }
    

}
?>

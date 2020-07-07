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
 
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class JSSupportTicketViewTicket extends JSSupportticketView
{
	function display($tpl = null){
		require_once(JPATH_COMPONENT."/views/common.php");
		if($layoutName == 'ticketdetail'){
			$id = JRequest::getVar('id');
			$email = JRequest::getVar('email');
			//for email and tracking id
			if(!is_numeric($id)){
				$result = $this->getJSModel('ticket')->checkEmailAndTicketID($email,$id);
				if($result == 1){
					$id = $this->getJSModel('ticket')->getIdFromTrackingId($id);
				}
			}
			$uid = $user->getId();
			$result = $this->getJSModel('ticket')->getTicketDetailById($id,$uid);
			$this->assignRef('ticket', $result[0]);
			$this->assignRef('messages', $result[2]);
			$this->assignRef('attachment', $result[6]);
			$this->assignRef('email', $email);
			$this->assignRef('id', $id);
			$this->assignRef('userfields', $result[7]);
		}elseif($layoutName == 'formticket'){
			$id = JRequest::getVar('id');
			$data = $mainframe->getUserState('com_jssupportticket.data');
			$mainframe->setUserState('com_jssupportticket.data',null);
			$result = $this->getJSModel('ticket')->getFormData($id,$data);
			$this->assignRef('data', $data);
			$this->assignRef('lists', $result[2]);
			$this->assignRef('userfields', $result[3]);
			$this->assignRef('fieldsordering', $result[4]);
		}elseif($layoutName == 'mytickets'){
			$sort =  JRequest::getVar('sortby','');
			if (isset($sort)){
				if ($sort == '') {$sort='defaultdesc';}
			}else {$sort='defaultdesc';}
    			
    		$searchticketid = JRequest::getVar('filter_ticketid');              
			$uid = $user->getId();
			$listtype = JRequest::getVar('lt',1);
			$sortby = $this->getTicketListOrdering($sort);
			$sortlinks = $this->getTicketListSorting($sort);
			$sortlinks['sorton'] = $sorton;
			$sortlinks['sortorder'] = $sortorder;
			$result = $this->getJSModel('ticket')->getUserMyTickets($uid,$listtype,$searchticketid,$sortby,$limitstart,$limit);
			$this->assignRef('username', $uname);
			$this->assignRef('result', $result[0]);
			$this->assignRef('ticketinfo', $result[2]);
			$this->assignRef('lists', $result[3]);
			$this->assignRef('lt', $listtype);
			$this->assignRef('sortlinks', $sortlinks);
			$this->assignRef('filter_data', $result[4]);
			$total = $result[1];
			$pagination = new JPagination($total, $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
		}
		require_once(JPATH_COMPONENT."/views/ticket/ticket_breadcrumbs.php");
		parent::display($tpl);
	}
	function getTicketListOrdering( $sort ) {
		global $sorton, $sortorder;
		switch ( $sort ) {
			case "subjectdesc": $ordering = "ticket.subject DESC"; $sorton = "subject"; $sortorder="DESC"; break;
			case "subjectasc": $ordering = "ticket.subject ASC";  $sorton = "subject"; $sortorder="ASC"; break;
			case "prioritydesc": $ordering = "priority.priority DESC"; $sorton = "priority"; $sortorder="DESC"; break;
			case "priorityasc": $ordering = "priority.priority ASC";  $sorton = "priority"; $sortorder="ASC"; break;
			case "ticketiddesc": $ordering = "ticket.ticketid DESC";  $sorton = "ticketid"; $sortorder="DESC"; break;
			case "ticketidasc": $ordering = "ticket.ticketid ASC";  $sorton = "ticketid"; $sortorder="ASC"; break;
			case "answereddesc": $ordering = "ticket.isanswered DESC";  $sorton = "answered"; $sortorder="DESC"; break;
			case "answeredasc": $ordering = "ticket.isanswered ASC";  $sorton = "answered"; $sortorder="ASC"; break;
			case "attachmentsdesc": $ordering = "attachments DESC";  $sorton = "attachments"; $sortorder="DESC"; break;
			case "attachmentsasc": $ordering = "attachments ASC";  $sorton = "attachments"; $sortorder="ASC"; break;
			case "createddesc": $ordering = "ticket.created DESC";  $sorton = "created"; $sortorder="DESC"; break;
			case "createdasc": $ordering = "ticket.created ASC";  $sorton = "created"; $sortorder="ASC"; break;
			case "defaultdesc": $ordering = "ticket.status ASC,ticket.isanswered ASC,ticket.priorityid ASC,ticket.created DESC";  $sorton = "created"; $sortorder="DESC"; break;
			case "defaultasc": $ordering = "ticket.status ASC,ticket.created ASC";  $sorton = "created"; $sortorder="ASC"; break;
			case "statusdesc": $ordering = "ticket.status DESC";  $sorton = "status"; $sortorder="DESC"; break;
			case "statusasc": $ordering = "ticket.status ASC";  $sorton = "status"; $sortorder="ASC"; break;
			default: $ordering = "ticket.isanswered DESC";
		}
		return $ordering;
	}

	function getTicketListSorting( $sort ) {
		$sortlinks['subject'] = $this->getSortArg("subject",$sort);
		$sortlinks['priority'] = $this->getSortArg("priority",$sort);
		$sortlinks['ticketid'] = $this->getSortArg("ticketid",$sort);
		$sortlinks['answered'] = $this->getSortArg("answered",$sort);
		$sortlinks['status'] = $this->getSortArg("status",$sort);
		$sortlinks['created'] = $this->getSortArg("created",$sort);
		$sortlinks['attachments'] = $this->getSortArg("attachments",$sort);
		$sortlinks['created'] = $this->getSortArg("created",$sort);

		return $sortlinks;
	}
	function getSortArg( $type, $sort ) {
		$mat = array();
		if ( preg_match( "/(\w+)(asc|desc)/i", $sort, $mat ) ) {
			if ( $type == $mat[1] ) {
				return ( $mat[2] == "asc" ) ? "{$type}desc" : "{$type}asc";
			} else {
				return $type . $mat[2];
			}
		}
		return "iddesc";
	}
}
?>
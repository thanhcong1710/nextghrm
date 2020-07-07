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

class JSSupportticketViewTicket extends JSSupportTicketView
{
	function display($tpl = null){
        require_once(JPATH_COMPONENT_ADMINISTRATOR."/views/common.php");
        global $sorton,$sortorder;
        if($layoutName == 'tickets'){
            JToolBarHelper::addNew('addnewticket');
            $sort =  JRequest::getVar('sortby','');
            if ($sort == '') $sort='statusasc';

            $sortby = $this->getTicketListOrdering($sort);
            $sortlinks = $this->getTicketListSorting($sort);
            $sortlinks['sorton'] = $sorton;
            $sortlinks['sortorder'] = $sortorder;
            $mainframe->setUserState( $option.'.limitstart', $limitstart );
            $searchsubject = JRequest::getVar('filter_subject');
            $searchfrom = JRequest::getVar('filter_from');
            $searchfromemail = JRequest::getVar('filter_fromemail');
            $searchticketid = JRequest::getVar('filter_ticketid');
            $listtype = JRequest::getVar('lt',1);
            if($listtype == 1) $text = JText::_('Open');
            if($listtype == 2) $text = JText::_('Answered');
            if($listtype == 3) $text = JText::_('Overdue');
            if($listtype == 4) $text = JText::_('Close');
            if($listtype == 5) $text = JText::_('My Tickets');
            JToolBarHelper::title(JText::_('Tickets') . ' <small><small>[ ' . $text . ' ]</small></small>');
            $result = $this->getJSModel('ticket')->getAdminMyTickets($searchsubject,$searchfrom,$searchfromemail,$searchticketid,$listtype,$sortby,$limitstart,$limit);
            $total = $result[1];
            $this->assignRef('result', $result[0]);
            $this->assignRef('lists', $result[2]);
            $this->assignRef('ticketinfo', $result[3]);
            $this->assignRef('listtype', $listtype);
            $this->assignRef('sortlinks', $sortlinks);
            $this->assignRef('sorton', $sorton);
            $this->assignRef('sortorder', $sortorder);
            $pagination = new JPagination($total, $limitstart, $limit);
        }elseif($layoutName == 'ticketdetails'){
			JToolBarHelper::title(JText::_('Ticket'));
            $ticketid = JRequest::getVar('cid', array (0), '', 'array');
            $ticketid = $ticketid[0];
            $result = $this->getJSModel('ticket')->getTicketDetailById($ticketid);
			$user = JSSupportticketCurrentUser::getInstance();
			$isstaff = 0;
            $this->assignRef('ticketdetail', $result[0]);
            $this->assignRef('ticketnotes', $result[1]);
            $this->assignRef('ticketreplies', $result[2]);
            $this->assignRef('lists', $result[3]);
            $this->assignRef('isemailban', $result[4]);
            $this->assignRef('ticketattachment', $result[6]);
            $this->assignRef('userfields', $result[7]);
            $this->assignRef('fieldsordering', $result[8]);
            $this->assignRef('isstaff', $isstaff);
            if(isset($result[9])) $this->assignRef('tickethistory', $result[9]);
        }elseif($layoutName == 'formticket'){
			JToolBarHelper::save('saveticketsave','Save Ticket');
			JToolBarHelper::save2new('saveticketandnew');
			JToolBarHelper::save('saveticket');
			$c_id = JRequest::getVar('cid', array (0), '', 'array');
			$c_id = $c_id[0];
			$id = JRequest::getVar('id');
			if(isset($id))
				$c_id = $id;
            $data = $mainframe->getUserState('com_jssupportticket.data');
            $mainframe->setUserState('com_jssupportticket.data',null);
			$result = $this->getJSModel('ticket')->getFormData($c_id,$data);
			$isNew = true;
			if (isset($c_id) && ($c_id <> '' || $c_id <> 0)) $isNew = false;
			$text = $isNew ? JText::_('Add') : JText::_('Edit');
			JToolBarHelper::title(JText::_('Ticket') . ': <small><small>[ ' . $text . ' ]</small></small>');
			if ($isNew) JToolBarHelper::cancel('cancelticket');	else JToolBarHelper::cancel('cancelticket', 'Close');

			$this->assignRef('lists', $result[2]);
			if(isset($result[0]))
			$this->assignRef('editticket', $result[0]);
            $this->assignRef('data', $data);
            $this->assignRef('userfields', $result[3]);
			$this->assignRef('attachments', $result[5]);
			$this->assignRef('fieldsordering', $result[4]);
		}
        $this->assignRef('pagination', $pagination);
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
            case "createddesc": $ordering = "ticket.created DESC";  $sorton = "created"; $sortorder="DESC"; break;
            case "createdasc": $ordering = "ticket.created ASC";  $sorton = "created"; $sortorder="ASC"; break;
            case "statusdesc": $ordering = "ticket.status DESC";  $sorton = "status"; $sortorder="DESC"; break;
            case "statusasc": $ordering = "ticket.status ASC";  $sorton = "status"; $sortorder="ASC"; break;
            default: $ordering = "ticket.status ASC";
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

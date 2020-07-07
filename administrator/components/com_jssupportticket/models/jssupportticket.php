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

class JSSupportticketModelJSSupportticket extends JSSupportTicketModel{
    function __construct() {
        parent::__construct();
    }
    
    function getControlPanelData(){
      $curdate = date('Y-m-d');
      $fromdate = date('Y-m-d', strtotime("now -1 month"));
      $db = JFactory::getDbo();
      $result = array();
      $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `#__js_ticket_tickets` WHERE priorityid = priority.id AND status = 0 AND (lastreply = '0000-00-00 00:00:00' OR lastreply IS NULL) AND date(created) >= '".$fromdate."' AND date(created) <= '".$curdate."' ) AS totalticket
                  FROM `#__js_ticket_priorities` AS priority ORDER BY priority.priority";
      $db->setQuery($query);
      $openticket_pr = $db->loadObjectList();
      $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `#__js_ticket_tickets` WHERE priorityid = priority.id AND isanswered = 1 AND status != 4 AND status != 0 AND date(created) >= '".$fromdate."' AND date(created) <= '".$curdate."') AS totalticket
                  FROM `#__js_ticket_priorities` AS priority ORDER BY priority.priority";
      $db->setQuery($query);
      $answeredticket_pr = $db->loadObjectList();
      $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `#__js_ticket_tickets` WHERE priorityid = priority.id AND isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00' AND lastreply != '') AND date(created) >= '".$fromdate."' AND date(created) <= '".$curdate."') AS totalticket
                  FROM `#__js_ticket_priorities` AS priority ORDER BY priority.priority";
      $db->setQuery($query);
      $pendingticket_pr = $db->loadObjectList();
      $result['stack_chart_horizontal']['title'] = "['".JText::_("Tickets")."',";
      $result['stack_chart_horizontal']['data'] = "['".JText::_("Pending")."',";

      foreach($pendingticket_pr AS $pr){
          $result['stack_chart_horizontal']['title'] .= "'".$pr->priority."',";
          $result['stack_chart_horizontal']['data'] .= $pr->totalticket.",";
      }
      $result['stack_chart_horizontal']['title'] .= "]";
      $result['stack_chart_horizontal']['data'] .= "],['".JText::_("Answered")."',";

      foreach($answeredticket_pr AS $pr){
          $result['stack_chart_horizontal']['data'] .= $pr->totalticket.",";
      }

      $result['stack_chart_horizontal']['data'] .= "],['".JText::_("New")."',";

      foreach($openticket_pr AS $pr){
          $result['stack_chart_horizontal']['data'] .= $pr->totalticket.",";
      }
      
      $result['stack_chart_horizontal']['data'] .= "]";

      $result['ticket_total']['openticket'] = 0;
      $result['ticket_total']['pendingticket'] = 0;
      $result['ticket_total']['answeredticket'] = 0;

      $count = count($openticket_pr);
      for($i = 0;$i < $count; $i++){
          $result['ticket_total']['openticket'] += $openticket_pr[$i]->totalticket;
          $result['ticket_total']['pendingticket'] += $pendingticket_pr[$i]->totalticket;
          $result['ticket_total']['answeredticket'] += $answeredticket_pr[$i]->totalticket;
      }

      $query = "SELECT ticket.id,ticket.ticketid,ticket.subject,ticket.name,ticket.created,priority.priority,priority.prioritycolour,ticket.status
                  FROM `#__js_ticket_tickets` AS ticket
                  JOIN `#__js_ticket_priorities` AS priority ON priority.id = ticket.priorityid
                  ORDER BY ticket.status ASC, ticket.created DESC LIMIT 0, 5";
      $db->setQuery($query);
      $result['tickets'] = $db->loadObjectList();
      return $result;
    }

}

?>

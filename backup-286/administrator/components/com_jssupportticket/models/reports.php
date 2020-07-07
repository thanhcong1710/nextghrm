<?php
/**
 * @Copyright Copyright (C) 2015 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:     Buruj Solutions
 + Contact:     www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:  May 22, 2015
  ^
  + Project:    JS Tickets
  ^
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
jimport('joomla.html.html');

class JSSupportticketModelReports extends JSSupportTicketModel {

    function __construct() {
        parent::__construct();
    }

    function getOverallReportsData(){
        $db = JFactory::getDbo();
        $result = array();

        //Overall Data by status
        $query = "SELECT COUNT(id) FROM `#__js_ticket_tickets` WHERE status = 0 AND (lastreply = '0000-00-00 00:00:00' OR lastreply IS NULL)";
        $db->setQuery($query);
        $openticket = $db->loadResult();

        $query = "SELECT COUNT(id) FROM `#__js_ticket_tickets` WHERE status = 4";
        $db->setQuery($query);
        $closeticket = $db->loadResult();

        $query = "SELECT COUNT(id) FROM `#__js_ticket_tickets` WHERE isanswered = 1 AND status != 4 AND status != 0";
        $db->setQuery($query);
        $answeredticket = $db->loadResult();

        $query = "SELECT COUNT(id) FROM `#__js_ticket_tickets` WHERE isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00' AND lastreply != '')";
        $db->setQuery($query);
        $pendingticket = $db->loadResult();

        $result['status_chart'] = "['".JText::_('New')."',$openticket],['".JText::_('Answered')."',$answeredticket],['".JText::_('Pending')."',$pendingticket]";
        $total = $openticket + $closeticket + $answeredticket + $pendingticket;
        $result['bar_chart'] = "
        ['".JText::_('New')."',$openticket,'#FF9900'],
        ['".JText::_('Answered')."',$answeredticket,'#179650'],
        ['".JText::_('Closed')."',$closeticket,'#5F3BBB'],
        ['".JText::_('Pending')."',$pendingticket,'#D98E11'],
        ";

        $query = "SELECT dept.departmentname,(SELECT COUNT(id) FROM `#__js_ticket_tickets` WHERE departmentid = dept.id) AS totalticket
                    FROM `#__js_ticket_departments` AS dept";
        $db->setQuery($query);
        $department = $db->loadObjectList();
        $result['pie3d_chart1'] = "";
        foreach($department AS $dept){
            $result['pie3d_chart1'] .= "['$dept->departmentname',$dept->totalticket],";
        }

        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `#__js_ticket_tickets` WHERE priorityid = priority.id) AS totalticket
                    FROM `#__js_ticket_priorities` AS priority";
        $db->setQuery($query);
        $department = $db->loadObjectList();
        $result['pie3d_chart2'] = "";
        foreach($department AS $dept){
            $result['pie3d_chart2'] .= "['$dept->priority',$dept->totalticket],";
        }

        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `#__js_ticket_tickets` WHERE priorityid = priority.id AND status = 0 AND (lastreply = '0000-00-00 00:00:00' OR lastreply IS NULL) ) AS totalticket
                    FROM `#__js_ticket_priorities` AS priority ORDER BY priority.priority";
        $db->setQuery($query);
        $openticket_pr = $db->loadObjectList();
        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `#__js_ticket_tickets` WHERE priorityid = priority.id AND isanswered = 1 AND status != 4 AND status != 0 ) AS totalticket
                    FROM `#__js_ticket_priorities` AS priority ORDER BY priority.priority";
        $db->setQuery($query);
        $answeredticket_pr = $db->loadObjectList();
        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `#__js_ticket_tickets` WHERE priorityid = priority.id AND isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00' AND lastreply != '') ) AS totalticket
                    FROM `#__js_ticket_priorities` AS priority ORDER BY priority.priority";
        $db->setQuery($query);
        $pendingticket_pr = $db->loadObjectList();
        $result['stack_chart_horizontal']['title'] = "['".JText::_('Tickets')."',";
        $result['stack_chart_horizontal']['data'] = "['".JText::_('Pending')."',";

        foreach($pendingticket_pr AS $pr){
            $result['stack_chart_horizontal']['title'] .= "'".$pr->priority."',";
            $result['stack_chart_horizontal']['data'] .= $pr->totalticket.",";
        }
        $result['stack_chart_horizontal']['title'] .= "]";
        $result['stack_chart_horizontal']['data'] .= "],['".JText::_('Answered')."',";

        foreach($answeredticket_pr AS $pr){
            $result['stack_chart_horizontal']['data'] .= $pr->totalticket.",";
        }

        $result['stack_chart_horizontal']['data'] .= "],['".JText::_('New')."',";

        foreach($openticket_pr AS $pr){
            $result['stack_chart_horizontal']['data'] .= $pr->totalticket.",";
        }
        
        $result['stack_chart_horizontal']['data'] .= "]";
        return $result;
    }

}

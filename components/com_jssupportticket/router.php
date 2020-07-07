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

function JSSupportTicketBuildRoute( &$query )
{
	$segments = array();
    $router = new JSSupportticketRouter;
	if(isset( $query['layout'] )) {        
		if(isset($query['c'])) {
            $controller = $query['c'];
            unset($query['c']);
        }else{
            $controller = '';
        }
		$segments[] = $router->buildLayout($query['layout'],$controller); unset($query['layout']);
	};
	if(isset( $query['task'] )) { $segments[] = 'tk-'.$query['task']; unset($query['task']);};
	if(isset( $query['id'] )) { $segments[] = 'ticketid-'.$query['id']; unset($query['id']);};
	if(isset( $query['email'] )) { $segments[] = 'email-'.$query['email']; unset($query['email']);};
	if(isset( $query['lt'] )) { $segments[] = 'listing-'.$router->buildListingFor($query['lt']); unset($query['lt']);};
	//for sorting
	if(isset( $query['sort'] )) { $segments[] = 'sort-'.$query['sort']; unset($query['sort']);};
	if(isset( $query['sortby'] )) { $segments[] = 'sortby-'.$query['sortby']; unset($query['sortby']);};

	//  echo '<br> item '.$query['Itemid'];
	if(isset( $query['Itemid'] )) { 
		$_SESSION['JSItemid'] = $query['Itemid'];
	};

	return $segments;
}

function JSSupportTicketParseRoute( $segments )
{
	$vars = array();
	$count = count($segments);
    $router = new JSSupportTicketRouter;
	//echo '<br> count '.$count;
	//print_r($segments);

	$site= JMenu::getInstance('site');
	$item	= $site->getActive();

	$result = $router->parseLayout($segments[0]);
	$vars['c'] = $result['controller'];
	$vars['layout'] = $result['layout'];

   	//echo '<br> layout '.$layout;print_r($segments);
    $i = 0;
    foreach ($segments AS $seg) {
        if ($i >= 1) {
            $array = explode(":", $seg);
            $index = $array[0];
            //unset the current index
            unset($array[0]);
            if (isset($array[1])) $value = implode("-", $array);

            switch ($index) {
	            case "task": $vars['tk'] = $value; break;
	            case "ticketid": $vars['id'] = $router->parseId($value); break;
	            case "email": $vars['email'] = $router->parseId($value); break;
	            case "listing": $vars['lt'] = $router->parseListingFor($value); break;
	            case "sort": $vars['sort'] = $value; break;
	            case "sortby": $vars['sortby'] = $value; break;
			}
		}
		$i++;
	}
	if(isset( $_SESSION['JSItemid'] )) { 
		$vars['Itemid'] = $_SESSION['JSItemid'];
	}
	return $vars;

}

class JSSupportTicketRouter {

    function buildLayout($layout, $controller) {
        $returnvalue = "";
        //echo '<br> layout ='.$layout;
        //echo '<br> controller ='.$controller;
        switch ($layout) {
            case "controlpanel":$returnvalue = "controlpanel";break;
            case "formticket": $returnvalue = "addticket"; break;
            case "mytickets": $returnvalue = "mytickets"; break;
            case "ticketdetail": $returnvalue = "viewticket"; break;
        }
        return $returnvalue;
    }

    function parseLayout($value) {
        //	$returnvalue = "";
        switch ($value) {
            case "controlpanel": $returnvalue["layout"] = "controlpanel"; $returnvalue["controller"] = "jssupportticket"; break;
            case "addticket": $returnvalue["layout"] = "formticket"; $returnvalue["controller"] = "ticket"; break;
            case "mytickets": $returnvalue["layout"] = "mytickets"; $returnvalue["controller"] = "ticket"; break;
            case "viewticket": $returnvalue["layout"] = "ticketdetail"; $returnvalue["controller"] = "ticket"; break;
        }
        if (isset($returnvalue))
            return $returnvalue;
    }

    function buildListingFor($value){
    	$returnvalue = '';
		switch ($value) {
			case '1':$returnvalue = 'open';break;
			case '4':$returnvalue = 'closed';break;
			case '3':$returnvalue = 'answered';break;
			case '5':$returnvalue = 'all';break;
    	}    	
    	return $returnvalue;
    }

    function parseListingFor($value){
    	$returnvalue = '';
		switch ($value) {
			case 'open':$returnvalue = '1';break;
			case 'closed':$returnvalue = '4';break;
			case 'answered':$returnvalue = '3';break;
			case 'all':$returnvalue = '5';break;
    	}    	
    	return $returnvalue;
    }

    function parseId($value) {
        $id = explode("-", $value);
        $count = count($id);
        $id = (int) $id[($count - 1)];
        return $id;
    }
}

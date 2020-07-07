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

	global $sorton,$sortorder;
	$option = 'com_jssupportticket';
	$mainframe = JFactory::getApplication();
	$itemid = JRequest::getVar('Itemid');
	$type = 'offl';
	$config =  $this->getJSModel('config')->getConfigs();
	$layoutName = JRequest::getVar('layout', '');
    $user = JSSupportTicketCurrentUser::getInstance();
    $isguest = $user->getIsGuest();
    $uid = $user->getId();
    $uname = $user->getName();
	$limit = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
//	$limitstart = $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
	$limitstart = JRequest::getVar('limitstart',0);

	$needlogin = false;
	switch($layoutName){
		case 'formticket':
		case 'mytickets' :
			$needlogin = true;
		break;
	}
	if($needlogin == true){
		if($isguest){
			$redirectUrl = 'index.php?option=com_jssupportticket&c=ticket&layout='.$layoutName.'&Itemid='.$itemid;
			$redirectUrl = '&amp;return='.base64_encode($redirectUrl);
			$finalUrl = 'index.php?option=com_users&view=login'. $redirectUrl;
			$msg = JText::_('Login required');
			$mainframe->redirect($finalUrl,$msg); 
		}
	}
	$this->assignRef('layoutname', $layoutName);
	$this->assignRef('config', $config);
	$this->assignRef('Itemid', $itemid);
	$this->assignRef('option', $option);
	$this->assignRef('user', $user);
?>
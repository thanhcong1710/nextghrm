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

class JSSupportticketViewSystemErrors extends JSSupportTicketView
{
	function display($tpl = null){
		require_once(JPATH_COMPONENT_ADMINISTRATOR."/views/common.php");
		JToolBarHelper::title(JText::_('System Errors'));
		if($layoutName == 'systemerrors'){
			$result = $this->getJSModel('systemerrors')->getSystemErrors($limitstart, $limit);
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('systemerrors', $result[0]);
		}elseif($layoutName == 'error'){
			$errorid = JRequest::getVar('cid');
			$result = $this->getJSModel('systemerrors')->getErrorDetail($errorid);
			$this->assignRef('error',$result);
		}
		$this->assignRef('pagination', $pagination);
		parent::display($tpl);
	}
}
?>

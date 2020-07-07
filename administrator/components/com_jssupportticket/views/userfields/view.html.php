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

class JSSupportticketViewUserFields extends JSSupportTicketView{
	
	function display($tpl = null){
		require_once(JPATH_COMPONENT_ADMINISTRATOR."/views/common.php");
      	if($layoutName == 'userfields'){
            JToolBarHelper::title(JText::_('User Fields'));
            JToolBarHelper::addNew('adduserfield');
            JToolBarHelper::editList('adduserfield');
            JToolBarHelper::deleteList('Are you sure to delete','removeuserfields');
            $fieldfor = JRequest::getVar('ff',1);
            if ($fieldfor) $_SESSION['ffusr'] = $fieldfor; else $fieldfor = $_SESSION['ffusr'];
            $result =  $this->getJSModel('userfields')->getUserFields($fieldfor, $limitstart, $limit);
            $this->assignRef('items', $result[0]);
            $total = $result[1];
            $this->assignRef('filter_fieldtitle',$result[2]);
            if ( $total <= $limitstart ) $limitstart = 0;
            $pagination = new JPagination( $total, $limitstart, $limit );
     	}elseif ($layoutName == 'formuserfield'){
			if (isset($_GET['cid'][0]))	$c_id= $_GET['cid'][0];
			else $c_id='';
			if ($c_id == ''){
				$cids = JRequest::getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];
			}
			if (is_numeric($c_id) == true) $result =  $this->getJSModel('userfields')->getUserFieldbyId($c_id);
			if (isset($_GET['ff'])) $fieldfor = $_GET['ff']; else $fieldfor = 1;
			if ($fieldfor) $_SESSION['ffusr'] = $fieldfor; else $fieldfor = $_SESSION['ffusr'];
			$this->assignRef('userfield', $result[0]);
			$this->assignRef('fieldvalues', $result[1]);
			$this->assignRef('fieldfor', $fieldfor);
	        $isNew=true;
			if ( isset($result[0]->id) ) $isNew = false;
			$text = ($isNew ? JText::_('Add'):JText::_('Edit'));
	                JToolBarHelper::title(JText::_('User Fields') . ': <small><small>[ ' . $text . ' ]</small></small>');
	                JToolBarHelper::save('saveuserfield','Save User field');
	    	if ($isNew) JToolBarHelper::cancel();	
	    	else JToolBarHelper::cancel('canceluserfield', 'Close');
    	}elseif($layoutName == 'fieldsordering'){
			JToolBarHelper::title(JText::_('Fields ordering'));
			$fieldfor = JRequest::getVar('ff',1);
			$result =  $this->getJSModel('userfields')->getFieldsOrderingLimit($fieldfor, $limitstart, $limit);
			$fields = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('fields',$fields);
		}
        $this->assignRef('pagination', $pagination);
        parent::display($tpl);
	}
}
?>
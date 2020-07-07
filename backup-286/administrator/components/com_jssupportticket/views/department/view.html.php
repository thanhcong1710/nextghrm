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

class JSSupportticketViewDepartment extends JSSupportTicketView
{
	function display($tpl = null)
	{
          require_once(JPATH_COMPONENT_ADMINISTRATOR."/views/common.php");
		JToolBarHelper::title(JText::_('Departments'));
		if($layoutName == 'formdepartment'){
               JToolBarHelper::save('savedepartmentsave','Save Department');
               JToolBarHelper::save2new('savedepartmentandnew');
               JToolBarHelper::save('savedepartment');

               $c_id = JRequest::getVar('cid', array (0), '', 'array');
               $c_id = $c_id[0];
               $result = $this->getJSModel('department')->getDepartmentForForm($c_id);
               $isNew = true;
               if (isset($c_id) && ($c_id <> '' || $c_id <> 0)) $isNew = false;
               $text = $isNew ? JText::_('Add') : JText::_('Edit');
               JToolBarHelper::title(JText::_('Department') . ': <small><small>[ ' . $text . ' ]</small></small>');
               if ($isNew)	JToolBarHelper::cancel('canceldepartment'); else JToolBarHelper::cancel('canceldepartment', 'Close');

               $this->assignRef('lists', $result[1]);
               $this->assignRef('department', $result[0]);
		}elseif($layoutName == 'departments'){
               JToolBarHelper::addNew('addnewdepartment');
               JToolBarHelper::editList('addnewdepartment');
               JToolBarHelper::deleteList(JText::_('Are you sure to delete'),'deletedepartment');
               $mainframe->setUserState( $option.'.limitstart', $limitstart );
               $searchdepartment = JRequest::getVar('filter_departmentname');
               $searchtype = $mainframe->getUserStateFromRequest( $option.'filter_type', 'filter_type',	'',	'string' );
               
               $result = $this->getJSModel('department')->getAllDepartments($searchdepartment, $searchtype,$limitstart,$limit);
               $total = $result[1];
               $this->assignRef('department', $result[0]);
               $this->assignRef('lists', $result[2]);
               $pagination = new JPagination($total, $limitstart, $limit);
          }

     	$this->assignRef('pagination', $pagination);
		
		parent::display($tpl);
	}
}
?>

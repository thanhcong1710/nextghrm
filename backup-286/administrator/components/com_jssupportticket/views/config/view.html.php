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
// Options button.
if (JFactory::getUser()->authorise('core.admin', 'com_jssupportticket')) {
    JToolBarHelper::preferences('com_jssupportticket');
}

class JSSupportticketViewConfig extends JSSupportTicketView
{
	function display($tpl = null)
	{
		require_once(JPATH_COMPONENT_ADMINISTRATOR."/views/common.php");
		JToolBarHelper::title(JText::_('Configurations'));
		if($layoutName == 'config'){
            JToolBarHelper::save('saveconf','Save Configurations');
            JToolBarHelper::cancel('cancelconfig');
            $result = $this->getJSModel('config')->getConfiguration();
            $this->assignRef('configuration', $result[0]);
            $this->assignRef('lists', $result[1]);
		}

		parent::display($tpl);
	}
}
?>

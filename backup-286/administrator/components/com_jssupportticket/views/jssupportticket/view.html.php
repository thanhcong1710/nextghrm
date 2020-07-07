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

class JSSupportticketViewJSSupportticket extends JSSupportTicketView {

    function display($tpl = null) {
        require_once(JPATH_COMPONENT_ADMINISTRATOR."/views/common.php");
        JToolBarHelper::title(JText::_('Tickets'));
        if ($layoutName == 'controlpanel') {
            JToolBarHelper::title(JText::_('Control Panel'));
            $result = $this->getJSModel('jssupportticket')->getControlPanelData();
            $version = $this->getJSModel('config')->getConfigByFor('version');
            $this->assignRef('result',$result);
            $this->assignRef('version',$version);
        } elseif ($layoutName == 'info') {
            JToolBarHelper::title(JText::_('About Us'));
        }
        parent::display($tpl);
    }

}

?>

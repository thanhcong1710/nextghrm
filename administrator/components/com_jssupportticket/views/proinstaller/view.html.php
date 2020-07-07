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

class JSSupportticketViewProInstaller extends JSSupportTicketView
{
	function display($tpl = null){
        
        require_once(JPATH_COMPONENT_ADMINISTRATOR."/views/common.php");
        JToolBarHelper::title(JText::_('JS Support Ticket Pro Installer'));
        if($layoutName == 'step1'){
        	$result = $this->getJSModel('proinstaller')->getServerValidate();
        	$this->assignRef('result',$result);
        }elseif($layoutName == 'step2'){
        	$result = $this->getJSModel('proinstaller')->getStepTwoValidate();
        	$this->assignRef('result',$result);
        }elseif($layoutName == 'step3'){
        	$result = $this->getJSModel('proinstaller')->getCountConfig();
                $configs = $this->getJSModel('config')->getConfigs();
                $this->assignRef('config_count',$result);
                $this->assignRef('config',$configs);
        }
        parent::display($tpl);
	}
}
?>

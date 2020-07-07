<?php
/*-------------------------------------------------------------------------
# com_layer_slider - com_layer_slider
# -------------------------------------------------------------------------
# @ author    John Gera, George Krupa, Janos Biro
# @ copyright Copyright (C) 2014 Offlajn.com  All Rights Reserved.
# @ license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @ website   http://www.offlajn.com
-------------------------------------------------------------------------*/
$revision = '5.1.1.048';
?><?php
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
jimport('joomla.application.component.view');
jimport('joomla.application.component.model');
if (!class_exists('JControllerLegacy')) {
	class JControllerLegacy extends JController {}
	class JViewLegacy extends JView {}
	class JModelLegacy extends JModel {}
}	
$GLOBALS['j25'] = version_compare(JVERSION, '3.0.0', 'l');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_layer_slider')) 
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

$controller	= JControllerLegacy::getInstance('Layer_slider');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();

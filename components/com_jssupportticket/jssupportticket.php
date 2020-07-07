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
require_once(JPATH_COMPONENT.'/JSApplication.php');
require_once(JPATH_COMPONENT.'/views/messageslayout.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.'/models/currentuser.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.'/models/constants.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.'/models/messages.php');
require_once (JPATH_COMPONENT_ADMINISTRATOR.'/models/cronjob.php');
$language = JFactory::getLanguage();
$language->load('com_jssupportticket', JPATH_ADMINISTRATOR, null, true);
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_jssupportticket/include/css/bootstrap.min.css');
$document->addStyleSheet('components/com_jssupportticket/include/css/jssupportticketdefault.css');
require_once('include/css/color.php');
if (JVERSION < 3) {
    JHtml::_('behavior.mootools');
    $document->addScript('administrator/components/com_jssupportticket/include/js/jquery.js');
}else{
    JHtml::_('behavior.framework');
    JHtml::_('jquery.framework');
}
$document->addScript('components/com_jssupportticket/include/js/my_js.js');

if ($c = JRequest::getCmd('c', 'jssupportticket'))
{
	$path = JPATH_COMPONENT.'/controllers/'.$c.'.php';
	jimport('joomla.filesystem.file');

	if (JFile::exists($path))
	{
		require_once ($path);
	}
	else
	{
		JError::raiseError('500', JText::_('Unknown Controller: <br>' . $c . ':' . $path));
	}
}

$c = 'JSSupportTicketController'.$c;
$controller = new $c ();
$controller->execute(JRequest::getCmd('task', 'display'));
$controller->redirect();
?>

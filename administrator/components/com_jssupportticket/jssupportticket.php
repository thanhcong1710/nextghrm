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
// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_jssupportticket')) {
	return JError::raiseWarning(404, JText::_('Jerror Alertnoauthor'));
}

$version = new JVersion;
$joomla = $version->getShortVersion();
$jversion = substr($joomla, 0, 3);

if (!defined('JVERSION')) {
    define('JVERSION', $jversion);
}

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'administrator/components/com_jssupportticket/include/css/jssupportticketadmin.css');
$document->addStyleSheet(JURI::root() . 'administrator/components/com_jssupportticket/include/css/bootstrap.min.css');

if (JVERSION < 3) {
    JHtml::_('behavior.mootools');
    $document->addScript('components/com_jssupportticket/include/js/jquery.js');
} else {
    JHtml::_('behavior.framework');
    JHtml::_('jquery.framework');
}

require_once(JPATH_COMPONENT.'/JSApplication.php');
$base = JPATH_BASE;
$base = substr($base, 0, strlen($base) - 14); //remove administrator
require_once($base.'/components/com_jssupportticket/views/messageslayout.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.'/models/currentuser.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.'/models/constants.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.'/models/messages.php');
require_once (JPATH_COMPONENT_ADMINISTRATOR.'/models/cronjob.php');
$task = JRequest::getCmd('task');
$c = '';
if (strstr($task, '.')) {
	$array = explode('.', $task);
	$c = $array[0];
	$task = $array[1];
} else {
	$c = JRequest::getCmd('c', 'jssupportticket');
	$task = JRequest::getCmd('task', 'display');
}
if ($c != '') {
	$path = JPATH_COMPONENT . '/controllers/' . $c . '.php';
	jimport('joomla.filesystem.file');
	if (JFile::exists($path)) {
		require_once ($path);
	} else {
		JError::raiseError('500', JText::_('Unknown Controller: <br>' . $c . ':' . $path));
	}
}
$c = 'JSSupportticketController'.$c;
$controller = new $c ();
$controller->execute($task);
$controller->redirect();

?>
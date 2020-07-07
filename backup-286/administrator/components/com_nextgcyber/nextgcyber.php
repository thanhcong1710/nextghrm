<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
if (!JFactory::getUser()->authorise('core.manage', 'com_nextgcyber')) {
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Register required Controllers
$controller_path = JPATH_ADMINISTRATOR . '/components/com_nextgcyber/controllers';
JLoader::register('NextgCyberControllerBaseForm', $controller_path . '/baseform.php');
JLoader::register('NextgCyberControllerAdmin', $controller_path . '/controlleradmin.php');

// Register required Views
JLoader::register('NextgCyberViewMain', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/views/view.php');

// Register required Helpers
JLoader::register('NextgCyberHelper', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/helper.php');

$controller = JControllerLegacy::getInstance('NextgCyber');
// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));
// Redirect if set by the controller
$controller->redirect();

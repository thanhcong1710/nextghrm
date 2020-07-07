<?php

/**
 * @package pkg_nextgcyber
 * @subpackage  com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://domain.com
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/helpers/route.php';
require_once JPATH_COMPONENT . '/helpers/query.php';
// Register helper
JLoader::register('NextgCyberHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/helper.php');
JLoader::register('NextgCyberSiteHelper', JPATH_COMPONENT . '/helpers/sitehelper.php');
$controller = JControllerLegacy::getInstance('NextgCyber');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();

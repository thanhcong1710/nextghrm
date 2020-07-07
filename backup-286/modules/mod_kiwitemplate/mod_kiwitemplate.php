<?php

/**
 * @package mod_kiwitemplate
 * @subpackage  mod_kiwitemplate
 *
 * @copyright Copyright (C) 2015 NextG-ERP. All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://erponline.co.nz
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
require_once __DIR__ . '/helper.php';
$doc = JFactory::getDocument();
$doc->addScript(JUri::root(true) . '/modules/mod_kiwitemplate/assets/js/popup.js');
$doc->addStyleSheet(JUri::root(true) . '/modules/mod_kiwitemplate/assets/css/template.css');
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
require JModuleHelper::getLayoutPath('mod_kiwitemplate', $params->get('layout', 'default'));

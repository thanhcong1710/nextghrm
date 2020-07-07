<?php

/**
 * @package        Kiwi Slider
 * @copyright (C) 2015 by NextG-ERP - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the helper functions only once
require_once __DIR__ . '/helper.php';
$cacheid = md5($module->id);
// Load jQuery
if ($params->get('load_jquery', true)) {
        JHtml::_('jquery.framework');
}

$module_path = 'modules/mod_kiwislider';
$css_path = $module_path . '/css';
// Load css
$doc = JFactory::getDocument();
$doc->addStyleSheet($css_path . '/mod_kiwislider.css?v=1.1');
// Use cookie
$cookie_id = md5($module->id);
$cacheparams = new stdClass;
$cacheparams->cachemode = 'id';
$cacheparams->class = 'modKiwiSliderHelper';
$cacheparams->method = 'display';
$cacheparams->methodparams = array($module->id);
$cacheparams->modeparams = $cacheid;
$displayData = JModuleHelper::moduleCache($module, $params, $cacheparams);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
require JModuleHelper::getLayoutPath('mod_kiwislider', $params->get('layout', 'default'));

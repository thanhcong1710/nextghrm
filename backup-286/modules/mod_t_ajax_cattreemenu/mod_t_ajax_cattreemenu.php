<?php

/**
 * @package mod_t_ajax_cattreemenu
 *
 * @copyright Copyright (C) 2015 NextG-ERP. All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://nextgerp.com
 * @author NextG-ERP support@nextgerp.com
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the helper functions only once
require_once __DIR__ . '/helper.php';

JLoader::register('JCategoryNode', JPATH_BASE . '/libraries/legacy/categories/categories.php');

$cacheid = md5($module->id);
// Load jQuery
if ($params->get('load_jquery', true))
{
        JHtml::_('jquery.framework');
}

$module_path = 'modules/mod_t_ajax_cattreemenu';
$assets_path = $module_path . '/assets';
// Load css
$doc = JFactory::getDocument();
$icon_type = $params->get('icon_type', 'img_icons');
// Load font
if ($params->get('load_font', true))
{
        switch ($icon_type) {
                case 'awesome':
                        $doc->addStyleSheet($assets_path . '/fonts/awesome/css/font-awesome.min.css');
                        break;

                case 'icomoon':
                        $doc->addStyleSheet('media/jui/css/icomoon.css');
                        break;

                default:
                        break;
        }
}

if ($icon_type != 'img_icons')
{
        $font_color = $params->get('font_color', '#eee');
        $doc->addStyleDeclaration('#treemenu-' . $module->id . ' .tree-icon {color: ' . $font_color . ';}');
}

if ($params->get('numitems'))
{
        $numitems_color = $params->get('numitems_color', '#fff');
        $numitems_background = $params->get('numitems_background', '#333');
        $doc->addStyleDeclaration('#treemenu-' . $module->id . ' .numitems {color: ' . $numitems_color . ';background-color: ' . $numitems_background . ';}');
}

// Use cookie
$cookie_id = "";
if ($params->get('remember_selection', true))
{
        $doc->addScript($assets_path . '/js/jquery.cookie.js');
        $cookie_id = $cacheid;
}

$unique = ($params->get('menu_unique', 0)) ? 'true' : 'false';
$options = 'collapsed: true,'
        . 'animated: "medium",'
        . 'persist: "cookie",'
        . 'unique: ' . $unique . ','
        . 'tree_id: "treemenu-' . $module->id . '",'
        . 'cookieId: "' . $cookie_id . '"';

if ($params->get('user_scroll', true))
{
        $options .= ', scroll: true, paddingTop : ' . (int) $params->get('padding_top', 0);
}


// Load js
$doc->addScript($assets_path . '/js/jquery.treeview.js?v=1.0.14');
$doc->addScriptDeclaration('jQuery(document).ready(function(){'
        . 'jQuery("#treemenu-' . $module->id . '").treeview({'
        . $options
        . '});'
        . '});');

$cacheparams = new stdClass;
$cacheparams->cachemode = 'id';
$cacheparams->class = 'ModTAjaxCatTreeMenuHelper';
$cacheparams->method = 'createTreeData';
$cat_id = $params->get('parent', 'root');
$cacheparams->methodparams = array($module->id, 'category', $cat_id, $cookie_id);
$cacheparams->modeparams = $cacheid;
$displayData = JModuleHelper::moduleCache($module, $params, $cacheparams);
if (!empty($displayData->children))
{
        $treeColor = $params->get('tree_color', 'treeview');
        $moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
        echo '<ul class="main-tree filetree ' . $treeColor . '" id="treemenu-' . $module->id . '" data-id="c-' . $cat_id . '" data-mid="' . $module->id . '" data-displayed="' . $displayData->article_loaded . '">';
        require JModuleHelper::getLayoutPath('mod_t_ajax_cattreemenu', $params->get('layout', 'default'));
        echo '</ul>';
}

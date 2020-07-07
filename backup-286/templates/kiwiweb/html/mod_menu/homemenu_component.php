<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
// Note. It is important to remove spaces between elements.
$class = $item->anchor_css ? $item->anchor_css : '';
$class .= ($item->parent && $params->get('showAllChildren')) ? ' dropdown-toggle ' : '';
$class = 'class="' . $class . '" ';
$position = $module->position;
if (!in_array($position, array('main-menu', 'home-menu', 'footer-nav', 'bottom-2', 'bottom-3'))) {
        $dataToggle = ($item->parent && $params->get('showAllChildren')) ? '' : '';
} else {
        $dataToggle = '';
}

$icon = ($item->parent && $item->level == 1 && $params->get('showAllChildren')) ? '<b class="caret"></b>' : '';
$title = $item->anchor_title ? 'title="' . $item->anchor_title . '" ' : '';
if ($item->menu_image) {
        $item->params->get('menu_text', 1) ?
                        $linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" /><span class="image-title">' . $item->title . '</span> ' :
                        $linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" />';
} else {
        $linktype = $item->title;
}

switch ($item->browserNav) :
        default:
        case 0:
                ?><a <?php echo $dataToggle; ?> <?php echo $class; ?>href="<?php echo $item->flink; ?>" <?php echo $title; ?>><?php echo $linktype . $icon; ?></a><?php
                break;
        case 1:
                // _blank
                ?><a <?php echo $class; ?>href="<?php echo $item->flink; ?>" target="_blank" <?php echo $title; ?>><?php echo $linktype; ?></a><?php
                break;
        case 2:
                // window.open
                ?><a <?php echo $class; ?>href="<?php echo $item->flink; ?>" onclick="window.open(this.href, 'targetWindow', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes');
                                                return false;" <?php echo $title; ?>><?php echo $linktype; ?></a>
                <?php
                break;
endswitch;
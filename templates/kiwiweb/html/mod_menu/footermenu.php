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
$useRow = true;
if ($module->position == 'bottom-3') {
        $useRow = false;
}
$col = 4;
$span = 12 / $col;
$totalSpan = 0;
$row_open = false;
$col_open = false;
$list = array_values($list);
foreach ($list as $i => &$item) :
        if ($useRow && $totalSpan >= 12 && $item->level == 1) {
                echo '</div>'; // End row
                $row_open = false;
                $totalSpan = 0;
        }

        if ($useRow && $totalSpan == 0) {
                echo '<div class="row">'; // Create row
                $row_open = true;
        }

        if ($item->level == 1) {
                if ($useRow) {
                        echo '<div class="col-md-' . $span . '">';
                }

                echo '<div class="footer-item">';
                $totalSpan += $span;
                $col_open = true;
        }

        $class = 'item-' . $item->id;
        if ($item->id == $active_id) {
                $class .= ' current';
        }

        if (in_array($item->id, $path)) {
                $class .= ' active';
        } elseif ($item->type == 'alias') {
                $aliasToId = $item->params->get('aliasoptions');
                if (count($path) > 0 && $aliasToId == $path[count($path) - 1]) {
                        $class .= ' active';
                } elseif (in_array($aliasToId, $path)) {
                        $class .= ' alias-parent-active';
                }
        }

        if ($item->type == 'separator') {
                $class .= ' divider';
        }

        if ($item->deeper) {
                $class .= ' deeper';
        }

        if ($item->parent) {
                $class .= ' parent dropdown';
        }

        if ($item->deeper && $item->level > 1) {
                $class = str_replace('dropdown ', ' ', $class);
                $class .= ' dropdown-submenu';
        }

        if (!empty($class)) {
                $class = ' class = "' . trim($class) . '"';
        }

        if ($item->level != 1) {
                echo '<li' . $class . '>';
        }

        if ($item->level == 1) {
                echo '<h3>';
        }

        // Render the menu item.
        switch ($item->type) :
                case 'separator':
                case 'url':
                case 'component':
                case 'heading':
                        require JModuleHelper::getLayoutPath('mod_menu', 'default_' . $item->type);
                        break;

                default:
                        require JModuleHelper::getLayoutPath('mod_menu', 'default_url');
                        break;
        endswitch;

        if ($item->level == 1) {
                echo '</h3>';
        }

        // The next item is deeper.
        if ($item->deeper) {
                echo '<ul class = "list-unstyled">';
        }
        // The next item is shallower.
        elseif ($item->shallower) {
                echo '</li>';
                echo str_repeat('</ul></li> ', $item->level_diff);
        }
        // The next item is on the same level.
        elseif (!$col_open) {
                echo '</li>';
        }

        if (!isset($list[$i + 1]) || $list[$i + 1]->level == 1) {
                echo '</div>'; // Close item
                if ($useRow) {
                        echo ' </div>'; // Close col
                }
                $col_open = false;
        }
endforeach;

if ($useRow && $totalSpan <= 12) {
        echo '</div>'; // End row
}
?>
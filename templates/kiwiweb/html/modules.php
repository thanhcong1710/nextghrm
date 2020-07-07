<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * This is a file to add template specific chrome to module rendering.  To use it you would
 * set the style attribute for the given module(s) include in your template to use the style
 * for each given modChrome function.
 *
 * eg.  To render a module mod_test in the submenu style, you would use the following include:
 * <jdoc:include type="module" name="test" style="submenu" />
 *
 * This gives template designers ultimate control over how modules are rendered.
 *
 * NOTICE: All chrome wrapping methods should be named: modChrome_{STYLE} and take the same
 * two arguments.
 */
/*
 * Module chrome for rendering the module in a submenu
 */
function modChrome_no($module, &$params, &$attribs) {
        if ($module->content) {
                echo $module->content;
        }
}

function modChrome_well($module, &$params, &$attribs) {
        if ($module->content) {
                echo "<div class=\"well " . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";
                if ($module->showtitle) {
                        echo "<h3 class=\"page-header\">" . $module->title . "</h3>";
                }
                echo $module->content;
                echo "</div>";
        }
}

function modChrome_default($module, &$params, &$attribs) {
        if ($module->content) {
                echo "<div class=\"" . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";
                if ($module->showtitle) {
                        echo "<h3 class=\"special-header modtitle\"><span class=\"special-header-inner\">" . $module->title . "</span></h3>";
                }
                echo '<div class="module-box">';
                echo $module->content;
                echo '</div>';
                echo "</div>";
        }
}

function modChrome_topItem($module, &$params, &$attribs) {
        if ($module->content) {
                echo "<div class=\"top-block\">";
                if ($module->showtitle) {
                        echo "<h3 class=\"top-item-header\">" . $module->title . "</h3>";
                }
                echo '<div class="desc">';
                echo $module->content;
                echo '</div>';
                echo "</div>";
        }
}

function modChrome_section($module, &$params, &$attribs) {
        if ($module->content) {
                echo "<div class=\"section " . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";
                if ($module->showtitle) {
                        echo "<h3 class=\"section-header\">" . $module->title . "</h3>";
                }
                echo '<div class="container">';
                echo $module->content;
                echo '</div>';
                echo "</div>";
        }
}

function modChrome_full($module, &$params, &$attribs) {
        if ($module->content) {
                echo "<div class=\"section " . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";
                if ($module->showtitle) {
                        echo "<h3 class=\"section-header\">" . $module->title . "</h3>";
                }
                echo $module->content;
                echo "</div>";
        }
}

?>

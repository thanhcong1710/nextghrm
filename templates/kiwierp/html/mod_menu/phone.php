<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
$root_page = JUri::root();
$base_template = $app->getTemplate(true);
$teplate_params = $base_template->params;
$logo = $teplate_params->get('logo_2');
// Note. It is important to remove spaces between elements.
?>
<nav class="navbar navbar-default">
        <div class="container">
                <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="<?php echo $root_page; ?>"><img src="<?php echo $logo; ?>" class="erp-logo-small" alt="NextG-ERP"/></a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav<?php echo $class_sfx; ?>"<?php
                            $tag = '';
                            if ($params->get('tag_id') != null) {
                                    $tag = $params->get('tag_id') . '';
                                    echo ' id="' . $tag . '"';
                            }
                            ?>>
                                    <?php
                                    foreach ($list as $i => &$item) :
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
                                                    $class = str_replace('dropdown', '', $class);
                                                    $class .= ' dropdown-submenu';
                                                    //var_dump($item);
                                            }

                                            if (!empty($class)) {
                                                    $class = ' class="' . trim($class) . '"';
                                            }

                                            echo '<li' . $class . '>';

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

                                            // The next item is deeper.
                                            if ($item->deeper && $item->level == 1) {
                                                    echo '<ul class="dropdown-menu">';
                                            } elseif ($item->deeper && $item->level > 1) {
                                                    echo '<ul>';
                                            }
                                            // The next item is shallower.
                                            elseif ($item->shallower) {
                                                    echo '</li>';
                                                    echo str_repeat('</ul></li>', $item->level_diff);
                                            }
                                            // The next item is on the same level.
                                            else {
                                                    echo '</li>';
                                            }
                                    endforeach;
                                    ?></ul>
                </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
</nav>
<?php
/**
 * @package pkg_nextgcyber
 * @subpackage  com_nextgcyber
 *
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://domain.com
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;

JHtml::stylesheet('com_nextgcyber/site/main.css', false, true, false);
?>
<div class="dashboard-page">
        <div class="row">
                <div class="col-md-3">
                        <div class="panel-group" id="accordion2">
                                <?php
                                $i = 0;
                                foreach ($displayData->menus as $key => $menuGroup):
                                        ?>
                                        <div class="panel panel-default">
                                                <?php
                                                $active = "";
                                                $class = "";
                                                $display = "";
                                                $i++;

                                                if (count($menuGroup) > 1):
                                                        foreach ($menuGroup as $line):
                                                                if ((isset($line['active']))) {
                                                                        $active = ' active';
                                                                        $display = ' in';
                                                                }
                                                        endforeach;
                                                        $class = " collapse";
                                                        ?>
                                                        <div class="panel-heading<?php echo $active; ?>">
                                                                <a class="accordion-toggle<?php echo $active; ?>" data-toggle="collapse" data-parent="#accordion2" href="#collapse<?php echo $i; ?>">
                                                                        <span class="fa fa-caret-square-o-down"></span>&nbsp;<?php echo JText::_($key); ?>
                                                                </a>
                                                        </div>
                                                        <?php
                                                else:
                                                        $class = ' ishead';
                                                endif;
                                                ?>

                                                <div id="collapse<?php echo $i; ?>" class="panel-collapse<?php echo $class . $active . $display; ?>">
                                                        <?php
                                                        foreach ($menuGroup as $line):
                                                                $lineActive = (isset($line['active'])) ? ' active' : '';
                                                                echo '<div class="panel-body' . $lineActive . '">';
                                                                echo '<a href="' . $line['href'] . '"><i class="' . $line['icon_class'] . '"></i>&nbsp;' . $line['title'] . '</a>';
                                                                echo '</div>';
                                                        endforeach;
                                                        ?>
                                                </div>
                                        </div>
                                <?php endforeach; ?>
                        </div>
                </div>
                <div class="col-md-9">
                        <?php echo $displayData->loadTemplate('item'); ?>
                </div>
        </div>
</div>
<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_category
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
?>
<div class="category-module<?php echo $moduleclass_sfx; ?>">
        <?php if ($grouped) : ?>
                <?php foreach ($list as $group_name => $group) : ?>
                        <div class="row">
                                <?php
                                foreach ($group as $key => $item) :
                                        if ($key == 0) {
                                                $item->heading = true;
                                                echo '<div class="col-md-6">';
                                        }
                                        echo JLayoutHelper::render('joomla.content.video', $item);
                                        if ($key == 0) {
                                                echo '</div><div class="col-md-6">';
                                                echo '<div class="video-scroll video-response">';
                                        }
                                endforeach;
                                echo '</div>';
                                echo '</div>';
                                ?>
                        </div>
                <?php endforeach; ?>
        <?php else : ?>
                <?php
                echo '<div class="row">';
                foreach ($list as $key => $item) :
                        if ($key == 0) {
                                $item->heading = true;
                                echo '<div class="col-md-6">';
                        }
                        echo JLayoutHelper::render('joomla.content.video', $item);
                        if ($key == 0) {
                                echo '</div><div class="col-md-6">';
                                echo '<div class="video-scroll video-response">';
                        }
                endforeach;
                echo '</div>'; // end video scroll
                echo '</div>'; // end col-md
                echo '</div>'; // end row
                ?>
        <?php endif; ?>
</div>
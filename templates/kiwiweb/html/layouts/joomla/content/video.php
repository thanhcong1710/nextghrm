<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

// Create a shortcut for params.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
$imageonly = $this->options->get('imageonly', false);
$isVideo = $this->options->get('isVideo', true);
$class = ($isVideo) ? 'video-item active' : 'video-item';
$class .= (!empty($displayData->heading)) ? ' featured-video' : '';
$is_featured = (!empty($displayData->heading)) ? true : false;
?>
<?php if ($displayData->state == 0 || strtotime($displayData->publish_up) > strtotime(JFactory::getDate()) || ((strtotime($displayData->publish_down) < strtotime(JFactory::getDate())) && $displayData->publish_down != '0000-00-00 00:00:00' )) :
        ?>
        <div class="system-unpublished">
        <?php endif; ?>
        <?php
        $images = json_decode($displayData->images);
        $hasImage = (!empty($images->image_intro)) ? true : false;
        ?>
        <div class="<?php echo $class; ?>" data-id="<?php echo $displayData->id; ?>">

                <?php
                if ($imageonly) {
                        echo JLayoutHelper::render('joomla.content.blog_intro_image', $displayData);
                } else {
                        if ($hasImage) {
                                if (!$is_featured) {
                                        echo '<div class="row">';
                                        echo '<div class="col-xs-4">';
                                }
                                echo JLayoutHelper::render('joomla.content.blog_intro_image', $displayData);
                                if (!$is_featured) {
                                        echo '</div>';
                                        echo '<div class="col-xs-8">';
                                }
                        }
                        ?>
                        <?php if (!$imageonly): ?>
                                <div class="video-detailt">
                                        <div class="video-title">
                                                <?php echo JLayoutHelper::render('joomla.content.new_style_item_title', $displayData); ?>
                                        </div>
                                        <div class="video-info">
                                                <div class="view-counter">
                                                        <p><i class="fa fa-eye"></i>&nbsp;<?php echo JText::sprintf('TPL_KIWI_WEB_VIDEO_VIEW', $displayData->hits); ?>&nbsp;&nbsp;&nbsp;<i class="fa fa-calendar"></i>&nbsp;<?php echo JHTML::_('date', $displayData->created, 'DATE_FORMAT_LC3'); ?></p>
                                                </div>
                                        </div>
                                </div>
                        <?php endif; ?>
                        <?php
                        if (!$is_featured && $hasImage) {
                                echo '</div>';
                                echo '</div>';
                        }
                        ?>
                <?php } ?>

        </div>

        <?php if (!empty($displayData->metadesc)): ?>
                <meta itemprop="description" content="<?php echo $displayData->metadesc; ?>" />
        <?php endif; ?>

        <?php if ($displayData->state == 0 || strtotime($displayData->publish_up) > strtotime(JFactory::getDate()) || ((strtotime($displayData->publish_down) < strtotime(JFactory::getDate())) && $displayData->publish_down != '0000-00-00 00:00:00' )) :
                ?>
        </div>
<?php endif; ?>

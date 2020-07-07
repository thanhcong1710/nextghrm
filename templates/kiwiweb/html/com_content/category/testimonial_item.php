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
$params = $this->item->params;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
$canEdit = $this->item->params->get('access-edit');
$info = $params->get('info_block_position', 0);
$app = JFactory::getApplication();
$tpl_template = $app->getTemplate(true);
$tpl_params = $tpl_template->params;
$tpl_logo = $tpl_params->get('logo');
?>
<div class="testimonial-item">
        <div class="row">
                <div class="col-xs-4">
                        <?php
                        $images = json_decode($this->item->images);
                        $hasImage = (!empty($images->image_intro)) ? true : false;
                        if ($hasImage) {
                                echo JLayoutHelper::render('joomla.content.blog_intro_image', $this->item, JPATH_SITE, array('img_class' => 'testimonial-img'));
                        } else {
                                echo '<img alt="NextG-WEB" src="' . $tpl_logo . '"/>';
                        }
                        ?>
                </div>
                <div class="col-xs-8">
                        <div class="dialogbox">
                                <div class="body">
                                        <span class="tip tip-left"></span>
                                        <div class="message">
                                                <p class="mod-articles-category-introtext">
                                                        <span class="fa fa-quote-left"></span>
                                                        <?php echo strip_tags($this->item->introtext, '<a><em><strong>'); ?>
                                                        <span class="fa fa-quote-right"></span>
                                                </p>
                                                <p style="text-align: right;font-weight: bold;font-size: 12px;">
                                                        <?php if ($params->get('link_titles') == 1) : ?>
                                                                <?php echo JLayoutHelper::render('joomla.content.new_style_item_title', $this->item); ?>
                                                        <?php else: ?>
                                                                <?php echo $this->item->title; ?>
                                                        <?php endif; ?>
                                                </p>
                                        </div>
                                </div>
                        </div>
                </div>
        </div>
</div>
<?php echo $this->item->event->afterDisplayContent; ?>

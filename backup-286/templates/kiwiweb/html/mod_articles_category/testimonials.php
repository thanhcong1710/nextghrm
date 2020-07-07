<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_category
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
$col = 3;
$span = 12 / $col;
$totalSpan = 0;
$app = JFactory::getApplication();
$tpl_template = $app->getTemplate(true);
$tpl_params = $tpl_template->params;
$tpl_logo = $tpl_params->get('logo');
?>
<div class="category-module<?php echo $moduleclass_sfx; ?>">
        <?php foreach ($list as $item) : ?>
                <?php
                if ($totalSpan >= 12) {
                        echo '</div>';
                        $totalSpan = 0;
                }
                if ($totalSpan == 0) {
                        echo '<div class="row">';
                }
                $totalSpan += $span;
                ?>
                <div class="col-md-<?php echo $span; ?>">
                        <div class="testimonial-item">
                                <div class="row">
                                        <div class="col-xs-4">
                                                <?php
                                                $images = json_decode($item->images);
                                                $hasImage = (!empty($images->image_intro)) ? true : false;
                                                if ($hasImage) {
                                                        echo JLayoutHelper::render('joomla.content.blog_intro_image', $item, JPATH_SITE, array('img_class' => 'testimonial-img'));
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
                                                                        <?php if ($item->displayHits) : ?>
                                                                                <div class="mod-articles-category-hits">
                                                                                        (<?php echo $item->displayHits; ?>)
                                                                                </div>
                                                                        <?php endif; ?>

                                                                        <?php if ($params->get('show_author')) : ?>
                                                                                <div class="mod-articles-category-writtenby">
                                                                                        <?php echo $item->displayAuthorName; ?>
                                                                                </div>
                                                                        <?php endif; ?>

                                                                        <?php if ($item->displayCategoryTitle) : ?>
                                                                                <div class="mod-articles-category-category">
                                                                                        (<?php echo $item->displayCategoryTitle; ?>)
                                                                                </div>
                                                                        <?php endif; ?>

                                                                        <?php if ($item->displayDate) : ?>
                                                                                <div class="mod-articles-category-date">
                                                                                        <?php echo $item->displayDate; ?>
                                                                                </div>
                                                                        <?php endif; ?>

                                                                        <?php if ($params->get('show_introtext')) : ?>

                                                                                <p class="mod-articles-category-introtext">
                                                                                        <span class="fa fa-quote-left"></span>
                                                                                        <?php echo $item->displayIntrotext; ?>
                                                                                        <span class="fa fa-quote-right"></span>
                                                                                </p>

                                                                        <?php endif; ?>
                                                                        <p style="text-align: right;font-weight: bold;font-size: 12px;">
                                                                                <?php if ($params->get('link_titles') == 1) : ?>
                                                                                        <a class="mod-articles-category-title <?php echo $item->active; ?>" href="<?php echo $item->link; ?>">
                                                                                                <?php echo $item->title; ?>
                                                                                        </a>
                                                                                <?php else: ?>
                                                                                        <?php echo $item->title; ?>
                                                                                <?php endif; ?>
                                                                        </p>

                                                                        <?php if ($params->get('show_readmore')) : ?>
                                                                                <p class="mod-articles-category-readmore">
                                                                                        <a class="mod-articles-category-title <?php echo $item->active; ?>" href="<?php echo $item->link; ?>">
                                                                                                <?php if ($item->params->get('access-view') == false) : ?>
                                                                                                        <?php echo JText::_('MOD_ARTICLES_CATEGORY_REGISTER_TO_READ_MORE'); ?>
                                                                                                <?php elseif ($readmore = $item->alternative_readmore) : ?>
                                                                                                        <?php echo $readmore; ?>
                                                                                                        <?php echo JHtml::_('string.truncate', $item->title, $params->get('readmore_limit')); ?>
                                                                                                <?php elseif ($params->get('show_readmore_title', 0) == 0) : ?>
                                                                                                        <?php echo JText::sprintf('MOD_ARTICLES_CATEGORY_READ_MORE_TITLE'); ?>
                                                                                                <?php else : ?>
                                                                                                        <?php echo JText::_('MOD_ARTICLES_CATEGORY_READ_MORE'); ?>
                                                                                                        <?php echo JHtml::_('string.truncate', $item->title, $params->get('readmore_limit')); ?>
                                                                                                <?php endif; ?>
                                                                                        </a>
                                                                                </p>
                                                                        <?php endif; ?>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
        <?php endforeach; ?>
        <?php
        if ($totalSpan > 0) {
                echo '</div>';
        }
        ?>
        <div class="view-all" style="text-align:right;margin-bottom:10px;">
                <?php
                $catids = $params->get('catid');
                $c_link = JRoute::_(ContentHelperRoute::getCategoryRoute($catids[0]));
                ?>
                <a class="btn btn-link" href="<?php echo $c_link; ?>"><span class="fa fa-angle-double-right"></span>&nbsp;View All</a>
        </div>
</div>

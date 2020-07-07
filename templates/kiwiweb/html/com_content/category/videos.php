<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
JHtml::_('jquery.framework');
$document = JFactory::getDocument();
$document->addScript(JUri::root(true) . '/templates/kiwiweb/js/video.js');
JHtml::_('behavior.caption');
?>
<div class="kiwiweb-template videos<?php echo $this->pageclass_sfx; ?>">
        <div class="video-library kiwerp-video-group">
                <?php if ($this->params->get('show_page_heading', 1)) : ?>
                        <h1 class="item-title">
                                <i class="fa fa-video-camera video-icon-square"></i> <?php echo $this->escape($this->params->get('page_heading')); ?>
                                <?php /* ?>
                                  <div class="visible-desktop visible-lg visible-md video-search-form">
                                  <?php echo JLayoutHelper::render('joomla.form.searchform', $this->category); ?>
                                  </div>
                                  <?php */ ?>
                        </h1>
                <?php endif; ?>

                <?php if ($this->params->get('show_category_title', 1) or $this->params->get('page_subheading')) : ?>
                        <h2> <?php echo $this->escape($this->params->get('page_subheading')); ?>
                                <?php if ($this->params->get('show_category_title')) : ?>
                                        <span class="subheading-category"><?php echo $this->category->title; ?></span>
                                <?php endif; ?>
                        </h2>
                <?php endif; ?>

                <?php if ($this->params->get('show_cat_tags', 1) && !empty($this->category->tags->itemTags)) : ?>
                        <?php $this->category->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
                        <?php echo $this->category->tagLayout->render($this->category->tags->itemTags); ?>
                <?php endif; ?>

                <?php if ($this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
                        <div class="category-desc clearfix">
                                <?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
                                        <img alt="<?php echo htmlspecialchars($this->category->title); ?>" src="<?php echo $this->category->getParams()->get('image'); ?>" />
                                <?php endif; ?>
                                <?php if ($this->params->get('show_description') && $this->category->description) : ?>
                                        <?php echo JHtml::_('content.prepare', $this->category->description, '', 'com_content.category'); ?>
                                <?php endif; ?>
                        </div>
                <?php endif; ?>

                <?php if (empty($this->lead_items) && empty($this->link_items) && empty($this->intro_items)) : ?>
                        <?php if ($this->params->get('show_no_articles', 1)) : ?>
                                <p><?php echo JText::_('COM_CONTENT_NO_ARTICLES'); ?></p>
                        <?php endif; ?>
                <?php endif; ?>

                <div class="row">
                        <div class="col-md-6">
                                <?php $leadingcount = 0; ?>
                                <?php if (!empty($this->lead_items)) : ?>
                                        <div class="items-leading clearfix">
                                                <?php foreach ($this->lead_items as &$item) : ?>
                                                        <div class="leading-<?php echo $leadingcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>"
                                                             itemscope itemtype="http://schema.org/TechArticle">
                                                                     <?php
                                                                     $this->item = & $item;
                                                                     $this->item->heading = true;
                                                                     echo $this->loadTemplate('item');
                                                                     ?>
                                                        </div>
                                                        <?php $leadingcount++; ?>
                                                <?php endforeach; ?>
                                        </div><!-- end items-leading -->
                                <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                                <div class="video-scroll video-response">
                                        <?php
                                        $introcount = (count($this->intro_items));
                                        $counter = 0;
                                        ?>
                                        <?php if (!empty($this->intro_items)) : ?>
                                                <?php foreach ($this->intro_items as $key => &$item) : ?>
                                                        <?php $rowcount = ((int) $key % (int) $this->columns) + 1; ?>
                                                        <?php if ($rowcount == 1) : ?>
                                                                <?php $row = $counter / $this->columns; ?>
                                                                <div class="items-row cols-<?php echo (int) $this->columns; ?> <?php echo 'row-' . $row; ?> clearfix">
                                                                <?php endif; ?>
                                                                <div>
                                                                        <div class="column-<?php echo $rowcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>"
                                                                             itemscope itemtype="http://schema.org/TechArticle">
                                                                                     <?php
                                                                                     $this->item = & $item;
                                                                                     echo $this->loadTemplate('item');
                                                                                     ?>
                                                                        </div>
                                                                        <!-- end item -->
                                                                        <?php $counter++; ?>
                                                                </div><!-- end col-md- -->
                                                                <?php if (($rowcount == $this->columns) or ( $counter == $introcount)) : ?>
                                                                </div><!-- end row -->
                                                        <?php endif; ?>
                                                <?php endforeach; ?>
                                        <?php endif; ?>
                                </div>
                        </div>
                </div>
        </div>

        <?php if (!empty($this->link_items)) : ?>
                <?php echo $this->loadTemplate('links'); ?>
        <?php endif; ?>

        <?php if (!empty($this->children[$this->category->id]) && $this->maxLevel != 0) : ?>
                <div class="cat-children">
                        <?php if ($this->params->get('show_category_heading_title_text', 1) == 1) : ?>
                                <h3> <?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?> </h3>
                        <?php endif; ?>
                        <?php echo $this->loadTemplate('children'); ?> </div>
        <?php endif; ?>
        <?php if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
                <div class="pagination">
                        <?php if ($this->params->def('show_pagination_results', 1)) : ?>
                                <p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
                        <?php endif; ?>
                        <?php echo $this->pagination->getPagesLinks(); ?>
                </div>
        <?php endif; ?>
</div>

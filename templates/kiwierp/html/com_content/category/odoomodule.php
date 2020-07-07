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

JHtml::_('behavior.caption');
?>
<div class="kiwierp-template odoo-module<?php echo $this->pageclass_sfx; ?>" itemscope='itemscope' itemtype='http://schema.org/EntryPoint'>
        <?php if ($this->params->get('show_page_heading', 1)) : ?>
                <div class="page-header">
                        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
                </div>
        <?php endif; ?>

        <?php if ($this->params->get('show_category_title', 1) or $this->params->get('page_subheading')) : ?>
                <h2> <?php echo $this->escape($this->params->get('page_subheading')); ?>
                        <?php if ($this->params->get('show_category_title')) : ?>
                                <span class="subheading-category"><?php echo $this->category->title; ?></span>
                        <?php endif; ?>
                </h2>
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
                        <p><?php echo JText::_('TPL_KIWI_ERP_NO_ARTICLES'); ?></p>
                <?php endif; ?>
        <?php endif; ?>

        <?php
        $leadingcount = (count($this->lead_items));
        $counter = 0;
        ?>
        <?php if (!empty($this->lead_items)) : ?>
                <div class="items-leading clearfix well well-small">
                        <?php foreach ($this->lead_items as $key => &$item) : ?>
                                <?php $rowcount = ((int) $key % (int) $this->columns) + 1; ?>
                                <?php if ($rowcount == 1) : ?>
                                        <?php $row = $counter / $this->columns; ?>
                                        <div class="items-row cols-<?php echo (int) $this->columns; ?> <?php echo 'row-' . $row; ?> row clearfix">
                                        <?php endif; ?>
                                        <div class="col-md-<?php echo round((12 / $this->columns)); ?> leading-<?php echo $key; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>"
                                             itemprop="application" itemscope itemtype="http://schema.org/SoftwareApplication">
                                                     <?php
                                                     $this->item = & $item;
                                                     $params = $this->item->params;
                                                     ?>
                                                <div class="media">
                                                        <?php
                                                        echo JLayoutHelper::render('joomla.content.odoo_intro_image', $this->item);
                                                        echo JLayoutHelper::render('joomla.content.default_schema', array('item' => $this->item, 'params' => $params));
                                                        ?>
                                                        <div class="media-body">
                                                                <?php echo JLayoutHelper::render('joomla.content.new_style_item_title', $this->item, null, array('heading' => 'h3')); ?>
                                                                <div itemprop="description">
                                                                        <?php echo $this->item->metadesc; ?>
                                                                </div>

                                                                <?php
                                                                if ($params->get('access-view')) :
                                                                        $link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
                                                                else :
                                                                        $menu = JFactory::getApplication()->getMenu();
                                                                        $active = $menu->getActive();
                                                                        $itemId = $active->id;
                                                                        $link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
                                                                        $returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
                                                                        $link = new JUri($link1);
                                                                        $link->setVar('return', base64_encode($returnURL));
                                                                endif;
                                                                ?>
                                                        </div>
                                                </div>
                                                <?php $counter++; ?>
                                        </div>
                                        <?php if (($rowcount == $this->columns) or ( $counter == $leadingcount)) : ?>
                                        </div><!-- end row -->
                                <?php endif; ?>
                        <?php endforeach; ?>
                </div><!-- end items-leading -->
        <?php endif; ?>

        <?php
        $introcount = (count($this->intro_items));
        $counter = 0;
        ?>

        <?php if (!empty($this->intro_items)) : ?>
                <?php foreach ($this->intro_items as $key => &$item) : ?>
                        <?php $rowcount = ((int) $key % (int) $this->columns) + 1; ?>
                        <?php if ($rowcount == 1) : ?>
                                <?php $row = $counter / $this->columns; ?>
                                <div class="items-row cols-<?php echo (int) $this->columns; ?> <?php echo 'row-' . $row; ?> row clearfix">
                                <?php endif; ?>
                                <div class="col-md-<?php echo round((12 / $this->columns)); ?>">
                                        <div class="item column-<?php echo $rowcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>"
                                             itemprop="application" itemscope itemtype="http://schema.org/SoftwareApplication">
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

        <?php if (!empty($this->link_items)) : ?>
                <div class="items-more">
                        <?php echo $this->loadTemplate('links'); ?>
                </div>
        <?php endif; ?>

        <?php if (!empty($this->children[$this->category->id]) && $this->maxLevel != 0) : ?>
                <div class="cat-children">
                        <?php if ($this->params->get('show_category_heading_title_text', 1) == 1) : ?>
                                <h3> <?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?> </h3>
                        <?php endif; ?>
                        <?php echo $this->loadTemplate('children'); ?> </div>
        <?php endif; ?>
        <?php if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
                <nav class="pagination">
                        <?php if ($this->params->def('show_pagination_results', 1)) : ?>
                                <p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
                        <?php endif; ?>
                        <?php echo $this->pagination->getPagesLinks(); ?>
                </nav>
        <?php endif; ?>

        <?php if ($this->params->get('show_cat_tags', 1) && !empty($this->category->tags->itemTags)) : ?>
                <?php $this->category->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
                <?php echo $this->category->tagLayout->render($this->category->tags->itemTags); ?>
        <?php endif; ?>
</div>

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

// Create shortcuts to some parameters.
$params = $this->item->params;
$images = json_decode($this->item->images);
$urls = json_decode($this->item->urls);
$canEdit = $params->get('access-edit');
$user = JFactory::getUser();
$info = $params->get('info_block_position', 0);
JHtml::_('behavior.caption');
$useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date') || $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author'));
?>
<div class="base-template item-page<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="http://schema.org/BlogPosting">
        <meta itemprop="inLanguage" content="<?php echo ($this->item->language === '*') ? JFactory::getConfig()->get('language') : $this->item->language; ?>" />
        <?php
        if (!$useDefList) {
                echo JLayoutHelper::render('joomla.content.default_schema', array('item' => $this->item, 'params' => $params));
        }

        if (!empty($this->item->metakey)) {
                echo '<meta itemprop="keywords" content="' . $this->item->metakey . '" />';
        }

        if (!empty($this->item->metadesc)) {
                echo '<meta itemprop="description" content="' . $this->item->metadesc . '" />';
        }
        ?>

        <?php if ($this->params->get('show_page_heading', 1)) : ?>
                <div class="page-header">
                        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
                </div>
                <?php
        endif;
        if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && $this->item->paginationrelative) {
                echo $this->item->pagination;
        }
        ?>
        <?php if (!$useDefList && $this->print) : ?>
                <div id="pop-print" class="btn btn-default hidden-print"><span class="fa fa-print"></span>
                        <?php echo JHtml::_('icon.print_screen', $this->item, $params); ?>
                </div>
                <div class="clearfix"> </div>
        <?php endif; ?>
        <?php if ($params->get('show_title') || $params->get('show_author')) : ?>
                <div class="item-title">
                        <h1 itemprop="name">
                                <?php if ($params->get('show_title')) : ?>
                                        <?php if ($params->get('link_titles') && !empty($this->item->readmore_link)) : ?>
                                                <a href="<?php echo $this->item->readmore_link; ?>" itemprop="url"> <?php echo $this->escape($this->item->title); ?></a>
                                        <?php else : ?>
                                                <?php echo $this->escape($this->item->title); ?>
                                        <?php endif; ?>
                                <?php endif; ?>
                        </h1>
                        <?php if ($this->item->state == 0) : ?>
                                <span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
                        <?php endif; ?>
                        <?php if (strtotime($this->item->publish_up) > strtotime(JFactory::getDate())) : ?>
                                <span class="label label-warning"><?php echo JText::_('JNOTPUBLISHEDYET'); ?></span>
                        <?php endif; ?>
                        <?php if ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != '0000-00-00 00:00:00') : ?>
                                <span class="label label-warning"><?php echo JText::_('JEXPIRED'); ?></span>
                        <?php endif; ?>
                </div>
        <?php endif; ?>
        <?php if (!$this->print) : ?>
                <?php if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
                        <?php echo JLayoutHelper::render('joomla.content.icons', array('params' => $params, 'item' => $this->item, 'print' => false)); ?>
                <?php endif; ?>
        <?php else : ?>
                <?php if ($useDefList) : ?>
                        <div id="pop-print" class="btn btn-default hidden-print"><span class="fa fa-print"></span>
                                <?php echo JHtml::_('icon.print_screen', $this->item, $params); ?>
                        </div>
                <?php endif; ?>
        <?php endif; ?>

        <?php if ($info == 0 && $params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
                <?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
                <?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
        <?php endif; ?>

        <?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
                <?php echo JLayoutHelper::render('joomla.content.inline_info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
        <?php endif; ?>
        <?php echo JLayoutHelper::render('joomla.content.social_buttons'); ?>

        <?php
        if (!$params->get('show_intro')) : echo $this->item->event->afterDisplayTitle;
        endif;
        ?>
        <?php echo $this->item->event->beforeDisplayContent; ?>

        <?php if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '0')) || ($params->get('urls_position') == '0' && empty($urls->urls_position))) || (empty($urls->urls_position) && (!$params->get('urls_position')))) :
                ?>
                <?php echo $this->loadTemplate('links'); ?>
        <?php endif; ?>
        <?php if ($params->get('access-view')): ?>
                <?php if (isset($images->image_fulltext) && !empty($images->image_fulltext)) : ?>
                        <?php $imgfloat = (empty($images->float_fulltext)) ? $params->get('float_fulltext') : $images->float_fulltext; ?>
                        <div class="item-image"><img class="img-thumbnail img-responsive<?php echo ($images->image_fulltext_caption) ? (' caption"' . ' title="' . htmlspecialchars($images->image_fulltext_caption) . '"') : '"'; ?>
                                                     src="<?php echo htmlspecialchars($images->image_fulltext); ?>" alt="<?php echo htmlspecialchars($this->item->title); ?>" itemprop="image" /> </div>
                                             <?php endif; ?>
                                             <?php
                                             if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && !$this->item->paginationrelative):
                                                     echo $this->item->pagination;
                                             endif;
                                             ?>
                                             <?php
                                             if (isset($this->item->toc)) :
                                                     echo $this->item->toc;
                                             endif;
                                             ?>
                <div itemprop="articleBody">
                        <?php echo $this->item->text; ?>
                </div>

                <?php echo JLayoutHelper::render('joomla.content.social_large_buttons'); ?>

                <?php if ($useDefList && ($info == 1 || $info == 2)) : ?>
                        <?php echo JLayoutHelper::render('joomla.content.inline_info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'below')); ?>
                        <?php if ($params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
                                <?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
                                <?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
                        <?php endif; ?>
                <?php endif; ?>

                <?php
                if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition && !$this->item->paginationrelative):
                        echo $this->item->pagination;
                        ?>
                <?php endif; ?>
                <?php if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '1')) || ($params->get('urls_position') == '1'))) : ?>
                        <?php echo $this->loadTemplate('links'); ?>
                <?php endif; ?>
                <?php // Optional teaser intro text for guests ?>
        <?php elseif ($params->get('show_noauth') == true && $user->get('guest')) : ?>
                <?php echo $this->item->introtext; ?>

                <?php //Optional link to let them register to see the whole article.   ?>
                <?php
                if ($params->get('show_readmore') && $this->item->fulltext != null) :
                        $link1 = JRoute::_('index.php?option=com_users&view=login');
                        $link = new JUri($link1);
                        ?>
                        <p class="readmore">
                                <a href="<?php echo $link; ?>">
                                        <?php $attribs = json_decode($this->item->attribs); ?>
                                        <?php
                                        if ($attribs->alternative_readmore == null) :
                                                echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
                                        elseif ($readmore = $this->item->alternative_readmore) :
                                                echo $readmore;
                                                if ($params->get('show_readmore_title', 0) != 0) :
                                                        echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
                                                endif;
                                        elseif ($params->get('show_readmore_title', 0) == 0) :
                                                echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
                                        else :
                                                echo JText::_('COM_CONTENT_READ_MORE');
                                                echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
                                        endif;
                                        ?>
                                </a>
                        </p>
                <?php endif; ?>
        <?php endif; ?>
        <?php
        if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition && $this->item->paginationrelative) :
                echo $this->item->pagination;
                ?>
        <?php endif; ?>
        <?php echo $this->item->event->afterDisplayContent; ?> </div>

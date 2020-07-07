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
?>
<div class="media">
        <?php if ($this->item->state == 0 || strtotime($this->item->publish_up) > strtotime(JFactory::getDate()) || ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != '0000-00-00 00:00:00' )) :
                ?>
                <div class="system-unpublished">
                <?php endif; ?>

                <?php echo JLayoutHelper::render('joomla.content.odoo_intro_image', $this->item); ?>

                <div class="media-body">
                        <?php
                        echo $this->item->event->beforeDisplayContent;
                        echo JLayoutHelper::render('joomla.content.new_style_item_title', $this->item, null, array('heading' => 'h4'));
                        if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) :
                                echo JLayoutHelper::render('joomla.content.icons', array('params' => $params, 'item' => $this->item, 'print' => false));
                        endif;

                        echo JLayoutHelper::render('joomla.content.default_schema', array('item' => $this->item, 'params' => $params));
                        ?>
                        <div itemprop="description">
                                <?php echo $this->item->metadesc; ?>
                        </div>
                </div>
                <?php if ($this->item->state == 0 || strtotime($this->item->publish_up) > strtotime(JFactory::getDate()) || ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != '0000-00-00 00:00:00' )) :
                        ?>
                </div>
        <?php endif; ?>

        <?php echo $this->item->event->afterDisplayContent; ?>
</div>
<div class="visible-xs visible-sm line"></div>

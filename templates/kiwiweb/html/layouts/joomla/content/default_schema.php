<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
$author = ($displayData['item']->created_by_alias ? $displayData['item']->created_by_alias : $displayData['item']->author);
?>
<meta itemprop="author" content="<?php echo $author; ?>" />
<meta itemprop="genre" content="<?php echo $displayData['item']->parent_title; ?>" />
<meta itemprop="datePublished" content="<?php echo JHtml::_('date', $displayData['item']->publish_up, 'c'); ?>" />
<meta itemprop="dateCreated" content="<?php echo JHtml::_('date', $displayData['item']->created, 'c'); ?>" />

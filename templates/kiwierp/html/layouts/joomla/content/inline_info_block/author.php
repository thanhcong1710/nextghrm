<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('JPATH_BASE') or die;
?>
<li class="createdby" itemprop="author" itemscope itemtype="http://schema.org/Person">
        <?php $author = ($displayData['item']->created_by_alias ? $displayData['item']->created_by_alias : $displayData['item']->author); ?>
        <?php $author = '<span itemprop="name">' . $author . '</span>'; ?>
        <?php if (!empty($displayData['item']->contact_link) && $displayData['params']->get('link_author') == true) : ?>
                <?php echo JText::sprintf('<span class="fa fa-user"></span> ', JHtml::_('link', $displayData['item']->contact_link, $author, array('itemprop' => 'url'))); ?>
        <?php else : ?>
                <?php echo '<span class="fa fa-user"></span> '.$author; ?>
        <?php endif; ?>
</li>

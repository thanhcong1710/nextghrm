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
<li class="category-name">
        <?php $title = $this->escape($displayData['item']->category_title); ?>
        <?php if ($displayData['params']->get('link_category') && $displayData['item']->catslug) : ?>
                <?php $url = '<a class="text-muted" href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($displayData['item']->catslug)) . '" itemprop="genre">' . $title . '</a>'; ?>
                <?php echo '<span class="fa fa-folder"></span> '. $url; ?>
        <?php else : ?>
                <?php echo '<span class="fa fa-folder"></span> <span itemprop="genre">' . $title . '</span>'; ?>
        <?php endif; ?>
</li>
<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
?>


<ol class="nav nav-tabs nav-stacked">
        <?php
        foreach ($this->link_items as &$item) :
                $itemUrl = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid));
                ?>
                <li itemprop="application" itemscope itemtype="http://schema.org/SoftwareApplication">
                        <meta itemprop="name" content="<?php echo $item->title; ?>" />
                        <?php if (!empty($item->metadesc)): ?>
                                <meta itemprop="description" content="<?php echo $item->metadesc; ?>" />
                        <?php endif; ?>

                        <?php echo JLayoutHelper::render('joomla.content.default_schema', array('item' => $item, 'params' => $item->params)); ?>

                        <?php if (isset($item->image_intro) && $item->image_intro): ?>
                                <meta content="<?php echo htmlspecialchars($item->image_intro); ?>" itemprop="thumbnailUrl" />
                        <?php endif; ?>
                        <a href="<?php echo $itemUrl; ?>" itemprop="url" title="<?php echo $this->escape($item->title); ?>"><?php echo $item->title; ?></a>
                </li>
        <?php endforeach; ?>
</ol>

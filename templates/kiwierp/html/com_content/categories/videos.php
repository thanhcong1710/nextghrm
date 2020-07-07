<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
$com_path = JPATH_SITE . '/components/com_content/';
JModelLegacy::addIncludePath($com_path . '/models', 'ContentModel');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
JHtml::_('jquery.framework');
$document = JFactory::getDocument();
$document->addScript(JUri::root(true) . '/templates/kiwierp/js/video.js');
JHtml::_('behavior.caption');
echo '<div class="kiwierp-template videos">';
echo JLayoutHelper::render('joomla.content.categories_default', $this);
echo $this->loadTemplate('items');
echo '</div>';
?>

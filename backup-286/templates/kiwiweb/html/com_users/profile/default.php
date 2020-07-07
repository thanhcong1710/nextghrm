<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
?>
<div class="profile<?php echo $this->pageclass_sfx ?>">
        <?php if ($this->params->get('show_page_heading')) : ?>
                <div class="page-header">
                        <h1>
                                <?php echo $this->escape($this->params->get('page_heading')); ?>
                        </h1>
                </div>
        <?php endif; ?>
        <?php if (JFactory::getUser()->id == $this->data->id) : ?>
                <ul class="btn-toolbar pull-right">
                        <li class="btn-group">
                                <a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_users&task=profile.edit&user_id=' . (int) $this->data->id); ?>">
                                        <span class="fa fa-user"></span> <?php echo JText::_('COM_USERS_EDIT_PROFILE'); ?></a>
                        </li>
                        <li class="btn-group">
                                <?php JLoader::register('NextgCyberHelperRoute', JPATH_SITE . '/components/com_nextgcyber/helpers/route.php'); ?>
                                <a class="btn btn-default" href="<?php echo NextgCyberHelperRoute::getDashboardRoute(); ?>">
                                        <span class="fa fa-dashboard"></span> <?php echo JText::_('TPL_KIWI_WEB_DASHBOARD'); ?></a>
                        </li>

                </ul>
        <?php endif; ?>
        <?php echo $this->loadTemplate('core'); ?>

        <?php echo $this->loadTemplate('params'); ?>

        <?php echo $this->loadTemplate('custom'); ?>

</div>

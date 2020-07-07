<?php
/**
 * @package pkg_nextgcyber NextgCyber for Joomla
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2015 NextgCyber . All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://nextgerp.com
 * @author NextG-ERP support@nextgerp.com
 */
defined('_JEXEC') or die;

// Create some shortcuts.
$params = &$this->item->params;
$n = count($this->items);
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title"><?php echo JText::_('COM_NEXTGCYBER_DASHBOARD_INSTANCES_HEADER'); ?></h1>
    </div>
    <div class="panel-body">
        <form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm" class="form-inline">
            <fieldset class="filters btn-toolbar clearfix">
                <div class="btn-group">
                    <label class="filter-search-lbl element-invisible" for="filter-search">
                        <?php echo JText::_('COM_NEXTGCYBER_INSTANCE_FILTER_LABEL') . '&#160;'; ?>
                    </label>
                    <input type="text" name="filter_search" id="filter-search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="form-control" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_NEXTGCYBER_INSTANCE_FILTER_SEARCH_DESC'); ?>" placeholder="<?php echo JText::_('COM_NEXTGCYBER_INSTANCE_FILTER_LABEL'); ?>" />
                </div>
                <div class="btn-group pull-right">
                    <label for="limit" class="element-invisible">
                        <?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
                    </label>
                    <?php echo $this->pagination->getLimitBox(); ?>
                </div>
                <input type="hidden" name="filter_order" value="" />
                <input type="hidden" name="filter_order_Dir" value="" />
                <input type="hidden" name="limitstart" value="" />
                <input type="hidden" name="task" value="" />
            </fieldset>

            <?php if (empty($this->items)) : ?>
                <p><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_EMPTY_LABEL'); ?></p>
                <p><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_EMPTY_DESC'); ?></p>
            <?php else: ?>
                <?php echo JLayoutHelper::render('com_nextgcyber.instances.list', $this->items, JPATH_COMPONENT, null); ?>
            <?php endif; ?>

            <?php // Add pagination links   ?>
            <?php if (!empty($this->items)) : ?>
                <?php if (($this->params->def('show_pagination', 2) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
                    <div class="pagination">

                        <?php if ($this->params->def('show_pagination_results', 1)) : ?>
                            <p class="counter pull-right">
                                <?php echo $this->pagination->getPagesCounter(); ?>
                            </p>
                        <?php endif; ?>

                        <?php echo $this->pagination->getPagesLinks(); ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </form>
    </div>
</div>


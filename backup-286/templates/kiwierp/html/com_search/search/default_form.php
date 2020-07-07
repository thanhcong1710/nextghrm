<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_search
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;
$lang = JFactory::getLanguage();
$upper_limit = $lang->getUpperLimitSearchWord();
?>

<form id="searchForm" action="<?php echo JRoute::_('index.php?option=com_search'); ?>" method="post">
        <div class="form-group">
                <div class="input-group">
                        <input type="text"
                               name="searchword"
                               placeholder="<?php echo JText::_('COM_SEARCH_SEARCH_KEYWORD'); ?>"
                               id="search-searchword"
                               size="30"
                               maxlength="<?php echo $upper_limit; ?>"
                               value="<?php echo $this->escape($this->origkeyword); ?>"
                               class="form-control" />
                        <span class="input-group-btn">
                                <button name="Search" onclick="this.form.submit()"
                                        class="btn btn-success hasTooltip"
                                        title="<?php echo JHtml::tooltipText('COM_SEARCH_SEARCH'); ?>">
                                        <span class="fa fa-search"></span> <?php echo JHtml::tooltipText('COM_SEARCH_SEARCH'); ?>
                                </button>
                        </span>
                </div>
        </div>
        <input type="hidden" name="task" value="search" />
        <div class="form-group searchintro<?php echo $this->params->get('pageclass_sfx'); ?>">
                <?php if (!empty($this->searchword)): ?>
                        <div class="alert <?php echo $this->total > 0 ? 'alert-success' : 'alert-warning'; ?>">
                                <p><?php echo JText::plural('COM_SEARCH_SEARCH_KEYWORD_N_RESULTS', $this->total); ?></p>
                        </div>
                <?php endif; ?>
        </div>

        <fieldset class="form-group phrases">
                <legend><?php echo JText::_('COM_SEARCH_FOR'); ?>
                </legend>
                <div class="phrases-box">

                        <?php
                        $this->lists['searchphrase'] = str_replace('class="radio"', '', $this->lists['searchphrase']);
                        $this->lists['searchphrase'] = str_replace('class="controls"', 'class="form-group"', $this->lists['searchphrase']);
                        echo $this->lists['searchphrase'];
                        ?>
                </div>
                <div class="ordering-box">
                        <label for="ordering" class="ordering">
                                <?php echo JText::_('COM_SEARCH_ORDERING'); ?>
                        </label>
                        <?php echo $this->lists['ordering']; ?>
                </div>
        </fieldset>

        <?php if ($this->params->get('search_areas', 1)) : ?>
                <fieldset class="alert alert-info">
                        <?php
                        foreach ($this->searchareas['search'] as $val => $txt) :
                                $checked = is_array($this->searchareas['active']) && in_array($val, $this->searchareas['active']) ? 'checked="checked"' : '';
                                ?>
                                <div class="checkbox">
                                        <label for="area-<?php echo $val; ?>">
                                                <input type="checkbox" name="areas[]" value="<?php echo $val; ?>" id="area-<?php echo $val; ?>" <?php echo $checked; ?> />
                                                <?php echo JText::_($txt); ?>
                                        </label>
                                </div>
                        <?php endforeach; ?>
                </fieldset>
        <?php endif; ?>

        <?php if ($this->total > 0) : ?>

                <div class="form-limit">
                        <label for="limit">
                                <?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
                        </label>
                        <?php echo $this->pagination->getLimitBox(); ?>
                </div>
                <p class="counter">
                        <?php echo $this->pagination->getPagesCounter(); ?>
                </p>

        <?php endif; ?>

</form>

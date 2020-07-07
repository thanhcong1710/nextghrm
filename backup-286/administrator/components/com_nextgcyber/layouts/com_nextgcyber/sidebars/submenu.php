<?php
/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author Daniel.Vu
 */
// No direct access

defined('_JEXEC') or die('Restricted access');
JHtml::stylesheet('com_nextgcyber/font-awesome.min.css', false, true, false);
JHtml::stylesheet('com_nextgcyber/main.css', false, true, false);
JHtmlBehavior::core();

JFactory::getDocument()->addScriptDeclaration('
	jQuery(document).ready(function($)
	{
		if (window.toggleSidebar)
		{
			toggleSidebar(true);
		}
		else
		{
			$("#j-toggle-sidebar-header").css("display", "none");
			$("#j-toggle-button-wrapper").css("display", "none");
		}
	});
');
?>
<div id="j-toggle-sidebar-wrapper">
    <div id="j-toggle-button-wrapper" class="j-toggle-button-wrapper">
        <?php echo JLayoutHelper::render('joomla.sidebars.toggle'); ?>
    </div>
    <div id="sidebar">
        <div class="sidebar-nav"  id="accordion2">
            <?php if ($displayData->displayMenu) : ?>
                <?php
                foreach ($displayData->list as $item) :
                    $active = (isset($item['active']) && $item['active'] == 1) ? ' active' : '';
                    $collapse = (isset($item['active']) && $item['active'] == 1) ? ' in' : '';
                    if (isset($item['is_parent']) && $item['is_parent']):
                        ?>

                        <div class="accordion-group">
                            <div class="accordion-heading<?php echo $active; ?>">
                                <a class="accordion-toggle<?php echo $active; ?>" data-toggle="collapse" data-parent="#accordion2" href="#<?php echo $item['group']; ?>">
                                    <?php if ($item['icon']): ?>
                                        <span class="<?php echo $item['icon']; ?>"></span>
                                    <?php endif; ?>
                                    <?php echo $item['name']; ?>
                                </a>
                            </div>
                            <div id="<?php echo $item['group']; ?>" class="accordion-body collapse<?php echo $collapse; ?>">
                                <?php
                            else:

                                echo '<div class="accordion-inner ' . $active . '">';
                                ?>
                                <?php
                                if ($displayData->hide) :
                                    ?>
                                    <a class="nolink">
                                        <?php echo $item['name']; ?>
                                    </a>
                                    <?php
                                else :
                                    if (strlen($item['link'])) :
                                        ?>

                                        <a href="<?php echo JFilterOutput::ampReplace($item['link']); ?>">
                                            <?php if ($item['icon']): ?>
                                                <i class="<?php echo $item['icon']; ?>"></i>
                                            <?php endif; ?>
                                            <?php echo $item['name']; ?>
                                        </a>
                                        <?php
                                    else : echo $item['name'];
                                    endif;
                                endif;

                                echo '</div>';
                            endif;

                            if (isset($item['end']) && $item['end']) {
                                echo '</div>';
                                echo '</div>';
                            }
                            ?>
                        <?php endforeach; ?>

                    <?php endif; ?>
                    <?php if ($displayData->displayMenu && $displayData->displayFilters) : ?>
                        <hr />
                    <?php endif; ?>
                    <?php if ($displayData->displayFilters) : ?>
                        <div class="filter-select hidden-phone">
                            <h4 class="page-header"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></h4>
                            <?php foreach ($displayData->filters as $filter) : ?>
                                <label for="<?php echo $filter['name']; ?>" class="element-invisible"><?php echo $filter['label']; ?></label>
                                <select name="<?php echo $filter['name']; ?>" id="<?php echo $filter['name']; ?>" class="span12 small" onchange="this.form.submit()">
                                    <?php if (!$filter['noDefault']) : ?>
                                        <option value=""><?php echo $filter['label']; ?></option>
                                    <?php endif; ?>
                                    <?php echo $filter['options']; ?>
                                </select>
                                <hr class="hr-condensed" />
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div id="j-toggle-sidebar"></div>
        </div>
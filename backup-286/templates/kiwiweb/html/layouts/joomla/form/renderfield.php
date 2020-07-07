<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Layout variables
 * ---------------------
 * 	$options         : (array)  Optional parameters
 * 	$label           : (string) The html code for the label (not required if $options['hiddenLabel'] is true)
 * 	$input           : (string) The input field html code
 */
?>

<?php
if (!empty($displayData['options']['showonEnabled']))
{
        JHtml::_('jquery.framework');
        JHtml::_('script', 'jui/cms.js', false, true);
}
$class = ($displayData['options']['class']) ? ' ' . $displayData['options']['class'] : '';
?>

<div class="form-group<?php echo $class; ?>" <?php echo $displayData['options']['rel']; ?>>
        <?php if (empty($displayData['options']['hiddenLabel'])) : ?>
                <div class="control-label"><?php echo $displayData['label']; ?></div>
        <?php endif; ?>
        <div class="controls"><?php echo $displayData['input']; ?></div>
</div>

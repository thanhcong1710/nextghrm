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
$displayTag = $this->options->get('displayTag', false);
if ($displayTag) {
        $com_path = JPATH_SITE . '/components/com_content/';
        JModelLegacy::addIncludePath($com_path . '/models', 'ContentModel');
        $article = JModelLegacy::getInstance('Form', 'ContentModel', array('ignore_request' => true));
        $form = $article->getForm();
}
?>
<form class="form-inline search-form">
        <?php
        if ($displayTag) {
                $form->setFieldAttribute('tags', 'id', rand(1, 9999));
                echo '<div class="form-group">';
                echo '<label for="exampleInputName2" class="hidden-xs hidden-sm">Filter by tag</label>&nbsp;';
                echo $form->getInput('tags');
                echo '</div>';
        }
        ?>
        <div class="form-group">
                <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-search"></i></div>
                        <input type="hidden" name="catid" value="<?php echo $displayData->id; ?>"/>
                        <input type="text" name="keyword" class="form-control" placeholder="<?php echo JText::_('TPL_KIWI_ERP_SEARCH_FORM_KEYWORD_PLACEHOLDER'); ?>">
                </div>
        </div>
        <button type="submit" class="btn btn-success inline-search"><?php echo JText::_('TPL_KIWI_ERP_SEARCH_FORM_SEARCH_BUTTON_LABEL'); ?></button>
</form>
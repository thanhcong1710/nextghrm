<?php
/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://nextgcyber.com
 * @author Daniel.Vu
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
$app = JFactory::getApplication();
$input = $app->input;
?>
<script type="text/javascript">
    Joomla.submitbutton = function (task)
    {
        if (task == 'product.cancel' || document.formvalidator.isValid(document.getElementById('item-form')))
        {
            Joomla.submitform(task, document.getElementById('item-form'));
        }
    }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_nextgcyber&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
    <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_NEXTGCYBER_ITEM_DETAILS', true)); ?>

    <legend><?php echo JText::_('COM_NEXTGCYBER_ITEM_DETAILS'); ?></legend>
    <div class="form-horizontal">
        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('name'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('name'); ?>
            </div>
        </div>

        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('content_id'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('content_id'); ?>
            </div>
        </div>

        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('active'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('active'); ?>
            </div>
        </div>

    </div>
    <?php echo JHtml::_('bootstrap.endTab'); ?>
    <?php if ($this->canDo->get('core.admin')) : ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('COM_NEXTGCYBER_FIELDSET_RULES', true)); ?>
        <?php echo $this->form->getInput('rules'); ?>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
    <?php endif; ?>

    <?php echo JHtml::_('bootstrap.endTabSet'); ?>

    <div>
        <input type="hidden" name="task" value="product.edit" />
        <input type="hidden" name="return" value="<?php echo $input->getCmd('return'); ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
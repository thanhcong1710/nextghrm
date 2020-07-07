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
NextgCyberSiteHelper::loadLibrary();
$app = JFactory::getApplication();
$input = $app->input;
?>
<script type="text/javascript">
    Joomla.submitbutton = function (task)
    {
        if (task == 'profile.cancel' || document.formvalidator.isValid(document.id('item-form')))
        {
            Joomla.submitform(task, document.getElementById('item-form'));
        }
    }
</script>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title"><?php echo JText::_('COM_NEXTGCYBER_PROFILE_HEADER'); ?></h1>
    </div>
    <div class="panel-body">
        <form action="<?php echo JRoute::_('index.php?option=com_nextgcyber&layout=edit'); ?>"
              method="post" name="adminForm" id="item-form" class="form-validate form form-horizontal">
            <legend><?php echo JText::_('COM_NEXTGCYBER_CUSTOMER_PROFILE_LEGEND'); ?></legend>
            <div>
                <div class="">
                    <?php foreach ($this->form->getFieldset() as $field): ?>
                        <div class="form-group">
                            <?php echo $field->label; ?>
                            <div class="col-sm-9">
                                <?php echo $field->input; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="form-actions">
                <div class="pull-right">
                    <button type="submit" class="btn btn-primary validate"><span><?php echo JText::_('JSUBMIT'); ?></span></button>
                    <a class="btn btn-default" href="<?php echo JRoute::_(NextgCyberHelperRoute::getProfileRoute()); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>
                    <input type="hidden" name="task" value="profile.save" />
                    <input type="hidden" name="option" value="com_nextgcyber" />
                    <input type="hidden" name="return" value="<?php echo $input->getCmd('return'); ?>" />
                </div>
            </div>
            <?php echo JHtml::_('form.token'); ?>
        </form>
    </div>
</div>

<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
$app = JFactory::getApplication();
$template = $app->getTemplate(true);
$params = $template->params;
$logo = $params->get('logo');
?>
<div class="row">
        <div class="col-md-8 col-md-offset-2">
                <div class="registration<?php echo $this->pageclass_sfx ?>">
                        <?php if ($this->params->get('show_page_heading')) : ?>
                                <div class="page-header">
                                        <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
                                </div>
                        <?php endif; ?>
                        <div class="panel panel-default">
                                <div class="panel-heading">
                                        <h3 class="panel-title"><?php echo JText::_('TPL_KIWI_ERP_REGISTER_PAGE_HEADER'); ?></h3>
                                </div>
                                <div class="panel-body">
                                        <form id="member-registration"
                                              action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register'); ?>"
                                              method="post"
                                              class="form-horizontal form-validate"
                                              enctype="multipart/form-data">

                                                <?php
                                                foreach ($this->form->getFieldsets() as $fieldset):
                                                        $fields = $this->form->getFieldset($fieldset->name);
                                                        foreach ($fields as $field):
                                                                if ($field->type != 'Spacer') {
                                                                        $this->form->setFieldAttribute($field->fieldname, 'labelclass', 'control-label col-sm-4');
                                                                } else {
                                                                        $this->form->setFieldAttribute($field->fieldname, 'class', 'col-sm-12');
                                                                }

                                                        endforeach;
                                                endforeach;
                                                ?>
                                                <?php foreach ($this->form->getFieldsets() as $fieldset): // Iterate through the form fieldsets and display each one. ?>
                                                        <?php $fields = $this->form->getFieldset($fieldset->name); ?>
                                                        <?php if (count($fields)): ?>
                                                                <fieldset>
                                                                        <?php if (isset($fieldset->label)):// If the fieldset has a label set, display it as the legend.    ?>
                                                                                <legend><?php echo JText::_($fieldset->label); ?></legend>
                                                                        <?php endif; ?>
                                                                        <?php
                                                                        ?>
                                                                        <?php foreach ($fields as $field) :// Iterate through the fields in the set and display them.?>
                                                                                <?php if ($field->hidden):// If the field is hidden, just display the input.?>
                                                                                        <?php echo $field->input; ?>
                                                                                <?php else: ?>
                                                                                        <div class="form-group">
                                                                                                <?php echo $field->label; ?>
                                                                                                <?php if (!$field->required && $field->type != 'Spacer') : ?>
                                                                                                        <span class="optional"><?php echo JText::_('COM_USERS_OPTIONAL'); ?></span>
                                                                                                <?php endif; ?>
                                                                                                <div class="col-sm-8">
                                                                                                        <?php
                                                                                                        if (!($field instanceof JFormFieldCaptcha)) {
                                                                                                                $field->__set('class', $field->getAttribute('class') . ' form-control');
                                                                                                        }
                                                                                                        ?>
                                                                                                        <?php echo $field->input; ?>
                                                                                                </div>
                                                                                        </div>
                                                                                <?php endif; ?>
                                                                        <?php endforeach; ?>
                                                                </fieldset>
                                                        <?php endif; ?>
                                                <?php endforeach; ?>
                                                <div class="form-group">
                                                        <div class="">
                                                                <div class="col-sm-6">
                                                                        <button type="submit" class="btn btn-primary btn-block validate"><?php echo JText::_('JREGISTER'); ?></button>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                        <a class="btn btn-default" href="<?php echo JRoute::_(''); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>
                                                                </div>
                                                        </div>
                                                        <input type="hidden" name="option" value="com_users" />
                                                        <input type="hidden" name="task" value="registration.register" />
                                                </div>
                                                <?php echo JHtml::_('form.token'); ?>
                                        </form>
                                </div>
                        </div>

                </div>

        </div>
</div>
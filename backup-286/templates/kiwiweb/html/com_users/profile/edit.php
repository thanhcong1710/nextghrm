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
JHtml::_('formbehavior.chosen', 'select');
//load user_profile plugin language
$lang = JFactory::getLanguage();
$lang->load('plg_user_profile', JPATH_ADMINISTRATOR);

foreach ($this->form->getFieldsets() as $fieldset) {
        $fields = $this->form->getFieldset($fieldset->name);
        foreach ($fields as $field) {
                if ($field->type != 'Spacer') {
                        $this->form->setFieldAttribute($field->fieldname, 'labelclass', 'control-label col-sm-3');

                        $this->form->setFieldAttribute($field->fieldname, 'class', 'form-control');
                } else {
                        $this->form->setFieldAttribute($field->fieldname, 'class', 'col-sm-12');
                }
        }
}
?>
<div class="row">
        <div class="col-md-8 col-md-offset-2">
                <div class="profile-edit<?php echo $this->pageclass_sfx ?>">
                        <?php if ($this->params->get('show_page_heading')) : ?>
                                <div class="page-header">
                                        <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
                                </div>
                        <?php endif; ?>

                        <script type="text/javascript">
                                Joomla.twoFactorMethodChange = function (e)
                                {
                                        var selectedPane = 'com_users_twofactor_' + jQuery('#jform_twofactor_method').val();

                                        jQuery.each(jQuery('#com_users_twofactor_forms_container>div'), function (i, el) {
                                                if (el.id != selectedPane)
                                                {
                                                        jQuery('#' + el.id).hide(0);
                                                }
                                                else
                                                {
                                                        jQuery('#' + el.id).show(0);
                                                }
                                        });
                                }
                        </script>
                        <div class="panel panel-default">
                                <div class="panel-heading">
                                        <h3 class="panel-title"><?php echo JText::_('TPL_KIWI_WEB_EDIT_PROFILE_HEADER'); ?></h3>
                                </div>
                                <div class="panel-body">
                                        <form id="member-profile"
                                              action="<?php echo JRoute::_('index.php?option=com_users&task=profile.save'); ?>"
                                              method="post"
                                              class="form-horizontal form-validate"
                                              enctype="multipart/form-data">
                                                      <?php
                                                      foreach ($this->form->getFieldsets() as $group => $fieldset):
                                                              $fields = $this->form->getFieldset($group);
                                                              foreach ($fields as $field):
                                                                      if ($field->type != 'Spacer' && $field->type != 'Captcha') {
                                                                              $this->form->setFieldAttribute($field->fieldname, 'labelclass', 'col-sm-3 control-label', $group);
                                                                              $this->form->setFieldAttribute($field->fieldname, 'class', 'form-control', $group);
                                                                              if ($field->type == "Calendar") {
                                                                                      $this->form->setFieldAttribute($field->fieldname, 'type', 'Calendar2', $group);
                                                                              }
                                                                      } elseif ($field->type == 'Spacer') {
                                                                              $this->form->setFieldAttribute($field->fieldname, 'class', 'col-sm-12', $group);
                                                                      } elseif ($field->type == 'Captcha') {
                                                                              $this->form->setFieldAttribute($field->fieldname, 'labelclass', 'col-sm-3', $group);
                                                                      }
                                                              endforeach;

                                                      endforeach;
                                                      ?>
                                                      <?php foreach ($this->form->getFieldsets() as $group => $fieldset):// Iterate through the form fieldsets and display each one.    ?>
                                                              <?php $fields = $this->form->getFieldset($group); ?>
                                                              <?php if (count($fields)): ?>
                                                                <fieldset>
                                                                        <?php if (isset($fieldset->label)):// If the fieldset has a label set, display it as the legend.    ?>
                                                                                <legend><?php echo JText::_($fieldset->label); ?></legend>
                                                                        <?php endif; ?>
                                                                        <?php foreach ($fields as $field):// Iterate through the fields in the set and display them.?>
                                                                                <?php if ($field->hidden):// If the field is hidden, just display the input.?>
                                                                                        <div class="form-group">
                                                                                                <div class="controls">
                                                                                                        <?php echo $field->input; ?>
                                                                                                </div>
                                                                                        </div>
                                                                                <?php else: ?>
                                                                                        <div class="form-group">
                                                                                                <?php echo $field->label; ?>
                                                                                                <div class="col-sm-9">
                                                                                                        <?php echo $field->input; ?>
                                                                                                </div>
                                                                                        </div>
                                                                                <?php endif; ?>
                                                                        <?php endforeach; ?>
                                                                </fieldset>
                                                        <?php endif; ?>
                                                <?php endforeach; ?>

                                                <?php if (count($this->twofactormethods) > 1): ?>
                                                        <fieldset>
                                                                <legend><?php echo JText::_('COM_USERS_PROFILE_TWO_FACTOR_AUTH') ?></legend>

                                                                <div class="form-group">

                                                                        <label id="jform_twofactor_method-lbl" for="jform_twofactor_method" class="hasTooltip"
                                                                               title="<strong><?php echo JText::_('COM_USERS_PROFILE_TWOFACTOR_LABEL') ?></strong><br/><?php echo JText::_('COM_USERS_PROFILE_TWOFACTOR_DESC') ?>">
                                                                                       <?php echo JText::_('COM_USERS_PROFILE_TWOFACTOR_LABEL'); ?>
                                                                        </label>


                                                                        <?php echo JHtml::_('select.genericlist', $this->twofactormethods, 'jform[twofactor][method]', array('onchange' => 'Joomla.twoFactorMethodChange()'), 'value', 'text', $this->otpConfig->method, 'jform_twofactor_method', false) ?>

                                                                </div>

                                                                <div id="com_users_twofactor_forms_container">
                                                                        <?php foreach ($this->twofactorform as $form): ?>
                                                                                <?php $style = $form['method'] == $this->otpConfig->method ? 'display: block' : 'display: none'; ?>
                                                                                <div id="com_users_twofactor_<?php echo $form['method'] ?>" style="<?php echo $style; ?>">
                                                                                        <?php echo $form['form'] ?>
                                                                                </div>
                                                                        <?php endforeach; ?>
                                                                </div>
                                                        </fieldset>

                                                        <fieldset>
                                                                <legend>
                                                                        <?php echo JText::_('COM_USERS_PROFILE_OTEPS') ?>
                                                                </legend>
                                                                <div class="alert alert-info">
                                                                        <?php echo JText::_('COM_USERS_PROFILE_OTEPS_DESC') ?>
                                                                </div>
                                                                <?php if (empty($this->otpConfig->otep)): ?>
                                                                        <div class="alert alert-warning">
                                                                                <?php echo JText::_('COM_USERS_PROFILE_OTEPS_WAIT_DESC') ?>
                                                                        </div>
                                                                <?php else: ?>
                                                                        <?php foreach ($this->otpConfig->otep as $otep): ?>
                                                                                <span class="col-md-3">
                                                                                        <?php echo substr($otep, 0, 4) ?>-<?php echo substr($otep, 4, 4) ?>-<?php echo substr($otep, 8, 4) ?>-<?php echo substr($otep, 12, 4) ?>
                                                                                </span>
                                                                        <?php endforeach; ?>
                                                                        <div class="clearfix"></div>
                                                                <?php endif; ?>
                                                        </fieldset>
                                                <?php endif; ?>

                                                <div class="form-group form-actions">
                                                        <div class="col-sm-6"><button type="submit" class="btn btn-primary validate btn-block"><span><?php echo JText::_('JSUBMIT'); ?></span></button></div>
                                                        <div class="col-sm-6"><a class="btn btn-default" href="<?php echo JRoute::_(''); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a></div>
                                                        <input type="hidden" name="option" value="com_users" />
                                                        <input type="hidden" name="task" value="profile.save" />

                                                </div>
                                                <?php echo JHtml::_('form.token'); ?>
                                        </form>
                                </div>
                        </div>

                </div>

        </div>
</div>
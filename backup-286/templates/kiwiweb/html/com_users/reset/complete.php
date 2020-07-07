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
$this->form->setFieldAttribute('password1', 'class', 'form-control');
$this->form->setFieldAttribute('password2', 'class', 'form-control');
$app = JFactory::getApplication();
$template = $app->getTemplate(true);
$params = $template->params;
$logo = $params->get('logo');
?>
<div class="row">
        <div class="col-md-6 col-md-offset-3">
                <div class="reset-complete<?php echo $this->pageclass_sfx ?>">
                        <?php if ($this->params->get('show_page_heading')) : ?>
                                <div class="page-header">
                                        <h1>
                                                <?php echo $this->escape($this->params->get('page_heading')); ?>
                                        </h1>
                                </div>
                        <?php endif; ?>
                        <div class="panel panel-default">
                                <div class="panel-heading">
                                        <h3 class="panel-title"><?php echo JText::_('TPL_KIWI_WEB_RESET_PASSWORD_HEADER'); ?></h3>
                                </div>
                                <div class="panel-body">
                                        <form action="<?php echo JRoute::_('index.php?option=com_users&task=reset.complete'); ?>" method="post" class="form-validate form-horizontal">
                                                <?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
                                                        <fieldset>
                                                                <?php foreach ($this->form->getFieldset($fieldset->name) as $name => $field) : ?>
                                                                        <p><?php echo JText::_($fieldset->label); ?></p>
                                                                        <div class="form-group">
                                                                                <?php echo $field->label; ?>
                                                                                <?php echo $field->input; ?>
                                                                        </div>
                                                                <?php endforeach; ?>
                                                        </fieldset>
                                                <?php endforeach; ?>

                                                <div class="control-group">
                                                        <div class="controls">
                                                                <button type="submit" class="btn btn-primary validate"><?php echo JText::_('JSUBMIT'); ?></button>
                                                        </div>
                                                </div>
                                                <?php echo JHtml::_('form.token'); ?>
                                        </form>
                                </div>
                        </div>

                </div>

        </div>
</div>
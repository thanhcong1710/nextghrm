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
$app = JFactory::getApplication();
$template = $app->getTemplate(true);
$params = $template->params;
$logo = $params->get('logo');
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="login<?php echo $this->pageclass_sfx ?>">
            <?php if ($this->params->get('show_page_heading', false)) : ?>
                <div class="page-header">
                    <h1>
                        <?php echo $this->escape($this->params->get('page_heading')); ?>
                    </h1>
                </div>
            <?php endif; ?>

            <?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
                <div class="login-description">
                <?php endif; ?>

                <?php if ($this->params->get('logindescription_show') == 1) : ?>
                    <?php echo $this->params->get('login_description'); ?>
                <?php endif; ?>

                <?php if (($this->params->get('login_image') != '')) : ?>
                    <img src="<?php echo $this->escape($this->params->get('login_image')); ?>" class="login-image" alt="<?php echo JTEXT::_('COM_USER_LOGIN_IMAGE_ALT') ?>"/>
                <?php endif; ?>

                <?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
                </div>
            <?php endif; ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo JText::_('TPL_KIWI_ERP_LOGIN_PAGE_LOGIN_HEADER'); ?></h3>
                </div>
                <div class="panel-body">
                    <form action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post" class="form-horizontal form-validate">

                        <fieldset>
                            <?php foreach ($this->form->getFieldset('credentials') as $field) : ?>
                                <?php if (!$field->hidden) : ?>
                                    <div class="form-group">
                                        <?php echo $field->label; ?>
                                        <div class="col-sm-9">
                                            <?php echo $field->input; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>

                            <?php if ($this->tfa): ?>
                                <?php
                                $this->form->setFieldAttribute('secretkey', 'labelclass', 'control-label col-sm-3');
                                $this->form->setFieldAttribute('secretkey', 'class', 'form-control');
                                ?>
                                <div class="form-group">
                                    <?php echo $this->form->getField('secretkey')->label; ?>
                                    <div class="col-sm-9">
                                        <?php echo $this->form->getField('secretkey')->input; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <div class="checkbox">
                                            <label>
                                                <input id="remember" type="checkbox" name="remember" class="checkbox" value="yes"/>
                                                <?php echo JText::_('COM_USERS_LOGIN_REMEMBER_ME') ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <div class="row login-buttons">
                                        <div class="col-md-4">

                                            <button type="submit" class="btn btn-primary btn-block">
                                                <?php echo JText::_('JLOGIN'); ?>
                                            </button>
                                        </div>
                                        <div class="col-md-8">
                                            <?php
                                            $usersConfig = JComponentHelper::getParams('com_users');
                                            if ($usersConfig->get('allowUserRegistration')) :
                                                ?>

                                                <a class="btn btn-success2 btn-block" href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
                                                    <?php echo JText::_('TPL_KIWI_ERP_REGISTER_PAGE_HEADER'); ?>
                                                </a>

                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <ul>
                                        <li>
                                            <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
                                                <?php echo JText::_('COM_USERS_LOGIN_RESET'); ?></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
                                                <?php echo JText::_('COM_USERS_LOGIN_REMIND'); ?></a>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                            <?php
                            $return = $app->input->getString('return');
                            $return = ($this->params->get('login_redirect_url')) ? base64_encode($this->params->get('login_redirect_url')) : $return;
                            ?>
                            <input type="hidden" name="return" value="<?php echo $return; ?>" />
                            <?php echo JHtml::_('form.token'); ?>
                        </fieldset>
                    </form>

                </div>
            </div>

        </div>

    </div>
</div>

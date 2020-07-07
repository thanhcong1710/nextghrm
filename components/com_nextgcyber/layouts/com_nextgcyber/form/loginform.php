<?php
/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://domain.com
 * @author Daniel.Vu
 */
// No direct access
// Display list backups of instance
defined('_JEXEC') or die('Restricted access');
$language = JFactory::getLanguage();
$language->load('com_users', JPATH_SITE, $language->getTag(), true); // this loads
// Load user model
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_users/models');
JForm::addFormPath(JPATH_ROOT . '/components/com_users/models/forms');
JLoader::register('UsersHelper', JPATH_ADMINISTRATOR . '/components/com_users/helpers/users.php');
$tfa_method = UsersHelper::getTwoFactorMethods();
$tfa = is_array($tfa_method) && count($tfa_method) > 1;
$loginModel = JModelLegacy::getInstance('Login', 'UsersModel');
$loginForm = $loginModel->getForm();
$loginForm->setFieldAttribute('username', 'class', 'form-control');
$loginForm->setFieldAttribute('username', 'placeholder', 'form-control');
$loginForm->setFieldAttribute('password', 'class', 'form-control');
$return = (!empty($displayData->return)) ? $displayData->return : base64_encode(JRoute::_(JUri::getInstance()->toString()));

$loginForm->setValue('return', null, $return);
?>
<div>
    <form class="form-vertical" method="POST">
        <?php
        // Set labelclass
        foreach ($loginForm->getFieldset() as $field):
            if ($field->type != 'Spacer' && $field->type != 'Captcha') {
                $loginForm->setFieldAttribute($field->fieldname, 'labelclass', 'col-sm-4 control-label');
                $loginForm->setFieldAttribute($field->fieldname, 'class', 'form-control');
            } elseif ($field->type == 'Spacer') {
                $loginForm->setFieldAttribute($field->fieldname, 'class', 'col-sm-12');
            } elseif ($field->type == 'Captcha') {
                $loginForm->setFieldAttribute($field->fieldname, 'labelclass', 'col-sm-12');
            }

        endforeach;

        foreach ($loginForm->getFieldset() as $field):
            ?>
            <?php
            $hidden = ($field->name == 'return') ? ' hidden' : '';
            if ($field->name == 'secretkey' && !$tfa) {
                continue;
            }
            if ($field->name == 'username'):
                ?>
                <div class="row"><?php echo $field->label; ?></div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><span class="fa fa-user"></span></span>
                        <?php echo $field->input; ?>

                    </div>
                </div>
            <?php elseif ($field->name == 'password'):
                ?>
                <div class="row"><?php echo $field->label; ?></div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><span class="fa fa-lock"></span></span>
                        <?php echo $field->input; ?>

                    </div>
                </div>
            <?php else:
                ?>
                <div class="form-group<?php echo $hidden; ?>">
                    <div class="row">
                        <?php echo $field->label; ?>
                        <div class="col-sm-8">
                            <?php echo $field->input; ?>
                        </div>
                    </div>
                </div>
            <?php
            endif;
            ?>


        <?php endforeach; ?>
        <div class="form-group hidden">
            <div class="row">
                <div class="col-sm-8 col-md-offset-4">
                    <div class="checkbox">
                        <label>
                            <input name="remember" type="checkbox" class="checkbox">
                            <?php echo JText::_('COM_NEXTGCYBER_REMEMBER_ME_LABEL'); ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary btn-block nc-price-store">
                        <?php echo JText::_('COM_NEXTGCYBER_LOGIN_BUTTON_LABEL'); ?>
                    </button>

                </div>
                <div class="col-md-6">
                    <a class="btn btn-primary nc-price-store btn-block" href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
                        <?php echo JText::_('COM_NEXTGCYBER_USER_REGISTER_BUTTON_LABEL'); ?>
                    </a>
                </div>
            </div>
        </div>
        <input type="hidden" value="com_users" name="option" />
        <input type="hidden" value="user.login" name="task" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
<?php
/**
 * @package        JFBConnect/JLinked
 * @copyright (C) 2011-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

if ($registerType == "communitybuilder" && file_exists(JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php'))
        $passwordName = 'passwd';
else
        $passwordName = 'password';

if ($params->get('showLoginForm')) {
        if ($params->get('forgotColor') == 'white') {
                $forgotColor = ' icon-white';
        } else {
                $forgotColor = '';
        }
        $usericon = '<span class="fa fa-user"></span>';
        $passwordicon = '<span class="fa fa-lock"></span>';
        switch ($params->get('bootstrapVersion')) {
                case 2:
                        $inputgoupclass = "input-prepend input-append";
                        $addonclass = "add-on";
                        break;

                case 3:
                        $inputgoupclass = "input-group";
                        $addonclass = "input-group-addon";
                        break;
        }

        $forgotUsername = $helper->getForgotUser($params->get('register_type'), $params->get('showForgotUsername'), $forgotLink, $forgotUsernameLink, $forgotColor);
        $forgotPassword = $helper->getForgotPassword($params->get('register_type'), $params->get('showForgotPassword'), $forgotLink, $forgotPasswordLink, $forgotColor);
        ?>

        <form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="kiwi-login-module-form" class="form-vertical" role="form">

                <div class="form-group">
                        <div id="form-kiwi-login-module-username" class="<?php echo $inputgoupclass; ?>">
                                <span class="<?php echo $addonclass; ?>"><?php echo $usericon; ?></span>
                                <input name="username" tabindex="1" id="kiwi-login-module-username" class="form-control" alt="username" type="text" placeholder="<?php echo JText::_('MOD_KIWILOGIN_USERNAME'); ?>">
                                <span class="<?php echo $addonclass; ?>"><?php echo $forgotUsername; ?></span>
                        </div>
                </div>
                <div class="form-group">
                        <div id="form-kiwi-login-module-password" class="<?php echo $inputgoupclass; ?>">
                                <span class="<?php echo $addonclass; ?>"><?php echo $passwordicon; ?></span>
                                <input name="<?php echo $passwordName; ?>" tabindex="2" id="kiwi-login-module-passwd" class="form-control" alt="password" type="password" placeholder="<?php echo JText::_('MOD_KIWILOGIN_PASSWORD') ?>">
                                <span class="<?php echo $addonclass; ?>"><?php echo $forgotPassword; ?></span>
                        </div>
                </div>

                <div class="form-group">
                        <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
                                <div class="control-group" id="form-kiwi-login-module-remember">
                                        <label for="kiwi-login-module-remember">
                                                <input id="kiwi-login-module-remember" type="checkbox" name="remember" class="inputbox" value="yes" alt="Remember Me"/>
                                                <?php echo JText::_('JGLOBAL_REMEMBER_ME'); ?>
                                        </label>
                                </div>
                        <?php endif; ?>
                </div>

                <div class="form-group">
                        <div class="control-group" id="form-kiwi-login-module-submitcreate">
                                <button type="submit" name="Submit" class="btn btn-success<?php
                                if (!$showRegisterLinkInLogin) {
                                        echo 'span12';
                                }
                                ?>"><?php echo JText::_('MOD_KIWILOGIN_LOGIN') ?></button>
                                        <?php if ($showRegisterLinkInLogin) : ?>
                                        <a class="btn btn-default" href="<?php echo $registerLink; ?>"><?php echo JText::_('MOD_KIWILOGIN_REGISTER_FOR_THIS_SITE'); ?></a>
                                <?php endif; ?>
                        </div>
                </div>

                <?php
                echo '<input type="hidden" name="option" value="com_users"/>';
                echo '<input type="hidden" name="task" value="user.login"/>';
                echo '<input type="hidden" name="return" value="' . $jLoginUrl . '"/>';
                echo JHTML::_('form.token');
                ?>
        </form>

        <?php
}
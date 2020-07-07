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
        if ($params->get('bootstrapVersion') == 2) {
                $inputgoupclass = "input-prepend input-append";
                $addonclass = "add-on";
        } elseif ($params->get('bootstrapVersion') == 3) {
                $inputgoupclass = "input-group";
                $addonclass = "input-group-addon";
        }
        $forgotUsername = $helper->getForgotUser($params->get('register_type'), $params->get('showForgotUsername'), $forgotLink, $forgotUsernameLink, $forgotColor);
        $forgotPassword = $helper->getForgotPassword($params->get('register_type'), $params->get('showForgotPassword'), $forgotLink, $forgotPasswordLink, $forgotColor);
        ?>

        <div class="kiwi-login-module-joomla-login horizontal <?php echo $joomlaSpan; ?>">
                <form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="kiwi-login-module-form">
                        <fieldset class="userdata span12">
                                <div class="control-group pull-left" id="form-kiwi-login-module-username" style="margin-right:10px;">
                                        <div class="controls">
                                                <div class="<?php echo $inputgoupclass; ?>">
                                                        <span class="<?php echo $addonclass; ?>"><?php echo $usericon; ?></span>
                                                        <input name="username" tabindex="1" id="kiwi-login-module-username" alt="username" type="text" class="input-small" placeholder="<?php echo JText::_('MOD_KIWILOGIN_USERNAME'); ?>">
                                                        <span class="<?php echo $addonclass; ?>"><?php echo $forgotUsername; ?></span>
                                                </div>
                                        </div>
                                </div>
                                <div class="control-group pull-left" id="form-kiwi-login-module-password" style="margin-right:10px;">
                                        <div class="controls">
                                                <div class="<?php echo $inputgoupclass; ?>">
                                                        <span class="<?php echo $addonclass; ?>"><?php echo $passwordicon; ?></span>
                                                        <input name="<?php echo $passwordName; ?>" tabindex="2" id="kiwi-login-module-passwd" alt="password" type="password" class="input-small" placeholder="<?php echo JText::_('MOD_KIWILOGIN_PASSWORD') ?>">
                                                        <span class="<?php echo $addonclass; ?>"><?php echo $forgotPassword; ?></span>
                                                </div>
                                        </div>
                                </div>
                                <div class="control-group pull-left" id="form-kiwi-login-module-submit-button">
                                        <button type="submit" name="Submit" class="btn btn-primary"><?php echo JText::_('MOD_KIWILOGIN_LOGIN') ?></button>
        <?php if ($showRegisterLinkInLogin) : ?>
                                                <a class="btn" href="<?php echo $registerLink; ?>"><?php echo JText::_('MOD_KIWILOGIN_REGISTER_FOR_THIS_SITE'); ?></a>
        <?php endif; ?>
                                </div>
                                        <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
                                        <div class="control-group clearfix" id="form-kiwi-login-module-remember">
                                                <label for="kiwi-login-module-remember">
                                                        <input id="kiwi-login-module-remember" type="checkbox" name="remember" class="inputbox" value="yes" alt="Remember Me"/>
                                        <?php echo JText::_('JGLOBAL_REMEMBER_ME'); ?>
                                                </label>
                                        </div>
        <?php endif; ?>


        <?php
        if ($registerType == "communitybuilder" && file_exists(JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php')) {// Use Community Builder's login
                include_once(JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php');
                global $_CB_framework;
                echo '<input type="hidden" name="option" value="com_comprofiler" />' . "\n";
                echo '<input type="hidden" name="task" value="login" />' . "\n";
                echo '<input type="hidden" name="op2" value="login" />' . "\n";
                echo '<input type="hidden" name="lang" value="' . $_CB_framework->getCfg('lang') . '" />' . "\n";
                echo '<input type="hidden" name="force_session" value="1" />' . "\n"; // makes sure to create joomla 1.0.11+12 session/bugfix
                echo '<input type="hidden" name="return" value="B:' . $jLoginUrl . '"/>';
                echo cbGetSpoofInputTag('login');
        } else {
                echo '<input type="hidden" name="option" value="com_users"/>';
                echo '<input type="hidden" name="task" value="user.login"/>';
                echo '<input type="hidden" name="return" value="' . $jLoginUrl . '"/>';
        }
        echo JHTML::_('form.token');
        ?>

                        </fieldset>
                </form>
        </div>
                                <?php
                                if ($orientation == 'bottom')
                                        echo '<div class="clearfix"></div>';
                        }
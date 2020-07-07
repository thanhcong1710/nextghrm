<?php
/**
 * @package        JFBConnect/JLinked
 * @copyright (C) 2011-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
if ($params->get('load_css', 1) == 1) {
        $document->addStyleSheet('modules/mod_kiwilogin/css/mod_kiwilogin.css');
}
$jfbcLogin = $helper->getFBLoginButton($loginButtonType, $orientation, $alignment, $fbLoginButtonImage);
$jlinkedLogin = $helper->getLILoginButton($loginButtonType, $orientation, $alignment, $liLoginButtonImage);
$logintext = JText::_("MOD_KIWILOGIN_LOGIN");
echo '<div class="kiwilogin login">';
if ($params->get('displayType') == 'modal') {
        if ($params->get('modalButtonStyle') == 'button') {
                echo '<div class="kiwi-login-module"><a class="btn btn-primary" href="#kiwi-login-block" role="button" data-toggle="modal">';
                echo $logintext;
                echo '</a>';
                if ($showRegisterLinkInModal)
                        echo '<a class="btn" href="' . $registerLink . '">Register</a>';
                echo '</div>';
        }
        else { //text
                echo '<a href="#kiwi-login-block" role="button" data-toggle="modal"><span class="fa fa-lock"></span> ';
                echo $logintext;
                echo '</a>';
                if ($showRegisterLinkInModal)
                        echo ' / <a href="' . $registerLink . '">Register</a>';
                echo '';
        }
        switch ($params->get('bootstrapVersion')) {
                case 2:
                        $hide = "hide ";
                        break;

                case 3:
                        $hide = "";
                        break;
        }

        ob_start();
        echo '<div id="kiwi-login-block" class="modal ' . $hide . 'fade" tabindex="-1" role="dialog" aria-labelledby="kiwi-login-block-label" aria-hidden="true">';
        echo '<div class="modal-dialog">';
}
?>

<div class="modal-content">
        <?php if ($params->get('user_intro')): ?>
                <div class="kiwi-login-module-desc">
                        <?php echo $params->get('user_intro'); ?>
                </div>
        <?php endif; ?>

        <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title"><?php echo JText::_("MOD_KIWILOGIN_MODEL_TITLE"); ?></h3>
        </div>
        <div class="modal-body">
                <?php
                require(JModuleHelper::getLayoutPath("mod_kiwilogin", "joomlaLogin_" . $layout));
                require(JModuleHelper::getLayoutPath('mod_kiwilogin', "socialLogin"));
                ?>
        </div>
        <div class="clearfix"></div>
</div>

<?php
if ($params->get('displayType') == 'modal') {
        echo '</div></div>';
        $modalContents = ob_get_clean();
        $doc = JFactory::getDocument();
        if ($doc->getType() == 'html') {
                $buffer = $doc->getBuffer('component');
                $buffer .= $modalContents;
                $doc->setBuffer($buffer, array('type' => 'component', 'name' => null, 'title' => null));
        }
}

echo '</div>';
?>
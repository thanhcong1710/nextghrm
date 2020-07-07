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

echo $helper->getSocialAvatar($registerType, $profileLink, $user);
echo '<div class="kiwilogin logout">';
if ($params->get('greetingName') != 2) {
        if ($params->get('greetingName') == 0)
                $name = $user->get('username');
        else
                $name = $user->get('name');
        echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="fa fa-user"></span> ' . JText::sprintf('MOD_KIWILOGIN_WELCOME', $name);
        echo '<b class="caret"></b>';
        echo '</a>';
}
echo '<ul class="dropdown-menu">';
$showlogoutbtn = $params->get('showLogoutButton');
$showlogoutbtn_str = "";

if ($params->get('showUserMenu')) {
        echo $helper->getUserMenu($params->get('showUserMenu'), $params->get('userMenuStyle'));
}

if ($params->get('showConnectButton')) {
        echo "<li>" . $helper->getReconnectButtons($loginButtonType, $orientation, $alignment, $fbLoginButtonImage, $liLoginButtonImage) . "</li>";
}
if ($showlogoutbtn == 1) {
        ?>
        <li><?php echo $helper->getLogoutButton($params->get('usesecure'), $jLogoutUrl); ?></li>
        <?php
} elseif ($showlogoutbtn == 2) {
        ?>
        <li><?php echo $helper->getLogoutLink($params->get('usesecure'), $jLogoutUrl); ?></li>
        <?php
}
echo "</ul>";
echo '</div>';
?>
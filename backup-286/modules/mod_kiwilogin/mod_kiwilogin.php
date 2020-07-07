<?php

/**
 * @package			mod_kiwilogin
 * @copyright (C) 2011-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

require_once(__DIR__ . '/helper.php');
require_once(__DIR__ . '/scl_helper.php');
$helper = new modKiwiLoginHelper($params);

$user = JFactory::getUser();

$jLoginUrl = $helper->getLoginRedirect('jlogin');
$jLogoutUrl = $helper->getLoginRedirect('jlogout');

$registerType = $params->get('register_type');
$forgotLink = '';
if ($registerType == "jomsocial" && file_exists(JPATH_BASE . '/components/com_community/libraries/core.php')) {
        $jspath = JPATH_BASE . '/components/com_community';
        include_once($jspath . '/libraries/core.php');
        $registerLink = CRoute::_('index.php?option=com_community&view=register');
        $profileLink = CRoute::_('index.php?option=com_community');
} else if ($registerType == "communitybuilder" && file_exists(JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php')) {
        $registerLink = JRoute::_("index.php?option=com_comprofiler&task=registers", false);
        $forgotLink = JRoute::_("index.php?option=com_comprofiler&task=lostPassword");
        $profileLink = JRoute::_("index.php?option=com_comprofiler", false);
} else if ($registerType == "virtuemart" && file_exists(JPATH_ADMINISTRATOR . '/components/com_virtuemart/version.php')) {
        require_once (JPATH_ADMINISTRATOR . '/components/com_virtuemart/version.php');
        if (class_exists('vmVersion') && property_exists('vmVersion', 'RELEASE')) {
                if (version_compare('1.99', vmVersion::$RELEASE)) // -1 if ver1, 1 if 2.0+
                        $registerLink = JRoute::_("index.php?option=com_virtuemart&view=user", false);
                else {
                        if (file_exists(JPATH_SITE . '/components/com_virtuemart/virtuemart_parser.php')) {
                                require_once (JPATH_SITE . '/components/com_virtuemart/virtuemart_parser.php');
                                global $sess;
                                $registerLink = $sess->url(SECUREURL . 'index.php?option=com_virtuemart&amp;page=shop.registration');
                        }
                }
        }
        $profileLink = '';
} else if ($registerType == 'kunena' && JFolder::exists(JPATH_SITE . '/components/com_kunena')) {
        $profileLink = JRoute::_('index.php?option=com_kunena&view=user', false);
        $registerLink = JRoute::_('index.php?option=com_users&view=registration', false);
} else {
        $profileLink = '';
        $registerLink = JRoute::_('index.php?option=com_users&view=registration', false);
}
// common for J!, JomSocial, and Virtuemart

$forgotUsernameLink = JRoute::_('index.php?option=com_users&view=remind', false);
$forgotPasswordLink = JRoute::_('index.php?option=com_users&view=reset', false);

$showRegisterLink = $params->get('showRegisterLink');
$showRegisterLinkInModal = $showRegisterLink == 2 || $showRegisterLink == 3;
$showRegisterLinkInLogin = $showRegisterLink == 1 || $showRegisterLink == 3;

$layout = $params->get('socialButtonsLayout', 'vertical'); //horizontal or vertical
$orientation = $params->get('socialButtonsOrientation'); //bottom or side
$loginButtonType = $params->get('loginButtonType');
$alignment = $params->get('socialButtonsAlignment');
$fbLoginButtonImage = $params->get('facebookLoginButtonLinkImage');
$liLoginButtonImage = $params->get('linkedInLoginButtonLinkImage');
$span = '';
if ($params->get('bootstrapVersion') == 2) {
        $span = "span";
} elseif ($params->get('bootstrapVersion') == 3) {
        $span = "col-md-";
}
if ($layout == 'horizontal') {
        $joomlaSpan = 'pull-left';
        $socialSpan = 'pull-' . $alignment;
} else if ($orientation == 'side') {
        $joomlaSpan = $span . '7';
        $socialSpan = $span . '5';
} else { //orientation == 'bottom'
        $joomlaSpan = $span . '12';
        $socialSpan = $span . '12';
}

require(JModuleHelper::getLayoutPath('mod_kiwilogin', $helper->getType()));
?>
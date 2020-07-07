<?php

/**
 * @package        JFBConnect/JLinked
 * @copyright (C) 2011-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

define('DISPLAY_BLOCK', ' class="show"');

class modKiwiLoginHelper {

        var $isJLinkedInstalled = false;
        var $isJFBConnectInstalled = false;
        var $jlinkedLibrary = null;
        var $jfbcLibrary = null;
        var $params;

        function __construct($params) {
                $this->params = $params;
                //Check to see if JLinked is installed
                if (class_exists("JLinkedApiLibrary")) {
                        $this->isJLinkedInstalled = true;
                        $this->jlinkedLibrary = JLinkedApiLibrary::getInstance();
                        $renderKey = $this->jlinkedLibrary->getSocialTagRenderKey();
                        $this->jlinkedRenderKey = $renderKey != "" ? " key=" . $renderKey : "";
                }

                //Check to see if JFBConnect is installed
                if (class_exists("JFBConnectFacebookLibrary")) {
                        $this->isJFBConnectInstalled = true;
                        $this->jfbcLibrary = JFBConnectFacebookLibrary::getInstance();
                }
        }

        function getType() {
                $user = JFactory::getUser();
                return (!$user->get('guest')) ? 'logout' : 'login';
        }

        function getLoginRedirect($loginType) {
                if (JRequest::getString('return'))
                        return JRequest::getString('return');

                $url = '';
                if (($loginType == 'jlogin' && $this->params->get('jlogin') != '') ||
                        ($loginType == 'jlogout' && $this->params->get('jlogout'))) {
                        $itemId = $this->params->get($loginType);
                        if ($itemId)
                                $url = SCLibraryUtilities::getLinkFromMenuItem($itemId, $loginType == 'jlogout');
                }

                if (!$url) {
                        $uri = JURI::getInstance();
                        $url = $uri->toString(array('scheme', 'host', 'path', 'query'));
                }

                return base64_encode($url);
        }

        function getAvatarDimensions(&$width, &$height) {
                $picHeightParam = $this->params->get("profileHeight");
                $picWidthParam = $this->params->get("profileWidth");
                $height = $picHeightParam != "" ? 'height="' . $picHeightParam . 'px"' : "";
                $width = $picWidthParam != "" ? 'width="' . $picWidthParam . 'px"' : "";
        }

        function getSocialAvatarImage($avatarURL, $profileURL) {
                $html = '';
                if ($avatarURL) {
                        $picWidth = "";
                        $picHeight = "";
                        $this->getAvatarDimensions($picWidth, $picHeight);

                        $html = '<img src="' . $avatarURL . '" ' . $picWidth . " " . $picHeight . ' />';

                        $linked = ($this->params->get("linkProfile") == 1);
                        if ($linked && $profileURL != '')
                                $html = '<a target="_BLANK" href="' . $profileURL . '">' . $html . '</a>';
                }
                return $html;
        }

        function showSecurely() {
                $uri = JURI::getInstance();
                $scheme = $uri->getScheme();
                return $scheme == 'https';
        }

        function getFacebookAvatar() {
                $html = "";
                if ($this->isJFBConnectInstalled) {
                        $fbUserId = $this->jfbcLibrary->getMappedFbUserId();

                        # Show their FB avatar (if desired), or give them the option to link accounts
                        if ($fbUserId) {
                                $fbProfileURL = 'https://www.facebook.com/profile.php?id=' . $fbUserId;
                                $fbAvatarSize = $this->getJFBConnectAvatarSize();
                                $fbAvatarURL = 'https://graph.facebook.com/' . $fbUserId . '/picture?type=' . $fbAvatarSize;

                                if ($this->showSecurely())
                                        $fbAvatarURL .= '&return_ssl_resources=1';

                                $html = $this->getSocialAvatarImage($fbAvatarURL, $fbProfileURL);
                        }
                }
                return $html;
        }

        function getLinkedInAvatar() {
                $html = "";
                if ($this->isJLinkedInstalled && $this->jlinkedLibrary->getMappedLinkedInUserId()) {
                        $app = JFactory::getApplication();

                        $liAvatarUrl = $app->getUserState('modtvtmaloginLiAvatar', null);
                        $liProfileURL = $app->getUserState('modtvtmaloginLiProfileURL', null);

                        if ($liAvatarUrl == null || $liProfileURL == null) {
                                $data = $this->jlinkedLibrary->api('profile', '~:(picture-url,public-profile-url)');
                                //Avatar URL
                                $liAvatarUrl = $data->get('picture-url');
                                $app->setUserState('modtvtmaloginLiAvatar', $liAvatarUrl);
                                //Profile URL
                                $liProfileURL = $data->get('public-profile-url');
                                $app->setUserState('modtvtmaloginLiProfileURL', $liProfileURL);
                        }

                        $html = $this->getSocialAvatarImage($liAvatarUrl, $liProfileURL);
                }
                return $html;
        }

        function getJoomlaAvatar($registerType, $profileLink, $user) {
                $html = '';
                if ($registerType == 'jomsocial' && file_exists(JPATH_BASE . '/components/com_community/libraries/core.php')) {
                        $jsUser = & CFactory::getUser($user->id);
                        $avatarURL = $jsUser->get('_avatar');
                        $html = $this->getSocialAvatarImage($avatarURL, $profileLink);
                } else if ($registerType == "communitybuilder" && file_exists(JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php')) {
                        $db = JFactory::getDbo();
                        $query = "SELECT avatar FROM #__comprofiler WHERE id = " . $user->id;
                        $db->setQuery($query);
                        $avatarURL = $db->loadResult();
                        if ($avatarURL)
                                $avatarURL = JRoute::_('images/comprofiler/' . $avatarURL, false);
                        $html = $this->getSocialAvatarImage($avatarURL, $profileLink);
                }
                else if ($registerType == 'kunena' && JFolder::exists(JPATH_SITE . '/components/com_kunena')) {
                        $db = JFactory::getDbo();
                        $query = "SELECT avatar FROM #__kunena_users WHERE userid = " . $user->id;
                        $db->setQuery($query);
                        $avatarURL = $db->loadResult();
                        if ($avatarURL)
                                $avatarURL = JRoute::_('media/kunena/avatars/' . $avatarURL, false);
                        $html = $this->getSocialAvatarImage($avatarURL, $profileLink);
                }
                return $html;
        }

        function getSocialAvatar($registerType, $profileLink, $user) {
                $html = "";
                $enableProfilePic = $this->params->get('enableProfilePic');
                if ($enableProfilePic == 'facebook') {
                        $html = $this->getFacebookAvatar();
                        if ($html == "")
                                $html = $this->getLinkedInAvatar();
                }
                else if ($enableProfilePic == 'linkedin') {
                        $html = $this->getLinkedInAvatar();
                        if ($html == "")
                                $html = $this->getFacebookAvatar();
                }
                else if ($enableProfilePic == 'joomla') {
                        $html = $this->getJoomlaAvatar($registerType, $profileLink, $user);
                }

                if ($html != "")
                        $html = '<div id="scprofile-pic">' . $html . '</div>';

                return $html;
        }

        function getJFBConnectAvatarSize() {
                $picHeightParam = $this->params->get("profileHeight");
                $picWidthParam = $this->params->get("profileWidth");

                $picHeight = intval($picHeightParam);
                $picWidth = intval($picWidthParam);

                if ($picWidth > 100)
                        return "large";
                else if ($picWidth > 50)
                        return "normal";
                else if ($picWidth <= 50 & $picHeight == $picWidth)
                        return "square";
                else
                        return "small";
        }

        function getFBLoginButton($loginButtonType, $orientation, $alignment, $fbLoginButtonLinkImage) {
                if ($loginButtonType == 'javascript') {
                        $jfbcLogin = $this->getJFBConnectLoginButton(null, DISPLAY_BLOCK, $alignment);
                } else if ($loginButtonType == 'icon_text_button') {
                        $jfbcLogin = $this->getJFBConnectLoginButton(JURI::root() . 'modules/mod_kiwilogin/images/button_facebook.png', DISPLAY_BLOCK, $alignment);
                } else if ($loginButtonType == 'icon_button') {
                        if ($orientation == 'side')
                                $display = DISPLAY_BLOCK;
                        else
                                $display = '';
                        $jfbcLogin = $this->getJFBConnectLoginButton(JURI::root() . 'modules/mod_kiwilogin/images/icon_facebook.png', $display, $alignment);
                }
                else if ($loginButtonType == "image_link") {
                        $jfbcLogin = $this->getJFBConnectLoginButton($fbLoginButtonLinkImage, DISPLAY_BLOCK, $alignment);
                } else {
                        $jfbcLogin = '';
                }

                return $jfbcLogin;
        }

        function getLILoginButton($loginButtonType, $orientation, $alignment, $liLoginButtonLinkImage) {
                if ($loginButtonType == 'javascript') {
                        $jlinkedLogin = $this->getJLinkedLoginButton(null, DISPLAY_BLOCK, $alignment);
                } else if ($loginButtonType == 'icon_text_button') {
                        $jlinkedLogin = $this->getJLinkedLoginButton(JURI::root() . 'modules/mod_kiwilogin/images/button_linkedin.png', DISPLAY_BLOCK, $alignment);
                } else if ($loginButtonType == 'icon_button') {
                        if ($orientation == 'side')
                                $display = DISPLAY_BLOCK;
                        else
                                $display = '';
                        $jlinkedLogin = $this->getJLinkedLoginButton(JURI::root() . 'modules/mod_kiwilogin/images/icon_linkedin.png', $display, $alignment);
                }
                else if ($loginButtonType == "image_link") {
                        $jlinkedLogin = $this->getJLinkedLoginButton($liLoginButtonLinkImage, DISPLAY_BLOCK, $alignment);
                } else {
                        $jlinkedLogin = '';
                }

                return $jlinkedLogin;
        }

        private function getJFBConnectLoginButton($buttonImage, $display, $alignment) {
                if ($this->isJFBConnectInstalled) {
                        if ($buttonImage) {
                                return '<div class="jfbcLogin pull-' . $alignment . '"><a' . $display . ' id="sc_fblogin" href="javascript:void(0)" onclick="jfbc.login.login_custom();"><img src="' . $buttonImage . '" /></a></div>';
                        } else {
                                $buttonSize = $this->params->get("loginButtonSize");
                                $loginButton = $this->jfbcLibrary->getLoginButton($buttonSize);
                                $loginButton = '<div class="jfbcLogin">' . $loginButton . '</div>';
                                return $loginButton;
                        }
                }
                return "";
        }

        private function getJLinkedLoginButton($buttonImage, $display, $alignment) {
                //Show JLinked Login Button
                if ($this->isJLinkedInstalled) {
                        if ($buttonImage) {
                                return '<div class="jLinkedLoginImage pull-' . $alignment . '"><a' . $display . ' id="sc_lilogin" href="javascript:void(0)" onclick="jlinked.login.login();"><img src="' . $buttonImage . '" /></a></div>';
                        } else {
                                $buttonSize = $this->params->get("loginButtonSize");
                                return '{JLinkedLogin size=' . $buttonSize . $this->jlinkedRenderKey . '}';
                        }
                }
                return "";
        }

        function getLogoutButton($useSecure, $jLogoutUrl) {
                if ($this->isJFBConnectInstalled) {
                        $button = '<input type="submit" name="Submit" id="jfbcLogoutButton" class="button btn btn-primary" value="'
                                . JText::_('JLOGOUT') . "\" onclick=\"javascript:jfbc.login.logout_button_click('" . $jLogoutUrl . "')\" />";
                        return $button;
                } else {
                        return '<div class="kiwi-login-module-joomla-login">
                <form action="' . JRoute::_('index.php', true, $useSecure) . '" method="post" id="kiwi-login-module-form">
                    <div class="logout-button" id="scLogoutButton">
                        <input type="submit" name="Submit" class="button btn btn-primary" value="' . JText::_('JLOGOUT') . '" />
                        <input type="hidden" name="option" value="com_users" />
                        <input type="hidden" name="task" value="user.logout" />
                        <input type="hidden" name="return" value="' . $jLogoutUrl . '" />'
                                . JHtml::_('form.token') . '
                    </div>
                </form>
            </div>';
                }
        }

        function getLogoutLink($useSecure, $jLogoutUrl) {
                $url = 'index.php?option=com_users&task=user.logout&' . JSession::getFormToken() . '=1&return=' . $jLogoutUrl;
                return '<a href="' . JRoute::_($url, true, $useSecure) . '"><span class="fa fa-sign-out"></span> ' . JText::_('JLOGOUT') . '</a>';
        }

        function getFBConnectButton($loginButtonType, $orientation, $alignment, $fbLoginButtonLinkImage) {
                if ($loginButtonType == 'javascript') {
                        $jfbcLogin = $this->getJFBCConnectButton(null, DISPLAY_BLOCK, $alignment);
                } else if ($loginButtonType == 'icon_text_button') {
                        $jfbcLogin = $this->getJFBCConnectButton(JURI::root() . 'modules/mod_kiwilogin/images/button_facebook.png', DISPLAY_BLOCK, $alignment);
                } else if ($loginButtonType == 'icon_button') {
                        if ($orientation == 'side')
                                $display = DISPLAY_BLOCK;
                        else
                                $display = '';
                        $jfbcLogin = $this->getJFBCConnectButton(JURI::root() . 'modules/mod_kiwilogin/images/icon_facebook.png', $display, $alignment);
                }
                else if ($loginButtonType == "image_link") {
                        $jfbcLogin = $this->getJFBCConnectButton($fbLoginButtonLinkImage, DISPLAY_BLOCK, $alignment);
                } else {
                        $jfbcLogin = '';
                }

                return $jfbcLogin;
        }

        private function getJFBCConnectButton($buttonImage, $display, $alignment) {
                if ($this->isJFBConnectInstalled && !$this->jfbcLibrary->getMappedFbUserId()) {
                        if ($buttonImage) {
                                return '<div class="jfbcConnect pull-' . $alignment . '"><a' . $display . ' id="sc_fbconnect" href="javascript:void(0)" onclick="jfbc.login.login_custom();"><img src="' . $buttonImage . '" /></a></div>';
                        } else {
                                $perms = JFBConnectProfileLibrary::getRequiredScope();
                                if ($perms != "")
                                        $perms = 'data-scope="' . $perms . '"'; // OAuth2 calls them 'scope'

                                $buttonHtml = '<div class="fb-connect-user">';
                                $buttonHtml .= '<div class="fb-login-button" onlogin="javascript:jfbc.login.on_login();" ' . $perms . '>' . JText::_('MOD_KIWILOGIN_CONNECT_BUTTON') . '</div>';
                                $buttonHtml .= '</div>';
                                return $buttonHtml;
                        }
                }
                return "";
        }

        function getLIConnectButton($loginButtonType, $orientation, $alignment, $liLoginButtonLinkImage) {
                if ($loginButtonType == 'javascript') {
                        $jlinkedLogin = $this->getJLinkedConnectButton(null, DISPLAY_BLOCK, $alignment);
                } else if ($loginButtonType == 'icon_text_button') {
                        $jlinkedLogin = $this->getJLinkedConnectButton(JURI::root() . 'modules/mod_kiwilogin/images/button_linkedin.png', DISPLAY_BLOCK, $alignment);
                } else if ($loginButtonType == 'icon_button') {
                        if ($orientation == 'side')
                                $display = DISPLAY_BLOCK;
                        else
                                $display = '';
                        $jlinkedLogin = $this->getJLinkedConnectButton(JURI::root() . 'modules/mod_kiwilogin/images/icon_linkedin.png', $display, $alignment);
                }
                else if ($loginButtonType == "image_link") {
                        $jlinkedLogin = $this->getJLinkedConnectButton($liLoginButtonLinkImage, DISPLAY_BLOCK, $alignment);
                } else {
                        $jlinkedLogin = '';
                }

                return $jlinkedLogin;
        }

        private function getJLinkedConnectButton($buttonImage, $display, $alignment) {
                if ($this->isJLinkedInstalled && !$this->jlinkedLibrary->getMappedLinkedInUserId()) {
                        if ($buttonImage) {
                                return '<div class="jLinkedLoginImage pull-' . $alignment . '"><a id="sc_liconnect" href="' . $this->jlinkedLibrary->getLoginURL() . '"><img src="' . $buttonImage . '" /></a></div>';
                        } else {
                                $buttonHtml = '<link rel="stylesheet" href="components/com_jlinked/assets/jlinked.css" type="text/css" />';
                                $buttonHtml .= '<div class="li-connect-user">';
                                $buttonHtml .= '<div class="jLinkedLogin"><a href="' . $this->jlinkedLibrary->getLoginURL() . '"><span class="jlinkedButton"></span><span class="jlinkedLoginButton">' . JText::_('MOD_KIWILOGIN_CONNECT_BUTTON') . '</span></a></div>';
                                $buttonHtml .= '</div>';
                                return $buttonHtml;
                        }
                }
                return "";
        }

        function getReconnectButtons($loginButtonType, $orientation, $alignment, $fbLoginButtonLinkImage, $liLoginButtonLinkImage) {
                $buttonHtml = '';
                if ($this->isJFBConnectInstalled)
                        $buttonHtml .= $this->getFBConnectButton($loginButtonType, $orientation, $alignment, $fbLoginButtonLinkImage);
                if ($this->isJLinkedInstalled)
                        $buttonHtml .= $this->getLIConnectButton($loginButtonType, $orientation, $alignment, $liLoginButtonLinkImage);

                if ($buttonHtml)
                        $buttonHtml = '<div class="sc-connect-user">' . JText::_('mod_kiwilogin_CONNECT_USER') . '</div>' . $buttonHtml;

                return $buttonHtml;
        }

        function getForgotUser($registerType, $showForgotUsername, $forgotLink, $forgotUsernameLink, $buttonImageColor) {
                $forgotlogin_questionsign = '<span class="fa fa-question-circle' . $buttonImageColor . '" title="' . JText::_('mod_kiwilogin_FORGOT_LOGIN') . '"> </span>';
                $forgotusername_questionsign = '<span class="fa fa-question-circle' . $buttonImageColor . '" title="' . JText::_('mod_kiwilogin_FORGOT_USERNAME') . '"> </span>';

                $forgotUsername = '';

                if ($showForgotUsername && $registerType == "communitybuilder" && file_exists(JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php')) {
                        $forgotUsername = '<a href="' . $forgotLink . '" class="forgot hasTooltip" data-placement="right" data-original-title="' . JText::_('mod_kiwilogin_FORGOT_LOGIN') . '">' . $forgotlogin_questionsign . '</a>';
                } else if ($showForgotUsername) {
                        $forgotUsername = '<a href="' . $forgotUsernameLink . '" class="forgot hasTooltip" data-placement="right" data-original-title="' . JText::_('mod_kiwilogin_FORGOT_USERNAME') . '">' . $forgotusername_questionsign . '</a>';
                }

                return $forgotUsername;
        }

        function getForgotPassword($registerType, $showForgotPassword, $forgotLink, $forgotPasswordLink, $buttonImageColor) {
                $forgotlogin_questionsign = '<span class="fa fa-question-circle' . $buttonImageColor . '" title="' . JText::_('mod_kiwilogin_FORGOT_LOGIN') . '"> </span>';
                $forgotpw_questionsign = '<span class="fa fa-question-circle' . $buttonImageColor . '" title="' . JText::_('mod_kiwilogin_FORGOT_PASSWORD') . '"> </span>';
                $forgotPassword = '';
                if ($showForgotPassword && $registerType == "communitybuilder" && file_exists(JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php')) {
                        $forgotPassword = '<a href="' . $forgotLink . '" class="forgot hasTooltip" data-placement="right" data-original-title="' . JText::_('mod_kiwilogin_FORGOT_LOGIN') . '">' . $forgotlogin_questionsign . '</a>';
                } else if ($showForgotPassword) {
                        $forgotPassword = '<a href="' . $forgotPasswordLink . '" class="forgot hasTooltip" data-placement="right" data-original-title="' . JText::_('mod_kiwilogin_FORGOT_PASSWORD') . '">' . $forgotpw_questionsign . '</a>';
                }

                return $forgotPassword;
        }

        function getUserMenu($userMenu, $menuStyle) {
                $app = JFactory::getApplication();
                $menu = $app->getMenu();
                $menu_items = $menu->getItems('menutype', $userMenu);

                if (!empty($menu_items)) {
                        $db = JFactory::getDbo();
                        $query = 'SELECT title FROM #__menu_types WHERE menutype=' . $db->quote($userMenu);
                        $db->setQuery($query);
                        $parentTitle = $db->loadResult();

                        if ($menuStyle) { //Show in List view
                                $menuNav = '<div class="scuser-menu list-view">';
                                //$menuNav .= '<ul class="menu nav"><li class="dropdown"><span>'.$parentTitle.'</span>';
                                $menuNav .= '<ul class="menu nav"><li><span>' . $parentTitle . '</span>';
                                //$menuNav .= '<ul class="dropdown-menu">';
                                $menuNav .= '<ul class="flat-list">';
                                foreach ($menu_items as $menuItem)
                                        $menuNav .= '<li><a href="' . $menuItem->link . '&Itemid=' . $menuItem->id . '">' . $menuItem->title . '</a></li>';
                                $menuNav .= '</ul>';
                                $menuNav .= '</li></ul>';
                                $menuNav .= '</div>';
                        } else { //Show in Bootstrap dropdown list
                                $menuNav = '';
                                foreach ($menu_items as $menuItem)
                                        $menuNav .= '<li><a href="' . $menuItem->link . '&Itemid=' . $menuItem->id . '">' . $menuItem->title . '</a></li>';
                        }
                } else
                        $menuNav = '';
                return $menuNav;
        }

}

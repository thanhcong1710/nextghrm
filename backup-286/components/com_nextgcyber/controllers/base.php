<?php

/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2015 NextgCyber . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://nextgerp.com
 * @author Daniel.Vu
 */
// No direct access
defined('_JEXEC') or die;
JLoader::register('NextgCyberCustomerHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/customerhelper.php');

class NextgCyberControllerBase extends JControllerLegacy {

    public function __construct($config = array()) {
        parent::__construct($config);
        $app = JFactory::getApplication();
        // Check if is robot
        if ($app->client->robot) {
            $app->close();
        }
    }

    /**
     *  Method to verify current user password
     *  @return JSON
     *  @since 1.0
     */
    public function verifyPassword() {
        // remove joomla notice, this make error
        error_reporting(0);
        ob_start();
        $id = $this->input->getInt('id', 0);
        $password = $this->input->getString('pwd', null);
        $response = array('label' => JText::_('COM_NEXTGCYBER_INSTANCE_FORM_VERIFY_REQUEST'));

        $user = JFactory::getUser();
        // Import related plugins
        jimport('joomla.user.authentication');
        JPluginHelper::importPlugin('authentication');
        JPluginHelper::importPlugin('user');

        // Get authentication plugins
        $plugins = JPluginHelper::getPlugin('authentication');

        // Create authentication response
        $authenticationResponse = new stdClass();
        $authentication = new JAuthentication;

        // Login data
        $credentials = ['username' => $user->get('username'), 'password' => $password];
        /*
         * Loop through the plugins and check if the credentials can be used to authenticate
         * the user
         *
         * Any errors raised in the plugin should be returned via the JAuthenticationResponse
         * and handled appropriately.
         */
        foreach ($plugins as $plugin) {
            // Do not use cookie data
            if ($plugin->name == 'cookie') {
                continue;
            }

            $className = 'plg' . $plugin->type . $plugin->name;

            if (class_exists($className)) {
                $plugin = new $className($authentication, (array) $plugin);
            } else {
                // Bail here if the plugin can't be created
                JLog::add(JText::sprintf('JLIB_USER_ERROR_AUTHENTICATION_FAILED_LOAD_PLUGIN', $className), JLog::WARNING, 'jerror');
                continue;
            }

            // Try to authenticate
            $plugin->onUserAuthenticate($credentials, array(), $authenticationResponse);

            // If authentication is successful break out of the loop
            if ($authenticationResponse->status === JAuthentication::STATUS_SUCCESS) {
                if (empty($authenticationResponse->type)) {
                    $authenticationResponse->type = isset($plugin->_name) ? $plugin->_name : $plugin->name;
                }
                break;
            }
        } // End foreach

        if (empty($authenticationResponse->username)) {
            $authenticationResponse->username = $credentials['username'];
        }

        if (empty($authenticationResponse->fullname)) {
            $authenticationResponse->fullname = $credentials['username'];
        }

        if (empty($authenticationResponse->password) && isset($credentials['password'])) {
            $authenticationResponse->password = $credentials['password'];
        }

        if ($authenticationResponse->status === JAuthentication::STATUS_SUCCESS) {
            $hashPassword = NextgCyberHelper::generateToken();
            $response['token'] = md5($id . $user->get('username') . $hashPassword);
        } else {
            $response['subtitle'] = JText::_('COM_NEXTGCYBER_ERROR_ON_VERIFY_PASSWORD');
            $response['subtitle_class'] = 'alert alert-danger';
        }
        ob_end_clean();
        echo new JResponseJson($response);
    }

    /**
     * Method to get login form
     * @return JSON
     * @since 1.0s
     */
    public function login() {
        $user = JFactory::getUser();
        $response = [];
        if ($user->guest) {
            $response['label'] = JText::_('COM_NEXTGCYBER_INSTANCE_FORM_ERROR_GUEST_REQUEST');
            $response['html'] = JLayoutHelper::render('com_nextgcyber.form.loginform', null, JPATH_COMPONENT);
            echo new JResponseJson($response);
            return false;
        }
    }

    /**
     * Method to get password validate form
     * @return JSON
     * @since 1.0
     */
    public function getValidate() {
        $user = JFactory::getUser();
        $input = JFactory::getApplication()->input;
        $id = $input->getInt('id', 0);
        $response = [];
        if ($user->guest) {
            $response['label'] = JText::_('COM_NEXTGCYBER_INSTANCE_FORM_ERROR_GUEST_REQUEST');
            $response['html'] = JLayoutHelper::render('com_nextgcyber.form.loginform', null, JPATH_COMPONENT);
            echo new JResponseJson($response);
            return false;
        } else {
            $displayData = new stdClass();
            $displayData->id = $id;
            $displayData->subtitle = JText::_('COM_NEXTGCYBER_INSTANCE_FORM_VERIFY_REQUEST_REASON');
            $response['label'] = JText::_('COM_NEXTGCYBER_INSTANCE_FORM_VERIFY_REQUEST');
            $response['html'] = JLayoutHelper::render('com_nextgcyber.form.password', $displayData, JPATH_COMPONENT);
            echo new JResponseJson($response);
            return false;
        }
    }

}

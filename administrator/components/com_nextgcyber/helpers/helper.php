<?php

/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

abstract class NextgCyberHelper extends JHelper {

    protected static $language_tag = null;

    /**
     *
     * @var type
     *
     * @since 1.0.0
     */
    protected static $usertoken = [];

    public function __construct() {
        $this->autoLoadHelper();
    }

    /**
     * Method to get current URI
     *
     * @param string|boolean $prefix
     * @return string
     * @since 1.0
     */
    public static function getReturn($prefix = '&return=') {
        $return = base64_encode(JURI::getInstance()->toString());
        if ($prefix) {
            $return = $prefix . $return;
        }
        return $return;
    }

    public static function addSubmenu($vName = false) {
        JHtml::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/html');
        $jiput = JFactory::getApplication()->input;
        $option = $jiput->get('option');
        $text_prefix = "COM_NEXTGCYBER_MENU";
        $return = static::getReturn();

        JHtml::_('tsidebar.addEntry', JText::_($text_prefix . '_DASHBOARD'), 'index.php?option=' . $option . '&view=nextgcyber', $vName == 'nextgcyber', 'fa fa-fw fa-dashboard');
        JHtml::_('tsidebar.addEntry', JText::_($text_prefix . '_SETTING'), 'index.php?option=com_config&view=component&component=com_nextgcyber' . $return, $vName == 'settings', 'fa fa-fw fa-cogs');

        //JHtml::_('tsidebar.addEntry', JText::_($text_prefix . '_PARTNERS'), 'index.php?option=' . $option . '&view=partners', $vName == 'partners', 'fa fa-fw fa-users');
        // Group
        JHtml::_('tsidebar.addEntry', JText::_($text_prefix . '_GROUP_DATA'), '#', ($vName == 'orders' || $vName == 'invoices' || $vName == 'products'), 'fa fa-fw fa-caret-square-o-down', true, 'order-group');
        //JHtml::_('tsidebar.addEntry', JText::_($text_prefix . '_PRODUCTS'), 'index.php?option=' . $option . '&view=products', $vName == 'products', 'fa fa-fw fa-plus');
        //JHtml::_('tsidebar.addEntry', JText::_($text_prefix . '_ORDERS'), 'index.php?option=' . $option . '&view=orders', $vName == 'orders', 'fa fa-fw fa-plus');
        //JHtml::_('tsidebar.addEntry', JText::_($text_prefix . '_INVOICES'), 'index.php?option=' . $option . '&view=invoices', $vName == 'invoices', 'fa fa-fw fa-plus', false, 'order-group', true);
        //JHtml::_('tsidebar.addEntry', JText::_($text_prefix . '_GROUP_ODOO'), '#', ($vName == 'instances' || $vName == 'subdomains'), 'fa fa-fw fa-caret-square-o-down', true, 'odoo-group');
        //JHtml::_('tsidebar.addEntry', JText::_($text_prefix . '_INSTANCES'), 'index.php?option=' . $option . '&view=instances', $vName == 'instances', 'fa fa-fw fa-plus');
        //JHtml::_('tsidebar.addEntry', JText::_($text_prefix . '_SUBDOMAINS'), 'index.php?option=' . $option . '&view=subdomains', $vName == 'subdomains', 'fa fa-fw fa-plus', false, 'odoo-group', true);
        JHtml::_('tsidebar.addEntry', JText::_($text_prefix . '_PRODUCTS'), 'index.php?option=' . $option . '&view=products', $vName == 'products', 'fa fa-fw fa-plus', false, 'odoo-group', true);
    }

    /**
     * Get version of erp manager component
     * @return string
     */
    public static function getInfo() {
        $version = static::getVersion();
        $info = '<div class="span12" style="text-align: center; margin: 5px 0;">NextgCyber(version ' . $version . '), &copy; 2016, All Rights Reserved.<br /></div>';
        return $info;
    }

    /**
     * Method to get current erpmanger version
     * @return string
     * @since 1.0.19
     */
    public static function getVersion() {
        $xml = JFactory::getXML(JPATH_ADMINISTRATOR . '/components/com_nextgcyber/nextgcyber.xml');
        $version = (string) $xml->version;
        return $version;
    }

    public static function getActions($section, $id = null) {
        // Reverted a change for version 2.5.6
        $user = JFactory::getUser();
        $result = new JObject;

        $path = JPATH_ADMINISTRATOR . '/components/com_nextgcyber/access.xml';

        if ($section && $id) {
            $assetName = 'com_nextgcyber' . '.' . $section . '.' . (int) $id;
        } else {
            $assetName = 'com_nextgcyber';
        }

        $actions = JAccess::getActionsFromFile($path, "/access/section[@name='component']/");

        foreach ($actions as $action) {
            $result->set($action->name, $user->authorise($action->name, $assetName));
        }

        return $result;
    }

    /**
     * Method to generate random string
     *
     * @param string $length Length of the return string. Default value is 32
     * @param string $characters sample string from which the randon string is generated. Default value is an empty string
     * @param array $reject array list of strings to be rejected when a generated string matchs. Default value is an empty array
     *
     * @return string return a random string with $length characters in length
     */
    public static function randomString($length = 32, $characters = '', $reject = array()) {
        if (!$characters) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        $max_rand_int = strlen($characters) - 1;

        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $max_rand_int)];
        }
        if (is_array($reject) && count($reject) > 0) {
            foreach ($reject as $value) {
                if ($randomString == $value) {
                    return self::randomString($length, $characters, $reject);
                }
            }
        }

        return $randomString;
    }

    /**
     * Returns a Model object, always creating it
     *
     * @param string $modelType model type to instanciate, e.g. Invoice
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return boolean|JModelLegacy A model object which is an instance of JModelLegacy or failed on failure
     */
    public static function getAdminModel($modelType, $config = array()) {
        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_nextgcyber/models', 'NextgCyberModel');
        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_nextgcyber/tables', 'NextgCyberTable');
        $tableFilePath = JPATH_ADMINISTRATOR . '/components/com_nextgcyber/tables/' . strtolower($modelType) . '.php';
        if (JFile::exists($tableFilePath)) {
            JLoader::register('NextgCyberTable' . ucfirst($modelType), $tableFilePath);
        }
        $model = JModelLegacy::getInstance($modelType, 'NextgCyberModel', $config);
        return $model;
    }

    /**
     * Get com_nextgcyber params. This method is a kind of wrapper for JComponentHelper::getParams('com_nextgcyber')
     *
     * @see JComponentHelper::getParams('com_nextgcyber')
     * @param mixed $name name of the param whose value will be returned
     *
     * @return mixed param value
     *
     * @since 1.0
     */
    public static function getParam($name, $default = null) {
        $componentParams = JComponentHelper::getParams('com_nextgcyber');
        return $componentParams->get($name, $default);
    }

    /**
     * Method to clear all space in data field
     * @param array $data
     * @return array
     * @since 1.0
     */
    public static function prepareSubmittedData($data) {
        // Validate input
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = trim($data[$key]);
            }
        }
        return $data;
    }

    /**
     * Method to update new component params into the database
     *
     * @param \Joomla\Registry\Registry $params new params
     *
     * @return boolean True on success update the database table, otherwise false
     *
     * @since 1.0
     */
    public static function updateParams(Joomla\Registry\Registry $params) {
        $db = JFactory::getDBO();
        try {
            // JSON format for the parameters
            $json_params = $params->toString();

            // Update the parameters into the database
            $query = $db->getQuery(true);
            $query->update($db->quoteName('#__extensions'))
                    ->set($db->quoteName('params') . ' = ' . $db->quote($json_params))
                    ->where($db->quoteName('name') . ' = ' . $db->quote('com_nextgcyber'));
            $db->setQuery($query);
            $db->execute();
            return true;
        } catch (Exception $ex) {
            JFactory::getApplication()->enqueueMessage($ex->getMessage(), 'error');
            return false;
        }
    }

    /**
     * function to get number of days in a fiven month in a year
     *
     * @param int $month
     * @param int $year
     */
    public static function days_in_month($month, $year) {
        // calculate number of days in a month
        $days = 0;
        if ($month == 2) {
            if ($year % 4) {
                $days = 28;
            } else {
                if ($year % 100) {
                    $days = 29;
                } else {
                    if ($year % 400) {
                        $days = 28;
                    } else {
                        $days = 29;
                    }
                }
            }
        } else {
            if (($month - 1) % 7 % 2) {
                $days = 30;
            } else {
                $days = 31;
            }
        }
        return $days;
    }

    /**
     * Method format number by language
     *
     * @param float $number
     * @param int $decimals
     * @param string $language_code
     *
     * @return float
     *
     * @since 1.0
     */
    public static function numberFormat($number, $decimals = null, $language_code = null) {
        if (!$language_code) {
            $language_code = JFactory::getLanguage()->getTag();
        }

        switch ($language_code) {
            case 'vi-VN':
                $decimals = !is_null($decimals) ? $decimals : 0;
                $dec_point = ',';
                $thousands_sep = '.';
                break;

            default:
                $decimals = !is_null($decimals) ? $decimals : 2;
                $dec_point = '.';
                $thousands_sep = ',';
                break;
        }
        return number_format($number, $decimals, $dec_point, $thousands_sep);
    }

    /**
     * Method to get http status code from HTTP response of a URL
     *
     * @param string $url
     *
     * @return mixed return false if failed or array information of http
     * <br/>array('code' => 200, 'redirect_url' => false)
     * <br/>return HTTP status code, e.g. 200 (OK), 404 (file not found), etc on success, otherwise return false;
     *
     * @throws Exception throw an exception if PHP5 CURL functionality is not available
     *
     * @since 1.0
     *
     * @see rfc2616 http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
     */
    public static function getHTTPCode($url) {
        // check if php CURL is available
        if (!function_exists('curl_init')) {
            throw new Exception('PHP5 CURL functionality is not available. Please install it. On debian/ubuntu, just execute command "apt-get install php5-curl", then restart web server."!');
        }
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($handle);
        if ($response === false) {
            return false;
        }

        $return = array();
        $return['code'] = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $return['redirect_url'] = curl_getinfo($handle, CURLINFO_REDIRECT_URL);
        if ($return['code'] != 200) {
            JFactory::getApplication()->enqueueMessage(curl_error($handle));
        }
        curl_close($handle);
        return $return;
    }

    /**
     * Method calculate percent in range
     * @param int $from
     * @param int $to
     * @param int $percent
     * @return integer
     * @since 1.0.0
     */
    public static function calculatePercentage($from, $to, $percent) {
        $range = $to - $from;
        return $from + ($range / 100 * $percent);
    }

    /**
     * Get unique string against time stamp
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function getUniqueStr() {
        return uniqid(md5(JFactory::getConfig()->get('secret')));
    }

    /**
     * Method to generate token based on user id and user hashed-stored passwd
     *
     * @param int $uid if no input, current login user id will be used
     *
     * @return string
     *
     * @sicne 3.8.0
     */
    public static function generateToken($uid = null) {
        if (empty($uid)) {
            $user = JFactory::getUser();
            $uid = $user->get('id');
        }

        if ($uid > 0) {
            if (!isset(self::$usertoken[$uid])) {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('password')
                        ->from('#__users')
                        ->where('id = ' . (int) $uid);
                $db->setQuery($query);
                $password = (string) $db->loadResult();
                self::$usertoken[$uid] = JApplicationHelper::getHash($uid . $password);
            }
            return self::$usertoken[$uid];
        }
        return JApplicationHelper::getHash($uid . 'guest');
    }

    /**
     * Method crate secret token
     * @param string $string
     * @param string $salt
     * @return string
     * @since 1.0.18
     */
    public static function createSecretToken($string, $salt = "") {
        $config = JFactory::getConfig();
        $secret = $config->get('secret');
        return JApplicationHelper::getHash($secret . $string . $salt);
    }

    /**
     * Method to get all error message
     * @param boolean $html
     * @return string
     * @since 1.0
     * @author Daniel.Vu
     */
    public function getErrorMessage($html = false) {
        $app = JFactory::getApplication();
        $messages = $app->getMessageQueue();
        $msg = '';
        foreach ($messages as $message) {
            if ($html) {
                $msg = '<li>' . $message['message'] . '</li>' . $msg;
            } else {
                $msg = $message['message'] . $msg;
            }
        }
        $msg = ($html && $msg) ? '<ul>' . $msg . '</ul>' : $msg;
        return $msg;
    }

}

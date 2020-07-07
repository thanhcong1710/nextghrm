<?php

/**
 * @package nextgcyber NextgCyber for Joomla
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://domain.com
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;

JLoader::register('NextgCyberHelper', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/helper.php');

class NextgCyberIPHelper extends NextgCyberHelper {

    /**
     * Method to check localhost
     * @return boolean
     * @since 1.0.18
     */
    public static function isLocalHost() {
        $whitelist = array(
            '127.0.0.1',
            '::1'
        );
        if (!in_array(static::getIP(), $whitelist)) {
            return false;
        }
        return true;
    }

    /**
     * Method to get current IP of user
     * @since 1.0.18
     * @return string
     */
    public static function getIP() {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Method auto dectect tax by country
     * @return integer
     * @since 1.2
     */
    public static function useTax() {
        if (!static::isLocalHost()) {
            $country = geoip_record_by_name(static::getIP());
            if (!empty($country)) {
                $country_code = NextgCyberHelper::getParam('country_code', 'NZL');
                if (empty($country_code)) {
                    return True;
                }
                if ($country_code == $country['country_code3']) {
                    return true;
                }
            }
            return false;
        }

        return false;
    }

}

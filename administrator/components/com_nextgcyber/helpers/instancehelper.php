<?php

/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;

class NextgCyberInstanceHelper extends NextgCyberHelper {

    public static function isReady($ip, $port, $retry = 3) {
        try {
            $http_response = static::getHTTPCode('http://' . $ip . ':' . $port);
            switch ($http_response['code']) {
                case 302:
                    if (!empty($http_response['redirect_url'])) {
                        while ($http_response['code'] != 200 && !empty($http_response['redirect_url'])) {
                            $http_response = static::getHTTPCode($http_response['redirect_url']);
                        }

                        if ($http_response['code'] == 200) {
                            return true;
                        }
                    }

                    return $http_response['code'];

                case 200:
                    return true;

                default:
                    sleep(1);
                    $retry--;
                    while ($retry > 0) {
                        return self::isReady($ip, $port, $retry);
                    }
                    return $http_response['code'];
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}

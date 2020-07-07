<?php

/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;

class NextgCyberDateTimeHelper extends NextgCyberHelper {

    public static function addTime(JDate $startdate, $interval = '1 month') {
        $startdate->add(DateInterval::createfromdatestring('+' . $interval));
        return $startdate;
    }

    public static function subTime(JDate $startdate, $interval = '1 month') {
        $startdate->sub(DateInterval::createfromdatestring($interval));
        return $startdate;
    }

}

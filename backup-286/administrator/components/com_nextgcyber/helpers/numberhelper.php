<?php

/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;

class NextgCyberNumberHelper extends NextgCyberHelper {

    public static function format($number, $decimals = 2, $dec_point = '.', $thousands_sep = ',') {
        return number_format($number, $decimals, $dec_point, $thousands_sep);
    }

    /**
     * Method to convert byte to gb
     * @param integer $number
     * @return float
     * @since 1.0
     */
    public static function toGb($number) {
        $gb = $number / pow(1024, 3);
        return number_format($gb, 2);
    }

    public static function formatBytes($size, $precision = 2) {
        $size = (int) $size;
        $base = log($size, 1024);
        $suffixes = array('', 'k', 'M', 'G', 'T');

        $formated = round(pow(1024, $base - floor($base)), $precision);
        $formated .= (!empty($suffixes[floor($base)])) ? $suffixes[floor($base)] : '';
        return $formated;
    }

    public static function toByteSize($p_sFormatted) {
        $aUnits = array('B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4, 'PB' => 5, 'EB' => 6, 'ZB' => 7, 'YB' => 8);
        $sUnit = strtoupper(trim(substr($p_sFormatted, -2)));
        if (intval($sUnit) !== 0) {
            $sUnit = 'B';
        }
        if (!in_array($sUnit, array_keys($aUnits))) {
            return false;
        }
        $iUnits = trim(substr($p_sFormatted, 0, strlen($p_sFormatted) - 2));
        if (!intval($iUnits) == $iUnits) {
            return false;
        }
        return $iUnits * pow(1024, $aUnits[$sUnit]);
    }

}

<?php

/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;
JLoader::register('NextgCyberOdooDB', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/odoodb.php');

class NextgCyberCurrencyHelper extends NextgCyberHelper {

    protected static $currency = null;

    public static function getCurrency() {
        if (!empty(static::$currency)) {
            return static::$currency;
        }
        $odoo_db = new NextgCyberOdooDB();
        $conn = $odoo_db->getConn();
        $currency = $conn->getCurrency();
        if (empty($currency)) {
            return false;
        }
        static::$currency = (object) $currency;
        return static::$currency;
    }

}

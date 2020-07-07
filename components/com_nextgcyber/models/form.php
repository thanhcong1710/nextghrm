<?php

/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://domain.com
 * @author Daniel.Vu
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

JLoader::register('NextgCyberModelBaseItem', JPATH_COMPONENT . '/models/baseitem.php');

class NextgCyberModelForm extends NextgCyberModelBaseItem {

    /**
     * Method to get all pricelist
     * @return array
     * @since 1.2
     * @author Daniel.Vu
     */
    public function getPricelistOptions() {
        $db = $this->getOdooDB();
        $query = $db->getQuery(true);
        $query->select('id,name');
        $query->from('product.pricelist');
        $query->where('type = sale');
        $db->setQuery($query);
        $items = $db->loadObjectList();
        return $items;
    }

    /**
     * Method to get all payment period
     * @return array
     * @since 1.2
     * @author Daniel.Vu
     */
    public function getPaymentPeriod() {
        $db = $this->getOdooDB();
        $query = $db->getQuery(true);
        $query->select('id,name,month_number,discount');
        $query->from('nc.payment.period');
        $db->setQuery($query);
        $items = $db->loadObjectList();
        return $items;
    }

}

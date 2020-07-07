<?php

/**
 * @package pkg_nextgcyber
 * @subpackage  com_nextgcyber
 * @copyright Copyright (C) 2015 NextgCyber . All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://nextgerp.com
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;

/**
 * This models supports retrieving lists of article plans.
 *
 * @package pkg_nextgcyber
 * @subpackage  com_nextgcyber
 * @since       1.0
 */
JLoader::register('NextgCyberModelPricing', JPATH_COMPONENT . '/models/pricing.php');

class NextgCyberModelTrial extends NextgCyberModelPricing {

    protected $odoo_model = 'product.product';

    /**
     *
     * @var type
     */
    private $_items = null;

}

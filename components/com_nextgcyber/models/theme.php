<?php

/**
 * @package pkg_nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2015 NextgCyber . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://nextgerp.com
 * @author NextG-ERP support@nextgerp.com
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
JLoader::register('NextgCyberModelBaseItem', JPATH_COMPONENT . '/models/baseitem.php');

class NextgCyberModelTheme extends NextgCyberModelBaseItem {

    /**
     * Model context string.
     *
     * @var    string
     * @since  12.2
     */
    protected $_context = 'com_nextgcyber.product.product';
    protected $odoo_model = 'product.product';

    public function getItem($pk = null) {
        $item = parent::getItem($pk);
        return $item;
    }

}

<?php

/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://nextgcyber.com
 * @author Daniel.Vu
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
JLoader::register('NextgCyberTableBase', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/tables/base.php');

class NextgCyberTableProduct extends NextgCyberTableBase {

    /**
     * Constructor
     *
     * @param object Database connector object
     */
    public function __construct() {
        $this->object_name = 'product.product';
        parent::__construct();
    }

}

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
defined('_JEXEC') or die;

class NextgCyberControllerInvoices extends NextgCyberControllerAdmin {

    public function getModel($name = 'Invoice', $prefix = 'NextgCyberModel', $config = array('ignore_request' => true)) {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

}

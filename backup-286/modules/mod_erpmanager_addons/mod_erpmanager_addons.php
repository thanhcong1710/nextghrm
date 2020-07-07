<?php

/**
 * @package pkg_nextgcyber
 * @subpackage  mod_nextgcyber_addons
 *
 * @copyright Copyright (C) 2015 NextG-ERP . All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://nextgerp.com
 * @author NextG-ERP support@nextgerp.com
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once( dirname(__FILE__) . '/helper.php' );

$addons = modNextgCyberAddonsHelper::getList($params);
require(JModuleHelper::getLayoutPath('mod_nextgcyber_addons'));

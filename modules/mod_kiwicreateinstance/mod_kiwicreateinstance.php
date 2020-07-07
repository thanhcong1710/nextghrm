<?php

/**
 * @package mod_kiwicreateinstance
 * @subpackage  mod_kiwicreateinstance
 *
 * @copyright Copyright (C) 2015 NextG-ERP. All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://nextgerp.com
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/helper.php';
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
require JModuleHelper::getLayoutPath('mod_kiwicreateinstance', $params->get('layout', 'default'));

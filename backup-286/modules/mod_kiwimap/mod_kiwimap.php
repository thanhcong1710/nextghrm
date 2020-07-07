<?php

/**
 * @package mod_kiwimap
 * @subpackage  mod_kiwimap
 *
 * @copyright Copyright (C) 2015 NextG-ERP. All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: nextgerp.com
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';
$displayData = ModKiwiMapHelper::getMap($params);
if ($displayData) {
        $moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
        require JModuleHelper::getLayoutPath('mod_kiwimap', $params->get('layout', 'default'));
}


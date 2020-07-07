<?php

/**
 * @package mod_kiwiagentregister
 * @subpackage  mod_kiwiagentregister
 *
 * @copyright Copyright (C) 2015 NextG-ERP. All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://nextgerp.com
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/helper.php';
$doc = JFactory::getDocument();
$doc->addScript(JUri::root(true) . '/modules/mod_kiwiagentregister/assets/js/agentregister.js');
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
require JModuleHelper::getLayoutPath('mod_kiwiagentregister', $params->get('layout', 'default'));

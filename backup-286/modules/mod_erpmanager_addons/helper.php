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
require_once JPATH_SITE . '/components/com_nextgcyber/helpers/route.php';

JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_nextgcyber/models', 'NextgCyberModel');

class modNextgCyberAddonsHelper
{

        /**
         * Retrieves the hello message
         *
         * @param array $params An object containing the module parameters
         * @access public
         */
        public static function getList(&$params)
        {
                // Get the dbo
                $db = JFactory::getDbo();

                // Get an instance of the generic articles model
                $model = JModelLegacy::getInstance('Plans', 'NextgCyberModel', array('ignore_request' => true));

                // Set application parameters in model
                $app = JFactory::getApplication();
                $appParams = $app->getParams();
                $model->setState('params', $appParams);

                // We get addons only
                $model->setState('filter.isaddon', 1);

                // Set the filters based on the module params
                $model->setState('list.start', 0);
                $model->setState('list.limit', 0);
                $model->setState('filter.published', 1);

                // Access filter
//                $access = !JComponentHelper::getParams('com_nextgcyber')->get('show_noauth');
//                $model->setState('filter.access', $access);
                // Filter by language
                $model->setState('filter.language', $app->getLanguageFilter());

                $items = $model->getItems();

//                foreach ($items as &$item)
//                {
//
//                }

                return $items;
        }

}

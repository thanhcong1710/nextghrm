<?php

/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2015 NextgCyber . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://nextgerp.com
 * @author NextG-ERP support@nextgerp.com
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class NextgCyberControllerAdmin extends JControllerAdmin
{

        function __construct($config = array())
        {
                parent::__construct($config);
        }

        /**
         * Function that allows child controller access to model data
         * after the item has been deleted.
         *
         * @param   JModelLegacy  $model  The data model object.
         * @param   integer       $ids    The array of ids for items being deleted.
         *
         * @return  void
         *
         * @since   3.0
         */
        protected function postDeleteHook(JModelLegacy $model, $ids = null)
        {

        }

}

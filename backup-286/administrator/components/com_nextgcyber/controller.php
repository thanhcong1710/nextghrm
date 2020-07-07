<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * General Controller of erp_manager component
 */
class NextgCyberController extends JControllerLegacy
{

        /**
         * display task
         *
         * @return void
         */
        public function display($cachable = false, $urlparams = false)
        {
                // set default view if not set
                $input = JFactory::getApplication()->input;
                $input->set('view', $input->get('view', 'NextgCyber'));
                // call parent behavior
                parent::display($cachable, $urlparams);
        }

}

<?php

/**
 * @package     Joomla.Plugin
 * @subpackage  System.redirect
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Plugin class for redirect handling.
 *
 * @since  1.6
 */
class PlgSystemNCHelper extends JPlugin {

    /**
     * Affects constructor behavior. If true, language files will be loaded automatically.
     *
     * @var    boolean
     * @since  3.4
     */
    protected $autoloadLanguage = false;

    /**
     * Constructor.
     *
     * @param   object  &$subject  The object to observe
     * @param   array   $config    An optional associative array of configuration settings.
     *
     * @since   1.6
     */
    public function __construct(&$subject, $config) {
        parent::__construct($subject, $config);
    }

    public function onUserAfterLogin($options) {
        $session = JFactory::getSession();
        $pricing_store = $session->get('pricing.store', array());
        if (!empty($pricing_store)) {
            JLoader::register('NextgCyberHelperRoute', JPATH_SITE . '/components/com_nextgcyber/helpers/route.php');
            $app = JFactory::getApplication();
            if (!empty($pricing_store['type']) && $pricing_store['type'] == 'pricing') {
                $url = JRoute::_(NextgCyberHelperRoute::getPricingRoute());
            } else {
                $url = JRoute::_(NextgCyberHelperRoute::getTryingRoute());
            }
            $app->redirect($url);
        }
    }

}

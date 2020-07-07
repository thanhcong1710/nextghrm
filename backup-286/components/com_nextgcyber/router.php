<?php

/**
 * @package pkg_nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://domain.com
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;

/**
 * Routing class from com_nextgcyber
 * @package pkg_nextgcyber
 * @subpackage  com_nextgcyber
 * @since       1.0
 */
class NextgCyberRouter extends JComponentRouterBase {

    /**
     * Build the route for the com_nextgcyber component
     *
     * @param   array  &$query  An array of URL arguments
     *
     * @return  array  The URL arguments to use to assemble the subsequent URL.
     *
     * @since   3.3
     */
    public function build(&$query) {
        $segments = array();

        // Get a menu item based on Itemid or currently active
        $app = JFactory::getApplication();
        $menu = $app->getMenu();
        // We need a menu item.  Either the one specified in the query, or the current active one if none specified
        if (empty($query['Itemid'])) {
            $menuItem = $menu->getActive();
            $menuItemGiven = false;
        } else {
            $menuItem = $menu->getItem($query['Itemid']);
            $menuItemGiven = true;
        }

        // Check again
        if ($menuItemGiven && isset($menuItem) && $menuItem->component != 'com_nextgcyber') {
            $menuItemGiven = false;
            unset($query['Itemid']);
        }

        if (isset($query['view'])) {
            $view = $query['view'];
        } else {
            return $segments;
        }

        // Are we dealing with an plan or category that is attached to a menu item?
        if (($menuItem instanceof stdClass) && $menuItem->query['view'] == $query['view'] && isset($query['id']) && isset($menuItem->query['id']) && $menuItem->query['id'] == (int) $query['id']) {
            unset($query['view']);

            if (isset($query['layout'])) {
                unset($query['layout']);
            }

            unset($query['id']);

            return $segments;
        }

        // Plans view, dashboard view
        if ($view == 'dashboard' || $view == 'profile' || $view == 'pricing' || $view == 'trial') {
            if (!$menuItemGiven || $menuItem->query['view'] != $view) {
                $segments[] = $view;
            }

            unset($query['view']);
            return $segments;
        }

        // list link
        $listlink = ['instances', 'invoices', 'orders'];
        if (in_array($view, $listlink)) {
            if (!$menuItemGiven || $menuItem->query['view'] != $view) {
                $segments[] = $view;
            }
            unset($query['view']);
            return $segments;
        }

        // Detail link
        $detaillink = ['instance', 'order', 'invoice'];
        if (in_array($view, $detaillink)) {
            if ($menuItem->query['view'] != $view) {
                $segments[] = $view;
            }
            $segments[] = $query['id'];
            unset($query['view']);
            unset($query['id']);
            return $segments;
        }

        if ($view == 'archive') {
            if (!$menuItemGiven) {
                $segments[] = $view;
                unset($query['view']);
            }

            if (isset($query['year'])) {
                if ($menuItemGiven) {
                    $segments[] = $query['year'];
                    unset($query['year']);
                }
            }

            if (isset($query['year']) && isset($query['month'])) {
                if ($menuItemGiven) {
                    $segments[] = $query['month'];
                    unset($query['month']);
                }
            }
        }

        if ($view == 'featured') {
            if (!$menuItemGiven) {
                $segments[] = $view;
            }

            unset($query['view']);
        }

        /*
         * If the layout is specified and it is the same as the layout in the menu item, we
         * unset it so it doesn't go into the query string.
         */
        if (isset($query['layout'])) {
            if ($menuItemGiven && isset($menuItem->query['layout'])) {
                if ($query['layout'] == $menuItem->query['layout']) {
                    unset($query['layout']);
                }
            } else {
                if ($query['layout'] == 'default') {
                    unset($query['layout']);
                }
            }
        }

        $total = count($segments);
        for ($i = 0; $i < $total; $i++) {
            $segments[$i] = str_replace(':', '-', $segments[$i]);
        }
        return $segments;
    }

    /**
     * Parse the segments of a URL.
     *
     * @param   array  &$segments  The segments of the URL to parse.
     *
     * @return  array  The URL attributes to be used by the application.
     *
     * @since   3.3
     */
    public function parse(&$segments) {
        $total = count($segments);
        $vars = array();

        for ($i = 0; $i < $total; $i++) {
            $segments[$i] = preg_replace('/-/', ':', $segments[$i], 1);
        }

        // Get the active menu item.
        $app = JFactory::getApplication();
        $menu = $app->getMenu();
        $item = $menu->getActive();
        // Count route segments
        $count = count($segments);

        /*
         * Standard routing for plans.  If we don't pick up an Itemid then we get the view from the segments
         * the first segment is the view and the last segment is the id of the plan or category.
         */
        if (!isset($item)) {
            $vars['view'] = $segments[0];
            $vars['id'] = $segments[$count - 1];
            return $vars;
        }

        /*
         * If there is only one segment, then it points to either an plan or a category.
         * We test it first to see if it is a category.  If the id and alias match a category,
         * then we assume it is a category.  If they don't we assume it is an plan
         */
        if ($count == 1) {
            switch ($segments[0]) {
                case 'test':
                    $vars['view'] = 'test';
                    return $vars;
                default:
                    $vars['view'] = $segments[0];
                    return $vars;
            }
        }

        if ($count == 2) {
            switch ($segments[0]) {
                case 'test':
                    $vars['view'] = 'test';
                    $vars['layout'] = $segments[1];
                    return $vars;
                default:
                    $vars['view'] = $segments[0];
                    $vars['id'] = $segments[1];
                    return $vars;
            }
        }
        return $vars;
    }

}

function NextgCyberParseRoute($segments) {
    $router = new NextgCyberRouter;
    return $router->parse($segments);
}

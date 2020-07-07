<?php

/**
 * @package        JFBConnect/JLinked
 * @copyright (C) 2011-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

class SCLibraryUtilities {

        public static function getLinkFromMenuItem($itemId, $isLogout) {
                $app = JFactory::getApplication();
                $menu = $app->getMenu();
                $item = $menu->getItem($itemId);

                if ($item) {
                        if ($item->type == 'url') { //External menu item
                                $redirect = $item->link;
                        } else if ($item->type == 'alias') { //Alias menu item
                                $aliasedId = $item->params->get('aliasoptions');

                                if ($isLogout && static::isMenuRegistered($aliasedId))
                                        $link = 'index.php';
                                else
                                        $link = static::getLinkWithItemId($item->link, $aliasedId);
                                $redirect = JRoute::_($link, false);
                        }
                        else { //Regular menu item
                                if ($isLogout && static::isMenuRegistered($itemId))
                                        $link = 'index.php';
                                else
                                        $link = static::getLinkWithItemId($item->link, $itemId);
                                $redirect = JRoute::_($link, false);
                        }
                } else
                        $redirect = '';

                return $redirect;
        }

        public static function getLinkWithItemId($link, $itemId) {
                $app = JFactory::getApplication();
                $router = $app->getRouter();

                if ($link) {
                        if ($router->getMode() == JROUTER_MODE_SEF)
                                $url = 'index.php?Itemid=' . $itemId;
                        else
                                $url = $link . '&Itemid=' . $itemId;
                } else
                        $url = '';

                return $url;
        }

        public static function isMenuRegistered($menuItemId) {
                $db = JFactory::getDBO();
                $query = "SELECT * FROM #__menu WHERE id=" . $db->quote($menuItemId);
                $db->setQuery($query);
                $menuItem = $db->loadObject();
                return ($menuItem && $menuItem->access != "1");
        }

}

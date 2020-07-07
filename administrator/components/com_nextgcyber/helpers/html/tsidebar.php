<?php

/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author Daniel.Vu
 */
// No direct access

defined('_JEXEC') or die('Restricted access');

/**
 * Utility class to render a list view sidebar
 *
 * @package     Joomla.Libraries
 * @subpackage  HTML
 * @since       1.0
 */
abstract class JHtmlTSidebar extends JHtmlSidebar {

    /**
     * Render the sidebar.
     *
     * @return  string  The necessary HTML to display the sidebar
     *
     * @since   1.0
     */
    public static function render() {
        // Collect display data
        $data = new stdClass;
        $data->list = static::getEntries();
        $data->filters = static::getFilters();
        $data->action = static::getAction();
        $data->displayMenu = count($data->list);
        $data->displayFilters = count($data->filters);
        $data->hide = JFactory::getApplication()->input->getBool('hidemainmenu');

        // Create a layout object and ask it to render the sidebar
        $layout = new JLayoutFile('com_nextgcyber.sidebars.submenu', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/layouts');
        $sidebarHtml = $layout->render($data);

        return $sidebarHtml;
    }

    /**
     *
     * @param type $name
     * @param type $link
     * @param type $active
     * @param type $parent_id
     * @param type $icon
     * @param type $is_parent
     * @param type $group
     * @param type $end
     */
    public static function addEntry($name, $link = '', $active = false, $icon = 'icon-home', $is_parent = false, $group = false, $end = false) {
        static::$entries[$name] = array('name' => $name, 'link' => $link, 'active' => $active, 'icon' => $icon, 'is_parent' => $is_parent, 'group' => $group, 'end' => $end);
    }

}

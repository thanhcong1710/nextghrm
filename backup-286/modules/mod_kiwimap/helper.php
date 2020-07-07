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
if (!defined('DS')) {
        define('DS', DIRECTORY_SEPARATOR);
}

JLoader::register('PhocaMapsHelperRoute', JPATH_SITE . '/components/com_phocamaps/helpers/route.php');
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_phocamaps/models', 'PhocaMapsModel');
JLoader::register('PhocaMapsHelper', JPATH_ADMINISTRATOR . '/components/com_phocamaps/helpers/phocamaps.php');
JLoader::register('PhocaMapsMap', JPATH_ADMINISTRATOR . '/components/com_phocamaps/helpers/phocamapsmap.php');

class ModKiwiMapHelper {

        public static function getMap(&$params) {
                $language = JFactory::getLanguage();
                $language->load('com_phocamaps', JPATH_ROOT, $language->getTag(), true);

                $model = JModelLegacy::getInstance('Map', 'PhocaMapsModel', array('ignore_request' => true));
                $model->setId($params->get('id', 0));
                $item = $model->getData();
                $document = JFactory::getDocument();
                $app = JFactory::getApplication();
                $displayData = new stdClass();
                $displayData->t['p'] = $app->getParams();

                // PLUGIN WINDOW - we get information from plugin
                $get = '';
                $get['tmpl'] = $app->input->get('tmpl', '', 'string');

                JHTML::stylesheet('media/com_phocamaps/css/phocamaps.css');
                if (JFile::exists(JPATH_SITE . '/media/com_phocamaps/css/custom.css')) {
                        JHTML::stylesheet('media/com_phocamaps/css/custom.css');
                }
                $displayData->t['enable_kml'] = $displayData->t['p']->get('enable_kml', 0);
                $displayData->t['display_print_route'] = $displayData->t['p']->get('display_print_route', 1);
                $displayData->t['close_opened_window'] = $displayData->t['p']->get('close_opened_window', 0);
                $displayData->t['load_api_ssl'] = (int) $displayData->t['p']->get('load_api_ssl', 0);

                $displayData->map = $item['map'];
                $displayData->marker = $item['marker'];



                if ((!isset($displayData->map)) || (isset($displayData->map) && $displayData->map == null)) {
                        echo '<div id="phocamaps"><div class="error">' . JText::_('COM_PHOCAMAPS_WARNING_SELECT_MAP') . '</div></div>';
                        return false;
                }

                // Plugin information
                $displayData->t['pluginmap'] = 0;
                if (isset($get['tmpl']) && $get['tmpl'] == 'component') {
                        $displayData->t['pluginmap'] = 1;
                        // NO SCROLLBAR if windows is called by plugin but if there is a route form, display it
                        if (isset($displayData->map->displayroute) && $displayData->map->displayroute == 1) {
                                $document->addCustomTag("<style type=\"text/css\"> \n"
                                        . " html,body, .contentpane{background:#ffffff;text-align:left;} \n"
                                        . " </style> \n");
                        } else {
                                $document->addCustomTag("<style type=\"text/css\"> \n"
                                        . " html,body, .contentpane{overflow:hidden;background:#ffffff;} \n"
                                        . " </style> \n");
                        }
                }

                // Display Description
                $displayData->t['description'] = '';
                if (isset($displayData->map->description) && $displayData->map->description != '' && $displayData->t['pluginmap'] == 0) {
                        $displayData->t['description'] = '<div class="pm-desc">' . $displayData->map->description . '</div>';
                }

                // Check Width and Height
                $displayData->t['fullwidth'] = 0;
                if (!isset($displayData->map->width)) {
                        $displayData->map->width = 400;
                }
                if (isset($displayData->map->width) && (int) $displayData->map->width < 1) {
                        $displayData->t['fullwidth'] = 1;
                }
                if (!isset($displayData->map->height) || (isset($displayData->map->height) && (int) $displayData->map->height < 1)) {
                        $displayData->map->height = 400;
                }
                if (!isset($displayData->map->zoom) || (isset($displayData->map->zoom) && (int) $displayData->map->zoom < 1)) {
                        $displayData->map->zoom = 2;
                }

                // Map Langugage


                $displayData->t['params'] = '';
                if (!isset($displayData->map->lang) || (isset($displayData->map->lang) && $displayData->map->lang == '')) {
                        $displayData->t['params'] = '{other_params:"sensor=false"}';
                        $displayData->t['paramssearch'] = '';
                        $displayData->t['lang'] = '';
                } else {
                        //$displayData->t['params'] = '{"language":"'.$displayData->map->lang.'", "other_params":"sensor=false"}';
                        $displayData->t['params'] = '{other_params:"sensor=false&language=' . $displayData->map->lang . '"}';
                        $displayData->t['paramssearch'] = '{"language":"' . $displayData->map->lang . '"}';
                        $displayData->t['lang'] = $displayData->map->lang;
                }

                // Design
                $displayData->t['border'] = '';
                if (isset($displayData->map->border)) {
                        switch ($displayData->map->border) {
                                case 1:
                                        $displayData->t['border'] = '-grey';
                                        break;
                                case 2:
                                        $displayData->t['border'] = '-greywb';
                                        break;
                                case 3:
                                        $displayData->t['border'] = '-greyrc';
                                        break;
                                case 4:
                                        $displayData->t['border'] = '-black';
                                        break;
                        }
                }

                // Plugin - no border
                if ($displayData->t['pluginmap'] == 1) {
                        $displayData->t['border'] = '';
                        $displayData->t['stylesite'] = 'margin:10px;';
                } else {
                        $displayData->t['stylesite'] = 'margin:0;padding:0;margin-top:10px;';
                }

                $displayData->t['stylesitewidth'] = '';
                if ($displayData->t['fullwidth'] == 1) {
                        $displayData->t['stylesitewidth'] = 'style="width:100%"';
                }

                // Parameters
                if (isset($displayData->map->continuouszoom) && (int) $displayData->map->continuouszoom == 1) {
                        $displayData->map->continuouszoom = 1;
                } else {
                        $displayData->map->continuouszoom = 0;
                }

                if (isset($displayData->map->doubleclickzoom) && (int) $displayData->map->doubleclickzoom == 1) {
                        $displayData->map->disabledoubleclickzoom = 0;
                } else {
                        $displayData->map->disabledoubleclickzoom = 1;
                }

                if (isset($displayData->map->scrollwheelzoom) && (int) $displayData->map->scrollwheelzoom == 1) {
                        $displayData->map->scrollwheelzoom = 1;
                } else {
                        $displayData->map->scrollwheelzoom = 0;
                }

                // Since 1.1.0 zoomcontrol is alias for navigationcontrol
                if (empty($displayData->map->zoomcontrol)) {
                        $displayData->map->zoomcontrol = 0;
                }

                if (empty($displayData->map->scalecontrol)) {
                        $displayData->map->scalecontrol = 0;
                }

                if (empty($displayData->map->typecontrol)) {
                        $displayData->map->typecontrol = 0;
                }
                if (empty($displayData->map->typecontrolposition)) {
                        $displayData->map->typecontrolposition = 0;
                }


                if (empty($displayData->map->typeid)) {
                        $displayData->map->typeid = 0;
                }


                // Display Direction
                $displayData->t['displaydir'] = 0;
                if (isset($displayData->map->displayroute) && $displayData->map->displayroute == 1) {
                        if (isset($displayData->marker) && !empty($displayData->marker)) {
                                $displayData->t['displaydir'] = 1;
                        }
                }

                // KML Support
                $displayData->t['load_kml'] = FALSE;
                if ($displayData->t['enable_kml'] == 1) {
                        jimport('joomla.filesystem.folder');
                        jimport('joomla.filesystem.file');
                        $path = PhocaMapsPath::getPath();
                        if (isset($displayData->map->kmlfile) && JFile::exists($path->kml_abs . $displayData->map->kmlfile)) {
                                $displayData->t['load_kml'] = $path->kml_rel_full . $displayData->map->kmlfile;
                        }
                }
                return $displayData;
        }

}

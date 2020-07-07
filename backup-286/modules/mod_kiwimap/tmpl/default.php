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

$app = JFactory::getApplication();
if ($app->input->get('print', '', 'int') == 1 || $app->input->get('tmpl', '', 'string') == 'component') {

        //$foutput = '<div style="clear:both"></div>';
        echo '<div id="phocamaps" class="phocamaps' . $displayData->t['p']->get('pageclass_sfx') . $params->get('moduleclass_sfx') . '">';
} else {
        echo '<div id="phocamaps" class="phocamaps' . $displayData->t['p']->get('pageclass_sfx') . $params->get('moduleclass_sfx') . '">';
        if ($displayData->t['p']->def('show_page_heading', 1)) {
                echo '<h1>' . $displayData->t['p']->get('page_heading') . '</h1>';
        }
        //$foutput = PhocaMapsHelper::getInfo();
}


if ((!isset($displayData->map->longitude)) || (!isset($displayData->map->latitude)) || (isset($displayData->map->longitude) && $displayData->map->longitude == '') || (isset($displayData->map->latitude) && $displayData->map->latitude == '')) {
        echo '<p>' . JText::_('COM_PHOCAMAPS_GOOGLE_MAPS_ERROR_FRONT') . '</p>';
} else {
        if ($params->get('show_description', 0)) {
                echo $displayData->t['description'];
        }


        $id = $params->get('id', '');
        $map = new PhocaMapsMap($id);
        $map->loadAPI('jsapi', (int) $displayData->t['load_api_ssl']);
        $map->loadGeoXMLJS();
        $map->loadBase64JS();

        // Map Box
        if ($displayData->t['border'] == '') {
                echo '<div class="phocamaps-box" align="center" style="' . $displayData->t['stylesite'] . '">';
                if ($displayData->t['fullwidth'] == 1) {
                        echo '<div id="phocaMap' . $id . '" style="margin:0;padding:0;width:100%;height:' . $displayData->map->height . 'px"></div>';
                } else {
                        echo '<div id="phocaMap' . $id . '" style="margin:0;padding:0;width:' . $displayData->map->width . 'px;height:' . $displayData->map->height . 'px"></div>';
                }
                echo '</div>';
        } else {
                echo '<div id="phocamaps-box"><div class="pmbox' . $displayData->t['border'] . '" ' . $displayData->t['stylesitewidth'] . '><div><div><div>';
                if ($displayData->t['fullwidth'] == 1) {
                        echo '<div id="phocaMap' . $id . '" style="width:100%;height:' . $displayData->map->height . 'px"></div>';
                } else {
                        echo '<div id="phocaMap' . $id . '" style="width:' . $displayData->map->width . 'px;height:' . $displayData->map->height . 'px"></div>';
                }
                echo '</div></div></div></div></div>';
        }

        // Direction
        if ($displayData->t['displaydir']) {

                $countMarker = count($displayData->marker);
                $form = '';
                if ((int) $countMarker > 1) {

                        $form .= ' ' . JText::_('COM_PHOCAMAPS_TO') . ': <select name="pmto' . $id . '" id="toPMAddress' . $id . '">';
                        foreach ($displayData->marker as $key => $markerV) {
                                if ((isset($markerV->longitude) && $markerV->longitude != '') && (isset($markerV->latitude) && $markerV->latitude != '')) {
                                        $form .= '<option value="' . $markerV->latitude . ',' . $markerV->longitude . '">' . $markerV->title . '</option>';
                                }
                        }
                        $form .= '</select>';
                } else if ((int) $countMarker == 1) {

                        foreach ($displayData->marker as $key => $markerV) {
                                if ((isset($markerV->longitude) && $markerV->longitude != '') && (isset($markerV->latitude) && $markerV->latitude != '')) {
                                        $form .= '<input name="pmto' . $id . '" id="toPMAddress' . $id . '" type="hidden" value="' . $markerV->latitude . ',' . $markerV->longitude . '" />';
                                }
                        }
                }

                if ($form != '') {
                        echo '<div class="pmroute">';
                        echo '<form class="form-inline" action="#" onsubmit="setPhocaDir' . $id . '(this.pmfrom' . $id . '.value, this.pmto' . $id . '.value); return false;">';
                        echo JText::_('COM_PHOCAMAPS_FROM_ADDRESS') . ': <input class="pm-input-route input" type="text" size="30" id="fromPMAddress' . $id . '" name="pmfrom' . $id . '" value=""/>';
                        echo $form;
                        echo ' <input name="pmsubmit' . $id . '" type="submit" class="pm-input-route-btn btn" value="' . JText::_('COM_PHOCAMAPS_GET_ROUTE') . '" />';
                        echo '</form></div>';
                        echo '<div id="phocaDir' . $id . '">';
                        if ($displayData->t['display_print_route'] == 1) {
                                echo '<div id="phocaMapsPrintIcon' . $id . '" style="display:none"></div>';
                        }
                        echo '</div>';
                }
        }

        // $id is not used anymore as this is added in methods of Phoca Maps Class
        // e.g. 'phocaMap' will be not 'phocaMap'.$id as the id will be set in methods

        echo $map->startJScData();
        echo $map->addAjaxAPI('maps', '3', $displayData->t['params']);
        echo $map->addAjaxAPI('search', '1', $displayData->t['paramssearch']);

        echo $map->createMap('phocaMap', 'mapPhocaMap', 'phocaLatLng', 'phocaOptions', 'tstPhocaMap', 'tstIntPhocaMap', FALSE, FALSE, $displayData->t['displaydir']);
        echo $map->cancelEventFunction();
        echo $map->checkMapFunction();
        echo $map->startMapFunction();

        echo $map->setLatLng($displayData->map->latitude, $displayData->map->longitude);

        echo $map->startMapOptions();
        echo $map->setMapOption('zoom', $displayData->map->zoom) . ',' . "\n";
        echo $map->setCenterOpt() . ',' . "\n";
        echo $map->setTypeControlOpt($displayData->map->typecontrol, $displayData->map->typecontrolposition) . ',' . "\n";
        echo $map->setNavigationControlOpt($displayData->map->zoomcontrol) . ',' . "\n";
        echo $map->setMapOption('scaleControl', $displayData->map->scalecontrol, TRUE) . ',' . "\n";
        echo $map->setMapOption('scrollwheel', $displayData->map->scrollwheelzoom) . ',' . "\n";
        echo $map->setMapOption('disableDoubleClickZoom', $displayData->map->disabledoubleclickzoom) . ',' . "\n";
        //	echo $map->setMapOption('googleBar', $displayData->map->googlebar).','."\n";// Not ready yet
        //	echo $map->setMapOption('continuousZoom', $displayData->map->continuouszoom).','."\n";// Not ready yet
        echo $map->setMapTypeOpt($displayData->map->typeid) . "\n";
        echo $map->endMapOptions();
        if ($displayData->t['close_opened_window'] == 1) {
                echo $map->setCloseOpenedWindow();
        }
        echo $map->setMap();

        // Markers
        jimport('joomla.filter.output');
        if (isset($displayData->marker) && !empty($displayData->marker)) {

                $iconArray = array(); // add information about created icons to array and check it so no duplicity icons js code will be created
                foreach ($displayData->marker as $key => $markerV) {

                        if ((isset($markerV->longitude) && $markerV->longitude != '') && (isset($markerV->latitude) && $markerV->latitude != '')) {

                                $hStyle = 'font-size:120%;margin: 5px 0px;font-weight:bold;';
                                $text = '<div style="' . $hStyle . '">' . addslashes($markerV->title) . '</div>';

                                // Try to correct images in description
                                $markerV->description = PhocaMapsHelper::fixImagePath($markerV->description);
                                $markerV->description = str_replace('src="/images', 'src="' . JURI::root(true) . '/images', $markerV->description);
                                $markerV->description = str_replace('src="images', 'src="' . JURI::root(true) . '/images', $markerV->description);
                                $markerV->description = str_replace('@', '&#64;', $markerV->description);
                                $text .= '<div>' . PhocaMapsHelper::strTrimAll(addslashes($markerV->description)) . '</div>';
                                if ($markerV->displaygps == 1) {
                                        $text .= '<div class="pmgps"><table border="0"><tr><td><strong>' . JText::_('COM_PHOCAMAPS_GPS') . ': </strong></td>'
                                                . '<td>' . PhocaMapsHelper::strTrimAll(addslashes($markerV->gpslatitude)) . '</td></tr>'
                                                . '<tr><td></td>'
                                                . '<td>' . PhocaMapsHelper::strTrimAll(addslashes($markerV->gpslongitude)) . '</td></tr></table></div>';
                                }


                                if (empty($markerV->icon)) {
                                        $markerV->icon = 0;
                                }
                                if (empty($markerV->title)) {
                                        $markerV->title = '';
                                }
                                if (empty($markerV->description)) {
                                        $markerV->description = '';
                                }

                                $iconOutput = $map->setMarkerIcon($markerV->icon, $markerV->iconext, $markerV->iurl, $markerV->iobject, $markerV->iurls, $markerV->iobjects, $markerV->iobjectshape);
                                echo $map->outputMarkerJs($iconOutput['js'], $markerV->icon, $markerV->iconext);

                                echo $map->setMarker($markerV->id, $markerV->title, $markerV->description, $markerV->latitude, $markerV->longitude, $iconOutput['icon'], $iconOutput['iconid'], $text, $markerV->contentwidth, $markerV->contentheight, $markerV->markerwindow, $iconOutput['iconshadow'], $iconOutput['iconshape'], $displayData->t['close_opened_window']);
                        }
                }
        }

        if ($displayData->t['load_kml']) {
                echo $map->setKMLFile($displayData->t['load_kml']);
        }

        if ($displayData->t['displaydir']) {
                echo $map->setDirectionDisplayService('phocaDir');
        }
        echo $map->setListener();
        echo $map->endMapFunction();

        if ($displayData->t['displaydir']) {

                echo $map->setDirectionFunction($displayData->t['display_print_route'], $displayData->map->id, $displayData->map->alias, $displayData->t['lang']);
        }

        echo $map->setInitializeFunction();
        echo $map->endJScData();
}

//echo $foutput;
echo '</div>';
?>

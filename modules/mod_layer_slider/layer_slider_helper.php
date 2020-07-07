<?php
/*-------------------------------------------------------------------------
# mod_layer_slider - Layer Slider
# -------------------------------------------------------------------------
# @ author    John Gera, George Krupa, Janos Biro
# @ copyright Copyright (C) 2014 Offlajn.com  All Rights Reserved.
# @ license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @ website   http://www.offlajn.com
-------------------------------------------------------------------------*/
?><?php
defined('_JEXEC') or die;

if(!defined('NL')) { define("NL", "\r\n"); }
if(!defined('TAB')) { define("TAB", "\t"); }


function get_option( $option, $default = false){
  // Get a db connection.
  $db = JFactory::getDbo();
  // Create a new query object.
  $query = $db->getQuery(true);
   
  // Select all records from the user profile table where key begins with "custom.".
  // Order it by the ordering field.
  $query->select("option_value");
  $query->from($db->quoteName('#__layerslider_options'));
  $query->where($db->quoteName('option_name')."='".$option."'");
  // Reset the query using our newly populated query object.
  $db->setQuery($query);
   
  // Load the results as a list of stdClass objects (see later for more options on retrieving data).
  $settings = $db->loadRow();
  
  if($settings)
    $default = json_decode($settings[0],true);
    
  return $default;
}

function ls_load_google_fonts() {

	// Get font list
	$fonts = get_option('ls-google-fonts', array());

	// Check fonts if any
	if(!empty($fonts) && is_array($fonts)) {
		$lsFonts = array();
		foreach($fonts as $item) {
			if(!$item['admin']) {
				$lsFonts[] = $item['param'];
			}
		}
		$lsFonts = implode('|', $lsFonts);
		$query_args = array(
			'family' => $lsFonts,
			'subset' => 'latin,latin-ext',
		);

    $doc = JFactory::getDocument();
    $doc->addCustomTag( '<link id="ls-google-fonts-css" media="all" type="text/css" href="https://fonts.googleapis.com/css?family='.$query_args['family'].'&subset='.$query_args['subset'].'" rel="stylesheet">' );

	}
}  

ls_load_google_fonts();

function __($a, $b){
  return $a;
}

function has_filter() {
	return false;
}

function wp_upload_dir() {
	return array("basedir"=>JPATH_SITE."/images","baseurl"=>JURI::root()."images");
}

/********************************************************/
/*                        MISC                          */
/********************************************************/

function layerslider_check_unit($str) {

	if(strstr($str, 'px') == false && strstr($str, '%') == false) {
		return $str.'px';
	} else {
		return $str;
	}
}

function layerslider_convert_urls($arr) {

	// Layer BG
	if(strpos($arr['properties']['background'], 'http://') !== false) {
		$arr['properties']['background'] = parse_url($arr['properties']['background'], PHP_URL_PATH);
	}

	// Layer Thumb
	if(strpos($arr['properties']['thumbnail'], 'http://') !== false) {
		$arr['properties']['thumbnail'] = parse_url($arr['properties']['thumbnail'], PHP_URL_PATH);
	}

	// Image sublayers
	foreach($arr['sublayers'] as $sublayerkey => $sublayer) {

		if($sublayer['type'] == 'img') {
			if(strpos($sublayer['image'], 'http://') !== false) {
				$arr['sublayers'][$sublayerkey]['image'] = parse_url($sublayer['image'], PHP_URL_PATH);
			}
		}
	}

	return $arr;
}
?>
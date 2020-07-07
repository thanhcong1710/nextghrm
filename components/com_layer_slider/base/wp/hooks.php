<?php
/*-------------------------------------------------------------------------
# com_layer_slider - com_layer_slider
# -------------------------------------------------------------------------
# @ author    John Gera, George Krupa, Janos Biro
# @ copyright Copyright (C) 2014 Offlajn.com  All Rights Reserved.
# @ license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @ website   http://www.offlajn.com
-------------------------------------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
?><?php

// Joomla functions

function apply_filters($func,$param1,$param2 = false){

  switch ($func) {
    case "ls_slider_title" :
      return ls_filter_slider_title($param1,$param2);
      break;
    case "ls_get_preview_for_slider" :
      return ls_filter_get_perview_for_slider($param1);
      break;
    case "ls_get_thumbnail" :
      return ls_get_thumbnail($param1,$param2);
      break;
    case "ls_get_image" :
      return ls_get_image($param1);
      break;
    case "ls_parse_defaults" :
      return ls_parse_defaults($param1,$param2);
      break;
    default:
	
	   break;
    }
}


function ls_filter_slider_title($sliderName = '', $maxLength = 50) {
	$name = empty($sliderName) ? 'Unnamed' : stripslashes($sliderName);
	return isset($name[$maxLength]) ? substr($name, 0, $maxLength) . ' ...' : $name;
}

function ls_filter_get_perview_for_slider( $sliderObj = array() ) {

	// Find an image
	if(isset($sliderObj['data']['layers'])) {
		foreach($sliderObj['data']['layers'] as $layer) {
			if(!empty($layer['properties']['background'])) {
				$image = $layer['properties']['background'];
				break;
			}
		}
	}

	if(isset($image)) {
		return $image;
	} else {
		return LS_ROOT_URL.'/static/img/slider_preview.jpg';
	}
}


function ls_get_thumbnail($id = null, $url = null) {

	// Image ID
	if(!empty($id)) {
		if($image = wp_get_attachment_thumb_url($id, 'thumbnail')) {
			return $image;
		}
	} 

	if(!empty($url)) {

		$thumb = substr_replace($url, '-150x150.', strrpos($url,'.'), 1);
		$file = LS_ROOT_PATH.'/sampleslider/'.basename($thumb);

		if(file_exists($file)) { return $thumb; } else { return $url; }
	}

	return LS_ROOT_URL.'/static/img/not_set.png';
}

function ls_get_image($id = null, $url = null) {

	if(!empty($id)) {
		if($image = wp_get_attachment_url($id, 'thumbnail')) {
			return $image;
		}
	}

	return $url;
}


function ls_parse_defaults($defaults = array(), $raw = array()) {

	$ret = array();
	foreach($defaults as $key => $default) {
		$phpKey = is_string($default['keys']) ? $default['keys'] : $default['keys'][0];
		$jsKey  = is_string($default['keys']) ? $default['keys'] : $default['keys'][1];
		$retKey = isset($default['props']['meta']) ? 'props' : 'attrs';

		if(isset($raw[$phpKey]) && is_array($raw[$phpKey])) {
			$ret[$retKey][$jsKey] = $raw[$phpKey];
		} elseif(is_bool($default['value'])) {
			if($default['value'] == true && empty($raw[$phpKey])) {
				$ret[$retKey][$jsKey] = false;
			} elseif($default['value'] == false && !empty($raw[$phpKey])) {
				$ret[$retKey][$jsKey] = true;
			}
		} elseif(isset($raw[$phpKey])) {
			if(isset($default['props']['meta']) || $default['value'] != $raw[$phpKey]) {
				$ret[$retKey][$jsKey] = is_numeric($raw[$phpKey]) ? (float) $raw[$phpKey] : stripslashes($raw[$phpKey]);
			}
		}
	}

	return $ret;
}

function ls_array_to_attr($arr, $mode = '') {
	if(!empty($arr) && is_array($arr)) {
		$ret = array();
		foreach($arr as $key => $val) {
			if($mode == 'css' && is_numeric($val)) {
				$ret[] = ''.$key.':'.layerslider_check_unit($val).';';
			} elseif(is_bool($val)) {
				$bool = $val ? 'true' : 'false';
				$ret[] = "$key:$bool;";
			} else {
				$ret[] = "$key:$val;";
			}
		}
		return implode('', $ret);
	}
}

function layerslider_loaded() {
	if(has_action('layerslider_ready')) {
		do_action('layerslider_ready');
	}
}

?>

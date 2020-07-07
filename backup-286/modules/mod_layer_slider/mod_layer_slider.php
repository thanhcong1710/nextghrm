<?php
/*-------------------------------------------------------------------------
# mod_layer_slider - Layer Slider
# -------------------------------------------------------------------------
# @ author    John Gera, George Krupa, Janos Biro
# @ copyright Copyright (C) 2014 Offlajn.com  All Rights Reserved.
# @ license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @ website   http://www.offlajn.com
-------------------------------------------------------------------------*/
$revision = '5.1.1.048';
$revision = '5.1.1.048';
$revision = '5.1.1.033';
$revision = '5.1.1.033';
?><?php
defined('_JEXEC') or die;

if (!defined('LS_ROOT_PATH')) define("LS_ROOT_PATH", JPATH_SITE."/components/com_layer_slider/base/");
if (!defined('LS_ROOT_URL')) define("LS_ROOT_URL", JURI::root()."components/com_layer_slider/base/" );

$GLOBALS['j25'] = version_compare(JVERSION, '3.0.0', 'l');

$root = JURI::root(true);
if ($root != '/') $root.= '/';

require_once JPATH_SITE.'/components/com_layer_slider/base/wp/hooks.php';
require_once JPATH_SITE.'/components/com_layer_slider/base/classes/class.ls.sliders.php';
require_once dirname(__FILE__).'/layer_slider_helper.php';


$document = JFactory::getDocument();
$document->addStyleSheet($root . 'components/com_layer_slider/base/static/css/layerslider.css');
$document->addStyleSheet($root . 'components/com_layer_slider/base/static/css/layerslider.transitiongallery.css');
$document->addStyleSheet($root . 'modules/mod_layer_slider/imagelightbox.css');

if ($GLOBALS['j25']) {
  if (get_option('load_jquery', false)) {
    $document->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js');
    $document->addScript($root . 'media/offlajn/jquery.noconflict.js');
  }
}
else JHtml::_('jquery.framework');

$document->addScript($root . 'components/com_layer_slider/base/static/js/layerslider.kreaturamedia.js');
$document->addScript('https://cdnjs.cloudflare.com/ajax/libs/gsap/1.11.2/TweenMax.min.js');
$document->addScript($root . 'components/com_layer_slider/base/static/js/layerslider.transitions.js');
$document->addScript($root . 'modules/mod_layer_slider/imagelightbox.js');

// User resources
$uploads = wp_upload_dir();
if(file_exists($uploads['basedir'].'/layerslider.custom.transitions.js')) {
  $document->addCustomTag( '<script id="ls-user-transitions" src="'.$uploads['baseurl'].'/layerslider.custom.transitions.js" type="text/javascript" ></script>' );
}

if(file_exists($uploads['basedir'].'/layerslider.custom.css')) {
  $document->addCustomTag( '<link id="ls-user-css" href="'.$uploads['baseurl'].'/layerslider.custom.css" type="text/css" rel="stylesheet" ></link>' );
}


$id = $params->get('slider',0);

if(!$slider = LS_Sliders::find($id)) {
	return '[LayerSliderWP] '.__('Slider not found', 'LayerSlider').'';
}

// Slider and markup data
$slides = $slider['data'];
$data = '';

// Include slider file
if(is_array($slides)) {

	// Get phpQuery
	if(!class_exists('phpQuery')) {
		libxml_use_internal_errors(true);
		include LS_ROOT_PATH.'/helpers/phpQuery.php';
	}

	include LS_ROOT_PATH.'/config/defaults.php';
	include LS_ROOT_PATH.'/includes/slider_markup_init.php';
	include LS_ROOT_PATH.'/includes/slider_markup_html.php';
	$data = implode('', $data);
}

echo $data;

?>


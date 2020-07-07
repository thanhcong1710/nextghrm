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

$lsPluginDefaults = array(
	
	// PATHS
	'paths' => array(
		'rootUrl' => plugins_url('', __FILE__),
		'skins' => plugins_url('', __FILE__).'/skins/',
		'transitions' => plugins_url('', __FILE__).'/demos/transitions.json'
	),

	'features' => array(
		'autoupdate' => true
	),

	// INTERFACE
	'interface' => array(

		'settings' => array(

		),

		'fonts' => array(

		),

		'news' => array(
			'display' => true,
			'collapsed' => false
		),

	),

	// Settings
	'settings' => array(
		'scriptsInFooter' => false,
		'conditionalScripts' => false,
		'concatenateOutput' => true,
		'cacheOutput' => false
	)
);

?>
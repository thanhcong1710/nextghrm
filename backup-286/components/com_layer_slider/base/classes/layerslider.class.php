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

// Get class files
include LS_ROOT_PATH.'/classes/class.ls.posts.php';
include LS_ROOT_PATH.'/classes/class.ls.sliders.php';

class LayerSlider {

	public $sources, $sliders, $posts, $autoupdate;

	function __construct() {

		// Get instances
		$this->posts   = new LS_Posts();

		// Do other stuff later
	}
}

?>

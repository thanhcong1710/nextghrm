<?php
/*-------------------------------------------------------------------------
# plg_load_layer_slider - Layer slider content plugin
# -------------------------------------------------------------------------
# @ author    Janos Biro
# @ copyright Copyright (C) 2014 Offlajn.com  All Rights Reserved.
# @ license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @ website   http://www.offlajn.com
-------------------------------------------------------------------------*/
?><?php
defined('_JEXEC') or die;

class PlgContentLoadLayerSlider extends JPlugin
{
	protected static $modules = array();

	protected static $mods = array();

	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		// Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer')
		{
			return true;
		}

		// Simple performance check to determine whether bot should process further
		if (strpos($article->text, 'layerslider') === false && strpos($article->text, 'ls-navigate') === false )
		{
			return true;
		}

		// Expression to search for(modules)
		$regexmod	= '/{layerslider\s+id="?(.*?)"?}/i';
		$stylemod	= $this->params->def('style', 'none');

		// Find all instances of plugin and put in $matchesmod for loadmodule
		preg_match_all($regexmod, $article->text, $matchesmod, PREG_SET_ORDER);

		if ($matchesmod){
  		foreach ($matchesmod as $matchmod){
  		  $id=$matchmod[1];
        jimport( 'joomla.application.module.helper' );
        $module = JModuleHelper::getModule( 'mod_layer_slider', 'Layer Slider'.rand() );
        $attribs['style'] = 'xhtml';
        $module->params= "slider=".$id;
  		  $output = JModuleHelper::renderModule( $module, $attribs );
  		  $article->text = preg_replace($regexmod, addcslashes($output, '\\$'), $article->text, 1);
  		}
    }

		// Expression to search for(modules)
		$regexmod	= '/{ls-navigate\s+id="(.*?)"\s+action="?(.*?)"?}(.*?){\/ls-navigate}/i';
		$stylemod	= $this->params->def('style', 'none');


		// Find all instances of plugin and put in $matchesmod for loadmodule
		preg_match_all($regexmod, $article->text, $matchesmod, PREG_SET_ORDER);

		if ($matchesmod){
  		foreach ($matchesmod as $matchmod){
  		  $id=$matchmod[1];
  		  $action=$matchmod[2];
        if (!is_numeric($action)) $action = "'$action'";
  		  $name=$matchmod[3];
  		  $output= '<a href="javascript:void()" onclick="jQuery(\'#layerslider_'.$id.'\').layerSlider('.$action.')">'.$name.'</a>';
  		  $article->text = preg_replace($regexmod, addcslashes($output, '\\$'), $article->text, 1);
  		}
    }
	}

	protected function _loadmod($module, $title, $style = 'none')
	{
		self::$mods[$module] = '';
		$document	= JFactory::getDocument();
		$renderer	= $document->loadRenderer('module');
		$mod		= JModuleHelper::getModule($module, $title);

		// If the module without the mod_ isn't found, try it with mod_.
		// This allows people to enter it either way in the content
		if (!isset($mod))
		{
			$name = 'mod_'.$module;
			$mod  = JModuleHelper::getModule($name, $title);
		}

		$params = array('style' => $style);
		ob_start();

		echo $renderer->render($mod, $params);

		self::$mods[$module] = ob_get_clean();

		return self::$mods[$module];
	}
}

<?php
/*-------------------------------------------------------------------------
# plg_layer_slider - Layer Slider editor extend
# -------------------------------------------------------------------------
# @ author    Janos Biro
# @ copyright Copyright (C) 2014 Offlajn.com  All Rights Reserved.
# @ license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @ website   http://www.offlajn.com
-------------------------------------------------------------------------*/
?><?php
defined('_JEXEC') or die;

class PlgButtonLayer_Slider extends JPlugin
{
	protected $autoloadLanguage = true;

	public function onDisplay($name)
	{
		$js = "
		function jSelectLayerSlider(id)
		{
			var tag = '{layerslider id=\"'+id+'\"}';
			jInsertEditorText(tag, '".$name."');
			SqueezeBox.close();
		}";

		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($js);

		JHtml::_('behavior.modal');

		$link = 'index.php?option=com_layer_slider&view=sliderlist&tmpl=component';

		$button = new JObject;
		$button->modal = true;
		$button->class = 'btn';
		$button->link = $link;
		$button->text = "Insert LayerSlider";
		$button->name = 'article icon-pictures';
		$button->options = "{handler: 'iframe', size: {x: 800, y: 500}}";

		return $button;
	}
}

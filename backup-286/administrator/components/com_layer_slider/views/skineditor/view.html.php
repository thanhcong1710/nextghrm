<?php
/*-------------------------------------------------------------------------
# com_layer_slider - com_layer_slider
# -------------------------------------------------------------------------
# @ author    John Gera, George Krupa, Janos Biro
# @ copyright Copyright (C) 2014 Offlajn.com  All Rights Reserved.
# @ license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @ website   http://www.offlajn.com
-------------------------------------------------------------------------*/
?><?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Layer_slider.
 */
class Layer_sliderViewSkinEditor extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
    require_once JPATH_COMPONENT.'/helpers/layer_slider.php';
    require_once LS_ROOT_PATH.'wp/compatibility.php';
    
    $document = JFactory::getDocument();
    $document->addStyleSheet(JURI::root() . '/components/com_layer_slider/base/static/css/skin.editor.css');
    
    ls_screen::addHelp();

    require_once LS_ROOT_PATH.'views/skin_editor.php';
	  JToolBarHelper::title('Layer Slider');
		parent::display($tpl);
	}
    
	protected function getSortFields()
	{
		return array(
		);
	}

    
}

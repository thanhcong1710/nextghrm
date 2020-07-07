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
class Layer_sliderViewSliders extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		JToolBarHelper::title('Layer Slider');
		
    ob_start();
    require_once LS_ROOT_PATH.'views/slider_list.php';
    $page = ob_get_contents();
    ob_end_clean();

    
    $pattern = '?page=layerslider&action=edit&id=';
    $replacement = '?option=com_layer_slider&view=slider&id=';
    $page = str_replace($pattern, $replacement, $page);
    
    $pattern = '?page=layerslider&action=remove&id=';
    $replacement = '?option=com_layer_slider&task=manage_sliders&action=remove&id=';
    $page = str_replace($pattern, $replacement, $page);

    $pattern = '?page=layerslider&action=import_sample&slider';
    $replacement = '?option=com_layer_slider&view=sliders&task=import_sample&slider';
    $page = str_replace($pattern, $replacement, $page);

    ls_screen::addHelp();
    
    if(!$GLOBALS['j25']) { 
      echo '<img alt="Offlajn & Kreatura Media" src="components/com_layer_slider/assets/images/common-logo.png" style="position: absolute; top: 34px; right: 21px;">';
    }
    echo $page;
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors));
		}
        
		Layer_sliderHelper::addSubmenu('sliders');
        
		$this->addToolbar();
        
//        $this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helpers/layer_slider.php';

		$state	= $this->get('State');
		$canDo	= Layer_sliderHelper::getActions($state->get('filter.category_id'));

	}
    
	protected function getSortFields()
	{
		return array(
		);
	}

    
}

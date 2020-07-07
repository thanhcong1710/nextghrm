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
 * View to edit
 */
class Layer_sliderViewSlider extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;
	public $slname;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
    JToolBarHelper::title('Layer Slider');
    ls_screen::addHelp();

    if(!$GLOBALS['j25']) { 
      echo '<img alt="Offlajn & Kreatura Media" src="components/com_layer_slider/assets/images/common-logo.png" style="position: absolute; top: 34px; right: 21px;">';
    }
    require_once LS_ROOT_PATH.'wp/layerslider.php';
    require_once LS_ROOT_PATH.'wp/compatibility.php';
    require_once LS_ROOT_PATH.'views/slider_edit.php';
    $this->slname = $slider['properties']['title'];
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= true;
        if (isset($this->item->checked_out)) {
		    $checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
        } else {
            $checkedOut = false;
        }
		$canDo		= Layer_sliderHelper::getActions();

	}
}

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
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Layer_slider model.
 */
class Layer_sliderModelSlider extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_LAYER_SLIDER';


	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		return $form;
	}

  public function addNewSlider($title){
    $id = LS_Sliders::add($title);
  	header('Location: index.php?option=com_layer_slider&view=slider&id='.$id.'&showsettings=1');
  	die();
  }
  
  public function saveSlider(){

  	// DB stuff
    $db = JFactory::getDbo();
    $user = JFactory::getUser();
  
  	// Get slider ID if any
  	$id = !empty($_POST['slider_id']) ? (int) $_POST['slider_id'] : '';
  
  	// Get slider data unless when we need to reset
  	
  	if($_POST['layerkey'] == 0 && !empty($id)) {
  		$data = array();
  	} else { 	
  		$slider = LS_Sliders::find( $id );
  		$id = $slider['id'];
  		$data = $slider['data'];
  	}

    if(!get_magic_quotes_gpc()){
      foreach($_POST['layerslider-slides']['layers'][$_POST['layerkey']]['sublayers'] as &$sublayer){
          $sublayer['transition'] = addslashes($sublayer['transition']);
          $sublayer['styles'] = addslashes($sublayer['styles']);
      }
    }

    if (isset($_POST['layerslider-slides']) && isset($_POST['layerslider-slides']['layers'])) {
      foreach ($_POST['layerslider-slides']['layers'] as &$lyr) {
        if (isset($lyr['properties']) && isset($lyr['properties']['background'])) {
          $lyr['properties']['background'] = preg_replace('/^http(s?)\/\//', 'http$1://', $lyr['properties']['background']);
        }
      }
    }

  	// Add modifications
  	if(isset($_POST['layerslider-slides']['properties']['relativeurls'])) {
  		$data['properties'] = $_POST['layerslider-slides']['properties'];
  		$data['layers'][ $_POST['layerkey'] ] = layerslider_convert_urls($_POST['layerslider-slides']['layers'][$_POST['layerkey']]);
  	} else {
  		$data['properties'] = $_POST['layerslider-slides']['properties'];
  		$data['layers'][ $_POST['layerkey'] ] = $_POST['layerslider-slides']['layers'][$_POST['layerkey']];
  	}
  
  	// Save slider
    $query = $db->getQuery(true);
    
    // Create and populate an object.
    $datas = new stdClass();
    $datas->id = $id;
    $datas->author = $user->id;
    $datas->name = $db->escape($data['properties']['title']);
    $datas->slug = !empty($data['properties']['slug']) ? $db->escape($data['properties']['slug']) : '';
    $datas->data = json_encode($data);
    $datas->date_m =time();
    
    // Insert the object into the user profile table.
    $result = JFactory::getDbo()->updateObject('#__layerslider', $datas, 'id');      		
  
  	die(json_encode(array('status' => 'ok')));
  }

  public function duplicateSlider($id){
    
    $sliders = LS_Sliders::find($id);
    $slider = $sliders['data'];
    
  	// Name check
  	if(empty($slider['properties']['title'])) {
  		$slider['properties']['title'] = 'Unnamed';
  	}
  
  	// Insert the duplicate
  	$slider['properties']['title'] .= ' copy';

		// Save as new
		$name = $slider['properties']['title'];
		LS_Sliders::add($name, $slider);
	
  	// Reload page
  	header('Location: index.php?option=com_layer_slider');
  	die();
  }
	
	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since	1.6
	 */
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');

		if (empty($table->id)) {

			// Set ordering to the last item if not set
			if (@$table->ordering === '') {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM slider');
				$max = $db->loadResult();
				$table->ordering = $max+1;
			}

		}
	}

}
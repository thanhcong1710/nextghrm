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
class Layer_sliderModelTransitions extends JModelAdmin
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
  
  public function saveTransitions($jinput) {
    
  	// Array to hold transitions
  	$transitions = array();
  
  	// Get transitions
  	$transitions['t2d'] = $jinput->get("t2d",array(),"ARRAY");
  	$transitions['t3d'] = $jinput->get("t3d",array(),"ARRAY");
  
  	array_walk_recursive($transitions['t2d'], 'layerslider_builder_convert_numbers');
  	array_walk_recursive($transitions['t3d'], 'layerslider_builder_convert_numbers');
  
  	// Iterate over the sections
  	foreach($transitions['t3d'] as $key => $val) {
  
  		// Rows
  		if(strstr($val['rows'], ',')) { $tmp = explode(',', $val['rows']); $tmp[0] = (int) trim($tmp[0]); $tmp[1] = (int) trim($tmp[1]); $transitions['t3d'][$key]['rows'] = $tmp; }
  			else { $transitions['t3d'][$key]['rows'] = (int) $val['rows']; }
  
  		// Cols
  		if(strstr($val['cols'], ',')) { $tmp = explode(',', $val['cols']); $tmp[0] = (int) trim($tmp[0]); $tmp[1] = (int) trim($tmp[1]); $transitions['t3d'][$key]['cols'] = $tmp; }
  			else { $transitions['t3d'][$key]['cols'] = (int) $val['cols']; }
  
  		// Depth
  		if(isset($val['tile']['depth'])) {
  			$transitions['t3d'][$key]['tile']['depth'] = 'large'; }
  
  		// Before
  		if(!isset($val['before']['enabled'])) {
  			unset($transitions['t3d'][$key]['before']['transition']); }
  
  		// After
  		if(!isset($val['after']['enabled'])) {
  			unset($transitions['t3d'][$key]['after']['transition']); }
  	}
  
  	// Iterate over the sections
  	foreach($transitions['t2d'] as $key => $val) {
  
  		if(strstr($val['rows'], ',')) { $tmp = explode(',', $val['rows']); $tmp[0] = (int) trim($tmp[0]); $tmp[1] = (int) trim($tmp[1]); $transitions['t2d'][$key]['rows'] = $tmp; }
  			else { $transitions['t2d'][$key]['rows'] = (int) $val['rows']; }
  
  		if(strstr($val['cols'], ',')) { $tmp = explode(',', $val['cols']); $tmp[0] = (int) trim($tmp[0]); $tmp[1] = (int) trim($tmp[1]); $transitions['t2d'][$key]['cols'] = $tmp; }
  			else { $transitions['t2d'][$key]['cols'] = (int) $val['cols']; }
  
  		if(empty($val['transition']['rotateX'])) {
  			unset($transitions['t2d'][$key]['transition']['rotateX']); }
  
  		if(empty($val['transition']['rotateY'])) {
  			unset($transitions['t2d'][$key]['transition']['rotateY']); }
  
  		if(empty($val['transition']['rotate'])) {
  			unset($transitions['t2d'][$key]['transition']['rotate']); }
  
  		if(empty($val['transition']['scale']) || $val['transition']['scale'] == '1.0' || $val['transition']['scale'] == '1') {
  			unset($transitions['t2d'][$key]['transition']['scale']); }
  
  	}
  
  	// Save transitions
  	$upload_dir = wp_upload_dir();
  	$custom_trs = $upload_dir['basedir'] . '/layerslider.custom.transitions.js';
  	$data = 'var layerSliderCustomTransitions = '.json_encode($transitions).';';
  	file_put_contents($custom_trs, $data);
  	die('SUCCESS');
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
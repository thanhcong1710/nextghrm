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
class Layer_sliderModelStyles extends JModelAdmin
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

  public function saveStyles($jinput) {
  	
		// Get target file and content
  	$upload_dir = wp_upload_dir();
  	$file = $upload_dir['basedir'].'/layerslider.custom.css';
  	$content = $jinput->get("contents",0,"STRING");
  
  	// Attempt to save changes
  	if(is_writable($upload_dir['basedir'])) {
  		file_put_contents($file, $content);
  		header('Location: index.php?option=com_layer_slider&view=customstyleseditor&edited=1');
  		die();
  
  	// File isn't writable
  	} else {
  		JError::raiseWarning( 100, "It looks like your files isn't writable, so PHP couldn't make any changes (CHMOD)." );
  		header('Location: index.php?option=com_layer_slider&view=customstyleseditor');
  	}
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
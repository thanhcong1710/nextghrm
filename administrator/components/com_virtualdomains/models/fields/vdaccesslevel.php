<?php
/**
 * @version		$Id: vdaccesslevel.php 150 2012-10-29 15:59:45Z michel $
 * @package		Joomla.Framework
 * @subpackage	Form
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */
class JFormFieldVdAccessLevel extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $type = 'VdAccessLevel';

	private $_exclude = null;
	
	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		// Initialize variables.
		$attr = '';
		
		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$attr .= $this->multiple ? ' multiple="multiple"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';
		
		$this->_exclude = ($this->element['selfexclude']  == 'true') ?  $this->form->getValue('id') : '';
		
		// Get the field options.
		$options = $this->getOptions();
		
		if(!is_array($options)) $options = array();
        array_unshift($options, JHtml::_('select.option', '', JText::_('JOPTION_VDACCESS_NONE')));
		//return JHtml::_('access.level', $this->name, $this->value, $attr, $options, $this->id);
		return JHtml::_('select.genericlist', $options, $this->name,
			array(
				'list.attr' => $attr,
				'list.select' => $this->value,
				'id' => $this->id
			));
	}
	
	protected function getOptions() {
		
		$options = array();
		
		$db		= JFactory::getDbo();
		
		$query = "SELECT GROUP_CONCAT( DISTINCT `viewlevel`
						SEPARATOR ', ' )
						FROM #__virtualdomain
						WHERE published =1
						AND id != ".(int) $this->_exclude." 
						GROUP BY published";
		$db->setQuery($query );

		
		$vdlevels = $db->loadResult();

		if($vdlevels) { 
			$query	= $db->getQuery(true);

			$query->select('a.id AS value, a.title AS text');
			$query->from('#__viewlevels AS a');
			$query->group('a.id');
			$query->order('a.ordering ASC');
			$query->order('`title` ASC');
			$query->where('id IN ('.$vdlevels.')');
			// Get the options.
			$db->setQuery($query);
			$options = $db->loadObjectList();
		}
		return $options;

		
	}
	
}

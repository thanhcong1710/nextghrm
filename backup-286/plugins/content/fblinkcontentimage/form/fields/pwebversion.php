<?php
/**
* @version 2.0.12
* @package PWebFBLinkArticleImages
* @copyright © 2014 Perfect Web sp. z o.o., All rights reserved. http://www.perfect-web.co
* @license GNU General Public Licence http://www.gnu.org/licenses/gpl-3.0.html
* @author Piotr Moćko
*/

defined('_JEXEC') or die( 'Restricted access' );

class JFormFieldPwebVersion extends JFormField
{
	protected $type = 'PwebVersion';
	
	protected function getInput()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('manifest_cache')
			->from('#__extensions')
			->where(array(
				'type = '.$db->quote($this->element['ext_type']),
				'element = '.$db->quote($this->element['ext_element']),
				'folder = '.$db->quote($this->element['ext_folder']),
				'client_id = '.$db->quote($this->element['ext_client'])
			));
		$db->setQuery($query);
		try {
			$manifest = $db->loadResult();
		} catch (RuntimeException $e) {
			$manifest = null;
		}
		
		$version = 'unknow';
		if ($manifest) {
			$manifest = new JRegistry($manifest);
			$version = $manifest->get('version');
		}
		
		return '<div style="padding-top:5px"><strong id="pweb-version" class="badge badge-inverse">'.$version.'</strong></div>';
	}
}
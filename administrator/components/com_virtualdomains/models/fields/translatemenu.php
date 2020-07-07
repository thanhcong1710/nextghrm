<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* @date		$Date: 2013-09-01 13:40:01 +0200 (So, 01. Sep 2013) $
* @revision    $Rev: 158 $
* @id 			$Id: translatemenu.php 158 2013-09-01 11:40:01Z michel $
* @version		$Id: translatemenu.php 158 2013-09-01 11:40:01Z michel $
* @package		Virtualdomain
* @subpackage 	Models
* @author     	Michael Liebler {@link http://www.janguo.de}
* @copyright	Copyright (C) 2008 - 2010 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Virtualdomains is free software. This version may have been modified pursuant to the
* GNU General Public License, and as distributed it includes or is derivative
* of works licensed under the GNU General Public License or other free or open
* source software licenses. See COPYRIGHT.php for copyright notices and
* details.
*/
 

class JFormFieldTranslateMenu extends JFormField
{
    /**
     * Element name
     *
     * @access	protected
     * @var		string
     */
    public $type = 'TranslateMenu';

    protected function getInput()
    {
		$lang = JFactory::getLanguage();
		$langs = $lang->getKnownLanguages();
        $class = $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$html ="<table class='table table-striped'>";

					
		foreach($langs  as $lang) {
			   $value = (isset($this->value[$lang['tag']])) ? $this->value[$lang['tag']] : ''; 
			   $html .= '<tr><td class="paramlist_key" width="40%">'.$lang['tag'].'</td>';
			   $html .= '<td class="paramlist_key" width="40%"><input type="text" name="jform[params][translatemenu]['.$lang['tag'].']" id="jform_'.$lang['tag'].'" value="'.$value.'" class="'.$class.'" size="20"/></td></tr>';	
		}
		
        $html .="</table>";
        return $html;
    }

} ?>

<?php 
/**
 * @Copyright Copyright (C) 2015 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:     Buruj Solutions
  + Contact:    www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:  May 22, 2015
  ^
   + Project:    JS Tickets
  ^
 */

defined('_JEXEC') or die('Restricted access');
	$commonpath="index.php?option=com_jssupportticket";
	$pathway = $mainframe->getPathway();

	if ($config['cur_location'] == 1) {
		switch($layoutName){
			case 'controlpanel':
				$pathway->addItem(JText::_('Control panel'), $commonpath.'&c=jssupportticket&layout=controlpanel');
			break;
		}
	}	

?>


<?php
/**
 * @Copyright Copyright (C) 2015 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	May 22, 2015
  ^
  + Project: 	JS Tickets
  ^
 */

defined('_JEXEC') or die('Restricted access');
class messagesLayout{
	public static function getRecordNotFound(){
		$layout="<div id='js-messagelayout-wrapper'>
					<div id='js-imgfor-message'>
						<img src='".JURI::root()."components/com_jssupportticket/include/images/layout_messages/norecordfound.png'>
					</div>
					<div id='js-datafor-message'>
						<div class='js-message-title'>
							".JText::_('Oooops')." !
						</div>
						<div class='js-message-detail'>
							".JText::_('Record not found')."
						</div>
					</div>
				</div>";
		echo $layout;
	}
	public static function getSystemOffline($title, $message){
		$layout="<div id='js-messagelayout-wrapper'>
					<div id='js-imgfor-message'>
						<img src='".JURI::root()."components/com_jssupportticket/include/images/layout_messages/offline.png'>
					</div>
					<div id='js-datafor-message'>
						<div class='js-message-title'>
							".$title."
						</div>
						<div class='js-message-detail'>
							".$message."
						</div>
					</div>
				</div>";
		echo $layout;
	}
	public static function getUserNotLogin(){
		$layout="<div id='js-messagelayout-wrapper'>
					<div id='js-imgfor-message'>
						<img src='".JURI::root()."components/com_jssupportticket/include/images/layout_messages/notlogin.png'>
					</div>
					<div id='js-datafor-message'>
						<div class='js-message-title'>
							".JText::_('Oooops')." !
						</div>
						<div class='js-message-detail'>
							".JText::_('You are not Logged in')."
						</div>
						<div class='js-message-button'>
							<a href='#'>".JText::_('Login')."</a>
						</div>
					</div>
				</div>";
		echo $layout;
	}
}
?>
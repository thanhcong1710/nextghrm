<?php

/**
 * @package pkg_nextgcyber
 * @subpackage  com_nextgcyber
 * @copyright Copyright (C) 2015 NextgCyber . All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://nextgerp.com
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;
JLoader::register('NextgCyberCustomerHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/customerhelper.php');
JLoader::register('NextgCyberNumberHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/numberhelper.php');
JLoader::register('NextgCyberMenuHelper', JPATH_COMPONENT . '/helpers/menuhelper.php');
JLoader::register('NextgCyberViewItem', JPATH_COMPONENT . '/views/itemview.php');

class NextgCyberViewOrder extends NextgCyberViewItem {

}

<?php

/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://nextgcyber.com
 * @author Daniel.Vu
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla view library

/**
 * HTML View class for the NextgCyber Component
 */
class NextgCyberViewNextgCyber extends NextgCyberViewMain {

    // Overwriting JView display method
    function display($tpl = null) {
        JToolBarHelper::title(JText::_('COM_NEXTGCYBER'));
        $this->setDocument(JText::_('COM_NEXTGCYBER'), false);
        $this->addToolBar();
        parent::display($tpl);
    }

    protected function addToolbar($type = null, $action = null, $s = null) {
        if (JFactory::getUser()->authorise('core.admin', 'com_nextgcyber')) {
            JToolBarHelper::divider();
            JToolBarHelper::preferences('com_nextgcyber', 400, 600);
        }
    }

}

?>

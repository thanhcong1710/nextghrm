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

class NextgCyberViewInvoice extends NextgCyberViewMain {

    /**
     * Display Page
     * @return void
     * @since 1.0.0
     */
    public function display($tpl = null) {
        // get the Data
        $form = $this->get('Form');
        $item = $this->get('Item');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        // Assign the Data
        $this->form = $form;
        $this->item = $item;
        $this->state = $this->get('State');
        $this->canDo = NextgCyberHelper::getActions('invoice', $this->item->id);

        // Set the toolbar
        $this->addToolBar('invoice');
        // Display the template
        parent::display($tpl);
    }

}

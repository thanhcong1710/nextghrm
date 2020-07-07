<?php

/**
 * Description of view
 *
 * @author Daniel.Vu
 */
jimport('joomla.application.component.view');
jimport('joomla.html.pane');

//JHTML::_('behavior.modal');
class NextgCyberViewMain extends JViewLegacy {

    function __construct($config = array()) {
        JHtml::_('jquery.framework');
        JHtml::_('bootstrap.framework');
        $version = NextgCyberHelper::getVersion();
        $document = JFactory::getApplication()->getDocument();
        $base = JUri::root(true);
        $document->addScript($base . '/media/com_nextgcyber/js/site/jquery.circle-diagram.js?v=' . $version);
        $document->addStyleSheet($base . '/media/com_nextgcyber/css/main.css?v=' . $version);
        return parent::__construct($config);
    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument($string, $script = true) {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_($string));
    }

    /**
     * @param string $section access control section
     * @param boolean $islist : true for list view or false for single view
     */
    protected function addToolbar($section, $islist = false) {
        if ($islist) {
            $this->addToolbarForListView($section);
        } else {
            $this->addToolbarForDetailView($section);
        }
    }

    /**
     * Display toolbar of item list
     * @param type $type access rule
     * @param type $action  controller
     */
    protected function addToolbarForListView($section) {
        $viewName = $this->getName();
        $canDo = NextgCyberHelper::getActions($section);
        $user = JFactory::getUser();
        JToolBarHelper::title(JText::_('COM_NEXTGCYBER_' . strtoupper($section) . '_MANAGER'), 'generic.png');

        if ($canDo->get('core.create') || (count($user->getAuthorisedCategories('com_nextgcyber', 'core.create'))) > 0) {
            JToolBarHelper::addNew($section . '.add');
        }

        if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own'))) {
            JToolBarHelper::editList($section . '.edit');
        }

        if ($canDo->get('core.edit.state')) {
            JToolBarHelper::divider();
            JToolBarHelper::publish($viewName . '.publish', 'JTOOLBAR_PUBLISH', true);
            JToolBarHelper::unpublish($viewName . '.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            JToolBarHelper::divider();
            JToolBarHelper::checkin($viewName . '.checkin');
        }

        if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete')) {
            JToolBarHelper::deleteList('', $viewName . '.delete', 'JTOOLBAR_EMPTY_TRASH');
            JToolBarHelper::divider();
        } elseif ($canDo->get('core.edit.state')) {
            JToolBarHelper::trash($viewName . '.trash');
            JToolBarHelper::divider();
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_nextgcyber');
            JToolBarHelper::divider();
        }
    }

    /**
     * Display toolbar of single item
     * @param type $type  access rule
     * @param type $action  controller
     */
    protected function addToolbarForDetailView($section) {
        $title = strtoupper($section);
        $user = JFactory::getUser();
        $userId = $user->get('id');
        $isNew = ($this->item->id == 0);
        $checkedOut = !(isset($this->item->checked_out) && $this->item->checked_out == 0 || isset($this->item->checked_out) && $this->item->checked_out == $userId || !isset($this->item->checked_out));
        $canDo = NextgCyberHelper::getActions($section, $this->item->id);
        $input = JFactory::getApplication()->input;
        $input->set('hidemainmenu', true);
        JToolBarHelper::title($isNew ? JText::_('COM_NEXTGCYBER_' . $title . '_NEW') : JText::_('COM_NEXTGCYBER_' . $title . '_EDIT'));
        // Built the actions for new and existing records.
        // For new records, check the create permission.
        if ($isNew && (count($user->getAuthorisedCategories('com_nextgcyber', 'core.create')) > 0)) {
            JToolBarHelper::apply($section . '.apply');
            JToolBarHelper::save($section . '.save');
            JToolBarHelper::cancel($section . '.cancel');
        } else {
            // Can't save the record if it's checked out.
            if (!$checkedOut) {
                // Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
                if ($canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId)) {
                    JToolBarHelper::apply($section . '.apply');
                    JToolBarHelper::save($section . '.save');
                }
            }
            JToolBarHelper::cancel($section . '.cancel', 'JTOOLBAR_CLOSE');
        }
    }

    /**
     * Display page
     * @return void
     * @since 1.0.0
     */
    public function display($tpl = null) {
        JText::script('COM_NEXTGCYBER_LOADING_TEXT');
        JText::script('COM_NEXTGCYBER_ARE_YOU_SURE_TO_DO_THIS_ACTION');
        JText::script('COM_NEXTGCYBER_INSTANCE_INPUT_PASSWD_CONFIRM_LABEL');
        JText::script('JSUBMIT');
        JText::script('JCANCEL');
        JText::script('COM_NEXTGCYBER_SHOW_MORE_BUTTON_LABEL');
        JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
        JLoader::register('JHtmlTSidebar', JPATH_COMPONENT . '/helpers/html/tsidebar.php');
        $view = JFactory::getApplication()->input->getString('view');
        NextgCyberHelper::addSubmenu($view);
        $layout = JFactory::getApplication()->input->getString('layout');
        if (!$layout) {
            $this->sidebar = JHtmlTSidebar::render();
        }

        parent::display($tpl);
    }

}

?>

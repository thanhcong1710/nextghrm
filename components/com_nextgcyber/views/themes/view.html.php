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

/**
 * Frontpage View class
 *
 * @package pkg_nextgcyber
 * @subpackage  com_nextgcyber
 * @since       1.0
 */
class NextgCyberViewThemes extends JViewLegacy {

    protected $state = null;
    protected $item = null;
    protected $items = null;
    protected $pagination = null;
    protected $columns = 1;

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise a Error object.
     */
    public function display($tpl = null) {
        $user = JFactory::getUser();
        $app = JFactory::getApplication();
        $state = $this->get('State');
        $items = $this->get('Items');
        $pagination = $this->get('Pagination');
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            $app->enqueueMessage(implode("\n", $errors), 'error');
            return false;
        }

        $params = &$state->params;

        $this->stdplans = [];
        $this->addons = [];

        // PREPARE THE DATA
        // Get the metrics for the structural page layout.
        // Compute the article slugs and prepare introtext (runs content plugins).
        foreach ($items as &$item) {
            $item->event = new stdClass;

            $dispatcher = JEventDispatcher::getInstance();

            // Old plugins: Ensure that text property is available
            if (!isset($item->text)) {
                $item->text = '';
            }
            JPluginHelper::importPlugin('content');
            $dispatcher->trigger('onContentPrepare', array('com_nextgcyber.featured', &$item, &$item->params, 0));

            // Old plugins: Use processed text as introtext
            $item->introtext = $item->text;

            $results = $dispatcher->trigger('onContentAfterTitle', array('com_nextgcyber.featured', &$item, &$item->params, 0));
            $item->event->afterDisplayTitle = trim(implode("\n", $results));

            $results = $dispatcher->trigger('onContentBeforeDisplay', array('com_nextgcyber.featured', &$item, &$item->params, 0));
            $item->event->beforeDisplayContent = trim(implode("\n", $results));

            $results = $dispatcher->trigger('onContentAfterDisplay', array('com_nextgcyber.featured', &$item, &$item->params, 0));
            $item->event->afterDisplayContent = trim(implode("\n", $results));
        }

        //Escape strings for HTML output
        $this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));
        $this->params = &$params;
        $this->items = &$items;
        $this->pagination = &$pagination;
        $this->user = &$user;
        $this->_prepareDocument();
        parent::display($tpl);
    }

    /**
     * Prepares the document
     */
    protected function _prepareDocument() {
        $app = JFactory::getApplication();
        $menus = $app->getMenu();
        $title = null;
        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();
        if ($menu) {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        } else {
            $this->params->def('page_heading', JText::_('COM_NEXTGCYBER_PLAN_TITLE'));
        }
        $title = $this->params->get('page_title', '');
        if (empty($title)) {
            $title = $app->get('sitename');
        } elseif ($app->get('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
        } elseif ($app->get('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        }

        $this->document->setTitle($title);

        if ($this->params->get('menu-meta_description')) {
            $this->document->setDescription($this->params->get('menu-meta_description'));
        }

        if ($this->params->get('menu-meta_keywords')) {
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }

        if ($this->params->get('robots')) {
            $this->document->setMetadata('robots', $this->params->get('robots'));
        }
    }

}

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
JLoader::register('NextgCyberMenuHelper', JPATH_COMPONENT . '/helpers/menuhelper.php');

class NextgCyberViewItem extends JViewLegacy {

    protected $state = null;
    protected $item = null;
    protected $columns = 1;

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise a Error object.
     */
    public function display($tpl = null) {
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        if ($user->guest) {
            $url = JRoute::_('index.php?option=com_users&view=login&return=' . base64_encode(JURI::getInstance()->toString()));
            $app->redirect($url);
            return;
        }
        $partner_id = NextgCyberCustomerHelper::getPartnerIdByID($user->get('id'));
        if (empty($partner_id)) {
            JErrorPage::render(new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403));
            return false;
        }
        $state = $this->get('State');
        $item = $this->get('Item');
        $this->model = $this->getModel();
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            $app->enqueueMessage(implode("\n", $errors), 'error');
            return false;
        }

        $params = $state->params;
        //Escape strings for HTML output
        $this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));
        $this->params = $params;
        $this->item = $item;
        $this->user = $user;
        $this->state = $state;
        $this->_prepareDocument();
        $this->menus = NextgCyberMenuHelper::getMenu('customer', $this->getName());
        parent::display($tpl);
    }

    /**
     * Prepares the document
     */
    protected function _prepareDocument() {
        $doc = JFactory::getDocument();
        $doc->setMetaData('robots', 'noindex, nofollow');

        $app = JFactory::getApplication();
        $menus = $app->getMenu();
        $title = null;

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();

        if ($menu) {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        } else {
            $this->params->def('page_heading', JText::_('COM_NEXTGCYBER_CUSTOMER_INVOICE_TITLE'));
        }

        $title = (isset($this->item->number)) ? $this->item->number : null;

        if (empty($title)) {
            $title = $app->get('sitename');
        } elseif ($app->get('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
        } elseif ($app->get('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        }

        $this->document->setTitle($title);
    }

}

<?php

/**
 * @package pkg_nextgcyber NextgCyber for Joomla
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2015 NextgCyber . All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://nextgerp.com
 * @author NextG-ERP support@nextgerp.com
 */
defined('_JEXEC') or die;
JLoader::register('NextgCyberMenuHelper', JPATH_COMPONENT . '/helpers/menuhelper.php');
JLoader::register('NextgCyberCustomerHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/customerhelper.php');
JLoader::register('NextgCyberViewItem', JPATH_COMPONENT . '/views/itemview.php');

class NextgCyberViewProfile extends NextgCyberViewItem {

    protected $item;
    protected $form;
    protected $params;
    protected $state;

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed   A string if successful, otherwise a Error object.
     *
     * @since   1.0
     */
    public function display($tpl = null) {
        $layout = $this->getLayout();
        if ($layout == 'edit') {
            $this->form = $this->get('Form');
        }
        // Check for layout override
        $active = JFactory::getApplication()->getMenu()->getActive();
        if (isset($active->query['layout'])) {
            $this->setLayout($active->query['layout']);
        }
        return parent::display($tpl);
    }

}

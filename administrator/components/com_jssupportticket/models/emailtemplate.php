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
defined('_JEXEC') or die('Not Allowed');

jimport('joomla.application.component.model');
jimport('joomla.html.html');

class JSSupportticketModelEmailtemplate extends JSSupportTicketModel {

    function __construct() {
        parent::__construct();
    }

    function storeEmailTemplate() {
        $row = $this->getTable('emailtemplates');

        $data = JRequest::get('post');
        $data['body'] = JRequest::getVar('body', '', 'post', 'string', JREQUEST_ALLOWRAW);

        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return SAVE_ERROR;
        }
        if (!$row->store()) {
           $this->getJSModel('systemerrors')->updateSystemErrors($this->_db->getErrorMsg());
            $this->setError($this->_db->getErrorMsg());
            return SAVE_ERROR;
        }
        return SAVED;
    }

    function getTemplate($tempfor) {
        $db = JFactory::getDBO();
        switch ($tempfor) {
            case 'ew-tk' : $tempatefor = 'ticket-new';
                break;
            case 'sntk-tk' : $tempatefor = 'ticket-staff';
                break;
            case 'ew-md' : $tempatefor = 'department-new';
                break;
            case 'ew-sm' : $tempatefor = 'staff-new';
                break;
            case 'ew-ht' : $tempatefor = 'helptopic-new';
                break;
            case 'rs-tk' : $tempatefor = 'reassign-tk';
                break;
            case 'cl-tk' : $tempatefor = 'close-tk';
                break;
            case 'dl-tk' : $tempatefor = 'delete-tk';
                break;
            case 'lk-tk' : $tempatefor = 'lock-tk';
                break;
            case 'ulk-tk' : $tempatefor = 'unlock-tk';
                break;
            case 'minp-tk' : $tempatefor = 'markprgs-tk';
                break;
            case 'pc-tk' : $tempatefor = 'pchnge-tk';
                break;
            case 'ml-ew' : $tempatefor = 'mlnew-tk';
                break;
            case 'ml-rp' : $tempatefor = 'mlrep-tk';
                break;
            case 'mo-tk' : $tempatefor = 'moverdue-tk';
                break;
            case 'be-tk' : $tempatefor = 'banemail-tk';
                break;
            case 'be-trtk' : $tempatefor = 'banemail-trtk';
                break;
            case 'dt-tk' : $tempatefor = 'deptrans-tk';
                break;
            case 'ebct-tk' : $tempatefor = 'banemailcloseticket-tk';
                break;
            case 'ube-tk' : $tempatefor = 'unbanemail-tk';
                break;
            case 'rsp-tk' : $tempatefor = 'responce-tk';
                break;
            case 'rpy-tk' : $tempatefor = 'reply-tk';
                break;
            case 'tk-ew-ad' : $tempatefor = 'ticket-new-admin';
                break;
        }
        $query = "SELECT * FROM `#__js_ticket_emailtemplates` WHERE templatefor = " . $db->Quote($tempatefor);
        $db->setQuery($query);
        $template = $db->loadObject();
        return $template;
    }

    function getEmailTemplate($title) {
        $db = $this->getDbo();
        $query = "SELECT id, title FROM `#__js_ticket_email_templates` WHERE status = 1 ORDER BY title ASC";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $emailtmpl = array();
        if ($title)
            $emailtmpl[] = array('value' => '', 'text' => $title);
        foreach ($rows as $row) {
            $emailtmpl[] = array('value' => $row->id, 'text' => $row->title);
        }
        return $emailtmpl;
    }
}
?>

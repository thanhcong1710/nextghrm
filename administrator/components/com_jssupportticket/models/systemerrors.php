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

class JSSupportticketModelSystemErrors extends JSSupportTicketModel {

    function __construct() {
        parent::__construct();
    }

    function updateSystemErrors($error) {
        $row = $this->getTable('systemerrors');
        $user = JFactory::getUser();
        if (!$user->guest())
            $uid = $user->id;
        else
            $uid = 0;
        $data['uid'] = $uid;
        $data['errors'] = $error;
        $data['isview'] = 2; // means not viewd
        $data['created'] = $cur = date('Y-m-d H:i:s');

        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return 2;
        }
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        return true;
    }
    
    function getSystemErrors($limitstart, $limit) {
        $db = $this->getDbo();
        $query = "SELECT Count(id) FROM `#__js_ticket_system_errors` ";
        $db->setQuery($query);
        $total = $db->loadResult();

        $query = "SELECT error.*,user.name AS username 
					FROM `#__js_ticket_system_errors` AS error
					LEFT JOIN `#__users` AS user ON error.uid = user.id";
        $db->setQuery($query, $limitstart, $limit);
        $systemerrors = $db->loadObjectList();
        $result[0] = $systemerrors;
        $result[1] = $total;
        return $result;
    }

    function getErrorDetail($id) {
        if (!is_numeric($id))
            return False;
        $db = $this->getDbo();
        $this->updateIsView($id);
        $query = "SELECT error.*,user.name AS username
					FROM `#__js_ticket_system_errors` AS error
					LEFT JOIN `#__users` AS user ON error.uid = user.id
					WHERE error.id = " . $id;
        $db->setQuery($query);
        $result = $db->loadObject();
        return $result;
    }

    function updateIsView($id) {
        if (!is_numeric($id))
            return False;
        $db = $this->getDbo();
        $query = "UPDATE `#__js_ticket_system_errors` set isview = 1 WHERE id = " . $id;
        $db->setQuery($query);
        $db->query();
    }

}

?>

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

class TableReplies extends JTable {

    var $id = null;
    var $ticketid = null;
    var $name = null;
    var $message = null;
    var $staffid = null;
    var $rating = null;
    var $status = null;
    var $created = null;

    function __construct(&$db) {
        parent::__construct('#__js_ticket_replies', 'id', $db);
    }

    /**
     * Validation
     * 
     * @return boolean true if buffer is valid
     * 
     */
    function check() {
        if (trim($this->message) == '') {
            $this->_error = "Message cannot be empty.";
            return false;
        }
        return true;
    }

}

?>

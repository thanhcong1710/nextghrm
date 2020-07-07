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

class TableDepartments extends JTable {

    var $id = null;
    var $emailtemplateid = null;
    var $emailid = null;
    var $autoresponceemailid = null;
    var $managerid = null;
    var $departmentname = null;
    var $departmentsignature = null;
    var $ispublic = 0;
    var $ticketautoresponce = 0;
    var $messageautoresponce = 0;
    var $canappendsignature = 0;
    var $created = null;
    var $updated = null;
    var $status = 0;

    function __construct(&$db) {
        parent::__construct('#__js_ticket_departments', 'id', $db);
    }

    /**
     * Validation
     * 
     * @return boolean true if buffer is valid
     * 
     */
    function check() {
        return true;
    }

}

?>

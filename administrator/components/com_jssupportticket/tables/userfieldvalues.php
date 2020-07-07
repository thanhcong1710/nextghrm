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

class TableUserFieldValues extends JTable {

    var $id = null;
    var $field = null;
    var $fieldtitle = null;
    var $fieldvalue = null;
    var $ordering = null;
    var $sys = null;

    function __construct(&$db) {
        parent::__construct('#__js_ticket_userfieldvalues', 'id', $db);
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

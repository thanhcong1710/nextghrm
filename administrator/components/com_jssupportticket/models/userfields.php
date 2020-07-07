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

class JSSupportticketModelUserFields extends JSSupportTicketModel {

    function __construct() {
        parent::__construct();
    }

     function storeUserField() {

        $db = JFactory::getDBO();
        $row = $this->getTable('userfields');

        $data = JRequest::get('post');
        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return SAVE_ERROR;
        }
        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return SAVE_ERROR;
        }
        if (!$row->store()) {
           $this->getJSModel('systemerrors')->updateSystemErrors($this->_db->getErrorMsg());
            $this->setError($this->_db->getErrorMsg());
            return SAVE_ERROR;
        }
        // add in field ordering
        if ($data['id'] == '') { // only for new
            $query = "INSERT INTO #__js_ticket_fieldsordering
                    (field, fieldtitle, ordering, section, fieldfor, published, sys, cannotunpublish)
                    VALUES(" . $row->id . ",'" . $data['title'] . "', ( SELECT max(ordering)+1 FROM #__js_ticket_fieldsordering AS field WHERE fieldfor = 1 ), ''
                    , 1 ," . $data['published'] . " ,0,0)
            ";
            //echo '<br>sql '.$query;
            $db->setQuery($query);
            if (!$db->query()) {
                return SAVE_ERROR;
            }
        }

        // store values
        $ids = $data['jsIds'];
        $names = $data['jsNames'];
        $values = $data['jsValues'];
        $fieldvaluerow = $this->getTable('userfieldvalues');

        for ($i = 0; $i <= $data['valueCount']; $i++) {

            $fieldvaluedata = array();
            if (isset($ids[$i]))
                $fieldvaluedata['id'] = $ids[$i];
            else
                $fieldvaluedata['id'] = '';
            $fieldvaluedata['field'] = $row->id;
            $fieldvaluedata['fieldtitle'] = $names[$i];
            $fieldvaluedata['fieldvalue'] = $values[$i];
            $fieldvaluedata['ordering'] = $i + 1;
            $fieldvaluedata['sys'] = 0;

            if (!$fieldvaluerow->bind($fieldvaluedata)) {
                $this->setError($this->_db->getErrorMsg());
                return SAVE_ERROR;
            }
            if (!$fieldvaluerow->store()) {
               $this->getJSModel('systemerrors')->updateSystemErrors($fieldvaluerow->getErrorMsg());
                $this->setError($this->_db->getErrorMsg());
                return SAVE_ERROR;
            }
        }

        return SAVED;
    }

    function getUserFieldsForView($fieldfor, $id) {
        $db = $this->getDBO();
        //if (isset($id) == false) return false;
        $result;
        //if (is_numeric($id) == false) return $result;
        $field = array();
        $result = array();
        $query = "SELECT  * FROM `#__js_ticket_userfields` 
					WHERE published = 1 AND fieldfor = " . $fieldfor;
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $i = 0;
        foreach ($rows as $row) {
            $field[0] = $row;
            if ($id != "") {
                if(!is_numeric($id)) return false;
                $query = "SELECT  * FROM `#__js_ticket_userfield_data` WHERE referenceid = " . $id . " AND field = " . $row->id;
                $db->setQuery($query);
                $data = $db->loadObject();
                $field[1] = $data;
            }
            if ($row->type == "select") {
                $query = "SELECT  value.* FROM `#__js_ticket_userfieldvalues` value
				JOIN `#__js_ticket_userfield_data` udata ON udata.data = value.id
				WHERE value.field = " . $row->id;
                $db->setQuery($query);
                //echo '<br> sql '.$query;
                $value = $db->loadObject();
                $field[2] = $value;
            }
            $result[] = $field;
            $i++;
        }
        return $result;
    }

    function getUserFieldsForForm($fieldfor, $id) {
        if ($id != '')
            if (!is_numeric($id))
                return false;
        if (!is_numeric($fieldfor))
            return false;
        $db = $this->getDBO();
        $result;
        $field = array();
        $result = array();
        $query = "SELECT  * FROM `#__js_ticket_userfields`
                                    WHERE published = 1 AND fieldfor = " . $fieldfor;
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $i = 0;
        foreach ($rows as $row) {
            $field[0] = $row;
            if ($id != "") {
                $query = "SELECT  * FROM `#__js_ticket_userfield_data` WHERE referenceid = " . $id . " AND field = " . $row->id;
                $db->setQuery($query);
                $data = $db->loadObject();
                $field[1] = $data;
            }
            if ($row->type == "select") {
                $query = "SELECT  * FROM `#__js_ticket_userfieldvalues` WHERE field = " . $row->id;
                $db->setQuery($query);
                $values = $db->loadObjectList();
                $field[2] = $values;
            }
            $result[] = $field;
            $i++;
        }
        return $result;
    }

    function deleteUserFieldOptionValue($id) {
        $row = $this->getTable('userfieldvalues');
        if ($row->load($id)) {
            $db = JFactory::getDBO();
            $query = "SELECT count(id) FROM `#__js_ticket_userfield_data` WHERE field = " . $row->field . " AND data=" . $row->id;
            $db->setQuery($query);
            $total = $db->loadResult();
            if ($total > 0)
                $return = DELETE_ERROR;
            else {
                $return = DELETED;
                $row->delete();
            }
        } else
            $return = DELETE_ERROR;
        return $return;
    }

     function getFieldsOrderingForView($fieldfor) {
        if(!is_numeric($fieldfor)) return false;
        $db = $this->getDBO();
        $query = "SELECT  * FROM `#__js_ticket_fieldsordering` 
                    WHERE published = 1 AND fieldfor =  " . $fieldfor
                . " ORDER BY ordering";
        //echo '<br> SQL '.$query;
        $db->setQuery($query);
        $fields = $db->loadObjectList();
        return $fields;
    }

    function storeUserFieldData($data, $ticketid) { //store  user field data
        for ($i = 0; $i <= $data['userfields_total']; $i++) {
            $row = $this->getTable('userfielddata');
            $fname = "userfields_" . $i;
            $fid = "userfields_" . $i . "_id";
            $dataid = "userdata_" . $i . "_id";
            //$fielddata['id'] = "";

            $fielddata['id'] = $data[$dataid];
            $fielddata['referenceid'] = $ticketid;
            $fielddata['field'] = $data[$fid];
            $fielddata['data'] = $data[$fname];
            if (!$row->bind($fielddata)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
               $this->getJSModel('systemerrors')->updateSystemErrors($row->getErrorMsg());
                echo $this->_db->getErrorMsg();
                return false;
            }
        }
        return true;
    }

    function deleteUserField() {
        $cids = JRequest::getVar('cid', array(0), null, 'array');
        $row = $this->getTable('userfields');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->userFieldCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return DELETE_ERROR;
                }
            } else
                $deleteall++;
        }
        if($deleteall == 1){
            return DELETED;
        }else{
            $deleteall = $deleteall-1;
            JSSupportticketMessage::$recordid = $deleteall;
            return DELETE_ERROR;
        }
    }

    function userFieldCanDelete($field) {
        $db = $this->getDBO();
        if (!is_numeric($field))
            return false;
        $query = "SELECT COUNT(id) FROM `#__js_ticket_userfield_data` WHERE field = " . $field;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total == 0)
            return true;
        else
            return false;
    }

    function getFieldsOrderingLimit($fieldfor, $limitstart, $limit) {
        if (is_numeric($fieldfor) == false)
            return false;
        $db = JFactory::getDBO();
        $result = array();

        $query = "SELECT COUNT(id) FROM `#__js_ticket_fieldsordering` WHERE fieldfor = " . $fieldfor;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT field.* ,userfield.title as userfieldtitle
                    FROM `#__js_ticket_fieldsordering` AS field
                    LEFT JOIN `#__js_ticket_userfields` AS userfield ON field.field = userfield.id
                    WHERE field.fieldfor = " . $fieldfor;
        $query .= " ORDER BY field.ordering";

        //echo $query;
        $db->setQuery($query, $limitstart, $limit);
        $fields = $db->loadObjectList();
        $result[0] = $fields;
        $result[1] = $total;
        return $result;
    }


    function getFieldsOrdering($fieldfor)
    {
        if(!is_numeric($fieldfor)) return false;
        $db = $this->getDBO();
        $query =  "SELECT  * FROM `#__js_ticket_fieldsordering` 
                    WHERE published = 1 AND fieldfor =  ". $fieldfor
                    ." ORDER BY ordering";
        $db->setQuery($query);
        $fields = $db->loadObjectList();
        return $fields;
    }

    function fieldPublished($field_id, $value) {
        if (is_numeric($field_id) == false)
            return false;
        $db = JFactory::getDBO();

        $query = " UPDATE `#__js_ticket_fieldsordering` SET published = " . $value . " WHERE id = " . $field_id;
        //echo '<br>sql '.$query;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function fieldRequired($field_id, $value) {
        if (is_numeric($field_id) == false)
            return false;
        $db = JFactory::getDBO();

        $query = " UPDATE `#__js_ticket_fieldsordering` SET required = " . $value . " WHERE id = " . $field_id;
        //echo '<br>sql '.$query;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function fieldOrderingUp($field_id) {
        if (is_numeric($field_id) == false)
            return false;
        $db = JFactory::getDBO();

        $query = "UPDATE `#__js_ticket_fieldsordering` AS f1, `#__js_ticket_fieldsordering` AS f2
                    SET f1.ordering = f1.ordering - 1 WHERE f1.ordering = f2.ordering + 1 AND f1.fieldfor = f2.fieldfor
                    AND f2.id = " . $field_id . " ; ";
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }

        $query = " UPDATE `#__js_ticket_fieldsordering` SET ordering = ordering + 1 WHERE id = " . $field_id . ";"
        ;
        //echo '<br>sql '.$query;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function fieldOrderingDown($field_id) {
        if (is_numeric($field_id) == false)
            return false;
        $db = JFactory::getDBO();

        $query = "UPDATE `#__js_ticket_fieldsordering` AS f1, `#__js_ticket_fieldsordering` AS f2 SET f1.ordering = f1.ordering + 1 
                    WHERE f1.ordering = f2.ordering - 1 AND f1.fieldfor = f2.fieldfor AND f2.id = " . $field_id . " ; ";

        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }

        $query = " UPDATE `#__js_ticket_fieldsordering` SET ordering = ordering - 1 WHERE id = " . $field_id . ";";
        //echo '<br>sql '.$query;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function getFieldsOrderingforForm($fieldfor) {
        if (is_numeric($fieldfor) == false)
            return false;
        $db = $this->getDBO();
        $query = "SELECT  * FROM `#__js_ticket_fieldsordering`
                    WHERE published = 1 AND fieldfor =  " . $fieldfor
                . " ORDER BY ordering";
        //echo '<br> SQL '.$query;
        $db->setQuery($query);
        $fieldordering = $db->loadObjectList();
        return $fieldordering;
    }

    
    function getUserFields($fieldfor, $limitstart, $limit) {
        if (is_numeric($fieldfor) == false)
            return false;
        $db = JFactory::getDBO();
        $result = array();
        $filter_fieldtitle = JRequest::getVar('filter_fieldtitle');
        $query = "SELECT COUNT(id) FROM `#__js_ticket_userfields` WHERE fieldfor = " . $fieldfor;
        if($filter_fieldtitle){
            $query .= " AND title LIKE '%".$filter_fieldtitle."%'";
        }
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT field.* FROM `#__js_ticket_userfields` AS field WHERE fieldfor = " . $fieldfor;
        if($filter_fieldtitle){
            $query .= " AND field.title LIKE '%".$filter_fieldtitle."%'";
        }
        $query .= " ORDER BY field.id";

        $db->setQuery($query, $limitstart, $limit);
        $this->_application = $db->loadObjectList();

        $result[0] = $this->_application;
        $result[1] = $total;
        $result[2] = $filter_fieldtitle;
        return $result;
    }

    function getUserFieldbyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $result = array();
        $db = JFactory::getDBO();
        $query = "SELECT * FROM #__js_ticket_userfields WHERE id = " . $db->Quote($c_id);
        $db->setQuery($query);
        $result[0] = $db->loadObject();

        $query = "SELECT * FROM #__js_ticket_userfieldvalues WHERE field = " . $db->Quote($c_id);
        $db->setQuery($query);
        $result[1] = $db->loadObjectList();

        return $result;
    }

    function isFieldRequiredByField($field){
        $db = JFactory::getDbo();
        $query = "SELECT field.required FROM `#__js_ticket_fieldsordering` AS field WHERE field.field = '$field'";
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }
}

?>

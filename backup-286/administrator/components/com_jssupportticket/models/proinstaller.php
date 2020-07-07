<?php

/**
 * @Copyright Copyright (C) 2012 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:     Buruj Solutions
  + Contact:        www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:  May 03, 2012
  ^
  + Project:    JS Tickets
  ^
 */
defined('_JEXEC') or die('Not Allowed');

jimport('joomla.application.component.model');
jimport('joomla.html.html');

class JSSupportticketModelProinstaller extends JSSupportTicketModel {

    function __construct() {
        parent::__construct();
    }

    function getServerValidate() {
        $result = array();
        $array = explode('.', phpversion());
        $phpversion = $array[0] . '.' . $array[1];
        $curlexist = function_exists('curl_version');
        $curlversion = '';
        if (extension_loaded('gd') && function_exists('gd_info')) {
            $gd_lib = 1;
        } else {
            $gd_lib = 0;
        }
        $zip_lib = 0;
        if (file_exists('components/com_jssupportticket/include/lib/pclzip.lib.php')) {
            $zip_lib = 1;
        }
        $result['phpversion'] = $phpversion;
        $result['curlexist'] = $curlexist;
        $result['curlversion'] = $curlversion;
        $result['gdlib'] = $gd_lib;
        $result['ziplib'] = $zip_lib;
        return $result;
    }

    function getConfigByConfigName($configname) {
        $db = JFactory::getDBO();
        $query = "SELECT * FROM `#__js_ticket_config` WHERE configname = " . $db->quote($configname);
        $db->setQuery($query);
        $result = $db->loadObject();
        return $result;
    }

    function getCountConfig() {
        $db = JFactory::getDBO();
        $query = "SELECT COUNT(*) AS count_config FROM `#__js_ticket_config` ";
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    function getStepTwoValidate() {
        $return['admin_dir'] = substr(sprintf('%o', fileperms('components/com_jssupportticket')), -3);
        $return['site_dir'] = substr(sprintf('%o', fileperms('../components/com_jssupportticket')), -3);
        $return['tmp_dir'] = substr(sprintf('%o', fileperms('../tmp')), -3);
        $db = $this->getDbo();
        $query = 'CREATE TABLE js_test_table(
                    id int,
                    name varchar(255)
                );';
        $db->setQuery($query);
        $return['create_table'] = 0;
        if ($db->query()) {
            $return['create_table'] = 1;
        }
        $query = 'INSERT INTO js_test_table(id,name) VALUES (1,\'Naeem\'),(2,\'Saad\');';
        $db->setQuery($query);
        $return['insert_record'] = 0;
        if ($db->query()) {
            $return['insert_record'] = 1;
        }
        $query = 'UPDATE js_test_table SET name = \'Shoaib Rehmat\' WHERE id = 1;';
        $db->setQuery($query);
        $return['update_record'] = 0;
        if ($db->query()) {
            $return['update_record'] = 1;
        }
        $query = 'DELETE FROM js_test_table;';
        $db->setQuery($query);
        $return['delete_record'] = 0;
        if ($db->query()) {
            $return['delete_record'] = 1;
        }
        $query = 'DROP TABLE js_test_table;';
        $db->setQuery($query);
        $return['drop_table'] = 0;
        if ($db->query()) {
            $return['drop_table'] = 1;
        }
        if($return['tmp_dir'] >= 755){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_URL, 'http://test.setup.joomsky.com/logo.png');
            $fp = fopen('../tmp/logo.png', 'w+');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
            curl_exec ($ch);
            curl_close ($ch);
            fclose($fp);
            $return['file_downloaded'] = 0;
            if(file_exists('../tmp/logo.png')){
                $return['file_downloaded'] = 1;
            }
        }else $return['file_downloaded'] = 0;
        return $return;
    }

    function getmyversionlist() {
        $post_data['transactionkey'] = JRequest::getVar('transactionkey');
        $post_data['serialnumber'] = JRequest::getVar('serialnumber');
        $post_data['domain'] = JRequest::getVar('domain');
        $post_data['producttype'] = JRequest::getVar('producttype', null, 'pro');
        $post_data['productcode'] = JRequest::getVar('productcode');
        $post_data['productversion'] = JRequest::getVar('productversion');
        $post_data['JVERSION'] = JRequest::getVar('JVERSION');
        $post_data['count'] = JRequest::getVar('config_count');
        $post_data['installerversion'] = JRequest::getVar('installerversion');
        $ch = curl_init();
        $url = "https://setup.joomsky.com/jssupportticketjm/pro/index.php";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
?>
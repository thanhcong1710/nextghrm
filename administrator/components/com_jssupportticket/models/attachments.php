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

class JSSupportTicketModelattachments extends JSSupportticketModel {

    function __construct() {
        parent::__construct();
    }

    function storeTicketAttachment($ticketid,$replyattachmentid = ''){
        $config = $this->getJSModel('config')->getConfigByFor('default');
        $filesize = $config['filesize'];
        $total = count($_FILES['filename']['name']);
        for ($i = 0; $i < $total; $i++) {
            if ($_FILES['filename']['name'][$i] != '') {
                if ($_FILES['filename']['size'][$i] > 0) {
                    $uploadfilesize = $_FILES['filename']['size'][$i];
                    $uploadfilesize = $uploadfilesize / 1024; //kb
                    if ($uploadfilesize > $filesize) {
                        return FILE_SIZE_ERROR;
                    }
                    $file_name = str_replace(' ', '_', $_FILES['filename']['name'][$i]);
                    $result = $this->checkExtension($file_name);
                    if ($result == 'N') {
                        return FILE_EXTENTION_ERROR;
                    }
                    $res = $this->uploadAttchments($i, $ticketid, 1, 0, 'ticket');
                    if ($res) {
                        $result = $this->storeAttachment($ticketid, $uploadfilesize, $file_name,$replyattachmentid);
                    }else{ 
                        return FILE_RW_ERROR;
                    }
                }
            }
        }
        return true;
    }

    function storeAttachment($ticketid, $filesize, $filename, $replyattachmentid = 0) {
        if (!is_numeric($ticketid))
            return false;
        $row = $this->getTable('attachments');
        $data['ticketid'] = $ticketid;
        $data['replyattachmentid'] = $replyattachmentid; // this should set to zero when new ticket created
        $data['filename'] = $filename;
        $data['filesize'] = $filesize;
        $data['created'] = $curdate = date('Y-m-d H:i:s');

        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->store()) {
            $this->getJSModel('systemerrors')->updateSystemErrors($row->getErrorMsg());
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        return true;
    }
    
    function uploadAttchments($i, $id, $action, $isdeletefile, $filefor){
        if (is_numeric($id) == false) return false;
        $isupload = false;
        $config = $this->getJSModel('config')->getConfigs();
        $datadirectory = $config['data_directory'];
        $base = JPATH_BASE;
        if(JFactory::getApplication()->isAdmin()){
            $base = substr($base, 0, strlen($base) - 14); //remove administrator    
        }  
        $path = $base.'/'.$datadirectory;
        if (!file_exists($path)){ // create user directory
            $this->makeDir($path);
        }
        $path = $path . '/attachmentdata';
        if (!file_exists($path)){ // create user directory
            $this->makeDir($path);
        }
        $path = $path . '/'.$filefor;
        if (!file_exists($path)){ // create user directory
            $this->makeDir($path);
        }
        if ($action == true) {
            if($_FILES['filename']['size'][$i] > 0){
                $file_name = str_replace(' ', '_', $_FILES['filename']['name'][$i]);
                $file_tmp = $_FILES['filename']['tmp_name'][$i]; // actual location
                $userpath = $path . '/'.$filefor.'_' . $id;
                if (!file_exists($userpath)) { // create user directory
                    $this->makeDir($userpath);
                }
                $isupload = true;
            }
        }
        if ($isupload == true && $isdeletefile == false){
            move_uploaded_file($file_tmp, $userpath.'/' . $file_name);
            return true;
        }
        if ($isdeletefile == true){
            $userpath= $path .'/'.$filefor.'_'.$id;
            $files = glob($userpath.'/*.*');
            array_map('unlink', $files); // delete all file in the direcoty
            return true;
        }
        return false;
    }

    function makeDir($path) {
        if (!file_exists($path)) { // create directory
            mkdir($path, 0755);
            $ourFileName = $path . '/index.html';
            $ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
            fclose($ourFileHandle);
        }
    }

    function checkExtension($filename) {
        $i = strrpos($filename, ".");
        if (!$i) {
            return 6;
        }
        $l = strlen($filename) - $i;
        $ext = substr($filename, $i + 1, $l);
        $config = $this->getJSModel('config')->getConfigByFor('default');
        $extensions = explode(",", $config['fileextension']);
        $match = 'N';
        foreach ($extensions as $extension) {
            if ($extension == $ext) {
                $match = 'Y';
                break;
            }
        }
        return $match;
    }

    function getExtension($str) {
        $i = strrpos($str, ".");
        if (!$i) {
            return "";
        }
        $l = strlen($str) - $i;
        $ext = substr($str, $i + 1, $l);
        return $ext;
    }


}?>
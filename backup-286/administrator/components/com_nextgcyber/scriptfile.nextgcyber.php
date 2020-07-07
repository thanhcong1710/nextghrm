<?php

/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2015 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://nextgcyber.com
 * @author Daniel.Vu
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

//Import neccessary library
jimport('joomla.installer.installer');
jimport('joomla.installer.helper');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class com_nextgcyberInstallerScript {

    /**
     * method to install the component
     *
     * @return void
     */
    function install($parent) {
        // $parent is the class calling this method
    }

    /**
     * method to uninstall the component
     *
     * @return void
     */
    function uninstall($parent) {
        // $parent is the class calling this method
    }

    /**
     * method to update the component
     *
     * @return void
     */
    function update($parent) {
        // $parent is the class calling this method
        $version = $parent->get('manifest')->version;
        echo '<p>' . $version . '</p>';
    }

    /**
     * method to run before an install/update/uninstall method
     *
     * @return void
     */
    function preflight($type, $parent) {
        // $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)
        //echo '<p>' . JText::_('COM_HELLOWORLD_PREFLIGHT_' . $type . '_TEXT') . '</p>';
    }

    /**
     * method to run after an install/update/uninstall method
     *
     * @return void
     */
    function postflight($type, $parent) {
        $version = $parent->get('manifest')->version;
    }

    /**
     * Delete records against the $condition from table $tablename
     * @param string $tablename database table name
     * @param array $condition E.g. $condition = array('uid' => 3, 'invoiceid' => 5)
     * @param bool $transaction enable/disable transaction
     */
    private function _delete($tablename, $condition) {
        try {
            $db = JFactory::getDbo();

            $query = $db->getQuery(true);
            $condition_sql = array();

            foreach ($condition as $key => $value) {
                $condition_sql[] = $db->quoteName($key) . '=' . $db->quote($value);
            }

            $query->delete($db->quoteName($tablename));
            $query->where($condition_sql);
            $db->setQuery($query);
            $db->execute();
        } catch (Exception $e) {
            // catch any database errors.
            JErrorPage::render($e);
        }
    }

    /**
     * Delete all deprecated file
     * @since 2.0.0
     * @return void
     */
    protected function _deleteDeprecatedFile($version) {
        $deletelist = array();
        $com_path_admin = JPATH_ADMINISTRATOR . '/components/com_nextgcyber';
        $com_path_site = JPATH_SITE . '/components/com_nextgcyber';
        foreach ($deletelist as $file) {
            if (JFile::exists($file)) {
                JFile::delete($file);
            } elseif (JFolder::exists($file)) {
                JFolder::delete($file);
            }
        }
    }

    private function doesGroupExist($groupTitle) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('a.id')
                ->from('#__usergroups AS a')
                ->where('a.title= ' . $db->quote($groupTitle));
        $db->setQuery($query);
        if ($group_id = $db->loadResult()) {
            return $group_id;
        } else {
            return 0;
        }
    }

    /**
     * method to create or update a group
     *
     * @param string $groupTitle
     * @param int $parent_id
     * @return int
     * @throws InvalidArgumentException
     */
    private function createGroup($groupTitle, $parent_id) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $groupTitle = trim($groupTitle);
        $groupId = $this->doesGroupExist($groupTitle);

        if (is_numeric($parent_id)) {
            $parent_id = (int) $parent_id;
        } else {
            throw new InvalidArgumentException('Group\'s Parent ID must be numeric');
        }

        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_users/models/', 'UsersModel');
        $groupModel = JModelLegacy::getInstance('Group', 'UsersModel');


        $groupData = [
            'title' => $groupTitle,
            'parent_id' => $parent_id,
            'id' => $groupId
        ];

        unset($groupId);

        if ($groupModel->save($groupData)) {
            $saved_id = $groupModel->getState('group.id');
            $query = $db->getQuery(true);
            $query->select('a.id')
                    ->from('#__viewlevels as a')
                    ->where('a.title=' . $db->quote($groupTitle));
            $db->setQuery($query);
            $levelId = $db->loadResult();
            $levelModel = JModelLegacy::getInstance('Level', 'UsersModel');
            if (!$levelId) {
                // Create new view level
                $data = array(
                    'id' => 0,
                    'title' => $groupTitle,
                    'rules' => array($saved_id)
                );
            } else {
                $levelRecord = $levelModel->getItem($levelId);
                $levelRecord->rules[] = $saved_id;
                $rules = $levelRecord->rules;
                $data = array(
                    'id' => $levelId,
                    'rules' => $rules
                );
            }

            $levelModel->save($data);
            return $saved_id;
        } else {
            return 0;
        }
    }

    /**
     * Insert an array of values into a database table
     * @param string $tablename name of table to insert, eg #__tdownloadstore_config
     * @param array $columns columns to be inserted, eg array('id', key', 'value')
     * @param array $values values to insert into $columns, eg array ("1,'firstname', 'David'", "2,'familyname', 'Tran'")
     */
    private function _insert($tablename, $columns, $values, $transaction = true) {
        $db = JFactory::getDbo();

        try {
            if ($transaction) {
                $db->transactionStart();
            }
            $query = $db->getQuery(true);
            $query->insert($db->quoteName($tablename));
            $query->columns($db->quoteName($columns));
            $query->values($values);
            $db->setQuery($query);
            $db->execute();
            $lastRowId = $db->insertid();
            if ($transaction) {
                $db->transactionCommit();
            }
            return $lastRowId;
        } catch (Exception $e) {
            // catch any database errors.
            if ($transaction) {
                $db->transactionRollback();
            }
            JErrorPage::render($e);
        }
    }

    /**
     * Method generate random string
     * @param integer $length
     * @return string
     * @since 1.0.4
     */
    private function _generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}

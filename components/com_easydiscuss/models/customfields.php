<?php

/**
 * @package		EasyDiscuss
 * @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * EasyDiscuss is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Restricted access');

require_once dirname(__FILE__) . '/model.php';

class EasyDiscussModelCustomFields extends EasyDiscussModel
{

        /**
         * Category total
         *
         * @var integer
         */
        protected $_total = null;

        /**
         * Pagination object
         *
         * @var object
         */
        protected $_pagination = null;

        /**
         * Category data array
         *
         * @var array
         */
        protected $_data = null;

        public function __construct()
        {
                parent::__construct();
                $app = JFactory::getApplication();
                $limit = $app->getUserStateFromRequest('com_easydiscuss.customs.limit', 'limit', $app->getCfg('list_limit'), 'int');
                $limitstart = JRequest::getVar('limitstart', 0, '', 'int');

                $this->setState('limit', $limit);
                $this->setState('limitstart', $limitstart);
        }

        /**
         * Method to get the total nr of the categories
         *
         * @access public
         * @return integer
         */
        public function getTotal()
        {
                // Lets load the content if it doesn't already exist
                if (empty($this->_total))
                {
                        $query = $this->_buildQuery();
                        $this->_total = $this->_getListCount($query);
                }

                return $this->_total;
        }

        /**
         * Method to get a pagination object for the categories
         *
         * @access public
         * @return integer
         */
        public function getPagination()
        {
                // Lets load the content if it doesn't already exist
                if (empty($this->_pagination))
                {
                        jimport('joomla.html.pagination');
                        $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
                }

                return $this->_pagination;
        }

        /**
         * Method to build the query for the customs
         *
         * @access private
         * @return string
         */
        protected function _buildQuery()
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('a.*')
                        ->from('#__discuss_customfields AS a')
                        ->where($this->_buildQueryWhere())
                        ->order($this->_buildQueryOrderBy());
                return $query;
        }

        protected function _buildQueryWhere()
        {
                $app = JFactory::getApplication();
                $db = JFactory::getDbo();
                $filter_state = $app->getUserStateFromRequest('com_easydiscuss.customs.filter_state', 'filter_state', '', 'word');
                $where = array();

                if ($filter_state)
                {
                        if ($filter_state == 'P')
                        {
                                $where[] = 'a.published = 1';
                        } else if ($filter_state == 'U')
                        {
                                $where[] = 'a.published = 0';
                        }
                }
                return $where;
        }

        protected function _buildQueryOrderBy()
        {
                $app = JFactory::getApplication();
                $filter_order = $app->getUserStateFromRequest('com_easydiscuss.customs.filter_order', 'filter_order', 'a.id', 'cmd');
                $filter_order_Dir = $app->getUserStateFromRequest('com_easydiscuss.customs.filter_order_Dir', 'filter_order_Dir', '', 'word');
                $orderby = $filter_order . ' ' . $filter_order_Dir;
                return $orderby;
        }

        /**
         * Method to get categories item data
         *
         * @access public
         * @return array
         */
        public function getData($usePagination = true)
        {
                // Lets load the content if it doesn't already exist
                if (empty($this->_data))
                {
                        $query = $this->_buildQuery();
                        if ($usePagination)
                                $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
                        else
                                $this->_data = $this->_getList($query);
                }

                return $this->_data;
        }

        /**
         * Method to publish or unpublish customs
         *
         * @access public
         * @return array
         */
        public function publish($customs = array(), $publish = 1)
        {
                if (count($customs) > 0)
                {
                        try {
                                $db = JFactory::getDbo();
                                $query = $db->getQuery(true);
                                $query->update('#__discuss_customfields')
                                        ->set('published = ' . $db->quote($publish))
                                        ->where('id IN (' . implode(',', $customs) . ')');
                                $db->setQuery($query);
                                $db->execute();
                                return true;
                        } catch (Exception $ex) {
                                return false;
                        }
                }
                return false;
        }

        public function sortDescending($a, $b)
        {
                // Descending sort based on the object property "ordering"
                return ($a->ordering < $b->ordering) ? 1 : -1;
        }

        public function getMyFields($postId = null, $aclId = null)
        {
                if ($aclId == null)
                {
                        return false;
                }

                // NEW POST
                if ($postId == null)
                {
                        return $this->setNewFields($aclId);
                }
                $myResults = $this->checkMyFields($postId, $aclId);
                if (!empty($myResults))
                {
                        usort($myResults, array('EasyDiscussModelCustomFields', 'sortDescending'));
                }

                return $myResults;
        }

        public function setNewFields($aclId)
        {
                return $this->getNewFields($aclId);
        }

        public function getNewFields($aclId = null)
        {
                static $loaded = array();
                $sig = (int) $aclId;
                if (!isset($loaded[$sig]))
                {
                        $db = JFactory::getDbo();
                        $my = JFactory::getUser();
                        $myUserGroups = (array) DiscussHelper::getUserGroupId($my);

                        if (empty($myUserGroups))
                        {
                                $loaded[$sig] = array();
                        } else
                        {
                                $userQuery = $db->getQuery(true);
                                $userQuery->select('a.*, b.acl_id')
                                        ->from('#__discuss_customfields AS a')
                                        ->leftJoin('#__discuss_customfields_rule AS b ON a.id = b.field_id')
                                        ->where('a.published = 1')
                                        ->where('b.acl_id = ' . (int) $aclId);

                                $userQuery->where('b.content_type = ' . $db->quote('user'))
                                        ->where('b.content_id = ' . (int) $my->id);

                                $groupQuery = $db->getQuery(true);
                                $groupQuery->select('a.*, b.acl_id')
                                        ->from('#__discuss_customfields AS a')
                                        ->leftJoin('#__discuss_customfields_rule AS b ON a.id = b.field_id')
                                        ->where('a.published = 1')
                                        ->where('b.acl_id = ' . (int) $aclId);

                                $groupQuery->where('b.content_tye = ' . $db->quote('group'));
                                if (count($myUserGroups) == 1)
                                {
                                        $gid = array_pop($myUserGroups);
                                        $groupQuery->where('b.content_id = ' . (int) $gid);
                                } else
                                {
                                        $groupQuery->where('b.content_id IN (' . implode(', ', $myUserGroups) . ')');
                                }

                                $groupQuery->union($userQuery);
                                $db->setQuery($groupQuery);
                                $loaded[$sig] = $db->loadObjectList();
                        }
                }

                return $loaded[$sig];
        }

        public function checkMyFields($postId, $aclId)
        {
                // GET MY VALUE
                return $this->getAllFields($postId, $aclId);
        }

        public function getAllFields($postId = null, $aclId = null)
        {
                if ($aclId == null || $postId == null)
                {
                        return false;
                }

                static $loaded = array();
                $sig = (int) $postId . '-' . (int) $aclId;
                if (!isset($loaded[$sig]))
                {
                        $my = JFactory::getUser();
                        $db = JFactory::getDbo();
                        $myUserGroups = (array) DiscussHelper::getUserGroupId($my);

                        if (empty($myUserGroups))
                        {
                                $loaded[$sig] = array();
                        } else
                        {
                                $userQuery = $db->getQuery(true);
                                $userQuery->select('a.*, b.field_id, b.acl_id, b.content_id, b.content_type, b.status')
                                        ->select('c.field_id, c.' . $db->quoteName('value') . ', c.post_id')
                                        ->from('#__discuss_customfields AS a')
                                        ->leftJoin('#__discuss_customfields_rule AS b ON a.id = b.field_id')
                                        ->leftJoin('#__discuss_customfields_value AS c ON a.id = c.field_id AND c.post_id = ' . (int) $postId);

                                $userQuery->where('a.published = 1')
                                        ->where('b.content_type = ' . $db->quote('user'))
                                        ->where('b.acl_id = ' . (int) $aclId)
                                        ->where('b.content_id = ' . (int) $my->id);

                                $groupQuery = $db->getQuery(true);
                                $groupQuery->select('a.*, b.field_id, b.acl_id, b.content_id, b.content_type, b.status')
                                        ->select('c.field_id, c.' . $db->quoteName('value') . ', c.post_id')
                                        ->from('#__discuss_customfields AS a')
                                        ->leftJoin('#__discuss_customfields_rule AS b ON a.id = b.field_id')
                                        ->leftJoin('#__discuss_customfields_value AS c ON a.id = c.field_id AND c.post_id = ' . (int) $postId);

                                $groupQuery->where('a.published = 1')
                                        ->where('b.content_type = ' . $db->quote('group'))
                                        ->where('b.acl_id = ' . (int) $aclId);

                                if (count($myUserGroups) == 1)
                                {
                                        $gid = array_pop($myUserGroups);
                                        $groupQuery->where('b.content_id = ' . (int) $gid);
                                } else
                                {
                                        $groupQuery->where('b.content_id IN (' . implode(', ', $myUserGroups) . ')');
                                }

                                $groupQuery->union($userQuery);
                                $db->setQuery($groupQuery);
                                $result = $db->loadObjectList();

                                // @user with multiple group will generate duplicate result, hence we remove it
                                if (!empty($result))
                                {
                                        $myFinalResults = array();

                                        // Remove dupes records which have no values
                                        foreach ($result as $item)
                                        {
                                                if (!array_key_exists($item->id, $myFinalResults))
                                                {
                                                        $myFinalResults[$item->id] = $item;
                                                } else
                                                {
                                                        if (!empty($item->id))
                                                        {
                                                                // If the pending item have value, replace the existing record
                                                                $myFinalResults[$item->id] = $item;
                                                        }
                                                }
                                        }
                                        $result = $myFinalResults;
                                }
                                $loaded[$sig] = $result;
                        }
                }

                return $loaded[$sig];
        }

        public function deleteCustomFieldsValue($id, $type = null)
        {
                if (!$id)
                {
                        return false;
                }

                $db = JFactory::getDbo();
                $query = $db->getQuery(true);

                if ($type == 'post')
                {
                        $query->delete('#__discuss_customfields_value')
                                ->where('post_id = ' . (int) $id);
                }

                if ($type == 'field')
                {
                        // Delete all custom field's value of that particular field.
                        $query->delete('#__discuss_customfields_value')
                                ->where('field_id = ' . (int) $id);
                }

                if ($type == 'update')
                {
                        // If edit post, when certain custom fields is unpublish, we don't want to delete the unpublish because what if the user publish it back? unless he want to delete post
                        // Delete published only
                        $query->delete('#__discuss_customfields_value AS a')
                                ->leftJoin('#__discuss_customfields AS b ON a.field_id = b.id')
                                ->where('a.post_id = ' . (int) $id)
                                ->where('b.published = 1');
                }

                try {
                        $db->setQuery($query);
                        $db->execute();
                        return true;
                } catch (Exception $ex) {
                        return false;
                }
        }

        public function deleteCustomFieldsRule($id)
        {
                if (!$id)
                {
                        return false;
                }
                try {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query->delete('#__discuss_customfields_rule')
                                ->where('field_id = ' . (int) $id);
                        $db->setQuery($query);
                        $db->execute();
                        return true;
                } catch (Exception $ex) {
                        return false;
                }
        }

}

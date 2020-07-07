<?php

/**
 * @package		EasyDiscuss
 * @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *
 * EasyDiscuss is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Restricted access');

require_once dirname(__FILE__) . '/model.php';

class EasyDiscussModelAssigned extends EasyDiscussModel
{

        /**
         * Category total
         *
         * @var integer
         */
        private $_total = null;

        /**
         * Pagination object
         *
         * @var object
         */
        private $_pagination = null;

        /**
         * Category data array
         *
         * @var array
         */
        private $_data = null;

        public function __construct()
        {
                parent::__construct();
                $app = JFactory::getApplication();
                $limit = ($app->getCfg('list_limit') == 0) ? 5 : DiscussHelper::getListLimit();
                $limitstart = JRequest::getVar('limitstart', 0, 'REQUEST');

                // In case limit has been changed, adjust it
                $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

                $this->setState('limit', $limit);
                $this->setState('limitstart', $limitstart);
        }

        /**
         * Method to get a pagination object for the categories
         *
         * @access public
         * @return object
         */
        public function getPagination()
        {
                return $this->_pagination;
        }

        /**
         * Method to get an array of post assigned to
         *
         * @access public
         * @return array
         */
        public function _buildQuery($userid = null)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $date = DiscussHelper::getDate();

                if (is_null($userid))
                {
                        $userid = JFactory::getUser()->id;
                }

                $nowSql = $date->toMySQL();
                $nullDate = $db->getNullDate();
                $query->select('DATEDIFF( ' . $db->quote($nowSql) . ', a.created) AS noofdays')
                        ->select('DATEDIFF(' . $db->quote($nowSql) . ', IF(a.replied = ' . $db->quote($nullDate) . ', a.created, a.replied) ) AS daydiff')
                        ->select('TIMEDIFF(' . $db->quote($nowSql) . ', IF(a.replied = ' . $db->quote($nullDate) . ', a.created, a.replied) ) AS timediff')
                        ->select('a.*, COUNT(c.id) AS num_replies, e.title AS category')
                        ->select('pt.suffix AS post_type_suffix, pt.title AS post_type_title')
                        ->select('IF(a.replied = ' . $db->Quote($nullDate) . ', a.created, a.replied) AS lastupdate');

                $query->from('#__discuss_posts AS a')
                        ->leftJoin('#__discuss_posts AS c ON c.parent_id = a.id AND c.published = 1')
                        ->leftJoin('#__discuss_category AS e ON e.id = a.category_id')
                        ->leftJoin('#__discuss_assignment_map AS am ON am.post_id = a.id')
                        ->leftJoin('#__discuss_post_types AS pt ON a.post_type = pt.alias');

                $subQuery = $db->getQuery(true);
                $subQuery->select('MAX(created)')
                        ->from('#__discuss_assignment_map')
                        ->where('post_id = a.id');

                $query->where('am.created = (' . $subQuery . ')')
                        ->where('am.assignee_id = ' . (int) $db->quote($userid))
                        ->where('a.parent_id = 0')
                        ->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')')
                        ->group('am.created');
                return $query;
        }

        /**
         * Method get total assigned post by user id
         * @since 3.2.9600
         * @return integer
         */
        public function getTotalAssigned($userId = null)
        {
                if (is_null($userId))
                {
                        $userId = JFactory::getUser()->id;
                }
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_posts AS a')
                        ->leftJoin('#__discuss_assignment_map AS b ON b.post_id = a.id')
                        ->where('b.assignee_id = ' . (int) $userId)
                        ->where('a.parent_id = 0')
                        ->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                $db->setQuery($query);
                $total = $db->loadResult();
                if (!$total)
                {
                        return 0;
                }
                return (int) $total;
        }

        /**
         * Method get total solved post by user
         * @param integer $userId
         * @return int
         * @since 3.2.9600
         */
        public function getTotalSolved($userId = null)
        {
                if (is_null($userId))
                {
                        $userId = JFactory::getUser()->id;
                }

                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_posts AS a')
                        ->leftJoin('#__discuss_assignment_map AS b ON b.post_id = a.id')
                        ->where('b.assignee_id = ' . (int) $userId)
                        ->where('a.parent_id = 0')
                        ->where('a.isresolve = 1')
                        ->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                $db->setQuery($query);
                $total = $db->loadResult();
                if (!$total)
                {
                        return 0;
                }
                return (int) $total;
        }

        /**
         * Method count all unresolverd post by user
         * @param integer $userId
         * @return int
         * @since 3.2.9600
         */
        public function getTotalUnresolved($userId = null)
        {
                if (is_null($userId))
                {
                        $userId = JFactory::getUser()->id;
                }

                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_posts AS a')
                        ->leftJoin('#__discuss_assignment_map AS b ON b.post_id = a.id')
                        ->where('b.assignee_id = ' . (int) $userId)
                        ->where('a.parent_id = 0')
                        ->where('a.isresolve = 0')
                        ->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                $db->setQuery($query);
                $total = $db->loadResult();
                if (!$total)
                {
                        return 0;
                }
                return (int) $total;
        }

        public function getPosts()
        {
                if (empty($this->_data))
                {
                        $query = $this->_buildQuery();
                        $limitstart = $this->getState('limitstart');
                        $limit = $this->getState('limit');
                        $this->_data = $this->_getList($query, $limitstart, $limit);
                }

                return $this->_data;
        }

        /**
         * Method to get the number of post assigned to
         *
         * @access public
         * @return integer
         */
        public function getPostCount()
        {

        }

}

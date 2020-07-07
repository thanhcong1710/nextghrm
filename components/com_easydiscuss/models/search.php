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

class EasyDiscussModelSearch extends EasyDiscussModel
{

        /**
         * Post total
         *
         * @var integer
         */
        public $_total = null;

        /**
         * Pagination object
         *
         * @var object
         */
        public $_pagination = null;

        /**
         * Post data array
         *
         * @var array
         */
        public $_data = null;

        /**
         * Parent ID
         *
         * @var integer
         */
        private $_parent = null;
        private $_isaccept = null;

        public function __construct($config = array())
        {
                parent::__construct($config);

                $mainframe = JFactory::getApplication();
                $limit = $mainframe->getUserStateFromRequest('com_easydiscuss.search.limit', 'limit', DiscussHelper::getListLimit(), 'int');
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
        public function getTotal($sort, $filter, $category = '', $featuredOnly = 'all')
        {
                $db = DiscussHelper::getDBO();


                // Lets load the content if it doesn't already exist
                if (empty($this->_total))
                {
                        $query = $this->_buildQuery($sort, $filter, $category, true);
                        $db->setQuery($query);
                        $count = $db->loadResult();
                        $this->_total = ($count) ? $count : '0';
                }

                return $this->_total;
        }

        /**
         * Method to get a pagination object for the posts
         *
         * @access public
         * @return integer
         */
        public function getPagination($parent_id = 0, $sort = 'latest', $filter = '', $category = '', $featuredOnly = 'all')
        {
                $this->_parent = $parent_id;

                // Lets load the content if it doesn't already exist
                if (empty($this->_pagination))
                {
                        $this->_pagination = DiscussHelper::getPagination($this->getTotal($sort, $filter, $category, $featuredOnly), $this->getState('limitstart'), $this->getState('limit'));
                }

                return $this->_pagination;
        }

        /**
         * Method to build the query for the tags
         *
         * @access private
         * @return string
         */
        private function _buildQuery($sort = 'latest', $filter = '', $category = '', $isCountOnly = false)
        {
                $db = JFactory::getDbo();
                $nowSQL = $db->quote(JFactory::getDate()->toSql());
                $nullDateSQL = $db->quote($db->getNullDate());
                $languageTag = JFactory::getLanguage()->getTag();
                // Get the WHERE and ORDER BY clauses for the query
                if (empty($this->_parent))
                {
                        $parent_id = JRequest::getInt('parent_id', 0);
                        $this->_parent = $parent_id;
                }

                $excludeCats = DiscussHelper::getPrivateCategories();
                // // Posts
                $pquery = $db->getQuery(true);
                $pquery->select('DATEDIFF(' . $nowSQL . ', a.created ) AS noofdays')
                        ->select('DATEDIFF(' . $nowSQL . ', IF(a.replied = ' . $nullDateSQL . ', a.created, a.replied) ) AS daydiff')
                        ->select('TIMEDIFF(' . $nowSQL . ', IF(a.replied = ' . $nullDateSQL . ', a.created, a.replied) ) AS timediff');

                $pquery_select = $db->quote('posts') . ' AS itemtype,';
                $pquery_select .= ' a.id, a.title, a.content, a.user_id, a.category_id, a.parent_id, a.user_type, a.created AS created, a.poster_name,';
                $pquery_select .= ' b.title AS category, a.password, a.featured AS featured, a.islock AS islock, a.isresolve AS isresolve,';
                $pquery_select .= ' IF(a.replied = ' . $nullDateSQL . ', a.created, a.replied) AS lastupdate,';
                $pquery_select .= ' a.legacy, pt.suffix AS post_type_suffix, pt.title AS post_type_title';
                $pquery->select($pquery_select)
                        ->from('#__discuss_posts AS a')
                        ->leftJoin('#__discuss_category AS b ON a.category_id = b.id')
                        ->leftJoin('#__discuss_post_types AS pt ON a.post_type = pt.alias')
                        ->where($this->_buildQueryWhere('posts', 'a', $category))
                        ->where('a.language IN (' . $db->quote($languageTag) . ',' . $db->quote('*') . ')');

                if (!empty($excludeCats))
                {
                        $pquery->where('a.category_id NOT IN (' . implode(',', $excludeCats) . ')');
                }

                // Replies
                $rquery = $db->getQuery(true);
                $rquery->select('DATEDIFF(' . $nowSQL . ', a.created ) AS noofdays')
                        ->select('DATEDIFF(' . $nowSQL . ', IF(a.replied = ' . $nullDateSQL . ', a.created, a.replied) ) AS daydiff')
                        ->select('TIMEDIFF(' . $nowSQL . ', IF(a.replied = ' . $nullDateSQL . ', a.created, a.replied) ) AS timediff');

                $rquery_select = $db->quote('replies') . ' AS itemtype,';
                $rquery_select .= ' a.id, a.title, a.content, a.user_id, a.category_id, a.parent_id, a.user_type, a.created AS created, a.poster_name,';
                $rquery_select .= ' b.title AS category, a.password, a.featured AS featured, a.islock AS islock, a.isresolve AS isresolve,';
                $rquery_select .= ' IF(a.replied = ' . $nullDateSQL . ', a.created, a.replied) AS lastupdate,';
                $rquery_select .= ' a.legacy, ' . $db->quote('') . ' AS post_type_suffix, ' . $db->quote('') . ' AS post_type_title';
                $rquery->select($rquery_select)
                        ->from('#__discuss_posts AS a')
                        ->leftJoin('#__discuss_category AS b ON a.category_id = b.id')
                        ->where($this->_buildQueryWhere('replies', 'a', $category))
                        ->where('a.language IN (' . $db->quote($languageTag) . ',' . $db->quote('*') . ')');

                if (!empty($excludeCats))
                {
                        $rquery->where('a.category_id NOT IN (' . implode(',', $excludeCats) . ')');
                }

                // Categories
                $cquery = $db->getQuery(true);
                $cquery->select('0 AS noofdays, 0 AS daydiff')
                        ->select($db->quote('00:00:00') . ' AS timediff')
                        ->select($db->quote('category') . ' AS itemtype');

                $cquery_select = 'a.id, a.title, a.description as content, a.created_by as user_id, a.id AS category_id, 0 AS parent_id, 0 AS user_type, a.created AS created, 0 AS poster_name,';
                $cquery_select .= ' a.title AS category, 0 AS password,0 AS featured, 0 AS islock , 0 AS isresolve,';
                $cquery_select .= ' a.created AS lastupdate,';
                $cquery_select .= ' 1 AS legacy, ' . $db->quote('') . ' AS post_type_suffix, ' . $db->quote('') . ' AS post_type_title';
                $cquery->select($cquery_select)
                        ->from('#__discuss_category AS a')
                        ->where($this->_buildQueryWhere('category', 'a', $category))
                        ->where('a.language IN (' . $db->quote($languageTag) . ',' . $db->quote('*') . ')');

                if (!empty($excludeCats))
                {
                        $cquery->where('a.id NOT IN (' . implode(',', $excludeCats) . ')');
                }

                $cquery->union($pquery)
                        ->union($rquery);

                if ($isCountOnly)
                {
                        $query = $db->getQuery(true);
                        $query->select('COUNT(*)')
                                ->from('(' . $cquery . ') AS x');
                        return $query;
                } else
                {
                        $query = $db->getQuery(true);
                        $query->select('*')
                                ->from('(' . $cquery . ') AS x');
                        $query->order('x.lastupdate DESC');
                        return $query;
                }
        }

        private function _buildQueryWhere($type, $tbl, $categoryId)
        {
                $db = JFactory::getDbo();
                $search = JRequest::getString('query', '');
                $phrase = 'all';
                $where = array();
                $extra = array();

                $where[] = $tbl . '.published = 1';

                if ($type == 'posts')
                {
                        $where[] = $tbl . '.parent_id = 0';
                }

                if ($type == 'replies')
                {
                        $where[] = $tbl . '.parent_id != 0';
                }

                // Private discussions should not show up
                $where[] = $tbl . '.private = 0';

                if ($type == 'posts' || $type == 'replies')
                {
                        if (!empty($categoryId))
                        {
                                $where[] = $tbl . '.category_id = ' . $db->quote($categoryId);
                        }

                        $words = explode(' ', $search);
                        $wheres = array();
                        foreach ($words as $word)
                        {
                                $word = $db->quote('%' . $db->escape($word, true) . '%', false);
                                $wheres2 = array();

                                if ($type == 'posts')
                                {
                                        $wheres2[] = $tbl . '.title LIKE ' . $word;
                                }
                                $wheres2[] = $tbl . '.content LIKE ' . $word;
                                $wheres[] = implode(' OR ', $wheres2);
                        }
                        $whereString = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
                        $where[] = '(' . $whereString . ')';
                } else if ($type == 'category')
                {
                        if (!empty($categoryId))
                        {
                                $where[] = $tbl . '.id = ' . $db->quote($categoryId);
                        }

                        $extra[] = $tbl . '.title LIKE ' . $db->quote('%' . $db->escape($search, true) . '%', false);
                        $extra = '(' . implode(') OR (', $extra) . ')';
                        $where[] = '(' . $extra . ')';
                }

                return $where;
        }

        private function _buildQueryOrderBy()
        {
                $mainframe = JFactory::getApplication();
                $filter_order = $mainframe->getUserStateFromRequest('com_easydiscuss.search.filter_order', 'filter_order', 'created DESC', 'cmd');
                $filter_order_Dir = $mainframe->getUserStateFromRequest('com_easydiscuss.search.filter_order_Dir', 'filter_order_Dir', '', 'word');
                $orderby = $filter_order . ' ' . $filter_order_Dir;
                return $orderby;
        }

        /**
         * Method to get posts item data
         *
         * @access public
         * @return array
         */
        public function getData($usePagination = true, $sort = 'latest', $limitstart = null, $filter = '', $category = '', $limit = null)
        {
                if (empty($this->_data))
                {
                        $query = $this->_buildQuery($sort, $filter, $category);

                        if ($usePagination)
                        {
                                $limitstart = is_null($limitstart) ? $this->getState('limitstart') : $limitstart;
                                $limit = is_null($limit) ? $this->getState('limit') : $limit;
                                $this->_data = $this->_getList($query, $limitstart, $limit);
                        } else
                        {
                                $limit = is_null($limit) ? $this->getState('limit') : $limit;
                                $this->_data = $this->_getList($query, 0, $limit);
                        }
                }

                return $this->_data;
        }

        public function clearData()
        {
                $this->_data = null;
        }

}

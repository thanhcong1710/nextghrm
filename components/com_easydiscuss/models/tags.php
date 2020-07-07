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

class EasyDiscussModelTags extends EasyDiscussModel
{

        /**
         * Tag total
         *
         * @var integer
         */
        var $_total = null;

        /**
         * Pagination object
         *
         * @var object
         */
        var $_pagination = null;

        /**
         * Tag data array
         *
         * @var array
         */
        var $_data = null;

        function __construct()
        {
                parent::__construct();
                $app = JFactory::getApplication();
                $limit = $app->getUserStateFromRequest('com_easydiscuss.tags.limit', 'limit', DiscussHelper::getListLimit(), 'int');
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
        function getTotal()
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
        function getPagination()
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
         * Method to build the query for the tags
         *
         * @access private
         * @return string
         */
        function _buildQuery()
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('*')
                        ->from('#__discuss_tags')
                        ->where($this->_buildQueryWhere())
                        ->order($this->_buildQueryOrderBy());
                return $query;
        }

        function _buildQueryWhere()
        {
                $app = JFactory::getApplication();
                $db = JFactory::getDbo();
                $filter_state = $app->getUserStateFromRequest('com_easydiscuss.tags.filter_state', 'filter_state', '', 'word');
                $search = $app->getUserStateFromRequest('com_easydiscuss.tags.search', 'search', '', 'string');
                $search = $db->getEscaped(trim(JString::strtolower($search)));
                $where = array();
                if ($filter_state)
                {
                        if ($filter_state == 'P')
                        {
                                $where[] = 'published = 1';
                        } else if ($filter_state == 'U')
                        {
                                $where[] = 'published = 0';
                        }
                }

                if ($search)
                {
                        $where[] = 'LOWER( title ) LIKE \'%' . $search . '%\' ';
                }
                $where[] = 'language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')';
                return $where;
        }

        function _buildQueryOrderBy()
        {
                $app = JFactory::getApplication();
                $filter_order = $app->getUserStateFromRequest('com_easydiscuss.tags.filter_order', 'filter_order', 'title ASC', 'cmd');
                $filter_order_Dir = $app->getUserStateFromRequest('com_easydiscuss.tags.filter_order_Dir', 'filter_order_Dir', '', 'word');
                $orderby = $filter_order . ' ' . $filter_order_Dir;
                return $orderby;
        }

        /**
         * Method to get categories item data
         *
         * @access public
         * @return array
         */
        function getData()
        {
                // Lets load the content if it doesn't already exist
                if (empty($this->_data))
                {
                        $query = $this->_buildQuery();
                        $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
                }

                return $this->_data;
        }

        /**
         * Method to publish or unpublish tags
         *
         * @access public
         * @return array
         */
        function publish($tags = array(), $publish = 1)
        {
                if (count($tags) > 0)
                {
                        try {
                                $db = JFactory::getDbo();
                                $query = $db->getQuery(true);
                                $query->update('#__discuss_tags')
                                        ->set('published = ' . $db->quote($publish))
                                        ->where('id IN (' . implode(',', $tags) . ')');
                                $db->setQuery($query);
                                $db->execute();
                        } catch (Exception $ex) {
                                return false;
                        }
                        return true;
                }
                return false;
        }

        function searchTag($title)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('id')
                        ->from('#__discuss_tags')
                        ->where('title = ' . $db->quote($title))
                        ->where('language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                $db->setQuery($query, 0, 1);
                return $db->loadObject();
        }

        function getTagName($id)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('title')
                        ->from('#__discuss_tags')
                        ->where('id = ' . (int) $id)
                        ->where('language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                $db->setQuery($query, 0, 1);
                return $db->loadResult();
        }

        function getTagNames($ids)
        {
                $names = array();
                foreach ($ids as $id)
                {
                        $names[] = $this->getTagName($id);
                }
                $names = implode(' + ', $names);
                return $names;
        }

        /**
         * Method to get total tags created so far iregardless the status.
         *
         * @access public
         * @return integer
         */
        function getTotalTags($userId = 0)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_tags');
                if (!empty($userId))
                {
                        $query->where('user_id = ' . (int) $userId);
                }
                $query->where('language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                $db->setQuery($query);
                $result = $db->loadResult();
                return (empty($result)) ? 0 : $result;
        }

        function isExist($tagName, $excludeTagIds = '0')
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_tags')
                        ->where('title = ' . $db->quote($tagName))
                        ->where('language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                if ($excludeTagIds != '0')
                {
                        $query->where('id != ' . $db->quote($excludeTagIds));
                }

                $db->setQuery($query);
                $result = $db->loadResult();
                return (empty($result)) ? 0 : $result;
        }

        function getTagCloud($limit = '', $order = 'title', $sort = 'asc', $userId = '')
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('a.id, a.title, a.alias, a.created, COUNT(c.id) AS post_count')
                        ->from('#__discuss_tags AS a')
                        ->leftJoin('#__discuss_posts_tags AS b ON a.id = b.tag_id')
                        ->leftJoin('#__discuss_posts AS c ON b.post_id = c.id AND c.published = 1 AND c.private = 0');
                $exclude = DiscussHelper::getPrivateCategories();
                if (!empty($exclude))
                {
                        $query->where('c.category_id NOT IN(' . implode(',', $exclude) . ')');
                }

                $query->where('a.published = 1')
                        ->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');

                if (!empty($userId))
                {
                        $query->where('a.user_id = ' . (int) $userId);
                }

                $query->group('a.id');

                //order
                switch ($order) {
                        case 'postcount':
                                $order = 'post_count';
                                break;
                        case 'title':
                        default:
                                $order = 'title';
                }

                //sort
                switch ($sort) {
                        case 'asc':
                                $order .= ' ASC ';
                                break;
                        case 'desc':
                        default:
                                $order .= ' DESC ';
                }

                $query->order($order);

                //limit
                if (empty($limit))
                {
                        $limit = 0;
                }
                $db->setQuery($query, 0, $limit);
                return $db->loadObjectList();
        }

        function getTags($count = "")
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('id, title, alias')
                        ->from('#__discuss_tags')
                        ->where('published = 1')
                        ->where('language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')')
                        ->order('title');
                $limit = 0;
                if (!empty($count))
                {
                        $limit = $count;
                }

                $db->setQuery($query, 0, $limit);
                return $db->loadObjectList();
        }

}

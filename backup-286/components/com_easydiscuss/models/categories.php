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

class EasyDiscussModelCategories extends EasyDiscussModel
{

        /**
         * Category total
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
         * Category data array
         *
         * @var array
         */
        var $_data = null;
        var $endResults = array();

        function __construct()
        {
                parent::__construct();
                $app = JFactory::getApplication();
                $limit = $app->getUserStateFromRequest('com_easydiscuss.categories.limit', 'limit', DiscussHelper::getListLimit(), 'int');
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
        function _buildQuery($options = array())
        {
                // Get the WHERE and ORDER BY clauses for the query
                $where = $this->_buildQueryWhere($options);
                $orderby = $this->_buildQueryOrderBy($options);
                $db = JFactory::getDbo();
                $query = $db->getQuery();
                $query->select('*')
                        ->from('#__discuss_category')
                        ->where($where)
                        ->order($orderby);
                return $query;
        }

        function _buildQueryWhere($options = array())
        {
                $app = JFactory::getApplication();
                $db = JFactory::getDbo();
                $filter_state = $app->getUserStateFromRequest('com_easydiscuss.categories.filter_state', 'filter_state', '', 'word');
                $search = $app->getUserStateFromRequest('com_easydiscuss.categories.search', 'search', '', 'string');
                $search = $db->getEscaped(trim(JString::strtolower($search)));

                if (isset($options['published']) && $options['published'] == true)
                {
                        $filter_state = 'P';
                }

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

        function _buildQueryOrderBy($options = array())
        {
                $app = JFactory::getApplication();
                $filter_order = $app->getUserStateFromRequest('com_easydiscuss.categories.filter_order', 'filter_order', 'lft', 'cmd');
                $filter_order_Dir = $app->getUserStateFromRequest('com_easydiscuss.categories.filter_order_Dir', 'filter_order_Dir', '', 'word');
                return $filter_order . ' ' . $filter_order_Dir . ', ordering';
        }

        /**
         * Method to get categories item data
         *
         * @access public
         * @return array
         */
        function getData($usePagination = true, $options = array())
        {
                // Lets load the content if it doesn't already exist
                if (empty($this->_data))
                {
                        $query = $this->_buildQuery($options);

                        if ($usePagination)
                                $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
                        else
                                $this->_data = $this->_getList($query);
                }
                return $this->_data;
        }

        /**
         * Method to publish or unpublish categories
         *
         * @access public
         * @return array
         */
        function publish($categories = array(), $publish = 1)
        {
                if (count($categories) > 0)
                {
                        try {
                                $db = JFactory::getDbo();
                                $query = $db->getQuery(true);
                                $query->update('#__discuss_category')
                                        ->set('published = ' . $db->quote($publish))
                                        ->where('id IN (' . implode(',', $categories) . ')');

                                $db->setQuery($query);
                                $db->execute();
                                return true;
                        } catch (Exception $ex) {
                                return false;
                        }
                }
                return false;
        }

        /**
         * Returns the number of discussion created within this category.
         *
         * @return int	$result	The total count of entries.
         * @param boolean	$published	Whether to filter by published.
         */
        function getUsedCount($categoryId, $published = false)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_posts')
                        ->where('category_id = ' . (int) $categoryId);

                if ($published)
                {
                        $query->where('published = 1');
                }

                $query->where('language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                $db->setQuery($query);
                return $db->loadResult();
        }

        public function getCategoryTree($sortParentChild = true)
        {
                $db = JFactory::getDbo();
                $config = DiscussHelper::getConfig();
                $query = $db->getQuery(true);
                $query->select('a.*, COUNT(b.id) -1 AS depth')
                        ->from('#__discuss_category AS a')
                        ->innerJoin('#__discuss_category AS b')
                        ->where('a.published = ' . $db->quote(DISCUSS_ID_PUBLISHED))
                        ->where('(a.lft BETWEEN b.lft AND b.rgt)')
                        ->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');

                // get all private categories id
                $excludeCats = DiscussHelper::getPrivateCategories();
                if (!empty($excludeCats))
                {
                        $query->where('a.id NOT IN (' . implode(',', $excludeCats) . ')');
                }
                $query->group('a.id');

                if (!$config->get('layout_show_all_subcategories'))
                {
                        $query->having('depth = 0');
                }

                $sortConfig = $config->get('layout_ordering_category', 'latest');
                switch ($sortConfig) {
                        case 'alphabet' :
                                $orderBy = 'a.title';
                                break;
                        case 'ordering' :
                                $orderBy = 'a.lft';
                                break;
                        case 'latest' :
                                $orderBy = 'a.created';
                                break;
                        default :
                                $orderBy = 'a.lft';
                                break;
                }
                $query->order($orderBy . ' ' . $config->get('layout_sort_category', 'asc'));

                $db->setQuery($query);
                $rows = $db->loadObjectList();
                $total = count($rows);
                $categories = array();
                for ($i = 0; $i < $total; $i++)
                {
                        $category = DiscussHelper::getTable('Category');
                        $category->bind($rows[$i]);
                        $category->depth = $rows[$i]->depth;
                        $categories[] = $category;
                }

                if ($sortParentChild && ( $sortConfig == 'alphabet' || $sortConfig == 'latest' ))
                {
                        $cats = array();
                        $groups = array();

                        foreach ($categories as $row)
                        {
                                $cats[$row->parent_id][] = $row;
                        }

                        $this->sortAlpha($groups, $cats, 0);
                        $categories = $groups;
                }

                return $categories;
        }

        private function sortAlpha(&$groups, $cats, $parent_id)
        {
                if (!empty($cats[$parent_id]))
                {
                        foreach ($cats[$parent_id] as $row)
                        {
                                $groups[] = $row;
                                $this->sortAlpha($groups, $cats, $row->id);
                        }
                }
        }

        /**
         * Retrieves a list of categories from the site.
         *
         * @since	3.0
         * @access	public
         * @param	int 	If there's a parent id provided, it would load sub categories.
         */
        public function getCategories($options = array())
        {
                // Legacy
                if (!is_array($options))
                {
                        $parent_id = $options;
                        $options = array('parent_id' => $parent_id);
                }

                $default = array(
                        'acl_type' => DISCUSS_CATEGORY_ACL_ACTION_VIEW,
                        'bind_table' => true,
                        'parent_id' => 0
                );

                $options += $default;
                $db = JFactory::getDbo();
                $my = JFactory::getUser();

                $query = $db->getQuery(true);
                $query->select('*')
                        ->from('#__discuss_category')
                        ->where('parent_id = ' . $db->quote($options['parent_id']))
                        ->where('published = 1')
                        ->where('language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');

                if ($my->id == 0)
                {
                        $query->where('private != 1');
                }

                //check categories acl here.
                $catIds = DiscussHelper::getAclCategories($options['acl_type'], $my->id, $options['parent_id']);

                if (count($catIds) > 0)
                {
                        $strIds = '';
                        foreach ($catIds as $cat)
                        {
                                $strIds = ( empty($strIds) ) ? $cat->id : $strIds . ', ' . $cat->id;
                        }

                        $query->where('id NOT IN (' . $strIds . ')');
                }

                $query->order($db->quoteName('lft'));
                $db->setQuery($query);
                $rows = $db->loadObjectList();

                if ($options['bind_table'])
                {
                        $total = count($rows);
                        $categories = array();

                        for ($i = 0; $i < $total; $i++)
                        {
                                $ignore['alias'] = true;

                                $category = DiscussHelper::getTable('Category');
                                $category->bind($rows[$i], $ignore);

                                $categories[] = $category;
                        }
                        return $categories;
                }

                return $rows;
        }

        function getParentCategories($contentId, $type = 'all', $isPublishedOnly = false, $showPrivateCat = true)
        {
                $db = JFactory::getDbo();
                $config = DiscussHelper::getConfig();
                $app = JFactory::getApplication();
                $sortConfig = $config->get('layout_ordering_category', 'latest');

                $query = $db->getQuery(true);
                $query->select('a.id, a.title, a.alias, a.private, a.' . $db->quoteName('default') . ', a.container')
                        ->from('#__discuss_category AS a')
                        ->where('a.parent_id = 0');

                if ($app->isSite())
                {
                        $query->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                }

                if ($type == 'poster')
                {
                        $query->where('a.created_by = ' . (int) $contentId);
                } else if ($type == 'category')
                {
                        $query->where('a.id = ' . (int) $contentId);
                }

                if ($isPublishedOnly)
                {
                        $query->where('a.published = 1');
                }

                if (!$app->isAdmin())
                {
                        // we do not need to see the privacy when user accessing category via backend because only admin can access it.
                        // in a way, we do not resttict for admin.
                        //check categories acl here.
                        $catIds = DiscussHelper::getAccessibleCategories('0', DISCUSS_CATEGORY_ACL_ACTION_SELECT);

                        if (count($catIds) > 0)
                        {
                                $strIds = '';
                                foreach ($catIds as $cat)
                                {
                                        $strIds = ( empty($strIds) ) ? $cat->id : $strIds . ', ' . $cat->id;
                                }

                                if (count($catIds) == 1)
                                {
                                        $query->where('a.id = ' . (int) $strIds);
                                } else
                                {
                                        $query->where('a.id IN (' . $strIds . ')');
                                }
                        }
                }

                switch ($sortConfig) {
                        case 'alphabet' :
                                $orderBy = 'a.title';
                                break;
                        case 'ordering' :
                                $orderBy = 'a.lft';
                                break;
                        case 'latest' :
                                $orderBy = 'a.created';
                                break;
                        default :
                                $orderBy = 'a.lft';
                                break;
                }

                $query->order($orderBy . ' ' . $config->get('layout_sort_category', 'ASC'));
                $db->setQuery($query);
                return $db->loadObjectList();
        }

        /**
         * Method get all child items
         * @param integer $parentId
         * @return array
         */
        public function getChildIds($parentId = 0)
        {
                return DiscussHelper::getHelper('Category')->getChildIds($parentId);
        }

        /**
         * Method get all child categories
         * @param integer $parentId
         * @param boolean $isPublishedOnly
         * @param boolean $includePrivate
         * @return array
         */
        public function getChildCategories($parentId, $isPublishedOnly = false, $includePrivate = true)
        {
                return DiscussHelper::getHelper('Category')->getChildCategories($parentId, $isPublishedOnly, $includePrivate);
        }

        /**
         * Method get all private categories
         * @return array
         * @since 3.2.9600
         */
        function getPrivateCategories()
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('a.id')
                        ->from('#__discuss_category AS a')
                        ->where('a.private = 1')
                        ->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                $db->setQuery($query);
                return $db->loadObjectList();
        }

        /**
         * Method count all child of category
         * @param integer $categoryId
         * @param integer $published
         * @return integer
         * @since 3.2.9600
         */
        function getChildCount($categoryId, $published = false)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_category')
                        ->where('parent_id = ' . (int) $categoryId)
                        ->where('language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                if ($published)
                {
                        $query->where('published = 1');
                }
                $db->setQuery($query);
                return $db->loadResult();
        }

}

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

class EasyDiscussModelCategory extends EasyDiscussModel
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
                $limit = (JFactory::getApplication()->getCfg('list_limit') == 0) ? 5 : DiscussHelper::getListLimit();

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
         * @return integer
         */
        public function getPagination()
        {
                return $this->_pagination;
        }

        protected function _getParentIdsWithPost()
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('*')
                        ->from('#__discuss_category')
                        ->where('published = 1')
                        ->where('parent_id = 0')
                        ->where('language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                $my = JFactory::getUser();
                if ($my->id == 0)
                {
                        $query->where('private = 0');
                }

                $db->setQuery($query);
                $result = $db->loadObjectList();
                $validCat = array();

                if (count($result) > 0)
                {
                        for ($i = 0; $i < count($result); $i++)
                        {
                                $item = & $result[$i];

                                $item->childs = null;
                                DiscussHelper::buildNestedCategories($item->id, $item);

                                $catIds = array();
                                $catIds[] = $item->id;
                                DiscussHelper::accessNestedCategoriesId($item, $catIds);

                                $item->cnt = $this->getTotalPostCount($catIds);

                                if ($item->cnt > 0)
                                {
                                        $validCat[] = $item->id;
                                }
                        }
                }

                return $validCat;
        }

        /*
         * Retrieves the default category
         */

        public function getDefaultCategory()
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('*')
                        ->from('#__discuss_category')
                        ->where($db->quoteName('default') . ' = 1')
                        ->where('language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                $db->setQuery($query);
                $result = $db->loadObject();
                if (!$result)
                {
                        return false;
                }
                $category = DiscussHelper::getTable('Category');
                $category->bind($result);
                return $category;
        }

        public function getCategories($sort = 'latest', $hideEmptyPost = true, $limit = 0)
        {
                $db = JFactory::getDbo();
                $languageTag = JFactory::getLanguage()->getTag();
                $andWhere = array();
                $andWhere[] = 'a.published = 1';
                $andWhere[] = 'a.parent_id = 0';
                $andWhere[] = 'a.language IN (' . $db->quote($languageTag) . ',' . $db->quote('*') . ')';

                $my = JFactory::getUser();
                if ($my->id == 0)
                {
                        $andWhere[] = 'a.private = 0';
                }

                if ($hideEmptyPost)
                {
                        $arrParentIds = $this->_getParentIdsWithPost();
                        if (!empty($arrParentIds))
                        {
                                $andWhere[] = 'a.id IN (' . implode(',', $arrParentIds) . ')';
                        }

                        if ($my->id == 0)
                        {
                                $andWhere[] = 'a.private = 0';
                        }
                        $this->_total = count($arrParentIds);
                } else
                {
                        $subQuery = $db->getQuery(true);
                        $subQuery->select('a.id')
                                ->from('#__discuss_category AS a')
                                ->leftJoin('#__discuss_posts AS b ON a.id = b.category_id AND b.published = 1');
                        $subQuery->where($andWhere);
                        $subQuery->where('b.language IN (' . $db->quote($languageTag) . ',' . $db->quote('*') . ')');
                        $subQuery->group('a.id');
                        $db->setQuery($subQuery);
                        $result = $db->loadAssocList(null, 'id');
                        $this->_total = count($result);
                        if ($db->getErrorNum())
                        {
                                JError::raiseError(500, $db->stderr());
                        }
                }

                $limit = ($limit == 0) ? $this->getState('limit') : $limit;
                $limitstart = $this->getState('limitstart');
                if (empty($this->_pagination))
                {
                        jimport('joomla.html.pagination');
                        $this->_pagination = new JPagination($this->_total, $limitstart, $limit);
                }

                $query = $db->getQuery(true);
                $query->select('a.id, a.title, a.alias, COUNT(b.id) AS cnt, a.description')
                        ->from('#__discuss_category AS a')
                        ->leftJoin('#__discuss_posts AS b ON a.id = b.category_id AND b.published = 1');
                $query->where($andWhere);
                $query->where('b.language IN (' . $db->quote($languageTag) . ',' . $db->quote('*') . ')');
                $query->group('a.id');

                switch ($sort) {
                        case 'popular' :
                                $orderBy = 'cnt DESC';
                                break;
                        case 'alphabet' :
                                $orderBy = 'a.title ASC';
                                break;
                        case 'ordering' :
                                $orderBy = 'a.ordering ASC';
                                break;
                        case 'latest' :
                        default :
                                $orderBy = 'a.created DESC';
                                break;
                }

                $query->order($orderBy);
                $db->setQuery($query, $limitstart, $limit);
                return $db->loadObjectList();
        }

        public function getTotalPostCount($catIds)
        {
                $categoryId = '';
                $isIdArray = false;
                if (is_array($catIds))
                {
                        if (count($catIds) > 1)
                        {
                                $categoryId = implode(',', $catIds);
                                $isIdArray = true;
                        } else
                        {
                                $categoryId = $catIds[0];
                        }
                } else
                {
                        $categoryId = $catIds;
                }

                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $languageTag = JFactory::getLanguage()->getTag();
                $query->select('COUNT(b.id) AS cnt')
                        ->from('#__discuss_category AS a')
                        ->leftJoin('#__discuss_posts AS b ON a.id = b.category_id AND p.published = 1')
                        ->where('a.published = 1')
                        ->where('a.language IN (' . $db->quote($languageTag) . ',' . $db->quote('*') . ')')
                        ->where('b.language IN (' . $db->quote($languageTag) . ',' . $db->quote('*') . ')');

                if ($isIdArray)
                {
                        $query->where('a.id IN (' . $categoryId . ')');
                } else
                {
                        $query->where('a.id = (' . $categoryId . ')');
                }

                $query->group('a.id')
                        ->having('(COUNT(b.id)) > 0');

                $db->setQuery($query);
                $result = $db->loadAssocList(null, 'cnt');
                if (!empty($result))
                {
                        return array_sum($result);
                } else
                {
                        return '0';
                }
        }

        /**
         * Method to get total category created so far iregardless the status.
         *
         * @access public
         * @return integer
         */
        public function getTotalCategory($userId = 0)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_category');
                $query->where('language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                if (!empty($userId))
                {
                        $query->where('created_by = ' . $db->quote($userId));
                }
                $db->setQuery($query);
                $result = $db->loadResult();
                return (empty($result)) ? 0 : $result;
        }

        public function isExist($categoryName, $excludeCatIds = '0')
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_category')
                        ->where('title = ' . $db->quote($categoryName))
                        ->where('language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                if ($excludeCatIds != '0')
                {
                        $query->where('id != ' . $db->quote($excludeCatIds));
                }
                $db->setQuery($query);
                $result = $db->loadResult();
                return (empty($result)) ? 0 : $result;
        }

}

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

class EasyDiscussModelLabels extends EasyDiscussModel
{

        protected $_total = null;
        protected $_pagination = null;
        protected $_data = null;

        public function __construct()
        {
                parent::__construct(array());
                $limit = JFactory::getApplication()->getUserStateFromRequest('com_easydiscuss.labels.limit', 'limit', DiscussHelper::getListLimit(), 'int');
                $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
                $this->setState('limit', $limit);
                $this->setState('limitstart', $limitstart);
        }

        /**
         * Method to get the total number of the labels
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
         * Method to get a pagination object for the labels
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
         * Method to build the query for the labels
         *
         * @access private
         * @return string
         */
        private function _buildQuery()
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('*')
                        ->from('#__discuss_posts_labels')
                        ->where($this->_buildQueryWhere())
                        ->order($this->_buildQueryOrderBy());
                return $query;
        }

        private function _buildQueryWhere()
        {
                $app = JFactory::getApplication();
                $db = JFactory::getDbo();
                $filter_state = $app->getUserStateFromRequest('com_easydiscuss.labels.filter_state', 'filter_state', '', 'word');
                $search = $app->getUserStateFromRequest('com_easydiscuss.labels.search', 'search', '', 'string');
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

                return $where;
        }

        private function _buildQueryOrderBy()
        {
                $app = JFactory::getApplication();
                $filter_order = $app->getUserStateFromRequest('com_easydiscuss.labels.filter_order', 'filter_order', 'ordering ASC', 'int');
                $filter_order_Dir = $app->getUserStateFromRequest('com_easydiscuss.labels.filter_order_Dir', 'filter_order_Dir', '', 'word');
                return $filter_order . ' ' . $filter_order_Dir;
        }

        /**
         * Method to get labels item data
         *
         * @access public
         * @return array
         */
        public function getData()
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
         * Method to publish or unpublish labels
         *
         * @access public
         * @return boolean
         */
        public function publish($labels = array(), $publish = true)
        {
                if (is_integer($labels))
                {
                        $labels = array($labels);
                } elseif (!is_array($labels) || count($labels) < 1)
                {
                        return false;
                }

                $publish = $publish ? 1 : 0;
                try {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query->update('#__discuss_posts_labels')
                                ->set('published = ' . $db->quote($publish))
                                ->where('id IN (' . implode(',', $labels) . ')');
                        $db->setQuery($query);
                        $db->execute();
                        return true;
                } catch (Exception $ex) {
                        return false;
                }
        }

        /**
         * Method find label by title
         * @param string $title
         * @return object
         */
        public function searchLabel($title)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('id')
                        ->from('#__discuss_posts_labels')
                        ->where('title = ' . $db->quote($title));
                $db->setQuery($query, 0, 1);
                return $db->loadObject();
        }

        /**
         * Method get label title
         * @param integer $id
         * @return string
         */
        public function getLabelTitle($id)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('title')
                        ->from('#__discuss_posts_labels')
                        ->where('id = ' . (int) $id);
                $db->setQuery($query, 0, 1);
                return $db->loadResult();
        }

        /**
         * Method to get total labels
         *
         * @access public
         * @param boolean $ignoreUnpublish default false
         * @return integer
         */
        public function getTotalLabels($ignoreUnpublish = false)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_posts_labels');

                if (!$ignoreUnpublish)
                {
                        $query->where('published = 1');
                }

                $db->setQuery($query);
                $result = $db->loadResult();
                return (empty($result)) ? 0 : $result;
        }

        /**
         * Method get all published label
         * @return array
         */
        public function getLabels()
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('id, title')
                        ->from('#__discuss_posts_labels')
                        ->where('published = 1')
                        ->order('ordering');
                $db->setQuery($query);
                return $db->loadObjectList();
        }

}

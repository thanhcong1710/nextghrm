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

class EasyDiscussModelPosts extends EasyDiscussModel
{

        public $isModule = false;

        /**
         * Post total
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
         * Post data array
         *
         * @var array
         */
        protected $_data = null;

        /**
         * Parent ID
         *
         * @var integer
         */
        protected $_parent = null;
        protected $_isaccept = null;
        protected $_favs = true;
        protected $_cache = array();
        static $_lastReply = array();

        public function __construct()
        {
                parent::__construct();
                $langue = JFactory::getApplication('site')->getLanguage()->getTag();
                $mainframe = JFactory::getApplication();
                $limit = $mainframe->getUserStateFromRequest('com_easydiscuss.posts.limit', 'limit', DiscussHelper::getListLimit());
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
        public function getTotal($sort = 'latest', $filter = '', $category = '', $featuredOnly = 'all')
        {
                $sid = serialize($sort) . serialize($filter) . serialize($category) . serialize($featuredOnly);
                if (isset($this->_cache[$sid]))
                {
                        return $this->_cache[$sid];
                } else
                {
                        $query = $this->_buildQueryTotal($sort, $filter, $category, $featuredOnly);
                        $db = JFactory::getDBO();
                        $db->setQuery($query);
                        $this->_total = $db->loadResult();
                        if ($this->_total)
                        {
                                $this->_cache[$sid] = $this->_total;
                        }
                }
                return $this->_total;
        }

        /**
         * Removes all finder indexed items for replies
         *
         * @since	3.0
         * @access	public
         * @param	string
         * @return
         */
        public function deleteRepliesInFinder($postId)
        {
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->delete('#__finder_links')
                        ->where('url LIKE (' . $db->quote('%index.php?option=com_easydiscuss&view=post&id=' . $postId . '#reply-%') . ')');
                $db->setQuery($query);
                return $db->execute();
        }

        /**
         * Method to get a pagination object for the posts
         *
         * @access public
         * @return integer
         */
        public function getPagination($parent_id = 0, $sort = 'latest', $filter = '', $category = '', $featuredOnly = 'all', $userId = '')
        {
                $this->_parent = $parent_id;
                // Lets load the content if it doesn't already exist
                if (empty($this->_pagination))
                {
                        $this->_pagination = DiscussHelper::getPagination($this->getTotal($sort, $filter, $category, $featuredOnly, $userId), $this->getState('limitstart'), $this->getState('limit'));
                }
                return $this->_pagination;
        }

        /**
         * Retrieve the total number of posts which are resolved.
         *
         * @since	3.0
         * @access	public
         */
        public function getTotalResolved()
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_posts')
                        ->where('isresolve = 1')
                        ->where('published = ' . $db->quote(DISCUSS_ID_PUBLISHED))
                        ->where('parent_id = 0')
                        ->where('private = 0')
                        ->where('language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                $db->setQuery($query);
                return $db->loadResult();
        }

        /**
         * Method to build the query for the tags
         *
         * @access private
         * @return string
         */
        private function _buildQueryTotal($sort = 'latest', $filter = '', $category = '', $featuredOnly = 'all', $reply = false, $userId = '')
        {
                // Get the WHERE and ORDER BY clauses for the query
                if (empty($this->_parent))
                {
                        $parent_id = JRequest::getInt('parent_id', 0);
                        $this->_parent = $parent_id;
                }
                $my = JFactory::getUser();
                $filteractive = (empty($filter)) ? JRequest::getString('filter', 'allposts') : $filter;
                $where = $this->_buildQueryWhere($filter, $category, $featuredOnly, array(), $userId);
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_posts AS a')
                        ->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');

                if ($filteractive == 'myreplies')
                {
                        $query->where('a.parent_id != 0 AND a.published = 1');
                }


                if ($filter == 'favourites')
                {
                        $query->leftJoin('#__discuss_favourites AS f ON f.post_id = a.id');
                }

                if ($filter == 'mine')
                {
                        $query->where('a.user_id = ' . (int) $my->id);
                }

                $query->where($where);

                if (!empty($this->_isaccept))
                {
                        $query->where('a.answered = 1');
                }

                if ($filteractive == 'unanswered')
                {
                        // Should not fetch posts which are resolved
                        $query->where('a.isresolve = 0');
                }

                if ($filter == 'assigned')
                {
                        $query->innerJoin('#__discuss_assignment_map AS am ON am.post_id = a.id AND am.assignee_id = ' . (int) $my->id);
                }

                $excludeCats = array();
                // We do not need to check for private categories for replies since replies are posted in that particular discussion.
                if (!$reply)
                {
                        $excludeCats = DiscussHelper::getPrivateCategories();
                }

                if (!empty($excludeCats))
                {
                        $query->where('a.category_id NOT IN (' . implode(',', $excludeCats) . ')');
                }

                return $query;
        }

        /**
         * Method to build the query for the tags
         *
         * @access private
         * @return string
         */
        private function _buildQuery($sort = 'latest', $filter = '', $category = '', $featuredOnly = 'all', $reply = false, $exclude = array(), $reference = null, $referenceId = null, $userId = null, $private = null)
        {
                // Get the WHERE and ORDER BY clauses for the query
                if (empty($this->_parent))
                {
                        $parent_id = JRequest::getInt('parent_id', 0);
                        $this->_parent = $parent_id;
                }

                if (isset($this->isModule) && $this->isModule == true)
                {
                        $this->_parent = 0;
                }

                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $orderby = '';

                // Include polls
                $pollsQuery = $db->getQuery(true);
                $pollsQuery->select('COUNT(*)')
                        ->from('#__discuss_polls')
                        ->where('post_id = a.id');
                $query->select('(' . $pollsQuery . ') AS polls_cnt');

                // Include favourites
                $favouritesQuery = $db->getQuery(true);
                $favouritesQuery->select('COUNT(*)')
                        ->from('#__discuss_favourites')
                        ->where('post_id = a.id');
                $query->select('(' . $favouritesQuery . ') AS totalFavourites');

                // Calculate number replies
                $numberRepliesQuery = $db->getQuery(true);
                $numberRepliesQuery->select('COUNT(*)')
                        ->from('#__discuss_posts')
                        ->where('parent_id = a.id AND published = 1');
                $query->select('(' . $numberRepliesQuery . ') AS num_replies');

                // Include attachments
                if (!$reply)
                {
                        $attachmentsQuery = $db->getQuery(true);
                        $attachmentsQuery->select('COUNT(*)')
                                ->from('#__discuss_attachments')
                                ->where('uid = a.id')
                                ->where($db->quoteName('type') . ' = ' . $db->quote(DISCUSS_QUESTION_TYPE))
                                ->where('published = 1');
                        $query->select('(' . $attachmentsQuery . ') AS attachments_cnt');
                }

                //sorting criteria
                if ($sort == 'likes')
                {
                        $query->select('a.num_likes AS likeCnt');
                }

                if ($sort == 'voted')
                {
                        $query->select('a.sum_totalvote AS VotedCnt');
                }

                $my = JFactory::getUser();
                if ($my->id != 0)
                {
                        $voteQuery = $db->getQuery(true);
                        $voteQuery->select('COUNT(*)')
                                ->from('#__discuss_votes')
                                ->where('post_id = a.id')
                                ->where('user_id = ' . (int) $my->id);
                        $query->select('(' . $voteQuery . ') AS isVoted');
                } else
                {
                        $query->select('0 AS isVoted');
                }

                $query->select('a.post_status, a.post_type, pt.suffix AS post_type_suffix, pt.title AS post_type_title ,a.*, e.title AS category, a.legacy');
                $query->select('IF(a.replied = ' . $db->quote($db->getNullDate()) . ', a.created, a.replied) AS lastupdate');

                $totalVoteQuery = $db->getQuery(true);
                $totalVoteQuery->select('COUNT(*)')
                        ->from('#__discuss_votes')
                        ->where('post_id = a.id');
                $query->select('(' . $totalVoteQuery . ') AS total_vote_cnt');

                $query->from('#__discuss_posts AS a')
                        ->leftJoin('#__discuss_post_types AS pt ON a.post_type = pt.alias')
                        ->leftJoin('#__discuss_category AS e ON a.category_id = e.id');

                if ($filter == 'favourites')
                {
                        $query->leftJoin('#__discuss_favourites AS f ON f.post_id = a.id');
                }

                if ($filter == 'assigned')
                {
                        $query->innerJoin('#__discuss_assignment_map AS am ON am.post_id = a.id AND am.assignee_id = ' . (int) $my->id);
                }

                // 3rd party integrations
                if (!is_null($reference) && !is_null($referenceId))
                {
                        $query->innerJoin('#__discuss_posts_references AS ref ON a.id = ref.post_id'
                                . ' AND ref.extension = ' . $db->quote($reference)
                                . ' AND ref.reference_id = ' . $db->quote($referenceId));
                }

                $where = $this->_buildQueryWhere($filter, $category, $featuredOnly, $exclude, $userId);
                $query->where($where);

                if ($filter == 'answer')
                {
                        $query->where('a.answered = 1');
                }

                if ($filter == 'mine')
                {
                        $query->where('a.user_id = ' . (int) $my->id);
                }

                $filteractive = (empty($filter)) ? JRequest::getString('filter', 'allposts') : $filter;
                if ($filteractive == 'unanswered')
                {
                        $query->where('a.answered = 0');
                }

                if (!EDC::isSiteAdmin() && !EDC::isModerator() && !$private && $filter != 'mine')
                {
                        $query->where('a.private = 0');
                }

                $excludeCats = array();
                // We do not need to check for private categories for replies since replies are posted in that particular discussion.
                if (!$reply)
                {
                        $excludeCats = DiscussHelper::getPrivateCategories();
                }

                if (!empty($excludeCats))
                {
                        $query->where('a.category_id NOT IN (' . implode(',', $excludeCats) . ')');
                }

                $config = DiscussHelper::getConfig();
                if ($featuredOnly && $config->get('layout_featuredpost_style') != '0' && empty($this->_parent))
                {
                        switch ($config->get('layout_featuredpost_sort', 'date_latest')) {
                                case 'date_oldest':
                                        $orderby = 'a.replied ASC'; //used in getdata only
                                        break;
                                case 'order_asc':
                                        $orderby = 'a.ordering ASC'; //used in getreplies only
                                        break;
                                case 'order_desc':
                                        $orderby = 'a.ordering DESC'; //used in getdate and getreplies
                                        break;
                                case 'date_latest':
                                default:
                                        $orderby = 'a.replied DESC'; //used in getsticky and get created date
                                        break;
                        }
                } else
                {
                        switch ($sort) {
                                case 'popular':
                                        $orderby = 'num_replies DESC, a.created DESC'; //used in getdata only
                                        break;
                                case 'hits':
                                        $orderby = 'a.hits DESC'; //used in getdata only
                                        break;
                                case 'voted':
                                        $orderby = 'a.sum_totalvote DESC'; //used in getreplies only
                                        break;
                                case 'likes':
                                        $orderby = 'a.num_likes DESC'; //used in getdate and getreplies
                                        break;
                                case 'activepost':
                                        $orderby = 'a.replied DESC'; //used in getsticky and getlastreply
                                        break;
                                case 'featured':
                                        $orderby = 'a.featured DESC, a.created DESC'; //used in getsticky and getlastreply
                                        break;
                                case 'oldest':
                                case 'replylatest':
                                        $orderby = 'a.created ASC'; //used in discussion replies
                                        break;
                                case 'latest':
                                default:
                                        $orderby = 'a.replied DESC'; //used in getsticky and get created date
                                        break;
                        }
                }

                $query->order($orderby);
                return $query;
        }

        private function _getDateDiffs(&$results)
        {
                $now = DiscussHelper::getDate();
                $today = explode(' ', $now->toMySQL());
                $today = $today[0];

                if (!empty($results))
                {
                        for ($i = 0; $i < count($results); $i++)
                        {
                                $item = & $results[$i];

                                //creation date
                                $creation = $item->created;
                                $creation = explode(' ', $creation);
                                $creation = $creation[0];

                                //daydiff
                                $datetotest = ($item->replied == '0000-00-00 00:00:00' ) ? $item->created : $item->replied;
                                $datesegment = explode(' ', $datetotest);
                                $datesegment = $datesegment[0];

                                $noofdays = floor((abs(strtotime($today) - strtotime($creation)) / (60 * 60 * 24)));
                                $daydiff = floor((abs(strtotime($today) - strtotime($datesegment)) / (60 * 60 * 24)));
                                $timediff = $this->calcTimeDiff(strtotime($now->toMySQL()), strtotime($datetotest));

                                // var_dump( $item->noofdays, $noofdays );
                                // var_dump( $item->daydiff, $daydiff );
                                // var_dump( $item->timediff, $timediff );

                                $item->noofdays = $noofdays;
                                $item->daydiff = $daydiff;
                                $item->timediff = $timediff;
                        }
                }
        }

        private function calcTimeDiff($date1, $date2)
        {
                $diff = abs($date1 - $date2);
                $seconds = 0;
                $hours = 0;
                $minutes = 0;

                if ($diff % 86400 > 0)
                {

                        $rest = ($diff % 86400);
                        $days = ($diff - $rest) / 86400;

                        if ($rest % 3600 > 0)
                        {
                                $rest1 = ($rest % 3600);
                                $hours = ($rest - $rest1) / 3600;

                                if ($rest1 % 60 > 0)
                                {
                                        $rest2 = ($rest1 % 60);
                                        $minutes = ($rest1 - $rest2) / 60;
                                        $seconds = $rest2;
                                } else
                                {
                                        $minutes = $rest1 / 60;
                                }
                        } else
                        {
                                $hours = $rest / 3600;
                        }
                } else
                {
                        $days = $diff / 86400;
                }

                $hours = ($days * 24) + $hours;
                $time = $hours . ':' . $minutes . ':' . $seconds;
                return $time;
        }

        private function _buildQueryWhere($filter = '', $category = '', $featuredOnly = 'all', $exclude = array(), $userId = '')
        {
                $db = JFactory::getDbo();
                $user_id = JRequest::getInt('user_id');
                $search = $db->escape(JRequest::getString('query', ''));
                $filteractive = (empty($filter)) ? JRequest::getString('filter', 'allposts') : $filter;
                $where = array();
                $where[] = 'a.published = 1';

                // get all posts where parent_id = 0
                if (empty($this->_parent))
                {
                        $this->_parent = '0';
                }

                if ($user_id)
                {
                        $where[] = 'a.user_id = ' . $db->quote((int) $user_id);
                }

                if ($filteractive == 'featured' || $featuredOnly === true)
                {
                        $where[] = 'a.featured = 1';
                } else if ($featuredOnly === false && $filter != 'resolved')
                {
                        $where[] = 'a.featured = 0';
                }

                if ($filteractive == 'myposts')
                {
                        $my = JFactory::getUser();
                        $where[] = 'a.user_id = ' . $db->quote($my->id);
                }

                if ($filteractive == 'userposts' && !empty($userId))
                {
                        $where[] = 'a.user_id= ' . $db->quote($userId);
                }

                if ($filteractive == 'new')
                {
                        $config = DiscussHelper::getConfig();
                        $where[] = 'DATEDIFF( ' . $db->quote(DiscussHelper::getDate()->toMySQL()) . ', a.created ) <= ' . $db->quote($config->get('layout_daystostaynew'));
                }

                if ($filteractive == 'myreplies')
                {
                        $my = JFactory::getUser();
                        $where[] = ' a.parent_id != 0 AND a.user_id = ' . $db->quote($my->id);
                }

                if (!empty($exclude))
                {
                        $excludePost = 'a.id NOT IN(';

                        for ($i = 0; $i < count($exclude); $i++)
                        {
                                $excludePost .= $db->quote($exclude[$i]);

                                if (next($exclude) !== false)
                                {
                                        $excludePost .= ',';
                                }
                        }

                        $excludePost .= ')';
                        $where[] = $excludePost;
                }

                // @since 3.0
                if ($filteractive == 'unread')
                {
                        $my = JFactory::getUser();
                        $profile = DiscussHelper::getTable('Profile');
                        $profile->load($my->id);

                        $readPosts = $profile->posts_read;
                        if ($readPosts)
                        {
                                $readPosts = unserialize($readPosts);
                                if (count($readPosts) > 1)
                                {
                                        $extraSQL = implode(',', $readPosts);
                                        $where[] = 'a.id NOT IN (' . $extraSQL . ')';
                                } else
                                {
                                        $where[] = 'a.id != ' . $db->quote($readPosts[0]);
                                }
                        }
                        $where[] = 'a.legacy = 0';
                }

                if ($filteractive == 'unanswered')
                {
                        // Should not fetch posts which are resolved
                        $where[] = 'a.isresolve = ' . $db->quote(0);
                        $where[] = 'a.created = a.replied';
                }

                if ($filteractive == 'favourites')
                {
                        $my = JFactory::getUser();

                        if (empty($userId))
                        {
                                $id = $my->id;
                        } else
                        {
                                $id = $userId;
                        }

                        $where[] = 'f.created_by = ' . $db->quote($id);
                }

                if ($filteractive == 'unresolved')
                {
                        $where[] = 'a.isresolve = 0';
                }

                // @since 3.1 resolved filter
                if ($filteractive == 'resolved')
                {
                        $where[] = 'a.isresolve = 1';
                }

                if ($this->_parent == 'allreplies')
                {
                        $where[] = 'a.parent_id != 0';

                        $excludedCategories = DiscussHelper::getPrivateCategories();

                        if (!empty($excludedCategories))
                        {
                                $where[] = 'a.category_id NOT IN (' . implode(',', $excludedCategories) . ')';
                        }
                } else
                {
                        $where[] = ' a.parent_id = ' . $db->quote($this->_parent);

                        if ($this->_isaccept)
                        {
                                $where[] = 'a.answered = 1';
                        } else
                        {
                                $where[] = 'a.answered = 0';
                        }
                }

                if ($search)
                {
                        $where[] = 'LOWER( a.title ) LIKE \'%' . $search . '%\' ';
                }

                // Filter by category
                if (!empty($category))
                {
                        require_once dirname(__FILE__) . '/categories.php';

                        if (!is_array($category))
                        {
                                $category = array($category);
                        }

                        $tmpCategoryArr = array();

                        for ($i = 0; $i < count($category); $i++)
                        {
                                $categoryId = $category[$i];

                                // Fetch all subcategories from within this category
                                $model = $this->getInstance('Categories', 'EasyDiscussModel');
                                $childs = $model->getChildIds($categoryId);

                                if ($childs)
                                {
                                        $childs[] = $categoryId;

                                        foreach ($childs as &$child)
                                        {
                                                $child = $db->quote($child);
                                                $tmpCategoryArr[] = $child;
                                        }
                                } else
                                {
                                        $tmpCategoryArr[] = $db->quote($category[$i]);
                                }
                        }

                        if (count($tmpCategoryArr) > 0)
                        {
                                $where[] = 'a.category_id IN (' . implode(',', $tmpCategoryArr) . ')';
                        }
                }
                $where[] = 'a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')';
                return $where;
        }

        /**
         * Retrieve a list of discussions
         *
         * @since	1.0
         * @param	array 	An array of options
         * the ignorePostIds must be a string when pass into this method.
         *
         */
        public function getDiscussions($options = array())
        {
                $sort = isset($options['sort']) ? $options['sort'] : 'latest';
                $pagination = isset($options['pagination']) ? $options['pagination'] : true;
                $limitstart = isset($options['limitstart']) ? $options['limitstart'] : null;
                $filter = isset($options['filter']) ? $options['filter'] : '';
                $category = isset($options['category']) ? $options['category'] : '';
                $limit = isset($options['limit']) ? $options['limit'] : null;
                $featured = isset($options['featured']) ? $options['featured'] : 'all';
                $exclude = isset($options['exclude']) ? $options['exclude'] : array();
                $reference = isset($options['reference']) ? $options['reference'] : null;
                $referenceId = isset($options['reference_id']) ? $options['reference_id'] : null;
                $userId = isset($options['userId']) ? $options['userId'] : null;
                $query = $this->_buildQuery($sort, $filter, $category, $featured, false, $exclude, $reference, $referenceId, $userId);
                $limitstart = is_null($limitstart) ? $this->getState('limitstart') : $limitstart;
                $limit = is_null($limit) ? $this->getState('limit') : $limit;
                if ($limit == DISCUSS_NO_LIMIT)
                {
                        $result = $this->_getList($query, 0);
                        $this->_getDateDiffs($result);
                } else
                {
                        if ($pagination)
                        {
                                $result = $this->_getList($query, $limitstart, $limit);
                                $this->_getDateDiffs($result);
                        } else
                        {
                                $result = $this->_getList($query, 0, $limit);
                                $this->_getDateDiffs($result);
                        }
                }
                return $result;
        }

        /**
         * Method to get posts item data
         *
         * @access public
         * @return array
         */
        public function getData($usePagination = true, $sort = 'latest', $limitstart = null, $filter = '', $category = '', $limit = null, $featuredOnly = 'all', $userId = null, $isModule = false)
        {
                $query = $this->_buildQuery($sort, $filter, $category, $featuredOnly, false, array(), null, null, $userId);

                if ($usePagination)
                {
                        $limitstart = is_null($limitstart) ? $this->getState('limitstart') : $limitstart;
                        $limit = is_null($limit) ? $this->getState('limit') : $limit;

                        $this->_data = $this->_getList($query, $limitstart, $limit);
                        $this->_getDateDiffs($this->_data);
                } else
                {
                        $limit = is_null($limit) ? $this->getState('limit') : $limit;
                        $this->_data = $this->_getList($query, 0, $limit);
                        $this->_getDateDiffs($this->_data);
                }

                if ($this->_favs == true)
                {
                        return $this->_data;
                }
        }

        public function clearData()
        {
                $this->_data = null;
        }

        /**
         * Method to get replies
         *
         * @access public
         * @return array
         */
        public function getReplies($id, $sort = 'replylatest', $limitstart = null, $limit = null)
        {
                $this->_parent = $id;
                $this->_isaccept = false;

                $isReplies = ( $id == 'allreplies' ) ? false : true;
                $query = $this->_buildQuery($sort, '', '', 'all', $isReplies);

                $result = '';
                if (!empty($limitstart))
                {
                        if (empty($limit))
                        {
                                $limit = $this->getState('limit');
                        }
                        $result = $this->_getList($query, $limitstart, $limit);
                        $this->_getDateDiffs($result);
                } else
                {
                        if (!empty($limit))
                        {
                                $result = $this->_getList($query, 0, $limit);
                                $this->_getDateDiffs($result);
                        } else
                        {
                                $result = $this->_getList($query);
                                $this->_getDateDiffs($result);
                        }
                }

                return $result;
        }

        /**
         * Method to publish or unpublish categories
         *
         * @access public
         * @return array
         */
        public function publish($categories = array(), $publish = 1)
        {
                if (count($categories) > 0)
                {
                        try {
                                $db = JFactory::getDbo();
                                $query = $db->getQuery(true);
                                $query->update('#__discuss_posts')
                                        ->set('published = ' . $db->quote($publish))
                                        ->where('id IN (' . implode(',', $categories) . ')');
                                $db->setQuery($query);
                                if (!$db->execute())
                                {
                                        return false;
                                }
                        } catch (Exception $ex) {
                                return false;
                        }

                        // We need to update the parent post last replied date
                        foreach ($categories as $postId)
                        {
                                // Load the reply
                                $reply = DiscussHelper::getTable('Posts');
                                $reply->load($postId);

                                // We only need replies
                                if (!empty($reply->parent_id))
                                {
                                        $parent = DiscussHelper::getTable('Post');
                                        $parent->load($reply->parent_id);

                                        // Check if current reply date is more than the last replied date of the parent to determine if this reply is new or is an old pending moderate reply.
                                        if ($reply->created > $parent->replied)
                                        {
                                                try {
                                                        $query->clear();
                                                        $query->update('#__discuss_posts')
                                                                ->set('replied = ' . $db->quote($reply->created))
                                                                ->where('id = ' . $parent->id);
                                                        $db->setQuery($query);
                                                        if (!$db->execute())
                                                        {
                                                                return false;
                                                        }
                                                } catch (Exception $ex) {
                                                        return false;
                                                }
                                        }
                                }
                        }

                        return true;
                }
                return false;
        }

        public function getPostsBy($type, $typeId = 0, $sort = 'latest', $limitstart = null, $published = DISCUSS_FILTER_PUBLISHED, $search = '', $limit = null)
        {
                $db = JFactory::getDbo();
                $queryOrder = '';
                $queryWhere = [];
                switch ($published) {
                        case DISCUSS_FILTER_PUBLISHED:
                        default:
                                $queryWhere[] = 'a.published = 1';
                                break;
                }

                $contentId = '';
                $isIdArray = false;
                if (is_array($typeId))
                {
                        if (count($typeId) > 1)
                        {
                                $contentId = implode(',', $typeId);
                                $isIdArray = true;
                        } else
                        {
                                $contentId = $typeId[0];
                        }
                } else
                {
                        $contentId = $typeId;
                }

                switch ($type) {
                        case 'category':
                                $queryWhere[] = ($isIdArray) ? 'a.category_id IN (' . $contentId . ')' : 'a.category_id = ' . $db->quote($contentId);
                                break;
                        case 'user':
                                $queryWhere[] = 'a.user_id = ' . $db->quote($contentId);
                                break;
                        default:
                                break;
                }

                if (!empty($search))
                {
                        $queryWhere[] = 'a.title LIKE ' . $db->quote('%' . $search . '%');
                }

                //getting only main posts.
                $queryWhere[] = 'a.parent_id = 0';
                $queryWhere[] = 'a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')';

                switch ($sort) {
                        case 'latest':
                                $queryOrder = 'a.created DESC';
                                break;
                        case 'popular':
                                $queryOrder = 'a.hits DESC';
                                break;
                        case 'alphabet':
                                $queryOrder = 'a.title ASC';
                        case 'likes':
                                $queryOrder = 'a.num_likes DESC';
                                break;
                        default :
                                break;
                }

                $limitstart = is_null($limitstart) ? $this->getState('limitstart') : $limitstart;
                $limit = is_null($limit) ? $this->getState('limit') : $limit;
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_posts AS a')
                        ->where($queryWhere);
                $db->setQuery($query);
                $this->_total = $db->loadResult();

                jimport('joomla.html.pagination');
                // $this->_pagination	= new JPagination( $this->_total , $limitstart , $limit);
                $this->_pagination = DiscussHelper::getPagination($this->_total, $limitstart, $limit);

                $nowSql = $db->quote(JFactory::getDate()->toSql());
                $query->clear();
                $query->select('DATEDIFF(' . $nowSql . ', a.created) AS noofdays');
                $query->select('DATEDIFF(' . $nowSql . ', a.created) as daydiff, TIMEDIFF(' . $nowSql . ', a.created) AS timediff');
                $query->select('a.id, a.title, a.alias, a.created, a.modified, a.replied, a.legacy')
                        ->select('a.content, a.category_id, a.published, a.ordering, a.vote, a.hits, a.islock')
                        ->select('a.featured, a.isresolve, a.isreport, a.user_id, a.parent_id')
                        ->select('a.user_type, a.poster_name, a.poster_email, a.num_likes')
                        ->select('a.num_negvote, a.sum_totalvote, a.answered')
                        ->select('a.post_status, a.post_type, pt.title AS post_type_title, pt.suffix AS post_type_suffix')
                        ->select('COUNT(b.id) AS num_replies')
                        ->select('c.title AS category, a.password');

                $query->from('#__discuss_posts AS a')
                        ->leftJoin('#__discuss_posts AS b ON a.id = b.parent_id AND b.published = 1')
                        ->leftJoin('#__discuss_category AS c ON a.category_id = c.id')
                        ->leftJoin('#__discuss_post_types AS pt ON a.post_type = pt.alias');

                $query->where('b.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');

                $query->where($queryWhere);
                $query->group('a.id');
                $query->order($queryOrder);
                $db->setQuery($query, 0, $limitstart, $limit);
                return $db->loadObjectList();
        }

        public function setLastReplyBatch($ids)
        {
                $authorIds = array();

                if (count($ids) > 0)
                {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query->select('*')
                                ->from('#__discuss_posts AS a');
                        if (count($ids) == 1)
                        {
                                $query->where('a.parent_id = ' . $db->quote($ids[0]));
                        } else
                        {
                                $query->where('a.parent_id IN (' . implode(',', $ids) . ')');
                        }
                        $languageTag = JFactory::getLanguage()->getTag();
                        $query->where('a.language IN (' . $db->quote($languageTag) . ',' . $db->quote('*') . ')');

                        $subQuery = $db->getQuery(true);
                        $subQuery->select('MAX(b.id)')
                                ->from('#__discuss_posts AS b')
                                ->where('a.parent_id = b.parent_id')
                                ->where('b.language IN (' . $db->quote($languageTag) . ',' . $db->quote('*') . ')');

                        $query->where('a.id = (' . $subQuery . ')');
                        $db->setQuery($query);
                        $result = $db->loadObjectList();
                        if (count($result) > 0)
                        {
                                foreach ($result as $item)
                                {
                                        self::$_lastReply[$item->parent_id] = $item;
                                        $authorIds[] = $item->user_id;
                                }
                        }

                        foreach ($ids as $id)
                        {
                                if (!isset(self::$_lastReply[$id]))
                                {
                                        self::$_lastReply[$id] = '';
                                }
                        }
                }

                return $authorIds;
        }

        public function getLastReply($id)
        {
                if (isset(self::$_lastReply[$id]))
                {
                        return self::$_lastReply[$id];
                }

                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('*')
                        ->from('#__discuss_posts')
                        ->where('parent_id = ' . (int) $id)
                        ->where('language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')')
                        ->order('created DESC');
                $db->setQuery($query, 0, 1);
                $result = $db->loadObject();
                self::$_lastReply[$id] = $result;
                return $result;
        }

        public function getTotalReplies($id)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*) AS replies')
                        ->from('#__discuss_posts')
                        ->where('parent_id = ' . (int) $id)
                        ->where('language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')')
                        ->where('answered = 0 AND published = 1');
                $db->setQuery($query);
                return $db->loadResult();
        }

        /**
         * Retrieves the total number of comments for this particular discussion.
         *
         * @since	3.0
         * @access	public
         * @param	int		$id		The post id
         * @param	string	$type	Type of comments to calculate (post to calculate individual post comment count, thread to calculate full thread comment count)
         * @return	int
         * @author	Jason Rey <jasonrey@stackideas.com>
         */
        public static function getTotalComments($postid, $type = 'post')
        {
                static $loaded = array();
                $sig = $postid . $type;
                if (isset($loaded[$sig]))
                {
                        return $loaded[$sig];
                }

                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $ids = array();
                $count = 0;
                if ($type == 'thread')
                {
                        $query->clear();
                        $query->select('id')
                                ->from('#__discuss_posts')
                                ->where('language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')')
                                ->where('parent_id = ' . (int) $postid);
                        $db->setQuery($query);
                        $ids = $db->loadAssocList(null, 'id');
                        array_unshift($ids, $postid);
                } else
                {
                        $ids = array($postid);
                }

                foreach ($ids as $id)
                {
                        $query->clear();
                        $query->select('COUNT(*)')
                                ->from('#__discuss_comments')
                                ->where('post_id = ' . (int) $id);
                        $db->setQuery($query);
                        $result = $db->loadResult();
                        $tmpSig = $result . 'post';
                        $loaded[$tmpSig] = $result;
                        $count += (int) $result;
                }
                $loaded[$sig] = $count;
                return $loaded[$sig];
        }

        /**
         * Method to retrieve blog posts based on the given tag id.
         *
         * @access public
         * @param	int		$tagId	The tag id.
         * @return	array	$rows	An array of blog objects.
         */
        public function getTaggedPost($tagId = 0, $sort = 'latest', $filter = '', $limitStart = '')
        {
                if ($tagId == 0)
                {
                        return false;
                }

                if (is_array($tagId) && empty($tagId))
                {
                        return false;
                }

                $db = JFactory::getDbo();
                $limit = $this->getState('limit');
                $limitStart = (empty($limitStart) ) ? $this->getState('limitstart') : $limitStart;
                $filteractive = (empty($filter)) ? JRequest::getString('filter', 'allposts') : $filter;
                $nowSql = $db->quote(JFactory::getDate()->toSql());

                $query = $db->getQuery(true);
                $query->select('DATEDIFF(' . $nowSql . ', b.created) AS noofdays')
                        ->select('DATEDIFF(' . $nowSql . ', b.created) AS daydiff, TIMEDIFF(' . $nowSql . ', b.created) AS timediff');

                // Include polls
                $pollsQuery = $db->getQuery(true);
                $pollsQuery->select('COUNT(*)')
                        ->from('#__discuss_polls')
                        ->where('post_id = b.id');
                $query->select('(' . $pollsQuery . ') AS polls_cnt');

                // Include favourites
                $favouritesQuery = $db->getQuery(true);
                $favouritesQuery->select('COUNT(*)')
                        ->from('#__discuss_favourites')
                        ->where('post_id = b.id');
                $query->select('(' . $favouritesQuery . ') AS totalFavourites');

                // Include attachments
                $attachmentsQuery = $db->getQuery(true);
                $attachmentsQuery->select('COUNT(*)')
                        ->from('#__discuss_attachments')
                        ->where('uid = b.id')
                        ->where($db->quoteName('type') . ' = ' . $db->quote(DISCUSS_QUESTION_TYPE))
                        ->where('published = 1');
                $query->select('(' . $attachmentsQuery . ') AS attachments_cnt');

                //sorting criteria
                if ($sort == 'likes')
                {
                        $query->select('b.num_likes AS likeCnt');
                }

                if ($sort == 'popular')
                {
                        $query->select('COUNT(c.id) AS PopularCnt');
                }

                if ($sort == 'voted')
                {
                        $query->select('b.sum_totalvote AS VotedCnt');
                }

                $select = 'b.id, b.title, b.alias, b.created, b.modified, b.replied,';
                $select .= ' b.content, b.published, b.ordering, b.vote, b.hits, b.islock,';
                $select .= ' b.featured, b.isresolve, b.isreport, b.user_id, b.parent_id,';
                $select .= ' b.user_type, b.poster_name, b.poster_email, b.num_likes,';
                $select .= ' b.post_status, b.post_type,pt.suffix AS post_type_suffix, pt.title AS post_type_title ,';
                $select .= ' b.num_negvote, b.sum_totalvote, b.category_id, d.title AS category, b.password, ';
                $select .= ' COUNT(b.id) AS num_replies, b.legacy';
                $query->select($select);

                if (is_array($tagId))
                {
                        $query->from('#__discuss_posts AS b')
                                ->innerJoin('#__discuss_posts_tags AS a ON a.post_id = b.id')
                                ->innerJoin('#__discuss_tags AS e ON e.id = a.tag_id')
                                ->innerJoin('#__discuss_category AS d ON d.id = b.category_id');
                } else
                {
                        $query->from('#__discuss_posts_tags AS a')
                                ->innerJoin('#__discuss_posts AS b ON a.post_id = b.id')
                                ->leftJoin('#__discuss_posts AS c ON b.id = c.parent_id AND c.published = 1')
                                ->innerJoin('#__discuss_category AS d ON d.id = b.category_id');
                }

                // Join with post types table
                $query->leftJoin('#__discuss_post_types AS pt ON b.post_type = pt.alias');

                if (is_array($tagId))
                {
                        $query->where('a.tag_id IN (' . implode(',', $tagId) . ')');
                } else
                {
                        $query->where('a.tag_id = ' . (int) $tagId);
                }

                $query->where('b.published = 1')
                        ->where('b.private = 0')
                        ->where('b.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');

                $excludeCats = DiscussHelper::getPrivateCategories();

                if (!empty($excludeCats))
                {
                        $query->where('b.category_id NOT IN (' . implode(',', $excludeCats) . ')');
                }

                if ($filteractive == 'featured')
                {
                        $query->where('b.featured = 1');
                }

                $orderby = '';
                switch ($sort) {
                        case 'popular':
                                $orderby = 'PopularCnt DESC, b.created DESC'; //used in getdata only
                                break;
                        case 'hits':
                                $orderby = 'b.hits DESC'; //used in getdata only
                                break;
                        case 'voted':
                                $orderby = 'b.sum_totalvote DESC, b.created DESC'; //used in getreplies only
                                break;
                        case 'likes':
                                $orderby = 'b.num_likes DESC, b.created DESC'; //used in getdate and getreplies
                                break;
                        case 'activepost':
                                $orderby = 'b.featured DESC, b.replied DESC'; //used in getsticky and getlastreply
                                break;
                        case 'featured':
                        case 'latest':
                        default:
                                $orderby = 'b.featured DESC, b.created DESC'; //used in getsticky and get created date
                                break;
                }

                if (is_array($tagId))
                {
                        $orderby = $orderby . ', COUNT(b.id) DESC';
                }

                $having = "";
                if ($filteractive == 'unanswered')
                {
                        $groupby = 'b.id';
                        $having = 'COUNT(c.id) = 0';
                } else
                {
                        $groupby = 'b.id';
                }

                if (is_array($tagId))
                {
                        $groupby = 'b.id';
                        $having = 'COUNT(b.id) >= ' . count($tagId);
                }

                $query->order($orderby);
                $query->group($groupby);

                if ($having)
                {
                        $query->having($having);
                }

                $db->setQuery($query, $limitStart, $limit);
                $rows = $db->loadObjectList();

                //total tag's post sql
                $totalQuery = $db->getQuery(true);
                $totalQuery->select('COUNT(*)')
                        ->from('(' . $query . ') AS t');
                $db->setQuery($totalQuery);
                $this->_total = $db->loadResult();
                $this->_pagination = DiscussHelper::getPagination($this->_total, $this->getState('limitstart'), $this->getState('limit'));
                return $rows;
        }

        /**
         * Get all child posts based on parent_id given
         */
        public function getAllReplies($parent_id)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('*')
                        ->from('#__discuss_posts')
                        ->where('published = 1')
                        ->where('parent_id = ' . (int) $parent_id)
                        ->where('language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                $db->setQuery($query);
                return $db->loadObjectList();
        }

        /**
         * Method delete all reply with post id
         * @param integer $parent_id
         * @return boolean
         */
        public function deleteAllReplies($parent_id)
        {
                if (!$parent_id)
                {
                        return false;
                }

                try {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query->delete('#__discuss_posts')
                                ->where('parent_id = ' . (int) $parent_id);
                        $db->setQuery($query);
                        if ($db->execute())
                        {
                                return true;
                        }
                } catch (Exception $ex) {
                        return false;
                }
                return false;
        }

        public function getNegativeVote($postId)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_votes')
                        ->where('post_id = ' . (int) $postId)
                        ->where($db->quoteName('value') . ' = -1');
                $db->setQuery($query);
                return $db->loadResult();
        }

        public function getComments($postId, $limit = null, $limitstart = null)
        {
                $db = JFactory::getDbo();
                $offset = DiscussDateHelper::getOffSet(true);
                $nowSQL = $db->quote(JFactory::getDate()->toSql());
                $query = $db->getQuery(true);
                $query->select('DATEDIFF(' . $nowSQL . ', DATE_ADD(a.created, INTERVAL ' . $offset . ' HOUR ) ) AS noofdays')
                        ->select('DATEDIFF(' . $nowSQL . ', DATE_ADD(a.created, INTERVAL ' . $offset . ' HOUR ) ) AS daydiff')
                        ->select('TIMEDIFF(' . $nowSQL . ', DATE_ADD(a.created, INTERVAL ' . $offset . ' HOUR ) ) AS timediff')
                        ->select('a.*');
                $query->from('#__discuss_comments AS a');

                if (is_array($postId))
                {
                        if (count($postId) == 1)
                        {
                                $query->where('a.post_id = ' . (int) $postId)
                                        ->order('a.created ASC');
                        } else
                        {
                                $query->where('a.post_id IN (' . implode(',', $postId) . ')')
                                        ->order('a.post_id, a.created ASC');
                        }
                } else
                {
                        $query->where('a.post_id = ' . (int) $postId)
                                ->order('a.created ASC');
                }

                if ($limit !== null)
                {
                        if ($limitstart !== null)
                        {
                                $db->setQuery($query, $limitstart, $limit);
                        } else
                        {
                                $db->setQuery($query, 0, $limit);
                        }
                }

                $db->setQuery($query);
                return $db->loadObjectList();
        }

        /**
         * Method to get replies
         *
         * @access public
         * @return array
         */
        public function getAcceptedReply($id)
        {
                $db = JFactory::getDbo();
                $this->_parent = $id;
                $this->_isaccept = true;
                $query = $this->_buildQuery('latest', 'answer', '', 'all', true);
                $db->setQuery($query);
                $result = $db->loadObjectList();
                $this->_getDateDiffs($result);
                return $result;
        }

        public function getUnresolvedCount($filter = '', $category = '', $tagId = '', $featuredOnly = 'all', $queryOnly = false)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(a.id)')
                        ->from('#__discuss_posts AS a');

                if (!empty($tagId))
                {
                        $query->innerJoin('#__discuss_posts_tags AS c ON a.id = c.post_id AND c.tag_id = ' . $db->quote($tagId));
                }

                $query->where('a.parent_id = 0')
                        ->where('a.published = 1')
                        ->where('a.isresolve = 0');

                if ($featuredOnly === true)
                {
                        $query->where('a.featured = 1');
                } else if ($featuredOnly === false)
                {
                        $query->where('a.featured = 0');
                }

                if (!EDC::isSiteAdmin() && !EDC::isModerator())
                {
                        $query->where('a.private = 0');
                }

                if ($category)
                {
                        if (!is_array($category))
                        {
                                $category = array($category);
                        }

                        $model = DiscussHelper::getModel('Categories');

                        foreach ($category as $categoryId)
                        {
                                $data = $model->getChildIds($categoryId);

                                if ($data)
                                {
                                        foreach ($data as $childCategory)
                                        {
                                                $childs[] = $childCategory;
                                        }
                                }
                                $childs[] = $categoryId;
                        }

                        $query->where('a.category_id IN (' . implode(',', $childs) . ')');
                }

                // get all private categories id
                $excludeCats = DiscussHelper::getPrivateCategories();
                if (!empty($excludeCats))
                {
                        $query->where('a.category_id NOT IN (' . implode(',', $excludeCats) . ')');
                }
                $query->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                $db->setQuery($query);
                return $db->loadResult();
        }

        public function getResolvedCount($filter = '', $category = '', $tagId = '', $featuredOnly = 'all', $queryOnly = false)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_posts AS a');

                if (!empty($tagId))
                {
                        $query->innerJoin('#__discuss_posts_tags AS c ON a.id = c.post_id AND c.tag_id = ' . (int) $tagId);
                }

                $query->where('a.parent_id = 0')
                        ->where('a.published = 1')
                        ->where('a.isresolve = 1');

                if ($featuredOnly === true)
                {
                        $query->where('a.featured = 1');
                } else if ($featuredOnly === false)
                {
                        $query->where('a.featured = 0');
                }

                if ($category)
                {
                        if (!is_array($category))
                        {
                                $category = array($category);
                        }

                        $model = DiscussHelper::getModel('Categories');

                        foreach ($category as $categoryId)
                        {
                                $data = $model->getChildIds($categoryId);

                                if ($data)
                                {
                                        foreach ($data as $childCategory)
                                        {
                                                $childs[] = $childCategory;
                                        }
                                }
                                $childs[] = $categoryId;
                        }

                        $query->where('a.category_id IN (' . implode(',', $childs) . ')');
                }

                // get all private categories id
                $excludeCats = DiscussHelper::getPrivateCategories();
                if (!empty($excludeCats))
                {
                        $query->where('a.category_id NOT IN (' . implode(',', $excludeCats) . ')');
                }
                $query->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                $db->setQuery($query);
                return $db->loadResult();
        }

        public function getUnansweredCount($filter = '', $category = '', $tagId = '', $featuredOnly = 'all')
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(a.id)')
                        ->from('#__discuss_posts AS a')
                        ->leftJoin('#__discuss_posts AS b ON a.id = b.parent_id AND b.published = 1');

                if (!empty($tagId))
                {
                        $query->innerJoin('#__discuss_posts_tags AS c ON a.id = c.post_id AND c.tag_id = ' . (int) $tagId);
                }

                $query->where('a.parent_id = 0')
                        ->where('a.published = 1')
                        ->where('a.isresolve = 0')
                        ->where('b.id IS NULL')
                        ->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');

                if ($featuredOnly === true)
                {
                        $query->where('a.featured = 1');
                } else if ($featuredOnly === false)
                {
                        $query->where('a.featured = 0');
                }

                if (!EDC::isSiteAdmin() && !EDC::isModerator())
                {
                        $query->where('a.private = 0');
                }

                if ($category)
                {
                        $model = DiscussHelper::getModel('Categories');
                        $childs = $model->getChildIds($category);
                        $childs[] = $category;
                        $query->where('a.category_id IN (' . implode(',', $childs) . ')');
                }

                // get all private categories id
                $excludeCats = DiscussHelper::getPrivateCategories();
                if (!empty($excludeCats))
                {
                        $query->where('a.category_id NOT IN (' . implode(',', $excludeCats) . ')');
                }

                $db->setQuery($query);
                return $db->loadResult();
        }

        public function getUnreadCount($category = 0, $excludeFeatured = false)
        {
                $excludeCats = DiscussHelper::getPrivateCategories();
                if (!is_array($category))
                {
                        $category = array($category);
                }
                $catModel = DiscussHelper::getModel('Categories');
                $childs = array();
                foreach ($category as $categoryId)
                {
                        $data = $catModel->getChildIds($categoryId);
                        if ($data)
                        {
                                foreach ($data as $childCategory)
                                {
                                        $childs[] = $childCategory;
                                }
                        }
                        $childs[] = $categoryId;
                }

                if (empty($category))
                {
                        $categoryIds = false;
                } else
                {
                        $categoryIds = array_diff($childs, $excludeCats);

                        if (empty($categoryIds))
                        {
                                return '0';
                        }
                }

                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_posts')
                        ->where('published = 1')
                        ->where('parent_id = 0')
                        ->where('answered = 0')
                        ->where('legacy = 0')
                        ->where('language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');

                if ($categoryIds && !( count($categoryIds) == 1 && empty($categoryIds[0]) ))
                {
                        if (count($categoryIds) == 1)
                        {
                                $query->where('category_id = ' . (int) $categoryIds[0]);
                        } else
                        {
                                $query->where('category_id IN (' . implode(',', $categoryIds) . ')');
                        }
                }

                if ($excludeFeatured)
                {
                        $query->where('featured = 0');
                }

                if (!EDC::isSiteAdmin() && !EDC::isModerator())
                {
                        $query->where('private = 0');
                }

                $profile = DiscussHelper::getTable('Profile');
                $my = JFactory::getUser();
                $profile->load($my->id);
                $readPosts = $profile->posts_read;
                if ($readPosts)
                {
                        $readPosts = unserialize($readPosts);
                        if (count($readPosts) > 1)
                        {
                                $query->where('id NOT IN (' . implode(',', $readPosts) . ')');
                        } else
                        {
                                $query->where('id != ' . $db->quote($readPosts[0]));
                        }
                }

                $db->setQuery($query);
                $result = $db->loadResult();
                return empty($result) ? '0' : $result;
        }

        public function getNewCount($filter = '', $category = '', $tagId = '', $featuredOnly = 'all')
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_posts AS a');

                if (!empty($tagId))
                {
                        $query->innerJoin('#__discuss_posts_tags AS c ON a.id = c.post_id AND c.tag_id = ' . (int) $tagId);
                }

                $query->where('a.parent_id = 0')
                        ->where('a.published = 1')
                        ->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');

                if ($featuredOnly === true)
                {
                        $query->where('a.featured = 1');
                } else if ($featuredOnly === false)
                {
                        $query->where('a.featured = 0');
                }

                $config = DiscussHelper::getConfig();
                $query->where('DATEDIFF( ' . $db->quote(DiscussHelper::getDate()->toMySQL()) . ', a.created) <= ' . $db->quote($config->get('layout_daystostaynew')));

                if ($category)
                {
                        $model = DiscussHelper::getModel('Categories');
                        $childs = $model->getChildIds($category);
                        $childs[] = $category;
                        $query->where('a.category_id IN (' . implode(',', $childs) . ')');
                }

                // get all private categories id
                $excludeCats = DiscussHelper::getPrivateCategories();
                if (!empty($excludeCats))
                {
                        $query->where('a.category_id NOT IN (' . implode(',', $excludeCats) . ')');
                }
                $db->setQuery($query);
                return $db->loadResult();
        }

        public function getFeaturedCount($filter = '', $category = '', $tagId = '')
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*) AS CNT')
                        ->from('#__discuss_posts AS a');
                if (!empty($tagId))
                {
                        $query->innerJoin('#__discuss_posts_tags AS b ON a.id = b.post_id AND b.tag_id = ' . (int) $tagId);
                }

                $query->where('a.featured = 1')
                        ->where('a.parent_id = 0')
                        ->where('a.published = 1')
                        ->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                if ($category)
                {
                        $query->where('a.category_id = ' . (int) $category);
                }

                // get all private categories id
                $excludeCats = DiscussHelper::getPrivateCategories();
                if (!empty($excludeCats))
                {
                        $query->where('a.category_id NOT IN (' . implode(',', $excludeCats) . ')');
                }

                $db->setQuery($query);
                return $db->loadResult();
        }

        public function getFeaturedPosts($category = '')
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('a.*')
                        ->from('#__discuss_posts AS a');
                $query->where('a.featured = 1')
                        ->where('a.parent_id = 0')
                        ->where('a.published = 1')
                        ->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');

                if ($category)
                {
                        $query->where('a.category_id = ' . (int) $category);
                }

                // get all private categories id
                $excludeCats = DiscussHelper::getPrivateCategories();
                if (!empty($excludeCats))
                {
                        $query->where('a.category_id NOT IN (' . implode(',', $excludeCats) . ')');
                }
                $db->setQuery($query);
                return $db->loadResult();
        }

        /**
         * Get unresolved posts from a specific user.
         *
         * @access	public
         * @param	int	$userId		The specific user.
         */
        public function getUnresolvedFromUser($userId)
        {
                $db = JFactory::getDbo();
                $nowSQL = $db->quote(JFactory::getDate()->toSql());
                $limitstart = $this->getState('limitstart');
                $limit = $this->getState('limit');

                $query = $db->getQuery(true);
                $query->select('DATEDIFF(' . $nowSQL . ', b.created) AS noofdays')
                        ->select('DATEDIFF(' . $nowSQL . ', b.created) AS daydiff, TIMEDIFF(' . $nowSQL . ', b.created) AS timediff');

                $select = 'b.id, b.title, b.alias, b.created, b.modified, b.replied, b.legacy,';
                $select .= ' b.content, b.category_id, b.published, b.ordering, b.vote, b.hits, b.islock,';
                $select .= ' b.featured, b.isresolve, b.isreport, b.user_id, b.parent_id,';
                $select .= ' b.user_type, b.poster_name, b.poster_email, b.num_likes,';
                $select .= ' b.num_negvote, b.sum_totalvote, b.answered,';
                $select .= ' b.post_status, b.post_type, pt.title AS post_type_title, pt.suffix AS post_type_suffix,';
                $select .= ' COUNT(d.id) AS num_replies,';
                $select .= ' c.title AS category, b.password';
                $query->select($select)
                        ->from('#__discuss_posts AS b')
                        ->leftJoin('#__discuss_posts AS d ON d.parent_id = b.id')
                        ->leftJoin('#__discuss_category AS c ON c.id = b.category_id')
                        ->leftJoin('#__discuss_post_types AS pt ON b.post_type = pt.alias');

                $query->where('b.user_id = ' . (int) $userId)
                        ->where('b.isresolve = 0')
                        ->where('b.parent_id = 0')
                        ->where('b.published = 1')
                        ->where('b.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')')
                        ->group('b.id');

                $this->_total = $this->_getListCount($query);
                $this->_pagination = DiscussHelper::getPagination($this->_total, $limitstart, $limit);
                $this->_data = $this->_getList($query, $limitstart, $limit);
                return $this->_data;
        }

        /**
         * Retrieve replies from a specific user
         * */
        public function getRepliesFromUser($userId, $ordering = '')
        {
                $db = JFactory::getDbo();
                $nowSQL = $db->quote(JFactory::getDate()->toSql());
                $limitstart = $this->getState('limitstart');
                $limit = $this->getState('limit');

                $query = $db->getQuery(true);
                $query->select('DATEDIFF(' . $nowSQL . ', b.created) AS noofdays')
                        ->select('DATEDIFF(' . $nowSQL . ', b.created) AS daydiff, TIMEDIFF(' . $nowSQL . ', b.created) AS timediff');

                $select = 'b.id, b.title, b.alias, b.created, b.modified, b.replied, b.legacy,';
                $select .= ' b.content, b.category_id, b.published, b.ordering, b.vote, a.hits, b.islock,';
                $select .= ' b.featured, b.isresolve, b.isreport, b.user_id, b.parent_id,';
                $select .= ' b.user_type, b.poster_name, b.poster_email, b.num_likes,';
                $select .= ' b.num_negvote, b.sum_totalvote, b.answered,';
                $select .= ' b.post_status, b.post_type, pt.title AS post_type_title, pt.suffix AS post_type_suffix,';
                $select .= ' COUNT(a.id) AS num_replies,';
                $select .= ' c.title AS category, b.password';
                $query->select($select)
                        ->from('#__discuss_posts AS a')
                        ->innerJoin('#__discuss_posts AS b ON a.parent_id = b.id')
                        ->leftJoin('#__discuss_category AS c ON c.id = b.category_id')
                        ->leftJoin('#__discuss_post_types AS pt ON b.post_type = pt.alias');

                $query->where('a.user_id = ' . (int) $userId)
                        ->where('a.published = 1')
                        ->where('a.parent_id != 0 AND b.published = 1 AND b.parent_id = 0')
                        ->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                $query->group('b.id');
                if (!empty($ordering))
                {
                        if ($ordering == 'latest')
                        {
                                $query->order('a.created DESC');
                        }
                }
                $this->_total = $this->_getListCount($query);
                $this->_pagination = DiscussHelper::getPagination($this->_total, $limitstart, $limit);
                $this->_data = $this->_getList($query, $limitstart, $limit);
                return $this->_data;
        }

        public function getUserReplies($postId, $excludeLastReplyUser = false)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $repliesUser = '';
                $lastReply = '';
                $languageTag = JFactory::getLanguage()->getTag();
                if ($excludeLastReplyUser)
                {
                        $query->clear();
                        $query->select('id, user_id, poster_name, poster_email')
                                ->from('#__discuss_posts')
                                ->where('published = 1 AND parent_id = ' . (int) $postId)
                                ->where('language IN (' . $db->quote($languageTag) . ',' . $db->quote('*') . ')')
                                ->order('id DESC');
                        $db->setQuery($query, 0, 1);
                        $lastReply = $db->loadAssoc();
                }

                if (isset($lastReply['id']))
                {
                        $query->clear();
                        $query->select('DISTINCT user_id, poster_email, poster_name')
                                ->from('#__discuss_posts')
                                ->where('published = 1 AND parent_id = ' . (int) $postId)
                                ->where('language IN (' . $db->quote($languageTag) . ',' . $db->quote('*') . ')')
                                ->where('id != ' . (int) $lastReply['id']);

                        if (!empty($lastReply['user_id']))
                        {
                                $query->where('user_id != ' . (int) $lastReply['user_id']);
                        }

                        if (!empty($lastReply['poster_email']))
                        {
                                $query->where('poster_email != ' . $db->quote($lastReply['poster_email']));
                        }

                        $query->order('id DESC');
                        $db->setQuery($query, 0, 5);
                        $repliesUser = $db->loadObjectList();
                }
                return $repliesUser;
        }

        public function getCategoryId($postId)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('category_id')
                        ->from('#__discuss_posts')
                        ->where('id = ' . (int) $postId);
                $db->setQuery($query);
                return $db->loadResult();
        }

        /**
         * Retrieves a list of user id's that has participated in a discussion
         *
         * @access	public
         * @param	int $postId		The main discussion id.
         * @return	Array	An array of user id's.
         *
         * */
        public function getParticipants($postId)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('DISTINCT user_id')
                        ->from('#__discuss_posts')
                        ->where('parent_id = ' . (int) $postId)
                        ->where('language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                $db->setQuery($query);
                return $db->loadAssocList(null, 'user_id');
        }

        public function hasAttachments($postId, $type)
        {
                static $loaded = array();
                $index = $postId . $type;
                if (!isset($loaded[$index]))
                {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query->select('COUNT(*)')
                                ->from('#__discuss_attachments')
                                ->where('uid = ' . (int) $postId)
                                ->where($db->quoteName('type') . ' = ' . $db->quote($type))
                                ->where('published = 1');
                        $db->setQuery($query);
                        $result = $db->loadResult();
                        $loaded[$index] = $result;
                }
                return $loaded[$index];
        }

        public function hasPolls($postId)
        {
                static $cache = array();
                if (!isset($cache[$postId]))
                {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query->select('COUNT(DISTINCT(post_id))')
                                ->from('#__discuss_polls')
                                ->where('post_id = ' . (int) $postId);
                        $db->setQuery($query);
                        $result = $db->loadResult();
                        $cache[$postId] = $result;
                }
                return $cache[$postId];
        }

        /**
         * When merging posts, we need to update attachments type
         *
         * @since	1.0
         * @access	public
         * @param	string
         * @return
         */
        public function updateAttachments($postId, $type)
        {
                $db = JFactory::getDbo();
                $where = $type == 'questions' ? 'replies' : 'questions';
                $query = $db->getQuery(true);
                $query->update('#__discuss_attachments')
                        ->set($db->quoteName('type') . ' = ' . $db->quote($type))
                        ->where('uid = ' . (int) $postId)
                        ->where($db->quoteName('type') . ' = ' . $db->quote($where));
                $db->setQuery($query);
                $db->execute();
        }

        /**
         * Updates existing posts to a new parent.
         *
         * @since	1.0
         * @access	public
         * @param	string
         * @return
         */
        public function updateNewParent($currentParent, $newParent)
        {
                try {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query->update('#__discuss_posts')
                                ->set('parent_id = ' . (int) $newParent)
                                ->where('parent_id = ' . (int) $currentParent);
                        $db->setQuery($query);
                        $db->execute();
                } catch (Exception $ex) {
                        return false;
                }
        }

}

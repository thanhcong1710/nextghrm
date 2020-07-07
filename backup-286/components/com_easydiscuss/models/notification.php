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

class EasyDiscussModelNotification extends EasyDiscussModel
{

        /**
         * Post total
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
         * Post data array
         *
         * @var array
         */
        var $_data = null;

        /**
         * Parent ID
         *
         * @var integer
         */
        var $_parent = null;
        var $_isaccept = null;

        function __construct()
        {
                parent::__construct();
                $limit = JFactory::getApplication()->getUserStateFromRequest('com_easydiscuss.posts.limit', 'limit', DiscussHelper::getListLimit(), 'int');
                $limitstart = JRequest::getVar('limitstart', 0, '', 'int');

                $this->setState('limit', $limit);
                $this->setState('limitstart', $limitstart);
        }

        /**
         * Method to test if there are any notifications for any particular user.
         *
         * @access public
         *
         * @param	int $userId		The user id to test on.
         * @return	boolean
         */
        public function getTotalNotifications($userId)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('id')
                        ->from('#__discuss_notifications')
                        ->where($db->quoteName('target') . ' = ' . (int) $userId)
                        ->where($db->quoteName('state') . ' = ' . $db->quote(DISCUSS_NOTIFICATION_NEW))
                        ->group('cid, ' . $db->quoteName('type'));

                $totalQuery = $db->getQuery(true);
                $totalQuery->select('COUNT(*)')
                        ->from('(' . $query . ') AS x');
                $db->setQuery($totalQuery);
                return $db->loadResult();
        }

        /**
         * Returns a list of notifications for a specific user
         *
         * @access	public
         * @param	int	$userId		The target
         * @param	int	$limit		The limit of notifications to fetch
         * @return	array
         * */
        public function getNotifications($userid, $showNewOnly = false, $limit = 10)
        {
                $db = JFactory::getDbo();
                $subQuery = $db->getQuery(true);
                $subQuery->select('COUNT(b.cid) AS items, MAX(b.id) AS id, DATE_FORMAT(b.created, "%Y%m%d") AS day')
                        ->from('#__discuss_notifications AS b')
                        ->where('b.target = ' . (int) $userid);
                if ($showNewOnly)
                {
                        $subQuery->where('b.state = 1')
                                ->group('b.cid, b.type, day');
                }

                $query = $db->getQuery(true);
                $query->select('x.items, a.id, a.cid, a.type, a.title, a.target, a.author, a.permalink, a.created, a.component,'
                                . 'a.state, a.favicon, DATE_FORMAT(a.created, "%Y%m%d") AS day')
                        ->from('#__discuss_notifications AS a')
                        ->innerJoin('(' . $subQuery . ') AS x ON x.id = a.id');
                $query->where('a.target = ' . (int) $userid);
                if ($showNewOnly)
                {
                        $query->where('a.state = 1');
                }

                $query->order('a.id DESC');
                $db->setQuery($query, 0, $limit);
                return $db->loadObjectList();
        }

        /**
         * Updates notifications since the browser / viewer / user has already read the topic
         *
         * @access	public
         * @param	int	$userId		The current user that is viewing
         * @param	int $cid		The unique id of notification to clear
         * @param	Array $types	The type of notification to clear
         * */
        public function markRead($userId, $cid = false, $types)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->update('#__discuss_notifications')
                        ->set($db->quoteName('state') . ' = ' . $db->quote(DISCUSS_NOTIFICATION_READ))
                        ->where('target = ' . (int) $userId);

                // If cid is not provided, caller might just want to clear all notifications for a specific user when they view certain actions.
                if ($cid)
                {
                        $query->where('cid = ' . (int) $cid);
                }

                if (!is_array($types))
                {
                        $types = array($types);
                }

                $string = '';
                for ($i = 0; $i < count($types); $i++)
                {
                        $string .= $db->Quote($types[$i]);
                        if (next($types) !== false)
                        {
                                $string .= ',';
                        }
                }

                $query->where($db->quoteName('type') . ' IN (' . $string . ')');
                $db->setQuery($query);
                try {
                        $db->execute();
                        return true;
                } catch (Exception $ex) {
                        return false;
                }
        }

        public function deleteNotifications($postId = null)
        {
                if (!empty($postId))
                {
                        try {
                                $db = JFactory::getDbo();
                                $query = $db->getQuery(true);
                                $query->delete('#__discuss_notifications')
                                        ->where('cid = ' . (int) $postId);
                                $db->setQuery($query);
                                $db->execute();
                                return true;
                        } catch (Exception $ex) {
                                return false;
                        }
                }
                return false;
        }

}

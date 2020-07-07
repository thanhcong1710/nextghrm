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

class EasyDiscussModelSubscribe extends EasyDiscussModel
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

        function __construct()
        {
                parent::__construct();
                $limit = JFactory::getApplication()->getUserStateFromRequest('com_easydiscuss.posts.limit', 'limit', DiscussHelper::getListLimit(), 'int');
                $limitstart = JRequest::getVar('limitstart', 0, '', 'int');

                $this->setState('limit', $limit);
                $this->setState('limitstart', $limitstart);
        }

        // Used in user plugin when user changes email, all previous subscribed email should update to the new email.
        function updateSubscriberEmail($data, $isNew)
        {
                if (!$isNew)
                {
                        try {
                                $db = JFactory::getDbo();
                                $query = $db->getQuery(true);
                                $query->update('#__discuss_subscription')
                                        ->set('email = ' . $db->quote($data['email']))
                                        ->where('userid = ' . (int) $data['id']);
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
         * Determines if the particular email is already subscribed in the system.
         *
         * @since	3.0
         * @param	string	Type of subscription.
         * @param	string	The email address.
         * @param	int		Unique id.
         */
        public function isSiteSubscribed($type, $email, $cid)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('*')
                        ->from('#__discuss_subscription')
                        ->where($db->quoteName('type') . ' = ' . $db->quote($type))
                        ->where('email = ' . $db->quote($email))
                        ->where('cid = ' . (int) $cid);
                $db->setQuery($query);
                return $db->loadObject();
        }

        function isPostSubscribedEmail($subscription_info)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('id')
                        ->from('#__discuss_subscription')
                        ->where($db->quoteName('type') . ' = ' . $db->quote('post'))
                        ->where('email = ' . $db->quote($subscription_info['email']))
                        ->where('cid = ' . (int) $subscription_info['cid']);
                $db->setQuery($query);
                return $db->loadAssoc();
        }

        function isPostSubscribedUser($subscription_info)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('id')
                        ->from('#__discuss_subscription')
                        ->where($db->quoteName('type') . ' = ' . $db->quote('post'))
                        ->where('( userid = ' . (int) $subscription_info['userid'] . ' OR email = ' . $db->quote($db->Quote($subscription_info['email'])) . ' )')
                        ->where('cid = ' . (int) $subscription_info['cid']);
                $db->setQuery($query);
                return $db->loadAssoc();
        }

        function isTagSubscribedEmail($subscription_info)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('id')
                        ->from('#__discuss_subscription')
                        ->where($db->quoteName('type') . ' = ' . $db->quote('tag'))
                        ->where('email = ' . $db->quote($subscription_info['email']))
                        ->where('cid = ' . (int) $subscription_info['cid']);
                $db->setQuery($query);
                return $db->loadAssoc();
        }

        function isTagSubscribedUser($subscription_info)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('id')
                        ->from('#__discuss_subscription')
                        ->where($db->quoteName('type') . ' = ' . $db->quote('tag'))
                        ->where('(userid = ' . $db->quote($subscription_info['userid']) . ' OR email = ' . $db->quote($subscription_info['email']) . ')')
                        ->where('cid = ' . (int) $subscription_info['cid']);
                $db->setQuery($query);
                return $db->loadAssoc();
        }

        function addSubscription($subscription_info)
        {
                $config = DiscussHelper::getConfig();
                $my = JFactory::getUser();
                if ($config->get('main_allowguestsubscribe') || ($my->id && !$config->get('main_allowguestsubscribe')))
                {
                        $date = DiscussHelper::getDate();
                        $now = $date->toMySQL();
                        $subscriber = DiscussHelper::getTable('Subscribe');
                        $subscriber->userid = $subscription_info['userid'];
                        $subscriber->member = $subscription_info['member'];
                        $subscriber->type = $subscription_info['type'];
                        $subscriber->cid = $subscription_info['cid'];
                        $subscriber->email = $subscription_info['email'];
                        $subscriber->fullname = $subscription_info['name'];
                        $subscriber->interval = $subscription_info['interval'];
                        $subscriber->created = $now;
                        $subscriber->sent_out = $now;
                        return $subscriber->store();
                }

                return false;
        }

        /**
         * Updates an existing subscription.
         *
         * @since	3.0
         * @access	public
         */
        function updateSiteSubscription($subscriptionId, $data = array())
        {
                $config = DiscussHelper::getConfig();
                $my = JFactory::getUser();

                if ($config->get('main_allowguestsubscribe') || ($my->id && !$config->get('main_allowguestsubscribe')))
                {
                        $date = DiscussHelper::getDate();
                        $subscriber = DiscussHelper::getTable('Subscribe');

                        $subscriber->load($subscriptionId);
                        $subscriber->userid = $data['userid'];
                        $subscriber->member = $data['member'];
                        $subscriber->cid = $data['cid'];
                        $subscriber->fullname = $data['name'];
                        $subscriber->interval = $data['interval'];
                        $subscriber->sent_out = $date->toMySQL();
                        return $subscriber->store();
                }

                return false;
        }

        function updatePostSubscription($sid, $subscription_info)
        {
                $config = DiscussHelper::getConfig();
                $my = JFactory::getUser();

                if ($config->get('main_allowguestsubscribe') || ($my->id && !$config->get('main_allowguestsubscribe')))
                {
                        $db = DiscussHelper::getDBO();

                        $query = 'DELETE FROM `#__discuss_subscription` '
                                . ' WHERE `type` = ' . $db->Quote('post')
                                . ' AND `cid` = ' . $db->Quote($subscription_info['cid'])
                                . ' AND `email` = ' . $db->Quote($subscription_info['email'])
                                . ' AND `id` != ' . $db->Quote($sid);

                        $db->setQuery($query);
                        $result = $db->query();

                        if ($result)
                        {
                                $date = DiscussHelper::getDate();
                                $subscriber = DiscussHelper::getTable('Subscribe');

                                $subscriber->load($sid);
                                $subscriber->userid = $subscription_info['userid'];
                                $subscriber->member = $subscription_info['member'];
                                $subscriber->cid = $subscription_info['cid'];
                                $subscriber->fullname = $subscription_info['name'];
                                $subscriber->interval = $subscription_info['interval'];
                                $subscriber->sent_out = $date->toMySQL();
                                return $subscriber->store();
                        }
                }

                return false;
        }

        function getPostSubscribers($postid = '')
        {
                if (empty($postid))
                {
                        //invalid post id
                        return false;
                }

                $db = DiscussHelper::getDBO();

                $query = 'SELECT * FROM `#__discuss_subscription` '
                        . ' WHERE `type` = ' . $db->Quote('post')
                        . ' AND `cid` = ' . $db->Quote($postid);

                $db->setQuery($query);

                $result = $db->loadObjectList();

                $emails = array();
                $subscribers = array();

                foreach ($result as $row)
                {
                        if (!in_array($row->email, $emails))
                        {
                                $subscribers[$row->email] = $row;
                        }
                        $emails[] = $row->email;
                }
                return $subscribers;
        }

        function getCategorySubscribers($postid = '')
        {
                if (empty($postid))
                {
                        return false;
                }

                // get category id
                $table = DiscussHelper::getTable('post');
                $table->load($postid);

                $categoryid = $table->category_id;

                $db = DiscussHelper::getDBO();

                $query = 'SELECT * FROM `#__discuss_subscription` '
                        . ' WHERE `type` = ' . $db->Quote('category')
                        . ' AND `cid` = ' . $db->Quote($categoryid);

                $db->setQuery($query);

                $result = $db->loadObjectList();

                $emails = array();
                $subscribers = array();

                foreach ($result as $row)
                {
                        if (!in_array($row->email, $emails))
                        {
                                $subscribers[$row->email] = $row;
                        }
                        $emails[] = $row->email;
                }

                return $subscribers;
        }

        function getSiteSubscribers($interval = 'daily', $now = '', $categoryId = null)
        {
                $db = JFactory::getDBO();

                $timeQuery = '';
                $categoryGrps = array();

                if (!is_null($categoryId))
                {
                        $query = 'SELECT `content_id` FROM `#__discuss_category_acl_map`';
                        $query .= ' WHERE `category_id` = ' . $db->Quote($categoryId);
                        $query .= ' AND `acl_id` = ' . $db->Quote(DISCUSS_CATEGORY_ACL_ACTION_VIEW);
                        $query .= ' AND `type` = ' . $db->Quote('group');

                        $db->setQuery($query);
                        $categoryGrps = $db->loadResultArray();
                }

                if (!empty($now))
                {
                        switch ($interval) {
                                case 'weekly':
                                        $days = '7';
                                        break;
                                case 'monthly':
                                        $days = '30';
                                        break;
                                case 'daily':
                                        $days = '1';
                                default :
                                        break;
                        }

                        $timeQuery = ' AND DATEDIFF(' . $db->Quote($now) . ', `sent_out`) >= ' . $db->Quote($days);
                }


                if (!empty($categoryGrps))
                {
                        $result = array();
                        $aclItems = array();
                        $nonAclItems = array();

                        // site members
                        $queryCatIds = implode(',', $categoryGrps);

                        $query = 'SELECT * FROM `#__discuss_subscription` AS ds';
                        $query .= ' INNER JOIN `#__user_usergroup_map` as um on um.`user_id` = ds.`userid`';
                        $query .= ' WHERE ds.`interval` = ' . $db->Quote($interval);
                        $query .= ' AND ds.`type` = ' . $db->Quote('site');
                        $query .= ' AND um.`group_id` IN (' . $queryCatIds . ')';

                        $db->setQuery($query);
                        $aclItems = $db->loadObjectList();

                        if (count($aclItems) > 0)
                        {
                                foreach ($aclItems as $item)
                                {
                                        $result[] = $item;
                                }
                        }

                        //now get the guest subscribers
                        if (in_array('1', $categoryGrps) || in_array('0', $categoryGrps))
                        {

                                $query = 'SELECT * FROM `#__discuss_subscription` AS ds';
                                $query .= ' WHERE ds.`interval` = ' . $db->Quote($interval);
                                $query .= ' AND ds.`type` = ' . $db->Quote('site');
                                $query .= ' AND ds.`userid` = ' . $db->Quote('0');

                                $db->setQuery($query);
                                $nonAclItems = $db->loadObjectList();

                                if (count($nonAclItems) > 0)
                                {
                                        foreach ($nonAclItems as $item)
                                        {
                                                $result[] = $item;
                                        }
                                }
                        }
                } else
                {
                        $query = 'SELECT * FROM `#__discuss_subscription` AS ds'
                                . ' WHERE ds.`interval` = ' . $db->Quote($interval)
                                . ' AND ds.`type` = ' . $db->Quote('site');

                        $query .= $timeQuery;

                        $db->setQuery($query);

                        $result = $db->loadObjectList();
                }

                return $result;
        }

        function getTagSubscribers($tagid = '')
        {
                if (empty($tagid))
                {
                        //invalid tag id
                        return false;
                }

                $db = DiscussHelper::getDBO();

                $query = 'SELECT * FROM `#__discuss_subscription` '
                        . ' WHERE `type` = ' . $db->Quote('tag')
                        . ' AND `cid` = ' . $db->Quote($tagid);

                $db->setQuery($query);

                $result = $db->loadObjectList();
                $emails = array();
                $subscribers = array();

                foreach ($result as $row)
                {
                        if (!in_array($row->email, $emails))
                        {
                                $subscribers[] = $row;
                        }
                        $emails[] = $row->email;
                }
                return $subscribers;
        }

        function getCreatedPostByInterval($sent_out, $now = '')
        {
                $db = JFactory::getDbo();
                if (empty($now))
                {
                        $now = JFactory::getDate()->toSql();
                }

                $query = $db->getQuery(true);
                $query->select('DATEDIFF(' . $db->quote($now) . ', a.created) AS daydiff')
                        ->select('TIMEDIFF(' . $db->quote($now) . ', a.created) AS timediff, a.*')
                        ->from('#__discuss_posts AS a')
                        ->where('a.published = 1 and a.parent_id = 0 AND ( a.created > ' . $db->quote($sent_out) . ' AND a.created < ' . $db->quote($now) . ')')
                        ->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')')
                        ->order('a.created ASC');
                $db->setQuery($query);
                return $db->loadAssocList();
        }

        function isMySubscription($userid, $type, $subId)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('id')
                        ->from('#__discuss_subscription')
                        ->where($db->quoteName('type') . ' = ' . $db->quote($type))
                        ->where('id = ' . (int) $subId)
                        ->where('userid = ' . (int) $userid);
                $db->setQuery($query);
                return ( empty($db->loadResult()) ) ? false : true;
        }

        public function getSubscriptions()
        {
                $db = JFactory::getDbo();
                $nowSql = $db->quote(JFactory::getDate()->toSql());
                $nullDateSql = $db->quote($db->getNullDate());
                $my = JFactory::getUser();
                $email = JRequest::getVar('email');
                $query = $db->getQuery(true);
                $query->select('DATEDIFF(' . $nowSql . ', s.created ) AS noofdays')
                        ->select('DATEDIFF(' . $nowSql . ', IF(s.sent_out = ' . $nullDateSql . ', s.created, s.sent_out) ) AS daydiff')
                        ->select('TIMEDIFF(' . $nowSql . ', IF(s.sent_out = ' . $nullDateSql . ', s.created, s.sent_out) ) AS timediff')
                        ->select('IF(s.sent_out = ' . $nullDateSql . ', s.created, s.sent_out) AS lastsent')
                        ->select('s.*')
                        ->from('#__discuss_subscription AS s')
                        ->where('s.userid = ' . (int) $my->id);

                if ($email)
                {
                        $query->where('s.email = ' . $db->quote($email));
                }

                $db->setQuery($query);
                $result = $db->loadObjectList();
                $subscriptions = array();
                foreach ($result as $row)
                {
                        if ($row->type == 'post')
                        {
                                $query->clear();
                                $query->select('COUNT(*)')
                                        ->from('#__discuss_posts')
                                        ->where('id = ' . (int) $row->cid);
                                $db->setQuery($query);
                                if ($db->loadResult())
                                {
                                        $subscriptions[] = $row;
                                }
                        } else
                        {
                                $subscriptions[] = $row;
                        }
                }
                return $subscriptions;
        }

        public function isSubscribed($userid, $cid, $type = 'post')
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('id')
                        ->from('#__discuss_subscription')
                        ->where($db->quoteName('type') . ' = ' . $db->quote($type))
                        ->where('userid = ' . (int) $userid)
                        ->where('cid = ' . (int) $cid);
                $db->setQuery($query);
                return $db->loadResult();
        }

}

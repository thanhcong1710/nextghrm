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

class EasyDiscussModelBadges extends EasyDiscussModel
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
                $limit = JFactory::getApplication()->getUserStateFromRequest('com_easydiscuss.badges.limit', 'limit', DiscussHelper::getListLimit(), 'int');
                $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
                $this->setState('limit', $limit);
                $this->setState('limitstart', $limitstart);
        }

        /**
         * Retrieve a list of badges from the site
         *
         * @access public
         *
         * @param	null
         * @return	Array	An array of DiscussBadges object
         */
        public function getBadges($filter = array())
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('a.*')
                        ->from('#__discuss_badges AS a')
                        ->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');

                if (isset($filter['user']))
                {
                        $query->innerJoin('#__discuss_badges_users AS b ON b.badge_id = a.id AND b.published = 1');
                }

                $query->where('a.published = 1');

                if (isset($filter['user']))
                {
                        $query->where('b.user_id = ' . (int) $filter['user']);
                }

                $db->setQuery($query);
                $result = $db->loadObjectList();

                if (!$result)
                {
                        return $result;
                }

                $badges = array();
                foreach ($result as $res)
                {
                        $badge = DiscussHelper::getTable('Badges');
                        $badge->bind($res);
                        $badges[] = $badge;
                }

                return $badges;
        }

        /**
         * Delete badges based on user id
         *
         * @access public
         *
         * @param
         * @return state
         */
        public function removeBadges($userId = null)
        {
                try {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query->delete('#__discuss_badges_users')
                                ->where('user_id = ' . (int) $userId);

                        $db->setQuery($query);
                        $db->execute();
                        return true;
                } catch (Exception $ex) {
                        return false;
                }
        }

}

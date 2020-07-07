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

class EasyDiscussModelVotes extends EasyDiscussModel
{

        /**
         * Check if a user vote exists in the system.
         *
         * @since	3.0
         * @param	int		The unique post id.
         * @param	int 	The user's unique id.
         * @param	string	The user's ip address.
         * @param	string	The unique session id.
         * @return	boolean	True if user has already voted.
         */
        public function hasVoted($postId, $userId = null, $sessionId = null)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select("COUNT(*)")
                        ->from('#__discuss_votes');
                if ($userId)
                {
                        $query->where('user_id = ' . (int) $userId)
                                ->where('post_id = ' . (int) $postId);
                } else
                {
                        $query->where('post_id = ' . (int) $postId)
                                ->where('session_id = ' . (int) $sessionId);
                }

                $db->setQuery($query);
                $voted = $db->loadResult() ? true : false;
                return $voted;
        }

        /**
         * Gets the vote type.
         *
         * @since	3.0
         * @access	public
         * @param	int		The unique post id.
         * @param	int		The user's unique id.
         * @param	string	The unique session id.
         */
        function getVoteType($postId, $userId = null, $sessionId = null)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select($db->quoteName('value'))
                        ->from('#__discuss_votes');

                if ($userId)
                {
                        $query->where('user_id = ' . (int) $userId);
                } else
                {
                        $query->where('session_id = ' . $db->quote($sessionId));
                }

                $query->where('post_id = ' . $db->quote($postId));
                $db->setQuery($query);
                return $db->loadResult();
        }

        /**
         * Get's the total number of votes made for a specific post.
         *
         * @since	3.0
         * @param	int		The unique post id.
         * @return	int		The total number of votes.
         *
         */
        public function getTotalVotes($postId)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('SUM(' . $db->quoteName('value') . ') AS total')
                        ->from('#__discuss_votes')
                        ->where('post_id = ' . (int) $postId);
                $db->setQuery($query);
                return $db->loadResult();
        }

        /**
         * Gets a list of voters for a particular post.
         *
         * @since	3.0
         * @param	int 	The unique post id.
         * @return	Array	An array of voter objects.
         */
        public function getVoters($id)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('*')
                        ->from('#__discuss_votes')
                        ->where('post_id = ' . $db->quote($id));
                $db->setQuery($query);
                return $db->loadObjectList();
        }

}

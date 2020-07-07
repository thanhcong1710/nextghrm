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

class EasyDiscussModelConversation extends EasyDiscussModel
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

        function __construct()
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
         * Method to get a pagination object for the posts
         *
         * @access public
         * @return integer
         */
        public function getPagination()
        {
                return $this->_pagination;
        }

        /**
         * Adds a list of recipients that can see a particular message
         *
         * @since	3.0
         * @access	public
         * @param	int		The unique conversation id.
         * @param	int		The unique message id.
         * @param	int 	The unique recipient id.
         * @param	int		The unique creator id.
         */
        public function addMessageMap($conversationId, $messageId, $recipientId, $creator)
        {
                // Add record for recipient
                $map = DiscussHelper::getTable('ConversationMap');
                $map->user_id = $recipientId;
                $map->conversation_id = $conversationId;
                $map->message_id = $messageId;
                $map->isread = DISCUSS_CONVERSATION_UNREAD;
                $map->state = DISCUSS_CONVERSATION_PUBLISHED;
                $map->store();

                // Add a record for the creator.
                $map = DiscussHelper::getTable('ConversationMap');
                $map->user_id = $creator;
                $map->conversation_id = $conversationId;
                $map->message_id = $messageId;
                $map->isread = DISCUSS_CONVERSATION_READ;
                $map->state = DISCUSS_CONVERSATION_PUBLISHED;
                $map->store();

                return true;
        }

        /**
         * Adds a participant into a conversation
         *
         * @since	3.0
         * @access	public
         * @param	int		The conversation id.
         * @param	int 	The unique id of the user
         */
        public function addParticipant($conversationId, $participantId, $creatorId)
        {
                // Add recipient.
                $participant = DiscussHelper::getTable('ConversationParticipant');
                $participant->conversation_id = $conversationId;
                $participant->user_id = $participantId;
                $participant->store();

                // Add creator.
                $participant = DiscussHelper::getTable('ConversationParticipant');
                $participant->conversation_id = $conversationId;
                $participant->user_id = $creatorId;
                $participant->store();
                return true;
        }

        /**
         * Determines if the conversation is new for the particular node.
         *
         * @since	3.0
         * @access	public
         * @param	int		The unique conversation id.
         * @param	int 	The unique user id.
         * @return	boolean
         */
        public function isNew($conversationId, $userId)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_conversations AS a')
                        ->innerJoin('#__discuss_conversations_message AS b ON a.id = b.conversation_id')
                        ->innerJoin('#__discuss_conversations_message_maps AS c ON c.message_id = b.id AND c.isread = 0')
                        ->where('a.id = ' . (int) $conversationId)
                        ->where('c.user_id = ' . (int) $userId)
                        ->group('a.id');
                $db->setQuery($query);
                $isNew = $db->loadResult() > 0;
                return $isNew;
        }

        /**
         * Toggle a conversation read state.
         *
         * @since	3.0
         * @access	public
         * @param	int		The unique conversation id.
         * @param	int		The unique user id.
         * @param	int 	The read state
         */
        public function toggleRead($conversationId, $userId, $state)
        {
                try {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query->update('#__discuss_conversations_message_maps')
                                ->set('isread = ' . $db->quote($state))
                                ->where('conversation_id = ' . (int) $conversationId)
                                ->where('user_id = ' . (int) $userId);
                        $db->setQuery($query);
                        $db->execute();
                        return true;
                } catch (Exception $ex) {
                        return false;
                }
        }

        /**
         * Mark a conversation to old.
         *
         * @since	3.0
         * @access	public
         * @return	boolean
         * @param	int $conversationId
         * @param	int $userId
         */
        public function markAsRead($conversationId, $userId)
        {
                return $this->toggleRead($conversationId, $userId, DISCUSS_CONVERSATION_READ);
        }

        /**
         * Mark a conversation to new.
         *
         * @since	1.0
         * @access	public
         * @param	int $conversationId
         * @param	int $userId
         *
         * @return	boolean
         */
        public function markAsUnread($conversationId, $userId)
        {
                return $this->toggleRead($conversationId, $userId, DISCUSS_CONVERSATION_UNREAD);
        }

        /**
         * Archiving a conversation simply means modifying the state :)
         *
         * @since	1.0
         * @access	public
         * @param	int $conversationId
         * @param	int $nodeId
         * @return	boolean
         */
        public function archive($conversationId, $userId, $state = DISCUSS_CONVERSATION_ARCHIVED)
        {
                try {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query->update('#__discuss_conversations_message_maps')
                                ->set($db->quoteName('state') . ' = ' . $db->quote($state))
                                ->where('conversation_id = ' . (int) $conversationId)
                                ->where('user_id = ' . (int) $userId);
                        $db->setQuery($query);
                        $db->execute();
                        return true;
                } catch (Exception $ex) {
                        return false;
                }
        }

        /**
         * Remove the child message mapping for the particular node.
         *
         * @since	3.0
         * @access	public
         * @param	int		The unique conversation id.
         * @param	int 	The unique user id which owns the message mapping.
         * @return	boolean
         */
        public function delete($conversationId, $userId)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                // Delete the conversation items
                try {
                        $query->delete('#__discuss_conversations_message_maps')
                                ->where('conversation_id = ' . (int) $conversationId)
                                ->where('user_id = ' . (int) $userId);
                        $db->setQuery($query);
                        $db->execute();
                } catch (Exception $ex) {
                        return false;
                }

                // @rule: Check if this is the last child item. If it is the last, we should delete everything else.
                $query->clear();
                $query->select('COUNT(DISTINCT (c.user_id))')
                        ->from('#__discuss_conversations AS a')
                        ->innerJoin('#__discuss_conversations_message AS b ON a.id = b.conversation_id')
                        ->innerJoin('#__discuss_conversations_message_maps AS c ON b.id = c.message_id')
                        ->where('a.id = ' . (int) $conversationId)
                        ->where('c.user_id != ' . (int) $userId)
                        ->group('a.id');
                $db->setQuery($query);
                $total = $db->loadResult();
                if ($total <= 0)
                {
                        return $this->cleanup();
                }
                return true;
        }

        /**
         * Completely removes the conversation from the site.
         *
         * @return	boolean
         * @param	int $conversationId
         */
        private function cleanup($conversationId)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                try {
                        // @rule: Delete conversation first
                        $query->delete('#__discuss_conversations')
                                ->where('id = ' . (int) $conversationId);
                        $db->setQuery($query);
                        $db->execute();

                        // @rule: Delete messages for the conversation.
                        $query->clear();
                        $query->delete('#__discuss_conversations_message')
                                ->where('conversation_id = ' . (int) $conversationId);
                        $db->setQuery($query);
                        $db->execute();

                        // @rule: Delete messages mapping for the conversation.
                        $query->clear();
                        $query->delete('#__discuss_conversations_message_maps')
                                ->where('conversation_id = ' . (int) $conversationId);
                        $db->setQuery($query);
                        $db->execute();

                        // @rule: Delete participants for the conversation.
                        $query->clear();
                        $query->delete('#__discuss_conversations_participants')
                                ->where('conversation_id = ' . (int) $conversationId);
                        $db->setQuery($query);
                        $db->execute();

                        return true;
                } catch (Exception $ex) {
                        return false;
                }
        }

        /**
         * Checks whether or not the node id has any access to the conversation.
         *
         * @return	boolean
         * @param	int $conversationId
         * @param	int $userId
         */
        public function hasAccess($conversationId, $userId)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_conversations_participants')
                        ->where('conversation_id = ' . (int) $conversationId)
                        ->where('user_id = ' . (int) $userId);
                $db->setQuery($query);
                return ( $db->loadResult() > 0 );
        }

        /**
         * Retrieves a list of users who are participating in a conversation.
         *
         * @since	3.0
         * @access	public
         * @param	integer	$conversationId		The unique id of that conversation
         * @param	array	$currentUserId		Exlude a list of nodes
         *
         * @return	array	An array that contains SocialUser objects.
         */
        public function getParticipants($conversationId, $currentUserId = null)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('DISTINCT(a.user_id)')
                        ->from('#__discuss_conversations_participants AS a')
                        ->where('a.conversation_id = ' . (int) $conversationId);
                if (!is_null($currentUserId))
                {
                        $query->where('a.user_id != ' . (int) $currentUserId);
                }
                $db->setQuery($query);
                return $db->loadAssocList(null, 'user_id');
        }

        /**
         * Retrieves a list of messages in a particular conversation
         *
         * @since	3.0
         * @access	public
         * @param	int		The unique id of that conversation
         * @param	int		The current user id of the viewer.
         *
         */
        public function getMessages($conversationId, $userId, $viewAll = false, $count = false)
        {
                $db = JFactory::getDbo();
                $nowSql = $db->quote(JFactory::getDate()->toSql());
                $config = DiscussHelper::getConfig();
                $operation = '( UNIX_TIMESTAMP(' . $nowSql . ') - UNIX_TIMESTAMP( a.created) )';

                $query = $db->getQuery(true);
                $query->select('a.*')
                        ->select('FLOOR(' . $operation . ' / 60 / 60 / 24) AS daydiff')
                        ->from('#__discuss_conversations_message AS a')
                        ->leftJoin('#__discuss_conversations_message_maps AS b ON b.message_id = a.id')
                        ->where('a.conversation_id = ' . (int) $conversationId)
                        ->where('b.user_id = ' . (int) $userId);
                // @rule: Messages ordering.
                // @TODO: respect ordering settings.
                $query->order('a.created ASC');

                // By default show the latest messages limit by the numbers specified in backend
                $limit = 0;
                if (!$viewAll)
                {
                        $limit = $config->get('main_messages_limit', 5);
                }

                // If view == 'all', do nothing because we wanted to show all messages.
                if ($viewAll == 'previous')
                {
                        // View another 5 more previous messages
                        $limit = $config->get('main_messages_limit', 5) + $count;
                }
                $db->setQuery($query, 0, $limit);

                $rows = $db->loadObjectList();
                $messages = array();
                foreach ($rows as $row)
                {
                        $message = DiscussHelper::getTable('ConversationMessage');
                        $message->bind($row);
                        $message->daydiff = $row->daydiff;
                        $messages[] = $message;
                }
                return $messages;
        }

        /**
         * Retrieves a total number of conversations for a particular user
         *
         * @since	3.0
         * @access	public
         * @param	integer $userId
         * @param       array   $options
         */
        public function getCount($userId, $options = array())
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(a.id)')
                        ->from('#__discuss_conversations AS a')
                        ->innerJoin('#__discuss_conversations_message AS b ON a.id = b.conversation_id')
                        ->innerJoin('#__discuss_conversations_message_maps AS c ON c.message_id = b.id')
                        ->where('c.user_id = ' . (int) $userId)
                        ->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');

                // @rule: Respect filter options
                if (isset($options['filter']))
                {
                        switch ($options['filter']) {
                                case 'unread':
                                        $query->where('c.isread = ' . $db->quote(DISCUSS_CONVERSATION_UNREAD));
                                        break;
                        }
                }

                // @rule: Process any additional filters here.
                if (isset($options['archives']) && $options['archives'])
                {
                        $query->where('c.' . $db->quoteName('state') . ' = ' . $db->quote(DISCUSS_CONVERSATION_ARCHIVED));
                } else
                {
                        $query->where('c.' . $db->quoteName('state') . ' = ' . $db->quote(DISCUSS_CONVERSATION_PUBLISHED));
                }

                $query->group('a.id');
                $db->setQuery($query);
                return $db->loadResult();
        }

        /**
         * Retrieves a list of conversations for a particular node
         *
         * @since	3.0
         * @access	public
         * @param	int		The current user id of the viewer
         */
        public function getConversations($userId, $options = array())
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('a.*, b.message, c.isread')
                        ->from('#__discuss_conversations AS a')
                        ->innerJoin('#__discuss_conversations_message AS b ON a.id = b.conversation_id')
                        ->innerJoin('#__discuss_conversations_message_maps AS c ON c.message_id = b.id')
                        ->where('c.user_id = ' . (int) $userId)
                        ->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');

                // @rule: Process any additional filters here.
                if (isset($options['archives']) && $options['archives'])
                {
                        $query->where('c.' . $db->quoteName('state') . ' = ' . $db->quote(DISCUSS_CONVERSATION_ARCHIVED));
                } else
                {
                        $query->where('c.' . $db->quoteName('state') . ' = ' . $db->quote(DISCUSS_CONVERSATION_PUBLISHED));
                }

                // @rule: Respect filter options
                if (isset($options['filter']))
                {
                        switch ($options['filter']) {
                                case 'unread':
                                        $query->where('c.' . $db->quoteName('isread') . ' = ' . $db->quote(DISCUSS_CONVERSATION_UNREAD));
                                        break;
                        }
                }

                $query->group('b.conversation_id');
                $sorting = isset($options['sorting']) ? $options['sorting'] : 'latest';

                switch ($sorting) {
                        case 'latest':
                        default:
                                $query->order('a.lastreplied DESC');
                                break;
                }


                // If limit is provided, only show certain number of items.
                $limit = 0;
                if (isset($options['limit']))
                {
                        $limit = $options['limit'];
                        $limitstart = 0;
                } else
                {
                        $limitstart = $this->getState('limitstart');
                        $limit = $this->getState('limit');
                        $totalQuery = $db->getQuery(true);
                        $totalQuery->select('COUNT(*)')
                                ->from('(' . $query . ') AS x');
                        $db->setQuery($totalQuery);
                        $total = $db->loadResult();
                        $this->_pagination = DiscussHelper::getPagination($total, $limitstart, $limit);
                }

                $db->setQuery($query, $limitstart, $limit);
                $rows = $db->loadObjectList();
                if (!$rows)
                {
                        return $rows;
                }

                foreach ($rows as $row)
                {
                        $conversation = DiscussHelper::getTable('Conversation');
                        $conversation->bind($row);

                        $conversations[] = $conversation;
                }
                return $conversations;
        }

        /**
         * Inserts a new reply into an existing conversation.
         *
         * @since	1.0
         * @access	public
         * @param	int		$conversationId		The conversation id.
         * @param	string	$message			The content of the reply.
         * @param 	int 	$creatorId			The user's id.
         *
         * @return	SocialTableConversationMessage	The message object
         */
        public function insertReply($conversationId, $content, $creatorId)
        {
                $conversation = DiscussHelper::getTable('Conversation');
                $conversation->load($conversationId);
                $now = JFactory::getDate()->toSql();
                // Store the new message first.
                $message = DiscussHelper::getTable('ConversationMessage');
                $message->conversation_id = $conversationId;
                $message->message = $content;
                $message->created_by = $creatorId;
                $message->created = $now;
                $message->store();

                // Since a new message is added, add the visibility of this new message to the participants.
                $users = $this->getParticipants($conversation->id);

                foreach ($users as $userId)
                {
                        $map = DiscussHelper::getTable('ConversationMap');
                        $map->user_id = $userId;
                        $map->conversation_id = $conversation->id;
                        $map->state = DISCUSS_CONVERSATION_PUBLISHED;
                        $map->isread = $userId == $creatorId ? DISCUSS_CONVERSATION_READ : DISCUSS_CONVERSATION_UNREAD;
                        $map->message_id = $message->id;
                        $map->store();
                }

                // Update the last replied date.
                $conversation->lastreplied = $now;
                $conversation->store();
                return $message;
        }

}

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

class EasyDiscussModelMessaging extends EasyDiscussModel
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
                $app = JFactory::getApplication();
                $limit = ($app->getCfg('list_limit') == 0) ? 5 : DiscussHelper::getListLimit();
                $limitstart = JRequest::getVar('limitstart', 0, 'REQUEST');

                // In case limit has been changed, adjust it
                $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

                $this->setState('limit', $limit);
                $this->setState('limitstart', $limitstart);
        }

        /**
         * Delete's a message from the system.
         *
         * @since	3.0
         * @access	public
         * @param	int 	The unique message id.
         * @param	int		The unique user id.
         */
        public function delete($messageId, $userId)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_messages_states')
                        ->where($db->quoteName('deleted') . ' = 1')
                        ->where('message_id = ' . (int) $messageId);
                $db->setQuery($query);
                $total = $db->loadResult();
                // If nothing has been deleted before, we need to update the deletion part.
                if ($total == 0)
                {
                        // Just mark the message as deleted.
                        try {
                                $query->clear();
                                $query->update('#__discuss_messages_states')
                                        ->set($db->quoteName('deleted') . ' = 1')
                                        ->set('deleted_time = ' . $db->quote(JFactory::getDate()->toSql()))
                                        ->where('message_id = ' . (int) $messageId)
                                        ->where('user_id = ' . (int) $userId);
                                $db->setQuery($query);
                                $db->execute();
                                return true;
                        } catch (Exception $ex) {
                                return false;
                        }
                }

                // If there is already 1 record, it means we need to delete the entire message.
                // First, we need to delete the replies.
                $this->deleteMeta($messageId);

                // Delete the states
                $this->deleteMessageStates($messageId);

                // Delete the message
                $this->deleteMessage($messageId);

                return true;
        }

        /**
         * Reply existing message.
         *
         * @since	3.0
         * @access	public
         * @param	DiscussMessage		The message object
         * @param	string				The message content.
         * @param	int 				The unique user id.
         */
        public function reply(DiscussMessage $message, $content, $userId)
        {
                // Update all records in {#__discuss_messages_states} to ensure that `deleted` column is false.
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->update('#__discuss_messages_states')
                        ->set($db->quoteName('deleted') . ' = 0')
                        ->where('message_id = ' . (int) $message->id);
                $db->setQuery($query);
                $db->execute();

                // Store the message meta.
                $meta = DiscussHelper::getTable('MessageMeta');
                $meta->message_id = $message->id;
                $meta->message = $content;
                $meta->created = JFactory::getDate()->toSql();
                $meta->created_by = $userId;
                $meta->isparent = false;
                $meta->store();

                return $meta;
        }

        /**
         * Delete message.
         *
         * @since	3.0
         * @access	public
         * @param	int		The unique message id.
         * @return	bool	True if success and false otherwise.
         */
        public function deleteMessage($messageId)
        {
                try {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query->delete('#__discuss_messages')
                                ->where('id = ' . (int) $messageId);
                        $db->setQuery($query);
                        $db->execute();
                        return true;
                } catch (Exception $ex) {
                        return false;
                }
        }

        /**
         * Delete all message states with the provided message id.
         *
         * @since	3.0
         * @access	public
         * @param	int		The unique message id.
         * @return	bool	True if success and false otherwise.
         */
        public function deleteMessageStates($messageId)
        {
                try {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query->delete('#__discuss_messages_states')
                                ->where('message_id = ' . (int) $messageId);
                        $db->setQuery($query);
                        $db->execute();
                        return true;
                } catch (Exception $ex) {
                        return false;
                }
        }

        /**
         * Delete all message meta with the provided message id.
         *
         * @since	3.0
         * @access	public
         * @param	int		The unique message id.
         * @return	bool	True if success and false otherwise.
         */
        public function deleteMeta($messageId)
        {
                try {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query->delete('#__discuss_messages_meta')
                                ->where('message_id = ' . (int) $messageId);
                        $db->setQuery($query);
                        $db->execute();
                        return true;
                } catch (Exception $ex) {
                        return false;
                }
        }

        /**
         * Determines if the provided user id is a participant of a conversation.
         *
         * @since	3.0
         * @access	public
         * @param	int		The unique user id.
         * @param	int		The unique message id.
         * @return	bool	True if user is a participant, false otherwise.
         */
        public function isParticipant($userId, $messageId)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_messages_states')
                        ->where('user_id = ' . (int) $userId)
                        ->where('message_id = ' . (int) $messageId);
                $db->setQuery($query);
                return $db->loadResult();
        }

        /**
         * Method to get a pagination object for the categories
         *
         * @access public
         * @return integer
         */
        function getPagination()
        {
                return $this->_pagination;
        }

        /**
         * Initializes the state records for a single message.
         *
         * @since	3.0
         * @access	public
         * @param	DiscussMessage
         */
        public function initStates(DiscussMessage $message)
        {
                if (!$message->created_by || !$message->recipient)
                {
                        return false;
                }

                // Store creator's state
                $state = DiscussHelper::getTable('MessageState');
                $state->message_id = $message->id;
                $state->user_id = $message->created_by;

                // Creator's read state is always marked as is read.
                $state->isread = DISCUSS_MESSAGING_READ;
                $state->store();

                // Store recipient state
                $state = DiscussHelper::getTable('MessageState');
                $state->message_id = $message->id;
                $state->user_id = $message->recipient;
                $state->store();

                return true;
        }

        /**
         * Retrieves a list of messages.
         *
         * @since	3.0
         * @param	array 	An array of options.
         */
        public function getMessages($options = array())
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('a.*, b.title AS title, b.message AS message')
                        ->from('#__discuss_messages AS a')
                        ->innerJoin('#__discuss_messages_meta AS b ON b.message_id = a.id')
                        ->innerJoin('#__discuss_messages_states AS c ON c.message_id = a.id');

                if (isset($options['user_id']))
                {
                        $query->where('c.user_id = ' . (int) $options['user_id']);

                        // If user's id is provided, we need to only retrieve messages for a particular user.
                        $query->where('(a.recipient = ' . (int) $options['user_id'] . ' OR b.created_by != ' . (int) $options['user_id'] . ')');
                        $query->where('c.' . $db->quoteName('deleted') . ' = 0');
                }

                $query->group('a.id');
                $db->setQuery($query);
                return $db->loadObjectList();
        }

        /**
         * Gets the main message of a conversation.
         *
         * @since	3.0
         * @access	public
         * @param	int		The unique messaging id.
         * @return	DiscussMessage
         */
        public function getReplies($messageId, DiscussMessageState $state)
        {
                // Load the first initial message tied to the message object.
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('*')
                        ->from('#__discuss_messages_meta AS a')
                        ->where('a.message_id = ' . (int) $messageId)
                        ->where('a.isparent = 0');

                // If there's a `deleted_time` state, we need to only fetch records that are created after the deletion time.
                if ($state->deleted_time != $db->getNullDate())
                {
                        $query->where('a.created > ' . $db->quote($state->deleted_time));
                }

                $db->setQuery($query);
                $result = $db->loadObjectList();
                if (!$result)
                {
                        return $result;
                }

                $replies = array();
                foreach ($result as $row)
                {
                        $meta = DiscussHelper::getTable('MessageMeta');
                        $meta->bind($row);
                        $replies[] = $meta;
                }

                return $replies;
        }

        /**
         * Gets the main message of a conversation.
         *
         * @since	3.0
         * @access	public
         * @param	int		The unique messaging id.
         * @return	DiscussMessage
         */
        public function getMessage($messageId)
        {
                // Load the first initial message tied to the message object.
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('a.*')
                        ->from('#__discuss_messages_meta AS a')
                        ->innerJoin('#__discuss_messages_states AS b ON a.message_id = b.message_id')
                        ->where('a.message_id = ' . (int) $messageId)
                        ->where('b.' . $db->quoteName('deleted') . ' = 0')
                        ->where('a.isparent = 1');
                $db->setQuery($query);
                $result = $db->loadObject();
                $messageMeta = DiscussHelper::getTable('MessageMeta');
                $messageMeta->bind($result);
                return $messageMeta;
        }

        /**
         * Retrieves the total number of new messages for a user.
         *
         * @since	3.0
         * @access	public
         * @param	int		The user's unique id.
         * @return	int		The total number of new messages.
         */
        public function getNewMessagesCount($userId)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_messages AS a')
                        ->innerJoin('#__discuss_messages_states AS b ON b.message_id = a.id')
                        ->where('b.user_id = ' . (int) $userId)
                        ->where('b.isread = ' . $db->quote(DISCUSS_MESSAGING_UNREAD))
                        ->where('b.' . $db->quoteName('deleted') . ' = 0');
                $db->setQuery($query);
                return $db->loadResult();
        }

        /**
         * Marks a specified message as read.
         *
         * @since	3.0
         * @access	public
         * @param	int		The message id.
         * @param	int		The unique user id.
         */
        public function markUnRead($messageId, $userId)
        {
                return $this->markRead($messageId, $userId, DISCUSS_MESSAGING_UNREAD);
        }

        /**
         * Marks a specified message as read.
         *
         * @since	3.0
         * @access	public
         * @param	int		The message id.
         * @param	int		The unique user id.
         */
        public function markRead($messageId, $userId, $state = DISCUSS_MESSAGING_READ)
        {
                try {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query->update('#__discuss_messages_states')
                                ->set('isread = ' . $db->quote($state))
                                ->where('message_id = ' . (int) $messageId)
                                ->where('user_id = ' . (int) $userId);
                        $db->setQuery($query);
                        $db->execute();
                        return true;
                } catch (Exception $ex) {
                        return false;
                }
        }

}

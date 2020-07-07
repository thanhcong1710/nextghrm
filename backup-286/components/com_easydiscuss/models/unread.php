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

class EasyDiscussModelUnread extends EasyDiscussModel
{

        function __construct()
        {
                parent::__construct();

                $limit = JFactory::getApplication()->getUserStateFromRequest('com_easydiscuss.categories.limit', 'limit', DiscussHelper::getListLimit(), 'int');
                $limitstart = JRequest::getVar('limitstart', 0, '', 'int');

                $this->setState('limit', $limit);
                $this->setState('limitstart', $limitstart);
        }

        public function getUnreadPosts($postId)
        {
                // Read/Unread
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select($db->quoteName('status'))
                        ->from('#__discuss_unread')
                        ->where('post_id = ' . (int) $postId);
                $db->setQuery($query);
                return $db->loadResult();
        }

}

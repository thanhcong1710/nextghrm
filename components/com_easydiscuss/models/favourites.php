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

class EasyDiscussModelFavourites extends EasyDiscussModel
{

        function __construct()
        {
                parent::__construct();
                $limit = JFactory::getApplication()->getUserStateFromRequest('com_easydiscuss.categories.limit', 'limit', DiscussHelper::getListLimit(), 'int');
                $limitstart = JRequest::getVar('limitstart', 0, '', 'int');

                $this->setState('limit', $limit);
                $this->setState('limitstart', $limitstart);
        }

        public function isFav($postId, $userId, $type = 'post')
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('id')
                        ->from('#__discuss_favourites')
                        ->where('created_by = ' . (int) $userId)
                        ->where('post_id = ' . (int) $postId)
                        ->where($db->quoteName('type') . ' = ' . $db->quote($type));

                $db->setQuery($query);
                $result = $db->loadResultArray();
                return ( empty($result) ? false : true );
        }

        public function addFav($postId, $userId, $type = 'post')
        {
                $fav = DiscussHelper::getTable('Favourites');
                $fav->created_by = $userId;
                $fav->post_id = $postId;
                $fav->type = $type;
                $fav->created = JFactory::getDate()->toSql();
                if (!$fav->store())
                {
                        return false;
                }
                return true;
        }

        public function removeFav($postId, $userId, $type = 'post')
        {
                // Remove favourite for single user at specific post
                try {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query->delete('#__discuss_favourites')
                                ->where('created_by = ' . (int) $userId)
                                ->where('post_id = ' . (int) $postId)
                                ->where($db->quoteName('type') . ' = ' . $db->quote($type));
                        $db->setQuery($query);
                        $db->execute();
                        return true;
                } catch (Exception $ex) {
                        return false;
                }
        }

        /**
         * Retrieve favourite count.
         *
         * @since	3.0
         * @access	public
         */
        public function getFavouritesCount($id, $type = 'post')
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_favourites')
                        ->where('post_id = ' . (int) $id)
                        ->where($db->quoteName('type') . ' = ' . $db->quote($type));
                $db->setQuery($query);
                return $db->loadResult();
        }

        public function deleteAllFavourites($id)
        {
                if (!$id)
                {
                        return false;
                }

                try {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);

                        $query->delete('#__discuss_favourites')
                                ->where('post_id = ' . (int) $id);
                        $db->setQuery($query);
                        $db->execute();
                        return true;
                } catch (Exception $ex) {
                        return false;
                }
        }

}

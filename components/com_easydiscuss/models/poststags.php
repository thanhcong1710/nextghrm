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

class EasyDiscussModelPostsTags extends EasyDiscussModel
{

        static $_postTags = array();

        function setPostTagsBatch($ids)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                if (count($ids) > 0)
                {
                        $query->select('a.id, a.title, a.alias, b.post_id')
                                ->from('#__discuss_tags AS a')
                                ->leftJoin('#__discuss_posts_tags AS b ON a.id = b.tag_id');

                        if (count($ids) == 1)
                        {
                                $query->where('b.post_id = ' . (int) $ids[0]);
                        } else
                        {
                                $query->where('b.post_id IN (' . implode(',', $ids) . ')');
                        }

                        $query->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                        $db->setQuery($query);
                        $result = $db->loadObjectList();

                        if (count($result) > 0)
                        {
                                foreach ($result as $item)
                                {
                                        self::$_postTags[$item->post_id][] = $item;
                                }
                        }

                        foreach ($ids as $id)
                        {
                                if (!isset(self::$_postTags[$id]))
                                {
                                        self::$_postTags[$id] = array();
                                }
                        }
                }
        }

        /*
         * method to get post tags.
         *
         * param postId - int
         * return object list
         */

        function getPostTags($postId)
        {
                if (isset(self::$_postTags[$postId]))
                {
                        return self::$_postTags[$postId];
                }

                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('a.id, a.title, a.alias')
                        ->from('#__discuss_tags AS a')
                        ->leftJoin('#__discuss_posts_tags AS b ON a.id = b.tag_id')
                        ->where('b.post_id = ' . (int) $postId)
                        ->where('a.published = 1')
                        ->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                $db->setQuery($query);
                $result = $db->loadObjectList();
                self::$_postTags[$postId] = $result;
                return $result;
        }

        function add($tagId, $postId, $creationDate)
        {
                $db = JFactory::getDbo();
                $obj = new stdClass();
                $obj->tag_id = $tagId;
                $obj->post_id = $postId;
                $obj->created = $creationDate;
                return $db->insertObject('#__discuss_posts_tags', $obj);
        }

        function deletePostTag($postId)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->delete('#__discuss_posts_tags')
                        ->where('post_id = ' . (int) $postId);
                $db->setQuery($query);
                try {
                        $db->execute();
                        return true;
                } catch (Exception $ex) {
                        return false;
                }
        }

}

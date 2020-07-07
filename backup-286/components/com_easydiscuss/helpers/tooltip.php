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
include_once DISCUSS_CLASSES . '/json.php';

class DiscussTooltipHelper
{
        /*
         * Returns a html formatted string for a standard tooltip.
         *
         * @param	$userId		The subject's user id.
         * @return	$html		A string representing the tooltip's html
         */

        public function getHTML($content, $options)
        {
                $json = new Services_JSON();
                $options = $json->encode($options);

                $themes = new CodeThemes();
                $themes->set('content', $content);
                $themes->set('options', $options);

                return $themes->fetch('tooltip.php');
        }

        /*
         * Returns a html formatted string for the blogger's tooltip.
         *
         * @param	$userId		The subject's user id.
         * @return	$html		A string representing the tooltip's html
         */

        public function getPosterHTML($userId, $options)
        {
                $user = DiscussHelper::getTable('Profile');
                $user->load($userId);

                $json = new Services_JSON();
                $options = $json->encode($options);

                $themes = new DiscussThemes();
                $themes->set('user', $user);
                $themes->set('options', $options);

                return $themes->fetch('tooltip.poster.php');
        }

        public function getLastRepliesHTML($postId = '0', $options)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('DISTINCT a.user_id')
                        ->from('#__discuss_posts AS a')
                        ->where('a.published = 1')
                        ->where('a.parent_id = ' . (int) $postId)
                        ->where('a.user_type = ' . $db->quote('member'))
                        ->where('a.user_id != 0')
                        ->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');

                $db->setQuery($query, 0, 8);
                $replies = $db->loadObjectList();
                $json = new Services_JSON();
                $options = $json->encode($options);
                $themes = new DiscussThemes();
                $themes->set('replies', $replies);
                $themes->set('options', $options);
                return $themes->fetch('tooltip.lastreplies.php');
        }

}

<?php

/**
 * @package		EasyDiscuss
 * @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * EasyDiscuss is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Restricted access');

require_once DISCUSS_HELPERS . '/image.php';
require_once DISCUSS_HELPERS . '/integrate.php';

class DiscussProfile extends JTable
{

        public $id = null;
        public $nickname = null;
        public $avatar = null;
        public $description = null;
        public $url = null;
        public $params = null;
        public $user = null;
        public $alias = null;
        public $points = null;
        public $latitude = null;
        public $longitude = null;
        public $location = null;
        public $signature = null;
        public $site = null;

        /**
         * Determines if the user's profile has been edited or not.
         * @var bool
         */
        public $edited = null;

        /**
         * store the posts that has been read by user
         * @var serialized string.
         */
        public $posts_read = null;
        private $_data = array();
        static $instances = array();

        /*
         * Below attribute are the virtual which created when user is being loaded.
         *
         * numPostCreated
         * numPostAnswered
         * created
         */

        /**
         * Constructor for this class.
         *
         * @return
         * @param object $db
         */
        public function __construct(& $db)
        {
                parent::__construct('#__discuss_users', 'id', $db);

                $this->numPostCreated = 0;
                $this->numPostAnswered = 0;
                $this->profileLink = '';
                $this->avatarLink = '';
        }

        public function bind($data, $ignore = array())
        {
                parent::bind($data);

                $this->url = $this->_appendHTTP($this->url);

                $this->user = JFactory::getUser($this->id);

                //default to nickname for blogger alias if empty
                if (empty($this->alias))
                {
                        $this->alias = $this->nickname;
                }

                if (empty($this->alias))
                {
                        $this->alias = $this->user->username;
                }

                $this->alias = DiscussHelper::permalinkSlug($this->alias);

                return true;
        }

        public function _createDefault($id)
        {
                $db = DiscussHelper::getDBO();
                $user = JFactory::getUser($id);

                if ($user->id)
                {
                        $obj = new stdClass();
                        $obj->id = $user->id;
                        $obj->nickname = $user->name;
                        $obj->avatar = 'default.png';
                        $obj->description = '';
                        $obj->url = '';
                        $obj->params = '';

                        //default to username for blogger alias
                        $obj->alias = DiscussHelper::permalinkSlug($user->username);

                        $isCreated = $db->insertObject('#__discuss_users', $obj);
                        if ($isCreated)
                        {
                                $this->bind($obj);
                        }
                }
        }

        public function init($id = null)
        {
                if (is_array($id))
                {
                        $tmpArr = array();
                        foreach ($id as $uid)
                        {
                                if (!isset(self::$instances[$uid]))
                                {
                                        $tmpArr[] = $uid;
                                }
                        }

                        if (empty($tmpArr))
                        {
                                return;
                        }
                        if (count($tmpArr) == 1)
                        {
                                $id = array_pop($tmpArr);
                                self::load($id);
                        } else
                        {
                                $db = JFactory::getDbo();
                                $numPostCreatedQuery = $db->getQuery(true);
                                $numPostCreatedQuery->select('COUNT(*)')
                                        ->from('#__discuss_posts AS p1')
                                        ->where('p1.user_id = a.id AND p1.parent_id = 0 AND p1.published = 1');

                                $numPostAnsweredQuery = $db->getQuery(true);
                                $numPostAnsweredQuery->select('COUNT(*)')
                                        ->from('#__discuss_posts AS p2')
                                        ->where('p2.user_id = a.id AND p2.parent_id != 0 AND p2.published = 1');

                                $query = $db->getQuery(true);
                                $query->select('a.*')
                                        ->select('(' . $numPostCreatedQuery . ') AS numPostCreated')
                                        ->select('(' . $numPostAnsweredQuery . ') AS numPostAnswered')
                                        ->from('#__discuss_users AS a')
                                        ->where('a.id IN (' . implode(',', $tmpArr) . ')');
                                $db->setQuery($query);
                                $results = $db->loadObjectList();

                                // $numPostCreated	 = self::getNumTopicPostedGroup( $tmpArr );
                                // $numPostAnswered = self::getNumTopicAnsweredGroup( $tmpArr );

                                foreach ($results as $row)
                                {
                                        $user = new DiscussProfile($db);
                                        $user->bind($row);

                                        // $user->numPostCreated	= isset( $numPostCreated[$row->id] ) ? $numPostCreated[$row->id] : 0;
                                        // $user->numPostAnswered	= isset( $numPostAnswered[$row->id] ) ? $numPostAnswered[$row->id] : 0;

                                        $juser = JFactory::getUser($row->id);
                                        $user->user = $juser;

                                        self::$instances[$row->id] = $user;
                                }
                        }
                } else
                {
                        self::load($id);
                }
        }

        /**
         * override load method.
         * if user record not found in eblog_profile, create one record.
         *
         */
        public function load($id = null, $reset = true, $reload = false)
        {
                if (!isset(self::$instances[$id]))
                {
                        $createNew = false;

                        if (!empty($id))
                        {
                                $state = parent::load($id);

                                if (!$state)
                                {
                                        $this->_createDefault($id);
                                        $createNew = true;
                                }
                        }

                        if (!$createNew)
                        {
                                $this->numPostCreated = $this->getNumTopicPosted();
                                $this->numPostAnswered = $this->getNumTopicAnswered();
                        }

                        $user = JFactory::getUser($id);
                        $this->user = $user;

                        self::$instances[$id] = $this;
                } else
                {
                        // At times we might want to reload the user's data.
                        if ($reload)
                        {
                                parent::load($id);

                                $this->numPostCreated = $this->getNumTopicPosted();
                                $this->numPostAnswered = $this->getNumTopicAnswered();

                                $user = JFactory::getUser($id);
                                $this->user = $user;

                                $users[$id] = $this;
                        } else
                        {
                                $this->bind(self::$instances[$id]);
                        }
                }

                return self::$instances[$id];
        }

        public function store($updateNulls = false)
        {
                $tmpNumPostCreated = $this->numPostCreated;
                $tmpNumPostAnswered = $this->numPostAnswered;
                $tmpProfileLink = $this->profileLink;
                unset($this->numPostCreated);
                unset($this->numPostAnswered);
                unset($this->profileLink);
                unset($this->avatarLink);

                $result = parent::store();

                if ($result)
                {
                        $this->numPostCreated = $tmpNumPostCreated;
                        $this->numPostAnswered = $tmpNumPostAnswered;
                        $this->profileLink = $tmpProfileLink;
                }

                return $result;
        }

        public function setUser($my)
        {
                $this->load($my->id);
                $this->user = $my;
        }

        public function getLink($anchor = '')
        {
                if (!isset($this->profileLink) || empty($this->profileLink))
                {
                        $integrate = new DiscussIntegrate;
                        $field = $integrate->getField($this);

                        $config = DiscussHelper::getConfig();

                        if (!$config->get('layout_avatarLinking'))
                        {
                                // Always enforce to use EasyDiscuss profile linking if needed.
                                $this->profileLink = DiscussRouter::_('index.php?option=com_easydiscuss&view=profile&id=' . $this->id, false) . $anchor;
                        } else
                        {
                                $this->profileLink = $field['profileLink'];
                        }
                }

                return $this->profileLink;
        }

        public function getLinkHTML($defaultGuestName = '')
        {
                if ($this->id == 0)
                {
                        return $this->getName($defaultGuestName);
                }
                return '<a href="' . $this->getLink() . '" title="' . $this->getName() . '">' . $this->getName() . '</a>';
        }

        /**
         * Adds a badge for a specific user.
         *
         * @since	3.0
         * @access	public
         */
        public function addBadge($badgeId)
        {
                // Check if there's already a badge assigned to the user.
                $badgeUser = DiscussHelper::getTable('BadgesUsers');

                $exists = $badgeUser->loadByUser($this->id, $badgeId);

                if ($exists)
                {
                        $this->setError('Badge is already assigned to the user.');
                        return false;
                }

                $badgeUser->badge_id = $badgeId;
                $badgeUser->user_id = $this->id;
                $badgeUser->created = DiscussHelper::getDate()->toMySQL();
                $badgeUser->published = 1;

                return $badgeUser->store();
        }

        public function addPoint($point)
        {
                $this->points += $point;
        }

        public function getName($default = '')
        {
                if ($this->id == 0)
                {
                        return $default ? $default : JText::_('COM_EASYDISCUSS_GUEST');
                }

                $config = DiscussHelper::getConfig();
                $displayname = $config->get('layout_nameformat');

                switch ($displayname) {
                        case "name" :
                                $name = $this->user->name;
                                break;
                        case "username" :
                                $name = $this->user->username;
                                break;
                        case "nickname" :
                        default :
                                $name = (empty($this->nickname)) ? $this->user->name : $this->nickname;
                                break;
                }
                return $name;
        }

        public function getId()
        {
                return $this->id;
        }

        public function getOriginalAvatar()
        {
                jimport('joomla.filesystem.file');
                $config = DiscussHelper::getConfig();

                if ($config->get('layout_avatarIntegration') == 'jfbconnect')
                {
                        $integrate = new DiscussIntegrate;
                        $hasAvatar = $integrate->jfbconnect($this);

                        if ($hasAvatar)
                        {
                                return false;
                        }
                } else if ($config->get('layout_avatarIntegration') != 'default')
                {
                        return false;
                }

                $path = JPATH_ROOT . '/' . trim($config->get('main_avatarpath'), DIRECTORY_SEPARATOR);

                // If original image doesn't exist, skip this
                if (!JFile::exists($path . '/original_' . $this->avatar))
                {
                        return false;
                }

                $path = trim($config->get('main_avatarpath'), '/') . '/' . 'original_' . $this->avatar;
                $uri = rtrim(JURI::root(), '/');
                $uri .= '/' . $path;
                return $uri;
        }

        public function getAvatar($isThumb = true)
        {
                $config = DiscussHelper::getConfig();
                static $avatar;

                if (!$config->get('layout_avatar'))
                {
                        return false;
                }

                $key = $this->id . '_' . (int) $isThumb;
                if (!isset($avatar[$key]))
                {
                        $integrate = new DiscussIntegrate;
                        $field = $integrate->getField($this, $isThumb);
                        //$this->avatarLink	=  $field[ 'avatarLink' ];
                        $avatar[$key] = $field['avatarLink'];
                }

                $this->avatarLink = $avatar[$key];
                return $this->avatarLink;
        }

        public function getDescription()
        {
                return $this->description;
        }

        public function getWebsite()
        {
                return $this->url;
        }

        public function getParams()
        {
                return $this->params;
        }

        public function getUserType()
        {
                return $this->user->usertype;
        }

        public function _appendHTTP($url)
        {
                $returnStr = '';
                $regex = '/^(http|https|ftp):\/\/*?/i';
                if (preg_match($regex, trim($url), $matches))
                {
                        $returnStr = $url;
                } else
                {
                        $returnStr = 'http://' . $url;
                }

                return $returnStr;
        }

        public function getRSS()
        {
                return DiscussHelper::getHelper('Feeds')->getFeedURL('index.php?option=com_easydiscuss&view=profile&id=' . $this->id);
        }

        public function getAtom()
        {
                return DiscussHelper::getHelper('Feeds')->getFeedURL('index.php?option=com_easydiscuss&view=profile&id=' . $this->id, true);
        }

        /**
         * Returns a total number of topics a user has marked as favourite.
         *
         * @since	3.0
         * @access	public
         */
        public function getTotalFavourites()
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_favourites AS a')
                        ->innerJoin('#__discuss_posts AS b ON a.post_id = b.id')
                        ->where('created_by = ' . (int) $this->id)
                        ->where('b.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')')
                        ->where('b.published = 1');
                $db->setQuery($query);
                $total = $db->loadResult();
                return $total;
        }

        /**
         * Returns a total number of topic posted by the current user.
         *
         * @since	2.0
         * @access	public
         */
        public function getNumTopicPosted()
        {
                static $cache = array();

                if (empty($this->id))
                {
                        return '0';
                }

                $index = $this->id;

                if (!isset($cache[$index]))
                {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query->select('COUNT(*) AS CNT')
                                ->from('#__discuss_posts')
                                ->where('user_id = ' . (int) $this->id)
                                ->where('parent_id = 0')
                                ->where('language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')')
                                ->where('published = 1');
                        $db->setQuery($query);
                        $cache[$index] = $db->loadResult();
                }

                return $cache[$index];
        }

        /**
         * Returns a total number of topic posted by group of users.
         *
         * @since	3.0
         * @access	public
         */
        public function getNumTopicPostedGroup($userIds)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*) AS CNT, user_id')
                        ->from('#__discuss_posts')
                        ->where('user_id IN (' . implode(',', $userIds) . ')')
                        ->where('parent_id = 0 AND published = 1')
                        ->where('language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')')
                        ->group('user_id');
                $db->setQuery($query);
                $data = $db->loadObjectList();

                //foreach( $userIds as $uid )
                $result = array();
                foreach ($data as $row)
                {
                        $result[$row->user_id] = $row->CNT;
                }

                return $result;
        }

        /**
         * Retrieve the number of replies the user has posted
         * @since	3.0
         * @access	public
         */
        public function getNumTopicAnsweredGroup($userIds)
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);

                $query->select('COUNT(*) AS CNT, a.user_id')
                        ->from('#__discuss_posts AS a')
                        ->where('a.user_id IN (' . implode(',', $userIds) . ')')
                        ->where('a.published = 1 AND a.parent_id != 0')
                        ->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')')
                        ->group('a.user_id');
                $db->setQuery($query);
                $data = $db->loadObjectList();
                //foreach( $userIds as $uid )
                $result = array();
                foreach ($data as $row)
                {
                        $result[$row->user_id] = $row->CNT;
                }

                return $result;
        }

        /**
         * Retrieve the number of replies the user has posted
         * @since	2.0
         * @access	public
         */
        public function getNumTopicAnswered()
        {
                static $cache = array();

                if (empty($this->id))
                {
                        return '0';
                }

                $index = $this->id;

                if (!isset($cache[$index]))
                {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $languageTag = $db->quote(JFactory::getLanguage()->getTag());
                        $query->select('COUNT(*) AS CNT')
                                ->from('#__discuss_posts AS a')
                                ->innerJoin('#__discuss_posts AS b ON a.parent_id = b.id AND a.parent_id != 0')
                                ->where('a.user_id = ' . (int) $this->id)
                                ->where('a.published = 1')
                                ->where('b.published = 1')
                                ->where('a.language IN (' . $languageTag . ',' . $db->quote('*') . ')')
                                ->where('b.language IN (' . $languageTag . ',' . $db->quote('*') . ')');
                        $db->setQuery($query);
                        $cache[$index] = $db->loadResult();
                }
                return $cache[$index];
        }

        /**
         * Retrieve the count of topics that is posted by the user and it isn't resolved yet.
         *
         * @since	2.0
         * @access	public
         */
        public function getNumTopicUnresolved()
        {
                static $cache = array();
                $index = $this->id;
                if (!isset($cache[$index]))
                {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query->select('COUNT(*) AS CNT')
                                ->from('#__discuss_posts AS a')
                                ->where('a.user_id = ' . (int) $this->id)
                                ->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')')
                                ->where('a.published = 1 AND a.isresolve = 0 AND a.parent_id = 0');
                        $db->setQuery($query);
                        $cache[$index] = $db->loadResult();
                }
                return $cache[$index];
        }

        /**
         * Returns the total number of posts a user has made on the site.
         *
         * @since	3.0
         * @access	public
         */
        public function getTotalPosts()
        {
                static $cache = array();
                $index = $this->id;
                if (!isset($cache[$index]))
                {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query->select('COUNT(*)')
                                ->from('#__discuss_posts AS a')
                                ->where('a.user_id = ' . (int) $this->id)
                                ->where('a.published = 1')
                                ->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
                        $db->setQuery($query);
                        $cache[$index] = $db->loadResult();
                }

                return $cache[$index];
        }

        /**
         * Retrieve the total number of tags created by the user
         *
         * @since	3.0
         * @access	public
         */
        public function getTotalTags()
        {
                static $cache = array();
                $index = $this->id;
                if (!isset($cache[$index]))
                {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query->select('COUNT(*)')
                                ->from('#__discuss_tags')
                                ->where('user_id = ' . (int) $this->id)
                                ->where('language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')')
                                ->where('published = 1');
                        $db->setQuery($query);
                        $cache[$index] = $db->loadResult();
                }

                return $cache[$index];
        }

        public function getDateJoined()
        {
                $date = DiscussDateHelper::getDate($this->user->registerDate);
                return $date->toFormat('%d/%m/%Y');
        }

        public function getLastOnline($front = false)
        {
                $date = DiscussDateHelper::dateWithOffSet($this->user->lastvisitDate);

                if ($front)
                {
                        return $date->toFormat('%l.%M %P, %d/%m/%Y');
                } else
                {
                        return $date->toFormat('%d/%m/%Y');
                }
        }

        public function getURL($raw = false, $xhtml = false)
        {
                $url = 'index.php?option=com_easydiscuss&view=profile&id=' . $this->id;
                $url = $raw ? $url : DiscussRouter::_($url, $xhtml);
                return $url;
        }

        /**
         * Determines if the user is an admin on the site.
         *
         * @since	3.0
         * @access	public
         */
        public function isAdmin()
        {
                return DiscussHelper::isSiteAdmin($this->id);
        }

        public function isOnline()
        {
                static $loaded = array();

                if (!isset($loaded[$this->id]))
                {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);

                        $query->select('COUNT(*)')
                                ->from('#__session')
                                ->where('userid = ' . (int) $this->id)
                                ->where('client_id <>  1');
                        $db->setQuery($query);
                        $loaded[$this->id] = $db->loadResult() > 0 ? true : false;
                }
                return $loaded[$this->id];
        }

        /**
         * Get a list of badges for this user.
         *
         * @access	public
         * @return	Array	An array of DiscussTableBadges
         * */
        public function getBadges()
        {
                static $loaded = array();

                if (!isset($loaded[$this->id]))
                {
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query->select('*')
                                ->from('#__discuss_badges_users AS a')
                                ->innerJoin('#__discuss_badges AS b ON a.badge_id = b.id')
                                ->where('b.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')')
                                ->where('a.user_id = ' . (int) $this->id)
                                ->where('b.published = 1');
                        $db->setQuery($query);
                        $result = $db->loadObjectList();
                        $badges = array();

                        if (!$result)
                        {
                                return $result;
                        }

                        foreach ($result as $res)
                        {
                                $badge = DiscussHelper::getTable('Badges');
                                $badge->bind($res);

                                $badge->custom = $res->custom;
                                $badges[] = $badge;
                        }
                        $loaded[$this->id] = $badges;
                }

                return $loaded[$this->id];
        }

        public function getTotalBadges()
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('COUNT(*)')
                        ->from('#__discuss_badges_users AS a')
                        ->innerJoin('#__discuss_badges AS b ON a.badge_id = b.id')
                        ->where('a.user_id = ' . (int) $this->id)
                        ->where('b.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')')
                        ->where('b.published = 1');
                $db->setQuery($query);
                return $db->loadResult();
        }

        public function updatePoints()
        {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);

                $query->select($db->quoteName('points'))
                        ->from('#__discuss_users')
                        ->where('id = ' . (int) $this->id);
                $db->setQuery($query);
                $this->points = $db->loadResult();
        }

        public function getSignature($raw = false)
        {
                if (!array_key_exists('signature', $this->_data))
                {
                        if ($raw)
                        {
                                $this->_data['signature'] = trim($this->signature);
                        } else
                        {
                                $this->_data['signature'] = nl2br(EasyDiscussParser::bbcode($this->signature));
                        }
                }

                return $this->_data['signature'];
        }

        public function getPoints()
        {
                $aup = DiscussHelper::getHelper('Aup');

                if ($aup->exists())
                {
                        return $aup->getUserPoints($this->id);
                }

                return $this->points;
        }

        public function getRole()
        {
                $user = JFactory::getUser($this->id);
                $userGroupId = DiscussHelper::getUserGroupId($user);
                $role = DiscussHelper::getTable('Role');
                $title = $role->getTitle($userGroupId);
                return $title;
        }

        public function getRoleLabelClassname()
        {
                $user = JFactory::getUser($this->id);
                $userGroupId = DiscussHelper::getUserGroupId($user);
                $role = DiscussHelper::getTable('Role');
                $color = $role->getRoleColor($userGroupId);
                $classname = 'role-' . $color;
                return $classname;
        }

        public function getRoleId()
        {
                $userGroupId = DiscussHelper::getUserGroupId($this->user);
                $role = DiscussHelper::getTable('Role');
                $roleid = $role->getRoleId($userGroupId);
                return $roleid;
        }

        public function read($postId)
        {
                $posts = array();
                $doAdd = false;

                if (empty($this->id))
                        return false;

                if ($this->posts_read)
                {
                        $posts = unserialize($this->posts_read);
                        if (!in_array($postId, $posts))
                        {
                                $doAdd = true;
                        }
                } else
                {
                        $doAdd = true;
                }

                if ($doAdd)
                {
                        $posts[] = $postId;
                        $this->posts_read = serialize($posts);
                        $this->store();
                }

                return true;
        }

        /**
         * Deletes the user's avatar
         *
         * @since	1.0
         * @access	public
         * @param	string
         * @return
         */
        public function deleteAvatar()
        {
                $config = DiscussHelper::getConfig();
                $path = $config->get('main_avatarpath');
                $path = rtrim($path, '/');
                $path = JPATH_ROOT . '/' . $path;

                $original = $path . '/original_' . $this->avatar;
                $path = $path . '/' . $this->avatar;

                jimport('joomla.filesystem.file');

                // Test if the original file exists.
                if (JFile::exists($original))
                {
                        JFile::delete($original);
                }

                // Test if the avatar file exists.
                if (JFile::exists($path))
                {
                        JFile::delete($path);
                }

                $this->avatar = '';

                $this->store();
        }

        public function isRead($postId)
        {
                if ($this->posts_read)
                {
                        $posts = unserialize($this->posts_read);
                        return in_array($postId, $posts);
                } else
                {
                        return false;
                }
        }

}

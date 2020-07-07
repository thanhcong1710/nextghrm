<?php

/**
 * @package mod_t_ajax_cattreemenu
 *
 * @copyright Copyright (C) 2015 NextG-ERP. All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://nextgerp.com
 * @author NextG-ERP support@nextgerp.com
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_SITE . '/components/com_content/helpers/route.php';
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_content/models', 'ContentModel');

/**
 * Helper for mod_t_ajax_cattreemenu
 *
 * @package     mod_t_ajax_cattreemenu
 * @subpackage  mod_t_ajax_cattreemenu
 *
 * @since       1.0.0
 */
class ModTAjaxCatTreeMenuHelper {

        /**
         *
         * @var array
         */
        static protected $icons = array();

        /**
         * Get list of categories, included article if exist
         *
         * @param   Registry  &$params  module parameters
         * @param mixed $id ID
         * @param array $menuSelected
         * @param array $preloadIds
         * @return  array
         *
         * @since   1.0.0
         */
        protected static function getCatList(&$params, $id = null, $menuSelected = array(), $preloadIds = array()) {
                $options = array();
                $options['countItems'] = 1;

                $categories = JCategories::getInstance('Content', $options);
                $root = $categories->get($params->get('parent', 'root'));
                $category = (is_null($id)) ? $categories->get($params->get('parent', 'root')) : $categories->get($id);
                $jinput = JFactory::getApplication()->input;
                if ($category != null) {
                        $children = static::getCatChildren($params, $category->id);
                        $display_empty_cat = $params->get('display_empty_cat', 0);
                        $display_article = $params->get('display_article', true);
                        $maxLevel = $params->get('maxlevel', 0);
                        $article_number_display = $params->get('article_number', 10);

                        $query_cat_id = $jinput->getInt('catid', null);
                        $query_cat_id_2 = $jinput->getInt('id', null);
                        $query_option = $jinput->getString('option');
                        $query_view = $jinput->getString('view');
                        foreach ($children as $key => &$cat) {
                                $cat->type = 'category';
                                $cat->link = JRoute::_(ContentHelperRoute::getCategoryRoute($cat->id));
                                $cat->active = (($query_cat_id == $cat->id && $query_option == 'com_content' && $query_view == 'article') || ($query_cat_id_2 == $cat->id && $query_option == 'com_content' && $query_view == 'category'));
                                $children_sub = static::getCatChildren($params, $cat->id);
                                // Calculate number items
                                $subCat_total = 0;
                                foreach ($children_sub as $child) {
                                        if ($display_empty_cat || !empty($child->numitems)) {
                                                $subCat_total += 1;
                                                $cat->numitems += 1;
                                        }
                                }

                                // if stored in cookie, get all children before display menu
                                $isParent = ((($maxLevel == 0) || ($maxLevel >= ($cat->level - $root->level))) && ($children_sub || ($cat->numitems && $display_article)));
                                $cat->isParent = $isParent;

                                // Preload if selected
                                if (isset($menuSelected['c-' . $cat->id]) || in_array($cat->id, $preloadIds)) {
                                        $children_sub = static::getCatList($params, $cat->id, $menuSelected, $preloadIds);
                                        $article_loaded = count($children_sub) - $subCat_total;
                                        if (($cat->numitems - $subCat_total) > $article_loaded) {
                                                $cat->showMore = true;
                                                $cat->limit = $article_number_display;
                                                $cat->offset = $article_loaded;
                                                $cat->article_loaded = $article_loaded;
                                        }
                                        $cat->children = $children_sub;
                                        $cat->active = (in_array($cat->id, $preloadIds)) ? true : $cat->active;
                                }

                                // Unset if is empty category
                                if (!$display_empty_cat && empty($cat->numitems)) {
                                        unset($children[$key]);
                                }
                        }
                        unset($cat);

                        if ($display_article) {
                                // Find all articles with in category
                                $offset = 0;
                                $limit = (isset($menuSelected['c-' . $category->id]['limit'])) ? $menuSelected['c-' . $category->id]['limit'] : $article_number_display;
                                $articles = static::getArticles($params, $category->id, $menuSelected, $offset, $limit);
                                if (!empty($articles)) {
                                        foreach ($articles as $article) {
                                                $children[] = $article;
                                        }
                                }
                        }

                        return $children;
                }
        }

        /**
         * Method get all icons
         * @since 1.0.0
         * @return array
         */
        protected static function getIcons() {
                if (empty(static::$icons)) {
                        $icons = array();
                        $icons['folder'] = array();
                        $icons['folder']['awesome'] = 'fa fa-folder-o';
                        $icons['folder']['icomoon'] = 'icon-folder';
                        $icons['folder']['img_icons'] = 'folder';

                        $icons['file'] = array();
                        $icons['file']['awesome'] = 'fa fa-file-o';
                        $icons['file']['icomoon'] = 'icon-file';
                        $icons['file']['img_icons'] = 'file';

                        $icons['anchor'] = array();
                        $icons['anchor']['awesome'] = 'anchor';
                        $icons['anchor']['icomoon'] = 'anchor';
                        $icons['anchor']['img_icons'] = 'anchor';
                        static::$icons = $icons;
                }

                return static::$icons;
        }

        /**
         * Method get tree menu by ajax
         * @return JSON
         * @since 1.0.0
         */
        public static function getItemAjax() {
                // Load module language
                $lang = JFactory::getLanguage();
                $lang->load('mod_t_ajax_cattreemenu', JPATH_SITE . '/modules/mod_t_ajax_cattreemenu', $lang->getTag(), true);

                $app = JFactory::getApplication();
                $item_id = $app->input->getString('data_id', null);
                $module_id = $app->input->getInt('mid', null);

                $parse = explode('-', $item_id);
                switch ($parse[0]) {
                        case 'a':
                                $article_id = $parse[1];
                                break;
                        case 'c':
                                $category_id = $parse[1];
                                break;
                        default:
                                break;
                }

                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('title')
                        ->from('#__modules')
                        ->where('id = ' . (int) $module_id);
                $db->setQuery($query);
                $module_title = $db->loadResult();
                $response = array();
                if ($module_title) {
                        $module = JModuleHelper::getModule('mod_t_ajax_cattreemenu', $module_title);
                        $params = new JRegistry($module->params);
                        $layout = $params->get('layout', 'default');
                        $layout = str_replace('_:', '', $layout);
                        if ($module_id && isset($category_id)) {
                                $displayData = static::createTreeData($module_id, 'category', $category_id);
                        } elseif (isset($article_id)) {
                                $displayData = static::createTreeData($module_id, 'article', $article_id);
                        }

                        if (!empty($displayData->children)) {
                                // Return html
                                $response['html'] = JLayoutHelper::render('modules.mod_t_ajax_cattreemenu.tmpl.' . $layout, $displayData, JPATH_SITE);
                        }
                }
                return $response;
        }

        /**
         * Method get list articles in category
         * @param array $params
         * @param integer $cat_id
         * @param array $menuSelected
         * @return array
         * @since 1.0.0
         */
        protected static function getArticles($params, $cat_id, $menuSelected = array(), $offset = 0, $limit = 0) {
                $app = JFactory::getApplication();

                // Get an instance of the generic articles model
                $model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

                // Set application parameters in model
                $appParams = JFactory::getApplication()->getParams();
                $model->setState('params', $appParams);

                // Set the filters based on the module params
                $model->setState('list.start', $offset);
                $model->setState('list.limit', $limit);

                $model->setState('filter.published', 1);

                $model->setState('list.select', 'a.fulltext, a.id, a.title, a.alias, a.introtext, a.state, a.catid, a.access, a.attribs, a.created');

                // Access filter
                $access = !JComponentHelper::getParams('com_content')->get('show_noauth');
                $authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
                $model->setState('filter.access', $access);

                // Category filter
                $model->setState('filter.category_id', $cat_id);

                // Filter by language
                $model->setState('filter.language', $app->getLanguageFilter());

                // Set ordering
                $ordering = $params->get('article_ordering', 'a.publish_up');
                $model->setState('list.ordering', $ordering);

                if (trim($ordering) == 'rand()') {
                        $model->setState('list.direction', '');
                } else {
                        $direction = $params->get('article_direction', 1) ? 'DESC' : 'ASC';
                        $model->setState('list.direction', $direction);
                }

                $items = $model->getItems();
                $displayAnchor = $params->get('display_anchor', true);
                $query_article_id = $app->input->getInt('id', 0);
                $query_option = $app->input->getString('option');
                foreach ($items as &$item) {
                        $item->slug = $item->id . ':' . $item->alias;
                        $item->catslug = $item->catid . ':' . $item->category_alias;
                        $item->type = 'article';
                        if ($access || in_array($item->access, $authorised)) {
                                // We know that user has the privilege to view the article
                                $item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid));
                        } else {
                                $item->link = JRoute::_('index.php?option=com_users&view=login');
                        }
                        $item->active = ($query_article_id == $item->id && $query_option == 'com_content');
                        $children = [];
                        if ($displayAnchor) {
                                if (!empty($item->introtext) || !empty($item->fulltext)) {
                                        libxml_use_internal_errors(true);
                                        $doc = new DOMDocument();
                                        $doc->loadHTML(mb_convert_encoding($item->introtext . $item->fulltext, 'HTML-ENTITIES', 'UTF-8'));
                                        foreach ($doc->getElementsByTagName('a') as $node) {
                                                $node_href = $node->getAttribute('href');
                                                $node_id = $node->getAttribute('id');
                                                $node_name = $node->getAttribute('name');
                                                if (( (empty($node_href) || $node_href == '#' . $node_id) && $node_id) || $node_name) {
                                                        $anchor = new stdClass();
                                                        $anchor->id = $item->id;
                                                        $anchor->title = $node->nodeValue;
                                                        $anchor->type = 'anchor';
                                                        $id = ($node_name) ? $node_name : $node_id;
                                                        $anchor->link = $item->link . '#' . $id;
                                                        $anchor->active = false;
                                                        $anchor->isParent = 0;
                                                        $children[] = $anchor;
                                                }
                                        }
                                }
                        }
                        $item->numitems = count($children);
                        $item->isParent = ($item->numitems) ? true : false;

                        if (isset($menuSelected['a-' . $item->id])) {
                                $item->children = $children;
                        }
                }
                unset($item);
                return $items;
        }

        /**
         * Method get all anchor in article
         * @param integer $article_id
         * @return array
         * @since 1.0.0
         */
        protected static function getArticleAnchor($article_id) {
                // Get an instance of the generic articles model
                $model = JModelLegacy::getInstance('Article', 'ContentModel');
                $item = $model->getItem($article_id);
                $item->slug = $item->id . ':' . $item->alias;
                $item->catslug = $item->catid . ':' . $item->category_alias;
                // Access filter
                $access = !JComponentHelper::getParams('com_content')->get('show_noauth');
                $authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
                if ($access || in_array($item->access, $authorised)) {
                        // We know that user has the privilege to view the article
                        $item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid));
                } else {
                        $item->link = JRoute::_('index.php?option=com_users&view=login');
                }
                $anchors = array();
                $doc = new DOMDocument();
                libxml_use_internal_errors(true);
                $doc->loadHTML(mb_convert_encoding($item->introtext . $item->fulltext, 'HTML-ENTITIES', 'UTF-8'));
                $current_url = JUri::getInstance()->getPath();
                foreach ($doc->getElementsByTagName('a') as $node) {
                        $node_href = $node->getAttribute('href');
                        $node_id = $node->getAttribute('id');
                        $node_name = $node->getAttribute('name');
                        if (((empty($node_href) || $node_href == '#' . $node_id ) && $node_id) || $node_name) {
                                $anchor = new stdClass();
                                $anchor->id = $item->id;
                                $anchor->title = $node->nodeValue;
                                $anchor->type = 'anchor';
                                $id = ($node_name) ? $node_name : $node_id;
                                $anchor->link = $item->link . '#' . $id;
                                $anchor->active = false;

                                $anchor->isParent = 0;
                                $anchors[] = $anchor;
                        }
                }

                return $anchors;
        }

        /**
         * Method load more article with category
         * @return JSON
         * @since 1.0.0
         */
        public static function showMoreAjax() {
                // Load module language
                $lang = JFactory::getLanguage();
                $lang->load('mod_t_ajax_cattreemenu', JPATH_SITE . '/modules/mod_t_ajax_cattreemenu', $lang->getTag(), true);
                $app = JFactory::getApplication();
                $item_id = $app->input->getString('data_id', null);
                $module_id = $app->input->getString('mid', null);
                $offset = $app->input->getInt('offset', null);
                $limit = $app->input->getInt('limit', null);

                $parse = explode('-', $item_id);
                switch ($parse[0]) {
                        case 'c':
                                $category_id = $parse[1];
                                break;
                        default:
                                break;
                }

                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('title')
                        ->from('#__modules')
                        ->where('id = ' . (int) $module_id);
                $db->setQuery($query);
                $module_title = $db->loadResult();
                $response = array();
                if ($module_title) {
                        $module = JModuleHelper::getModule('mod_t_ajax_cattreemenu', $module_title);
                        $params = new JRegistry($module->params);
                        $layout = $params->get('layout', 'default');
                        $layout = str_replace('_:', '', $layout);
                        $displayData = new stdClass();
                        if ($module_id && isset($category_id)) {
                                $items = static::getArticles($params, $category_id, null, $offset, $limit);
                                $categories = JCategories::getInstance('Content', ['countItems' => true]);
                                $category = $categories->get($category_id);

                                // Display show more button
                                $offset = $offset + $limit;
                                $response['limit'] = $offset;
                                if ($category->numitems >= $offset) {
                                        $displayData->id = $category_id;
                                        $displayData->showMore = true;
                                        $displayData->limit = $limit;
                                        $displayData->offset = $offset;
                                }
                        }

                        if (!empty($items)) {
                                $displayData->module_id = $module_id;
                                $displayData->children = $items;
                                $displayData->params = $params;
                                // Return html
                                $response['html'] = JLayoutHelper::render('modules.mod_t_ajax_cattreemenu.tmpl.' . $layout, $displayData, JPATH_SITE);
                        }
                }
                return $response;
        }

        /**
         * Method create tree data
         * @param integer $module_id
         * @param string $type
         * @param integer $id
         * @param string $cookie_id
         * @return object
         * @since 1.0.0
         */
        public static function createTreeData($module_id, $type, $id, $cookie_id = null) {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('title')
                        ->from('#__modules')
                        ->where('id = ' . (int) $module_id);
                $db->setQuery($query);
                $module_title = $db->loadResult();
                $items = false;
                $displayData = new stdClass();
                $displayData->article_loaded = 0;
                if ($module_title) {
                        $module = JModuleHelper::getModule('mod_t_ajax_cattreemenu', $module_title);
                        $params = new JRegistry($module->params);

                        switch ($type) {
                                case 'category':
                                        // load category by current url
                                        $app = JFactory::getApplication();
                                        $query_option = $app->input->getString('option');
                                        $preloadIds = [];
                                        if ($query_option == 'com_content') {
                                                $query_catid = $app->input->getInt('catid');
                                                $categoryParentQuery = $db->getQuery(true);
                                                $categoryParentQuery->select('p.id')
                                                        ->from('#__categories AS a, #__categories AS p')
                                                        ->where('a.id = ' . (int) $query_catid)->where('p.lft <= a.lft')
                                                        ->where('p.rgt >= a.rgt');
                                                $db->setQuery($categoryParentQuery);
                                                $preloadIds = $db->loadAssocList(null, 'id');
                                        }

                                        $menuSelected = [];
                                        if (isset($cookie_id)) {
                                                $jinput = JFactory::getApplication()->input;
                                                $cookie = $jinput->cookie;
                                                $registry = new Joomla\Registry\Registry($cookie->getString($cookie_id));
                                                $menuSelected = $registry->toArray();
                                        }

                                        $items = static::getCatList($params, $id, $menuSelected, $preloadIds);
                                        $categories = JCategories::getInstance('Content', ['countItems' => true]);
                                        $category = $categories->get($id);
                                        $article_number = $params->get('article_number', 10);
                                        // Display show more button
                                        if ($category->numitems > $article_number) {
                                                $offset = (isset($menuSelected['c-' . $category->id]['limit'])) ? $menuSelected['c-' . $category->id]['limit'] : $article_number;
                                                $displayData->id = $id;
                                                $displayData->showMore = true;
                                                $displayData->limit = $article_number;
                                                $displayData->offset = $offset;
                                                $displayData->article_loaded = $offset;
                                                if ($category->numitems <= $displayData->article_loaded) {
                                                        $displayData->showMore = false;
                                                }
                                        }
                                        break;
                                case 'article':
                                        $items = static::getArticleAnchor($id);
                                        break;
                                default:
                                        break;
                        }
                }
                $displayData->children = $items;
                $displayData->module_id = $module->id;
                $displayData->params = $params;
                return $displayData;
        }

        /**
         * Method get all children of category
         * @param integer $cat_id
         * @return array
         * @since 1.0.0
         */
        protected static function getCatChildren($params, $cat_id) {
                $language = JFactory::getLanguage();
                $language_tag = $language->getTag();
                $user = JFactory::getUser();
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('a.id, a.title, a.level')
                        ->from('#__categories AS a')->where('a.published = 1')
                        ->where('a.parent_id = ' . (int) $cat_id);
                $query->where('a.language in (' . $db->quote($language_tag) . ',' . $db->quote('*') . ')');
                $query->where('a.access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')');

                // Count all item of category
                $subQuery = $db->getQuery(true);
                $subQuery->select('count(*)')->from('#__content AS c')
                        ->where('c.catid = a.id')
                        ->where('c.state = 1');
                $subQuery->where('c.language in (' . $db->quote($language_tag) . ',' . $db->quote('*') . ')');
                $query->select('(' . $subQuery . ') AS numitems');

                $ordering = $params->get('cat_ordering', 'a.id');
                $direction = $params->get('cat_direction', 'DESC');
                $query->order($ordering . ' ' . $direction);
                $db->setQuery($query);
                return $db->loadObjectList();
        }

}

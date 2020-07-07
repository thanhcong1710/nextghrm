<?php

defined('_JEXEC') or die;
jimport('joomla.plugin.plugin');
require_once JPATH_SITE . '/components/com_content/helpers/route.php';

class plgAjaxKiwiVideo extends JPlugin {

        /**
         * ?option=com_ajax&plugin=kiwivideo&format=json
         */
        function onAjaxKiwiVideo() {
                $app = JFactory::getApplication();
                $cmd = $app->input->getString('cmd', null);
                $id = $app->input->getInt('id', null);
                $response = array();
                $response['error'] = true;
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                if ($cmd == 'hit') {
                        if (empty($id)) {
                                return $response;
                        }
                        try {
                                $query->update('#__content')
                                        ->set('hits = hits + 1')
                                        ->where('id = ' . (int) $id);
                                $db->setQuery($query);
                                $db->execute();
                                $response['message'] = "";
                                $response['error'] = false;
                        } catch (Exception $ex) {
                                $response['message'] = $ex;
                        }
                } elseif ($cmd == 'get') {
                        if (empty($id)) {
                                return $response;
                        }
                        $com_path = JPATH_SITE . '/components/com_content/';
                        JModelLegacy::addIncludePath($com_path . '/models', 'ContentModel');
                        $articleModel = JModelLegacy::getInstance('Article', 'ContentModel');
                        $article = $articleModel->getItem($id);
                        if (!empty($article)) {
                                $response['html'] = $article->introtext;
                                $response['title'] = $article->title;
                                $response['error'] = false;
                        }
                } elseif ($cmd == 'search') {
                        $com_path = JPATH_SITE . '/components/com_content/';
                        JModelLegacy::addIncludePath($com_path . '/models', 'ContentModel');
                        $data = $app->input->get('data', array(), 'array');
                        $data['tags'] = !empty($data['jform[tags']) ? $data['jform[tags'] : array();
                        $query->clear();
                        // Search by keyword
                        $query->select('a.id')
                                ->from('#__content AS a');
                        if (!empty($data['keyword'])) {
                                $search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($data['keyword']), true) . '%'));
                                $query->where('((a.title LIKE ' . $search . ') '
                                        . 'OR (a.fulltext LIKE ' . $search . ') '
                                        . 'OR (a.introtext LIKE ' . $search . ') '
                                        . 'OR (a.metakey LIKE ' . $search . ') '
                                        . 'OR (a.metadesc LIKE ' . $search . '))'
                                );
                        } elseif (empty($data['keyword']) && empty($data['tags'])) {
                                $response['html'] = '';
                                $response['error'] = false;
                                return $response;
                        }
                        if (!empty($data['tags'])) {
                                $query->innerJoin('#__contentitem_tag_map AS ctm ON ctm.content_item_id = a.id')
                                        ->where('ctm.type_alias = ' . $db->quote('com_content.article'))
                                        ->where('ctm.tag_id IN (' . implode(',', $data['tags']) . ')');
                        }

                        if (!empty($data['catid'])) {
                                $catids = array($data['catid']);
                                // Category filter
                                if ($catids) {
                                        // Get an instance of the generic categories model
                                        $categories = JModelLegacy::getInstance('Categories', 'ContentModel', array('ignore_request' => true));
                                        $levels = 9999;
                                        $categories->setState('filter.get_children', $levels);
                                        $categories->setState('filter.published', 1);
                                        $additional_catids = array();

                                        foreach ($catids as $catid) {
                                                $categories->setState('filter.parentId', $catid);
                                                $recursive = true;
                                                $items = $categories->getItems($recursive);

                                                if ($items) {
                                                        foreach ($items as $category) {
                                                                $condition = (($category->level - $categories->getParent()->level) <= $levels);
                                                                if ($condition) {
                                                                        $additional_catids[] = $category->id;
                                                                }
                                                        }
                                                }
                                        }

                                        $catids = array_unique(array_merge($catids, $additional_catids));
                                }
                                $query->where('a.catid IN (' . implode(',', $catids) . ')');
                        }

                        $db->setQuery($query);
                        $article_ids = $db->loadAssocList(null, 'id');
                        if (!empty($article_ids)) {
                                $articles = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
                                $appParams = $app->getParams();
                                $articles->setState('params', $appParams);
                                $articles->setState('filter.published', 1);
                                $access = !JComponentHelper::getParams('com_content')->get('show_noauth');
                                $articles->setState('filter.access', $access);
                                $articles->setState('filter.article_id', $article_ids);
                                // Ordering
                                $articles->setState('list.ordering', 'a.ordering');
                                $articles->setState('list.direction', 'ASC');
                                // New Parameters
                                $articles->setState('filter.featured', 'show');
                                // Filter by language
                                $articles->setState('filter.language', $app->getLanguageFilter());
                                $items = $articles->getItems();
                                foreach ($items as &$item) {
                                        $item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;

                                        $item->parent_slug = ($item->parent_alias) ? ($item->parent_id . ':' . $item->parent_alias) : $item->parent_id;

                                        // No link for ROOT category
                                        if ($item->parent_alias == 'root') {
                                                $item->parent_slug = null;
                                        }

                                        $item->catslug = $item->category_alias ? ($item->catid . ':' . $item->category_alias) : $item->catid;
                                        $item->event = new stdClass;

                                        $dispatcher = JEventDispatcher::getInstance();

                                        // Old plugins: Ensure that text property is available
                                        if (!isset($item->text)) {
                                                $item->text = $item->introtext;
                                        }

                                        JPluginHelper::importPlugin('content');
                                        $dispatcher->trigger('onContentPrepare', array('com_content.category', &$item, &$item->params, 0));

                                        // Old plugins: Use processed text as introtext
                                        $item->introtext = $item->text;

                                        $results = $dispatcher->trigger('onContentAfterTitle', array('com_content.category', &$item, &$item->params, 0));
                                        $item->event->afterDisplayTitle = trim(implode("\n", $results));

                                        $results = $dispatcher->trigger('onContentBeforeDisplay', array('com_content.category', &$item, &$item->params, 0));
                                        $item->event->beforeDisplayContent = trim(implode("\n", $results));

                                        $results = $dispatcher->trigger('onContentAfterDisplay', array('com_content.category', &$item, &$item->params, 0));
                                        $item->event->afterDisplayContent = trim(implode("\n", $results));
                                }
                                unset($item);
                                $html = "";
                                foreach ($items as $key => $article) {
                                        $isVideo = (strpos($article->introtext, 'iframe') !== false) ? true : false;
                                        $html .= JLayoutHelper::render('joomla.content.video', $article, JPATH_SITE, array('isVideo' => $isVideo));
                                }
                                $response['html'] = $html;
                                $response['error'] = false;
                        } else {
                                if (!empty($data['keyword'])) {
                                        $response['html'] = JText::sprintf('TPL_KIWI_ERP_SEARCH_FORM_EMPTY_VIDEO', $data['keyword']);
                                } else {
                                        $response['html'] = JText::sprintf('TPL_KIWI_ERP_SEARCH_FORM_EMPTY_VIDEO_2');
                                }

                                $response['error'] = false;
                        }
                }
                return $response;
        }

}

<?php

/**
 * @version		3.0.0
 * @package		Kiwi Package
 * @author    	NextG-ERP - http://www.nextgerp.com
 * @copyright	Copyright (c) 2015 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

class plgContentKiwiArticle extends JPlugin {

        // JoomlaWorks reference parameters
        var $plg_name = "kiwiarticle";
        // Load the plugin language file
        protected $autoloadLanguage = true;

        public function onContentPrepare($context, &$row, &$params, $page = 0) {
                $document = JFactory::getDocument();
                // Simple performance checks to determine whether plugin should process further
                if (!preg_match("#{video=.+?}|{video=.+?}|{video=.+?}#s", $row->text)) {
                        return;
                }

                // Check if plugin is enabled
                if (JPluginHelper::isEnabled('content', $this->plg_name) == false) {
                        return;
                }

                // --- Tabs ---
                if ($document->getType() == 'html') {
                        if (preg_match_all("/{video=.+?}{video=.+?}|{video=.+?}/", $row->text, $matches, PREG_PATTERN_ORDER) > 0) {
                                foreach ($matches[0] as $match) {
                                        $article_id = preg_replace("/[^0-9]/", '', $match);
                                        $db = JFactory::getDbo();
                                        $query = $db->getQuery(true);
                                        $query->select('a.*')
                                                ->from('#__content AS a')
                                                ->where('a.id = ' . (int) $article_id);
                                        // Join on category table.
                                        $query->select('c.title AS category_title, c.alias AS category_alias, c.access AS category_access')
                                                ->join('LEFT', '#__categories AS c on c.id = a.catid');

                                        // Join over the categories to get parent category titles
                                        $query->select('parent.title as parent_title, parent.id as parent_id, parent.path as parent_route, parent.alias as parent_alias')
                                                ->join('LEFT', '#__categories as parent ON parent.id = c.parent_id');

                                        $db->setQuery($query);
                                        $article = $db->loadObject();
                                        // Convert parameter fields to objects.
                                        $registry = new \Joomla\Registry\Registry();
                                        $registry->loadString($article->attribs);

                                        $article->params = clone JFactory::getApplication()->getParams();
                                        $article->params->merge($registry);
                                        $article->slug = $article->alias ? ($article->id . ':' . $article->alias) : $article->id;

                                        $article->parent_slug = ($article->parent_alias) ? ($article->parent_id . ':' . $article->parent_alias) : $article->parent_id;

                                        // No link for ROOT category
                                        if ($article->parent_alias == 'root') {
                                                $article->parent_slug = null;
                                        }

                                        $article->catslug = $article->category_alias ? ($article->catid . ':' . $article->category_alias) : $article->catid;
                                        $html = JLayoutHelper::render('joomla.content.video', $article, JPATH_SITE, array('imageonly' => true));
                                        unset($articleModel, $article);
                                        $row->text = str_replace($match, $html, $row->text);
                                }
                        }
                }
        }

// End function
}

// End class

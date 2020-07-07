<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
$params = $this->params;
$class = ' class="first kiwiweb-video-cat kiwerp-video-group"';

// Get an instance of the generic articles model
function getItems($cat_id, $params) {
        $articles = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
        // Set application parameters in model
        $app = JFactory::getApplication();
        $appParams = $app->getParams();
        $articles->setState('params', $appParams);

        // Set the filters based on the module params
        $articles->setState('list.start', 0);
        $articles->setState('list.limit', (int) $params->get('count', 0));
        $articles->setState('filter.published', 1);

        // Access filter
        $access = !JComponentHelper::getParams('com_content')->get('show_noauth');
        $articles->setState('filter.access', $access);

        $catids = array($cat_id);
        $articles->setState('filter.category_id.include', (bool) $params->get('category_filtering_type', 1));

        // Category filter
        if ($catids) {
                if ($params->get('show_child_category_articles', 0) && (int) $params->get('levels', 0) > 0) {
                        // Get an instance of the generic categories model
                        $categories = JModelLegacy::getInstance('Categories', 'ContentModel', array('ignore_request' => true));
                        $categories->setState('params', $appParams);
                        $levels = $params->get('levels', 1) ? $params->get('levels', 1) : 9999;
                        $categories->setState('filter.get_children', $levels);
                        $categories->setState('filter.published', 1);
                        $categories->setState('filter.access', $access);
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
                $articles->setState('filter.category_id', $catids);
        }

        // Ordering
        $articles->setState('list.ordering', $params->get('article_ordering', 'a.ordering'));
        $articles->setState('list.direction', $params->get('article_ordering_direction', 'ASC'));
        // New Parameters
        $articles->setState('filter.featured', $params->get('show_front', 'show'));
        $articles->setState('filter.author_id', $params->get('created_by', ""));
        $articles->setState('filter.author_id.include', $params->get('author_filtering_type', 1));
        $articles->setState('filter.author_alias', $params->get('created_by_alias', ""));
        $articles->setState('filter.author_alias.include', $params->get('author_alias_filtering_type', 1));
        // Filter by language
        $articles->setState('filter.language', $app->getLanguageFilter());
        $items = $articles->getItems();
        // Compute the article slugs and prepare introtext (runs content plugins).
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
        return $items;
}

$lang = JFactory::getLanguage();

if (count($this->items[$this->parent->id]) > 0 && $this->maxLevelcat != 0) :
        ?>
        <?php foreach ($this->items[$this->parent->id] as $id => $item) : ?>
                <?php
                $articles = getItems($item->id, $params);
                if ($this->params->get('show_empty_categories_cat') || $item->numitems || count($item->getChildren())) :
                        if (!isset($this->items[$this->parent->id][$id + 1])) {
                                $class = ' class="last kiwiweb-video-cat kiwerp-video-group"';
                        }
                        ?>
                        <div <?php echo $class; ?> >
                                <h1 class="item-title">
                                        <i class="fa fa-video-camera video-icon-square"></i>
                                        <a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($item->id)); ?>">
                                                <?php echo $this->escape($item->title); ?></a>
                                        <?php if ($this->params->get('show_cat_num_articles_cat') == 1) : ?>
                                                <span class="badge badge-info">
                                                        <?php echo $item->numitems; ?>
                                                </span>
                                        <?php endif; ?>
                                        <?php if (count($item->getChildren()) > 0 && $this->maxLevelcat > 1) : ?>
                                                <a href="#category-<?php echo $item->id; ?>" data-toggle="collapse" data-toggle="button" class="btn btn-mini pull-right"><span class="fa fa-plus"></span></a>
                                        <?php endif; ?>
                                        <?php /* ?>
                                          <div class="visible-desktop visible-lg visible-md video-search-form">
                                          <?php echo JLayoutHelper::render('joomla.form.searchform', $item); ?>
                                          </div>
                                          <?php */ ?>
                                </h1>
                                <?php if ($this->params->get('show_description_image') && $item->getParams()->get('image')) : ?>
                                        <img alt="<?php echo htmlspecialchars($item->title); ?>" src="<?php echo $item->getParams()->get('image'); ?>" />
                                <?php endif; ?>
                                <?php if ($this->params->get('show_subcat_desc_cat') == 1) : ?>
                                        <?php if ($item->description) : ?>
                                                <div class="category-desc">
                                                        <?php echo JHtml::_('content.prepare', $item->description, '', 'com_content.categories'); ?>
                                                </div>
                                        <?php endif; ?>
                                <?php endif; ?>

                                <?php if (count($item->getChildren()) > 0 && $this->maxLevelcat > 1) : ?>
                                        <div class="collapse fade" id="category-<?php echo $item->id; ?>">
                                                <?php
                                                $this->items[$item->id] = $item->getChildren();
                                                $this->parent = $item;
                                                $this->maxLevelcat--;
                                                echo $this->loadTemplate('items');
                                                $this->parent = $item->getParent();
                                                $this->maxLevelcat++;
                                                ?>
                                        </div>
                                <?php endif; ?>
                                <?php if (!empty($articles)): ?>
                                        <?php
                                        $column = $params->get('num_columns', 3);
                                        $limit = 100;
                                        $totalSpan = 0;
                                        $span = 12 / $column;
                                        $counter = 0;
                                        ?>
                                        <div class="video-library">
                                                <div class="row">
                                                        <div class="col-md-6">
                                                                <div itemscope itemtype="http://schema.org/TechArticle">
                                                                        <?php
                                                                        $this->item = & $articles[0];
                                                                        $this->item->heading = true;
                                                                        echo $this->loadTemplate('item');
                                                                        ?>
                                                                </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                                <div class="video-scroll video-response">
                                                                        <?php
                                                                        unset($articles[0]);
                                                                        foreach ($articles as $key => $article) {
                                                                                if ($counter >= $limit) {
                                                                                        break;
                                                                                }
                                                                                $counter += 1;
                                                                                ?>
                                                                                <div itemscope itemtype="http://schema.org/TechArticle">
                                                                                        <?php
                                                                                        $this->item = & $article;
                                                                                        echo $this->loadTemplate('item');
                                                                                        ?>
                                                                                </div>
                                                                                <?php
                                                                        }
                                                                        ?>
                                                                </div>
                                                        </div>
                                                </div>

                                                <div class="readmore"><a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($item->id)); ?>" class="btn btn-default">View all <i class="fa fa-angle-double-right"></i></a></div>
                                        </div>
                                <?php endif; ?>
                        </div>
                <?php endif; ?>
        <?php endforeach; ?>
<?php endif; ?>

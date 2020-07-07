<?php
/**
 * @package mod_kiwiajaxsearch
 * @subpackage  mod_kiwiajaxsearch
 *
 * @copyright Copyright (C) 2015 NextG-ERP. All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://nextgerp.com
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
$app = JFactory::getApplication();
$option = $app->input->getString('option', null);
$layout = $app->input->getString('layout', null);
$view = $app->input->getString('view', null);
$id = $app->input->getInt('id', null);
if ($view == 'article') {
        return false;
}

if (!empty($view) && $option == 'com_content' && ($view == 'category' || $view = 'categories') && !empty($id)):
        $document = JFactory::getDocument();
        $document->addStyleSheet(JUri::root(true) . '/modules/mod_kiwiajaxsearch/assets/css/kiwiajaxsearch.css');
        $document->addScript(JUri::root(true) . '/modules/mod_kiwiajaxsearch/assets/js/kiwiajaxsearch.js');
        $options = array();
        $options['countItems'] = $params->get('numitems', 0);
        $categories = JCategories::getInstance('Content', $options);
        $category = $categories->get($id);
        ?>
        <div class="kiwias-video-search-box kiwias-video-group">
                <?php echo JLayoutHelper::render('joomla.form.searchform', $category, JPATH_SITE, array('displayTag' => true)); ?>
                <div class="video-response"></div>
        </div>
        <?php
        unset($categories, $category);
        ?>
<?php endif; ?>
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
$doc = JFactory::getDocument();
$doc->addStyleSheet('modules/mod_t_ajax_cattreemenu/assets/css/jquery.treeview.css?v=1.0.14');
$params = $displayData->params;
$icon_type = $params->get('icon_type', 'img_icons');
$icons = array();
$icons['folder'] = array();
$icons['folder']['awesome'] = 'fa fa-folder-o';
$icons['folder']['icomoon'] = 'icon-folder-close';
$icons['folder']['img_icons'] = 'folder';

$icons['file'] = array();
$icons['file']['awesome'] = 'fa fa-file-o';
$icons['file']['icomoon'] = 'icon-file';
$icons['file']['img_icons'] = 'file';

$icons['anchor'] = array();
$icons['anchor']['awesome'] = 'anchor';
$icons['anchor']['icomoon'] = 'anchor';
$icons['anchor']['img_icons'] = 'anchor';

foreach ($displayData->children as $item):
        $attr = ' data-mid="' . $displayData->module_id . '"';
        $item_class = 'cat-tree-item';
        switch ($item->type) {
                case 'category':
                        $attr .= ' data-id="c-' . $item->id . '"';
                        $icon = $icons['folder'][$icon_type];
                        break;
                case 'article':
                        $attr .= ' data-id="a-' . $item->id . '"';
                        $icon = $icons['file'][$icon_type];
                        break;
                case 'anchor':
                        $attr .= ' data-id="a-' . $item->id . '"';
                        $icon = $icons['anchor'][$icon_type];
                        $item_class .= ' anchor-item';
                        break;
                default:
                        break;
        }

        if (isset($item->article_loaded))
        {
                $attr .= ' data-displayed="' . $item->article_loaded . '"';
        }

        $item_class .= ($item->isParent) ? ' parent' : '';
        $item_class .= ($item->active) ? ' active' : '';
        $item_class .= ($item->isParent && isset($item->children) && is_array($item->children)) ? ' loaded' : '';
        ?>
        <li class="<?php echo trim($item_class); ?>"<?php echo $attr; ?>>
                <?php
                $numitems_style = $params->get('numitems_style', 'label');
                ?>
                <?php if ($icon_type == 'img_icons') : ?>
                        <span class="tree-icon <?php echo $icon; ?>">
                                <a href="<?php echo $item->link; ?>">
                                        <?php echo $item->title; ?>
                                        <?php if ($params->get('numitems', 0) && isset($item->numitems) && $item->numitems) : ?>
                                                <span class="numitems <?php echo $numitems_style; ?>"><?php echo $item->numitems; ?></span>
                                        <?php endif; ?>
                                </a>
                        </span>
                <?php else: ?>
                        <a href="<?php echo $item->link; ?>">
                                <span class="tree-icon <?php echo $icon; ?>"></span>
                                <?php echo $item->title; ?>
                                <?php if ($params->get('numitems', 0) && isset($item->numitems) && $item->numitems) : ?>
                                        <span class="numitems <?php echo $numitems_style; ?>"><?php echo $item->numitems; ?></span>
                                <?php endif; ?>
                        </a>
                <?php endif; ?>

                <?php if ($item->isParent): ?>
                        <ul class="sub-tree" style="display: none;">
                                <?php
                                if (isset($item->children) && is_array($item->children)):
                                        $layout = str_replace('_:', '', $params->get('layout', 'default'));
                                        $item->module_id = $displayData->module_id;
                                        $item->params = $params;
                                        echo JLayoutHelper::render('modules.mod_t_ajax_cattreemenu.tmpl.' . $layout, $item, JPATH_SITE);
                                endif;
                                ?>
                        </ul>
                <?php endif; ?>
        </li>
<?php endforeach; ?>
<?php
if (isset($displayData->showMore) && $displayData->showMore)
{
        echo '<a class="tree-more" data-offset="' . $displayData->offset . '" data-limit="' . $displayData->limit . '">' . JText::_('MOD_T_AJAX_CATTREEMENU_MORE_BUTTON_LABEL') . '</a>';
}
?>
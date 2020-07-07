<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
$params = $displayData->params;
$image_class = $this->options->get('img_class', '');
?>
<?php $images = json_decode($displayData->images); ?>
<?php if (isset($images->image_intro) && !empty($images->image_intro)) : ?>
        <?php $imgfloat = (empty($images->float_intro)) ? $params->get('float_intro') : $images->float_intro; ?>
        <div class="item-image blog-image">
                <?php echo '<div class="video-item-icon"><img src="' . JUri::root(true) . '/templates/kiwiweb/images/play_button.png" /></div>'; ?>
                <img
                <?php
                if ($images->image_intro_caption):
                        echo 'class="caption img-responsive ' . $image_class . '"' . ' title="' . htmlspecialchars($images->image_intro_caption) . '"';
                else:
                        echo 'class="img-responsive ' . $image_class . '"';
                endif;
                ?>
                        src="<?php echo JUri::root(true) . '/' . htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($displayData->title); ?>" itemprop="thumbnailUrl" /> </div>
<?php endif; ?>

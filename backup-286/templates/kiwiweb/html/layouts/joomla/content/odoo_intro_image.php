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
$image_span = $params->get('image_span', 2);
$empty_image = $params->get('empty_image', null);
$span = ' col-md-' . $image_span;
?>
<?php $images = json_decode($displayData->images); ?>
<?php if (isset($images->image_intro) && !empty($images->image_intro)) : ?>
        <?php $imgfloat = (empty($images->float_intro)) ? $params->get('float_intro') : $images->float_intro; ?>
        <div class="media-<?php echo htmlspecialchars($imgfloat); ?>">
                <a><img
                        <?php
                        if ($images->image_intro_caption):
                                echo 'class="media-object caption"' . ' title="' . htmlspecialchars($images->image_intro_caption) . '"';
                        else:
                                echo 'class="media-object"';
                        endif;
                        $image_alt = (!empty($images->image_intro_alt)) ? $images->image_intro_alt : $displayData->title;
                        ?>
                                src="<?php echo htmlspecialchars($images->image_intro); ?>"
                                alt="<?php echo htmlspecialchars($image_alt); ?>"
                                itemprop="thumbnailUrl"
                                class="img-thumbnail"
                                style="width: 64px; height: 64px;"
                                />
                </a>
        </div>
<?php elseif ($empty_image): ?>
        <?php $imgfloat = (empty($images->float_intro)) ? $params->get('float_intro') : $images->float_intro; ?>
        <div class="media-<?php echo htmlspecialchars($imgfloat); ?>">
                <img src="<?php echo $empty_image; ?>"
                     alt="<?php echo $displayData->title; ?>"
                     itemprop="thumbnailUrl"
                     class="media-object"
                     style="width: 64px; height: 64px;"
                     />
        </div>
<?php endif; ?>

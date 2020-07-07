<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
?>

<?php
$introcount = (count($this->link_items));
$counter = 0;
?>
<?php if (!empty($this->link_items)) : ?>
        <div class="header-title">
                <h1> <?php echo JText::_('TPL_KIWI_ERP_ANOTHER_VIDEO'); ?> </h1>
        </div>
        <div class="row links">
                <?php foreach ($this->link_items as $key => &$item) : ?>
                        <?php $rowcount = ((int) $key % (int) $this->columns) + 1; ?>
                        <?php if ($rowcount == 1) : ?>
                                <?php $row = $counter / $this->columns; ?>
                                <div class="items-row cols-<?php echo (int) $this->columns; ?> <?php echo 'row-' . $row; ?> clearfix">
                                <?php endif; ?>
                                <div class="col-md-<?php echo round((12 / $this->columns)); ?>">
                                        <div class="column-<?php echo $rowcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>"
                                             itemscope itemtype="http://schema.org/TechArticle">
                                                     <?php
                                                     $this->item = & $item;
                                                     echo $this->loadTemplate('item');
                                                     ?>
                                        </div>
                                        <!-- end item -->
                                        <?php $counter++; ?>
                                </div><!-- end col-md- -->
                                <?php if (($rowcount == $this->columns) or ( $counter == $introcount)) : ?>
                                </div><!-- end row -->
                        <?php endif; ?>
                <?php endforeach; ?>
        </div>
<?php endif; ?>

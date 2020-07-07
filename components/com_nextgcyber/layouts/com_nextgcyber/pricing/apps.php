<?php
/**
 * @package pkg_nextgcyber
 * @subpackage  com_nextgcyber
 * @copyright Copyright (C) 2015 NextgCyber . All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://nextgerp.com
 * @author NextG-ERP support@nextgerp.com
 */
defined('_JEXEC') or die;
$options = $this->options->toArray();
$currency = $this->options->get('currency', null);
$pricing_store = (!empty($options['pricing_store'])) ? $options['pricing_store'] : null;
$show_price = $this->options->get('show_price', true);
$label = $this->options->get('label', JText::_('COM_NEXTGCYBER_PRICING_CHOOSE_APPS'));
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h2 class="panel-title"><?php echo $label; ?></h2>
    </div>
    <div class="panel-body">
        <div class="alert alert-info">
            <?php echo JText::_('COM_NEXTGCYBER_PRICING_CHOOSE_APPS_DESC'); ?>
        </div>
        <?php
        $col = 3;
        $span = 12 / $col;
        $totalSpan = 0;
        foreach ($displayData as $product):
            if ($totalSpan >= 12) {
                echo '</div>';
                $totalSpan = 0;
            }
            if ($totalSpan == 0) {
                echo '<div class="row">';
            }
            $totalSpan += $span;
            ?>
            <div class="col-md-<?php echo $span; ?>">
                <div class="nc-pricing-product nc-app">
                    <div class="row">
                        <div class="col-md-3 col-xs-3 nc-pricing-image">
                            <?php if (!empty($product->content->link)): ?>
                                <a href="<?php echo $product->content->link; ?>" title="<?php echo $product->name; ?>">
                                <?php endif; ?>
                                <img src="data:image/png;base64,<?php echo $product->image; ?>" class="img-responsive icon_radius">
                                <?php if (!empty($product->content->link)): ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-9 col-xs-9">
                            <div class="nc-pricing-name">
                                <?php if (!empty($product->content->link)): ?>
                                    <a href="<?php echo $product->content->link; ?>" title="<?php echo $product->name; ?>"><?php echo $product->name; ?></a>
                                <?php else: ?>
                                    <span><?php echo $product->name; ?></span>
                                <?php endif; ?>

                            </div>
                            <?php if ($show_price): ?>
                                <div class="nc-pricing-price">
                                    <b><?php echo $product->nc_partner_price; ?> <span class="openerp_website_pricing_currency"><?php echo $currency->name; ?></span></b> / month
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                    $checked = (isset($pricing_store['apps'][$product->id])) ? ' checked' : '';
                    ?>
                    <input<?php echo $checked; ?> name="apps[<?php echo $product->id; ?>]"
                                                  data-price="<?php echo $product->nc_partner_price; ?>"
                                                  data-in-tax="<?php echo $product->in_tax; ?>"
                                                  data-out-tax="<?php echo $product->out_tax; ?>"
                                                  data-out-tax-name="<?php echo $product->out_tax_name; ?>"
                                                  class="nc-pricing-checkbox nc-pricing-item"
                                                  data-currency="<?php echo $currency->name; ?>"
                                                  data-nc-type="<?php echo $product->nc_type; ?>"
                                                  data-nc-module-type="<?php echo $product->nc_module_type; ?>"
                                                  data-id="<?php echo $product->id; ?>"
                                                  data-depend-parent="<?php echo implode(',', $product->nc_module_parent_ids); ?>"
                                                  data-depend-child="<?php echo implode(',', $product->nc_module_child_ids); ?>"
                                                  data-name="<?php echo $product->name; ?>"
                                                  type="checkbox">
                </div>
            </div>
        <?php endforeach; ?>
        <?php
        if ($totalSpan > 0) {
            echo '</div>';
        }
        ?>
    </div>
</div>
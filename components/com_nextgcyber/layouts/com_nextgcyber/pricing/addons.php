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
$addonsDesc = (!empty($options['addonsDesc'])) ? $options['addonsDesc'] : null;
$pricing_store = (!empty($options['pricing_store'])) ? $options['pricing_store'] : null;
$show_price = $this->options->get('show_price', true);
$span = $this->options->get('span', 6);
$default_user = $this->options->get('default_user', 1);
$default_storage = $this->options->get('default_storage', 1);
$default_bandwidth = $this->options->get('default_bandwidth', 2);
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h2 class="panel-title"><?php echo JText::_('COM_NEXTGCYBER_PRICING_CHOOSE_ADDONS'); ?></h2>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-<?php echo $span; ?>">
                <?php
                foreach ($displayData as $addon):
                    $label = ($addon->nc_type == 'odoo_user') ? 'Users' : 'GB';
                    if (isset($pricing_store['apps'][$addon->id])) {
                        $defaultValue = $pricing_store['apps'][$addon->id]['quantity'];
                    } else {
                        $defaultValue = ($addon->nc_type == 'odoo_storage') ? $default_storage : $default_bandwidth;
                        $defaultValue = ($addon->nc_type == 'odoo_user') ? $default_user : $defaultValue;
                    }
                    ?>
                    <div class="form-group">
                        <?php echo $addon->name; ?>
                        <div class="input-group col-md-10">
                            <div class="input-group-addon"><span class="fa fa-check-circle"></span></div>
                            <input data-currency="<?php echo $currency->name; ?>"
                                   data-id="<?php echo $addon->id; ?>"
                                   data-name="<?php echo $addon->name; ?>"
                                   data-price="<?php echo $addon->nc_partner_price; ?>"
                                   data-nc-type="<?php echo $addon->nc_type; ?>"
                                   data-nc-module-type="<?php echo $addon->nc_module_type; ?>"
                                   data-in-tax="<?php echo $addon->in_tax; ?>"
                                   data-out-tax="<?php echo $addon->out_tax; ?>"
                                   data-out-tax-name="<?php echo $addon->out_tax_name; ?>"
                                   type="text"
                                   name="<?php echo $addon->nc_type; ?>"
                                   class="form-control nc-pricing-item nc-addon"
                                   placeholder=""
                                   data-toggle="popover"
                                   data-placement="top"
                                   title="<?php echo $addon->name; ?>"
                                   data-content="<?php echo $addonsDesc[$addon->nc_type]; ?>"
                                   value="<?php echo $defaultValue; ?>">
                                   <?php if ($show_price): ?>
                                <div class="input-group-addon"><?php echo $label; ?> x <?php echo $addon->nc_partner_price; ?> <?php echo $currency->name; ?></div>
                            <?php else: ?>
                                <div class="input-group-addon"><?php echo $label; ?></div>
                            <?php endif; ?>
                            <div class="input-group-addon"><span class="fa fa-question-circle hasTooltip" data-placement="top" data-original-title="<?php echo $addonsDesc[$addon->nc_type]; ?>"></span></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
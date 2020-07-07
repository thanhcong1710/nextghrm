<?php /* ?>
  <div class="col-md-6">
  <?php
  foreach ($trainings as $training):
  $label = 'Hour';
  $defaultValue = 0;
  if (isset($pricing_store['training'][$training->id])) {
  $defaultValue = $pricing_store['training'][$training->id]['quantity'];
  } else {
  $defaultValue = 0;
  }
  ?>
  <div class="form-group">
  <?php echo $training->name; ?>
  <div class="input-group col-md-10">
  <div class="input-group-addon"><span class="fa fa-check-circle"></span></div>
  <input data-currency="<?php echo $currency->name; ?>" data-id="<?php echo $training->id; ?>" data-name="<?php echo $training->name; ?>" data-price="<?php echo $training->nc_partner_price; ?>" data-nc-type="<?php echo $training->nc_type; ?>" type="text" name="<?php echo $training->nc_type; ?>" class="form-control nc-pricing-item nc-addon" placeholder="" value="<?php echo $defaultValue; ?>">
  <div class="input-group-addon"><?php echo $label; ?> x <?php echo $training->nc_partner_price; ?> <?php echo $currency->name; ?></div>
  <div class="input-group-addon"><span class="fa fa-question-circle hasTooltip" data-placement="top" data-original-title="<?php echo $addonsDesc[$training->nc_type]; ?>"></span></div>
  </div>
  </div>
  <?php endforeach; ?>

  <?php
  foreach ($services as $service):
  ?>
  <div class="form-group">
  <?php echo $service->name; ?>
  <div class="input-group col-md-10">
  <div class="input-group-addon"><span class="fa fa-check-circle"></span></div>
  <?php
  $checked = (isset($pricing_store['service'][$service->id])) ? ' checked' : '';
  ?>
  <input<?php echo $checked; ?> name="services[<?php echo $service->id; ?>]" data-price="<?php echo $service->nc_partner_price; ?>" class="nc-pricing-checkbox nc-pricing-item form-control" data-currency="<?php echo $currency->name; ?>" data-nc-type="<?php echo $service->nc_type; ?>" data-id="<?php echo $service->id; ?>" data-depend-parent="" data-depend-child="" data-name="<?php echo $service->name; ?>" type="checkbox" style="outline: 1px solid #d9d9d9;margin:0;">
  <div class="input-group-addon"><b><?php echo $service->nc_partner_price; ?> <span class="openerp_website_pricing_currency"><?php echo $currency->name; ?></span></b> / month</div>
  <div class="input-group-addon"><span class="fa fa-question-circle hasTooltip" data-placement="top" data-original-title="<?php echo $addonsDesc[$service->nc_type]; ?>"></span></div>
  </div>

  </div>
  <?php endforeach; ?>
  </div>
  <?php */ ?>
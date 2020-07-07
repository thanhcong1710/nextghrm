<?php
/**
 * @package pkg_nextgcyber NextgCyber for Joomla
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2015 NextgCyber . All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://nextgerp.com
 * @author NextG-ERP support@nextgerp.com
 */
defined('_JEXEC') or die;
JHtml::_('jquery.framework');
JHtml::stylesheet('com_nextgcyber/site/main.css', false, true, false);
JHtml::script('com_nextgcyber/site/main.js', false, true, false);
JHtml::script('com_nextgcyber/site/pricing.js', false, true, false);
JLoader::register('NextgCyberCurrencyHelper', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/currencyhelper.php');
JLoader::register('NextgCyberCustomerHelper', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/customerhelper.php');
JLoader::register('NextgCyberIPHelper', JPATH_COMPONENT . '/helpers/iphelper.php');

$partner_id = NextgCyberCustomerHelper::getPartnerIdByID();
$user = JFactory::getUser();
if (!$user->guest && empty($partner_id)) {
    JFactory::getApplication()->redirect(NextgCyberHelperRoute::getPricingRoute());
    return false;
}

$formModel = JModelLegacy::getInstance('Form', 'NextgCyberModel');
$couponCodeModel = NextgCyberHelper::getAdminModel('CouponCode');
$currency = NextgCyberCurrencyHelper::getCurrency();
$current_domain = NextgCyberSiteHelper::getDomainName();
$return = base64_encode(JRoute::_(JUri::getInstance()->toString()));
$subheading = $this->params->get('page_subheading', '');
$ssl_included = NextgCyberHelper::getParam('ssl_included', true);
if ($subheading) {
    echo '<div>';
    echo $subheading;
    echo '</div>';
}

$apps = [];
$addons = [];
$trainings = [];
$services = [];

$odooThemeModels = JModelLegacy::getInstance('Themes', 'NextgCyberModel');
$odooThemeModels->clearState();
$themes = $odooThemeModels->getItems();

$odooCustomModuleModels = JModelLegacy::getInstance('CustomModules', 'NextgCyberModel');
$odooCustomModuleModels->clearState();
$customModules = $odooCustomModuleModels->getItems();

$addonsList = array('odoo_user', 'odoo_storage', 'odoo_bandwidth');
$addonsDesc = array(
    'odoo_user' => JText::_('COM_NEXTGCYBER_PRICING_USER_DESC'),
    'odoo_storage' => JText::_('COM_NEXTGCYBER_PRICING_STORAGE_DESC'),
    'odoo_bandwidth' => JText::_('COM_NEXTGCYBER_PRICING_BANDWIDTH_DESC'),
);
$session = JFactory::getSession();
$pricing_store = $session->get('pricing.store', array());

// Prepare pricing store
if (empty($pricing_store)) {
    $pricing_store['type'] = 'pricing';
} else {
    if (!empty($pricing_store['type']) && $pricing_store['type'] != 'pricing') {
        $pricing_store['type'] = 'pricing';
    }

    # Validate couponcode
    if (!empty($pricing_store['couponcode'])) {

        if (!$couponCodeModel->validate($pricing_store['couponcode']['code'], null, $partner_id)) {
            $pricing_store['couponcode'] = null;
        }
    }
}

#coupon code
$coupon_error_msg = (!empty($pricing_store['couponcode_error_msg'])) ? $pricing_store['couponcode_error_msg'] : '';
$coupon_success_msg = (!empty($pricing_store['couponcode_success_msg'])) ? $pricing_store['couponcode_success_msg'] : '';
$pricing_store['couponcode_error_msg'] = null;
$pricing_store['couponcode_success_msg'] = null;

$session->set('pricing.store', $pricing_store);

foreach ($this->items as $product):
    if ($product->nc_type == 'odoo_module' && $product->nc_module_type == 'standard') {
        $apps[] = $product;
    } elseif (in_array($product->nc_type, $addonsList)) {
        $key = array_search($product->nc_type, $addonsList);
        $addons[$key] = $product;
    } elseif ($product->nc_type == 'odoo_training') {
        $trainings[] = $product;
    } elseif ($product->nc_type == 'odoo_service') {
        $services[] = $product;
    }
endforeach;
ksort($addons);
$apps = NextgCyberSiteHelper::prepareApp($apps);
$domain = JFactory::getApplication()->input->get('odoo_name', '');
?>
<h1><?php echo JText::_('COM_NEXTGCYBER_PRICING_HEADER'); ?></h1>
<div class="row nextgcyber-pricing">
    <div class="col-md-8">
        <form class="form">
            <div class="pricing-board">
                <?php echo JLayoutHelper::render('com_nextgcyber.pricing.addons', $addons, JPATH_COMPONENT, array('currency' => $currency, 'addonsDesc' => $addonsDesc, 'pricing_store' => $pricing_store)); ?>
                <?php echo JLayoutHelper::render('com_nextgcyber.pricing.apps', $apps, JPATH_COMPONENT, array('currency' => $currency, 'pricing_store' => $pricing_store)); ?>
                <?php
                if ($customModules){
                    echo JLayoutHelper::render('com_nextgcyber.pricing.apps', $customModules, JPATH_COMPONENT, array('currency' => $currency, 'pricing_store' => $pricing_store, 'label'=> JText::_('COM_NEXTGCYBER_PRICING_CHOOSE_CUSTOM_APPS')));
                }
                
                if ($themes){
                    echo JLayoutHelper::render('com_nextgcyber.pricing.themes', $themes, JPATH_COMPONENT, array('currency' => $currency, 'pricing_store' => $pricing_store));
                }
                
                ?>
                
            </div> <!-- End  pricing-board-->
        </form>
    </div>
    <div class="col-md-4">
        <div class="pricing-subscription">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title"><?php echo JText::_('COM_NEXTGCYBER_PRICING_BILLING_HEADER'); ?></h2>
                </div>
                <div class="panel-body">

                    <table class="table table-striped nc-pricing-table">
                        <thead>
                            <tr>
                                <td style="width:60%"></td>
                                <td style="width:40%"></td>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>

                            <tr>
                                <td><strong><?php echo JText::_('COM_NEXTGCYBER_PRICING_TOTAL_MONTH'); ?></strong></td>
                                <td class="align-right nc-pricing-table-untaxed-amount-0"><span>0</span> <?php echo $currency->name; ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <strong><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_FORM_PAYMENT_PERIOD_LABEL'); ?></strong>
                                    <?php
                                    $paymentPeriod_options = $formModel->getPaymentPeriod();
                                    ?>
                                    <select class="form-control" name="payment_period_id" id="nc-payment-period-id">
                                        <?php
                                        foreach ($paymentPeriod_options as $key => $value) {
                                            echo '<option data-month="' . $value->month_number . '" data-discount="' . $value->discount . '" value="' . $value->id . '">' . $value->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr class="nc-pricing-table-period-discount">
                                <td class="nc-pricing-table-period-discount-percentage">Discount (<span>0</span>%)</td>
                                <td class="align-right nc-pricing-table-period-discount-amount"><span>0</span> <?php echo $currency->name; ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <?php
                                    $discountValue = (!empty($pricing_store['couponcode'])) ? $pricing_store['couponcode']['item']->value : 0;
                                    ?>
                                    <input type="hidden" data-title="Discount" value="<?php echo $discountValue; ?>" id="nc-discount-value"/>
                                    <strong><?php echo JText::_('COM_NEXTGCYBER_PRICING_HAVE_PROMOTION_CODE'); ?></strong>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><span class="fa fa-check-circle"></span></div>
                                                    <?php $active_code = (!empty($pricing_store['couponcode'])) ? $pricing_store['couponcode']['code'] : ''; ?>
                                                    <input type="text" name="code" class="form-control" id="promotional_code_input" placeholder="" value="<?php echo $active_code; ?>">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" id="nc-discount-apply-button" class="btn btn-primary btn-block"><?php echo JText::_('COM_NEXTGCYBER_PRICING_APPLY_PROMOTION_BUTTON'); ?></button>
                                        </div>
                                    </div>
                                    <div class="nc-coupon-discount-msg"></div>
                                </td>
                            </tr>
                            <tr class="nc-pricing-table-coupon-code-discount">
                                <td class="nc-pricing-table-coupon-code-discount-percentage">Discount (<span>0</span>%)</td>
                                <td class="align-right nc-pricing-table-coupon-code-discount-amount"><span>0</span> <?php echo $currency->name; ?></td>
                            </tr>
                            <tr style="border-top: 5px solid #D9D9D9;">

                            </tr>
                            <tr>
                                <td><strong><?php echo JText::_('COM_NEXTGCYBER_PRICING_UNTAXED_AMOUNT'); ?></strong></td>
                                <td class="align-right nc-pricing-table-untaxed-amount"><span>0</span> <?php echo $currency->name; ?></td>
                            </tr>
                            <tr class="nc-pricing-table-tax">
                                <td><strong></strong> <span class="tax-name"></span></td>
                                <td class="align-right nc-pricing-table-taxs-amount"><span>0</span> <?php echo $currency->name; ?></td>
                            </tr>
                            <tr>
                                <td><strong><?php echo JText::_('COM_NEXTGCYBER_PRICING_TOTAL_BILLING'); ?></strong></td>
                                <td class="align-right nc-pricing-table-total"><span>0</span> <?php echo $currency->name; ?></td>
                            </tr>

                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <?php echo JLayoutHelper::render('com_nextgcyber.pricing.domainform', null, JPATH_COMPONENT, array('domain' => $domain, 'current_domain' => $current_domain, 'ssl_included' => $ssl_included)); ?>
        <div class="row">
            <div class="col-md-6">
                <button class="btn btn-success btn-block nc-start-trial disabled"><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_START_FREE_TRIAL_BUTTON'); ?></button>
            </div>
            <div class="col-md-6">
                <button class="btn btn-success btn-block nc-start-trial nc-pay-now disabled"><?php echo JText::_('COM_NEXTGCYBER_PRICING_PAY_NOW_BUTTON'); ?></button>
            </div>
        </div>
    </div>
</div>
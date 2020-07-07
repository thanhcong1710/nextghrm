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
$partner_id = NextgCyberCustomerHelper::getPartnerIdByID();
$user = JFactory::getUser();
if (!$user->guest && empty($partner_id)) {
    JFactory::getApplication()->redirect(NextgCyberHelperRoute::getTryingRoute());
    return false;
}

$currency = NextgCyberCurrencyHelper::getCurrency();
$current_domain = NextgCyberSiteHelper::getDomainName();
$ssl_included = NextgCyberHelper::getParam('ssl_included', true);
$subheading = $this->params->get('page_subheading', '');
if ($subheading) {
    echo '<div>';
    echo $subheading;
    echo '</div>';
}

$apps = [];
$addons = [];
$addonsList = array('odoo_user', 'odoo_storage', 'odoo_bandwidth');
$addonsDesc = array(
    'odoo_user' => JText::_('COM_NEXTGCYBER_PRICING_USER_DESC'),
    'odoo_storage' => JText::_('COM_NEXTGCYBER_PRICING_STORAGE_DESC'),
    'odoo_bandwidth' => JText::_('COM_NEXTGCYBER_PRICING_BANDWIDTH_DESC'),
);
$session = JFactory::getSession();
$pricing_store = $session->get('pricing.store', false);
if (empty($pricing_store)) {
    $pricing_store = array();
}

if (empty($pricing_store)) {
    $pricing_store['type'] = 'trial';
} else {
    if (!empty($pricing_store['type']) && $pricing_store['type'] != 'trial') {
        $pricing_store['type'] = 'trial';
    }
}
$session->set('pricing.store', $pricing_store);
foreach ($this->items as $product):
    if ($product->nc_type == 'odoo_module') {
        $apps[] = $product;
    } elseif (in_array($product->nc_type, $addonsList)) {
        $key = array_search($product->nc_type, $addonsList);
        $addons[$key] = $product;
    }
endforeach;
ksort($addons);
$domain = JFactory::getApplication()->input->get('odoo_name', '');
?>
<div class="row nextgcyber-pricing">
    <div class="col-md-8">
        <form class="form">
            <div class="pricing-board">
                <?php echo JLayoutHelper::render('com_nextgcyber.pricing.addons', $addons, JPATH_COMPONENT, array('currency' => $currency, 'addonsDesc' => $addonsDesc, 'pricing_store' => $pricing_store, 'show_price' => false)); ?>
                <?php echo JLayoutHelper::render('com_nextgcyber.pricing.apps', $apps, JPATH_COMPONENT, array('currency' => $currency, 'pricing_store' => $pricing_store, 'show_price' => false)); ?>
            </div> <!-- End  pricing-board-->
        </form>

    </div>
    <div class="col-md-4">
        <?php echo JLayoutHelper::render('com_nextgcyber.pricing.domainform', null, JPATH_COMPONENT, array('domain' => $domain, 'current_domain' => $current_domain, 'ssl_included' => $ssl_included)); ?>
        <div class="row">
            <div class="col-md-6">
                <button class="btn btn-success btn-block nc-start-trial disabled"><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_START_FREE_TRIAL_BUTTON'); ?></button>
            </div>
            <div class="col-md-6">
                <a href="<?php echo JRoute::_(NextgCyberHelperRoute::getPricingRoute()); ?>" class="btn btn-success btn-block"><?php echo JText::_('COM_NEXTGCYBER_PRICING_PAY_NOW_BUTTON'); ?></a>
            </div>
        </div>
    </div>
</div>
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
?>
<h1><?php echo JText::_('COM_NEXTGCYBER_THEME_HEADER'); ?></h1>
<div class="nextgcyber-theme">
    <?php
    $col = 4;
    $span = 12 / $col;
    $totalSpan = 0;
    ?>
    <?php foreach ($this->items as $product): ?>
        <?php
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
            <div class="theme-item">
                <div class="item-image">
                    <?php if (!empty($product->content->link)): ?>
                        <a href="<?php echo $product->content->link; ?>" title="<?php echo $product->name; ?>">
                        <?php endif; ?>
                        <img src="data:image/png;base64,<?php echo $product->image; ?>" class="img-responsive icon_radius">
                        <?php if (!empty($product->content->link)): ?>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="item-name">
                    <?php if (!empty($product->content->link)): ?>
                        <a href="<?php echo $product->content->link; ?>" title="<?php echo $product->name; ?>"><?php echo $product->name; ?></a>
                    <?php else: ?>
                        <span><?php echo $product->name; ?></span>
                    <?php endif; ?>
                </div>
                <div class="item-price">
                    <div class="nc-pricing-price">
                        <b><?php echo $product->nc_partner_price; ?> <span class="openerp_website_pricing_currency"><?php echo $currency->name; ?></span></b> / month
                    </div>
                </div>
                <div class="item-button">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="#" class="btn btn-button btn-block btn-primary">View More</a>
                        </div>
                        <div class="col-md-6">
                            <a href="#" class="btn btn-button btn-block btn-success">Live demo</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <?php
    endforeach;
    ?>
    <?php
    if ($totalSpan > 0) {
        echo '</div>';
    }
    ?>
</div>
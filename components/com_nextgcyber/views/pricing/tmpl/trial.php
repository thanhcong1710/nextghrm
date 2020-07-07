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
$currency = NextgCyberCurrencyHelper::getCurrency();
$subheading = $this->params->get('page_subheading', '');
if ($subheading) {
    echo '<div>';
    echo $subheading;
    echo '</div>';
}
$user = JFactory::getUser();
$apps = [];
$addons = [];

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
    if ($product->nc_type == 'odoo_module' && $product->nc_module_type == 'standard') {
        $apps[] = $product;
    } elseif (in_array($product->nc_type, $addonsList)) {
        $key = array_search($product->nc_type, $addonsList);
        $addons[$key] = $product;
    }
endforeach;
ksort($addons);
$apps = NextgCyberSiteHelper::prepareApp($apps);
$domain = JFactory::getApplication()->input->get('odoo_name', '');
?>
<div class="row nextgcyber-pricing">
    <div class="col-md-8">
        <form class="form">
            <div class="pricing-board">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title"><?php echo JText::_('COM_NEXTGCYBER_PRICING_CHOOSE_APPS'); ?></h2>
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-info">
                            <?php echo JText::_('COM_NEXTGCYBER_PRICING_CHOOSE_APPS_DESC'); ?>
                        </div>
                        <?php
                        $col = 3;
                        $span = 12 / $col;
                        $totalSpan = 0;
                        foreach ($apps as $product):
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

                                        </div>
                                    </div>
                                    <?php
                                    $checked = (isset($pricing_store['apps'][$product->id])) ? ' checked' : '';
                                    ?>
                                    <input<?php echo $checked; ?> name="apps[<?php echo $product->id; ?>]" data-price="<?php echo $product->lst_price; ?>" class="nc-pricing-checkbox nc-pricing-item" data-currency="<?php echo $currency->name; ?>" data-nc-type="<?php echo $product->nc_type; ?>" data-nc-module-type="<?php echo $product->nc_module_type; ?>" data-id="<?php echo $product->id; ?>" data-depend-parent="<?php echo implode(',', $product->nc_module_parent_ids); ?>" data-depend-child="<?php echo implode(',', $product->nc_module_child_ids); ?>" data-name="<?php echo $product->name; ?>" type="checkbox">
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
                <?php if (!empty($themes)): ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="panel-title"><?php echo JText::_('COM_NEXTGCYBER_PRICING_CHOOSE_THEMES'); ?></h2>
                        </div>
                        <div class="panel-body">
                            <div class="alert alert-info">
                                <?php echo JText::_('COM_NEXTGCYBER_PRICING_CHOOSE_THEMES_DESC'); ?>
                            </div>
                            <?php
                            $col = 3;
                            $span = 12 / $col;
                            $totalSpan = 0;
                            foreach ($themes as $product):
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

                                            </div>
                                        </div>
                                        <?php
                                        $checked = (isset($pricing_store['apps'][$product->id])) ? ' checked' : '';
                                        ?>
                                        <input<?php echo $checked; ?> name="apps[<?php echo $product->id; ?>]" data-price="<?php echo $product->lst_price; ?>" class="nc-pricing-checkbox nc-pricing-item" data-currency="<?php echo $currency->name; ?>" data-nc-type="<?php echo $product->nc_type; ?>" data-nc-module-type="<?php echo $product->nc_module_type; ?>" data-id="<?php echo $product->id; ?>" data-depend-parent="<?php echo implode(',', $product->nc_module_parent_ids); ?>" data-depend-child="<?php echo implode(',', $product->nc_module_child_ids); ?>" data-name="<?php echo $product->name; ?>" type="checkbox">
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
                <?php endif; ?>

            </div> <!-- End  pricing-board-->
        </form>

    </div>
    <div class="col-md-4">
        <?php if (!$user->guest): ?>
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <span class="fa fa-plus"></span> <?php echo JText::_('COM_NEXTGCYBER_PRICING_USE_SUBDOMAIN_LABEL'); ?>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">
                            <ul>
                                <li>Free</li>
                                <li>Quick Access</li>
                                <li>SSL included</li>
                            </ul>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="fa fa-lock"></span> https://</div>
                                    <input type="text" name="domain" class="form-control nc-input-subdomain" id="input_domain" placeholder="Domain name" value="<?php echo $domain; ?>">
                                    <div class="input-group-addon">.nextgerp.com</div>
                                    <div class="input-group-addon"><span class="fa fa-question-circle hasTooltip" data-placement="top" data-original-title="<?php echo JText::_('COM_NEXTGCYBER_PRICING_ODOO_SETTINGS_DESC'); ?>"></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingTwo">
                        <h4 class="panel-title">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <span class="fa fa-plus"></span> <?php echo JText::_('COM_NEXTGCYBER_PRICING_USE_CUSTOMER_DOMAIN_LABEL'); ?>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                        <div class="panel-body">
                            <p><?php echo JText::_('COM_NEXTGCYBER_PRICUNG_USE_CUSTOMER_DOMAIN_DESC'); ?></p>
                            <p class="nc-input-domain-label align-center"></p>
                            <a class="btn btn-success nc-button nc-addDomain" data-id="0" data-action="getCustomDomainForm"><?php echo JText::_('COM_NEXTGCYBER_PRICING_ADD_CUSTOMER_DOMAIN_BUTTON'); ?></a>
                            <input type="hidden" name="domain_id" class="nc-input-domain_id"/>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-success btn-block nc-start-trial disabled"><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_START_FREE_TRIAL_BUTTON'); ?></button>
        <?php else: ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title"><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_FORM_ERROR_GUEST_REQUEST'); ?></h2>
                </div>
                <div class="panel-body">
                    <?php echo JLayoutHelper::render('com_nextgcyber.form.loginform', null, JPATH_COMPONENT); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
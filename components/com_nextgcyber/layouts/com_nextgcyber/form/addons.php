<?php
/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://domain.com
 * @author Daniel.Vu
 */
defined('_JEXEC') or die('Restricted access');
JLoader::register('NextgCyberCurrencyHelper', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/currencyhelper.php');
$return = (!empty($displayData->return)) ? $displayData->return : base64_encode(JRoute::_('index.php'));
$currency = NextgCyberCurrencyHelper::getCurrency();
$formModel = JModelLegacy::getInstance('Form', 'NextgCyberModel');
$apps = [];
$addons = [];
$addonsList = array('odoo_user', 'odoo_storage', 'odoo_bandwidth');
$addonsDesc = array(
    'odoo_user' => JText::_('COM_NEXTGCYBER_PRICING_USER_DESC'),
    'odoo_storage' => JText::_('COM_NEXTGCYBER_PRICING_STORAGE_DESC'),
    'odoo_bandwidth' => JText::_('COM_NEXTGCYBER_PRICING_BANDWIDTH_DESC'),
);
foreach ($displayData->apps as $product):
    if ($product->nc_type == 'odoo_module') {
        $apps[] = $product;
    } elseif (in_array($product->nc_type, $addonsList)) {
        $key = array_search($product->nc_type, $addonsList);
        $addons[$key] = $product;
    }
endforeach;
ksort($addons);
?>
<div>
    <form class="form-vertical" method="POST">
        <div class="alert alert-info">
            <?php echo $displayData->subtitle; ?>
        </div>
        <div class="pricing-board">
            <?php
            echo JLayoutHelper::render('com_nextgcyber.pricing.addons', $addons, JPATH_COMPONENT, array(
                'currency' => $currency,
                'addonsDesc' => $addonsDesc,
                'span' => 12,
                'default_user' => 0,
                'default_storage' => 0,
                'default_bandwidth' => 0,
            ));
            ?>
            <?php echo JLayoutHelper::render('com_nextgcyber.pricing.apps', $apps, JPATH_COMPONENT, array('currency' => $currency)); ?>
        </div> <!-- End  pricing-board-->

        <div class="form-group">
            <div>
                <button type="button" class="btn btn-primary nc-addons-button" data-id="<?php echo $displayData->id; ?>" data-return="<?php echo $return; ?>">
                    <?php echo JText::_('COM_NEXTGCYBER_INSTANCE_FORM_SUBMIT_BUTTON'); ?>
                </button>
            </div>
        </div>
        <input type="hidden" value="com_nextgcyber" name="option" />
        <input type="hidden" value="<?php echo $displayData->id; ?>" name="id" />
        <input type="hidden" value="json" name="format" />
        <input type="hidden" value="instance.addAddons" name="task" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
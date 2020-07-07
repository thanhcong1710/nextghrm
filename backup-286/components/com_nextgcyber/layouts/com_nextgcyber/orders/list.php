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
JLoader::register('NextgCyberCurrencyHelper', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/currencyhelper.php');
$currency = NextgCyberCurrencyHelper::getCurrency();
$user_tz = NextgCyberCustomerHelper::getUserTimezone();
$orderModel = JModelLegacy::getInstance('Order', 'NextgCyberModel');
$i = 0;
if (!empty($displayData)):
    ?>
    <div class="panel-group" id="accordion-order-list" role="tablist" aria-multiselectable="true">
        <?php
        foreach ($displayData as $order):
            $active = ($i == 0) ? true : false;
            echo JLayoutHelper::render('com_nextgcyber.orders.item', $order, JPATH_COMPONENT, array('active' => $active));
            $i++;
        endforeach;
        ?>
    </div>
<?php endif; ?>
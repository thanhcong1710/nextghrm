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
$active = $this->options->get('active', true);
$ajax = $this->options->get('ajax', false);
$return = $this->options->get('return', false);
$display_info = $this->options->get('display_info', true);
$class = ($active) ? ' in ' : '';
?>
<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="heading<?php echo $displayData->id; ?>">
        <h4 class="panel-title">
            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion-order-list" href="#order-collapse<?php echo $displayData->id; ?>" aria-expanded="false" aria-controls="order-collapse<?php echo $displayData->id; ?>">
                <?php echo JText::sprintf('COM_NEXTGCYBER_ORDER_NUMBER_PREFIX', $displayData->name); ?>
            </a>
        </h4>
    </div>
    <div id="order-collapse<?php echo $displayData->id; ?>" class="panel-collapse collapse<?php echo $class; ?>" role="tabpanel" aria-labelledby="heading<?php echo $displayData->id; ?>">
        <?php if ($display_info): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert">
                        <ul>
                            <?php
                            if (is_array($displayData->partner_id)) {
                                $partner_name = $displayData->partner_id[1];
                            } else {
                                $partner_name = $displayData->partner_id;
                            }
                            if (isset($displayData->partner_id_title)) {
                                $partner_name = $displayData->partner_id_title;
                            }
                            ?>
                            <li><?php echo JText::sprintf('COM_NEXTGCYBER_ORDER_CUSTOMER', $partner_name); ?></li>
                            <?php if (!empty($displayData->nc_payment_period_id)): ?>
                                <?php
                                if (is_array($displayData->nc_payment_period_id)) {
                                    $payment_period = $displayData->nc_payment_period_id[1];
                                } else {
                                    $payment_period = $displayData->nc_payment_period_id;
                                }
                                if (isset($displayData->nc_payment_period_id_title)) {
                                    $payment_period = $displayData->nc_payment_period_id_title;
                                }
                                ?>
                                <li><?php echo JText::sprintf('COM_NEXTGCYBER_ORDER_PAYMENT_PERIOD', $payment_period); ?></li>
                            <?php endif; ?>
                            <li>
                                <?php echo JText::sprintf('COM_NEXTGCYBER_ORDER_STATE', $orderModel->getStateLabel($displayData->state)); ?>
                            </li>
                            <li><?php echo JText::sprintf('COM_NEXTGCYBER_ORDER_CREATE_DATE', JHtml::_('date', $displayData->create_date, JText::_('DATE_FORMAT_LC3'), $user_tz)); ?></li>
                            <li><?php echo JText::sprintf('COM_NEXTGCYBER_ORDER_DATE_CONFIRM', JHtml::_('date', $displayData->date_order, JText::_('DATE_FORMAT_LC3'), $user_tz)); ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php echo JLayoutHelper::render('com_nextgcyber.orders.orderlines', $displayData->orderlines, JPATH_COMPONENT, null); ?>
        <div class="row">
            <div class="col-md-6 col-md-offset-6">
                <table class="table table-discuss">
                    <tfoot>
                        <tr>
                            <td class="align-right"><strong><?php echo JText::_('COM_NEXTGCYBER_ORDER_UNTAXED'); ?></strong>:</td>
                            <td class="align-right"><?php echo NextgCyberNumberHelper::format($displayData->amount_untaxed); ?> <?php echo $currency->name; ?></td>
                        </tr>
                        <tr>
                            <td class="align-right"><strong><?php echo JText::_('COM_NEXTGCYBER_ORDER_TAX'); ?></strong>:</td>
                            <td class="align-right"><?php echo NextgCyberNumberHelper::format($displayData->amount_tax); ?> <?php echo $currency->name; ?></td>
                        </tr>
                        <tr>
                            <td class="align-right"><strong><?php echo JText::_('COM_NEXTGCYBER_ORDER_AMOUNT_TOTAL'); ?></strong>:</td>
                            <td class="align-right"><?php echo NextgCyberNumberHelper::format($displayData->amount_total); ?> <?php echo $currency->name; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <?php
        if ($displayData->state == 'draft'):
            ?>
            <div class="row">
                <div class="col-md-6 col-md-offset-6">
                    <div class="row" style="margin-bottom:20px;">
                        <div class="col-md-6">
                            <?php if (!$ajax): ?>
                                <a href="<?php echo JRoute::_(NextgCyberHelperRoute::getCancelOrderRoute($displayData->id)); ?>" class="btn btn-danger btn-block">Delete</a>
                            <?php else: ?>
                                <a href="<?php echo JRoute::_(NextgCyberHelperRoute::getCancelOrderRoute($displayData->id, $return)); ?>" class="btn btn-danger btn-block">Delete</a>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <a href="<?php echo JRoute::_(NextgCyberHelperRoute::getConfirmOrderRoute($displayData->id)); ?>" class="btn btn-success btn-block"><?php echo JText::_('COM_NEXTGCYBER_INVOICE_REGISTER_PAYMENT_BUTTON'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
/**
 * @package pkg_nextgcyber
 * @subpackage  com_nextgcyber
 * @copyright Copyright (C) 2015 NextgCyber . All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://nextgerp.com
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;
JLoader::register('NextgCyberCurrencyHelper', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/currencyhelper.php');
$currency = NextgCyberCurrencyHelper::getCurrency();
$user_tz = NextgCyberCustomerHelper::getUserTimezone();
$invoiceModel = JModelLegacy::getInstance('Invoice', 'NextgCyberModel');
$i = 0;
if (!empty($displayData)):
    ?>
    <div class="panel-group" id="accordion-invoice-list" role="tablist" aria-multiselectable="true">
        <?php foreach ($displayData as $invoice): ?>
            <?php $active = ($i == 0) ? ' in ' : ''; ?>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading<?php echo $invoice->id; ?>">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion-invoice-list" href="#invoice_collapse<?php echo $invoice->id; ?>" aria-expanded="false" aria-controls="invoice_collapse<?php echo $invoice->id; ?>">
                            <?php echo JText::sprintf('COM_NEXTGCYBER_INVOICE_NUMBER_PREFIX', $invoice->number); ?>
                        </a>
                    </h4>
                </div>
                <div id="invoice_collapse<?php echo $invoice->id; ?>" class="panel-collapse collapse<?php echo $active; ?>" role="tabpanel" aria-labelledby="heading<?php echo $invoice->id; ?>">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="alert">
                                <ul>
                                    <li><?php echo JText::sprintf('COM_NEXTGCYBER_INVOICE_CUSTOMER', $invoice->partner_id[1]); ?></li>
                                    <li><?php echo JText::sprintf('COM_NEXTGCYBER_INVOICE_REFERENCE_DOCUMENT', $invoice->reference); ?></li>
                                    <li>
                                        <?php echo JText::sprintf('COM_NEXTGCYBER_INVOICE_STATE', $invoiceModel->getStateLabel($invoice->state)); ?>
                                    </li>
                                    <li><?php echo JText::sprintf('COM_NEXTGCYBER_INVOICE_DATE_DUE', JHtml::_('date', $invoice->date_due, JText::_('DATE_FORMAT_LC3'), $user_tz)); ?></li>
                                    <li><?php echo JText::sprintf('COM_NEXTGCYBER_INVOICE_DATE_INVOICE', JHtml::_('date', $invoice->date_invoice, JText::_('DATE_FORMAT_LC3'), $user_tz)); ?></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="alert">
                                <a class="btn btn-info btn-block" href="<?php echo JRoute::_(NextgCyberHelperRoute::getInvoiceRoute($invoice->id)); ?>">
                                    <?php echo JText::_('COM_NEXTGCYBER_INVOICE_DETAIL_BUTTON_LABEL'); ?>
                                </a>
                                <?php
                                if ($invoice->state == 'open') {
                                    echo '<a href="' . JRoute::_(NextgCyberHelperRoute::getRegisterPaymentRoute($invoice->id)) . '" class="btn btn-success btn-block">' . JText::_('COM_NEXTGCYBER_INVOICE_REGISTER_PAYMENT_BUTTON') . '</a>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php echo JLayoutHelper::render('com_nextgcyber.invoices.invoicelines', $invoice->invoicelines, JPATH_COMPONENT, null); ?>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6">
                            <table class="table table-discuss">
                                <tfoot>
                                    <tr>
                                        <td class="align-right"><strong><?php echo JText::_('COM_NEXTGCYBER_INVOICE_UNTAXED'); ?></strong>:</td>
                                        <td class="align-right"><?php echo NextgCyberNumberHelper::format($invoice->amount_untaxed); ?> <?php echo $currency->name; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="align-right"><strong><?php echo JText::_('COM_NEXTGCYBER_INVOICE_TAX'); ?></strong>:</td>
                                        <td class="align-right"><?php echo NextgCyberNumberHelper::format($invoice->amount_tax); ?> <?php echo $currency->name; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="align-right"><strong><?php echo JText::_('COM_NEXTGCYBER_INVOICE_AMOUNT_TOTAL'); ?></strong>:</td>
                                        <td class="align-right"><?php echo NextgCyberNumberHelper::format($invoice->amount_total); ?> <?php echo $currency->name; ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $i++;
        endforeach;
        ?>
    </div>
<?php endif; ?>
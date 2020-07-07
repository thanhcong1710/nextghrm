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
JLoader::register('NextgCyberCurrencyHelper', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/currencyhelper.php');
$currency = NextgCyberCurrencyHelper::getCurrency();
$user_tz = NextgCyberCustomerHelper::getUserTimezone();
?>
<?php if (empty($this->item)): ?>
    <div class="alert alert-danger"><?php echo JText::_('COM_NEXTGCYBER_ERROR_INVOICE_NOT_FOUND'); ?></div>
<?php else: ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-title"><?php echo JText::sprintf('COM_NEXTGCYBER_INVOICE_NUMBER_PREFIX', $this->item->number); ?></h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item"><?php echo JText::sprintf('COM_NEXTGCYBER_INVOICE_CUSTOMER', $this->item->partner_id[1]); ?></li>
                        <li class="list-group-item"><?php echo JText::sprintf('COM_NEXTGCYBER_INVOICE_REFERENCE_DOCUMENT', $this->item->reference); ?></li>
                        <li class="list-group-item">
                            <?php echo JText::sprintf('COM_NEXTGCYBER_INVOICE_STATE', $this->model->getStateLabel($this->item->state)); ?>
                            <?php
                            if ($this->item->state == 'open') {
                                echo '<a href="' . JRoute::_(NextgCyberHelperRoute::getRegisterPaymentRoute($this->item->id)) . '" class="btn btn-success">' . JText::_('COM_NEXTGCYBER_INVOICE_REGISTER_PAYMENT_BUTTON') . '</a>';
                            }
                            ?>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li><?php echo JText::sprintf('COM_NEXTGCYBER_INVOICE_DATE_DUE', JHtml::_('date', $this->item->date_due, JText::_('DATE_FORMAT_LC3'), $user_tz)); ?></li>
                        <li><?php echo JText::sprintf('COM_NEXTGCYBER_INVOICE_DATE_INVOICE', JHtml::_('date', $this->item->date_invoice, JText::_('DATE_FORMAT_LC3'), $user_tz)); ?></li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
    <?php echo JHtml::_('bootstrap.startTabSet', 'mytab', array('active' => 'general')); ?>

    <?php echo JHtml::_('bootstrap.addTab', 'mytab', 'general', JText::_('COM_NEXTGCYBER_INVOICE_INVOICELINES_TAB', true)); ?>
    <?php echo JLayoutHelper::render('com_nextgcyber.invoices.invoicelines', $this->item->invoicelines, JPATH_COMPONENT, null); ?>
    <div class="row">
        <div class="col-md-6 col-md-offset-6">
            <table class="table table-discuss">
                <tfoot>
                    <tr>
                        <td class="align-right"><?php echo JText::_('COM_NEXTGCYBER_INVOICE_UNTAXED'); ?>:</td>
                        <td class="align-right"><?php echo NextgCyberNumberHelper::format($this->item->amount_untaxed); ?> <?php echo $currency->name; ?></td>
                    </tr>
                    <tr>
                        <td class="align-right"><?php echo JText::_('COM_NEXTGCYBER_INVOICE_TAX'); ?>:</td>
                        <td class="align-right"><?php echo NextgCyberNumberHelper::format($this->item->amount_tax); ?> <?php echo $currency->name; ?></td>
                    </tr>
                    <tr>
                        <td class="align-right"><?php echo JText::_('COM_NEXTGCYBER_INVOICE_AMOUNT_TOTAL'); ?>:</td>
                        <td class="align-right"><?php echo NextgCyberNumberHelper::format($this->item->amount_total); ?> <?php echo $currency->name; ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <?php echo JHtml::_('bootstrap.endTab'); ?>

    <?php echo JHtml::_('bootstrap.addTab', 'mytab', 'payment', JText::_('COM_NEXTGCYBER_INVOICE_PAYMENTS_TAB', true)); ?>
    <?php echo JLayoutHelper::render('com_nextgcyber.invoices.payments', $this->item->payments, JPATH_COMPONENT, null); ?>
    <?php echo JHtml::_('bootstrap.endTab'); ?>

    <?php echo JHtml::_('bootstrap.endTabSet'); ?>
<?php endif; ?>
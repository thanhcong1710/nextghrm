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
JLoader::register('NextgCyberCustomerHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/customerhelper.php');
JLoader::register('NextgCyberNumberHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/numberhelper.php');
JLoader::register('NextgCyberCurrencyHelper', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/currencyhelper.php');
$currency = NextgCyberCurrencyHelper::getCurrency();
$user_tz = NextgCyberCustomerHelper::getUserTimezone();
?>
<table class="table table-discuss">
    <thead>
        <tr>
            <th><?php echo JText::_('COM_NEXTGCYBER_INVOICE_PAYMENT_EFFECTIVE_DATE_LABEL'); ?></th>
            <th><?php echo JText::_('COM_NEXTGCYBER_INVOICE_PAYMENT_REFERENCE_LABEL'); ?></th>
            <th><?php echo JText::_('COM_NEXTGCYBER_INVOICE_PAYMENT_JOURNAL_LABEL'); ?></th>
            <th class="align-right"><?php echo JText::_('COM_NEXTGCYBER_INVOICE_PAYMENT_DEBIT_LABEL'); ?></th>
            <th class="align-right"><?php echo JText::_('COM_NEXTGCYBER_INVOICE_PAYMENT_CREDIT_LABEL'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($displayData as $payment):
            ?>
            <tr>
                <td><?php echo JHtml::_('date', $payment->date, JText::_('DATE_FORMAT_LC3'), $user_tz); ?></td>
                <td><?php echo $payment->ref; ?></td>
                <td><?php echo $payment->journal_id[1]; ?></td>
                <td class="align-right"><?php echo NextgCyberNumberHelper::format($payment->debit); ?> <?php echo $currency->name; ?></td>
                <td class="align-right">
                    <?php echo NextgCyberNumberHelper::format($payment->credit); ?> <?php echo $currency->name; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
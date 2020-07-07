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
JLoader::register('NextgCyberNumberHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/numberhelper.php');
JLoader::register('NextgCyberCurrencyHelper', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/currencyhelper.php');
$currency = NextgCyberCurrencyHelper::getCurrency();
?>
<table class="table table-discuss">
    <thead>
        <tr>
            <th><?php echo JText::_('COM_NEXTGCYBER_INVOICE_LINE_NAME_LABEL'); ?></th>
            <th><?php echo JText::_('COM_NEXTGCYBER_INVOICE_LINE_DESCRIPTION_LABEL'); ?></th>
            <th class="align-right"><?php echo JText::_('COM_NEXTGCYBER_INVOICE_LINE_QUANTITY_LABEL'); ?></th>
            <th class="align-right"><?php echo JText::_('COM_NEXTGCYBER_INVOICE_LINE_UNITPRICE_LABEL'); ?></th>
            <th><?php echo JText::_('COM_NEXTGCYBER_INVOICE_LINE_TAXES_LABEL'); ?></th>
            <th class="align-right"><?php echo JText::_('COM_NEXTGCYBER_INVOICE_LINE_AMOUNT_LABEL'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($displayData as $invoice_line):
            ?>
            <tr>
                <td><?php echo $invoice_line->product_id[1]; ?></td>
                <td><?php echo $invoice_line->name; ?></td>
                <td class="align-right"><?php echo NextgCyberNumberHelper::format($invoice_line->quantity); ?></td>
                <td class="align-right"><?php echo NextgCyberNumberHelper::format($invoice_line->price_unit); ?> <?php echo $currency->name; ?></td>
                <td>
                    <?php
                    if (!empty($invoice_line->taxes)) {
                        foreach ($invoice_line->taxes as $tax) {
                            echo '<span class="label label-default">' . $tax->display_name . '</span>';
                        }
                    }
                    ?>
                </td>
                <td class="align-right"><?php echo NextgCyberNumberHelper::format($invoice_line->price_subtotal); ?> <?php echo $currency->name; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
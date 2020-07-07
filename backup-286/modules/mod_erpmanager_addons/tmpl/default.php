<?php
/**
 * @package pkg_nextgcyber
 * @subpackage  mod_nextgcyber_addons
 *
 * @copyright Copyright (C) 2015 NextG-ERP . All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://nextgerp.com
 * @author NextG-ERP support@nextgerp.com
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<?php
$mixed_addons = [];
$bw_addons = [];
$acc_addons = [];
$space_addons = [];
foreach ($addons as $addon) {
        $has_bw = (is_null($addon->max_bwpermonth) || $addon->max_bwpermonth > 0);
        $has_acc = (is_null($addon->max_uaccounts) || $addon->max_uaccounts > 0);
        $has_space = (is_null($addon->max_spacesize) || $addon->max_spacesize > 0);
        if ($has_bw && $has_acc && $has_space) {
                $mixed_addons[] = $addon;
        } elseif ($has_bw && !$has_acc && !$has_space) {
                $bw_addons[] = $addon;
        } elseif (!$has_bw && $has_acc && !$has_space) {
                $acc_addons[] = $addon;
        } elseif (!$has_bw && !$has_acc && $has_space) {
                $space_addons[] = $addon;
        } else {
                // Currently, we only support the above mentioned types of addon
                // other types will not be available for the frontend display
        }
}
?>
<div class="row nextgcyber-std-addon">

        <div class="col-sm-4">
                <table class="table table-hover">
                        <thead>
                                <tr>
                                        <th><?php echo JText::_('MOD_NEXTGCYBER_ADDONS_FIELD_TITLE_LABLE'); ?></th>
                                        <th class="text-right" width="30%"><?php echo JText::_('COM_NEXTGCYBER_PLAN_UNIT_PRICE_LABEL'); ?></th>
                                        <td width="10%"></td>
                                </tr>
                        </thead>
                        <?php foreach ($acc_addons as $addon): ?>

                                <tbody>
                                        <tr>
                                                <td>
                                                        <span class="fa fa-user"></span>
                                                        <?php echo is_null($addon->max_uaccounts) ? JText::_('COM_NEXTGCYBER_PLAN_MAX_UACCOUNT_UNLIMIT_LABEL') : JText::sprintf('COM_NEXTGCYBER_PLAN_MAX_UACCOUNT_LABEL', $addon->max_uaccounts); ?>
                                                </td>
                                                <td>
                                                        <p class="pull-right">
                                                                <?php echo $addon->currency_symbol . ' ' . number_format($addon->monthly_price, 0); ?>
                                                        </p>
                                                </td>
                                                <td>
                                                        <?php
                                                        $url = JRoute::_('index.php?option=com_nextgcyber&task=order.select&plan_id=' . $addon->id . '&return=' . base64_encode(JURI::getInstance()->toString()));
                                                        $text = '<span class="fa fa-shopping-cart"></span>';
                                                        $attr = [
                                                            'class' => 'btn btn-default btn-small hasTooltip',
                                                            'title' => JHtml::tooltipText(JText::_('COM_NEXTGCYBER_ADDON_BUY_BUTTON_LABEL'), JText::_('COM_NEXTGCYBER_ADDON_BUY_BUTTON_DESC')),
                                                        ];
                                                        echo JHtml::link($url, $text, $attr);
                                                        ?>
                                                </td>
                                        </tr>
                                </tbody>

                        <?php endforeach; ?>
                </table>
        </div>
        <div class="col-sm-4">
                <table class="table table-hover">
                        <thead>
                                <tr>
                                        <th><?php echo JText::_('MOD_NEXTGCYBER_ADDONS_FIELD_TITLE_LABLE'); ?></th>
                                        <th class="text-center" width="30%"><?php echo JText::_('COM_NEXTGCYBER_PLAN_UNIT_PRICE_LABEL'); ?></th>
                                        <td width="10%"></td>
                                </tr>
                        </thead>
                        <?php foreach ($space_addons as $addon): ?>

                                <tbody>
                                        <tr>
                                                <td><span class="fa fa-database"></span> <?php echo JText::sprintf('COM_NEXTGCYBER_PLAN_MAX_SPACESIZE_LABEL', $addon->max_spacesize); ?></td>
                                                <td><p class="pull-right"><?php echo $addon->currency_symbol . ' ' . number_format($addon->monthly_price, 0); ?></p></td>
                                                <td>
                                                        <?php
                                                        $url = JRoute::_('index.php?option=com_nextgcyber&task=order.select&plan_id=' . $addon->id . '&return=' . base64_encode(JURI::getInstance()->toString()));
                                                        $text = '<span class="fa fa-shopping-cart"></span>';
                                                        $attr = [
                                                            'class' => 'btn btn-default btn-small hasTooltip',
                                                            'title' => JHtml::tooltipText(JText::_('COM_NEXTGCYBER_ADDON_BUY_BUTTON_LABEL'), JText::_('COM_NEXTGCYBER_ADDON_BUY_BUTTON_DESC')),
                                                        ];
                                                        echo JHtml::link($url, $text, $attr);
                                                        ?>
                                                </td>
                                        </tr>
                                </tbody>

                        <?php endforeach; ?>
                </table>
        </div>
        <div class="col-sm-4">
                <table class="table table-hover">
                        <thead>
                                <tr>
                                        <th><?php echo JText::_('MOD_NEXTGCYBER_ADDONS_FIELD_TITLE_LABLE'); ?></th>
                                        <th class="text-center" width="30%"><?php echo JText::_('COM_NEXTGCYBER_PLAN_UNIT_PRICE_LABEL'); ?></th>
                                        <td width="10%"></td>
                                </tr>
                        </thead>
                        <?php foreach ($bw_addons as $addon): ?>

                                <tbody>
                                        <tr>
                                                <td><span class="fa fa-line-chart"></span> <?php echo JText::sprintf('COM_NEXTGCYBER_PLAN_MAX_BWPERMONTH_LABEL', $addon->max_bwpermonth); ?></td>
                                                <td><p class="pull-right"><?php echo $addon->currency_symbol . ' ' . number_format($addon->monthly_price, 0); ?></p></td>
                                                <td>
                                                        <?php
                                                        $url = JRoute::_('index.php?option=com_nextgcyber&task=order.select&plan_id=' . $addon->id . '&return=' . base64_encode(JURI::getInstance()->toString()));
                                                        $text = '<span class="fa fa-shopping-cart"></span>';
                                                        $attr = [
                                                            'class' => 'btn btn-default btn-small hasTooltip',
                                                            'title' => JHtml::tooltipText(JText::_('COM_NEXTGCYBER_ADDON_BUY_BUTTON_LABEL'), JText::_('COM_NEXTGCYBER_ADDON_BUY_BUTTON_DESC')),
                                                        ];
                                                        echo JHtml::link($url, $text, $attr);
                                                        ?>
                                                </td>
                                        </tr>
                                </tbody>

                        <?php endforeach; ?>
                </table>
        </div>

</div>

<?php if (!empty($mixed_addons)): ?>
        <div class="row-fluid nextgcyber-mixed-addons">
                <div class="col-sm-12">
                        <table class="table table-hover">
                                <thead>
                                        <tr>
                                                <th><?php echo JText::_('MOD_NEXTGCYBER_ADDONS_FIELD_TITLE_LABLE'); ?></th>
                                                <th class="text-center"><?php echo JText::_('COM_NEXTGCYBER_PLAN_UNIT_PRICE_LABEL'); ?></th>
                                        </tr>
                                </thead>
                                <?php foreach ($mixed_addons as $addon): ?>

                                        <tbody>
                                                <tr>
                                                        <td><span class="fa fa-database"></span> <?php echo JText::sprintf('COM_NEXTGCYBER_PLAN_MAX_SPACESIZE_LABEL', $addon->max_spacesize); ?></td>
                                                        <td><p class="pull-right"><?php echo $addon->currency_symbol . ' ' . number_format($addon->monthly_price, 0); ?></p></td>
                                                        <td><a class="btn btn-default btn-small">Mua</a></td>
                                                </tr>
                                        </tbody>

                                <?php endforeach; ?>
                        </table>
                </div>
        </div>
<?php endif; ?>
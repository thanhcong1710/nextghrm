<?php
/**
 * @package pkg_nextgcyber NextgCyber for Joomla
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2015 NextgCyber. All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 */
defined('_JEXEC') or die;
NextgCyberHelperRoute::createJavascriptVar();
$subheading = $this->params->get('page_subheading', '');
$description = $this->params->get('plan_description', '');
$user = JFactory::getUser();
$isLogin = ($user->guest) ? false : true;
?>

<?php
if ($subheading) {
        echo $subheading;
}
?>

<div class="price-list" style="margin-top:45px;">
        <?php
        $span = 2;
        $totalSpan = 0;
        foreach ($this->stdplans as $stdplan):
                if ($totalSpan >= 12) {
                        echo '</div>';
                        $totalSpan = 0;
                }
                if ($totalSpan == 0) {
                        echo '<div class="row">';
                }
                $totalSpan += $span;
                $terms = '<div class="item-features"><ul class="list-unstyled">';
                foreach ($stdplan->terms as $term) {
                        if (empty($term)) {
                                continue;
                        }

                        $terms .= '<li>';
                        if (isset($term['icon']) && $term['icon']) {
                                $terms .= '<p><span class="' . $term['icon'] . '"></span>&nbsp;';
                        }
                        $terms .= $term['term']
                                . '</p></li>';
                }
                $terms .= '</ul></div>';
                $class = '';
                $btn_class = '';
                if ($stdplan->istrial) {
                        $class = ' trial';
                        $btn_class = 'btn btn-block btn-success btn-lg';
                } elseif ($stdplan->featured) {
                        $class = ' active';
                        $btn_class = 'btn btn-block btn-success btn-lg';
                } else {
                        $btn_class = 'btn btn-block btn-success btn-lg';
                }

                if (!$isLogin) {
                        $btn_class .= ' modal-button login-request';
                }
                ?>

                <div class="col-md-2<?php echo $class; ?>" itemscope itemtype="http://schema.org/Product">
                        <div class="item<?php echo $class; ?>">
                                <div class="item-information" itemscope itemtype="http://schema.org/Offer">
                                        <div class="item-name">
                                                <div class="price-label-bg">
                                                        <div class="price-left"></div>
                                                        <div class="price-main"><?php echo $stdplan->title; ?></div>
                                                        <div class="price-right"></div>
                                                </div>
                                        </div>
                                        <div class="item-row">
                                                <?php echo (is_null($stdplan->max_uaccounts)) ? JText::_('COM_NEXTGCYBER_PLAN_MAX_UACCOUNT_UNLIMIT_LABEL') : JText::sprintf('COM_NEXTGCYBER_PLAN_MAX_UACCOUNT_LABEL', $stdplan->max_uaccounts); ?>
                                        </div>
                                        <div class="item-row"><?php echo (is_null($stdplan->max_spacesize)) ? JText::_('COM_NEXTGCYBER_PLAN_MAX_SPACESIZE_UNLIMIT_LABEL') : JText::sprintf('COM_NEXTGCYBER_PLAN_MAX_SPACESIZE_LABEL', $stdplan->max_spacesize); ?></div>
                                        <div class="item-row">
                                                <?php
                                                if (is_null($stdplan->max_bwpermonth)) {
                                                        echo JText::_('COM_NEXTGCYBER_PLAN_MAX_BWPERMONTH_UNLIMIT_LABEL');
                                                } elseif ($stdplan->max_bwpermonth >= 1024) {
                                                        echo JText::sprintf('COM_NEXTGCYBER_PLAN_MAX_BWPERMONTH_IN_TB_LABEL', $stdplan->max_bwpermonth / 1024);
                                                } else {
                                                        echo JText::sprintf('COM_NEXTGCYBER_PLAN_MAX_BWPERMONTH_LABEL', $stdplan->max_bwpermonth);
                                                }
                                                ?>
                                        </div>

                                        <div class="item-row">
                                                <?php echo (is_null($stdplan->max_backup)) ? JText::_('COM_NEXTGCYBER_PLAN_MAX_BACKUP_UNLIMIT_LABEL') : ($stdplan->max_backup > 0 ? JText::plural('COM_NEXTGCYBER_PLAN_MAX_BACKUP_LABEL', $stdplan->max_backup) : JText::sprintf('COM_NEXTGCYBER_PLAN_DISALLOW_BACKUP_LABEL')); ?>
                                        </div>
                                        <div class="item-price" itemprop="price">
                                                <?php echo $stdplan->strikethrough_monthly_price ? JText::sprintf('COM_NEXTGCYBER_PLAN_STRIKETHROUGH_PRICE_TAG_LABEL', NextgCyberCurrencyHelper::format_currency($stdplan->strikethrough_monthly_price, $stdplan->currency_symbol, 0), $stdplan->currency_symbol) . '<br />' : ''; ?>
                                                <?php echo ((float) $stdplan->monthly_price > 0) ? JText::sprintf('COM_NEXTGCYBER_PLAN_PRICE_TAG_LABEL', NextgCyberCurrencyHelper::format_currency($stdplan->monthly_price, $stdplan->currency_symbol, 0, $stdplan->currency_code)) : JText::_('COM_NEXTGCYBER_PLAN_FREE_OF_CHARGE_LABEL'); ?>
                                        </div>
                                        <meta itemprop="priceCurrency" content="<?php echo $stdplan->currency_code; ?>" />
                                        <div class="item-button item-row">
                                                <?php
                                                $url = JRoute::_('index.php?option=com_nextgcyber&task=order.select&plan_id=' . $stdplan->id . '&return=' . base64_encode(JURI::getInstance()->toString()));
                                                $text = '<span class="fa fa-shopping-cart"></span> ' . (($stdplan->istrial == 1) ? JText::_('COM_NEXTGCYBER_PLAN_TRY_NOW_BUTTON_LABEL') : JText::_('COM_NEXTGCYBER_PLAN_BUY_NOW_BUTTON_LABEL'));
                                                ?>
                                                <a href="<?php echo $url; ?>" class="<?php echo $btn_class; ?>"><?php echo $text; ?></a>
                                        </div>
                                </div>
                                <?php echo $terms; ?>
                        </div>
                </div>
                <?php
        endforeach;
        if ($totalSpan > 0) {
                echo '</div>';
        }
        ?>
</div>
<?php
echo $description;
?>

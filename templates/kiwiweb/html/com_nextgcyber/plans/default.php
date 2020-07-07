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
if ($subheading) {
        echo $subheading;
}
$btn_class = ($isLogin) ? '' : ' modal-button login-request';
?>

<div class="planlist">
        <div class="row">
                <div class="col-md-10 col-md-offset-1">
                        <div class="row">
                                <?php
                                foreach ($this->stdplans as $stdplan):
                                        if ($stdplan->istrial) {
                                                continue;
                                        }

                                        $class = '';
                                        if ($stdplan->istrial) {
                                                $class = ' trial';
                                        } elseif ($stdplan->featured) {
                                                $class = ' active';
                                        } else {
                                                $class = '';
                                        }
                                        ?>
                                        <div class="col-md-4">
                                                <div class="plan-item<?php echo $class; ?>">
                                                        <div class="vitual-border">
                                                                <div class="plan-name">
                                                                        <h2><?php echo $stdplan->title; ?></h2>
                                                                        <div class="plan-sub-text"><?php echo $stdplan->teaser_text; ?></div>
                                                                        <?php if ($stdplan->featured): ?>
                                                                                <div class="plan-featured"><?php echo JText::_('TPL_KIWI_ERP_MOST_POPULAR_LABEL'); ?></div>
                                                                        <?php endif; ?>
                                                                </div>
                                                        </div>
                                                        <div class="plan-price">
                                                                <div class="sub-text"><?php echo JText::_('TPL_KIWI_ERP_AS_LOW_AS_LABEL'); ?></div>
                                                                <?php
                                                                $monthly_price = NextgCyberHelper::numberFormat($stdplan->monthly_price);
                                                                $price = explode('.', $monthly_price);
                                                                ?>
                                                                <span class="price-number">
                                                                        <span class="currency-symbol"><?php echo $stdplan->currency_symbol; ?></span>
                                                                        <span class="number"><?php echo $price[0]; ?></span>
                                                                </span>
                                                                <span class="price-decimal">
                                                                        <span class="decimal">.<?php echo $price[1]; ?></span>
                                                                        <span class="period">/<?php echo JText::_('TPL_KIWI_ERP_MONTH_LABEL'); ?></span>
                                                                </span>

                                                        </div>
                                                        <?php
                                                        $url = JRoute::_('index.php?option=com_nextgcyber&task=order.select&plan_id=' . $stdplan->id . '&return=' . base64_encode(JURI::getInstance()->toString()));
                                                        $text = '<span class="fa fa-shopping-cart"></span> ' . (($stdplan->istrial == 1) ? JText::_('COM_NEXTGCYBER_PLAN_TRY_NOW_BUTTON_LABEL') : JText::_('COM_NEXTGCYBER_PLAN_BUY_NOW_BUTTON_LABEL'));
                                                        ?>

                                                        <div class="plan-feature">
                                                                <a href="<?php echo $url; ?>" class="btn btn-info<?php echo $btn_class; ?>"><?php echo JText::_('TPL_KIWI_ERP_SIGN_UP_NOW_LABEL'); ?></a>
                                                        </div>
                                                        <div class="plan-feature">
                                                                <?php echo ($stdplan->allow_customdomain) ? JText::_('COM_NEXTGCYBER_PLAN_ALLOW_CUSTOMDOMAIN_LABEL') : JText::_('COM_NEXTGCYBER_PLAN_NOT_ALLOW_CUSTOMDOMAIN_LABEL'); ?>
                                                        </div>
                                                        <div class="plan-feature"><?php echo (is_null($stdplan->max_backup)) ? JText::_('COM_NEXTGCYBER_PLAN_MAX_BACKUP_UNLIMIT_LABEL') : ($stdplan->max_backup > 0 ? JText::plural('COM_NEXTGCYBER_PLAN_MAX_BACKUP_LABEL', $stdplan->max_backup) : JText::sprintf('COM_NEXTGCYBER_PLAN_DISALLOW_BACKUP_LABEL')); ?></div>
                                                        <div class="plan-feature"><?php echo (is_null($stdplan->max_uaccounts)) ? JText::_('COM_NEXTGCYBER_PLAN_MAX_UACCOUNT_UNLIMIT_LABEL') : JText::sprintf('COM_NEXTGCYBER_PLAN_MAX_UACCOUNT_LABEL', $stdplan->max_uaccounts); ?></div>
                                                        <div class="plan-feature"><?php echo (is_null($stdplan->max_spacesize)) ? JText::_('COM_NEXTGCYBER_PLAN_MAX_SPACESIZE_UNLIMIT_LABEL') : JText::sprintf('COM_NEXTGCYBER_PLAN_MAX_SPACESIZE_LABEL', $stdplan->max_spacesize); ?></div>
                                                        <div class="plan-feature"><?php
                                                                if (is_null($stdplan->max_bwpermonth)) {
                                                                        echo JText::_('COM_NEXTGCYBER_PLAN_MAX_BWPERMONTH_UNLIMIT_LABEL');
                                                                } elseif ($stdplan->max_bwpermonth >= 1024) {
                                                                        echo JText::sprintf('COM_NEXTGCYBER_PLAN_MAX_BWPERMONTH_IN_TB_LABEL', $stdplan->max_bwpermonth / 1024);
                                                                } else {
                                                                        echo JText::sprintf('COM_NEXTGCYBER_PLAN_MAX_BWPERMONTH_LABEL', $stdplan->max_bwpermonth);
                                                                }
                                                                ?>
                                                        </div>
                                                        <?php
                                                        foreach ($stdplan->terms as $term):
                                                                if (empty($term)) {
                                                                        continue;
                                                                }
                                                                echo '<div class="plan-feature">';
                                                                if (isset($term['icon']) && $term['icon']) {
                                                                        echo '<span class="' . $term['icon'] . '"></span>&nbsp;';
                                                                }
                                                                echo $term['term'];
                                                                echo '</div>';
                                                        endforeach;
                                                        ?>
                                                        <div class="plan-register plan-feature">
                                                                <a href="<?php echo $url; ?>" class="btn btn-info<?php echo $btn_class; ?>"><?php echo JText::_('TPL_KIWI_ERP_SIGN_UP_NOW_LABEL'); ?></a>
                                                        </div>
                                                </div>
                                        </div>
                                <?php endforeach; ?>

                        </div>
                </div>
        </div>
</div>
<?php
echo $description;
?>

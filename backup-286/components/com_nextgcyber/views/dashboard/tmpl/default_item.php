<?php
/**
 * @package pkg_nextgcyber NextgCyber for Joomla
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://domain.com
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;

if (empty($this->dashboardData->instances)) {
    $url = JRoute::_(NextgCyberHelperRoute::getPricingRoute());
    $trial = JRoute::_(NextgCyberHelperRoute::getTryingRoute());
    echo '<div class="row">'
    . '<div class="col-md-8">'
    . '<div class="alert alert-info">' . JText::sprintf('COM_NEXTGCYBER_DASHBOARD_REDIRECT_PRICING_DESC', $url) . '</div>'
    . '</div>'
    . '<div class="col-md-4">'
    . '<a href="' . $trial . '" class="btn btn-success btn-block">' . JText::_('COM_NEXTGCYBER_DASHBOARD_CREATE_TRIAL_BUTTON') . '</a>'
    . '</div>'
    . '</div>';
} else {
    $url = JRoute::_(NextgCyberHelperRoute::getPricingRoute());
    ?>
    <div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1 class="panel-title"><a href="<?php echo JRoute::_(NextgCyberHelperRoute::getInstancesRoute()); ?>"><?php echo JText::_('COM_NEXTGCYBER_DASHBOARD_INSTANCE_LIST_HEADER'); ?></a></h1>
                <a href="<?php echo $url; ?>" class="btn btn-default btn-sm nc-button-create-new-instance"><?php echo JText::_('COM_NEXTGCYBER_DASHBOARD_CREATE_NEW_INSTANCE_BUTTON'); ?></a>
            </div>
            <div class="panel-body">
                <?php echo JLayoutHelper::render('com_nextgcyber.instances.list', $this->dashboardData->instances, JPATH_COMPONENT, null); ?>
            </div>
        </div>
    </div>
<?php }
?>
<div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-title"><a href="<?php echo JRoute::_(NextgCyberHelperRoute::getOrdersRoute()); ?>"><?php echo JText::_('COM_NEXTGCYBER_DASHBOARD_ORDER_LIST_HEADER'); ?></a></h1>
        </div>
        <div class="panel-body">
            <?php
            if (!empty($this->dashboardData->orders)):
                echo JLayoutHelper::render('com_nextgcyber.orders.list', $this->dashboardData->orders, JPATH_COMPONENT, null);
            endif;
            ?>
        </div>
    </div>
</div>
<div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-title"><a href="<?php echo JRoute::_(NextgCyberHelperRoute::getInvoicesRoute()); ?>"><?php echo JText::_('COM_NEXTGCYBER_DASHBOARD_INVOICE_LIST_HEADER'); ?></a></h1>
        </div>
        <div class="panel-body">
            <?php
            if (!empty($this->dashboardData->invoices)):
                echo JLayoutHelper::render('com_nextgcyber.invoices.list', $this->dashboardData->invoices, JPATH_COMPONENT, null);
            endif;
            ?>
        </div>
    </div>
</div>
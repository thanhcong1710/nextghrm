<?php
/**
 * @package pkg_nextgcyber
 * @subpackage  com_nextgcyber
 *
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://domain.com
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;
JLoader::register('NextgCyberCurrencyHelper', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/currencyhelper.php');
NextgCyberSiteHelper::loadLibrary();
JHtml::script('com_nextgcyber/site/pricing.js', false, true, false);
$currency = NextgCyberCurrencyHelper::getCurrency();
$user_tz = NextgCyberCustomerHelper::getUserTimezone();
$instanceModel = JModelLegacy::getInstance('Instance', 'NextgCyberModel');
$view_more = $this->options->get('view_more', true);
$return = base64_encode(JRoute::_(JUri::getInstance()->toString()));
?>
<div class="nc-instance-detail">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div>
                        <label><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_STATUS_LABEL'); ?>: </label>
                        <span class="isRunning"></span>
                    </div>

                    <div>
                        <button class="btn btn-default nc-button nc-stop" data-id="<?php echo $displayData->id; ?>"
                                data-action="stop"
                                data-container="body"
                                data-toggle="popover"
                                data-placement="bottom"
                                data-content="<?php echo JText::_('COM_NEXTGCYBER_INSTANCE_STOP_BUTTON_DESC'); ?>"><span class="fa fa-stop"></span></button>
                        <button class="btn btn-default nc-button nc-start" data-id="<?php echo $displayData->id; ?>"
                                data-action="start"
                                data-container="body"
                                data-toggle="popover"
                                data-placement="bottom"
                                data-content="<?php echo JText::_('COM_NEXTGCYBER_INSTANCE_START_BUTTON_DESC'); ?>"><span class="fa fa-play"></span></button>
                        <button class="btn btn-default nc-button nc-restart" data-id="<?php echo $displayData->id; ?>"
                                data-action="restart"
                                data-container="body"
                                data-toggle="popover"
                                data-placement="bottom"
                                data-content="<?php echo JText::_('COM_NEXTGCYBER_INSTANCE_RESTART_BUTTON_DESC'); ?>"><span class="fa fa-repeat"></span></button>
                        <button class="btn btn-default nc-button disabled"
                                data-container="body"
                                data-toggle="popover"
                                data-placement="bottom"
                                data-content="<?php echo JText::_('COM_NEXTGCYBER_INSTANCE_BACKUP_BUTTON_DESC'); ?>"><span class="fa fa-database"></span></button>
                        <button class="btn btn-default nc-button hidden nc-auto" data-id="<?php echo $displayData->id; ?>" data-action="isRunning"><span class="fa fa-check-circle"></span></button>
                    </div>
                </div>
                <div class="col-md-3">
                    <?php if ($view_more): ?>
                        <a href="<?php echo JRoute::_(NextgCyberHelperRoute::getInstanceRoute($displayData->id)); ?>"
                           class="btn btn-default btn-block"><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_DETAIL_BUTTON_LABEL'); ?>
                        </a>
                    <?php endif; ?>
                    <a target="_blank" class="btn btn-block btn-default" href="http://<?php echo $displayData->fullsubdomain_name; ?>">
                        <?php echo JText::_('COM_NEXTGCYBER_INSTANCE_ACCESS_YOUR_SERVICE_BUTTON_LABEL'); ?>
                    </a>

                </div>
                <div class="col-md-3">
                    <button class="btn btn-default btn-block nc-button nc-validate" data-subtitle="<?php echo JText::_('COM_NEXTGCYBER_INSTANCE_REDEPLOY_SUBTITLE'); ?>" data-id="<?php echo $displayData->id; ?>" data-action="redeploy"><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_REDEPLOY_BUTTON'); ?></button>
                    <button class="btn btn-danger btn-block nc-button nc-validate" data-subtitle="<?php echo JText::_('COM_NEXTGCYBER_INSTANCE_REVOKE_SUBTITLE'); ?>" data-id="<?php echo $displayData->id; ?>" data-action="delete"><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_REVOKE_BUTTON'); ?></button>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title"><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_DETAIL_HEADER'); ?></h2>
        </div>
        <div class="panel-body">
            <?php if ($displayData->type == 'trial'): ?>
                <div class="alert alert-success">
                    <div class="row">
                        <div class="col-md-9 align-justify">
                            <?php echo JText::sprintf('COM_NEXTGCYBER_INSTANCE_TRIAL_INSTANCE_DESC', JHtml::_('date', $displayData->publish_down, JText::_('DATE_FORMAT_LC3'), $user_tz)); ?>
                            <?php echo JText::_('COM_NEXTGCYBER_INSTANCE_TRIAL_INSTANCE_DESC2'); ?>
                        </div>
                        <div class="col-md-3">
                            <?php if (empty($displayData->open_invoice)): ?>
                                <button class="btn btn-success btn-block nc-button nc-validate" data-id="<?php echo $displayData->id; ?>" data-return="<?php echo $return; ?>" data-action="getPriceList"><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_PAYNOW_BUTTON'); ?></button>
                            <?php else: ?>
                                <?php
                                $invoice_id = $displayData->open_invoice[0]->id;
                                $registerPaymentUrl = NextgCyberHelperRoute::getRegisterPaymentRoute($invoice_id);
                                ?>
                                <a href="<?php echo JRoute::_($registerPaymentUrl); ?>" class="btn btn-success btn-block"><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_PAYNOW_BUTTON'); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php elseif ($displayData->type == 'normal'): ?>
                <?php if (!empty($displayData->open_invoice)): ?>
                    <div class="alert alert-success">
                        <?php
                        $invoice_id = $displayData->open_invoice[0]->id;
                        $invoice_number = $displayData->open_invoice[0]->number;
                        $invoice_due_date = $displayData->open_invoice[0]->date_due;
                        ?>
                        <div class="row">
                            <div class="col-md-9 align-justify">
                                <?php echo JText::sprintf('COM_NEXTGCYBER_INSTANCE_OPEN_INVOICE_EXIST_DESC', JHtml::link(JRoute::_(NextgCyberHelperRoute::getInvoiceRoute($invoice_id)), $invoice_number), JHtml::_('date', $invoice_due_date, JText::_('DATE_FORMAT_LC3'), $user_tz)); ?>
                            </div>
                            <div class="col-md-3">
                                <?php
                                $registerPaymentUrl = NextgCyberHelperRoute::getRegisterPaymentRoute($invoice_id);
                                ?>
                                <a href="<?php echo JRoute::_($registerPaymentUrl); ?>" class="btn btn-success btn-block"><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_PAYNOW_BUTTON'); ?></a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-8">
                    <ul>
                        <li>
                            <?php echo JText::sprintf('COM_NEXTGCYBER_INSTANCE_DOMAIN', '<a href="http://' . $displayData->fullsubdomain_name . '" target="_blank">' . $displayData->fullsubdomain_name . '</a>'); ?>
                        </li>
                        <li><?php echo JText::sprintf('COM_NEXTGCYBER_INSTANCE_ODOO_VERSION', $displayData->odoo_version); ?></li>
                        <li><?php echo JText::sprintf('COM_NEXTGCYBER_INSTANCE_CREATE_DATE', JHtml::_('date', $displayData->create_date, JText::_('DATE_FORMAT_LC3'), $user_tz)); ?></li>
                        <?php if (!empty($displayData->publish_down)): ?>
                            <li><?php echo JText::sprintf('COM_NEXTGCYBER_INSTANCE_PUBLISH_DOWN', JHtml::_('date', $displayData->publish_down, JText::_('DATE_FORMAT_LC3'), $user_tz)); ?></li>
                        <?php endif; ?>


                        <?php
                        /*
                          if (!empty($displayData->orders)): ?>
                          <li><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_REFERENCE'); ?>:
                          <?php
                          foreach ($displayData->orders as $order) {
                          echo '<a href="' . NextgCyberHelperRoute::getOrderRoute($order->id) . '"><span class="label label-default">' . $order->name . '</span></a> ';
                          }
                          ?>
                          </li>
                          <?php endif;
                         */
                        ?>
                    </ul>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="panel-title"><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_CUSTOMDOMAIN_HEADER'); ?></h2>
                        </div>
                        <div class="panel-body">
                            <?php
                            if (!empty($displayData->custom_domains)):
                                echo '<ul>';
                                foreach ($displayData->custom_domains as $custom_domain) {
                                    echo '<li><a href="http://' . $custom_domain->name . '" target="_blank">' . $custom_domain->name . '</a></li>';
                                }
                                echo '</ul>';
                            endif;
                            ?>
                            <button class="btn btn-success nc-button" data-id="<?php echo $displayData->id; ?>" data-action="getCustomDomainForm">Add new domain</button>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="panel-title"><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_APPS_HEADER'); ?></h2>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <?php
                                foreach ($displayData->apps as $app):
                                    ?>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="row">
                                                    <div>
                                                        <?php if (!empty($app->content->link)): ?>
                                                            <a href="<?php echo $app->content->link; ?>">
                                                            <?php endif; ?>
                                                            <img src="data:image/png;base64,<?php echo $app->image; ?>" class="img-responsive">
                                                            <?php if (!empty($app->content->link)): ?>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="align-center">
                                                        <?php if (!empty($app->content->link)): ?>
                                                            <a href="<?php echo $app->content->link; ?>">
                                                            <?php endif; ?>
                                                            <?php echo $app->name; ?>
                                                            <?php if (!empty($app->content->link)): ?>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bell fa-fw"></i><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_NOTIFICATION_HEADER'); ?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group instance-info"><?php echo JLayoutHelper::render('com_nextgcyber.instance.notification', $displayData, JPATH_COMPONENT); ?></div>
                            <!-- /.list-group -->
                            <a class="btn btn-default btn-block nc-button" data-action="getNotification" data-id="<?php echo $displayData->id; ?>">Refresh</a>
                            <button class="btn btn-success btn-block nc-button nc-validate" data-return="<?php echo $return; ?>" data-id="<?php echo $displayData->id; ?>" data-action="getAddonsForm"><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_ADD_RESOURCE_BUTTON'); ?></button>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

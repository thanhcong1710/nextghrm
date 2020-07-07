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
$current_domain = $this->options->get('current_domain', 'nextgerp.com');
$domain = $this->options->get('domain', '');
$ssl_included = $this->options->get('ssl_included', true);
?>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingOne">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <span class="fa fa-plus"></span> <?php echo JText::_('COM_NEXTGCYBER_PRICING_USE_SUBDOMAIN_LABEL'); ?>
                </a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
                <ul>
                    <li><?php echo JText::_('COM_NEXTGCYBER_PRICING_DOMAIN_FREE_LABEL'); ?></li>
                    <li><?php echo JText::_('COM_NEXTGCYBER_PRICING_QUICK_ACCESS_LABEL'); ?></li>
                    <?php if ($ssl_included): ?>
                        <li><?php echo JText::_('COM_NEXTGCYBER_PRICING_SSL_INCLUDED_LABEL'); ?></li>
                    <?php endif; ?>
                </ul>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon"><span class="fa fa-lock"></span> <?php echo ($ssl_included) ? 'https' : 'http'; ?>://</div>
                        <input type="text" name="domain" class="form-control nc-input-subdomain" id="input_domain" placeholder="<?php echo JText::_('COM_NEXTGCYBER_PRICING_SUBDOMAIN_PLACEHOLDER'); ?>" data-toggle="popover" data-placement="top" title="<?php echo JText::_('COM_NEXTGCYBER_PRICING_USE_SUBDOMAIN_LABEL'); ?>" data-content="<?php echo JText::_('COM_NEXTGCYBER_PRICING_SUBDOMAIN_POPOVER_CONTENT'); ?>" value="<?php echo $domain; ?>">
                        <div class="input-group-addon">.<?php echo $current_domain; ?></div>
                        <div class="input-group-addon"><span class="fa fa-question-circle hasTooltip" data-placement="top" data-original-title="<?php echo JText::_('COM_NEXTGCYBER_PRICING_ODOO_SETTINGS_DESC'); ?>"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingTwo">
            <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    <span class="fa fa-plus"></span> <?php echo JText::_('COM_NEXTGCYBER_PRICING_USE_CUSTOMER_DOMAIN_LABEL'); ?>
                </a>
            </h4>
        </div>
        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
            <div class="panel-body">
                <p><?php echo JText::_('COM_NEXTGCYBER_PRICUNG_USE_CUSTOMER_DOMAIN_DESC'); ?></p>
                <p class="nc-input-domain-label align-center"></p>
                <a class="btn btn-success nc-button nc-addDomain" data-id="0" data-action="getCustomDomainForm"><?php echo JText::_('COM_NEXTGCYBER_PRICING_ADD_CUSTOMER_DOMAIN_BUTTON'); ?></a>
                <input type="hidden" name="domain_id" class="nc-input-domain_id"/>
            </div>
        </div>
    </div>
</div>
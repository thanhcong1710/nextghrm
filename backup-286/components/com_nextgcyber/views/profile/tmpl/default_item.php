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
$user_tz = NextgCyberCustomerHelper::getUserTimezone();
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title"><?php echo JText::_('COM_NEXTGCYBER_PROFILE'); ?></h1>
    </div>
    <div class="panel-body">
        <ul class="btn-toolbar pull-right">
            <li class="btn-group">
                <a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_nextgcyber&task=profile.edit'); ?>">
                    <span class="fa fa-user"></span> <?php echo JText::_('COM_NEXTGCYBER_EDIT_PROFILE'); ?>
                </a>
            </li>
        </ul>
        <fieldset id="customer-profile-core">
            <legend>
                <?php echo JText::_('COM_NEXTGCYBER_CUSTOMER_PROFILE_LEGEND'); ?>
            </legend>
            <div class="row">
                <div class="col-md-6">
                    <div>
                        <span class="fa fa-user"></span>&nbsp;<strong><?php echo JText::_('COM_NEXTGCYBER_PROFILE_NAME_LABEL'); ?>:&nbsp;</strong>
                        <?php echo $this->item->name ? $this->escape($this->item->name) : '&nbsp;'; ?>
                    </div>
                    <div>
                        <span class="fa fa-envelope"></span>&nbsp;<strong><?php echo JText::_('COM_NEXTGCYBER_PROFILE_EMAIL_LABEL'); ?>:&nbsp;</strong>
                        <?php echo $this->item->email ? $this->escape($this->item->email) : '&nbsp;'; ?>
                    </div>
                    <div>
                        <span class="fa fa-link"></span>&nbsp;<strong><?php echo JText::_('COM_NEXTGCYBER_PROFILE_VIBER_LABEL'); ?>:&nbsp;</strong>
                        <?php echo (!empty($this->item->nc_viber)) ? $this->escape($this->item->nc_viber) : '&nbsp;'; ?>
                    </div>
                    <div>
                        <span class="fa fa-skype"></span>&nbsp;<strong><?php echo JText::_('COM_NEXTGCYBER_PROFILE_SKYPE_LABEL'); ?>:&nbsp;</strong>
                        <?php echo (!empty($this->item->nc_skype)) ? $this->escape($this->item->nc_skype) : '&nbsp;'; ?>
                    </div>
                    <div>
                        <span class="fa fa-calendar"></span>&nbsp;<strong><?php echo JText::_('COM_NEXTGCYBER_PROFILE_CREATE_LABEL'); ?>:&nbsp;</strong>
                        <?php echo JHtml::_('date', $this->item->create_date, null, $user_tz); ?>
                    </div>

                </div>
                <div class="col-md-6">
                    <div>
                        <span class="fa fa-fax"></span>&nbsp;<strong><?php echo JText::_('COM_NEXTGCYBER_PROFILE_FAX_LABEL'); ?>:&nbsp;</strong>
                        <?php echo (!empty($this->item->fax)) ? $this->escape($this->item->fax) : '&nbsp;'; ?>
                    </div>
                    <div>
                        <span class="fa fa-mobile"></span>&nbsp;<strong><?php echo JText::_('COM_NEXTGCYBER_PROFILE_MOBILE_LABEL'); ?>:&nbsp;</strong>
                        <?php echo (!empty($this->item->mobile)) ? $this->escape($this->item->mobile) : '&nbsp;'; ?>
                    </div>
                    <div>
                        <span class="fa fa-phone"></span>&nbsp;<strong><?php echo JText::_('COM_NEXTGCYBER_PROFILE_PHONE_LABEL'); ?>:&nbsp;</strong>
                        <?php echo (!empty($this->item->phone)) ? $this->escape($this->item->phone) : '&nbsp;'; ?>
                    </div>

                    <div>
                        <span class="fa fa-home"></span>&nbsp;<strong><?php echo JText::_('COM_NEXTGCYBER_PROFILE_CONTACT_ADDRESS_LABEL'); ?>:&nbsp;</strong>
                        <?php echo (!empty($this->item->contact_address)) ? $this->escape($this->item->contact_address) : '&nbsp;'; ?>
                    </div>
                    <div>
                        <span class="fa fa-home"></span>&nbsp;<strong><?php echo JText::_('COM_NEXTGCYBER_PROFILE_ADDRESS_LABEL'); ?>:&nbsp;</strong>
                        <?php echo (!empty($this->item->street)) ? $this->escape($this->item->street) : '&nbsp;'; ?>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>
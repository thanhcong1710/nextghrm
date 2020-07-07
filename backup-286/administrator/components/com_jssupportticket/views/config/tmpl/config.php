<?php
/**
 * @Copyright Copyright (C) 2015 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	May 22, 2015
  ^
  + Project: 	JS Tickets
  ^
 */
defined('_JEXEC') or die('Restricted access');

global $mainframe;

$document = JFactory::getDocument();

if (JVERSION < 3) {
    JHtml::_('behavior.mootools');
    $document->addScript('components/com_jssupportticket/include/js/jquery.js');
} else {
    JHtml::_('behavior.framework');
    JHtml::_('jquery.framework');
}
$document->addScript('components/com_jssupportticket/include/js/jquery_idTabs.js');

$captchaselection = array(
    array('value' => '1', 'text' => JText::_('Google Recaptcha')),
    array('value' => '2', 'text' => JText::_('Own Captcha'))
);
$owncaptchaoparend = array(
    array('value' => '2', 'text' => JText::_('2')),
    array('value' => '3', 'text' => JText::_('3'))
);
$owncaptchatype = array(
    array('value' => '0', 'text' => JText::_('Any')),
    array('value' => '1', 'text' => JText::_('Addition')),
    array('value' => '2', 'text' => JText::_('Subtraction'))
);


$date_format = array(
    '0' => array('value' => 'd-m-Y', 'text' => JText::_('DD-MM-YYYY')),
    '1' => array('value' => 'm-d-Y', 'text' => JText::_('MM-DD-YYYY')),
    '2' => array('value' => 'Y-m-d', 'text' => JText::_('YYYY-MM-DD')),);

$yesno = array(
    '0' => array('value' => '1',
        'text' => JText::_('Yes')),
    '1' => array('value' => '0',
        'text' => JText::_('No')),);
$enableddisabled = array(
    '0' => array('value' => '1',
        'text' => JText::_('Enabled')),
    '1' => array('value' => '0',
        'text' => JText::_('Disabled')),);

$showhide = array(
    '0' => array('value' => '1',
        'text' => JText::_('Show')),
    '1' => array('value' => '0',
        'text' => JText::_('Hide')),);

$offline = JHTML::_('select.genericList', $yesno, 'offline', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['offline']);

$curlocation = JHTML::_('select.genericList', $yesno, 'cur_location', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cur_location']);

$date_format = JHTML::_('select.genericList', $date_format, 'date_format', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['date_format']);

$big_field_width = 40;
$med_field_width = 25;
$sml_field_width = 15;
?>
<div id="js-tk-admin-wrapper">
    <div id="js-tk-leftmenu">
        <?php include_once('components/com_jssupportticket/views/menu.php'); ?>
    </div>
    <div id="js-tk-cparea">
        <div id="js-tk-heading"><h4><img id="js-admin-responsive-menu-link" src="components/com_jssupportticket/include/images/c_p/left-icons/menu.png" /><?php echo JText::_('Configurations'); ?></h4></div> 
        <form action="index.php" method="POST" name="adminForm" id="adminForm">
            <div id="tabs_wrapper" class="tabs_wrapper js-col-lg-12 js-col-md-12">
                <div class="idTabs">
                    <span><a class="selected" href="#generalsetting"><?php echo JText::_('General Setting'); ?></a></span> 
                    <span><a  href="#ticketsetting"><?php echo JText::_('Ticket Setting'); ?></a></span> 
                    <span><a  href="#emialsetting"><?php echo JText::_('Default System Email'); ?></a></span> 
                    <span><a  href="#auotrespondersetting"><?php echo JText::_('Mail Setting'); ?></a></span> 
                    <span><a  href="#menusetting"><?php echo JText::_('Menu Setting'); ?></a></span> 
                </div>
                <div id="generalsetting">
                        <legend><?php echo JText::_('General Setting'); ?></legend>
                        <div class="js-row js-null-margin">
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('Title'); ?></div>
                                <div class="js-col-lg-8 js-col-md-8 js-config-value"><input type="text" name="title" value="<?php echo $this->configuration['title']; ?>" class="inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" /></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('Offline'); ?></div>
                                <div class="js-col-lg-8 js-col-md-8 js-config-value"><?php echo $offline; ?></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('Offline Message'); ?></div>
                                <div class="js-col-lg-8 js-col-md-8 js-config-value"><textarea name="offline_text" cols="25" rows="3" class="inputbox"><?php echo $this->configuration['offline_text']; ?></textarea> </div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('Data Directory'); ?></div>
                                <div class="js-col-lg-8 js-col-md-8 js-config-value"><input type="text" name="data_directory" value="<?php echo $this->configuration['data_directory']; ?>" class="inputbox" size="<?php echo $med_field_width; ?>"/> </div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('Date Format'); ?></div>
                                <div class="js-col-lg-8 js-col-md-8 js-config-value"><?php echo $date_format; ?></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('Control Panel Coulmn'); ?></div>
                                <div class="js-col-lg-4 js-col-md-4 js-config-value"><input type="text" name="controlpanel_column_count" value="<?php echo $this->configuration['controlpanel_column_count']; ?>" class="inputbox" size="<?php echo $med_field_width; ?>" />&nbsp;<?php echo JText::_('Columns'); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-4 js-col-md-4"><small><?php echo JText::_('Values Between 1 T0 12 Default Is 3'); ?></small></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('Ticket Overdue'); ?></div>
                                <div class="js-col-lg-4 js-col-md-4 js-config-value"><input type="text" name="ticket_overdue_indays" value="<?php echo $this->configuration['ticket_overdue_indays']; ?>" class="inputbox" size="<?php echo $med_field_width; ?>" />&nbsp;<?php echo JText::_('Days'); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-4 js-col-md-4"><small><?php echo JText::_('Set no. of days to mark ticket as overdue'); ?></small></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('Ticket auto close'); ?></div>
                                <div class="js-col-lg-4 js-col-md-4 js-config-value"><input type="text" name="ticket_auto_close_indays" value="<?php echo $this->configuration['ticket_auto_close_indays']; ?>" class="inputbox" size="<?php echo $med_field_width; ?>" />&nbsp;<?php echo JText::_('Days'); ?></div>
                                <div class="js-col-lg-4 js-col-md-4"><small><?php echo JText::_('Ticket auto close if user not respond within given days'); ?></small></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('No. of attachment'); ?></div>
                                <div class="js-col-lg-4 js-col-md-4 js-config-value"><input type="text" name="noofattachment" value="<?php echo $this->configuration['noofattachment']; ?>" class="inputbox" size="<?php echo $med_field_width; ?>" /></div>
                                <div class="js-col-lg-4 js-col-md-4"><br clear="all"/><small><?php echo JText::_('No. of attachment allowed at a time'); ?></small></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('File maximum size'); ?></div>
                                <div class="js-col-lg-8 js-col-md-8 js-config-value"><input type="text" name="filesize" value="<?php echo $this->configuration['filesize']; ?>" class="inputbox" size="<?php echo $med_field_width; ?>" /> &nbsp;KB</div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('File extension'); ?></div>
                                <div class="js-col-lg-4 js-col-md-4 js-config-value"><textarea name="fileextension" cols="25" rows="3" class="inputbox"><?php echo $this->configuration['fileextension']; ?></textarea></div>
                                <div class="js-col-lg-4 js-col-md-4"><small><?php echo JText::_('File extension allowed to attach') ?></small></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('Breadcrumbs'); ?></div>
                                <div class="js-col-lg-4 js-col-md-4 js-config-value"><?php echo $curlocation; ?></div>
                                <div class="js-col-lg-4 js-col-md-4 js-config-value"><small><?php echo JText::_('Show hide breadcrumbs'); ?></small></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('Show count on my tickets'); ?></div>
                                <div class="js-col-lg-8 js-col-md-8 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'show_count_tickets', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['show_count_tickets']); ?></div>
                            </div>
                        </div>
                </div>
                <div id="ticketsetting">
                        <legend><?php echo JText::_('Ticket Setting'); ?></legend>
                        <div class="js-row js-null-margin">
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('Maximum tickets'); ?></div>
                                <div class="js-col-lg-4 js-col-md-4 js-config-value"><input type="text" name="maximum_ticket" value="<?php echo $this->configuration['maximum_ticket']; ?>" class="inputbox" size="<?php echo $sml_field_width; ?>" /><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-4 js-col-md-4"><small><?php echo JText::_('Maximum ticket per user'); ?></small></div>
                            </div>                            
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('Maximum open tickets'); ?></div>
                                <div class="js-col-lg-4 js-col-md-4 js-config-value"><input type="text" name="ticket_per_email" value="<?php echo $this->configuration['ticket_per_email']; ?>" class="inputbox" size="<?php echo $sml_field_width; ?>" /><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-4 js-col-md-4"><small><?php echo JText::_('Maximum opened tickets per user'); ?></small></div>
                            </div>                            
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('Reopen ticket within days'); ?></div>
                                <div class="js-col-lg-4 js-col-md-4 js-config-value"><input type="text" name="ticket_reopen_within_days" value="<?php echo $this->configuration['ticket_reopen_within_days']; ?>" class="inputbox" size="<?php echo $sml_field_width; ?>" /></div>
                                <div class="js-col-lg-4 js-col-md-4"><small><?php echo JText::_('Ticket can be reopen within given number of days'); ?></small></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('Visitor can create ticket'); ?></div>
                                <div class="js-col-lg-4 js-col-md-4 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'visitor_can_create_ticket', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['visitor_can_create_ticket']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-4 js-col-md-4"><small><?php echo JText::_('Can visitor create ticket or not'); ?></small></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('Show captcha on visitor form ticket'); ?></div>
                                <div class="js-col-lg-4 js-col-md-4 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'show_captcha_visitor_form_ticket', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['show_captcha_visitor_form_ticket']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-4 js-col-md-4"><small><?php echo JText::_('Show captcha when visitor want to create ticket'); ?></small></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('Captcha selection'); ?></div>
                                <div class="js-col-lg-4 js-col-md-4 js-config-value"><?php echo JHTML::_('select.genericList', $captchaselection, 'captcha_selection', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['captcha_selection']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-4 js-col-md-4"><small><?php echo JText::_('Which captcha you want to add'); ?></small></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('Own captcha calculation type'); ?></div>
                                <div class="js-col-lg-4 js-col-md-4 js-config-value"><?php echo JHTML::_('select.genericList', $owncaptchatype, 'owncaptcha_calculationtype', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['owncaptcha_calculationtype']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-4 js-col-md-4"><small><?php echo JText::_('Select calculation type addition or subtraction'); ?></small></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('Own captcha operands'); ?></div>
                                <div class="js-col-lg-4 js-col-md-4 js-config-value"><?php echo JHTML::_('select.genericList', $owncaptchaoparend, 'owncaptcha_totaloperand', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['owncaptcha_totaloperand']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-4 js-col-md-4"><small><?php echo JText::_('Select the total operands to be given'); ?></small></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('Own captcha subtraction answer positive'); ?></div>
                                <div class="js-col-lg-4 js-col-md-4 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'owncaptcha_subtractionans', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['owncaptcha_subtractionans']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-4 js-col-md-4"><small><?php echo JText::_('Is subtraction answer should be positive'); ?></small></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('New ticket message'); ?></div>
                                <div class="js-col-lg-4 js-col-md-4 js-config-value">
                                    <?php 
                                        $editor = JFactory::getEditor(); echo $editor->display('new_ticket_message', $this->configuration['new_ticket_message'], '550', '300', '60', '20', false);
                                    ?>
                                    <span class="js-config-pro">*</span>
                                </div>
                                <div class="js-col-lg-4 js-col-md-4"><small><?php echo JText::_('This message will show on new ticket'); ?></small></div>
                            </div>
                        </div>
                </div>
                <div id="emialsetting">
                        <legend><?php echo JText::_('Default System Emails'); ?></legend>
                        <div class="js-row js-null-margin">
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('Default Alert Email'); ?></div>
                                <div class="js-col-lg-4 js-col-md-4 js-config-value"><?php echo JHTML::_('select.genericList', $this->lists['emails'], 'alert_email', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['alert_email']); ?>&nbsp;<a href="index.php?option=com_jssupportticket&c=email&layout=formemail"><?php echo JText::_('Add Email'); ?></a></div>
                                <div class="js-col-lg-4 js-col-md-4"><small><?php echo JText::_('If Ticket Department Email Is Not Selected Then This Email Is Used To Send Emails'); ?></small></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('Default admin email'); ?></div>
                                <div class="js-col-lg-4 js-col-md-4 js-config-value"><?php echo JHTML::_('select.genericList', $this->lists['emails'], 'admin_email', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['admin_email']); ?>&nbsp;<a href="index.php?option=com_jssupportticket&c=email&layout=formemail"><?php echo JText::_('Add Email'); ?></a></div>
                                <div class="js-col-lg-4 js-col-md-4"><small><?php echo JText::_('Admin Email Address To Receive Emails'); ?></small></div>
                            </div>
                        </div>
                </div>
                <div id="menusetting">
                        <legend><?php echo JText::_('Staff Members Control Panel Links'); ?></legend>
                        <div class="js-row js-null-margin">
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Open Ticket'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_openticket_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_openticket_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('My Tickets'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_myticket_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_myticket_staff']); ?><span class="js-config-pro">*</span></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Add Role'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_addrole_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_addrole_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Roles'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_roles_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_roles_staff']); ?><span class="js-config-pro">*</span></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Add Staff'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_addstaff_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_addstaff_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Staff'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_staff_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_staff_staff']); ?><span class="js-config-pro">*</span></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Add Department'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_adddepartment_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_adddepartment_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Department'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_department_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_department_staff']); ?><span class="js-config-pro">*</span></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Add Category'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_addcategory_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_addcategory_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Category'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_category_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_category_staff']); ?><span class="js-config-pro">*</span></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Add Knowledge Base'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_addkbarticle_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_addkbarticle_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Knowledge Base'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_kbarticle_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_kbarticle_staff']); ?><span class="js-config-pro">*</span></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Add Download'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_adddownload_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_adddownload_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Download'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_download_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_download_staff']); ?><span class="js-config-pro">*</span></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Add Announcement'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_addannouncement_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_addannouncement_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Announcement'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_announcement_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_announcement_staff']); ?><span class="js-config-pro">*</span></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Add FAQ'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_addfaq_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_addfaq_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('FAQs'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_faq_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_faq_staff']); ?><span class="js-config-pro">*</span></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Mail'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_mail_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_mail_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Profile'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_myprofile_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_myprofile_staff']); ?><span class="js-config-pro">*</span></div>
                            </div>
                        </div>
                        <legend><?php echo JText::_('Staff Members Top Menu Links'); ?></legend>
                        <div class="js-row js-null-margin">
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Home'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'tplink_home_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['tplink_home_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Tickets'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'tplink_tickets_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['tplink_tickets_staff']); ?><span class="js-config-pro">*</span></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Knowledge Base'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'tplink_knowledgebase_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['tplink_knowledgebase_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Announcement'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'tplink_announcements_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['tplink_announcements_staff']); ?><span class="js-config-pro">*</span></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Download'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'tplink_downloads_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['tplink_downloads_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('FAQs'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'tplink_faqs_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['tplink_faqs_staff']); ?><span class="js-config-pro">*</span></div>
                            </div>
                        </div>
                        <legend><?php echo JText::_('User Control Panel Links'); ?></legend>
                        <div class="js-row js-null-margin">
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Open Ticket'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_openticket_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_openticket_user']); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('My Tickets'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_myticket_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_myticket_user']); ?></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Check Ticket Status'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_checkticketstatus_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_checkticketstatus_user']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Download'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_downloads_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_downloads_user']); ?><span class="js-config-pro">*</span></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Announcement'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_announcements_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_announcements_user']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('FAQs'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_faqs_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_faqs_user']); ?><span class="js-config-pro">*</span></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Knowledge Base'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'cplink_knowledgebase_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['cplink_knowledgebase_user']); ?><span class="js-config-pro">*</span></div>
                            </div>
                        </div>
                        <legend><?php echo JText::_('User Top Menu Links'); ?></legend>
                        <div class="js-row js-null-margin">
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Home'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'tplink_home_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['tplink_home_user']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Tickets'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'tplink_tickets_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['tplink_tickets_user']); ?><span class="js-config-pro">*</span></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Knowledge Base'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'tplink_knowledgebase_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['tplink_knowledgebase_user']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Announcement'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'tplink_announcements_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['tplink_announcements_user']); ?><span class="js-config-pro">*</span></div>
                            </div>
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Download'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'tplink_downloads_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['tplink_downloads_user']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('FAQs'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3 js-config-value"><?php echo JHTML::_('select.genericList', $yesno, 'tplink_faqs_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['tplink_faqs_user']); ?><span class="js-config-pro">*</span></div>
                            </div>
                        </div>
                </div>
                <div id="auotrespondersetting">
                        <legend><?php echo JText::_('Ban email New Ticket'); ?></legend>
                        <div class="js-row js-null-margin">
                            <div class="js-row js-null-margin">
                                <div class="js-col-lg-4 js-col-md-4 js-config-title"><?php echo JText::_('Mail to admin'); ?></div>
                                <div class="js-col-lg-4 js-col-md-4 js-config-value"><?php echo JHTML::_('select.genericList', $enableddisabled, 'banemail_new_ticket_admin', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['banemail_new_ticket_admin']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-4 js-col-md-4"><small><?php echo JText::_('Email send to admin when banned email try to create ticket'); ?></small></div>
                            </div>
                        </div>
                        <legend><?php echo JText::_('Ticket Operations Mail Setting'); ?></legend>
                        <div class="js-row js-null-margin">
                            <div class="js-row js-null-margin bgandfontcolor">
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JText::_('Ticket Operations Mail Setting'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JText::_('Admin'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JText::_('Staff'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JText::_('User'); ?></div>
                            </div>
                            <div class="js-row">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('New Ticket'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'new_ticket_admin', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['new_ticket_admin']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'new_ticket_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['new_ticket_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3"></div>
                            </div>
                            <div class="js-row">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Ticket reassign'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_reassign_admin', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_reassign_admin']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_reassign_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_reassign_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_reassign_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_reassign_user']); ?><span class="js-config-pro">*</span></div>
                            </div>
                            <div class="js-row">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Ticket close'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_close_admin', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_close_admin']); ?></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_close_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_close_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_close_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_close_user']); ?></div>
                            </div>
                            <div class="js-row">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Ticket delete'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_delete_admin', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_delete_admin']); ?></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_delete_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_delete_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_delete_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_delete_user']); ?></div>
                            </div>
                            <div class="js-row">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Ticket mark overdue'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_overdue_admin', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_overdue_admin']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_overdue_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_overdue_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_overdue_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_overdue_user']); ?><span class="js-config-pro">*</span></div>
                            </div>
                            <div class="js-row">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Ticket ban email'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_ban_email_admin', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_ban_email_admin']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_ban_email_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_ban_email_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_ban_email_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_ban_email_user']); ?><span class="js-config-pro">*</span></div>
                            </div>
                            <div class="js-row">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Ticket Department Transfer'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_department_transfer_admin', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_department_transfer_admin']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_department_transfer_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_department_transfer_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_department_transfer_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_department_transfer_user']); ?><span class="js-config-pro">*</span></div>
                            </div>
                            <div class="js-row">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Ticket Reply User'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_reply_user_admin', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_reply_user_admin']); ?></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_reply_user_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_reply_user_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_reply_user_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_reply_user_user']); ?></div>
                            </div>
                            <div class="js-row">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Ticket Response Staff'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_response_staff_admin', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_response_staff_admin']); ?></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_response_staff_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_response_staff_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_response_staff_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_response_staff_user']); ?></div>
                            </div>
                            <div class="js-row">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Ticket ban email and close ticket'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_ban_and_close_admin', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_ban_and_close_admin']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_ban_and_close_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_ban_and_close_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_ban_and_close_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_ban_and_close_user']); ?><span class="js-config-pro">*</span></div>
                            </div>
                            <div class="js-row">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Ticket unban email'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_unbanemail_admin', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_unbanemail_admin']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_unbanemail_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_unbanemail_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_unbanemail_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_unbanemail_user']); ?><span class="js-config-pro">*</span></div>
                            </div>
                            <div class="js-row">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Ticket Lock'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_lock_admin', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_lock_admin']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_lock_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_lock_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_lock_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_lock_user']); ?><span class="js-config-pro">*</span></div>
                            </div>
                            <div class="js-row">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Ticket Unlock'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_unlock_admin', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_unlock_admin']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_unlock_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_unlock_staff']); ?><span class="js-config-pro">*</span></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_unlock_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_unlock_user']); ?><span class="js-config-pro">*</span></div>
                            </div>
                            <div class="js-col-md-12">
                                <div class="js-col-lg-3 js-col-md-3 js-config-title"><?php echo JText::_('Ticket Change Priority'); ?></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_priority_admin', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_priority_admin']); ?></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_priority_staff', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_priority_staff']); ?></div>
                                <div class="js-col-lg-3 js-col-md-3"><?php echo JHTML::_('select.genericList', $enableddisabled, 'ticket_priority_user', 'class="inputbox" ' . '', 'value', 'text', $this->configuration['ticket_priority_user']); ?></div>
                            </div>
                        </div>
                </div>
            </div>   
            <input type="hidden" name="task" value="saveconf" />
            <input type="hidden" name="c" value="config" />
            <input type="hidden" name="layout" value="config" />
            <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
        </form>
    </div>
    <div class="js-config-pro-version-text"><?php echo JText::_('* Pro version only'); ?></div>
</div>
<div id="js-tk-copyright">
    <img width="85" src="http://www.joomsky.com/logo/jssupportticket_logo_small.png">&nbsp;Powered by <a target="_blank" href="http://www.joomsky.com">Joom Sky</a><br/>
    &copy;Copyright 2008 - <?php echo date('Y'); ?>, <a target="_blank" href="http://www.burujsolutions.com">Buruj Solutions</a>
</div>

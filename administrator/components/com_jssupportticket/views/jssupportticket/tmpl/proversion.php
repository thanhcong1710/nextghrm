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
?>
<div id="js-tk-admin-wrapper">
    <div id="js-tk-leftmenu">
        <?php include_once('components/com_jssupportticket/views/menu.php'); ?>
    </div>
    <div id="js-tk-cparea" class="js-col-md-12">
        <div id="js-tk-heading">
            <h4><img id="js-admin-responsive-menu-link" src="components/com_jssupportticket/include/images/c_p/left-icons/menu.png" /><?php echo JText::_('Pro Version'); ?></h4>
        </div>
        <div id="js-maincp-area">
        <div class="js-admin-propage">
            <div class="js-col-md-7"><img src="components/com_jssupportticket/include/images/pro_page/pro_led.png"></div>
            <div class="js-col-md-5">
                <span class="js-pro-title"><?php echo JText::_('Support Ticket Pro'); ?></span>
                <span class="js-pro-description"><?php echo JText::_('Feature available in pro version only'); ?></span>
                <a target="_blank" href="<?php echo 'http://www.joomsky.com/index.php/products/js-support-ticket-1/js-supprot-ticket-pro-joomla'; ?>" id="js-pro-link"></a>
            </div>
        </div>
        <span class="js-admin-title"><?php echo JText::_('JS Support Ticket pro feature');?></span>
        <div class="js-row js-pro-feature-wrapper">
            <div class="js-col-md-6 js-col-xs-12 js-pro-feature">
                <img src="components/com_jssupportticket/include/images/pro_page/theme.png" class="js-theme"/>
                <span class="js-pro-feature-title"><?php echo JText::_('Themes'); ?></span>
                <span class="js-pro-feature-description"><?php echo JText::_('Unlimited color tools to desire result'); ?></span>
            </div>
            <div class="js-col-md-6 js-col-xs-12 js-pro-feature">
                <img src="components/com_jssupportticket/include/images/pro_page/internalmail.png" class="js-internalmail"/>
                <span class="js-pro-feature-title"><?php echo JText::_('Internal Mail'); ?></span>
                <span class="js-pro-feature-description"><?php echo JText::_("Internal mail system for communication withing staffs and administrator"); ?></span>
            </div>
            <div class="js-col-md-6 js-col-xs-12 js-pro-feature">
                <img src="components/com_jssupportticket/include/images/pro_page/acl.png" class="js-acl"/>
                <span class="js-pro-feature-title"><?php echo JText::_('Access control level'); ?></span>
                <span class="js-pro-feature-description"><?php echo JText::_('Access control limit you can limit your staff to do specific task'); ?></span>
            </div>
            <div class="js-col-md-6 js-col-xs-12 js-pro-feature">
                <img src="components/com_jssupportticket/include/images/pro_page/premade_message.png" class="js-premade" />
                <span class="js-pro-feature-title"><?php echo JText::_('Premade messages'); ?></span>
                <span class="js-pro-feature-description"><?php echo JText::_('Staff member create premade message premade available in ticket reply'); ?></span>
            </div>
            <div class="js-col-md-6 js-col-xs-12 js-pro-feature">
                <img src="components/com_jssupportticket/include/images/pro_page/staff.png" class="js-staff"/>
                <span class="js-pro-feature-title"><?php echo JText::_('Staff members'); ?></span>
                <span class="js-pro-feature-description"><?php echo JText::_('Staff member can be created and reply your customer tickets'); ?></span>
            </div>
            <div class="js-col-md-6 js-col-xs-12 js-pro-feature">
                <img src="components/com_jssupportticket/include/images/pro_page/helptopic.png" class="js-helptopic"/>
                <span class="js-pro-feature-title"><?php echo JText::_('Help Topic'); ?></span>
                <span class="js-pro-feature-description"><?php echo JText::_('Help topic can be add with respect to departments'); ?></span>
            </div>
            <div class="js-col-md-6 js-col-xs-12 js-pro-feature">
                <img src="components/com_jssupportticket/include/images/pro_page/knowledgebase.png" class="js-knowledgebase"/>
                <span class="js-pro-feature-title"><?php echo JText::_('Knwoledge Base'); ?></span>
                <span class="js-pro-feature-description"><?php echo JText::_('You can create and maintin knwoledge Base for your ticket system'); ?></span>
            </div>
            <div class="js-col-md-6 js-col-xs-12 js-pro-feature">
                <img src="components/com_jssupportticket/include/images/pro_page/downloads.png" class="js-downloads"/>
                <span class="js-pro-feature-title"><?php echo JText::_('Downloads'); ?></span>
                <span class="js-pro-feature-description"><?php echo JText::_('You can add downloads for your customers'); ?></span>
            </div>
            <div class="js-col-md-6 js-col-xs-12 js-pro-feature">
                <img src="components/com_jssupportticket/include/images/pro_page/announcements.png" class="js-announcements"/>
                <span class="js-pro-feature-title"><?php echo JText::_('Announcements'); ?></span>
                <span class="js-pro-feature-description"><?php echo JText::_('Add announcements for your support system'); ?></span>
            </div>
            <div class="js-col-md-6 js-col-xs-12 js-pro-feature">
                <img src="components/com_jssupportticket/include/images/pro_page/faqs.png" class="js-faqs"/>
                <span class="js-pro-feature-title"><?php echo JText::_("FAQs"); ?></span>
                <span class="js-pro-feature-description"><?php echo JText::_("You can Manager FAQs"); ?></span>
            </div>
            <div class="js-col-md-6 js-col-xs-12 js-pro-feature">
                <img src="components/com_jssupportticket/include/images/pro_page/banned_email.png" class="js-banned_email"/>
                <span class="js-pro-feature-title"><?php echo JText::_('Banned emails'); ?></span>
                <span class="js-pro-feature-description"><?php echo JText::_('You can ban and unabn any spammy email address'); ?></span>
            </div>
            <div class="js-col-md-6 js-col-xs-12 js-pro-feature">
                <img src="components/com_jssupportticket/include/images/pro_page/overdue.png" class="js-overdue"/>
                <span class="js-pro-feature-title"><?php echo JText::_('Ticket overdue'); ?></span>
                <span class="js-pro-feature-description"><?php echo JText::_('If and only if customer not reply certain days ticket marked overdue'); ?></span>
            </div>
            <div class="js-col-md-6 js-col-xs-12 js-pro-feature">
                <img src="components/com_jssupportticket/include/images/pro_page/via_email.png" class="js-viaemail"/>
                <span class="js-pro-feature-title"><?php echo JText::_('Ticket via email'); ?></span>
                <span class="js-pro-feature-description"><?php echo JText::_('System will read your email and create tickets'); ?></span>
            </div>
            <div class="js-col-md-6 js-col-xs-12 js-pro-feature">
                <img src="components/com_jssupportticket/include/images/pro_page/visitorticketopen.png" class="js-visitorticketopen"/>
                <span class="js-pro-feature-title"><?php echo JText::_('Visitor ticket open'); ?></span>
                <span class="js-pro-feature-description"><?php echo JText::_('Visitor can also open ticket in you support system'); ?></span>
            </div>
            <div class="js-col-md-6 js-col-xs-12 js-pro-feature">
                <img src="components/com_jssupportticket/include/images/pro_page/lockticket.png" class="js-lockticket"/>
                <span class="js-pro-feature-title"><?php echo JText::_('Lock Ticket'); ?></span>
                <span class="js-pro-feature-description"><?php echo JText::_('You can now lock or unlock any ticket for certain time'); ?></span>
            </div>
            <div class="js-col-md-6 js-col-xs-12 js-pro-feature">
                <img src="components/com_jssupportticket/include/images/pro_page/internalnote.png" class="js-internalnote"/>
                <span class="js-pro-feature-title"><?php echo JText::_('Internal Notes'); ?></span>
                <span class="js-pro-feature-description"><?php echo JText::_('Internal notes for staff members and administrator ticket based'); ?></span>
            </div>
            <div class="js-col-md-6 js-col-xs-12 js-pro-feature">
                <img src="components/com_jssupportticket/include/images/pro_page/stafftransfer.png" class="js-stafftransfer"/>
                <span class="js-pro-feature-title"><?php echo JText::_('Assign to Staff'); ?></span>
                <span class="js-pro-feature-description"><?php echo JText::_('Ticket can be transferred to any other staff member'); ?></span>
            </div>
            <div class="js-col-md-6 js-col-xs-12 js-pro-feature">
                <img src="components/com_jssupportticket/include/images/pro_page/departmenttransfer.png" class="js-departmenttransfer"/>
                <span class="js-pro-feature-title"><?php echo JText::_('Department Transfer'); ?></span>
                <span class="js-pro-feature-description"><?php echo JText::_('Ticket can also be transferred to any other department'); ?></span>
            </div>
            <div class="js-col-md-6 js-col-xs-12 js-pro-feature">
                <img src="components/com_jssupportticket/include/images/pro_page/activity_log.png" class="js-activitylog"/>
                <span class="js-pro-feature-title"><?php echo JText::_('Activity Log'); ?></span>
                <span class="js-pro-feature-description"><?php echo JText::_('Ticket action history with time and user name'); ?></span>
            </div>
        </div>
        </div>
    </div>
</div>
<div id="js-tk-copyright">
    <img width="85" src="http://www.joomsky.com/logo/jssupportticket_logo_small.png">&nbsp;Powered by <a target="_blank" href="http://www.joomsky.com">Joom Sky</a><br/>
    &copy;Copyright 2008 - <?php echo date('Y'); ?>, <a target="_blank" href="http://www.burujsolutions.com">Buruj Solutions</a>
</div>

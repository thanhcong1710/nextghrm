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

<script language="javascript">
    // for joomla 1.6
    Joomla.submitbutton = function (task) {
        if (task == '') {
            return false;
        } else {
            if (task == 'save') {
                returnvalue = validate_form(document.adminForm);
            } else
                returnvalue = true;
            if (returnvalue) {
                Joomla.submitform(task);
                return true;
            } else
                return false;
        }
    }
    function validate_form(f) {
        if (document.formvalidator.isValid(f)) {
            f.check.value = '<?php if ((JVERSION == '1.5') || (JVERSION == '2.5')) echo JUtility::getToken(); else echo JSession::getFormToken(); ?>';//send token
        }
        else {
            alert('<?php echo JText::_('Some values are not acceptable please retry'); ?>');
            return false;
        }
        return true;
    }
</script>
<div id="js-tk-admin-wrapper">
    <div id="js-tk-leftmenu">
        <?php include_once('components/com_jssupportticket/views/menu.php'); ?>
    </div>
    <div id="js-tk-cparea">
        <div id="js-tk-heading"> <h4><img id="js-admin-responsive-menu-link" src="components/com_jssupportticket/include/images/c_p/left-icons/menu.png" /><?php echo JText::_('Email Templates'); ?></h4> </div>
        <div class="js-col-md-12">
        <form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" >
            <div class="js-email-menu">
                <?php $link = 'index.php?option='.$this->option.'&c=emailtemplate&layout=emailtemplate&tf='; ?>
                <span class="js-email-menu-link <?php if ($this->templatefor == 'ew-tk') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo $link; ?>ew-tk"><?php echo JText::_('New Ticket'); ?></a></span>
                <span class="js-email-menu-link <?php if ($this->templatefor == 'sntk-tk') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo $link; ?>sntk-tk"><?php echo JText::_('Staff Ticket'); ?><span class="js-config-pro">*</span></a></span>
                <span class="js-email-menu-link <?php if ($this->templatefor == 'ew-md') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo $link; ?>ew-md"><?php echo JText::_('New Department'); ?></a></span>
                <span class="js-email-menu-link <?php if ($this->templatefor == 'ew-sm') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo $link; ?>ew-sm"><?php echo JText::_('New Staff'); ?><span class="js-config-pro">*</span></a></span>
                <span class="js-email-menu-link <?php if ($this->templatefor == 'ew-ht') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo $link; ?>ew-ht"><?php echo JText::_('New Help Topic'); ?><span class="js-config-pro">*</span></a></span>
                <span class="js-email-menu-link <?php if ($this->templatefor == 'rs-tk') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo $link; ?>rs-tk"><?php echo JText::_('Reassign Ticket'); ?><span class="js-config-pro">*</span></a></span>
                <span class="js-email-menu-link <?php if ($this->templatefor == 'cl-tk') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo $link; ?>cl-tk"><?php echo JText::_('Close Ticket'); ?></a></span>
                <span class="js-email-menu-link <?php if ($this->templatefor == 'dl-tk') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo $link; ?>dl-tk"><?php echo JText::_('Delete Ticket'); ?></a></span>
                <span class="js-email-menu-link <?php if ($this->templatefor == 'mo-tk') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo $link; ?>mo-tk"><?php echo JText::_('Mark Overdue'); ?><span class="js-config-pro">*</span></a></span>
                <span class="js-email-menu-link <?php if ($this->templatefor == 'be-tk') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo $link; ?>be-tk"><?php echo JText::_('Ban email'); ?><span class="js-config-pro">*</span></a></span>
                <span class="js-email-menu-link <?php if ($this->templatefor == 'be-trtk') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo $link; ?>be-trtk"><?php echo JText::_('Ban email try to create ticket'); ?><span class="js-config-pro">*</span></a></span>
                <span class="js-email-menu-link <?php if ($this->templatefor == 'dt-tk') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo $link; ?>dt-tk"><?php echo JText::_('Department transfer'); ?><span class="js-config-pro">*</span></a></span>
                <span class="js-email-menu-link <?php if ($this->templatefor == 'ebct-tk') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo $link; ?>ebct-tk"><?php echo JText::_('Ban Email And Close Ticket'); ?><span class="js-config-pro">*</span></a></span>
                <span class="js-email-menu-link <?php if ($this->templatefor == 'ube-tk') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo $link; ?>ube-tk"><?php echo JText::_('Unban Email'); ?><span class="js-config-pro">*</span></a></span>
                <span class="js-email-menu-link <?php if ($this->templatefor == 'rsp-tk') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo $link; ?>rsp-tk"><?php echo JText::_('Response Ticket'); ?></a></span>
                <span class="js-email-menu-link <?php if ($this->templatefor == 'rpy-tk') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo $link; ?>rpy-tk"><?php echo JText::_('Reply Ticket'); ?></a></span>
                <span class="js-email-menu-link <?php if ($this->templatefor == 'tk-ew-ad') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo $link; ?>tk-ew-ad"><?php echo JText::_('New Ticket Admin Alert'); ?></a></span>
                <span class="js-email-menu-link <?php if ($this->templatefor == 'lk-tk') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo $link; ?>lk-tk"><?php echo JText::_('Lock Ticket'); ?><span class="js-config-pro">*</span></a></span>
                <span class="js-email-menu-link <?php if ($this->templatefor == 'ulk-tk') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo $link; ?>ulk-tk"><?php echo JText::_('Unlock ticket'); ?><span class="js-config-pro">*</span></a></span>
                <span class="js-email-menu-link <?php if ($this->templatefor == 'minp-tk') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo $link; ?>minp-tk"><?php echo JText::_('In progress ticket'); ?><span class="js-config-pro">*</span></a></span>
                <span class="js-email-menu-link <?php if ($this->templatefor == 'pc-tk') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo $link; ?>pc-tk"><?php echo JText::_('Ticket priority is changed by'); ?><span class="js-config-pro">*</span></a></span>
                <span class="js-email-menu-link <?php if ($this->templatefor == 'ml-ew') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo $link; ?>ml-ew"><?php echo JText::_('New Mail Receviced'); ?><span class="js-config-pro">*</span></a></span>
                <span class="js-email-menu-link <?php if ($this->templatefor == 'ml-rp') echo 'selected'; ?>"><a class="js-email-link" href="<?php echo $link; ?>ml-rp"><?php echo JText::_('New Mail Message Recevied'); ?><span class="js-config-pro">*</span></a></span>
            </div>
            <!-- copid  -->
            <div class="js-email-body">
                <div class="js-form-wrapper">
                    <div class="a-js-form-title"><?php echo JText::_('Subject'); ?></div>
                    <div class="a-js-form-field"><input class="inputbox required" type="text" name="subject" id="subject" size="135" maxlength="255" value="<?php if (isset($this->template)) echo $this->template->subject; ?>" /></div>
                </div>
                <div class="js-form-wrapper">
                    <div class="a-js-form-title"><?php echo JText::_('Body'); ?></div>
                    <div class="a-js-form-field"><?php $editor = JFactory::getEditor(); if (isset($this->template)) echo $editor->display('body', $this->template->body, '550', '300', '60', '20', false); else echo $editor->display('body', '', '550', '300', '60', '20', false); ?></div>
                </div>
                <div class="js-email-parameters">
                    <span class="js-email-parameter-heading"><?php echo JText::_('Parameters') ?></span>
                    <?php
                    if ($this->templatefor == 'ew-tk') {
                        ?>
                        <span class="js-email-paramater">{USERNAME} : <?php echo JText::_('Username'); ?></span>
                        <span class="js-email-paramater">{SUBJECT} : <?php echo JText::_('Subject'); ?></span>
                        <span class="js-email-paramater">{TRACKINGID} : <?php echo JText::_('Tracking ID'); ?></span>
                        <span class="js-email-paramater">{HELP_TOPIC} : <?php echo JText::_('Help Topic'); ?></span>
                        <span class="js-email-paramater">{EMAIL} : <?php echo JText::_('Email'); ?></span>
                        <span class="js-email-paramater">{MESSAGE} : <?php echo JText::_('Message'); ?></span>
                        <span class="js-email-paramater">{TICKETURL} : <?php echo JText::_('Ticket URL'); ?></span>
                        <?php
                    }elseif ($this->templatefor == 'sntk-tk') {
                        ?>
                        <span class="js-email-paramater">{USERNAME} : <?php echo JText::_('Username'); ?></span>
                        <span class="js-email-paramater">{SUBJECT} : <?php echo JText::_('Subject'); ?></span>
                        <span class="js-email-paramater">{TRACKINGID} : <?php echo JText::_('Tracking ID'); ?></span>
                        <span class="js-email-paramater">{HELP_TOPIC} : <?php echo JText::_('Help Topic'); ?></span>
                        <span class="js-email-paramater">{EMAIL} : <?php echo JText::_('Email'); ?></span>
                        <span class="js-email-paramater">{MESSAGE} : <?php echo JText::_('Message'); ?></span>
                        <span class="js-email-paramater">{TICKETURL} : <?php echo JText::_('Ticket URL'); ?></span>
                        <?php
                    } elseif ($this->templatefor == 'ew-md') {
                        ?>
                        <span class="js-email-paramater">{DEPARTMENT_TITLE} : <?php echo JText::_('Department title'); ?></span>
                        <?php
                    } elseif ($this->templatefor == 'ew-gr') {
                        ?>
                        <span class="js-email-paramater">{GROUP_TITLE} : <?php echo JText::_('Group Title'); ?></span>
                        <?php
                    } elseif ($this->templatefor == 'ew-sm') {
                        ?>
                        <span class="js-email-paramater">{STAFF_MEMBER_NAME} : <?php echo JText::_('Staff member name'); ?></span>
                        <?php
                    } elseif ($this->templatefor == 'ew-ht') {
                        ?>
                        <span class="js-email-paramater">{HELPTOPIC_TITLE} : <?php echo JText::_('Help topic title'); ?></span>
                        <span class="js-email-paramater">{DEPARTMENT_TITLE} : <?php echo JText::_('Department title'); ?></span>
                        <?php
                    } elseif ($this->templatefor == 'rs-tk') {
                        ?>
                        <span class="js-email-paramater">{SUBJECT} : <?php echo JText::_('Subject'); ?></span>
                        <span class="js-email-paramater">{TRACKINGID} : <?php echo JText::_('Tracking ID'); ?></span>
                        <span class="js-email-paramater">{STAFF_MEMBER_NAME} : <?php echo JText::_('Staff member name'); ?></span>
                        <span class="js-email-paramater">{TICKETURL} : <?php echo JText::_('Ticket URL'); ?></span>
                        <?php
                    } elseif ($this->templatefor == 'cl-tk') {
                        ?>
                        <span class="js-email-paramater">{SUBJECT} : <?php echo JText::_('Subject'); ?></span>
                        <span class="js-email-paramater">{TRACKINGID} : <?php echo JText::_('Tracking ID'); ?></span>
                        <span class="js-email-paramater">{TICKETURL} : <?php echo JText::_('Ticket URL'); ?></span>
                        <?php
                    } elseif ($this->templatefor == 'dl-tk') {
                        ?>
                        <span class="js-email-paramater">{SUBJECT} : <?php echo JText::_('Subject'); ?></span>
                        <span class="js-email-paramater">{TRACKINGID} : <?php echo JText::_('Tracking ID'); ?></span>
                        <?php
                    } elseif ($this->templatefor == 'mo-tk') {
                        ?>
                        <span class="js-email-paramater">{SUBJECT} : <?php echo JText::_('Subject'); ?></span>
                        <span class="js-email-paramater">{TRACKINGID} : <?php echo JText::_('Tracking ID'); ?></span>
                        <span class="js-email-paramater">{TICKETURL} : <?php echo JText::_('Ticket URL'); ?></span>
                        <?php
                    } elseif ($this->templatefor == 'be-tk') {
                        ?>
                        <span class="js-email-paramater">{EMAIL_ADDRESS} : <?php echo JText::_('Email address'); ?></span>
                        <?php
                    } elseif ($this->templatefor == 'be-trtk') {
                        ?>
                        <span class="js-email-paramater">{EMAIL_ADDRESS} : <?php echo JText::_('Email address'); ?></span>
                        <?php
                    } elseif ($this->templatefor == 'dt-tk') {
                        ?>
                        <span class="js-email-paramater">{SUBJECT} : <?php echo JText::_('Subject'); ?></span>
                        <span class="js-email-paramater">{TRACKINGID} : <?php echo JText::_('Tracking ID'); ?></span>
                        <span class="js-email-paramater">{DEPARTMENT_TITLE} : <?php echo JText::_('Department title'); ?></span>
                        <?php
                    } elseif ($this->templatefor == 'ebct-tk') {
                        ?>
                        <span class="js-email-paramater">{SUBJECT} : <?php echo JText::_('Subject'); ?></span>
                        <span class="js-email-paramater">{EMAIL_ADDRESS} : <?php echo JText::_('Email address'); ?></span>
                        <span class="js-email-paramater">{TICKETID} : <?php echo JText::_('Ticket Id'); ?></span>
                        <?php
                    } elseif ($this->templatefor == 'ube-tk') {
                        ?>
                        <span class="js-email-paramater">{EMAIL_ADDRESS} : <?php echo JText::_('Email address'); ?></span>
                        <?php
                    } elseif ($this->templatefor == 'rsp-tk') {
                        ?>
                        <span class="js-email-paramater">{USERNAME} : <?php echo JText::_('Username'); ?></span>
                        <span class="js-email-paramater">{SUBJECT} : <?php echo JText::_('Subject'); ?></span>
                        <span class="js-email-paramater">{TRACKINGID} : <?php echo JText::_('Tracking ID'); ?></span>
                        <span class="js-email-paramater">{EMAIL} : <?php echo JText::_('Email'); ?></span>
                        <span class="js-email-paramater">{MESSAGE} : <?php echo JText::_('Message'); ?></span>
                        <span class="js-email-paramater">{TICKETURL} : <?php echo JText::_('Ticket URL'); ?></span>
                        <?php
                    } elseif ($this->templatefor == 'rpy-tk') {
                        ?>
                        <span class="js-email-paramater">{USERNAME} : <?php echo JText::_('Username'); ?></span>
                        <span class="js-email-paramater">{SUBJECT} : <?php echo JText::_('Subject'); ?></span>
                        <span class="js-email-paramater">{TRACKINGID} : <?php echo JText::_('Tracking ID'); ?></span>
                        <span class="js-email-paramater">{EMAIL} : <?php echo JText::_('Email'); ?></span>
                        <span class="js-email-paramater">{MESSAGE} : <?php echo JText::_('Message'); ?></span>
                        <span class="js-email-paramater">{TICKETURL} : <?php echo JText::_('Ticket URL'); ?></span>
                        <?php
                    } elseif ($this->templatefor == 'tk-ew-ad') {
                        ?>
                        <span class="js-email-paramater">{USERNAME} : <?php echo JText::_('Username'); ?></span>
                        <span class="js-email-paramater">{SUBJECT} : <?php echo JText::_('Subject'); ?></span>
                        <span class="js-email-paramater">{TRACKINGID} : <?php echo JText::_('Tracking ID'); ?></span>
                        <span class="js-email-paramater">{EMAIL} : <?php echo JText::_('Email'); ?></span>
                        <span class="js-email-paramater">{MESSAGE} : <?php echo JText::_('Message'); ?></span>
                        <span class="js-email-paramater">{TICKETURL} : <?php echo JText::_('Ticket URL'); ?></span>
                        <?php
                    } elseif ($this->templatefor == 'lk-tk') {
                        ?>
                        <span class="js-email-paramater">{USERNAME} : <?php echo JText::_('Username'); ?></span>
                        <span class="js-email-paramater">{SUBJECT} : <?php echo JText::_('Subject'); ?></span>
                        <span class="js-email-paramater">{TRACKINGID} : <?php echo JText::_('Tracking ID'); ?></span>
                        <span class="js-email-paramater">{EMAIL} : <?php echo JText::_('Email'); ?></span>
                        <span class="js-email-paramater">{TICKETURL} : <?php echo JText::_('Ticket URL'); ?></span>
                        <?php
                    } elseif ($this->templatefor == 'ulk-tk') {
                        ?>
                        <span class="js-email-paramater">{USERNAME} : <?php echo JText::_('Username'); ?></span>
                        <span class="js-email-paramater">{SUBJECT} : <?php echo JText::_('Subject'); ?></span>
                        <span class="js-email-paramater">{TRACKINGID} : <?php echo JText::_('Tracking ID'); ?></span>
                        <span class="js-email-paramater">{EMAIL} : <?php echo JText::_('Email'); ?></span>
                        <span class="js-email-paramater">{TICKETURL} : <?php echo JText::_('Ticket URL'); ?></span>
                        <?php
                    } elseif ($this->templatefor == 'minp-tk') {
                        ?>
                        <span class="js-email-paramater">{SUBJECT} : <?php echo JText::_('Subject'); ?></span>
                        <span class="js-email-paramater">{TRACKINGID} : <?php echo JText::_('Tracking ID'); ?></span>
                        <span class="js-email-paramater">{TICKETURL} : <?php echo JText::_('Ticket URL'); ?></span>
                        <?php
                    } elseif ($this->templatefor == 'pc-tk') {
                        ?>
                        <span class="js-email-paramater">{SUBJECT} : <?php echo JText::_('Subject'); ?></span>
                        <span class="js-email-paramater">{TRACKINGID} : <?php echo JText::_('Tracking ID'); ?></span>
                        <span class="js-email-paramater">{PRIORITY_TITLE} : <?php echo JText::_('Priority'); ?></span>
                        <span class="js-email-paramater">{TICKETURL} : <?php echo JText::_('Ticket URL'); ?></span>
                        <?php
                    } elseif ($this->templatefor == 'ml-ew') {
                        ?>
                        <span class="js-email-paramater">{SUBJECT} : <?php echo JText::_('Subject'); ?></span>
                        <span class="js-email-paramater">{STAFF_MEMBER_NAME} : <?php echo JText::_('Staff member name'); ?></span>
                        <span class="js-email-paramater">{MESSAGE} : <?php echo JText::_('Message'); ?></span>
                        <?php
                    } elseif ($this->templatefor == 'ml-rp') {
                        ?>
                        <span class="js-email-paramater">{SUBJECT} : <?php echo JText::_('Subject'); ?></span>
                        <span class="js-email-paramater">{STAFF_MEMBER_NAME} : <?php echo JText::_('Staff member name'); ?></span>
                        <span class="js-email-paramater">{MESSAGE} : <?php echo JText::_('Message'); ?></span>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <!-- End Copied -->
            <input type="hidden" name="check" value="post"/>
            <?php if (isset($this->template)) {if (($this->template->created == '0000-00-00 00:00:00') || ($this->template->created == '')) $curdate = date('Y-m-d H:i:s'); else $curdate = $this->template->created; } else $curdate = date('Y-m-d H:i:s'); ?>
            <input type="hidden" name="created" value="<?php echo $curdate; ?>" />
            <input type="hidden" name="c" value="emailtemplate" />
            <input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
            <input type="hidden" name="id" value="<?php echo $this->template->id; ?>" />
            <input type="hidden" name="templatefor" value="<?php echo $this->template->templatefor; ?>" />
            <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
            <input type="hidden" name="task" value="saveemailtemplate" />
        </form>
        </div>
    </div>
    <div class="js-config-pro-version-text"><?php echo JText::_('* Pro version only'); ?></div>
</div>
<div id="js-tk-copyright">
    <img width="85" src="http://www.joomsky.com/logo/jssupportticket_logo_small.png">&nbsp;Powered by <a target="_blank" href="http://www.joomsky.com">Joom Sky</a><br/>
    &copy;Copyright 2008 - <?php echo date('Y'); ?>, <a target="_blank" href="http://www.burujsolutions.com">Buruj Solutions</a>
</div>

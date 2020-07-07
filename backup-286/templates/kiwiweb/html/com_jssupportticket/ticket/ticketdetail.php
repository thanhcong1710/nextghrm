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
JHTML::_('behavior.formvalidation');
JHTML::_('behavior.modal');
$document = JFactory::getDocument();
$document->addScript('administrator/components/com_jssupportticket/include/js/file/file_validate.js');
JText::script('Error file size too large');
JText::script('Error file extension mismatch');
$document->addStyleSheet(JUri::root(true) . '/templates/kiwiweb/css/ticket.css');
?>
<div>
        <script language="javascript">
                function myValidate(f) {
                        var chk_reopen = jQuery('input#isreopen').val();
                        if (document.formvalidator.isValid(f)) {
                                if (chk_reopen == "") {
                                        var msg_obj = jQuery("#message-required");
                                        if (typeof msg_obj !== 'undefined' && msg_obj !== null) {
                                                var msg_required_val = jQuery("#message-required").val();
                                                if (msg_required_val != '') {
                                                        var message = jQuery('textarea#message').val();
                                                        if (message == '') {
                                                                message = tinyMCE.get('message').getContent();
                                                        }
                                                        if (message == '') {
                                                                alert('<?php echo JText::_('Some values are not acceptable please retry'); ?>');
                                                                tinyMCE.get('message').focus();
                                                                return false;
                                                        }
                                                }
                                        }
                                } else if (chk_reopen == 1) {
                                        var msg_ro_obj = jQuery("#reopen-message-required");
                                        if (typeof msg_ro_obj !== 'undefined' && msg_ro_obj !== null) {
                                                var msg_ro_required_val = jQuery("#reopen-message-required").value;
                                                if (msg_ro_required_val != '') {
                                                        var message_ro = tinyMCE.get('messages').getContent();
                                                        if (message_ro == '') {
                                                                alert('<?php echo JText::_('Some values are not acceptable please retry'); ?>');
                                                                tinyMCE.get('messages').focus();
                                                                return false;
                                                        }
                                                }
                                        }
                                }
                                f.check.value = '<?php
if (JVERSION < 3)
        echo JUtility::getToken();
else
        echo JSession::getFormToken();
?>';//send token
                        } else {
                                alert('<?php echo JText::_('Some values are not accepatable please retry'); ?>');
                                return false;
                        }
                        return true;
                }

        </script>
        <?php
        if ($this->config['offline'] == '1') {
                messagesLayout::getSystemOffline($this->config['title'], $this->config['offline_text']);
        } else {
                ?>
                <div class="page-header">
                        <h1><?php echo JText::_('Ticket Detail'); ?>: <strong><?php echo $this->ticket->ticketid; ?></strong></h1>
                </div>
                <?php if (!empty($this->ticket)) { ?>
                        <div id="tk_detail_wraper">
                                <form action="<?php echo JUri::root(); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data"  onSubmit="return myValidate(this);">
                                        <div class="ticket-info">
                                                <?php
                                                $overdueindays = $this->config['ticket_overdue_indays'];
                                                $autocloseindays = $this->config['ticket_auto_close_indays'];
                                                $reopendays = $this->config['ticket_reopen_within_days'];
                                                $isautoclose = 0;
                                                $isoverdue = 0;
                                                $reopenticket = 1;
                                                ?>
                                                <div class="row">
                                                        <div class="col-md-12">
                                                                <div class="well well-sm">
                                                                        <?php echo JText::_('Subject'); ?>&nbsp;:
                                                                        <?php echo $this->ticket->subject; ?>
                                                                </div>
                                                        </div>
                                                </div>
                                                <div class="row">
                                                        <div class="col-md-3">
                                                                <div>
                                                                        <div class="well well-sm"><span class="fa fa-user"></span>&nbsp;<?php echo $this->ticket->name; ?></div>
                                                                        <div class="well well-sm"><span class="fa fa-envelope"></span>&nbsp;<?php echo $this->ticket->email; ?></div>
                                                                </div>
                                                        </div>
                                                        <div class="col-md-9">
                                                                <div class="user-box">
                                                                        <div class="row">
                                                                                <div class="col-md-6">
                                                                                        <ul class="list-unstyled">
                                                                                                <li>
                                                                                                        <span class="fa fa-ticket"></span>
                                                                                                        <strong><?php echo JText::_('Ticket ID'); ?>&nbsp;:</strong>
                                                                                                        <span><?php echo $this->ticket->ticketid; ?></span>
                                                                                                </li>
                                                                                                <li>
                                                                                                        <span class="fa fa-calendar"></span>
                                                                                                        <strong><?php echo JText::_('Created'); ?>: </strong>
                                                                                                        <?php
                                                                                                        echo JHTML::_('date', $this->ticket->created, JText::_('DATE_FORMAT_LC2'));
                                                                                                        ?>
                                                                                                </li>


                                                                                                <li>
                                                                                                        <span class="fa fa-calendar"></span>&nbsp;<strong><?php echo JText::_('Last reply'); ?>:</strong>
                                                                                                        <span>
                                                                                                                <?php
                                                                                                                if ($this->ticket->lastreply) {
                                                                                                                        echo JHTML::_('date', $this->ticket->lastreply, JText::_('DATE_FORMAT_LC2'));
                                                                                                                } else {
                                                                                                                        echo JHTML::_('date', $this->ticket->created, JText::_('DATE_FORMAT_LC2'));
                                                                                                                }
                                                                                                                ?>
                                                                                                        </span>
                                                                                                </li>

                                                                                                <li>
                                                                                                        <span class="fa fa-life-ring"></span>&nbsp;
                                                                                                        <strong><?php echo JText::_('Department'); ?></strong>&nbsp;:
                                                                                                        <?php echo $this->ticket->departmentname; ?>
                                                                                                </li>
                                                                                        </ul>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                        <ul class="list-unstyled">


                                                                                                <li>
                                                                                                        <span class="fa fa-bolt"></span>&nbsp;
                                                                                                        <strong> <?php echo JText::_('Priority'); ?>&nbsp;:</strong>
                                                                                                        <span class="label label-default"><?php echo $this->ticket->priority; ?></span>
                                                                                                </li>

                                                                                                <li>
                                                                                                        <?php
                                                                                                        if ($this->ticket->status == 4) {
                                                                                                                $date = JFactory::getDate();
                                                                                                                $now = $date->toSql();
                                                                                                                $status = JText::_('Close');
                                                                                                                //echo "(" . JHTML::_('date', $this->ticket->created, JText::_('DATE_FORMAT_LC2')) . ")";
                                                                                                                //$reopenclosedate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($this->ticket->closed)) . " +" . $reopendays . " day"));
                                                                                                                $reopenclosedate = JFactory::getDate($this->ticket->closed);
                                                                                                                if (!empty($reopendays)) {
                                                                                                                        $reopenclosedate->add($reopendays . " day");
                                                                                                                }

                                                                                                                if ($reopenclosedate->toSql() < $now) {
                                                                                                                        $reopenticket = 0;
                                                                                                                }
                                                                                                        } elseif ($this->ticket->status == 0) {
                                                                                                                $status = JText::_('New');
                                                                                                        } elseif ($this->ticket->status == 3) {
                                                                                                                $status = JText::_('Waiting your reply');
                                                                                                        } elseif ($this->ticket->status == 2)
                                                                                                                $status = JText::_('Waiting Staff Reply');
                                                                                                        ?>
                                                                                                        <strong> <?php echo JText::_('Status'); ?>&nbsp;:</strong>
                                                                                                        <span>
                                                                                                                <?php
                                                                                                                echo $status;
                                                                                                                ?>
                                                                                                        </span>
                                                                                                </li>
                                                                                                <li>
                                                                                                        <?php if ($this->ticket->status != 4 && $isautoclose != 1) { ?>
                                                                                                                <button class="btn btn-danger" onclick="actioncall('<?php echo 3; ?>')" ><?php echo JText::_('Close Ticket'); ?></button>
                                                                                                        <?php } elseif ($reopenticket == 1) { ?>
                                                                                                                <button class="btn btn-success" onclick="actioncall('<?php echo 8; ?>')" ><?php echo JText::_('Reopen Ticket'); ?></button>
                                                                                                        <?php } ?>
                                                                                                </li>

                                                                                        </ul>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>

                                                </div>

                                                <div id="tk_properties" style="display: none;">
                                                        <div id="js-tk-heading" class="js-small">
                                                                <span id="js-tk-heading-text"><?php echo JText::_('More Detail'); ?></span>
                                                        </div>
                                                        <div class="js-col-md-6 js-col-sm-6 js-col-xs-12 tk_key_value_wraper">
                                                                <div class="tk_key_wraper">
                                                                        <span class="tk_key_text tk_properties_key"> <?php echo JText::_('Phone'); ?> </span>
                                                                </div>
                                                                <div class="tk_value_wraper">
                                                                        <span class="tk_properties_hyphen"> : </span>
                                                                        <span class="tk_value_text tk_properties_value">
                                                                                <?php
                                                                                echo $this->ticket->phone;
                                                                                if ($this->ticket->phoneext)
                                                                                        echo ' - ' . $this->ticket->phoneext;
                                                                                ?>
                                                                        </span>
                                                                </div>
                                                        </div>
                                                        <?php
                                                        if ($this->userfields) {
                                                                foreach ($this->userfields as $ufield) {
                                                                        $userfield = $ufield[0];
                                                                        if ($userfield->type == "checkbox") {
                                                                                if (isset($ufield[1])) {
                                                                                        $fvalue = $ufield[1]->data;
                                                                                        $userdataid = $ufield[1]->id;
                                                                                } else {
                                                                                        $fvalue = " ";
                                                                                        $userdataid = "";
                                                                                }
                                                                                if ($fvalue == '1')
                                                                                        $fvalue = JText::_("True");
                                                                                else
                                                                                        $fvalue = JText::_("False");
                                                                        }elseif ($userfield->type == "select") {
                                                                                if (isset($ufield[2])) {
                                                                                        $fvalue = $ufield[2]->fieldtitle;
                                                                                        $userdataid = $ufield[2]->id;
                                                                                } else {
                                                                                        $fvalue = " ";
                                                                                        $userdataid = "";
                                                                                }
                                                                        } else {
                                                                                if (isset($ufield[1])) {
                                                                                        $fvalue = $ufield[1]->data;
                                                                                        $userdataid = $ufield[1]->id;
                                                                                } else {
                                                                                        $fvalue = " ";
                                                                                        $userdataid = "";
                                                                                }
                                                                        }

                                                                        echo '<div class="js-col-md-6 js-col-sm-6 js-col-xs-12 tk_key_value_wraper">';
                                                                        echo '<div class="tk_key_wraper">';
                                                                        echo '<span class="tk_key_text tk_properties_key">';
                                                                        echo $userfield->title;
                                                                        echo '</span></div>';
                                                                        echo '<div class="tk_value_wraper">';
                                                                        echo '<span class="tk_properties_hyphen"> : </span>';
                                                                        echo '<span class="tk_value_text tk_properties_value">' . $fvalue;
                                                                        echo '</span>';
                                                                        echo '</div>';
                                                                        echo '</div>';
                                                                }
                                                        }
                                                        ?>
                                                </div>
                                        </div>    <!-- end ticket detail content wraper -->
                                        <div id="tk_detail_reply_wraper">
                                                <div class="ticket-row">
                                                        <div class="row">
                                                                <div class="col-md-12">
                                                                        <h3 class="ticket-support-request"><span class="fa fa-support"></span>&nbsp;<?php echo JText::_('Support request'); ?></h3>
                                                                        <h3 class="ticket-subject"><?php echo $this->ticket->subject; ?></h3>
                                                                        <div class="request-box"><span class="fa fa-user"></span>&nbsp;<?php echo $this->ticket->name; ?></div>
                                                                        <div class="ticket-created">
                                                                                <span class="fa fa-calendar"></span>&nbsp;
                                                                                <?php
                                                                                echo JHTML::_('date', $this->ticket->created, JText::_('DATE_FORMAT_LC2'));
                                                                                ?>
                                                                        </div>
                                                                        <div class="ticket-desc">
                                                                                <div><?php echo $this->ticket->message; ?></div>
                                                                        </div>

                                                                        <?php
                                                                        if (isset($this->attachment[0]->filename) && $this->attachment[0]->filename <> '') {
                                                                                foreach ($this->attachment as $row) {
                                                                                        if ($row->filename && $row->filename <> '') {
                                                                                                $datadirectory = $this->config['data_directory'];
                                                                                                $path = $datadirectory . '/attachmentdata/ticket/ticket_' . $row->id . '/' . $row->filename;
                                                                                                $path = str_replace(' ', '%20', $path);
                                                                                                ?>
                                                                                                <div class="tk_detail_reply_attachments_wrper">
                                                                                                        <div class="tk_detail_reply_attachments_file_size">
                                                                                                                <span class="tk_detail_reply_attachments_file_name"><?php echo $row->filename; ?> </span>
                                                                                                                <span class="tk_detail_reply_attachments_file_size"><?php echo '( ' . round($row->filesize, 2) . "KB" . ' )'; ?></span>

                                                                                                        </div>
                                                                                                        <div class="tk_detail_reply_attachments_download_wraper">
                                                                                                                <a target="_blank" href="<?php echo $path; ?>" class="tk_detail_reply_attachments_download_button"><?php echo JText::_('Downloads'); ?></a>
                                                                                                        </div>
                                                                                                </div>
                                                                                                <?php
                                                                                        }
                                                                                }
                                                                        }
                                                                        ?>
                                                                </div>
                                                        </div>
                                                </div>
                                                <?php
                                                //$k = 0;
                                                for ($i = 0, $n = count($this->messages); $i < $n; $i++) {
                                                        $row = & $this->messages[$i];
                                                        ?>
                                                        <div class="ticket-row ticket-reply">
                                                                <div class="row">
                                                                        <div class="col-md-12">
                                                                                <h4 class="ticket-reply-from"><?php echo JText::_('Reply from'); ?> <?php
                                                                                        if ($row->name)
                                                                                                echo $row->name;
                                                                                        else
                                                                                                echo $this->ticket->name;
                                                                                        ?>
                                                                                </h4>
                                                                                <div class="ticket-created">
                                                                                        <span class="fa fa-calendar"></span>&nbsp;
                                                                                        <?php
                                                                                        echo JHTML::_('date', $row->created, JText::_('DATE_FORMAT_LC2'));
                                                                                        ?>
                                                                                </div>
                                                                                <div class="ticket-desc">
                                                                                        <?php echo $row->message; ?>
                                                                                </div>
                                                                                <?php
                                                                                $count = $row->count;
                                                                                if ($count >= 1) {
                                                                                        $outdex = $i + $count;
                                                                                        for ($j = $i; $j < $outdex; $j++) {
                                                                                                if ($row->filename && $row->filename <> '') {
                                                                                                        $datadirectory = $this->config['data_directory'];
                                                                                                        $path = $datadirectory . '/attachmentdata/ticket/ticket_' . $row->ticketid . '/' . $row->filename;
                                                                                                        $path = str_replace(' ', '%20', $path);
                                                                                                        ?>
                                                                                                        <div class="tk_detail_reply_attachments_wrper">
                                                                                                                <div class="tk_detail_reply_attachments_file_size">
                                                                                                                        <span class="tk_detail_reply_attachments_file_name"><?php echo $row->filename; ?> </span>
                                                                                                                        <span class="tk_detail_reply_attachments_file_size"><?php echo '( ' . round($row->filesize, 2) . "KB" . ' )'; ?></span>
                                                                                                                </div>
                                                                                                                <div class="tk_detail_reply_attachments_download_wraper">
                                                                                                                        <a href="<?php echo $path; ?>" class="tk_detail_reply_attachments_download_button"><?php echo JText::_('Downloads'); ?></a>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <?php
                                                                                                }
                                                                                                $row = & $this->messages[$j + 1];
                                                                                        }
                                                                                        $i = $outdex - 1;
                                                                                }
                                                                                ?>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                <?php } ?>
                                        </div>   <!-- end ticket detail reply wraper  -->
                                        <?php if ($this->ticket->lock == 0 && $this->ticket->status != 4 && $isautoclose != 1) { ?>
                                                <div id="tk_reply_message_wrapper">
                                                        <div id="tk_reply_message_editor">
                                                                <span class="tk_reply_message_editor_arrow"></span>
                                                                <span id="tk_reply_message_editor_top"></span>
                                                                <?php
                                                                $editor1 = JFactory::getEditor();
                                                                echo $editor1->display('message', '', '55', '20', '30', '20', false);
                                                                ?>
                                                                <input type='hidden' id='message-required' name="message-required" value="<?php echo 'required'; ?>">
                                                        </div>
                                                        <div class="tk_attachments_wraper">
                                                                <div class="tk_attachment_key_wrapper">
                                                                        <span class="tk_attachment_key_text">Attacchments</span>
                                                                </div>
                                                                <div class="tk_attachment_value_wrapper">
                                                                        <span class="tk_attachment_value_text">
                                                                                <input type="file" class="inputbox" name="filename[]" onchange="uploadfile(this, '<?php echo $this->config["filesize"]; ?>', '<?php echo $this->config["fileextension"]; ?>');" size="20" maxlenght='30'/>
                                                                                <span class='tk_attachment_remove'></span>
                                                                        </span>
                                                                </div>
                                                                <span class="tk_attachments_config">
                                                                        <small><?php echo JText::_('Maximum file size') . ' (' . $this->config['filesize']; ?>KB)<br><?php echo JText::_('File extension type') . ' (' . $this->config['fileextension'] . ')'; ?></small>
                                                                </span>
                                                                <span id="tk_attachment_add" class="tk_attachments_add"><?php echo JText::_('Add more file'); ?></span>
                                                        </div>
                                                </div> <!-- end ticket reply message wraper  -->
                                                <div>
                                                        <input class="btn btn-success" type="submit"  name="submit_app" value="<?php echo JText::_('Post Reply'); ?>" />
                                                </div>

                                        <?php } ?>
                                        <input type="hidden" name="created" value="<?php echo $curdate = JFactory::getDate()->toSql(); ?>" />
                                        <input type="hidden" name="view" value="ticket" />
                                        <input type="hidden" name="c" value="ticket" />
                                        <input type="hidden" name="layout" value="ticketdetail" />
                                        <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                                        <input type="hidden" name="task" value="actionticket" />
                                        <input type="hidden" name="check" value="" />
                                        <input type="hidden" name="email" value="<?php echo $this->email; ?>" />
                                        <input type="hidden" name="callaction" id="callaction" value="" />
                                        <input type="hidden" name="callfrom" id="callfrom" value="savemessage" />
                                        <input type="hidden" name="isreopen" id="isreopen" value="" />
                                        <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
                                        <input type="hidden" name="lastreply" value="<?php echo $this->ticket->lastreply; ?>" />
                                        <input type="hidden" name="ticketid" value="<?php if (isset($this->ticket)) echo $this->ticket->id; ?>" />
                                </form>
                        </div>
                        <script language=Javascript>
                                jQuery(document).ready(function () {
                                        jQuery("#tk_attachment_add_reopen").click(function () {
                                                var obj = this;
                                                var current_files = jQuery('div#tk_r eopenticket div.tk_attachments_wraper input[type="file"]').length;
                                                var total_allow =<?php echo $this->config['noofattachment']; ?>;
                                                var append_text = "<span class='tk_attachment_value_text'><input name='filename[]' type='file' onchange=uploadfile(this,'<?php echo $this->config['filesize']; ?>','<?php echo $this->config['fileextension']; ?>'); size='20' maxlenght='30'  /><span  class='tk_attachment_remove'></span></span>";
                                                if (current_files < total_allow) {
                                                        jQuery("div#tk_reopenticket div.tk_attachments_wraper div.tk_attachment_value_wrapper").append(append_text);
                                                } else if ((current_files === total_allow) || (current_files > total_allow)) {
                                                        alert('<?php echo JText::_('File upload limit exceed'); ?>');
                                                        jQuery(obj).hide();
                                                }
                                        });

                                        jQuery(document).delegate("div#tk_reopenticket div.tk_attachments_wraper span.tk_attachment_value_text span.tk_attachment_remove", "click", function (e) {
                                                jQuery(this).parent().remove();
                                                var current_files = jQuery('div#tk_r eopenticket div.tk_attachments_wraper input[type="file"]').length;
                                                var total_allow =<?php echo $this->config['noofattachment']; ?>;
                                                if (current_files < total_allow) {
                                                        jQuery("#tk_attachment_add_reopen").show();
                                                }
                                        });

                                        jQuery("#tk_attachment_add").click(function () {
                                                var obj = this;
                                                var current_files = jQuery('div#tk_reply_message_wr apper div.tk_attachment_value_wrapper input[type="file"]').length;
                                                var total_allow =<?php echo $this->config['noofattachment']; ?>;
                                                var append_text = "<span class='tk_attachment_value_text'><input name='filename[]' type='file' onchange=uploadfile(this,'<?php echo $this->config['filesize']; ?>','<?php echo $this->config['fileextension']; ?>'); size='20' maxlenght='30'  /><span  class='tk_attachment_remove'></span></span>";
                                                if (current_files < total_allow) {
                                                        jQuery("div#tk_reply_message_wrapper div.tk_attachment_value_wrapper").append(append_text);
                                                } else if ((current_files === total_allow) || (current_files > total_allow)) {
                                                        alert('<?php echo JText::_('File upload limit exceed'); ?>');
                                                        jQuery(obj).hide();
                                                }
                                        });
                                        jQuery(document).delegate("div#tk_reply_message_wrapper div.tk_attachments_wraper span.tk_attachment_value_text span.tk_attachment_remove", "click", function (e) {
                                                var current_files = jQuery('input[type="file"]').length;
                                                if (current_files != 1)
                                                        jQuery(this).parent().remove();
                                                var current_files = jQuery('input[type="file"]').length;
                                                var total_allow =<?php echo $this->config['noofattachment']; ?>;
                                                if (current_files < total_allow) {
                                                        jQuery("#tk_attachment_add").show();
                                                }
                                        });
                                        jQuery('#tk_request_detail_show_text').click(function () {
                                                if (jQuery('#tk_properties').css('display') == 'none') {
                                                        jQuery('#tk_request_detail_show_up_down_img').addClass('tk_request_detail_show_text_img_down');
                                                        jQuery('#tk_request_detail_show_up_down_img').removeClass('tk_request_detail_show_text_img');
                                                } else if (jQuery('#tk_properties').css('display') == 'block') {
                                                        jQuery('#tk_request_detail_show_text').text('<?php echo JText::_('Show detail'); ?>');
                                                        jQuery('#tk_request_detail_show_up_down_img').addClass('tk_request_detail_show_text_img');
                                                        jQuery('#tk_request_detail_show_up_down_img').removeClass('tk_request_detail_show_text_img_down');
                                                }
                                                jQuery('#tk_properties').slideToggle("slow");
                                        });
                                });

                                function actioncall(value) {
                                        if (value == 8) {
                                                /*
                                                 jQuery('#isreopen').val(1);
                                                 jQuery('div#tk_reopenticket').slideDown();
                                                 tinyMCE.get('messages').focus();
                                                 */
                                                jQuery('#callfrom').val('action');
                                                jQuery('#callaction').val(value);
                                                document.adminForm.submit();
                                        } else if (value == 3) {
                                                var yesclose = confirm('<?php echo JText::_('Are you sure to close ticket'); ?>');
                                                if (yesclose == true) {
                                                        jQuery('#callfrom').val('action');
                                                        jQuery('#callaction').val(value);
                                                        document.adminForm.submit();
                                                }
                                        }
                                }
                                function closereopndiv() {
                                        jQuery('div#tk_reopenticket').slideUp();
                                }
                        </script>
                        <?php
                }else {
                        messagesLayout::getRecordNotFound();
                }
        }
        ?>
</div>
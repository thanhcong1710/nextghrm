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
?>
<div class="js-row js-null-margin">
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
                        if(message == ''){
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
            f.check.value = '<?php if (JVERSION < 3) echo JUtility::getToken(); else echo JSession::getFormToken(); ?>';//send token
        } else {
            alert('<?php echo JText::_('Some values are not accepatable please retry'); ?>');
            return false;
        }
        return true;
    }

</script>
<?php 
if ($this->config['offline'] == '1') {
    messagesLayout::getSystemOffline($this->config['title'],$this->config['offline_text']);
} else { ?>
    <div id="js-tk-heading">
        <span id="js-tk-heading-text"><?php echo JText::_('Ticket Detail'); ?></span>
    </div>
    <?php 
    if(!empty($this->ticket)){ ?>
    <div id="tk_detail_wraper">
        <form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data"  onSubmit="return myValidate(this);">
            <div id="tk_detail_content_wraper">
                <?php
                $overdueindays = $this->config['ticket_overdue_indays'];
                $autocloseindays = $this->config['ticket_auto_close_indays'];
                $reopendays = $this->config['ticket_reopen_within_days'];
                $isautoclose = 0;
                $isoverdue = 0;
                $reopenticket = 1;
                ?>
                <div class="js-col-md-12 js-col-sm-12 js-col-xs-12" id="tk_detail_top_info_wraper">
                    <div class="js-col-md-4 js-col-sm-4 js-col-xs-12 js-nopadding">
                        <div id="tk_detail_status" class="js-col-md-5 js-col-sm-12 js-col-xs-12">
                            <span id="tk_detail_status_text">
                                <?php
                                if ($this->ticket->status == 4) {
                                    $status = JText::_('Close');
                                    $reopenclosedate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($this->ticket->closed)) . " +" . $reopendays . " day"));
                                    if ($reopenclosedate < date('Y-m-d H:i:s'))
                                        $reopenticket = 0;
                                }elseif ($this->ticket->status == 0){
                                    $status = JText::_('New');
                                }elseif ($this->ticket->status == 3) {
                                    $status = JText::_('Waiting your reply');
                                } elseif ($this->ticket->status == 2)
                                    $status = JText::_('Waiting Staff Reply');
                                    echo $status;
                                ?>

                            </span>
                        </div> 
                        <div id="tk_detail_post" class="js-col-md-7 js-col-sm-12 js-col-xs-12">
                            <span id="tk_detail_post_text">
                                <?php echo JText::_('Created'); ?>
                                <?php
                                $startTimeStamp = strtotime($this->ticket->created);
                                $endTimeStamp = strtotime("now");
                                $timeDiff = abs($endTimeStamp - $startTimeStamp);
                                $numberDays = $timeDiff / 86400;  // 86400 seconds in one day
                                // and you might want to convert to integer
                                $numberDays = intval($numberDays);
                                if ($numberDays != 0 && $numberDays == 1) {
                                    $day_text = JText::_('Day');
                                } elseif ($numberDays > 1) {
                                    $day_text = JText::_('Days');
                                } elseif ($numberDays == 0) {
                                    $day_text = JText::_('Today');
                                }
                                if ($numberDays == 0) {
                                    echo $day_text;
                                } else {
                                    echo $numberDays . ' ' . $day_text . ' ';
                                    echo JText::_('Ago');
                                }
                                ?>
                                <br> <?php echo date("d F, Y, h:i:s A", strtotime($this->ticket->created)); ?>
                            </span>
                        </div>
                    </div>
                    <div id="tk_detail_id" class="js-col-md-4 js-col-sm-4 js-col-xs-12">
                        <div class="js-col-md-12 js-col-sm-12 js-col-xs-12">
                            <span class="js-col-md-4 js-col-sm-6 js-col-xs-12"> <?php echo JText::_('Ticket ID'); ?>&nbsp;:</span>
                            <span class="js-col-md-8 js-col-sm-6 js-col-xs-12" id="tk_detail_id_value"><?php echo $this->ticket->ticketid; ?></span>
                        </div>
                        <div class="js-col-md-12 js-col-sm-12 js-col-xs-12">
                            <span class="js-col-md-4 js-col-sm-6 js-col-xs-4"> <?php echo JText::_('Priority'); ?>&nbsp;:</span>
                            <span class="js-col-md-8 js-col-sm-6 js-col-xs-12" style="background-color:<?php echo $this->ticket->prioritycolour; ?>;color:#ffffff;" id="tk_detail_id_value" class="tk_detail_id_perority"><?php echo $this->ticket->priority; ?></span>
                        </div>
                    </div>
                    <div id="tk_detail_reply" class="js-col-md-4 js-col-sm-4 js-col-xs-12">
                        <div class="js-col-md-12 js-col-sm-12 js-col-xs-12">
                            <span class="js-col-md-4 js-col-sm-6 js-col-xs-12"> <?php echo JText::_('Last reply'); ?>&nbsp;:</span>
                            <span class="js-col-md-8 js-col-sm-6 js-col-xs-12" id="tk_detail_id_value">
                                <?php
                                if ($this->ticket->lastreply) {
                                    echo date($this->config['date_format'], strtotime($this->ticket->lastreply));
                                } else {
                                    echo date($this->config['date_format'], strtotime($this->ticket->created));
                                }
                                ?>
                            </span>
                        </div>
                        <div class="js-col-md-12">
                            <span class="js-col-md-4 js-col-sm-6 js-col-xs-12"> <?php echo JText::_('Status'); ?>&nbsp;:</span>
                            <span class="js-col-md-8 js-col-sm-6 js-col-xs-12" id="tk_detail_id_value">
                                <?php
                                echo $status;
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div id="tk_detail_info" class="js-col-md-12 js-col-sm-12 js-col-xs-12 js-nopadding">
                    <div id="tk_detail_info_desc" class="js-col-md-9 js-nopadding">
                        <div id="tk_detail_info_key_value_wraper">
                            <span class="tk_detail_info_key"><?php echo JText::_('Subject'); ?>&nbsp;:</span>
                            <span class="tk_detail_info_value js-value-subject"><?php echo $this->ticket->subject; ?></span>
                        </div>
                        <div id="tk_detail_info_key_value_wraper">
                            <span class="tk_detail_info_key"><?php echo JText::_('Department'); ?>&nbsp;:</span>
                            <span class="tk_detail_info_value"><?php echo $this->ticket->departmentname; ?></span>
                        </div>
                    </div>
                    <?php if ($this->ticket->status != 4 && $isautoclose != 1) { ?>
                        <div id="tk_detail_info_oc_btn">
                            <img src="components/com_jssupportticket/include/images/closeticket.png">
                            <span onclick="actioncall('<?php echo 3; ?>')" ><?php echo JText::_('Close Ticket'); ?></span>
                        </div>
                    <?php } elseif ($reopenticket == 1) { ?>
                        <div id="tk_detail_info_oc_btn">
                            <img src="components/com_jssupportticket/include/images/reopenticket.png">
                            <span  onclick="actioncall('<?php echo 8; ?>')" ><?php echo JText::_('Reopen Ticket'); ?></span>
                        </div>
                    <?php } ?>
                </div>
                <div id="tk_request_info_wrapper">
                    <div id="js-tk-heading" class="js-small">
                        <span id="js-tk-heading-text"><?php echo JText::_('Requester Info'); ?></span>
                    </div>
                    <div id="tk_request_detail">
                        <div id="tk_request_detail_man">
                            <div id="tk_request_detail_pic">
                                <img  src="components/com_jssupportticket/include/images/user_s.png" alt="<?php echo JText::_('New Ticket'); ?>" />
                            </div>
                            <span id="tk_request_detail_name_text"><?php echo $this->ticket->name; ?></span>
                        </div>
                        <div id="tk_request_detail_email">
                            <div id="tk_request_detail_email_pic">
                                <img  src="components/com_jssupportticket/include/images/email.png" alt="<?php echo JText::_('New Ticket'); ?>" />
                            </div>
                            <span id="tk_request_detail_email_text"><?php echo $this->ticket->email; ?></span>
                        </div>
                        <div id="tk_request_detail_show_hide">
                            <span id="tk_request_detail_show_up_down_img" class="tk_request_detail_show_text_img"></span>
                            <span id="tk_request_detail_show_text"><?php echo JText::_('Show detail'); ?></span>
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
                                <?php echo $this->ticket->phone;if($this->ticket->phoneext) echo ' - '.$this->ticket->phoneext; ?>
                            </span>
                        </div>   
                    </div>    
                    <?php
                    if($this->userfields){
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
            <div id="tk_heading">
                <span id="tk_heading_text"><?php echo JText::_('Ticket Thread'); ?></span>
            </div>
            <div id="tk_detail_reply_wraper">
                <div class="tk_detail_reply">
                    <div class="tk_detail_reply_image">
                        <img  src="components/com_jssupportticket/include/images/user.png" alt="<?php echo JText::_('New Ticket'); ?>" />
                    </div>   
                    <div class="tk_detail_reply_description">
                        <!--<span class="tk_detail_reply_description_normal"></span>-->
                        <div class="tk_detail_reply_description_top">
                            <div class="tk_detail_reply_description_left">
                                <span class="tk_detail_reply_description_subject"><?php echo JText::_('Posted By'); ?> : <?php echo $this->ticket->name; ?></span>
                                <span class="tk_detail_reply_description_date">
                                    <?php echo "(" . date("l F d, Y, h:i:s A", strtotime($this->ticket->created)) . ")"; ?> 
                                </span>
                            </div>
                        </div>
                        <div class="tk_detail_reply_description_bottom">
                            <span class="tk_detail_reply_description_text"><?php echo $this->ticket->message; ?></span>
                        </div>
                        <?php
                        if (isset($this->attachment[0]->filename) && $this->attachment[0]->filename <> '') {
                            foreach ($this->attachment as $row) {
                                if ($row->filename && $row->filename <> '') {
                                    $datadirectory = $this->config['data_directory'];
                                    $path = $datadirectory . '/attachmentdata/ticket/ticket_' . $row->id . '/' . $row->filename;
                                    $path = str_replace(' ', '%20', $path); ?>  
                                    <div class="tk_detail_reply_attachments_wrper">
                                        <div class="tk_detail_reply_attachments_file_size">
                                            <span class="tk_detail_reply_attachments_file_name"><?php echo $row->filename; ?> </span>
                                            <span class="tk_detail_reply_attachments_file_size"><?php echo '( ' . round($row->filesize, 2) . "KB" . ' )'; ?></span>

                                        </div>
                                        <div class="tk_detail_reply_attachments_download_wraper">
                                            <a target="_blank" href="<?php echo $path; ?>" class="tk_detail_reply_attachments_download_button"><?php echo JText::_('Downloads'); ?></a>
                                        </div>
                                    </div>
                        <?php }
                            }
                        } ?>
                    </div>   
                </div>
                <?php
                //$k = 0;
                for ($i = 0, $n = count($this->messages); $i < $n; $i++) {
                    $row = & $this->messages[$i]; ?>
                    <div class="tk_detail_reply">
                        <div class="tk_detail_reply_image">
                            <img  src="components/com_jssupportticket/include/images/user.png" alt="<?php echo JText::_('New Ticket'); ?>" />
                        </div>   
                        <div class="tk_detail_reply_description">
                            <div class="tk_detail_reply_description_top">
                                <div class="tk_detail_reply_description_left">
                                    <span class="tk_detail_reply_description_subject"><?php echo JText::_('Posted By'); ?> : <?php if($row->name) echo $row->name; else echo $this->ticket->name; ?></span>
                                    <span class="tk_detail_reply_description_date">
                                        <?php echo "(" . date("l F d, Y, h:i:s A", strtotime($row->created)) . ")"; ?> 
                                    </span>
                                </div>
                            </div>
                            <div class="tk_detail_reply_description_bottom">
                                <span class="tk_detail_reply_description_text"><?php echo $row->message; ?></span>
                            </div>
                            <?php
                            $count = $row->count;
                            if ($count >= 1) {
                                $outdex = $i + $count;
                                for ($j = $i; $j < $outdex; $j++) {
                                    if ($row->filename && $row->filename <> '') {
                                        $datadirectory = $this->config['data_directory'];
                                        $path = $datadirectory . '/attachmentdata/ticket/ticket_' . $row->ticketid . '/' . $row->filename;
                                        $path = str_replace(' ', '%20', $path); ?>  
                                        <div class="tk_detail_reply_attachments_wrper">
                                            <div class="tk_detail_reply_attachments_file_size">
                                                <span class="tk_detail_reply_attachments_file_name"><?php echo $row->filename; ?> </span>
                                                <span class="tk_detail_reply_attachments_file_size"><?php echo '( ' . round($row->filesize, 2) . "KB" . ' )'; ?></span>
                                            </div>
                                            <div class="tk_detail_reply_attachments_download_wraper">
                                                <a href="<?php echo $path; ?>" class="tk_detail_reply_attachments_download_button"><?php echo JText::_('Downloads'); ?></a>
                                            </div>
                                        </div>
                            <?php }
                                    $row = & $this->messages[$j + 1];
                                }
                                $i = $outdex - 1;
                            } ?>
                        </div>   
                    </div>
            <?php } ?>
            </div>   <!-- end ticket detail reply wraper  -->
            <?php if ($this->ticket->lock == 0 && $this->ticket->status != 4 && $isautoclose != 1) { ?>
            <div id="tk_heading">
                <span id="tk_heading_text"><?php echo JText::_('Post Reply'); ?></span>
            </div>
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
                <div class="js-tk-submit">
                    <input class="tk_dft_btn" type="submit"  name="submit_app" value="<?php echo JText::_('Post Reply'); ?>" />
                </div>				        

            <?php } ?>
            <input type="hidden" name="created" value="<?php echo $curdate = date('Y-m-d H:i:s'); ?>" />
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
                var current_files = jQuery('div#tk_reopenticket div.tk_attachments_wraper input[type="file"]').length;
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
                var current_files = jQuery('div#tk_reopenticket div.tk_attachments_wraper input[type="file"]').length;
                var total_allow =<?php echo $this->config['noofattachment']; ?>;
                if (current_files < total_allow) {
                    jQuery("#tk_attachment_add_reopen").show();
                }
            });

            jQuery("#tk_attachment_add").click(function () {
                var obj = this;
                var current_files = jQuery('div#tk_reply_message_wrapper div.tk_attachment_value_wrapper input[type="file"]').length;
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
                if(current_files!=1)
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
                if(yesclose == true){
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
<?php }else{
        messagesLayout::getRecordNotFound();
      }
    } ?>
<div id="js-tk-copyright">
    <img width="85" src="http://www.joomsky.com/logo/jssupportticket_logo_small.png">&nbsp;Powered by <a target="_blank" href="http://www.joomsky.com">Joom Sky</a><br/>
    &copy;Copyright 2008 - <?php echo date('Y'); ?>, <a target="_blank" href="http://www.burujsolutions.com">Buruj Solutions</a>
</div>
</div>
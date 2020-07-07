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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHTML::_('behavior.formvalidation');
$document = JFactory::getDocument();
$document->addScript('components/com_jssupportticket/include/js/jquery_idTabs.js');
$document->addScript('components/com_jssupportticket/include/js/file/file_validate.js');
JText::script('Error file size too large');
JText::script('Error file extension mismatch');
?>
<script type="text/javascript">
    function validate_form(f)
    {
        if (document.formvalidator.isValid(f)) {
            f.check.value = '<?php if ((JVERSION == '1.5') || (JVERSION == '2.5')) echo JUtility::getToken();
                else echo JSession::getFormToken(); ?>';//send token
        } else {
            alert('<?php echo JText::_('Some values are not acceptable please retry'); ?>');
            return false;
        }
        document.adminForm.submit();
    }
    //moreDetailDiv
    jQuery(document).ready(function(){
        jQuery('a[href="#"]').click(function(e){
            e.preventDefault();
        });
        jQuery("a#moreactions").click(function(e){
            e.preventDefault();
            jQuery("div#js-tk-actiondiv").slideToggle();
        });
        
        jQuery("a#requester-showmore").click(function(e){
            e.preventDefault();
            jQuery("a#requester-showmore").find('img').toggleClass('js-hidedetail');
            jQuery("div#req-moredetail").slideToggle();
        });
        //ATTACHMENTS
        jQuery("#js-attachment-add").click(function () {
            var obj = this;
            var current_files = jQuery('input[type="file"]').length;
            var total_allow =<?php echo $this->config['noofattachment']; ?>;
            var append_text = "<span class='js-value-text'><input name='filename[]' type='file' onchange=uploadfile(this,'<?php echo $this->config['filesize']; ?>','<?php echo $this->config['fileextension']; ?>'); size='20' maxlenght='30' /><span  class='js-attachment-remove'></span></span>";
            if (current_files < total_allow) {
                jQuery(".js-attachment-files").append(append_text);
            } else if ((current_files === total_allow) || (current_files > total_allow)) {
                alert('<?php echo JText::_('File upload limit exceed'); ?>');
                jQuery(obj).hide();
            }
        });
        jQuery(document).delegate(".js-attachment-remove", "click", function (e) {
            var current_files = jQuery('input[type="file"]').length;
            if(current_files!=1)
                jQuery(this).parent().remove();
            var current_files = jQuery('input[type="file"]').length;
            var total_allow =<?php echo $this->config['noofattachment']; ?>;
            if (current_files < total_allow) {
                jQuery("#js-attachment-add").show();
            }
        });
    });
</script>
<div id="js-tk-admin-wrapper">
    <div id="js-tk-leftmenu">
        <?php include_once('components/com_jssupportticket/views/menu.php'); ?>
    </div>
    <div id="js-tk-cparea">
        <div id="js-tk-heading">
            <h4><img id="js-admin-responsive-menu-link" src="components/com_jssupportticket/include/images/c_p/left-icons/menu.png" /><?php echo JText::_('Ticket Detail'); ?></h4>
        </div>
        <form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
        <div id="js-tk-ticket-detail" class="js-col-md-12">
            <div class="js-back-white">
            <div class="js-col-md-12" id="js-firstrow">
                <div class="js-col-md-4 js-padding-null">
                    <span class="js-col-md-4 js-col-xs-12 js-status">
                        <?php if ($this->ticketdetail->status == 0) { ?> 
                            <span style="background-color: red;"><?php echo JText::_('New'); ?></span>
                        <?php } elseif ($this->ticketdetail->status == 2) { ?>
                            <span style="background-color: orange;"><?php echo JText::_('Waiting reply'); ?></span>
                        <?php } elseif ($this->ticketdetail->status == 3) { ?>
                            <span style="background-color: green;"><?php echo JText::_('Replied'); ?></span>
                        <?php } elseif ($this->ticketdetail->status == 4) { ?>
                            <span style="background-color: blue;"><?php echo JText::_('Close'); ?></span>
                        <?php } ?>
                    </span>
                    <span class="js-col-md-8 js-created">
                        <?php echo JText::_('Created'); ?>:
                        <?php
                        $startTimeStamp = strtotime($this->ticketdetail->created);
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
                        <br> <?php echo date("d F, Y, h:i:s A", strtotime($this->ticketdetail->created)); ?>
                    </span>
                </div>
                <div class="js-col-md-4 ticket-id">
                    <div class="js-wrapper">
                        <span class="js-col-md-6 js-col-xs-6 js-title"><?php echo JText::_('Ticket ID'); ?>&nbsp;:</span>
                        <span class="js-col-md-6 js-col-xs-6 js-value"><?php echo $this->ticketdetail->ticketid; ?></span>
                    </div>
                    <div class="js-wrapper">
                        <span class="js-col-md-6 js-col-xs-6 js-title"><?php echo JText::_('Priority'); ?>&nbsp;:</span>
                        <span class="js-col-md-6 js-col-xs-6 js-value" style="padding:0;color:#fff;text-align:center;"><div style="color:#FFFFF;background:<?php echo $this->ticketdetail->prioritycolour; ?>;"><?php echo $this->ticketdetail->priority; ?></div></span>
                    </div>
                </div>
                <div class="js-col-md-4">
                    <div class="js-wrapper">
                        <span class="js-col-md-6 js-col-xs-6 js-title"><?php echo JText::_('Last reply'); ?>&nbsp;:</span>
                        <span class="js-col-md-6 js-col-xs-6 js-value"><?php if($this->ticketdetail->lastreply) echo date($this->config['date_format'],strtotime($this->ticketdetail->lastreply)); else echo JText::_("Not Filled"); ?></span>
                    </div>
                </div>
                <div class="js-hrline"></div>
            </div>

            <div class="js-col-md-12" id="js-secondrow">
                <div class="js-col-md-8">
                    <div class="js-wrapper">
                        <span class="js-title">
                            <strong><?php echo JText::_('Subject'); ?>:</strong>
                        </span>
                        <span class="js-value-subject">
                            <?php echo $this->ticketdetail->subject; ?>
                        </span>
                    </div>
                    <div class="js-wrapper">
                        <span class="js-title">
                            <strong><?php echo JText::_('Department'); ?>:</strong>
                        </span>
                        <span class="js-value"><?php echo $this->ticketdetail->departmentname; ?></span>
                    </div>
                </div>
                <div class="js-col-md-4 js-wrapper-actions">
                    <?php $link = 'index.php?option='.$this->option.'&c=ticket&task=addnewticket&cid[]='.$this->ticketdetail->id; ?>
                    <a class="js-detal-alinks" href="<?php echo $link; ?>">
                        <img title="<?php echo JText::_('Edit Ticket'); ?>" src="components/com_jssupportticket/include/images/editticket.png">
                    </a>
                    <a class="js-detal-alinks" href="#" onclick="actioncall('<?php if ($this->ticketdetail->status == 4) echo 8; else echo 3; ?>')">
                        <?php if ($this->ticketdetail->status == 4){?><img title="<?php echo JText::_('Reopen Ticket'); ?>" src="components/com_jssupportticket/include/images/reopen.png"><?php }else{?><img title="<?php echo JText::_('Close Ticket'); ?>" src="components/com_jssupportticket/include/images/close2.png"><?php } ?>
                    </a>
                    <a class="js-detal-alinks" id="moreactions" href="#">
                        <img title="<?php echo JText::_('More options'); ?>" src="components/com_jssupportticket/include/images/down.png">
                    </a>
                </div>
            <div id="js-tk-actiondiv" style="display:none;">
                <div class="js-priority-wrapper">
                    <?php echo $this->lists['priorities']; ?>
                    <input type="button" class="button" onclick="actioncall(1)" value="<?php echo JText::_('Change Priority'); ?>">
                </div>                
            </div>
            </div>
            <div class="js-col-md-12 js-requester-info">
                <div class="js-col-md-12 js-tk-infoborder">
                    <span class="requester-title"><?php echo JText::_('Requester info'); ?></span>
                </div>
                <div class="js-col-md-4 requester-data"><img class="requester-image" src="components/com_jssupportticket/include/images/smallticketman.png"><?php echo $this->ticketdetail->name; ?></div>
                <div class="js-col-md-4 requester-data"><img class="requester-image" src="components/com_jssupportticket/include/images/email.png"><?php echo $this->ticketdetail->email; ?></div>
                <div class="js-col-md-4 requester-data"><a id="requester-showmore" href="#"><img class="js-showdetail" src="components/com_jssupportticket/include/images/showhide.png" /><?php echo JText::_('More Detail');?></a></div>
                <div id="req-moredetail" style="display:none;" class="js-col-md-12">
                    <div class="js-col-md-12 js-tk-infoborder-inner">
                        <span class="requester-title-inner"><?php echo JText::_('More Detail'); ?></span>
                    </div>    
                    <?php
                        foreach ($this->userfields as $ufield) {
                            $userfield = $ufield[0];
                            echo '<div class="js-col-md-4"><div class="requester-data-inner-border"><div class="requester-data-inner-title"><strong>' . $userfield->title . ' :</strong></div>';
                            if ($userfield->type == "checkbox") {
                                if (isset($ufield[1])) {
                                    $fvalue = $ufield[1]->data;
                                    $userdataid = $ufield[1]->id;
                                } else {
                                    $fvalue = "";
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
                                    $fvalue = "";
                                    $userdataid = "";
                                }
                            } else {
                                if (isset($ufield[1])) {
                                    $fvalue = $ufield[1]->data;
                                    $userdataid = $ufield[1]->id;
                                } else {
                                    $fvalue = "";
                                    $userdataid = "";
                                }
                            }
                            if($fvalue == "") $fvalue = JText::_('Not Filled');
                            echo '<div class="requester-data-inner-value">' . $fvalue . '</div></div></div>';
                        }
                        ?>
                   </div>
                </div>
            </div>    
            </div>
            <div class="js-col-md-12 js-floatleft">
                <div class="js-tk-subheading">
                    <?php echo JText::_('Ticket Thread'); ?>
                </div>
                <div id="js-ticket-threads">
                    <div class="js-tk-pic">
                        <img src="components/com_jssupportticket/include/images/ticketman.png"/>
                    </div>
                    <div class="js-tk-message">
                        <div id="pointer"><img src="components/com_jssupportticket/include/images/corner.png"/></div>
                        <div class="js-tk-row"><?php echo JText::_('Posted By').' : '.$this->ticketdetail->name; ?><span class="timedate"><?php $replyby = date("l F d, Y, h:i:s", strtotime($this->ticketdetail->created)); echo ' ( '. $replyby.' )'; ?></span></div>
                        <div class="message-area"><?php echo $this->ticketdetail->message; ?></div>
                        <?php
                        if (isset($this->ticketattachment[0]->filename) && $this->ticketattachment[0]->filename <> '') {
                            foreach ($this->ticketattachment as $row) {
                                if ($row->filename && $row->filename <> '') {
                                    $datadirectory = $this->config['data_directory'];
                                    $path = $datadirectory . '/attachmentdata/ticket/ticket_' . $row->id . '/' . $row->filename;
                                    $path = str_replace(' ', '%20', $path);
                                    echo '<div class="js-col-md-4 js-attachment-file">';
                                        if($row->filename && $row->filename <> ''){
                                            $datadirectory = $this->config['data_directory'];
                                            $path = '../' . $datadirectory . '/attachmentdata/ticket/ticket_' . $row->id . '/' . $row->filename;
                                            $path = str_replace(' ', '%20', $path);
                                            echo "<img src='components/com_jssupportticket/include/images/clip.png'><a target='_blank' href=" . $path . ">"
                                            . $row->filename . "&nbsp(" . round($row->filesize, 2) . " KB)" . "</a>";
                                        }
                                    echo "</div>";
                                 }
                            }
                        } ?>
                    </div>
                </div>
                <?php
                //$k = 0;
                for ($i = 0, $n = count($this->ticketreplies); $i < $n; $i++) {
                    $row = & $this->ticketreplies[$i]; ?>
                    <div id="js-ticket-threads">
                        <div class="js-tk-pic">
                            <img src="components/com_jssupportticket/include/images/ticketman.png"/>
                        </div>
                        <div class="js-tk-message">
                            <div id="pointer"><img src="components/com_jssupportticket/include/images/corner.png"/></div>
                            <div class="js-tk-row"><?php echo JText::_('Posted By').' : '; if($row->name) echo $row->name; else echo $this->ticketdetail->name; ?><span class="timedate"><?php $replyby = date("l F d, Y, h:i:s", strtotime($row->created)); echo ' ( '. $replyby.' )'; ?></span></div>
                            <div class="message-area"><?php echo $row->message; ?></div>
                            <div class="js-col-md-12 js-col-xs-12">
                            <?php
                            $count = $row->count;
                            if ($count >= 1) {
                                $outdex = $i + $count;
                                for ($j = $i; $j < $outdex; $j++) {
                                    if ($row->filename && $row->filename <> '') {
                                        $datadirectory = $this->config['data_directory'];
                                        $path = $datadirectory . '/attachmentdata/ticket/ticket_' . $row->ticketid . '/' . $row->filename;
                                        $path = str_replace(' ', '%20', $path);
                                        echo '<div class="js-col-md-4 js-attachment-file">';
                                        if($row->filename && $row->filename <> ''){
                                            $datadirectory = $this->config['data_directory'];
                                            $path = '../' . $datadirectory . '/attachmentdata/ticket/ticket_' . $row->ticketid . '/' . $row->filename;
                                            $path = str_replace(' ', '%20', $path);
                                            echo "<img src='components/com_jssupportticket/include/images/clip.png'><a target='_blank' href=" . $path . ">"
                                            . $row->filename . "&nbsp(" . round($row->filesize, 2) . " KB)" . "</a>";
                                        }
                                    echo "</div>";
                             }
                                    $row = & $this->ticketreplies[$j + 1];
                                }
                                $i = $outdex - 1;
                            } ?>
                            </div>
                        </div>
                    </div> 
            <?php } ?>
            </div>
            <div class="js-col-md-12 js-floatleft">
                <div id="postreply">
                    <div class="js-tk-subheading">
                        <?php echo JText::_('Post Reply'); ?>
                    </div>
                    <div class="js-col-xs-12 js-col-md-2 js-title"><?php echo JText::_('Response'); ?>:&nbsp;<font color="red">*</font></div>
                    <div class="js-col-xs-12 js-col-md-10 js-value"><?php $editor = JFactory::getEditor(); echo $editor->display('responce', '', '550', '300', '60', '20', false); ?></div>
                    <div class="js-col-xs-12 js-col-md-2 js-title"><?php echo JText::_('Attachments'); ?>:&nbsp;</div>
                    <div class="js-col-xs-12 js-col-md-10 js-value">
                        <div id="js-attachment-files" class="js-attachment-files">
                            <span class="js-value-text">
                                <input type="file" class="inputbox" name="filename[]" onchange="uploadfile(this, '<?php echo $this->config["filesize"]; ?>', '<?php echo $this->config["fileextension"]; ?>');" size="20" maxlenght='30'/>
                                <span class='js-attachment-remove'></span>
                            </span>
                        </div>
                        <div id="js-attachment-option">
                            <span class="js-attachment-ins">
                                <small><?php echo JText::_('Maximum file size') . ' (' . $this->config['filesize']; ?>KB)<br><?php echo JText::_('File extension type') . ' (' . $this->config['fileextension'] . ')'; ?></small>
                            </span>
                            <span id="js-attachment-add"><?php echo JText::_('Add more file'); ?></span>
                        </div>            
                    </div>
                    <div class="js-col-xs-12 js-col-md-2 js-title"><?php echo JText::_('Ticket Status'); ?>:&nbsp;</div>
                    <div class="js-col-xs-12 js-col-md-10 js-value"><input type="checkbox" name="replystatus" id ="replystatus" value="4"/> <?php echo JText::_('CLose on reply'); ?></div>
                    <div class="js-col-xs-12 js-col-md-12"><div id="js-submit-btn"><input  class="button setfloatoverride" type="button" onclick="validate_form(document.adminForm)" value="<?php echo JText::_('Post Reply'); ?>"/></div></div>
                </div>
            </div>
            <input type="hidden" name="id" value="<?php echo $this->ticketdetail->id; ?>" />
            <input type="hidden" name="email" value="<?php echo $this->ticketdetail->email; ?>" />
            <input type="hidden" name="email_ban" id="email_ban" value="<?php echo $this->isemailban; ?>" />
            <input type="hidden" name="lastreply" value="<?php echo $this->ticketdetail->lastreply; ?>" />
            <input type="hidden" id="staffid" name="staffid" value="<?php echo JSSupportTicketCurrentUser::getInstance()->getId(); ?>" />

            <input type="hidden" id="callaction" name="callaction" value="" />
            <input type="hidden" id="callfrom" name="callfrom" value="postreply" />
            <input type="hidden" id="option" name="option" value="<?php echo $this->option; ?>" />
            <input type="hidden" id="c" name="c" value="ticket" />
            <input type="hidden" id="view" name="view" value="ticket" />
            <input type="hidden" id="layout" name="layout" value="tickets" />
            <input type="hidden" id="task" name="task" value="actionticket" />
            <input type="hidden" id="check" name="check" value="0" />
            <input type="hidden" id="boxchecked" name="boxchecked" value="0" />
            <input type="hidden" id="created" name="created" value="<?php echo $curdate = date('Y-m-d H:i:s'); ?>"/>
        </form>

    </div>
</div>
<div id="js-tk-copyright">
    <img width="85" src="http://www.joomsky.com/logo/jssupportticket_logo_small.png">&nbsp;Powered by <a target="_blank" href="http://www.joomsky.com">Joom Sky</a><br/>
    &copy;Copyright 2008 - <?php echo date('Y'); ?>, <a target="_blank" href="http://www.burujsolutions.com">Buruj Solutions</a>
</div>


<script type="text/javascript">

    function actioncall(value) {
        jQuery('#callfrom').val('action');
        jQuery('#callaction').val(value);
        document.adminForm.submit();
    }
    
    function editResponce(id) {
        var rsrc = 'responce_' + id;
        var src = 'responce_edit_' + id;
        var esrc = 'editor_responce_' + id;
        showhide(rsrc, 'none');
        showhide(src, 'block');
        jQuery('#' + src).html("Loading...");
        jQuery.post('index.php?option=com_jssupportticket&c=ticket&task=editresponce&id=' + id, {data: id}, function (data) {
            jQuery('#' + src).html(data); //retuen value
            if (!tinyMCE.get(esrc)) { // toggle editor
                tinyMCE.execCommand('mceToggleEditor', false, esrc);
                return false;
            }
        });
    }

    function saveResponce(id) {
        var esrc = 'editor_responce_' + id;
        if (!tinyMCE.get(esrc)) { // check toggle
            alert("Please toggle editor");
        } else {
            var contant = tinyMCE.get(esrc).getContent();
            var rsrc = 'responce_' + id;
            var src = 'responce_edit_' + id;
            showhide(rsrc, 'block');
            showhide(src, 'none');


            jQuery('#' + rsrc).html("Saving...");
            var arr = new Array();
            arr[0] = id;
            arr[1] = contant;
            jQuery.ajax({
                type: "POST",
                url: "index.php?option=com_jssupportticket&c=ticket&task=saveresponceajax&id=" + arr[0] + "&val=" + arr[1],
                data: arr,
                success: function (data) {
                    if (data == 1) {
                        jQuery('#' + rsrc).html(contant);
                    } else if (data == 10) {
                        jQuery('#' + rsrc).html(data);
                    } else {
                        jQuery('#' + rsrc).html(data);
                    }
                    tinymce.remove(tinyMCE.get(esrc));

                }
            });
        }
    }

    function closeResponce(id) {
        var rsrc = 'responce_' + id;
        var src = 'responce_edit_' + id;
        var esrc = 'editor_responce_' + id;
        showhide(rsrc, 'block');
        showhide(src, 'none');
        tinymce.remove(tinyMCE.get(esrc));

    }
    
    function deleteResponce(id) {
        if (confirm("<?php echo JText::_('Are you sure delete'); ?>")) {

            var rsrc = 'responce_' + id;
            jQuery('#' + rsrc).html("Deleting...");

            jQuery.post('index.php?option=com_jssupportticket&c=ticket&task=deleteresponceajax&id=' + id, {data: id}, function (data) {
                jQuery('#' + src).html(data);
            });
        }
    }
    function showhide(layer_ref, state) {
        if (state == 'none') {
            jQuery('div#' + layer_ref).hide('slow');
        } else if (state == 'block') {
            jQuery('div#' + layer_ref).show('slow');

        }
    }
</script>
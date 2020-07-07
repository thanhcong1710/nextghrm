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
jimport('joomla.html.pane');
JHTML::_('behavior.formvalidation');
JHTML::_('behavior.calendar');
$document = JFactory::getDocument();
$document->addScript('components/com_jssupportticket/include/js/file/file_validate.js');
JText::script('Error file size too large');
JText::script('Error file extension mismatch');
?>

<script type="text/javascript">
// for joomla 1.6
    Joomla.submitbutton = function (task) {
        if (task == '') {
            return false;
        } else {
            if (task == 'saveticket' || task == 'saveticketandnew' || task == 'saveticketsave') {
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

    function validate_form(f)
    {
        if (document.formvalidator.isValid(f)) {
            f.check.value = '<?php if ((JVERSION == '1.5') || (JVERSION == '2.5')) echo JUtility::getToken(); else echo JSession::getFormToken(); ?>';//send token
        } else {
            alert('<?php echo JText::_('Some values are not acceptable please retry'); ?>');
            return false;
        }
        return true;
    }
</script>
<div id="userpopupblack" style="display:none;"></div>
<div id="userpopup" style="display:none;">
    <div class="js-row">
        <form id="userpopupsearch">
            <div class="search-center">
                <div class="search-center-heading"><?php echo JText::_('Select user'); ?><span class="close"></span></div>
                <div class="js-col-md-12">
                    <div class="js-col-xs-12 js-col-md-3 js-search-value">
                        <input type="text" name="username" id="username" placeholder="<?php echo JText::_('Username'); ?>" />
                    </div>
                    <div class="js-col-xs-12 js-col-md-3 js-search-value">
                        <input type="text" name="name" id="name" placeholder="<?php echo JText::_('Name'); ?>" />
                    </div>
                    <div class="js-col-xs-12 js-col-md-3 js-search-value">
                        <input type="text" name="emailaddress" id="emailaddress" placeholder="<?php echo JText::_('Email Address'); ?>"/>
                    </div>
                    <div class="js-col-xs-12 js-col-md-3 js-search-value-button">
                        <div class="js-button">
                            <input type="submit" value="<?php echo JText::_('Search'); ?>" />
                        </div>
                        <div class="js-button">
                            <input type="submit" onclick="this.form.getElementById('name').value = '';this.form.getElementById('username').value = ''; this.form.getElementById('emailaddress').value = '';" value="<?php echo JText::_('Reset'); ?>" />
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div id="records">
        <div id="records-inner">
            <div class="js-staff-searc-desc">
                <?php echo JText::_('Use Search Feature To Select The User'); ?>
            </div>
        </div>    
    </div>          
</div>
<div id="js-tk-admin-wrapper">
    <div id="js-tk-leftmenu">
        <?php include_once('components/com_jssupportticket/views/menu.php'); ?>
    </div>
    <div id="js-tk-cparea">
        <div id="js-tk-heading"><h4><img id="js-admin-responsive-menu-link" src="components/com_jssupportticket/include/images/c_p/left-icons/menu.png" /><?php echo JText::_('Add Ticket'); ?></h4></div> 
        <form action="index.php" method="POST" enctype="multipart/form-data" name="adminForm" id="adminForm">
            <?php
            $count = count($this->fieldsordering);
            $i = 0; // for userfield numbering
            foreach ($this->fieldsordering AS $field) { ?>
                <?php switch ($field->field) {
                        case 'users':
                            if ($field->published == 1) {  ?>
                                <div class="js-col-md-12">
                                    <div class="js-col-xs-12 js-col-md-2 js-title"><label for="email"><?php echo JText::_('Username'); ?><?php if($field->required == 1) echo ' <span style="color:red;">*</span>'; ?></label></div>
                                    <div class="js-col-xs-12 js-col-md-10 js-value">
                                        <?php if (isset($this->editticket->uid) && $this->editticket->uid!=0) {?>
                                            <div id="username-div"><input type="text" class="<?php if($field->required == 1) echo ' required'; ?>" value="<?php if(isset($this->data['username-text'])) echo $this->data['username-text']; else echo $this->editticket->name; ?>" id="username-text" name="username-text" readonly="readonly" /></div>
                                            <?php } else {
                                            ?>
                                            <div id="username-div"></div><input type="text" value="" id="username-text" name="username-text" value="<?php if(isset($this->data['username-text'])) echo $this->data['username-text']; ?>" readonly="readonly" /><a href="#" id="userpopup"><?php echo JText::_('Select User'); ?></a>
                                            <?php
                                        }
                                        ?>              
                                    </div>
                                </div>
                                <?php 
                            } 
                        break;
                    case 'emailaddress': ?>
                        <?php if ($field->published == 1) { ?>
                        <div class="js-col-md-12">
                            <div class="js-col-xs-12 js-col-md-2 js-title">
                                    <label for="email"><?php echo JText::_('Email address'); ?>:&nbsp;<font color="red">*</font></label>
                            </div>
                            <div class="js-col-xs-12 js-col-md-10 js-value">
                                <input class="inputbox required validate-email" type="text" id="email" name="email" size="40" maxlength="255" value="<?php if(isset($this->data['email'])) echo $this->data['email']; elseif (isset($this->editticket)) echo $this->editticket->email; ?>" />
                            </div>
                        </div>
                        <?php 
                    } ?>
                    <?php break;
                    case 'fullname':
                        ?>
                    <?php if ($field->published == 1) { ?>
                            <div class="js-col-md-12">
                                <div class="js-col-xs-12 js-col-md-2 js-title">
                                    <label for="name"><?php echo JText::_('Full Name'); ?>:&nbsp;<font color="red">*</font></label>
                                </div>
                                <div class="js-col-xs-12 js-col-md-10 js-value">
                                    <input class="inputbox required" type="text" name="name" id="name" size="40" maxlength="255" value="<?php if(isset($this->data['name'])) echo $this->data['name']; elseif (isset($this->editticket)) echo $this->editticket->name; ?>" />
                                </div>
                            </div>
                        <?php } ?>
                        <?php break;
                    case 'phone':
                        ?>
                        <?php if ($field->published == 1) { ?>     
                        <div class="js-col-md-12">
                                <div class="js-col-xs-12 js-col-md-2 js-title">
                                    <label for="phone"><?php echo JText::_('Phone No'); ?>:<?php if($field->required == 1) echo ' <span style="color:red;">*</span>'; ?></label>
                                </div>
                                <div class="js-col-xs-12 js-col-md-10 js-value">
                                    <input class="inputbox <?php if($field->required == 1) echo ' required'; ?>" type="text" name="phone" id="phone" size="40" maxlength="255" value="<?php if(isset($this->data['phone'])) echo $this->data['phone']; elseif (isset($this->editticket)) echo $this->editticket->phone; ?>" />
                                </div>
                        </div>
                        <div class="js-col-md-12">
                                <div class="js-col-xs-12 js-col-md-2 js-title">
                                    <label for="phoneext"><?php echo JText::_('Phone Ext'); ?>:&nbsp;</label>
                                </div>
                                <div class="js-col-xs-12 js-col-md-10 js-value">
                                    <input class="inputbox" type="text" name="phoneext" id="phoneext" size="5" maxlength="255" value="<?php if(isset($this->data['phoneext'])) echo $this->data['phoneext']; elseif (isset($this->editticket)) echo $this->editticket->phoneext; ?>" />
                                </div>
                        </div>
                        <?php } ?>
                        <?php break;
                    case 'department':
                        ?>
                        <?php if ($field->published == 1) { ?> 
                        <div class="js-col-md-12">
                            <div class="js-col-xs-12 js-col-md-2 js-title">
                                <label for="departmentid"><?php echo JText::_('Department'); ?>:<?php if($field->required == 1) echo ' <span style="color:red;">*</span>'; ?></label>
                            </div>
                            <div class="js-col-xs-12 js-col-md-10 js-value">
                                <?php echo $this->lists['departments']; ?>
                            </div>                
                        </div>                       
                        <?php } ?>
                        <?php break;
                    case 'priority':
                        ?>
                        <?php if ($field->published == 1) { ?>   
                        <div class="js-col-md-12">
                            <div class="js-col-xs-12 js-col-md-2 js-title">
                                <label for="priorityid"><?php echo JText::_('Priority'); ?>:&nbsp;<font color="red">*</font></label>
                            </div>
                            <div class="js-col-xs-12 js-col-md-10 js-value">
                                <?php echo $this->lists['priorities']; ?>
                            </div>
                        </div>
                        <?php } ?>
                        <?php break;
                    case 'subject':
                        ?>
                        <?php if ($field->published == 1) { ?>
                        <div class="js-col-md-12">
                        <div class="js-col-xs-12 js-col-md-2 js-title">
                            <label for="subject"><?php echo JText::_('Subject'); ?>:&nbsp;<font color="red">*</font></label>
                        </div>
                        <div class="js-col-xs-12 js-col-md-10 js-value">
                            <input style="width:100%;" class="inputbox required" type="text" name="subject" id="subject" size="40" maxlength="255" value="<?php if(isset($this->data['subject'])) echo $this->data['subject']; elseif (isset($this->editticket)) echo $this->editticket->subject; ?>" />
                        </div>    
                        </div>
                            <?php } ?>
                                <?php break;
                case 'issuesummary': ?>
                        <div class="js-col-md-12">
                        <div class="js-col-xs-12 js-col-md-2 js-title">
                            <label for="message"><?php echo JText::_('Issue Summary'); ?>:&nbsp;<font color="red">*</font></label>
                        </div>
                        <div class="js-col-xs-12 js-col-md-10 js-value">
                            <?php $message = isset($this->editticket) ? $this->editticket->message : ''; ?>
                            <?php $editor = JFactory::getEditor(); echo $editor->display('message', $message, '550', '300', '60', '20', false); ?>
                        </div>
                                </div>
                <?php break;
                case 'attachments': ?>
                    <?php if ($field->published == 1) { ?>
                    <div class="js-col-md-12">
                    <div class="js-col-xs-12 js-col-md-2 js-title">
                        <label for="attachment"><?php echo JText::_('Attachments'); ?>:&nbsp;</label>
                    </div>
                    <div class="js-col-xs-12 js-col-md-10 js-value">
                        <div id="js-attachment-files" class="js-attachment-files">
                            <span class="js-value-text">
                                <input type="file" class="inputbox" name="filename[]" onchange="uploadfile(this, '<?php echo $this->config["filesize"]; ?>', '<?php echo $this->config["fileextension"]; ?>');" size="20" maxlenght='30'/>
                                <span class='js-attachment-remove'></span>
                            </span>
                        </div>
                        <div id="js-attachment-option">
                            <span class="js-attachment-ins">
                                <small><?php echo JText::_('Maximum File Size') . ' (' . $this->config['filesize']; ?>KB)<br><?php echo JText::_('File Extension Type') . ' (' . $this->config['fileextension'] . ')'; ?></small>
                            </span>
                            <span id="js-attachment-add"><?php echo JText::_('Add More File'); ?></span>
                        </div>
                            <?php
                            if (!empty($this->attachments)) {
                                $ticketid = isset($this->editticket->id) ? $this->editticket->id : '' ;
                                foreach ($this->attachments AS $attachment) {                                    
                                    echo '
                                        <div class="js_ticketattachment">' . $attachment->filename . ' ( ' . $attachment->filesize . ' ) ' . '<a href="index.php?option=com_jssupportticket&c=ticket&task=deleteattachment&id=' . $attachment->id . '&ticketid=' . $ticketid. '">' . JText::_('Delete Attachment') . '</a></div>';
                                }
                            }
                            ?>

                    </div>
                    </div>
                                <?php } ?>
                                <?php break;
                case 'status':
                    ?>
                    <?php if ($field->published == 1) { ?>
                    <div class="js-col-md-12">
                    <div class="js-col-xs-12 js-col-md-2 js-title">
                        <label for="active"> <?php echo JText::_('Status'); ?>:<?php if($field->required == 1) echo ' <span style="color:red;">*</span>'; ?></label>
                    </div>
                    <div class="js-col-xs-12 js-col-md-10 js-value">
                        <input type="radio" id="active" value="<?php if (isset($this->editticket->status)) echo $this->editticket->status; else echo 0; ?>" name="status"<?php if (isset($this->editticket)) {if ($this->editticket->status) echo "checked=''"; } else echo "checked=''"; ?> /><label for="active"><?php echo JText::_('Active'); ?></label>
                        <input type="radio" id="disable" value="" name="status"<?php if (isset($this->editticket)) {if ($this->editticket->status == "") echo "checked=''"; } ?> /><label for="disable"><?php echo JText::_('Disabled'); ?></label>
                    </div>
                            </div>
                        <?php } ?>
                        <?php
                        break;
                default:
                    if (isset($this->userfields)) {
                        foreach ($this->userfields as $ufield) {
                            if($field->field == $ufield[0]->id) {
                                $userfield = $ufield[0];
                                $i++;
                                echo "<div class='js-col-md-12'><div class='js-col-xs-12 js-col-md-2 js-title'>";
                                if ($field->required == 1) {
                                    echo "<label id=".$userfield->name . "msg for=$userfield->name>$userfield->title :&nbsp;<font color='red'>*</font></label>";
                                    if ($userfield->type == 'emailaddress')
                                        $cssclass = "class ='inputbox required validate-email'";
                                    else
                                        $cssclass = "class ='inputbox required' ";
                                }else{
                                    echo $userfield->title . ":&nbsp;";
                                    if ($userfield->type == 'emailaddress')
                                        $cssclass = "class ='inputbox validate-email' ";
                                    else
                                        $cssclass = "class='inputbox' ";
                                }
                                echo "</div><div class='js-col-xs-12 js-col-md-10 js-value'>";

                                $readonly = $userfield->readonly ? ' readonly="readonly"' : '';
                                $maxlength = $userfield->maxlength ? 'maxlength="' . $userfield->maxlength . '"' : '';
                                if (isset($ufield[1])) {
                                    $fvalue = $ufield[1]->data;
                                    $userdataid = $ufield[1]->id;
                                } else {
                                    $fvalue = "";
                                    $userdataid = "";
                                }
                                echo '<input type="hidden" id="userfields_' . $i . '_id" name="userfields_' . $i . '_id"  value="' . $userfield->id . '"  />';
                                echo '<input type="hidden" id="userdata_' . $i . '_id" name="userdata_' . $i . '_id"  value="' . $userdataid . '"  />';
                                switch ($userfield->type) {
                                    case 'text':
                                        echo '<input type="text" id="userfields_' . $i . '" name="userfields_' . $i . '" size="' . $userfield->size . '" value="' . $fvalue . '" ' . $cssclass . $maxlength . $readonly . ' />';
                                        break;
                                    case 'emailaddress':
                                        echo '<input type="text" id="userfields_' . $i . '" name="userfields_' . $i . '" size="' . $userfield->size . '" value="' . $fvalue . '" ' . $cssclass . $maxlength . $readonly . ' />';
                                        break;
                                    case 'date':
                                        $userfieldid = 'userfields_' . $i;
                                        $userfieldid = "'" . $userfieldid . "'";
                                        if($readonly != '') $readonly = 'readonly';
                                        if($field->required == 1) $required = 'required'; else $required = '';
                                        echo JHTML::_('calendar', $fvalue, 'userfields_' . $i, 'userfields_' . $i, '%Y-%m-%d', array('class' => 'inputbox '.$required, 'size' => '10', 'maxlength' => '19','readonly'=>$readonly));
                                        break;
                                    case 'textarea':
                                        echo '<textarea name="userfields_' . $i . '" id="userfields_' . $i . '_field" cols="' . $userfield->cols . '" rows="' . $userfield->rows . '" ' . $readonly .$cssclass. '>' . $fvalue . '</textarea>';
                                        break;
                                    case 'checkbox':
                                        if($readonly != '') $readonly = 'disabled="disabled"';
                                        echo '<input type="checkbox" name="userfields_' . $i . '" id="userfields_' . $i . '_field" value="1" ' . 'checked="checked"' .$readonly.$cssclass. '/>';
                                        break;
                                    case 'select':
                                        if($readonly != '') $readonly = 'disabled="disabled"';
                                        $htm = '<select name="userfields_' . $i . '" id="userfields_' . $i . '" '.$readonly.$cssclass.'>';
                                        if (isset($ufield[2])) {
                                            foreach ($ufield[2] as $opt) {
                                                if ($opt->id == $fvalue)
                                                    $htm .= '<option value="' . $opt->id . '" selected="yes">' . $opt->fieldtitle . ' </option>';
                                                else
                                                    $htm .= '<option value="' . $opt->id . '">' . $opt->fieldtitle . ' </option>';
                                            }
                                        }
                                        $htm .= '</select>';
                                        echo $htm;
                                    break;
                                }
                                echo '</div></div>';
                            }
                            $i++;
                        }
                        echo '<input type="hidden" id="userfields_total" name="userfields_total"  value="' . $i . '"  />';
                    }break;
            }
                ?>
<?php }  // end of fieldsordering foreach  ?>       
                <div class="js-col-xs-12 js-col-md-12"><div id="js-submit-btn"><input type="submit" class="button" name="submit_app" onclick="return validate_form(document.adminForm)" value="<?php echo JText::_('Save Ticket'); ?>" /></div></div>
                <input type="hidden" name="id" id="id" value="<?php if (isset($this->editticket)) echo $this->editticket->id; ?>" />
                <input type="hidden" name="isoverdue" id="isoverdue" value="<?php if (isset($this->editticket)) echo $this->editticket->isoverdue; ?>" />
                <input type="hidden" name="ticketid" id="ticketid" value="<?php if (isset($this->editticket)) echo $this->editticket->ticketid; ?>" />
                <input type="hidden" name="c" id="c" value="ticket" />
                <input type="hidden" name="task" id="task" value="saveticket" />
                <input type="hidden" name="uid" id="uid" value="<?php if(isset($this->editticket)) echo $this->editticket->uid; ?>" />
                <input type="hidden" name="view" id="view" value="ticket" />
                <input type="hidden" name="layout" id="layout" value="formticket" />
                <input type="hidden" name="check" id="check" value="" />
                <input type="hidden" name="option" id="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" name="created" id="created" value="<?php if (!isset($this->ticket)) echo $curdate = date('Y-m-d H:i:s'); else echo $this->ticket->created; ?>"/>
                <input type="hidden" name="update" id="update" value="<?php if (isset($this->ticket)) echo $update = date('Y-m-d H:i:s'); ?>"/>
        </form>
    </div>
</div>
<div id="js-tk-copyright">
    <img width="85" src="http://www.joomsky.com/logo/jssupportticket_logo_small.png">&nbsp;Powered by <a target="_blank" href="http://www.joomsky.com">Joom Sky</a><br/>
    &copy;Copyright 2008 - <?php echo date('Y'); ?>, <a target="_blank" href="http://www.burujsolutions.com">Buruj Solutions</a>
</div>

<script>
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
    jQuery(document).ready(function () {
        jQuery("a#userpopup").click(function (e) {
            e.preventDefault();
            jQuery("div#userpopupblack").show();
            jQuery("div#userpopup").slideDown('slow');
        });
        setUserLink();
        function setUserLink() {
            jQuery("a.js-userpopup-link").each(function () {
                var anchor = jQuery(this);
                jQuery(anchor).click(function (e) {
                    var id = jQuery(this).attr('data-id');
                    var name = jQuery(this).html();
                    var email = jQuery(this).attr('data-email');
                    var displayname = jQuery(this).attr('data-name');
                    jQuery("input#username-text").val(name);
                    if(jQuery('input#name').val() == ''){
                        jQuery('input#name').val(displayname);
                    }
                    if(jQuery('input#email').val() == ''){
                        jQuery('input#email').val(email);
                    }
                    jQuery("input#uid").val(id);
                    jQuery("div#userpopup").slideUp('slow', function () {
                        jQuery("div#userpopupblack").hide();
                    });
                });
            });
        }
        jQuery("form#userpopupsearch").submit(function (e) {
            e.preventDefault();
            var name = jQuery("input#name").val();
            var emailaddress = jQuery("input#emailaddress").val();
            var username = jQuery("input#username").val();
            jQuery.post("index.php?option=com_jssupportticket&c=jssupportticket&task=getusersearchajax",{name: name,username:username, emailaddress: emailaddress}, function (data) {
                if (data) {
                    jQuery("div#records").html(data);
                    setUserLink();
                }
            });//jquery closed
        });
        jQuery("span.close, div#userpopupblack").click(function (e) {
            jQuery("div#userpopup").slideUp('slow', function () {
                jQuery("div#userpopupblack").hide();
            });

        });
    });
</script>

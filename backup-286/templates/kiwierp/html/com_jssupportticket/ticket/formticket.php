<?php
/**
 * @Copyright Copyright (C) 2015 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:     Buruj Solutions
  + Contact:     www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:  May 22, 2015
  ^
  + Project:    JS Tickets
  ^
 */
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.calendar');
JHTML::_('behavior.formvalidation');
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root(true) . '/templates/kiwierp/css/ticket.css');
$document->addScript('administrator/components/com_jssupportticket/include/js/file/file_validate.js');
JText::script('Error file size too large');
JText::script('Error file extension mismatch');
$this->lt = 0;

$user = JFactory::getUser();
$user_email = $user->get('email');
$user_fullname = $user->get('name');
?>
<script language="javascript">
        function myValidate(f) {
                if (document.formvalidator.isValid(f)) {
                        f.check.value = '<?php
if (JVERSION < 3)
        echo JUtility::getToken();
else
        echo JSession::getFormToken();
?>';//send token
                } else {
                        alert('<?php echo JText::_('Some values are not acceptable please retry'); ?>');
                        return false;
                }
                return true;
        }

        function saveticket(formobj) {
                var formObjdata = {};
                var inputs = jQuery('#adminForm').serializeArray();
                jQuery.each(inputs, function (i, input) {
                        formObjdata[input.name] = input.value;
                });
                var xhr;
                try {
                        xhr = new ActiveXObject('Msxml2.XMLHTTP');
                }
                catch (e) {
                        try {
                                xhr = new ActiveXObject('Microsoft.XMLHTTP');
                        }
                        catch (e2) {
                                try {
                                        xhr = new XMLHttpRequest();
                                }
                                catch (e3) {
                                        xhr = false;
                                }
                        }
                }
                xhr.onreadystatechange = function () {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                                jQuery('#message_text').html(xhr.responseText);
                        }
                }
                alert(xhr.readyState + " " + xhr.status);
                xhr.open("GET", "index.php?option=com_jssupportticket&c=ticket&task=saveticket&data=" + formobj, true);
                xhr.send(null);
        }
</script>
<?php
if ($this->config['offline'] == '1') {
        messagesLayout::getSystemOffline($this->config['title'], $this->config['offline_text']);
} else {
        ?>
        <div class="kiwi-ticket">
                <div class="mytickets">
                        <div class="row">
                                <div class="col-md-3">
                                        <?php require_once 'menu.php'; ?>
                                </div>
                                <div class="col-md-9">
                                        <form action="<?php echo JUri::root(); ?>" method="post" name="adminForm" id="adminForm" class="form ticket-form form-vertical" enctype="multipart/form-data">
                                                <?php
                                                $i = 0; // for userfields numbering
                                                $fieldcounter = 0;
                                                foreach ($this->fieldsordering AS $field) {
                                                        switch ($field->field) {
                                                                case 'emailaddress':
                                                                        if ($field->published == 1) {
                                                                                if ($fieldcounter % 2 == 0) {
                                                                                        if ($fieldcounter != 0) {
                                                                                                echo '</div>';
                                                                                        }
                                                                                        echo '<div class="row">';
                                                                                }
                                                                                $fieldcounter++;
                                                                                ?>
                                                                                <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                                <label for="email" class="control-label"><?php echo JText::_('Email address'); ?>&nbsp;<font color="red">*</font></label>
                                                                                                <div class="input-group">
                                                                                                        <div class="input-group-addon"><span class="fa fa-envelope"></span></div>
                                                                                                        <input class="inputbox required validate-email form-control" placeholder="<?php echo JText::_('Email address'); ?>" type="text" name="email" id="email" size="40" maxlength="255" value="<?php
                                                                                                        if (isset($this->data['email']))
                                                                                                                echo $this->data['email'];
                                                                                                        elseif (isset($this->email))
                                                                                                                echo $this->email;
                                                                                                        elseif ($user_email)
                                                                                                                echo $user_email;
                                                                                                        ?>" />

                                                                                                </div>

                                                                                        </div>
                                                                                </div>
                                                                                <?php
                                                                        }
                                                                        break;
                                                                case 'fullname':
                                                                        if ($field->published == 1) {
                                                                                if ($fieldcounter % 2 == 0) {
                                                                                        if ($fieldcounter != 0) {
                                                                                                echo '</div>';
                                                                                        }
                                                                                        echo '<div class="row">';
                                                                                }
                                                                                $fieldcounter++;
                                                                                ?>
                                                                                <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                                <label for="name" class="control-label"><?php echo JText::_('Full Name'); ?>&nbsp;<font color="red">*</font></label>
                                                                                                <div class="input-group">
                                                                                                        <div class="input-group-addon"><span class="fa fa-user"></span></div>
                                                                                                        <input class="inputbox required form-control" placeholder="<?php echo JText::_('Full Name'); ?>" type="text" name="name" id="name"size="40" maxlength="255" value=
                                                                                                               "<?php
                                                                                                               if (isset($this->data['name']))
                                                                                                                       echo $this->data['name'];
                                                                                                               elseif ($user_fullname)
                                                                                                                       echo $user_fullname;
                                                                                                               ?>" />

                                                                                                </div>
                                                                                        </div>
                                                                                </div>

                                                                                <?php
                                                                        }
                                                                        break;
                                                                case 'phone':
                                                                        if ($field->published == 1) {
                                                                                if ($fieldcounter % 2 == 0) {
                                                                                        if ($fieldcounter != 0) {
                                                                                                echo '</div>';
                                                                                        }
                                                                                        echo '<div class="row">';
                                                                                }
                                                                                $fieldcounter++;
                                                                                ?>
                                                                                <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                                <label for="phone" class="control-label"><?php echo JText::_('Phone No'); ?>&nbsp;</label>
                                                                                                <div class="input-group">
                                                                                                        <div class="input-group-addon"><span class="fa fa-phone"></span></div>
                                                                                                        <input class="inputbox form-control" placeholder="<?php echo JText::_('Phone No'); ?>" type="text" name="phone" id="phone" size="40" maxlength="255" value="<?php if (isset($this->data['phone'])) echo $this->data['phone']; ?>" />
                                                                                                </div>
                                                                                        </div>
                                                                                </div>
                                                                                <?php
                                                                                if ($fieldcounter % 2 == 0) {
                                                                                        if ($fieldcounter != 0) {
                                                                                                echo '</div>';
                                                                                        }
                                                                                        echo '<div class="row">';
                                                                                }
                                                                                $fieldcounter++;
                                                                                ?>
                                                                                <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                                <label for="phoneext" class="control-label"><?php echo JText::_('Phone Ext'); ?>&nbsp;</label>
                                                                                                <div class="input-group">
                                                                                                        <div class="input-group-addon"><span class="fa fa-phone"></span></div>
                                                                                                        <input class="inputbox form-control" placeholder="<?php echo JText::_('Phone Ext'); ?>" type="text" name="phoneext" id="phoneext" size="5" maxlength="255" value="<?php if (isset($this->data['phoneext'])) echo $this->data['phoneext']; ?>" />

                                                                                                </div>

                                                                                        </div>
                                                                                </div>

                                                                                <?php
                                                                        }
                                                                        break;
                                                                case 'department':
                                                                        if ($field->published == 1) {
                                                                                if ($fieldcounter % 2 == 0) {
                                                                                        if ($fieldcounter != 0) {
                                                                                                echo '</div>';
                                                                                        }
                                                                                        echo '<div class="row">';
                                                                                }
                                                                                $fieldcounter++;
                                                                                ?>
                                                                                <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                                <label for="departmentid" class="control-label"><?php echo JText::_('Department'); ?></label>
                                                                                                <div class="input-group">
                                                                                                        <div class="input-group-addon"><span class="fa fa-life-ring"></span></div>
                                                                                                        <?php
                                                                                                        $this->lists['departments'] = str_replace('class="inputbox"', 'class="inputbox form-control"', $this->lists['departments']);
                                                                                                        echo $this->lists['departments'];
                                                                                                        ?>
                                                                                                </div>

                                                                                        </div>
                                                                                </div>
                                                                                <?php
                                                                        }
                                                                        break;
                                                                case 'priority':
                                                                        if ($field->published == 1) {
                                                                                if ($fieldcounter % 2 == 0) {
                                                                                        if ($fieldcounter != 0) {
                                                                                                echo '</div>';
                                                                                        }
                                                                                        echo '<div class="row">';
                                                                                }
                                                                                $fieldcounter++;
                                                                                ?>
                                                                                <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                                <label for="priorityid" class="control-label"><?php echo JText::_('Priority'); ?>&nbsp;<font color="red">*</font></label>
                                                                                                <div class="input-group">
                                                                                                        <div class="input-group-addon"><span class="fa fa-bolt"></span></div>
                                                                                                        <?php
                                                                                                        $this->lists['priorities'] = str_replace('class="inputbox required"', 'class="inputbox required form-control"', $this->lists['priorities']);
                                                                                                        echo $this->lists['priorities'];
                                                                                                        ?>

                                                                                                </div>

                                                                                        </div>
                                                                                </div>
                                                                                <?php
                                                                        }
                                                                        break;
                                                                case 'subject':
                                                                        if ($field->published == 1) {
                                                                                if ($fieldcounter != 0) {
                                                                                        echo '</div>';
                                                                                        $fieldcounter = 0;
                                                                                }
                                                                                ?>
                                                                                <div class="form-group">
                                                                                        <label for="subject" class="control-label"><?php echo JText::_('Subject'); ?>&nbsp;<font color="red">*</font></label>
                                                                                        <div class="input-group">
                                                                                                <div class="input-group-addon"><span class="fa fa-paper-plane-o"></span></div>
                                                                                                <input class="inputbox required form-control" placeholder="<?php echo JText::_('Subject'); ?>" type="text" name="subject" id="subject" size="40" maxlength="255" value="<?php if (isset($this->data['subject'])) echo $this->data['subject']; ?>" />

                                                                                        </div>

                                                                                </div>
                                                                                <?php
                                                                        }
                                                                        break;
                                                                case 'issuesummary':
                                                                        if ($field->published == 1) {
                                                                                if (!isset($this->editticket->id)) {
                                                                                        if ($fieldcounter != 0) {
                                                                                                echo '</div>';
                                                                                                $fieldcounter = 0;
                                                                                        }
                                                                                        ?>
                                                                                        <div class="form-group">
                                                                                                <label for="issuesummary" class="control-label"><?php echo JText::_('Issue Summary'); ?>&nbsp;<font color="red">*</font></label>
                                                                                                <div class="editor-box">
                                                                                                        <?php
                                                                                                        $editor = JFactory::getEditor();
                                                                                                        echo $editor->display('message', '', '300', '300', '60', '20', array('class' => 'required form-control'));
                                                                                                        ?>
                                                                                                </div>
                                                                                        </div>
                                                                                        <?php
                                                                                }
                                                                        }
                                                                        break;
                                                                case 'attachments':
                                                                        $flag = true;
                                                                        if ($flag) {
                                                                                if ($field->published == 1) {
                                                                                        if ($fieldcounter != 0) {
                                                                                                echo '</div>';
                                                                                                $fieldcounter = 0;
                                                                                        }
                                                                                        ?>
                                                                                        <div class="form-group">
                                                                                                <label class="control-label"><?php echo JText::_('Attachments'); ?></label>
                                                                                                <div class="js-form-value">
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
                                                                                                </div>
                                                                                        </div>
                                                                                        <?php
                                                                                }
                                                                        }
                                                                        break;
                                                                default:
                                                                        if ($field->published == 1) {
                                                                                if (isset($this->userfields)) {
                                                                                        foreach ($this->userfields as $ufield) {
                                                                                                if ($field->field == $ufield[0]->id) {
                                                                                                        $userfield = $ufield[0];
                                                                                                        $i++;
                                                                                                        if ($fieldcounter % 2 == 0) {
                                                                                                                if ($fieldcounter != 0) {
                                                                                                                        echo '</div>';
                                                                                                                }
                                                                                                                echo '<div class="row">';
                                                                                                        }
                                                                                                        $fieldcounter++;
                                                                                                        echo '<div class="col-md-6">';
                                                                                                        echo '<div class="form-group">';
                                                                                                        if ($userfield->required == 1) {
                                                                                                                echo '
                                                            <label class="control-label" for="userfields_' . $i . '">' . $userfield->title . '&nbsp;<font color="red">*</font></label>';
                                                                                                                if ($userfield->type == 'emailaddress')
                                                                                                                        $cssclass = "class ='inputbox form-control required validate-email' ";
                                                                                                                else
                                                                                                                        $cssclass = "class ='inputbox form-control required' ";
                                                                                                        }else {
                                                                                                                echo '<label for="userfields_' . $i . '" class="control-label">' . $userfield->title . '</label>';
                                                                                                                if ($userfield->type == 'emailaddress')
                                                                                                                        $cssclass = "class ='inputbox form-control validate-email' ";
                                                                                                                else
                                                                                                                        $cssclass = "class='inputbox form-control' ";
                                                                                                        }
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
                                                                                                                        if ($readonly != '')
                                                                                                                                $readonly = 'readonly';
                                                                                                                        echo JHTML::_('calendar', $fvalue, 'userfields_' . $i, 'userfields_' . $i, '%Y-%m-%d', array('class' => 'inputbox form-control', 'size' => '10', 'maxlength' => '19', 'readonly' => $readonly));
                                                                                                                        break;
                                                                                                                case 'textarea':
                                                                                                                        echo '<textarea class="form-control" name="userfields_' . $i . '" id="userfields_' . $i . '_field" cols="' . $userfield->cols . '" rows="' . $userfield->rows . '" ' . $readonly . '>' . $fvalue . '</textarea>';
                                                                                                                        break;
                                                                                                                case 'checkbox':
                                                                                                                        if ($readonly != '')
                                                                                                                                $readonly = 'disabled="disabled"';
                                                                                                                        echo '<input type="checkbox" name="userfields_' . $i . '" id="userfields_' . $i . '_field" value="1" ' . 'checked="checked"' . $readonly . '/>';
                                                                                                                        break;
                                                                                                                case 'select':
                                                                                                                        if ($readonly != '')
                                                                                                                                $readonly = 'disabled="disabled"';
                                                                                                                        $htm = '<select class="form-control" name="userfields_' . $i . '" id="userfields_' . $i . '" ' . $readonly . '>';
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
                                                                                        }
                                                                                        echo '<input type="hidden" id="userfields_total" name="userfields_total"  value="' . $i . '"  />';
                                                                                }
                                                                        }
                                                                        break;
                                                        }
                                                }
                                                if ($fieldcounter != 0) {
                                                        echo '</div>'; // close extra div open in user field
                                                }
                                                if (isset($this->ticket)) {
                                                        if (($this->ticket->created == '0000-00-00 00:00:00') || ($this->ticket->created == ''))
                                                                $curdate = JFactory::getDate()->toSql();
                                                        else
                                                                $update = JFactory::getDate()->toSql();
                                                }else {
                                                        $curdate = JFactory::getDate()->toSql();
                                                        ?>
                                                        <input type="hidden" name="update" value="<?php
                                                        if (isset($update))
                                                                echo $update;
                                                        else
                                                                echo "";
                                                        ?>" /> <?php }
                                       ?>

                                                <input type="hidden" name="created" value="<?php echo $curdate; ?>" />
                                                <input type="hidden" name="view" value="ticket" />
                                                <input type="hidden" name="c" value="ticket" />
                                                <input type="hidden" name="layout" value="formticket" />
                                                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                                                <input type="hidden" name="task" value="saveticket" />
                                                <input type="hidden" name="check" value="" />
                                                <input type="hidden" name="status" value="0" />
                                                <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
                                                <input type="hidden" name="id" value="<?php if (isset($this->ticket)) echo $this->ticket->id; ?>" />
                                                <input class="btn btn-success" type="submit" onclick="return myValidate(document.adminForm);"  name="submit_app" value="<?php echo JText::_('Save Ticket'); ?>" />
                                        </form>
                                </div>
                        </div>
                </div>
        </div>
        <script language="Javascript" >
                jQuery("#js-attachment-add").click(function () {
                        var obj = this;
                        var current_files = jQuery('input[type="file" ]').length;
                        var total_allow =<?php echo $this->config['noofattachment']; ?>;
                        var append_text = "<span class='js-value-text'><input class='inputbox' name='filename[]' type='file' onchange=uploadfile(this,'<?php echo $this->config['filesize']; ?>','<?php echo $this->config['fileextension']; ?>'); size='20' maxlenght='30'  /><span  class='js-attachment-remove'></span></span>";
                        if (current_files < total_allow) {
                                jQuery("#js-attachment-files").append(append_text);
                        } else if ((current_files === total_allow) || (current_files > total_allow)) {
                                alert('<?php echo JText::_('File upload limit exceed'); ?>');
                                obj.hide();
                        }
                });

                jQuery(document).delegate(".js-attachment-remove", "click", function (e) {
                        var current_files = jQuery('input[type="file"]').length;
                        if (current_files != 1)
                                jQuery(this).parent().remove();
                        var current_files = jQuery('input[type="file" ]').length;
                        var total_allow =<?php echo $this->config['noofattachment']; ?>;
                        if (current_files < total_allow) {
                                jQuery("#js-attachment-add").show();
                        }
                });

        </script>
<?php }
?>
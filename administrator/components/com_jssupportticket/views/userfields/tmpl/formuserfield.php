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
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_jssupportticket/include/css/custom.boots.css');
$document->addStyleSheet(JUri::root() . 'administrator/components/com_jssupportticket/include/css/jssupportticketadmin.css');
if (JVERSION < 3) {
    JHtml::_('behavior.mootools');
    $document->addScript('components/com_jssupportticket/include/js/jquery.js');
} else {
    JHtml::_('behavior.framework');
    JHtml::_('jquery.framework');
}
JHTML::_('behavior.calendar');
JHTML::_('behavior.formvalidation');

$yesno = array(
    '0' => array('value' => 1, 'text' => JText::_('Yes')),
    '1' => array('value' => 0, 'text' => JText::_('No')),);

$fieldtype = array(
    '0' => array('value' => 'text', 'text' => JText::_('Text field')),
    '1' => array('value' => 'checkbox', 'text' => JText::_('Check box')),
    '2' => array('value' => 'date', 'text' => JText::_('Date')),
    '3' => array('value' => 'select', 'text' => JText::_('Drop down')),
    '4' => array('value' => 'emailaddress', 'text' => JText::_('Email address')),
    '6' => array('value' => 'textarea', 'text' => JText::_('Text area')),);

if (isset($this->userfield)) {
    $lstype = JHTML::_('select.genericList', $fieldtype, 'type', 'class="inputbox" ' . 'onchange="toggleType(this.options[this.selectedIndex].value);"', 'value', 'text', $this->userfield->type);
    $lsrequired = JHTML::_('select.genericList', $yesno, 'required', 'class="inputbox" ' . '', 'value', 'text', $this->userfield->required);
    $lsreadonly = JHTML::_('select.genericList', $yesno, 'readonly', 'class="inputbox" ' . '', 'value', 'text', $this->userfield->readonly);
    $lspublished = JHTML::_('select.genericList', $yesno, 'published', 'class="inputbox" ' . '', 'value', 'text', $this->userfield->published);
} else {
    $lstype = JHTML::_('select.genericList', $fieldtype, 'type', 'class="inputbox" ' . 'onchange="toggleType(this.options[this.selectedIndex].value);"', 'value', 'text', 0);
    $lsrequired = JHTML::_('select.genericList', $yesno, 'required', 'class="inputbox" ' . '', 'value', 'text', 0);
    $lsreadonly = JHTML::_('select.genericList', $yesno, 'readonly', 'class="inputbox" ' . '', 'value', 'text', 0);
    $lspublished = JHTML::_('select.genericList', $yesno, 'published', 'class="inputbox" ' . '', 'value', 'text', 1);
}
?>

<div id="js-tk-admin-wrapper">
    <div id="js-tk-leftmenu">
        <?php include_once('components/com_jssupportticket/views/menu.php'); ?>
    </div>
    <div id="js-tk-cparea">
        <div id="js-tk-heading"><h4><img id="js-admin-responsive-menu-link" src="components/com_jssupportticket/include/images/c_p/left-icons/menu.png" /><?php echo JText::_('Add User Field'); ?></h4></div>
        <form action="index.php" method="POST" name="adminForm" id="adminForm" >
            <div class="js-form-group">
                <label id="titlemsg" for="title" class="js-control-label js-col-sm-2"><?php echo JText::_("Field Type"); ?></label>
                <div class="js-col-sm-10">
                    <?php echo $lstype; ?>
                </div>  
            </div>  
            <div class="js-form-group">
                <label id="titlemsg" for="title" class="js-control-label js-col-sm-2"><?php echo JText::_("Field Name"); ?></label>
                <div class="js-col-sm-10">
                    <input onchange="prep4SQL(this);" type="text" name="name" mosReq=1 mosLabel="Name" class="inputbox" value="<?php if (isset($this->userfield)) echo $this->userfield->name; ?>"  />
                </div>  
            </div>  
            <div class="js-form-group">
                <label id="titlemsg" for="title" class="js-control-label js-col-sm-2"><?php echo JText::_("Field Title"); ?></label>
                <div class="js-col-sm-10">
                    <input type="text" name="title" class="inputbox" value="<?php if (isset($this->userfield)) echo $this->userfield->title; ?>" />
                </div>  
            </div>  
            <div class="js-form-group">
                <label id="titlemsg" for="title" class="js-control-label js-col-sm-2"><?php echo JText::_("Required"); ?></label>
                <div class="js-col-sm-10">
                    <?php echo $lsrequired; ?>
                </div>  
            </div>  
            <div class="js-form-group">
                <label id="titlemsg" for="title" class="js-control-label js-col-sm-2"><?php echo JText::_("Read Only"); ?></label>
                <div class="js-col-sm-10">
                    <?php echo $lsreadonly; ?>
                </div>  
            </div>  
            <div class="js-form-group">
                <label id="titlemsg" for="title" class="js-control-label js-col-sm-2"><?php echo JText::_("Published"); ?></label>
                <div class="js-col-sm-10">
                    <?php echo $lspublished; ?>
                </div>  
            </div>  
            <div class="js-form-group">
                <label id="titlemsg" for="title" class="js-control-label js-col-sm-2"><?php echo JText::_("Field Size"); ?></label>
                <div class="js-col-sm-10">
                    <input type="text" name="size" class="inputbox" value="<?php if (isset($this->userfield)) echo $this->userfield->size; ?>" />
                </div>  
            </div>  
            <div id="page1"></div>
            <div id="divText">
                <div class="js-form-group">
                    <label id="titlemsg" for="title" class="js-control-label js-col-sm-2"><?php echo JText::_("Max Length"); ?></label>
                    <div class="js-col-sm-10">
                        <input type="text" name="maxlength" class="inputbox" value="<?php if (isset($this->userfield)) echo $this->userfield->maxlength; ?>" />
                    </div>  
                </div>  
            </div>
            <div id="divColsRows">
                <div class="js-form-group">
                    <label id="titlemsg" for="title" class="js-control-label js-col-sm-2"><?php echo JText::_("Columns"); ?></label>
                    <div class="js-col-sm-10">
                        <input type="text" name="cols" class="inputbox" value="<?php if (isset($this->userfield)) echo $this->userfield->cols; ?>" />
                    </div>  
                </div>  
                <div class="js-form-group">
                    <label id="titlemsg" for="title" class="js-control-label js-col-sm-2"><?php echo JText::_("Rows"); ?></label>
                    <div class="js-col-sm-10">
                        <input type="text" name="rows" class="inputbox" value="<?php if (isset($this->userfield)) echo $this->userfield->rows; ?>" />
                    </div>  
                </div>  
            </div>
            <div id="divValues">
                <?php echo JText::_("Use the table below to add new values"); ?><br />
                <input type="button" class="button" onclick="insertRow();" value="Add a Value" />
                <table align=left id="divFieldValues" cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform" >
                    <thead>
                    <th class="title" width="20%"><?php echo JText::_("Title"); ?></th>
                    <th class="title" width="80%"><?php echo JText::_("Value"); ?></th>
                    </thead>
                    <tbody id="fieldValuesBody">
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <?php
                        $i = 0;
                        if (isset($this->userfield)) {
                            if ($this->userfield->type == 'select') {
                                foreach ($this->fieldvalues as $value) { ?>
                                    <tr id="jssupportticket_trcust<?php echo $i; ?>">
                                        <input type="hidden" value="<?php echo $value->id; ?>" name="jsIds[<?php echo $i; ?>]" />
                                        <td width="20%"><input type="text" value="<?php echo $value->fieldtitle; ?>" name="jsNames[<?php echo $i; ?>]" /></td>
                                        <td ><input type="text" value="<?php echo $value->fieldvalue; ?>" name="jsValues[<?php echo $i; ?>]" />
                                            <span class="jquery_span_closetr" data-rowid="jssupportticket_trcust<?php echo $i; ?>" data-optionid="<?php echo $value->id; ?>" style="width:auto;min-height:auto;float:right;padding:4px;background:#b31212;" ></span>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            }
                        } else { ?>
                        <tr id="jsjobs_trcust0">
                            <td width="20%"><input type="text" value="" name="jsNames[0]" /></td>
                            <td ><input type="text" value="" name="jsValues[0]" />
                                <span class="jquery_span_closetr" data-rowid="jssupportticket_trcust0" style="float:right;padding:4px;width:1%;background:#b31212;" ></span>  
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <input type="hidden" name="id" value="<?php if (isset($this->userfield->id)) echo $this->userfield->id; ?>" />
            <input type="hidden" name="valueCount" value="<?php echo ($i - 1); ?>" />
            <input type="hidden" name="fieldfor" value="<?php echo $this->fieldfor; ?>" />
            <input type="hidden" name="c" value="userfields" />
            <input type="hidden" name="layout" value="formuserfield" />
            <input type="hidden" name="task" value="saveuserfield" />
            <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
        </form>
<script type="text/javascript">
    function getObject(obj) {
        var strObj;
        if (document.all) {
            strObj = document.all.item(obj);
        } else if (document.getElementById) {
            strObj = document.getElementById(obj);
        }
        return strObj;
    }

    function insertRow() {
        var oTable = getObject("fieldValuesBody");
        var oRow, oCell, oCellCont, oInput;
        var i, j;
        i = document.adminForm.valueCount.value;
        i++;
        // Create and insert rows and cells into the first body.
        oRow = document.createElement("TR");
        jQuery(oRow).attr('id', "jssupportticket_trcust" + i);
        oTable.appendChild(oRow);

        oCell = document.createElement("TD");
        oInput = document.createElement("INPUT");
        oInput.name = "jsNames[" + i + "]";
        oInput.setAttribute('id', "jsNames_" + i);
        oCell.appendChild(oInput);
        oRow.appendChild(oCell);

        oCell = document.createElement("TD");
        oInput = document.createElement("INPUT");
        oInput.name = "jsValues[" + i + "]";
        oInput.setAttribute('id', "jsValues_" + i);
        oCell.appendChild(oInput);

        oSpan = document.createElement("SPAN");
        oSpan.setAttribute('style', "float:right;padding:4px;background:#b31212;");
        jQuery(oSpan).click(function () {
            jQuery('#jssupportticket_trcust' + i).remove();
            document.adminForm.valueCount.value = document.adminForm.valueCount.value - 1;

        });
        oCell.appendChild(oSpan);


        oRow.appendChild(oCell);
        oInput.focus();

        document.adminForm.valueCount.value = i;
    }

    function disableAll() {
        jQuery("#divValues").slideUp();
        jQuery("#divColsRows").slideUp();
        jQuery("#divText").slideUp();

    }
    function toggleType(type) {
//          alert(type);
        disableAll();
        prep4SQL(document.adminForm.name);
        setTimeout('selType( \'' + type + '\' )', 650);
    }
    function selType(sType) {
        var elem;

        switch (sType) {
            case 'editorta':
            case 'textarea':
                jQuery("#divText").slideDown();
                jQuery("#divColsRows").slideDown();
                break;
            case 'emailaddress':
            case 'password':
            case 'text':
                jQuery("#divText").slideDown();
                break;
            case 'select':
            case 'multiselect':
                jQuery("#divValues").slideDown();
                break;
            case 'radio':
            case 'multicheckbox':
                jQuery("#divColsRows").slideDown();
                jQuery("#divValues").slideDown();
                break;
            case 'delimiter':
            default:

        }
    }

    function prep4SQL(o) {
        if (o.value != '') {
            o.value = o.value.replace('js_', '');
            o.value = 'js_' + o.value.replace(/[^a-zA-Z]+/g, '');
        }
    }

    jQuery(document).ready(function () {
        toggleType(jQuery('#type').val());
    });
    jQuery("span.jquery_span_closetr").each(function () {
        var span = jQuery(this);
        jQuery(span).click(function () {
            var span_current = jQuery(this);
            if (jQuery(span_current).attr('data-optionid') != 'undefined') {
                jQuery.post("index.php?option=com_jssupportticket&c=userfields&task=deleteuserfieldoption", {id: jQuery(span_current).attr('data-optionid')}, function (data) {
                    if (data) {
                        var tr_id = jQuery(span_current).attr('data-rowid');
                        jQuery('#' + tr_id).remove();
                        document.adminForm.valueCount.value = document.adminForm.valueCount.value - 1;
                    } else {
                        alert('<?php echo JText::_('Option value in use'); ?>');

                    }

                });
            } else {
                var tr_id = jQuery(span_current).attr('data-rowid');
                jQuery('#' + tr_id).remove();
                document.adminForm.valueCount.value = document.adminForm.valueCount.value - 1;
            }
        });
    });
</script>
    </div>
</div>
<div id="js-tk-copyright">
    <img width="85" src="http://www.joomsky.com/logo/jssupportticket_logo_small.png">&nbsp;Powered by <a target="_blank" href="http://www.joomsky.com">Joom Sky</a><br/>
    &copy;Copyright 2008 - <?php echo date('Y'); ?>, <a target="_blank" href="http://www.burujsolutions.com">Buruj Solutions</a>
</div>

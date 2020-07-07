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
$editor = JFactory::getEditor();
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.formvalidation');
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_jssupportticket/include/css/custom.boots.css');
$document->addStyleSheet(JUri::root() . 'administrator/components/com_jssupportticket/include/css/jssupportticketadmin.css');
?>
<script type="text/javascript">
    Joomla.submitbutton = function (task) {
        if (task == '') {
            return false;
        } else {
            if (task == 'savedepartment' || task == 'savedepartmentandnew' || task == 'savedepartmentsave') {
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
<div id="js-tk-admin-wrapper">
    <div id="js-tk-leftmenu">
        <?php include_once('components/com_jssupportticket/views/menu.php'); ?>
    </div>
    <div id="js-tk-cparea">
        <div id="js-tk-heading"><h4><img id="js-admin-responsive-menu-link" src="components/com_jssupportticket/include/images/c_p/left-icons/menu.png" /><?php echo JText::_('Add Department'); ?></h4></div> 
        <form action="index.php" method="POST" enctype="multipart/form-data" name="adminForm" id="adminForm">
            <div class="js-col-xs-12 js-col-md-2 js-title"><label for="departmentname"><?php echo JText::_('Title'); ?>:&nbsp;<font color="red">*</font></label></div>
            <div class="js-col-xs-12 js-col-md-10 js-value"><input class="inputbox required" type="text" id="departmentname" name="departmentname" size="40" maxlength="255" value="<?php if (isset($this->department)) echo $this->department->departmentname; ?>" /></div>
            <div class="js-col-xs-12 js-col-md-2 js-title"><label for="emailid"><?php echo JText::_('Outgoing Email'); ?>:&nbsp;<font color="red">*</font></label></div>
            <div class="js-col-xs-12 js-col-md-10 js-value"><?php echo $this->lists['emaillist'] ?></div>
            <div class="js-col-xs-12 js-col-md-2 js-title"><?php echo JText::_('Type'); ?>:&nbsp;</div>
            <div class="js-col-xs-12 js-col-md-10 js-value">
                <input id="pub" type="radio" value="1" name="ispublic"<?php if (isset($this->department)) {if ($this->department->ispublic == 1) echo "checked=''"; } else echo "checked='checket'"; ?> /><label for="pub"><?php echo JText::_('Public'); ?></label>
                <input id="pri" type="radio" value="0" name="ispublic"<?php if (isset($this->department)) {if ($this->department->ispublic == 0) echo "checked=''"; } ?> /> <label for="pri"><?php echo JText::_('Private'); ?></label>
            </div>
            <div class="js-col-xs-12 js-col-md-2 js-title"><?php echo JText::_('Signature'); ?>:&nbsp;</div>
            <div class="js-col-xs-12 js-col-md-10 js-value"><?php $editor = JFactory::getEditor(); if (isset($this->department->departmentsignature)) echo $editor->display('departmentsignature', $this->department->departmentsignature, '550', '300', '60', '20', false); else echo $editor->display('departmentsignature', '', '550', '300', '60', '20', false); ?> </div>
            <div class="js-col-xs-12 js-col-md-2 js-title"></div>
            <div class="js-col-xs-12 js-col-md-10 js-value"><label><input type="checkbox" name="canappendsignature" value="1"<?php if (isset($this->department->canappendsignature)) echo "checked=''"; ?> /><?php echo JText::_('Append Signature'); ?></label></div>
            <div class="js-col-xs-12 js-col-md-2 js-title"><?php echo JText::_('Status'); ?>:&nbsp;</div>
            <div class="js-col-xs-12 js-col-md-10 js-value"><?php echo $this->lists['status']; ?></div>
            <div class="js-col-xs-12 js-col-md-12"><div id="js-submit-btn"><input type="submit" class="button" name="submit_app" onclick="return validate_form(document.adminForm)" value="<?php echo JText::_('Save Department'); ?>" /></div></div>

            <input type="hidden" name="id" value="<?php if (isset($this->department)) echo $this->department->id; ?>" />
            <input type="hidden" name="c" value="department" />
            <input type="hidden" name="task" value="savedepartment" />
            <input type="hidden" name="layout" value="formdepartment" />
            <input type="hidden" name="check" value="" />
            <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
            <input type="hidden" name="created" value="<?php if (!isset($this->department)) echo $curdate = date('Y-m-d H:i:s'); else echo $this->editgroup[0]->created; ?>"/>
            <input type="hidden" name="update" value="<?php if (isset($this->department)) echo $update = date('Y-m-d H:i:s'); ?>"/>
    </div>
</div>
<div id="js-tk-copyright">
    <img width="85" src="http://www.joomsky.com/logo/jssupportticket_logo_small.png">&nbsp;Powered by <a target="_blank" href="http://www.joomsky.com">Joom Sky</a><br/>
    &copy;Copyright 2008 - <?php echo date('Y'); ?>, <a target="_blank" href="http://www.burujsolutions.com">Buruj Solutions</a>
</div>

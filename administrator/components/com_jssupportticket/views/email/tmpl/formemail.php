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

$editor = JFactory::getEditor();

jimport('joomla.html.pane');
JHTML::_('behavior.formvalidation');
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_jssupportticket/include/css/custom.boots.css');
$document->addStyleSheet(JUri::root() . 'administrator/components/com_jssupportticket/include/css/jssupportticketadmin.css');
?>

<script type="text/javascript">
// for joomla 1.6
    Joomla.submitbutton = function (task) {
        if (task == '') {
            return false;
        } else {
            if (task == 'saveemail' || task == 'saveemailandnew' || task == 'saveemailsave') {
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
        <div id="js-tk-heading"><h4><img id="js-admin-responsive-menu-link" src="components/com_jssupportticket/include/images/c_p/left-icons/menu.png" /><?php echo JText::_('Add Email'); ?></h4></div> 
        <form action="index.php" method="POST" enctype="multipart/form-data" name="adminForm" id="adminForm">
            <div class="js-col-xs-12 js-col-md-2 js-title"><label for="email"><?php echo JText::_('Email'); ?>&nbsp;<font color="red">*</font></label></div>
            <div class="js-col-xs-12 js-col-md-10 js-value"><input class="inputbox required validate-email" type="text" id="email" name="email" size="40" maxlength="255" value="<?php if (isset($this->email)) echo $this->email->email; ?>" /></div>
            <div class="js-col-xs-12 js-col-md-2 js-title"><?php echo JText::_('Status'); ?>:&nbsp;</div>
            <div class="js-col-xs-12 js-col-md-10 js-value"><input type="radio" value="1" name="status"<?php if (isset($this->email)) {if ($this->email->status == 1) echo "checked=''"; } else echo "checked=''"; ?> /><?php echo JText::_('Active'); ?> <input type="radio" value="0" name="status"<?php if (isset($this->email)) {if ($this->email->status == 0) echo "checked=''"; } ?> /><?php echo JText::_('Disabled'); ?></div>
            <div class="js-col-xs-12 js-col-md-12"><div id="js-submit-btn"><input type="submit" class="button" name="submit_app" onclick="return validate_form(document.adminForm)" value="<?php echo JText::_('Save Email'); ?>" /></div></div>
            <input type="hidden" name="id" value="<?php if (isset($this->email)) echo $this->email->id; ?>" />
            <input type="hidden" name="c" value="email" />
            <input type="hidden" name="task" value="saveemail" />
            <input type="hidden" name="layout" value="formemail" />
            <input type="hidden" name="check" value="" />
            <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
            <input type="hidden" name="created" value="<?php if (!isset($this->email)) echo $curdate = date('Y-m-d H:i:s'); else echo $this->email->created; ?>"/>
            <input type="hidden" name="update" value="<?php if (isset($this->email)) echo $update = date('Y-m-d H:i:s'); ?>"/>
        </form>
    </div>
</div>
<div id="js-tk-copyright">
    <img width="85" src="http://www.joomsky.com/logo/jssupportticket_logo_small.png">&nbsp;Powered by <a target="_blank" href="http://www.joomsky.com">Joom Sky</a><br/>
    &copy;Copyright 2008 - <?php echo date('Y'); ?>, <a target="_blank" href="http://www.burujsolutions.com">Buruj Solutions</a>
</div>

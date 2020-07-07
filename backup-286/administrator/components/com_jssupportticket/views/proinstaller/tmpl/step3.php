<?php
/**
 * @Copyright Copyright (C) 2012 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:     Buruj Solutions
  + Contact:        www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:  May 03, 2012
  ^
  + Project:    JS Tickets
  ^
 */
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
?>
<script language=Javascript>
    function confirmdelete() {
        if (confirm("<?php echo JText::_('Are you sure to delete'); ?>") == true) {
            return true;
        } else
            return false;
    }
</script>
<div id="js-tk-admin-wrapper">
    <div id="js-tk-leftmenu">
        <?php include_once('components/com_jssupportticket/views/menu.php'); ?>
    </div>
    <div id="js-tk-cparea">
        <div id="js-tk-heading">
            <h4><img id="js-admin-responsive-menu-link" src="components/com_jssupportticket/include/images/c_p/left-icons/menu.png" /><?php echo JText::_('JS Support Ticket Pro Installer'); ?></h4>
        </div>
        <div style="display:none;" id="jsjob_installer_waiting_div"></div>
        <span style="display:none;" id="jsjob_installer_waiting_span">Please wait installation in progress</span>
        <div id="jsst-main-wrapper" >
            <div id="jsst-middle-wrapper">
                <div class="js-col-md-4 active"><span class="jsst-number">1</span><span class="jsst-text"><?php echo JText::_('Configuration'); ?></span></div>
                <div class="js-col-md-4 active"><span class="jsst-number">2</span><span class="jsst-text"><?php echo JText::_('Permissions'); ?></span></div>
                <div class="js-col-md-4 active"><span class="jsst-number">3</span><span class="jsst-text"><?php echo JText::_('Installation'); ?></span></div>
                <div class="js-col-md-4"><span class="jsst-number">4</span><span class="jsst-text"><?php echo JText::_('Finish'); ?></span></div>        
            </div>
            <div id="jsst-lower-wrapper" class="last">
                <span class="fill_form_title"><?php echo JText::_('Please insert activation key and press start');?></span>
                <div class="form">
                    <input type="text" name="transactionkey" id="transactionkey" value="" placeholder="<?php echo JText::_('Activation key'); ?>"/>
                    <a href="#" class="nextbutton" id="startpress"><?php echo JText::_('Start'); ?></a>
                    <input type="hidden" name="productcode" id="productcode" value="<?php echo isset($this->config['productcode']) ? $this->config['productcode'] : 'jssupportticket'; ?>" />
                    <input type="hidden" name="productversion" id="productversion" value="<?php echo isset($this->config['version']) ? $this->config['version'] : '100'; ?>" />
                    <input type="hidden" name="producttype" id="producttype" value="<?php echo isset($this->config['producttype']) ? $this->config['producttype'] : 'free'; ?>" />
                    <input type="hidden" name="domain" id="domain" value="<?php echo JURI::root(); ?>" />
                    <input type="hidden" name="JVERSION" id="JVERSION" value="<?php echo JVERSION; ?>" />
                    <input type="hidden" name="config_count" id="config_count" value="<?php echo $this->config_count; ?>" />
                </div>
            </div>
            <div id="jsst_error_message"></div>
            <div id="jsst_next_form"></div>
            <div class="last-message">
                <?php echo JText::_('It may take few minutes'); ?>
            </div>
        </div>
    </div>
</div>
<div id="js-tk-copyright">
    <img width="85" src="http://www.joomsky.com/logo/jssupportticket_logo_small.png">&nbsp;Powered by <a target="_blank" href="http://www.joomsky.com">Joom Sky</a><br/>
    &copy;Copyright 2008 - <?php echo date('Y'); ?>, <a target="_blank" href="http://www.burujsolutions.com">Buruj Solutions</a>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($){
        $("a#startpress").click(function(e){
            e.preventDefault();
            $('div#jsjob_installer_waiting_div').show();
            $('span#jsjob_installer_waiting_span').show();
            var transactionkey = $("input#transactionkey").val();
            var productcode = $("input#productcode").val();
            var productversion = $("input#productversion").val();
            var producttype = $("input#producttype").val();
            var domain = $("input#domain").val();
            var JVERSION = $("input#JVERSION").val();
            var config_count = $("input#config_count").val();
            $.post("index.php?option=com_jssupportticket&c=proinstaller&task=getmyversionlist",{transactionkey:transactionkey,productcode:productcode,productversion:productversion,domain:domain,JVERSION:JVERSION,producttype:producttype,config_count:config_count},function(data){
                if(data){
                    var array = $.parseJSON(data);
                    if(array[0] == 0){
                        $("div#jsst_error_message").html(array[1]).show();
                    }else{
                        $("div#jsst_next_form").html(array[2]).show();;
                    }
                    $('div#jsjob_installer_waiting_div').hide();
                    $('span#jsjob_installer_waiting_span').hide();
                }
            });
        });
    });
</script>

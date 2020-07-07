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
        <div id="jsst-main-wrapper" >
            <div id="jsst-middle-wrapper">
                <div class="js-col-md-4 active"><span class="jsst-number">1</span><span class="jsst-text"><?php echo JText::_('Configuration'); ?></span></div>
                <div class="js-col-md-4 active"><span class="jsst-number">2</span><span class="jsst-text"><?php echo JText::_('Permissions'); ?></span></div>
                <div class="js-col-md-4"><span class="jsst-number">3</span><span class="jsst-text"><?php echo JText::_('Installation'); ?></span></div>
                <div class="js-col-md-4"><span class="jsst-number">4</span><span class="jsst-text"><?php echo JText::_('Finish'); ?></span></div>        
            </div>
            <div id="jsst-lower-wrapper">
                <span class="jsst-main-title"><?php echo JText::_('Permissions'); ?></span>
                <?php if (($this->result['admin_dir'] < 755 ) || ($this->result['site_dir'] < 755 ) || ($this->result['tmp_dir'] < 755 ) || ($this->result['create_table'] != 1) || ($this->result['insert_record'] != 1) || ($this->result['update_record'] != 1 ) || ($this->result['delete_record'] != 1 ) || ($this->result['drop_table'] != 1 ) || ($this->result['file_downloaded'] != 1 )) { ?>
                    <div class="js-row jsst-main-error" id="jsst-table-data">
                        <img src="components/com_jssupportticket/include/images/error_icon.png" />
                        <div class="js-row">
                            <span class="jsst-main-error"><?php echo JText::_('Error occured'); ?></span>
                                <?php if ($this->result['admin_dir'] < 755 && $this->result['site_dir'] < 755 && $this->result['tmp_dir'] < 755) { ?>
                                    <span class="jsst-error-line"><?php echo JText::_('Directory permissions error'); ?></span>
                                <?php } ?>
                                <?php if ($this->result['create_table'] != 1) { ?>
                                    <span class="jsst-error-line"><?php echo JText::_('Database create table not allowed'); ?></span>
                                <?php } ?>
                                <?php if ($this->result['insert_record'] != 1) { ?>
                                    <span class="jsst-error-line"><?php echo JText::_('Database insert record not allowed'); ?></span>
                                <?php } ?>
                                <?php if ($this->result['update_record'] != 1) { ?>
                                    <span class="jsst-error-line"><?php echo JText::_('Database update record not allowed'); ?></span>
                                <?php } ?>
                                <?php if ($this->result['delete_record'] != 1) { ?>
                                    <span class="jsst-error-line"><?php echo JText::_('Database delete record not allowed'); ?></span>
                                <?php } ?>
                                <?php if ($this->result['drop_table'] != 1) { ?>
                                    <span class="jsst-error-line"><?php echo JText::_('Database drop table not allowed'); ?></span>
                                <?php } ?>
                                <?php if ($this->result['file_downloaded'] != 1) { ?>
                                    <span class="jsst-error-line"><?php echo JText::_('Error file not downloaded'); ?></span>
                                <?php } ?>
                            </div>
                    </div>
                <?php } ?>
                <div class="js-row" id="jsst-table-head">
                    <div class="js-col-md-8"><?php echo JText::_('Setting'); ?></div>
                    <div class="js-col-md-2"><?php echo JText::_('Recomended'); ?></div>
                    <div class="js-col-md-2"><?php echo JText::_('Current'); ?></div>
                </div>
                <div class="js-row <?php if($this->result['admin_dir'] < 755 && $this->result['site_dir'] < 755 && $this->result['tmp_dir'] < 755) echo 'error'; ?>" id="jsst-table-data">
                    <div class="js-col-md-8"><?php echo JText::_('Directory'); ?></div>
                    <div class="js-col-md-2 image"><img src="components/com_jssupportticket/include/images/tick.png" /></div>
                    <div class="js-col-md-2 image"><img src="components/com_jssupportticket/include/images/<?php
                        if ($this->result['admin_dir'] >= 755 && $this->result['site_dir'] >= 755 && $this->result['tmp_dir'] >= 755)
                            echo "tick";
                        else
                            echo "cross";
                        ?>.png" /></div>
                </div>
                <div class="js-row <?php if(($this->result['create_table'] != 1) || ($this->result['insert_record'] != 1) || ($this->result['update_record'] != 1 ) || ($this->result['delete_record'] != 1 ) && ($this->result['drop_table'] != 1 )) echo 'error'; ?>" id="jsst-table-data">
                    <div class="js-col-md-8"><?php echo JText::_('Database CRUD'); ?></div>
                    <div class="js-col-md-2 image"><img src="components/com_jssupportticket/include/images/tick.png" /></div>
                    <div class="js-col-md-2 image"><img src="components/com_jssupportticket/include/images/<?php
                        if ($this->result['create_table'] == 1 && $this->result['insert_record'] == 1 && $this->result['update_record'] == 1 && $this->result['delete_record'] == 1 && $this->result['drop_table'] == 1)
                            echo "tick";
                        else
                            echo "cross";
                        ?>.png" /></div>
                </div>
                <div class="js-row <?php if($this->result['file_downloaded'] != 1) echo 'error'; ?>" id="jsst-table-data">
                    <div class="js-col-md-8"><?php echo JText::_('File Download'); ?></div>
                    <div class="js-col-md-2 image"><img src="components/com_jssupportticket/include/images/tick.png" /></div>
                    <div class="js-col-md-2 image"><img src="components/com_jssupportticket/include/images/<?php
                        if ($this->result['file_downloaded'] == 1)
                            echo "tick";
                        else
                            echo "cross";
                        ?>.png" /></div>
                </div>
                <div class="js-row">
                    <?php if (($this->result['admin_dir'] >= 755 && $this->result['site_dir'] >= 755 && $this->result['tmp_dir'] >= 755 ) && ($this->result['create_table'] == 1) && ($this->result['insert_record'] == 1) && ($this->result['update_record'] == 1 ) && ($this->result['delete_record'] == 1 ) && ($this->result['drop_table'] == 1 ) && ($this->result['file_downloaded'] == 1 )) { ?>
                        <a class="nextbutton" href="index.php?option=com_jssupportticket&c=proinstaller&layout=step3"><?php echo JText::_('Next Step'); ?></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="js-tk-copyright">
    <img width="85" src="http://www.joomsky.com/logo/jssupportticket_logo_small.png">&nbsp;Powered by <a target="_blank" href="http://www.joomsky.com">Joom Sky</a><br/>
    &copy;Copyright 2008 - <?php echo date('Y'); ?>, <a target="_blank" href="http://www.burujsolutions.com">Buruj Solutions</a>
</div>

<?php
/**
 * @Copyright Copyright (C) 2015 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:     Buruj Solutions
 + Contact:    www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	May 22, 2015
  ^
  + Project:    JS Tickets
  ^
 */
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'administrator/components/com_jssupportticket/include/css/jssupportticketadmin.css');
global $mainframe;
?>

<div id="js-tk-admin-wrapper">
    <div id="js-tk-leftmenu">
        <?php include_once('components/com_jssupportticket/views/menu.php'); ?>
    </div>
    <div id="js-tk-cparea">
        <div id="js-tk-heading">
            <h4><img id="js-admin-responsive-menu-link" src="components/com_jssupportticket/include/images/c_p/left-icons/menu.png" /><?php echo JText::_('System Errors'); ?></h4>      
        </div>
        <div class="js-col-md-12">
            <a href="index.php?option=com_jssupportticket&c=systemerrors&view=systemerrors&layout=systemerrors"><?php echo JText::_('System Errors'); ?></a>
            <div class="js-col-md-12 js-error-wrapper">
                <div class="js-col-md-2 js-col-xs-12 js-error-title">
                    <?php echo JText::_('From'); ?>
                </div>
                <div class="js-col-md-10 js-col-xs-12 js-error-value">
                    <?php if($this->error->staffname) echo $this->error->staffname; else echo JText::_("User"); ?>
                </div>
            </div>
            <div class="js-col-md-12 js-error-wrapper">
                <div class="js-col-md-2 js-col-xs-12 js-error-title">
                    <?php echo JText::_('Date'); ?>
                </div>
                <div class="js-col-md-10 js-col-xs-12 js-error-value">
                    <?php echo JHtml::_('date',$this->error->created,$this->config['date_format']); ?>
                </div>
            </div>
            <div class="js-col-md-12 js-error-wrapper">
                <div class="js-col-md-2 js-col-xs-12 js-error-title">
                    <?php echo JText::_('View'); ?>
                </div>
                <div class="js-col-md-10 js-col-xs-12 js-error-value">
                    <?php if ($this->error->isview == 1) echo JText::_('Yes'); else echo JText::_('No'); ?>
                </div>
            </div>
            <div class="js-col-md-12 js-error-wrapper">
                <div class="js-col-md-2 js-col-xs-12 js-error-title">
                    <?php echo JText::_('Error'); ?>
                </div>
                <div class="js-col-md-10 js-col-xs-12 js-error-value">
                    <?php echo $this->error->error; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="js-tk-copyright">
    <img width="85" src="http://www.joomsky.com/logo/jssupportticket_logo_small.png">&nbsp;Powered by <a target="_blank" href="http://www.joomsky.com">Joom Sky</a><br/>
    &copy;Copyright 2008 - <?php echo date('Y'); ?>, <a target="_blank" href="http://www.burujsolutions.com">Buruj Solutions</a>
</div>
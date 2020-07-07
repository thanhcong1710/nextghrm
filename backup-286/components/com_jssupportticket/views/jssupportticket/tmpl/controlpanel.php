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
?>
<div class="js-row js-null-margin">
<?php
if($this->config['offline'] == '1'){
    messagesLayout::getSystemOffline($this->config['title'],$this->config['offline_text']);
}else{ ?>
    <div id="js-tk-heading">
        <span id="js-tk-heading-text"><?php echo $this->config['title']; ?></span>
    </div>
    <div id="js-maincp-area">
        <?php if($this->config['cplink_openticket_user'] == 1){ ?>
        <div class="js-col-xs-12 js-col-md-6 js-mnu-wrapper">        
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=ticket&layout=formticket&Itemid=<?php echo $this->Itemid; ?>">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/open_ticket.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('New Ticket'); ?></span></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <?php } ?>
        <?php if($this->config['cplink_myticket_user'] == 1){ ?>
        <div class="js-col-xs-12 js-col-md-6 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=ticket&layout=mytickets&Itemid=<?php echo $this->Itemid; ?>">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/my_tickets.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('My Tickets'); ?></span></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>    
        </div>
        <?php } ?>
    </div>
<?php } ?>
<div id="js-tk-copyright">
    <img width="85" src="http://www.joomsky.com/logo/jssupportticket_logo_small.png">&nbsp;Powered by <a target="_blank" href="http://www.joomsky.com">Joom Sky</a><br/>
    &copy;Copyright 2008 - <?php echo date('Y'); ?>, <a target="_blank" href="http://www.burujsolutions.com">Buruj Solutions</a>
</div>
</div>
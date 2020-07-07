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
$document->addStyleSheet(JUri::root(true) . '/templates/kiwierp/css/ticket.css');
?>
<div class="kiwi-ticket">
        <?php
        if ($this->config['offline'] == '1') {
                messagesLayout::getSystemOffline($this->config['title'], $this->config['offline_text']);
        } else {
                ?>
                <div class="page-header">
                        <h1><?php echo $this->config['title']; ?></h1>
                </div>
                <div id="js-maincp-area">
                        <?php if ($this->config['cplink_openticket_user'] == 1) { ?>
                                <div class="col-md-6">
                                        <a class="js-mnu-area" href="<?php echo JRoute::_('index.php?option=com_jssupportticket&c=ticket&layout=formticket&Itemid=' . $this->Itemid); ?>">
                                                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/open_ticket.png"/></div>
                                                <div class="js-mnu-text"><span> <?php echo JText::_('New Ticket'); ?></span></div>
                                                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
                                        </a>
                                </div>
                        <?php } ?>
                        <?php if ($this->config['cplink_myticket_user'] == 1) { ?>
                                <div class="col-md-6">
                                        <a class="js-mnu-area" href="<?php echo JRoute::_('index.php?option=com_jssupportticket&c=ticket&layout=mytickets&Itemid=' . $this->Itemid); ?>">
                                                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/my_tickets.png"/></div>
                                                <div class="js-mnu-text"><span> <?php echo JText::_('My Tickets'); ?></span></div>
                                                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
                                        </a>
                                </div>
                        <?php } ?>
                </div>
        <?php } ?>
</div>
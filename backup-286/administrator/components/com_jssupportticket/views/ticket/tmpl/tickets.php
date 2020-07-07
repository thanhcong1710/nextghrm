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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
?>

<script language=Javascript>
    function confirmdelete(deletefor) {
        msg = '';
        if(deletefor == 0){
            msg = "<?php echo JText::_('Are you sure to delete'); ?>";
        }else if(deletefor == 1){
            msg = "<?php echo JText::_('Are you sure to enforce delete'); ?>";
        }

        if (confirm(msg) == true) {
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
            <h4><img id="js-admin-responsive-menu-link" src="components/com_jssupportticket/include/images/c_p/left-icons/menu.png" /><?php echo JText::_('Tickets'); ?></h4>
            <?php $link = 'index.php?option='.$this->option.'&c=ticket&task=addnewticket'; ?>
            <a class="tk-heading-addbutton" href="<?php echo $link; ?>">
                <img class="js-heading-addimage" src="components/com_jssupportticket/include/images/add-btn.png">
                <?php echo JText::_('Add Ticket'); ?>
            </a>            
        </div>
        <div class="js-col-md-12">
        <form action="index.php" method="post" name="adminForm" id="adminForm">
            <div id="js-tk-filter">
                <div class="tk-search-value"><input type="text" name="filter_ticketid" id="filter_ticketid" placeholder="<?php echo JText::_('Ticket ID') ?>" size="10" value="<?php if (isset($this->lists['searchticket'])) echo $this->lists['searchticket']; ?>" class="text_area"/></div>
                <div class="tk-search-value"><input type="text" name="filter_subject" id="filter_subject" placeholder="<?php echo JText::_('Subject') ?>" size="10" value="<?php if (isset($this->lists['searchsubject'])) echo $this->lists['searchsubject']; ?>" class="text_area"/></div>
                <div class="tk-search-value"><input type="text" name="filter_from" id="filter_from" placeholder="<?php echo JText::_('From') ?>" size="10" value="<?php if (isset($this->lists['searchfrom'])) echo $this->lists['searchfrom']; ?>" class="text_area"/></div>
                <div class="tk-search-value"><input type="text" name="filter_fromemail" id="filter_fromemail" placeholder="<?php echo JText::_('Email') ?>" size="10" value="<?php if (isset($this->lists['searchfromemail'])) echo $this->lists['searchfromemail']; ?>" class="text_area"/></div>
                <div class="tk-search-button">
                    <button onclick="this.form.submit();"><?php echo JText::_('Search'); ?></button>
                    <button onclick="this.form.getElementById('filter_ticketid').value = ''; this.form.getElementById('filter_subject').value = ''; this.form.getElementById('filter_from').value = ''; this.form.getElementById('filter_fromemail').value = ''; this.form.submit();"><?php echo JText::_('Reset'); ?></button>
                </div>
            </div>
            <!-- tabs -->
            <div id="js-tk-atags">
                <a class="<?php if($this->listtype == 1) echo "selected";?>" href="index.php?option=com_jssupportticket&c=ticket&layout=tickets&lt=1">
                    <?php 
                    if($this->config['show_count_tickets'] == 1){
                        echo JText::_('Open') . "&nbsp;(" . $this->ticketinfo['open'] . ")";
                    }else{
                        echo JText::_('Open');
                    }
                    ?></a>
                <a class="<?php if($this->listtype == 2) echo "selected";?>" href="index.php?option=com_jssupportticket&c=ticket&layout=tickets&lt=2">
                    <?php 
                    if($this->config['show_count_tickets'] == 1){
                        echo JText::_('Answered') . "&nbsp;(" . $this->ticketinfo['isanswered'] . ")";
                    }else{
                        echo JText::_('Answered');
                    }
                    ?></a>
                <a class="<?php if($this->listtype == 4) echo "selected";?>" href="index.php?option=com_jssupportticket&c=ticket&layout=tickets&lt=4">
                    <?php 
                    if($this->config['show_count_tickets'] == 1){
                        echo JText::_('Closed') . "&nbsp;(" . $this->ticketinfo['close'] . ")";
                    }else{
                        echo JText::_('Closed');
                    }
                    ?></a>
                <a class="<?php if($this->listtype == 5) echo "selected";?>" href="index.php?option=com_jssupportticket&c=ticket&layout=tickets&lt=5">
                    <?php 
                    if($this->config['show_count_tickets'] == 1){
                        echo JText::_('All Tickets'); if ($this->ticketinfo['mytickets']) echo "&nbsp;(" . $this->ticketinfo['mytickets'] . ")"; else echo "&nbsp;(0)";
                    }else{
                        echo JText::_('All Tickets');
                    }
                    ?></a>
            </div>
            <!-- tickts listing -->
            <?php
            $link = 'index.php?option=com_jssupportticket&c=ticket&layout=tickets';
            if ($this->sortorder == 'ASC')
                $img = "sort0.png";
            else
                $img = "sort1.png";
            ?>
            <div class="js-admin-sorting js-col-md-12">
                <span class="js-col-md-2 js-admin-sorting-link"><a href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['subject']; ?>" class="<?php if ($this->sorton == 'subject') echo 'selected' ?>"><?php echo JText::_('Subject'); ?><?php if ($this->sorton == 'subject') { ?> <img src="<?php echo '../components/com_jssupportticket/include/images/' . $img ?>"> <?php } ?></a></span>
                <span class="js-col-md-2 js-admin-sorting-link"><a href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['priority']; ?>" class="<?php if ($this->sorton == 'priority') echo 'selected' ?>"><?php echo JText::_('Priority'); ?><?php if ($this->sorton == 'priority') { ?> <img src="<?php echo '../components/com_jssupportticket/include/images/' . $img ?>"> <?php } ?></a></span>
                <span class="js-col-md-2 js-admin-sorting-link"><a href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['ticketid']; ?>" class="<?php if ($this->sorton == 'ticketid') echo 'selected' ?>"><?php echo JText::_('Ticket Id'); ?><?php if ($this->sorton == 'ticketid') { ?> <img src="<?php echo '../components/com_jssupportticket/include/images/' . $img ?>"> <?php } ?></a></span>
                <span class="js-col-md-2 js-admin-sorting-link"><a href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['answered']; ?>" class="<?php if ($this->sorton == 'answered') echo 'selected' ?>"><?php echo JText::_('Answered'); ?><?php if ($this->sorton == 'answered') { ?> <img src="<?php echo '../components/com_jssupportticket/include/images/' . $img ?>"> <?php } ?></a></span>
                <span class="js-col-md-2 js-admin-sorting-link"><a href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['status']; ?>" class="<?php if ($this->sorton == 'status') echo 'selected' ?>"><?php echo JText::_('Status'); ?><?php if ($this->sorton == 'status') { ?> <img src="<?php echo '../components/com_jssupportticket/include/images/' . $img ?>"> <?php } ?></a></span>
                <span class="js-col-md-2 js-admin-sorting-link"><a href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['created']; ?>" class="<?php if ($this->sorton == 'created') echo 'selected' ?>"><?php echo JText::_('Created'); ?><?php if ($this->sorton == 'created') { ?> <img src="<?php echo '../components/com_jssupportticket/include/images/' . $img ?>"> <?php } ?></a></span>
            </div>
            <?php
            if (!(empty($this->result)) && is_array($this->result)) {
                $i = 0;
                foreach ($this->result AS $row) {
                    $checked = JHTML::_('grid.id', $i, $row->id);
                    $link_edit = 'index.php?option='.$this->option.'&c=ticket&task=addnewticket&cid[]='.$row->id;
                    $link_enforce_delete = 'index.php?option=' . $this->option . '&c=ticket&task=enforcedelete&cid='.$row->id;
                    $link_delete = 'index.php?option=' . $this->option . '&c=ticket&task=delete&cid='.$row->id;
                    $link_detail = 'index.php?option=' . $this->option . '&c=ticket&layout=ticketdetails&cid[]='.$row->id;
                    ?>
                    <div id="js-tk-wrapper">
                        <div class="js-col-xs-12 js-col-md-1 js-icon">
                            <img src="<?php echo JURI::root(); ?>components/com_jssupportticket/include/images/user.png" />
                        </div>
                        <div class="js-col-xs-12 js-col-md-7 js-middle">
                            <div class="js-col-md-12 js-col-xs-12 js-wrapper-subject js-wrapper"><span class="js-tk-title"><?php echo JText::_('Subject'); ?><font>:</font> </span><span class="js-tk-value"><a href="<?php echo $link_detail; ?>"> <?php echo $row->subject; ?></a></span></div>
                            <div class="js-col-md-12 js-col-xs-12 js-wrapper"><span class="js-tk-title"><?php echo JText::_('From'); ?><font>:</font></span><span class="js-tk-value"><?php echo $row->name; ?></span></div>
                            <div class="js-col-md-12 js-col-xs-12 js-tk-preletive js-wrapper">
                                <div class="js-tk-pabsolute">
                                    <?php
                                    $counter = 'one';
                                    if ($row->lock == 1) { ?>
                                        <img class="ticketstatusimage <?php echo $counter;$counter = 'two'; ?>" src="<?php echo JURI::root(); ?>components/com_jssupportticket/include/images/lockstatus.png" title="<?php echo JText::_('Ticket Is Locked'); ?>" />
                                    <?php } ?>
                                    <?php if ($row->isoverdue == 1) { ?>
                                        <img class="ticketstatusimage <?php echo $counter; ?>" src="<?php echo JURI::root(); ?>components/com_jssupportticket/include/images/mark_over_due.png" title="<?php echo JText::_('Ticket mark overdue'); ?>" />
                                    <?php } ?>
                                    <?php if ($row->status == 0) { ?> 
                                        <span style="background-color: #9ACC00;"><?php echo JText::_('New'); ?></span>
                                    <?php } elseif ($row->status == 1) { ?>
                                        <span style="background-color: orange;"><?php echo JText::_('Waiting reply'); ?></span>
                                    <?php } elseif ($row->status == 2) { ?>
                                        <span style="background-color: #FF7F50;"><?php echo JText::_('In progress'); ?></span>
                                    <?php } elseif ($row->status == 3) { ?>
                                        <span style="background-color: #507DE4;"><?php echo JText::_('Replied'); ?></span>
                                    <?php } elseif ($row->status == 4) { ?>
                                        <span style="background-color: #CB5355;"><?php echo JText::_('Close'); ?></span>
                                    <?php } ?>    
                                </div>
                                <span class="js-tk-title"><?php echo JText::_('Department'); ?><font>:</font></span><span class="js-tk-value"><?php echo JText::_($row->departmentname); ?></span>
                            </div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-4 js-right">
                            <div class="js-col-md-12 js-col-xs-12 js-wrapper"><span class="js-tk-title js-col-xs-6 js-col-md-6"><?php echo JText::_('Ticket ID'); ?></span><span class="js-tk-value js-col-md-6 js-col-xs-6"> <?php echo $row->ticketid; ?></span></div>
                            <div class="js-col-md-12 js-col-xs-12 js-wrapper"><span class="js-tk-title js-col-xs-6 js-col-md-6"><?php echo JText::_('Last Reply'); ?></span><span class="js-tk-value js-col-md-6 js-col-xs-6"><?php if ($row->lastreply == '' || $row->lastreply == '0000-00-00 00:00:00') echo JText::_('No last reply'); else echo JHtml::_('date',$row->lastreply,$this->config['date_format']); ?></span></div>
                            <div class="js-col-md-12 js-col-xs-12 js-wrapper"><span class="js-tk-title js-col-xs-6 js-col-md-6"><?php echo JText::_('Priority'); ?></span><span class="js-tk-value js-col-md-6 js-col-xs-6" style="background:<?php echo $row->prioritycolour; ?>;color:#ffffff;padding-left:10px;"><?php echo $row->priority; ?></span></div>
                        </div>
                        <div class="js-col-xs-12 js-col-md-12 js-bottom">
                            <span class="js-tk-date">
                                <?php echo JText::_('Created'); ?> <font>:</font> <?php echo JHtml::_('date',$row->created,$this->config['date_format']); ?>
                            </span>
                            <span class="js-tk-actions">
                                <a href="<?php echo $link_detail; ?>"><img class="js-tk-action-img" src="components/com_jssupportticket/include/images/tk-detail.png">&nbsp;&nbsp;<?php echo JText::_('Ticket detail'); ?> </a>
                                <a href="<?php echo $link_edit; ?>"><img class="js-tk-action-img" src="components/com_jssupportticket/include/images/edit_small.png">&nbsp;&nbsp;<?php echo JText::_('Edit'); ?> </a>
                                <a href="<?php echo $link_delete; ?>" onclick="return confirmdelete(0)"><img class="js-tk-action-img" src="components/com_jssupportticket/include/images/tk-remove.png">&nbsp;&nbsp;<?php echo JText::_('Delete'); ?> </a>
                                <a href="<?php echo $link_enforce_delete; ?>" onclick="return confirmdelete(1)"><img class="js-tk-action-img" src="components/com_jssupportticket/include/images/close2.png">&nbsp;&nbsp;<?php echo JText::_('Enforce delete'); ?> </a>
                            </span>
                        </div>
                    </div> 
                <?php
                    }
                    ?>
                    <div class="js-row js-tk-pagination">
                        <?php echo $this->pagination->getListFooter(); ?>
                    </div>
                    <?php
                }else {
                    messageslayout::getRecordNotFound(); //Empty Record
                } ?>
            <!-- tickets tabs and so on area -->
            <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
            <input type="hidden" name="lt" value="<?php echo $this->listtype; ?>" />
            <input type="hidden" name="c" value="ticket" />
            <input type="hidden" name="layout" value="tickets" />
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
        </form>
        </div>
    </div>
</div>
<div id="js-tk-copyright">
    <img width="85" src="http://www.joomsky.com/logo/jssupportticket_logo_small.png">&nbsp;Powered by <a target="_blank" href="http://www.joomsky.com">Joom Sky</a><br/>
    &copy;Copyright 2008 - <?php echo date('Y'); ?>, <a target="_blank" href="http://www.burujsolutions.com">Buruj Solutions</a>
</div>

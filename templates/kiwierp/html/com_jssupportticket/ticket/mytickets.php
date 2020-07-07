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
                <script type="text/javascript">
                        jQuery(document).ready(function ($) {
                                //jQuery('.custom_date').datepicker({dateFormat: 'yy-mm-dd'});
                                var combinesearch = "<?php echo isset($this->filter_data['iscombinesearch']) ? $this->filter_data['iscombinesearch'] : ''; ?>";
                                if (combinesearch) {
                                        doVisible();
                                        jQuery("#js-filter-wrapper-toggle-area").show();
                                }
                                jQuery("#js-filter-wrapper-toggle-btn").click(function () {
                                        if (jQuery("#js-filter-wrapper-toggle-search").is(":visible")) {
                                                doVisible();
                                        } else {
                                                jQuery("#js-filter-wrapper-toggle-search").show();
                                                jQuery("#js-filter-wrapper-toggle-ticketid").hide();
                                                jQuery("#js-filter-wrapper-toggle-minus").hide();
                                                jQuery("#js-filter-wrapper-toggle-plus").show();
                                        }
                                        jQuery("#js-filter-wrapper-toggle-area").toggle();
                                });
                                function doVisible() {
                                        jQuery("#js-filter-wrapper-toggle-search").hide();
                                        jQuery("#js-filter-wrapper-toggle-ticketid").show();
                                        jQuery("#js-filter-wrapper-toggle-minus").show();
                                        jQuery("#js-filter-wrapper-toggle-plus").hide();
                                }
                        });
                </script>
                <div class="mytickets">
                        <div class="row">
                                <div class="col-md-3">
                                        <?php require_once 'menu.php'; ?>
                                </div>
                                <div class="col-md-9">
                                        <form class="form form-vertical ticket-filter-form" action="<?php echo JRoute::_('index.php?option=com_jssupportticket&c=ticket&layout=mytickets&lt=' . $this->lt . '&Itemid=' . $this->Itemid); ?>" method="post" name="adminForm" id="adminForm">
                                                <div class="form-group">
                                                        <div id="js-filter-wrapper-toggle-search" >
                                                                <div class="input-group">
                                                                        <div class="input-group-addon"><span class="fa fa-search"></span></div>
                                                                        <input type="text" name="filter_ticketsearchkeys" id="filter_ticketsearchkeys" value="<?php echo isset($this->filter_data['searchkeys']) ? $this->filter_data['searchkeys'] : ''; ?>" class="text_area form-control" placeholder="<?php echo JText::_('Ticket Id') . ' ' . JText::_('Or') . ' ' . JText::_('Email address') . ' ' . JText::_('Or') . ' ' . JText::_('Subject'); ?>"/>
                                                                </div>

                                                        </div>
                                                        <div id="js-filter-wrapper-toggle-ticketid" style="display:none;">
                                                                <div class="input-group">
                                                                        <div class="input-group-addon"><span class="fa fa-search"></span></div>
                                                                        <input type="text" name="filter_ticketid" id="filter_ticketid" value="<?php if (isset($this->filter_data['ticketid'])) echo $this->filter_data['ticketid']; ?>" class="text_area form-control" placeholder="<?php echo JText::_('Ticket Id'); ?>" />
                                                                </div>
                                                        </div>
                                                        <div id="js-filter-wrapper-toggle-btn">
                                                                <div id="js-filter-wrapper-toggle-plus">
                                                                        <span class="fa fa-plus-circle"></span>
                                                                </div>
                                                                <div id="js-filter-wrapper-toggle-minus" style="display:none;">
                                                                        <span class="fa fa-minus-circle"></span>
                                                                </div>
                                                        </div>
                                                </div>
                                                <div id="js-filter-wrapper-toggle-area" style="display:none;">
                                                        <div class="row">
                                                                <div class="col-md-6">
                                                                        <div class="form-group">
                                                                                <div class="input-group">
                                                                                        <div class="input-group-addon"><span class="fa fa-user"></span></div>
                                                                                        <input type="text" name="filter_from" id="filter_from" class="text_area form-control" value="<?php if (isset($this->filter_data['from'])) echo $this->filter_data['from']; ?>" placeholder="<?php echo JText::_('From'); ?>" />
                                                                                </div>

                                                                        </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                        <div class="form-group">
                                                                                <div class="input-group">
                                                                                        <div class="input-group-addon"><span class="fa fa-envelope"></span></div>
                                                                                        <input type="text" name="filter_email" id="filter_email" class="text_area form-control" value="<?php if (isset($this->filter_data['email'])) echo $this->filter_data['email']; ?>" placeholder="<?php echo JText::_('Email'); ?>" />
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                        <div class="row">
                                                                <div class="col-md-6">
                                                                        <div class="form-group">
                                                                                <div class="input-group">
                                                                                        <div class="input-group-addon"><span class="fa fa-life-ring"></span></div>
                                                                                        <?php
                                                                                        $this->lists['departments'] = str_replace('id="filter_department"', 'class="inputbox form-control" id="filter_department"', $this->lists['departments']);
                                                                                        echo $this->lists['departments'];
                                                                                        ?>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                        <div class="form-group">
                                                                                <div class="input-group">
                                                                                        <div class="input-group-addon"><span class="fa fa-bolt"></span></div>
                                                                                        <?php
                                                                                        $this->lists['priorities'] = str_replace('id="filter_priority"', 'class="inputbox required form-control" id="filter_priority"', $this->lists['priorities']);
                                                                                        echo $this->lists['priorities'];
                                                                                        ?>

                                                                                </div>

                                                                        </div>
                                                                </div>
                                                        </div>
                                                        <div class="form-group">
                                                                <div class="input-group">
                                                                        <div class="input-group-addon"><span class="fa fa-paper-plane-o"></span></div>
                                                                        <input type="text" name="filter_subject" id="filter_subject" class="text_area form-control" value="<?php if (isset($this->filter_data['subject'])) echo $this->filter_data['subject']; ?>" placeholder="<?php echo JText::_('Subject'); ?>" />
                                                                </div>
                                                        </div>
                                                        <div class="row">
                                                                <div class="col-md-6">
                                                                        <div class="form-group">
                                                                                <?php echo JHTML::_('calendar', isset($this->filter_data['datestart']) ? $this->filter_data['datestart'] : '', 'filter_datestart', 'filter_datestart', '%Y-%m-%d', array('class' => 'inputbox form-control', 'size' => '10', 'maxlength' => '19', 'placeholder' => JText::_('Start Date'))); ?>
                                                                        </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                        <div class="form-group">
                                                                                <?php echo JHTML::_('calendar', isset($this->filter_data['dateend']) ? $this->filter_data['dateend'] : '', 'filter_dateend', 'filter_dateend', '%Y-%m-%d', array('class' => 'inputbox form-control', 'size' => '10', 'maxlength' => '19', 'placeholder' => JText::_('End Date'))); ?>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                                <div class="js-filter-wrapper">
                                                        <div class="js-filter-button">
                                                                <button class="btn btn-success" onclick="this.form.submit();"><?php echo JText::_('Search'); ?></button>
                                                                <button class="btn btn-default" onclick="this.form.getElementById('filter_ticketsearchkeys').value = '';
                                                                                        this.form.getElementById('filter_ticketid').value = '';
                                                                                        this.form.getElementById('filter_from').value = '';
                                                                                        this.form.getElementById('filter_email').value = '';
                                                                                        this.form.getElementById('filter_subject').value = '';
                                                                                        this.form.getElementById('filter_department').value = '';
                                                                                        this.form.getElementById('filter_priority').value = '';
                                                                                        this.form.getElementById('filter_datestart').value = '';
                                                                                        this.form.getElementById('filter_dateend').value = '';
                                                                                        this.form.submit();"><?php echo JText::_('Reset'); ?></button>
                                                        </div>
                                                </div>
                                        </form>
                                        <?php
                                        $email = isset($this->email) ? '&email=' . $this->email : '';
                                        $link = 'index.php?option=com_jssupportticket&c=ticket&layout=mytickets' . $email . '&lt=' . $this->lt . '&Itemid=' . $this->Itemid;
                                        $link = JRoute::_($link);
                                        if ($this->sortlinks['sortorder'] == 'ASC')
                                                $img = "components/com_jssupportticket/include/images/sort0.png";
                                        else
                                                $img = "components/com_jssupportticket/include/images/sort1.png";
                                        ?>
                                        <table class="table table-responsive table-bordered list-ticket">
                                                <thead>
                                                <th><a class="<?php if ($this->sortlinks['sorton'] == 'subject') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['subject']; ?>"><?php echo JText::_('Subject'); ?><?php if ($this->sortlinks['sorton'] == 'subject') { ?> <img src="<?php echo $img; ?>"> <?php } ?></a></th>
                                                <th class="visible-lg visible-md"><a class="<?php if ($this->sortlinks['sorton'] == 'priority') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['priority']; ?>"><?php echo JText::_('Priority'); ?><?php if ($this->sortlinks['sorton'] == 'priority') { ?> <img src="<?php echo $img; ?>"> <?php } ?></a></th>
                                                <th><a class="<?php if ($this->sortlinks['sorton'] == 'ticketid') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['ticketid']; ?>"><?php echo JText::_('Ticket ID'); ?><?php if ($this->sortlinks['sorton'] == 'ticketid') { ?> <img src="<?php echo $img; ?>"> <?php } ?></a></th>
                                                <th class="visible-lg visible-md"><a class="<?php if ($this->sortlinks['sorton'] == 'answered') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['answered']; ?>"><?php echo JText::_('Answered'); ?><?php if ($this->sortlinks['sorton'] == 'answered') { ?> <img src="<?php echo $img; ?>"> <?php } ?></a></th>
                                                <th><a class="<?php if ($this->sortlinks['sorton'] == 'status') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['status']; ?>"><?php echo JText::_('Status'); ?><?php if ($this->sortlinks['sorton'] == 'status') { ?> <img src="<?php echo $img; ?>"> <?php } ?></a></th>
                                                <th class="visible-lg visible-md"><a class="<?php if ($this->sortlinks['sorton'] == 'created') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['created']; ?>"><?php echo JText::_('Created'); ?><?php if ($this->sortlinks['sorton'] == 'created') { ?> <img src="<?php echo $img; ?>"> <?php } ?></a></th>
                                                <th class="visible-lg visible-md"><?php echo JText::_('Department'); ?></th>
                                                </thead>
                                                <?php
                                                if (!(empty($this->result)) && is_array($this->result)) {
                                                        $rows = $this->result;
                                                        $no = 1;
                                                        global $row;
                                                        $trclass = array("odd", "even");
                                                        $k = 0;
                                                        echo '<tbody>';
                                                        foreach ($rows as $row) {
                                                                $link = JRoute::_('index.php?option=com_jssupportticket&c=ticket&layout=ticketdetail&id=' . $row->id . $email . '&Itemid=' . $this->Itemid);
                                                                ?>
                                                                <tr>
                                                                        <td>
                                                                                <span class="fa fa-ticket"></span>
                                                                                <a href="<?php echo $link; ?>"><?php echo $row->subject; ?></a>
                                                                        </td>
                                                                        <td class="visible-lg visible-md">
                                                                                <span class="label label-danger"><?php echo $row->priority; ?></span>
                                                                        </td>
                                                                        <td>
                                                                                <?php echo $row->ticketid; ?>
                                                                        </td>
                                                                        <td class="visible-lg visible-md">
                                                                                <span>
                                                                                        <?php
                                                                                        if ($row->lastreply && $row->lastreply != '0000-00-00 00:00:00') {
                                                                                                echo JHTML::_('date', $row->lastreply, JText::_('DATE_FORMAT_LC4'));
                                                                                        } else {
                                                                                                //echo date("d F, Y", strtotime($row->created));
                                                                                                echo JText::_('Not filled');
                                                                                        }
                                                                                        ?>
                                                                                </span>
                                                                        </td>
                                                                        <td>
                                                                                <?php if ($row->status == 4) { ?>
                                                                                        <span class="label label-default"><?php echo JText::_('Close'); ?></span>
                                                                                <?php } elseif ($row->status == 3) { ?>
                                                                                        <span class="label label-info"><?php echo JText::_('Waiting your reply'); ?></span>
                                                                                <?php } elseif ($row->status == 1) { ?>
                                                                                        <span class="label label-primary"><?php echo JText::_('Waiting staff reply'); ?></span>
                                                                                <?php } else { ?>
                                                                                        <span class="label label-success"><?php echo JText::_('New'); ?></span>
                                                                                <?php } ?>
                                                                        </td>
                                                                        <td class="visible-lg visible-md"><span class="fa fa-calendar"></span>&nbsp;<?php echo JHTML::_('date', $row->created, JText::_('DATE_FORMAT_LC4')); ?></td>
                                                                        <td class="visible-lg visible-md"><span><?php echo $row->departmentname; ?></span></td>
                                                                </tr>
                                                                <?php
                                                        }
                                                        echo '</tbody>';
                                                        ?>

                                                        <form action="<?php echo JRoute::_('index.php?option=com_jssupportticket&c=ticket&layout=mytickets&Itemid=' . $this->Itemid); ?>" method="post">
                                                                <div id="jl_pagination" class="pagination">
                                                                        <div id="jl_pagination_pageslink">
                                                                                <?php echo $this->pagination->getPagesLinks(); ?>
                                                                        </div>
                                                                        <div id="jl_pagination_box">
                                                                                <?php
                                                                                echo JText::_('Display #');
                                                                                echo $this->pagination->getLimitBox();
                                                                                ?>
                                                                        </div>
                                                                        <div id="jl_pagination_counter">
                                                                                <?php echo $this->pagination->getResultsCounter(); ?>
                                                                        </div>
                                                                </div>
                                                        </form>
                                                        <?php
                                                } else {

                                                }
                                                ?>
                                        </table>
                                </div>
                        </div>
                </div>
        <?php } ?>
</div>
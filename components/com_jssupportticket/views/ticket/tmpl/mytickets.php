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
if ($this->config['offline'] == '1') {
    messagesLayout::getSystemOffline($this->config['title'],$this->config['offline_text']);
}else{ 
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
    <div id="js-tk-heading">
        <span id="js-tk-heading-text"><?php echo JText::_('My Tickets'); ?></span>
    </div>
    <div id="js-tk-content-wrapper">
        <div id="js-tk-mt-tabs-wrapper">
            <div id="tk_mt_tabs">
                <a class="js-col-md-2 js-col-sm-12 js-col-xs-12 js-col-md-offset-2 <?php if ($this->lt == 1) echo 'selected'; ?>" href="index.php?option=com_jssupportticket&c=ticket&layout=mytickets<?php if(isset($this->email)) echo '&email='.$this->email; ?>&lt=1&Itemid=<?php echo $this->Itemid; ?>">
                    <?php echo JText::_('Open'); if($this->config['show_count_tickets'] == 1) echo  "&nbsp;(" . $this->ticketinfo['open'] . ")"; ?>
                </a>
                <a class="js-col-md-2 js-col-sm-12 js-col-xs-12 <?php if ($this->lt == 4) echo 'selected'; ?>" href="index.php?option=com_jssupportticket&c=ticket&layout=mytickets<?php if(isset($this->email)) echo '&email='.$this->email; ?>&lt=4&Itemid=<?php echo $this->Itemid; ?>">
                    <?php echo JText::_('Closed'); if($this->config['show_count_tickets'] == 1) echo   "&nbsp;(" . $this->ticketinfo['close'] . ")"; ?>
                </a>
                <a class="js-col-md-2 js-col-sm-12 js-col-xs-12 <?php if ($this->lt == 3) echo 'selected'; ?>" href="index.php?option=com_jssupportticket&c=ticket&layout=mytickets<?php if(isset($this->email)) echo '&email='.$this->email; ?>&lt=3&Itemid=<?php echo $this->Itemid; ?>">
                    <?php echo JText::_('Answered'); if($this->config['show_count_tickets'] == 1) echo  "&nbsp;(" . $this->ticketinfo['isanswered'] . ")"; ?>
                </a>
                <a class="js-col-md-2 js-col-sm-12 js-col-xs-12 <?php if ($this->lt == 5) echo 'selected'; ?>" href="index.php?option=com_jssupportticket&c=ticket&layout=mytickets<?php if(isset($this->email)) echo '&email='.$this->email; ?>&lt=5&Itemid=<?php echo $this->Itemid; ?>">
                    <?php echo JText::_('My Tickets'); if($this->config['show_count_tickets'] == 1) echo  "&nbsp;(" . $this->ticketinfo['mytickets'] . ")"; ?>
                </a>
            </div>
        </div>
        <form class="js-tk-combinesearch" action="index.php?option=com_jssupportticket&c=ticket&layout=mytickets&lt=<?php echo $this->lt; ?>&Itemid=<?php echo $this->Itemid; ?>" method="post" name="adminForm" id="adminForm">
            <div class="js-col-md-12 js-filter-wrapper js-filter-wrapper-position"> 
                <div class="js-col-md-12 js-filter-value" id="js-filter-wrapper-toggle-search" ><input type="text" name="filter_ticketsearchkeys" id="filter_ticketsearchkeys" value="<?php echo isset($this->filter_data['searchkeys']) ? $this->filter_data['searchkeys'] : ''; ?>" class="text_area" placeholder="<?php echo JText::_('Ticket Id').' '.JText::_('Or').' '.JText::_('Email address').' '.JText::_('Or').' '.JText::_('Subject'); ?>"/></div>
                <div class="js-col-md-12 js-filter-value" id="js-filter-wrapper-toggle-ticketid" style="display:none;"><input type="text" name="filter_ticketid" id="filter_ticketid" value="<?php if (isset($this->filter_data['ticketid'])) echo $this->filter_data['ticketid']; ?>" class="text_area" placeholder="<?php echo JText::_('Ticket Id'); ?>" /></div>
                <div id="js-filter-wrapper-toggle-btn">
                    <div id="js-filter-wrapper-toggle-plus">
                        <img src="components/com_jssupportticket/include/images/plus.png"/>
                    </div> 
                    <div id="js-filter-wrapper-toggle-minus" style="display:none;">
                        <img src="components/com_jssupportticket/include/images/minus.png"/>
                    </div>
                </div>
            </div>
            <div id="js-filter-wrapper-toggle-area" style="display:none;">
                <div class="js-col-md-12 js-filter-wrapper">    
                    <div class="js-col-md-6 js-filter-value js-margin-bottom-xs"><input type="text" name="filter_from" id="filter_from" class="text_area" value="<?php if (isset($this->filter_data['from'])) echo $this->filter_data['from']; ?>" placeholder="<?php echo JText::_('From'); ?>" /></div>
                    <div class="js-col-md-6 js-filter-value"><input type="text" name="filter_email" id="filter_email" class="text_area" value="<?php if (isset($this->filter_data['email'])) echo $this->filter_data['email']; ?>" placeholder="<?php echo JText::_('Email'); ?>" /></div>
                </div>
                <div class="js-col-md-12 js-filter-wrapper">    
                    <div class="js-col-md-6 js-filter-value js-margin-bottom-xs"><?php echo $this->lists['departments']; ?></div>
                    <div class="js-col-md-6 js-filter-value"><?php echo $this->lists['priorities']; ?></div>
                </div>
                <div class="js-col-md-12 js-filter-wrapper">    
                    <div class="js-col-md-12 js-filter-value"><input type="text" name="filter_subject" id="filter_subject" class="text_area" value="<?php if (isset($this->filter_data['subject'])) echo $this->filter_data['subject']; ?>" placeholder="<?php echo JText::_('Subject'); ?>" /></div>
                </div>
                <div class="js-col-md-12 js-filter-wrapper">    
                    <div class="js-col-md-4 js-filter-value"><?php echo JHTML::_('calendar', isset($this->filter_data['datestart']) ? $this->filter_data['datestart'] : '', 'filter_datestart', 'filter_datestart', '%Y-%m-%d', array('class' => 'inputbox', 'size' => '10', 'maxlength' => '19' , 'placeholder' => JText::_('Start Date'))); ?></div>
                    <div class="js-col-md-4 js-ticket-special-character js-nullpadding">-</div>
                    <div class="js-col-md-4 js-filter-value"><?php echo JHTML::_('calendar', isset($this->filter_data['dateend']) ? $this->filter_data['dateend'] : '', 'filter_dateend', 'filter_dateend', '%Y-%m-%d', array('class' => 'inputbox', 'size' => '10', 'maxlength' => '19', 'placeholder' => JText::_('End Date'))); ?></div>
                </div>
            </div>
            <div class="js-col-md-12 js-filter-wrapper">
                <div class="js-filter-button">
                    <button class="tk-dft-btn" onclick="this.form.submit();"><?php echo JText::_('Search'); ?></button>
                    <button class="tk-dft-btn" onclick="this.form.getElementById('filter_ticketsearchkeys').value = ''; 
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
        $email = isset($this->email) ? '&email='.$this->email : '';
        $link = 'index.php?option=com_jssupportticket&c=ticket&layout=mytickets' . $email . '&lt=' . $this->lt . '&Itemid=' . $this->Itemid;
        if ($this->sortlinks['sortorder'] == 'ASC')
            $img = "components/com_jssupportticket/include/images/sort0.png";
        else
            $img = "components/com_jssupportticket/include/images/sort1.png";
        ?>
        <div id="tk_mt_sort_wraper">
            <ul id="tk_mt_sorts_menu">
                <li class="tk_mt_sorts_menu_link js-col-md-2 js-col-sm-6 js-col-xs-12">
                    <a class="<?php if ($this->sortlinks['sorton'] == 'subject') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['subject']; ?>"><?php echo JText::_('Subject'); ?><?php if ($this->sortlinks['sorton'] == 'subject') { ?> <img src="<?php echo $img; ?>"> <?php } ?></a>
                </li>
                <li class="tk_mt_sorts_menu_link js-col-md-2 js-col-sm-6 js-col-xs-12">
                    <a class="<?php if ($this->sortlinks['sorton'] == 'priority') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['priority']; ?>"><?php echo JText::_('Priority'); ?><?php if ($this->sortlinks['sorton'] == 'priority') { ?> <img src="<?php echo $img; ?>"> <?php } ?></a>
                </li>
                <li class="tk_mt_sorts_menu_link js-col-md-2 js-col-sm-6 js-col-xs-12">
                    <a class="<?php if ($this->sortlinks['sorton'] == 'ticketid') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['ticketid']; ?>"><?php echo JText::_('Ticket ID'); ?><?php if ($this->sortlinks['sorton'] == 'ticketid') { ?> <img src="<?php echo $img; ?>"> <?php } ?></a>
                </li>
                <li class="tk_mt_sorts_menu_link js-col-md-2 js-col-sm-6 js-col-xs-12">
                    <a class="<?php if ($this->sortlinks['sorton'] == 'answered') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['answered']; ?>"><?php echo JText::_('Answered'); ?><?php if ($this->sortlinks['sorton'] == 'answered') { ?> <img src="<?php echo $img; ?>"> <?php } ?></a>
                </li>
                <li class="tk_mt_sorts_menu_link js-col-md-2 js-col-sm-6 js-col-xs-12">
                    <a class="<?php if ($this->sortlinks['sorton'] == 'status') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['status']; ?>"><?php echo JText::_('Status'); ?><?php if ($this->sortlinks['sorton'] == 'status') { ?> <img src="<?php echo $img; ?>"> <?php } ?></a>
                </li>
                <li class="tk_mt_sorts_menu_link js-col-md-2 js-col-sm-6 js-col-xs-12">
                    <a class="<?php if ($this->sortlinks['sorton'] == 'created') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['created']; ?>"><?php echo JText::_('Created'); ?><?php if ($this->sortlinks['sorton'] == 'created') { ?> <img src="<?php echo $img; ?>"> <?php } ?></a>
                </li>
            </ul>
        </div>
    <?php
    if (!(empty($this->result)) && is_array($this->result)) {
        $rows = $this->result;
        $no = 1;
        global $row;
        $trclass = array("odd", "even");
        $k = 0;
        foreach ($rows as $row) {
            $link = JFilterOutput::ampReplace('index.php?option=com_jssupportticket&c=ticket&layout=ticketdetail&id=' . $row->id . $email . '&Itemid=' . $this->Itemid);
            ?>
            <div class="tk_mt_detail_main">
                <div class="tk_mt_detail_image js-col-md-1 js-col-sm-1 js-col-xs-4">
                    <img  src="components/com_jssupportticket/include/images/user.png" alt="<?php echo JText::_('New Ticket'); ?>" />
                </div>
                <div class="tk_mt_detail_desc js-col-md-7 js-col-sm-7 js-col-xs-8">
                    <div class="tk_mt_detail_desc_top">
                        <span class="tk_mt_detail_text"><?php echo JText::_('Subject'); ?> : </span>
                        <span class="tk_mt_detail_value_main">
                            <a class="tk_mt_detail_link" href="<?php echo $link; ?>"><?php echo $row->subject; ?> </a>
                        </span>
                    </div>
                    <div class="tk_mt_detail_desc_middle">
                        <div class="tk_mt_detail_desc_bottom">
                            <span class="tk_mt_detail_text"><?php echo JText::_('From'); ?>:</span>
                            <span class="tk_mt_detail_value"><?php echo $row->name; ?></span>
                        </div>
                        <div id='tk_mt_status'>
                        <?php if ($row->status == 4) { ?>
                            <span class="tk_mt_detail_status tk_mt_detail_status_close"><?php echo JText::_('Close'); ?> </span>
                        <?php }elseif ($row->status == 3) { ?> 
                            <span class="tk_mt_detail_status tk_mt_detail_status_reply_cus"><?php echo JText::_('Waiting your reply'); ?></span>
                        <?php } elseif ($row->status == 1) { ?>
                            <span class="tk_mt_detail_status tk_mt_detail_status_reply_staff"><?php echo JText::_('Waiting staff reply'); ?></span>
                        <?php } else { ?>
                            <span class="tk_mt_detail_status tk_mt_detail_status_progress"><?php echo JText::_('New'); ?> </span>
                        <?php } ?>
                            </div>   
                        </div>
                        <div class="tk_mt_detail_desc_bottom">
                            <span class="tk_mt_detail_text"><?php echo JText::_('Department'); ?>:</span>
                            <span class="tk_mt_detail_value"><?php echo $row->departmentname; ?></span>
                        </div>
                    </div>
                    <div class="tk_mt_detail_info js-col-md-4 js-col-sm-4 js-col-xs-12" >
                        <div id="tk_mt_detail_info_key_value_wraper">
                            <span class="tk_detail_info_key"><?php echo JText::_('Ticket ID'); ?>:</span>
                            <span class="tk_detail_info_value"><?php echo $row->ticketid; ?></span>
                        </div>
                        <div id="tk_mt_detail_info_key_value_wraper">
                            <span class="tk_detail_info_key"><?php echo JText::_('Last reply'); ?>:</span>
                            <span class="tk_detail_info_value">
                                <?php
                                if ($row->lastreply && $row->lastreply != '0000-00-00 00:00:00') {
                                    echo date("d F, Y", strtotime($row->lastreply));
                                } else {
                                    //echo date("d F, Y", strtotime($row->created));
                                    echo JText::_('Not filled');
                                }
                                ?>
                            </span>
                        </div>
                        <div id="tk_mt_detail_info_key_value_wraper">
                            <span class="tk_detail_info_key"><?php echo JText::_('Priority'); ?>:</span>
                            <span id="tk_mt_priority" class="tk_detail_info_value priority" style="background: <?php echo $row->prioritycolour; ?>;"><?php echo $row->priority; ?></span>
                        </div>
                    </div>
                </div>
            <?php } ?>
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

    <?php } else {
        messagesLayout::getRecordNotFound();
        } ?>

<?php } ?>
        </div>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            /*
            var classes = ".tk_mt_detail_status_reply_cus, .tk_mt_detail_status_reply_staff ";
            jQuery(classes).on("mouseover", function () {

                var wait_text = "";
                var current_class = jQuery(this).attr('class');
                if (current_class === "tk_mt_detail_status tk_mt_detail_status_reply_cus") {
                    wait_text = "<?php echo JText::_('Waiting your reply'); ?>";
                } else if (current_class === "tk_mt_detail_status tk_mt_detail_status_reply_staff") {
                    wait_text = "<?php echo JText::_('Waiting Staff Reply'); ?>";
                }
                jQuery(this).text(wait_text);
                jQuery(this).stop().animate({width: '150px'}, 500);
            });
            jQuery(classes).on("mouseout", function () {
                var wait_text = "<?php echo JText::_('Waiting'); ?>...";
                jQuery(this).text(wait_text);
                jQuery(this).stop().animate({width: '70px'}, 500);
            });


            jQuery('div.tk_mt_create_info').on("mouseover", function () {

                var obj = this;
                var scrollingWidth = jQuery(obj).find('span.tk_mt_create_info_text span.tk_mt_detail_text_sliding').width();
                scrollingWidth = scrollingWidth + 10;
                var initialOffset = jQuery(obj).find('span.tk_mt_create_info_text span.tk_mt_detail_text_sliding').offset().left;
                stopAnim = false;
                animateTitle(obj, scrollingWidth, initialOffset);
            });
            jQuery('div.tk_mt_create_info').on("mouseout", function () {
                obj = this;
                stopAnim = true;
                jQuery(obj).find('span.tk_mt_create_info_text span.tk_mt_detail_text_sliding').stop(true, true).css("left", "0");
            });

            var stopAnim = false;
            function animateTitle(obj, scrollingWidth, initialOffset) {
                if (!stopAnim) {
                    var $span = jQuery(obj).find('span.tk_mt_create_info_text span.tk_mt_detail_text_sliding');

                    var parent_div_width = jQuery(obj).width();

                    var child_div = jQuery(obj).find('span.tk_mt_create_info_text span.tk_mt_detail_text_clr').width();

                    var scroll_div_width = parent_div_width - child_div;

                    var animatewidth = (jQuery($span).width()) / 4;

                    if ((animatewidth < scroll_div_width)) {
                        $span.animate({left: (($span.offset().left === (scrollingWidth + initialOffset)) ? -initialOffset : ("-=" + animatewidth))},
                        {
                            duration: 6000,
                            easing: 'swing',
                            complete: function () {
                                if ($span.offset().left < scroll_div_width) {
                                    jQuery(this).css("left", scrollingWidth);
                                }
                            }
                        });
                    }
                }
            }
            */
        });

    </script>
<div id="js-tk-copyright">
    <img width="85" src="http://www.joomsky.com/logo/jssupportticket_logo_small.png">&nbsp;Powered by <a target="_blank" href="http://www.joomsky.com">Joom Sky</a><br/>
    &copy;Copyright 2008 - <?php echo date('Y'); ?>, <a target="_blank" href="http://www.burujsolutions.com">Buruj Solutions</a>
</div>
</div>
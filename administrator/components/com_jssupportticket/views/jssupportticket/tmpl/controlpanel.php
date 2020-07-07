<?php
/**
 * @Copyright Copyright (C) 2015 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:     Buruj Solutions
 + Contact:     www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:  May 22, 2015
  ^
  + Project:    JS Tickets
  ^
 */
defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script>
<script>
    google.setOnLoadCallback(drawStackChartHorizontal);
    function drawStackChartHorizontal() {
      var data = google.visualization.arrayToDataTable([
        <?php
            echo $this->result['stack_chart_horizontal']['title'].',';
            echo $this->result['stack_chart_horizontal']['data'];
        ?>
      ]);

      var view = new google.visualization.DataView(data);

      var options = {
        height:250,
        legend: { position: 'top', maxLines: 3 },
        bar: { groupWidth: '75%' },
        isStacked: true
      };
      var chart = new google.visualization.BarChart(document.getElementById("stack_chart_horizontal"));
      chart.draw(view, options);
    }
</script>
<div id="js-tk-admin-wrapper">
    <div id="js-tk-leftmenu">
        <?php include_once('components/com_jssupportticket/views/menu.php'); ?>
    </div>
    <div id="js-tk-cparea">
        <div id="js-tk-heading">
            <h4><img id="js-admin-responsive-menu-link" src="components/com_jssupportticket/include/images/c_p/left-icons/menu.png" /><?php echo JText::_('Control Panel'); ?></h4>
            <div class="right_side">
                <?php
                    $url = 'http://www.joomsky.com/jssupportticketsys/joomla/getlatestversion.php';
                    $pvalue = "dt=".date('Y-m-d');
                    if  (in_array  ('curl', get_loaded_extensions())) {
                        $ch = curl_init();
                        curl_setopt($ch,CURLOPT_URL,$url);
                        curl_setopt($ch,CURLOPT_POST,8);
                        curl_setopt($ch,CURLOPT_POSTFIELDS,$pvalue);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
                        $curl_errno = curl_errno($ch);
                        $curl_error = curl_error($ch);
                        $result = curl_exec($ch);
                        curl_close($ch);
                        if($result == str_replace('.', '', $this->version['version'])){ ?>
                            <img src="components/com_jssupportticket/include/images/latestversion.png" height="35" width="35" title="<?php echo JText::_('Your System Is Up To Date'); ?>">
                            <?php echo JText::_('Your System Is Up To Date'); ?>
                            </a>
                        <?php   
                        }elseif($result){ ?>
                            <img src="components/com_jssupportticket/include/images/newversion.png" height="35" width="35" title="<?php echo JText::_('New Version Is Available'); ?>">
                            <?php echo JText::_('New Version Is Available'); ?>
                        <?php           
                        }else{ ?>
                            <img src="components/com_jssupportticket/include/images/unableconnect.png" height="35" width="35" title="<?php echo JText::_('Unable Connect To Server'); ?>">
                            <?php echo JText::_('Unable Connect To Server'); ?>
                        <?php           
                        }
                    }else{ ?>
                        <img src="components/com_jssupportticket/include/images/unableconnect.png" height="35" width="35" title="<?php echo JText::_('Unable Connect To Server'); ?>">
                        <?php echo JText::_('Unable Connect To Server'); ?>
                    <?php           
                    }
                ?>
            </div>
        </div>
        <div class="js-col-md-12">
        <div id="graph-area">
            <div id="stack_chart_horizontal" style="width:100%;"></div>
        </div>
        <div class="js-admin-report-box-wrapper js-admin-controlpanel">
            <div class="js-col-md-4 js-admin-box js-col-md-offset-2 box1" >
                <div class="js-col-md-4 js-admin-box-image">
                    <img src="components/com_jssupportticket/include/images/report/ticket_icon.png" />
                </div>
                <div class="js-col-md-8 js-admin-box-content">
                    <div class="js-col-md-12 js-admin-box-content-number"><?php echo $this->result['ticket_total']['openticket']; ?></div>
                    <div class="js-col-md-12 js-admin-box-content-label"><?php echo JText::_("New"); ?></div>
                </div>
                <div class="js-col-md-12 js-admin-box-label"></div>
            </div>  
            <div class="js-col-md-4 js-admin-box box2">
                <div class="js-col-md-4 js-admin-box-image">
                    <img src="components/com_jssupportticket/include/images/report/ticket_answered.png" />
                </div>
                <div class="js-col-md-8 js-admin-box-content">
                    <div class="js-col-md-12 js-admin-box-content-number"><?php echo $this->result['ticket_total']['answeredticket']; ?></div>
                    <div class="js-col-md-12 js-admin-box-content-label"><?php echo JText::_("Answered"); ?></div>
                </div>
                <div class="js-col-md-12 js-admin-box-label"></div>
            </div>  
            <div class="js-col-md-4 js-admin-box box3">
                <div class="js-col-md-4 js-admin-box-image">
                    <img src="components/com_jssupportticket/include/images/report/ticket_pending.png" />
                </div>
                <div class="js-col-md-8 js-admin-box-content">
                    <div class="js-col-md-12 js-admin-box-content-number"><?php echo $this->result['ticket_total']['pendingticket']; ?></div>
                    <div class="js-col-md-12 js-admin-box-content-label"><?php echo JText::_("Pending"); ?></div>
                </div>
                <div class="js-col-md-12 js-admin-box-label"></div>
            </div>  
        </div>
        <div id="js-maincp-area">
        <div class="js-mnu-sub-heading"><?php echo JText::_("Control Panel"); ?></div>
        
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=ticket&layout=tickets">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/my_tickets.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Tickets'); ?></span></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>    
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/staffs.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Staff members'); ?></span><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>    
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=config&layout=config">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/configuration.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Configuration'); ?></span></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/ticket_via_email.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Ticket Via Email'); ?></span><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=department&layout=departments">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/departments.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Departments'); ?></span></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>    
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/add_article.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Knowledge Base'); ?></span><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/downloads.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Downloads'); ?></span><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/announcements.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Announcements'); ?></span><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/faqs.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('FAQs'); ?></span><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/mail.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Mail'); ?></span><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/roles.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Roles'); ?></span><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>    
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=priority&layout=priorities">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/priorities.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Priorities'); ?></span></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>    
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/categories.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Categories'); ?></span><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>    
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=email&layout=emails">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/mailsystem.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('System Emails'); ?></span></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/premade.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Premade'); ?></span><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/help-topic.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Help Topic'); ?></span><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=userfields&layout=userfields&ff=1">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/userfield.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('User Fields'); ?></span></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=userfields&layout=fieldsordering&ff=1">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/field-ordering.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Field Ordering'); ?></span></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=systemerrors&layout=systemerrors">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/system-error.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('System Errors'); ?></span></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/banemail.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Ban Emails'); ?></span><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/banemailloglist.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Ban Email Log List'); ?></span><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/themes.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Themes'); ?></span><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=emailtemplate&layout=emailtemplate&tf=ew-tk">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/email_temp.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Email Templates'); ?></span></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=reports&layout=overallreports">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/reports.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Reports'); ?></span></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=aboutus">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/aboutus.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('About Us'); ?></span></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="index.php?option=com_jssupportticket&c=proinstaller&layout=step1">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/update.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Update'); ?></span></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <div class="js-mnu-sub-heading"><?php echo JText::_("Support Area"); ?></div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="http://www.joomsky.com/jssupportticketsys/joomla/documentation.php" target="_blank">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/documentation.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Documentation'); ?></span></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="http://www.joomsky.com/jssupportticketsys/joomla/forum.php" target="_blank">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/forum.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Forum'); ?></span></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <div class="js-col-xs-12 js-col-md-3 js-mnu-wrapper">
            <a class="js-mnu-area" href="http://www.joomsky.com/jssupportticketsys/joomla/ticket.php" target="_blank">
                <div class="js-mnu-icon"><img src="components/com_jssupportticket/include/images/c_p/support.png"/></div>
                <div class="js-mnu-text"><span> <?php echo JText::_('Support'); ?></span></div>
                <div class="js-mnu-arrowicon"><img src="components/com_jssupportticket/include/images/c_p/arrow_icon.png"/></div>
            </a>
        </div>
        <div class="js-mnu-sub-heading"><?php echo JText::_("Latest Tickets"); ?></div>
        <div class="js-ticket-admin-cp-tickets">
            <div class="js-row js-ticket-admin-cp-head js-ticket-admin-hide-head">
                <div class="js-col-xs-12 js-col-md-2"><?php echo JText::_('Ticket Id'); ?></div>
                <div class="js-col-xs-12 js-col-md-3"><?php echo JText::_('Subject'); ?></div>
                <div class="js-col-xs-12 js-col-md-1"><?php echo JText::_('Status'); ?></div>
                <div class="js-col-xs-12 js-col-md-2"><?php echo JText::_('From'); ?></div>
                <div class="js-col-xs-12 js-col-md-2"><?php echo JText::_('Priority'); ?></div>
                <div class="js-col-xs-12 js-col-md-2"><?php echo JText::_('Created'); ?></div>
            </div>
            <?php foreach ($this->result['tickets'] AS $ticket): ?>
                <div class="js-ticket-admin-cp-data">
                    <div class="js-col-xs-12 js-col-md-2"><span class="js-ticket-admin-cp-showhide"><?php echo JText::_('Ticket Id');
            echo " : "; ?></span> <a href="index.php?option=com_jssupportticket&c=ticket&layout=ticketdetails&cid[]=<?php echo $ticket->id; ?>"><?php echo $ticket->ticketid; ?></a></div>
                    <div class="js-col-xs-12 js-col-md-3 js-admin-cp-text-elipses"><span class="js-ticket-admin-cp-showhide" ><?php echo JText::_('Subject');
            echo " : "; ?></span> <?php echo $ticket->subject; ?></div>
                    <div class="js-col-xs-12 js-col-md-1">
                        <span class="js-ticket-admin-cp-showhide" ><?php echo JText::_('Status');
            echo " : "; ?></span>
                        <?php
                        if ($ticket->status == 0) {
                            $style = "red;";
                            $status = JText::_('New');
                        } elseif ($ticket->status == 1) {
                            $style = "orange;";
                            $status = JText::_('Waiting Staff Reply');
                        } elseif ($ticket->status == 2) {
                            $style = "#FF7F50;";
                            $status = JText::_('In progress');
                        } elseif ($ticket->status == 3) {
                            $style = "green;";
                            $status = JText::_('Waiting your reply');
                        } elseif ($ticket->status == 4) {
                            $style = "blue;";
                            $status = JText::_('Closed');
                        }
                        echo '<span style="color:' . $style . '">' . $status . '</span>';
                        ?>
                    </div>
                    <div class="js-col-xs-12 js-col-md-2"> <span class="js-ticket-admin-cp-showhide" ><?php echo JText::_('From');
                        echo " : "; ?></span> <?php echo $ticket->name; ?></div>
                    <div class="js-col-xs-12 js-col-md-2" style="color:<?php echo $ticket->prioritycolour; ?>;"> <span class="js-ticket-admin-cp-showhide" ><?php echo JText::_('Priority');
            echo " : "; ?></span> <?php echo JText::_($ticket->priority); ?></div>
                    <div class="js-col-xs-12 js-col-md-2"><span class="js-ticket-admin-cp-showhide" ><?php echo JText::_('Created');
            echo " : "; ?></span> <?php echo date($this->config['date_format'], strtotime($ticket->created)); ?></div>
                </div>
        <?php endforeach; ?>
        </div>
        <div class="js-mnu-sub-heading"><?php echo JText::_("Make Review"); ?></div>
        <div class="js-ticket-admin-cp-review">
            <div class="js-ticket-admin-cp-review-upper">
                <img class="leftimage" src="components/com_jssupportticket/include/images/c_p/review.png" />
                <div class="js-ticket-admin-reivew-content">
                    <?php echo JText::_('If you use ').'<b>'.JText::_('JS Support Ticket').'</b>'.JText::_(', please write a appreciated review at'); ?>
                    <a href="http://extensions.joomla.org/extensions/extension/clients-a-communities/help-desk/js-support-ticket" target="_blank"><?php echo JText::_('Joomla Extensions Direcotroy'); ?><img src="components/com_jssupportticket/include/images/c_p/arrow.png" /></a>
                </div>
                <img class="rightimage" src="components/com_jssupportticket/include/images/c_p/star.png" />
            </div>
        </div>
    </div>
    </div>
    </div>
</div>
<div id="js-tk-copyright">
    <img width="85" src="http://www.joomsky.com/logo/jssupportticket_logo_small.png">&nbsp;Powered by <a target="_blank" href="http://www.joomsky.com">Joom Sky</a><br/>
    &copy;Copyright 2008 - <?php echo date('Y'); ?>, <a target="_blank" href="http://www.burujsolutions.com">Buruj Solutions</a>
</div>

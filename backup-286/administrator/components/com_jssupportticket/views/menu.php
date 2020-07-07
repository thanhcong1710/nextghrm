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
if (!defined('_VALID_MOS') && !defined('_JEXEC'))
    die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
?>
<div id="js-tk-links">
    <div class="js-divlink">
        <a href="index.php?option=com_jssupportticket&c=jssupportticket&layout=controlpanel">
            <img src="components/com_jssupportticket/include/images/c_p/left-icons/admin.png"/>
        </a>
        <a href="#" class="js-parent"><span class="text"><?php echo JText::_('Home'); ?> <img class="arrow" src="components/com_jssupportticket/include/images/c_p/arrow1.png"/></span></a>
        <div class="js-innerlink">
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=controlpanel"><span class="text"><?php echo JText::_('Control Panel'); ?></span></a>            
            <a class="js-child" href="index.php?option=com_jssupportticket&c=proinstaller&layout=step1"><span class="text"><?php echo JText::_('Update'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=aboutus"><span class="text"><?php echo JText::_('About Us'); ?></span></a>
        </div>
    </div>
    <div class="js-divlink">
        <a href="index.php?option=com_jssupportticket&c=ticket&layout=tickets">
            <img src="components/com_jssupportticket/include/images/c_p/left-icons/tickets.png"/>
        </a>
        <a href="#" class="js-parent"><span class="text"><?php echo JText::_('Tickets'); ?><img class="arrow" src="components/com_jssupportticket/include/images/c_p/arrow1.png"/></span></a>
        <div class="js-innerlink">
            <a class="js-child" href="index.php?option=com_jssupportticket&c=ticket&layout=tickets"><span class="text"><?php echo JText::_('Tickets'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=ticket&layout=formticket"><span class="text"><?php echo JText::_('Add Ticket'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=userfields&layout=userfields&ff=1"><span class="text"><?php echo JText::_('User Fields'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=userfields&layout=fieldsordering&ff=1"><span class="text"><?php echo JText::_('Field Ordering'); ?></span></a>
        </div>
    </div>
    <div class="js-divlink">
        <a href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
            <img src="components/com_jssupportticket/include/images/c_p/left-icons/staff_members.png"/>
        </a>
        <a href="#" class="js-parent"><span class="text"><?php echo JText::_('Staff members'); ?><img class="arrow" src="components/com_jssupportticket/include/images/c_p/arrow1.png"/><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></span></a>
        <div class="js-innerlink">
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('Staff members'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('Add staff member'); ?></span></a>
        </div>
    </div>
    <div class="js-divlink">
        <a href="index.php?option=com_jssupportticket&c=config&layout=config">
            <img src="components/com_jssupportticket/include/images/c_p/left-icons/configuration.png"/>
        </a>
        <a href="#" class="js-parent"><span class="text"><?php echo JText::_('Configurations'); ?><img class="arrow" src="components/com_jssupportticket/include/images/c_p/arrow1.png"/></span></a>
        <div class="js-innerlink">
            <a class="js-child" href="index.php?option=com_jssupportticket&c=config&layout=config"><span class="text"><?php echo JText::_('Configurations'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('Ticket via email'); ?><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('Themes'); ?><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></span></a>
        </div>
    </div>
    <div class="js-divlink">
        <a href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
            <img src="components/com_jssupportticket/include/images/c_p/left-icons/categories.png"/>
        </a>
        <a href="#" class="js-parent"><span class="text"><?php echo JText::_('Categories'); ?><img class="arrow" src="components/com_jssupportticket/include/images/c_p/arrow1.png"/><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></span></a>
        <div class="js-innerlink">
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('Categories'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('Add Category'); ?></span></a>
        </div>
    </div>
    <div class="js-divlink">
        <a href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
            <img src="components/com_jssupportticket/include/images/c_p/left-icons/knowledgebase.png"/>
        </a>
        <a href="#" class="js-parent"><span class="text"><?php echo JText::_('Knowledge Base'); ?><img class="arrow" src="components/com_jssupportticket/include/images/c_p/arrow1.png"/><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></span></a>
        <div class="js-innerlink">
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('Knowledge Base'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('Add Knowledge Base'); ?></span></a>
        </div>
    </div>
    <div class="js-divlink">
        <a href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
            <img src="components/com_jssupportticket/include/images/c_p/left-icons/downloads.png"/>
        </a>
        <a href="#" class="js-parent"><span class="text"><?php echo JText::_('Downloads'); ?><img class="arrow" src="components/com_jssupportticket/include/images/c_p/arrow1.png"/><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></span></a>
        <div class="js-innerlink">
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('Downloads'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('Add Download'); ?></span></a>
        </div>
    </div>
    <div class="js-divlink">
        <a href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
            <img src="components/com_jssupportticket/include/images/c_p/left-icons/announcements.png"/>
        </a>
        <a href="#" class="js-parent"><span class="text"><?php echo JText::_('Announcements'); ?><img class="arrow" src="components/com_jssupportticket/include/images/c_p/arrow1.png"/><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></span></a>
        <div class="js-innerlink">
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('Announcements'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('Add Announcement'); ?></span></a>
        </div>
    </div>
    <div class="js-divlink">
        <a href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
            <img src="components/com_jssupportticket/include/images/c_p/left-icons/faqs.png"/>
        </a>
        <a href="#" class="js-parent"><span class="text"><?php echo JText::_('FAQs'); ?><img class="arrow" src="components/com_jssupportticket/include/images/c_p/arrow1.png"/><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></span></a>
        <div class="js-innerlink">
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('FAQs'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('Add FAQ'); ?></span></a>
        </div>
    </div>
    <div class="js-divlink">
        <a href="index.php?option=com_jssupportticket&c=department&layout=departments">
            <img src="components/com_jssupportticket/include/images/c_p/left-icons/departments.png"/>
        </a>
        <a href="#" class="js-parent"><span class="text"><?php echo JText::_('Departments'); ?><img class="arrow" src="components/com_jssupportticket/include/images/c_p/arrow1.png"/></span></a>
        <div class="js-innerlink">
            <a class="js-child" href="index.php?option=com_jssupportticket&c=department&layout=departments"><span class="text"><?php echo JText::_('Departments'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=department&layout=formdepartment"><span class="text"><?php echo JText::_('Add Department'); ?></span></a>
        </div>
    </div>
    <div class="js-divlink">
        <a href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
            <img src="components/com_jssupportticket/include/images/c_p/left-icons/helptopic.png"/>
        </a>
        <a href="#" class="js-parent"><span class="text"><?php echo JText::_('Help Topics'); ?><img class="arrow" src="components/com_jssupportticket/include/images/c_p/arrow1.png"/><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></span></a>
        <div class="js-innerlink">
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('Help Topics'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('Add Help Topic'); ?></span></a>
        </div>
    </div>
    <div class="js-divlink">
        <a href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
            <img src="components/com_jssupportticket/include/images/c_p/left-icons/premade.png"/>
        </a>
        <a href="#" class="js-parent"><span class="text"><?php echo JText::_('Premade'); ?><img class="arrow" src="components/com_jssupportticket/include/images/c_p/arrow1.png"/><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></span></a>
        <div class="js-innerlink">
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('Premade'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('Add Premade Message'); ?></span></a>
        </div>
    </div>
    <div class="js-divlink">
        <a href="index.php?option=com_jssupportticket&c=priority&layout=priorities">
            <img src="components/com_jssupportticket/include/images/c_p/left-icons/priorities.png"/>
        </a>
        <a href="#" class="js-parent"><span class="text"><?php echo JText::_('Priorities'); ?><img class="arrow" src="components/com_jssupportticket/include/images/c_p/arrow1.png"/></span></a>
        <div class="js-innerlink">
            <a class="js-child" href="index.php?option=com_jssupportticket&c=priority&layout=priorities"><span class="text"><?php echo JText::_('Priorities'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=priority&layout=formpriority"><span class="text"><?php echo JText::_('Add Priority'); ?></span></a>
        </div>
    </div>
    <div class="js-divlink">
        <a href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
            <img src="components/com_jssupportticket/include/images/c_p/left-icons/roles.png"/>
        </a>
        <a href="#" class="js-parent"><span class="text"><?php echo JText::_('Roles'); ?><img class="arrow" src="components/com_jssupportticket/include/images/c_p/arrow1.png"/><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></span></a>
        <div class="js-innerlink">
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('Roles'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('Add Role'); ?></span></a>
        </div>
    </div>
    <div class="js-divlink">
        <a href="index.php?option=com_jssupportticket&c=email&layout=emails">
            <img src="components/com_jssupportticket/include/images/c_p/left-icons/system-emails.png"/>
        </a>
        <a href="#" class="js-parent"><span class="text"><?php echo JText::_('System Emails'); ?><img class="arrow" src="components/com_jssupportticket/include/images/c_p/arrow1.png"/></span></a>
        <div class="js-innerlink">
            <a class="js-child" href="index.php?option=com_jssupportticket&c=email&layout=emails"><span class="text"><?php echo JText::_('Emails'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=email&layout=formemail"><span class="text"><?php echo JText::_('Add Email'); ?></span></a>
        </div>
    </div>
    <div class="js-divlink">
        <a href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
            <img src="components/com_jssupportticket/include/images/c_p/left-icons/mail.png"/>
        </a>
        <a href="#" class="js-parent"><span class="text"><?php echo JText::_('Mail'); ?><img class="arrow" src="components/com_jssupportticket/include/images/c_p/arrow1.png"/><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></span></a>
        <div class="js-innerlink">
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('Mail'); ?></span></a>
        </div>
    </div>
    <div class="js-divlink">
        <a href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion">
            <img src="components/com_jssupportticket/include/images/c_p/left-icons/banned-emails.png"/>
        </a>
        <a href="#" class="js-parent"><span class="text"><?php echo JText::_('Banned Emails'); ?><img class="arrow" src="components/com_jssupportticket/include/images/c_p/arrow1.png"/><img class="proicon" src="components/com_jssupportticket/include/images/pro-icon.png" /></span></a>
        <div class="js-innerlink">
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('Ban Emails'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('Add Email'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=jssupportticket&layout=proversion"><span class="text"><?php echo JText::_('Banlist log'); ?></span></a>
        </div>
    </div>
    <div class="js-divlink">
        <a href="index.php?option=com_jssupportticket&c=emailtemplate&layout=emailtemplate&tf=tk-ew-ad">
            <img src="components/com_jssupportticket/include/images/c_p/left-icons/email-template.png"/>
        </a>
        <a href="#" class="js-parent"><span class="text"><?php echo JText::_('Email Templates'); ?><img class="arrow" src="components/com_jssupportticket/include/images/c_p/arrow1.png"/></span></a>
        <div class="js-innerlink">
            <a class="js-child" href="index.php?option=com_jssupportticket&c=emailtemplate&layout=emailtemplate&tf=tk-ew-ad"><span class="text"><?php echo JText::_('New Ticket Admin Alert'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=emailtemplate&layout=emailtemplate&tf=ew-tk"><span class="text"><?php echo JText::_('New Ticket'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=emailtemplate&layout=emailtemplate&tf=sntk-tk"><span class="text"><?php echo JText::_('Staff Ticket'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=emailtemplate&layout=emailtemplate&tf=rs-tk"><span class="text"><?php echo JText::_('Reassign Ticket'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=emailtemplate&layout=emailtemplate&tf=cl-tk"><span class="text"><?php echo JText::_('Close Ticket'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=emailtemplate&layout=emailtemplate&tf=dl-tk"><span class="text"><?php echo JText::_('Delete Ticket'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=emailtemplate&layout=emailtemplate&tf=mo-tk"><span class="text"><?php echo JText::_('Mark Overdue'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=emailtemplate&layout=emailtemplate&tf=be-tk"><span class="text"><?php echo JText::_('Ban email'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=emailtemplate&layout=emailtemplate&tf=dt-tk"><span class="text"><?php echo JText::_('Department Transfer'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=emailtemplate&layout=emailtemplate&tf=ebct-tk"><span class="text"><?php echo JText::_('Ban Email and Close Ticket'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=emailtemplate&layout=emailtemplate&tf=ube-tk"><span class="text"><?php echo JText::_('Unban Email'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=emailtemplate&layout=emailtemplate&tf=rsp-tk"><span class="text"><?php echo JText::_('Response Ticket'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=emailtemplate&layout=emailtemplate&tf=rpy-tk"><span class="text"><?php echo JText::_('Reply Ticket'); ?></span></a>
            <a class="js-child" href="index.php?option=com_jssupportticket&c=emailtemplate&layout=emailtemplate&tf=be-trtk"><span class="text"><?php echo JText::_('Ban email try to create ticket'); ?></span></a>
        </div>
    </div>
    <div class="js-divlink">
        <a href="index.php?option=com_jssupportticket&c=systemerrors&layout=systemerrors">
            <img src="components/com_jssupportticket/include/images/c_p/left-icons/system-errors.png"/>
        </a>        
        <a href="#" class="js-parent"><span class="text"><?php echo JText::_('System Errors'); ?><img class="arrow" src="components/com_jssupportticket/include/images/c_p/arrow1.png"/></span></a>
        <div class="js-innerlink">
            <a class="js-child" href="index.php?option=com_jssupportticket&c=systemerrors&layout=systemerrors"><span class="text"><?php echo JText::_('System Errors'); ?></span></a>
        </div>
    </div>
    <div class="js-divlink">
        <a href="index.php?option=com_jssupportticket&c=reports&layout=overallreports">
            <img src="components/com_jssupportticket/include/images/c_p/left-icons/reports.png"/>
        </a>
        <a href="#" class="js-parent"><span class="text"><?php echo JText::_('Reports'); ?><img class="arrow" src="components/com_jssupportticket/include/images/c_p/arrow1.png"/></span></a>
        <div class="js-innerlink">
            <a class="js-child" href="index.php?option=com_jssupportticket&c=reports&layout=overallreports"><span class="text"><?php echo JText::_('Reports'); ?></span></a>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery("img#js-admin-responsive-menu-link").click(function(e){
            e.preventDefault();
            if(jQuery("div#js-tk-leftmenu").css('display') == 'none'){
                jQuery("div#js-tk-leftmenu").show();
                jQuery("div#js-tk-leftmenu").width(280);
                jQuery("div#js-tk-leftmenu").find('a.js-parent,a.js-parent2').show();
                /*
                jQuery('a.js-parent.lastshown').next().find('a.js-child').css('display','block');
                jQuery('a.js-parent.lastshown').find('img.arrow').attr("src","components/com_jssupportticket/include/images/c_p/arrow2.png");
                jQuery('a.js-parent.lastshown').find('span').css('color','#ffffff');
                */
            }else{
                jQuery("div#js-tk-leftmenu").hide();
            }
        });
        jQuery("div#js-tk-leftmenu").hover(function(){
            jQuery(this).width(280);
            jQuery(this).find('a.js-parent,a.js-parent2').show();
            /*
            jQuery('a.js-parent.lastshown').next().find('a.js-child').css('display','block');
            jQuery('a.js-parent.lastshown').find('img.arrow').attr("src","components/com_jssupportticket/include/images/c_p/arrow2.png");
            jQuery('a.js-parent.lastshown').find('span').css('color','#ffffff');
            */
        },function(){
            jQuery(this).width(65);
            jQuery(this).find('a.js-parent,a.js-parent2').hide();
            jQuery('a.js-parent.lastshown').next().find('a.js-child').css('display','none');
            jQuery('a.js-parent.lastshown').find('img.arrow').attr("src","components/com_jssupportticket/include/images/c_p/arrow1.png");
            jQuery('a.js-parent.lastshown').find('span').css('color','#acaeb2');
        });
        jQuery("a.js-child").find('span.text').click(function(e){
            jQuery(this).css('color','#ffffff');
        });
        jQuery("a.js-parent").click(function(e){
            e.preventDefault();
            jQuery('a.js-parent.lastshown').next().find('a.js-child').css('display','none');
            jQuery('a.js-parent.lastshown').find('span').css('color','#acaeb2');
            jQuery('a.js-parent.lastshown').find('img.arrow').attr("src","components/com_jssupportticket/include/images/c_p/arrow1.png");
            jQuery('a.js-parent.lastshown').removeClass('lastshown');
            jQuery(this).find('span').css('color','#ffffff');
            jQuery(this).addClass('lastshown');
            if(jQuery(this).next().find('a.js-child').css('display') == 'none'){
                jQuery(this).next().find('a.js-child').css({'display':'block'},800);
                jQuery(this).find('img.arrow').attr("src","components/com_jssupportticket/include/images/c_p/arrow2.png");
            }
        });
    });
</script>

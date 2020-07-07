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
?>

<div id="js-tk-admin-wrapper">
    <div id="js-tk-leftmenu">
        <?php include_once('components/com_jssupportticket/views/menu.php'); ?>
    </div>
    <div id="js-tk-cparea">
    <div class="aboutus">
        <div id="js-tk-heading"><h4><img id="js-admin-responsive-menu-link" src="components/com_jssupportticket/include/images/c_p/left-icons/menu.png" /><?php echo JText::_('About Us'); ?></h4></div>
        <span class="js-admin-component"><?php echo JText::_('Component Detail'); ?></span>
        <span class="js-admin-component-detail"><?php echo JText::_('Component For On-line Ticket Support System'); ?></span>
        <div class="js-admin-info-wrapper">
            <span class="js-admin-info-title"><?php echo JText::_('Created By'); ?></span>
            <span class="js-admin-info-vlaue">Ahmad Bilal</span>
        </div>
        <div class="js-admin-info-wrapper">
            <span class="js-admin-info-title"><?php echo JText::_('Company'); ?></span>
            <span class="js-admin-info-vlaue">Joom Sky</span>
        </div>
        <div class="js-admin-info-wrapper">
            <span class="js-admin-info-title"><?php echo JText::_('Plugin Name'); ?></span>
            <span class="js-admin-info-vlaue"><?php echo JText::_('Js Support Ticket'); ?></span>
        </div>
        <div class="js-admin-joomsky-wrapper">
            <span class="js-admin-title">
                <img src="components/com_jssupportticket/include/images/aboutus_page/logo.png" />
                Joom Sky
            </span>
            <div class="js-col-md-8">
                Our philosophy on project development is quite simple. We deliver exactly what you need to ensure the growth and effective running of your business. To do this we undertake a complete analysis of your business needs with you, then conduct thorough research and use our knowledge and expertise of software development programs to identify the products that are most beneficial to your business projects.
                <span class="js-joomsky-link">
                    <a href="http://www.joomsky.com" target="_blank"><?php echo JText::_('Goto Web'); ?></a>
                </span>
            </div>
            <div class="js-col-md-4">
                    <img src="components/com_jssupportticket/include/images/aboutus_page/product-images.png" />
                
            </div>
        </div>
        <span class="js-our-products"><?php echo JText::_('Our Products');?></span>
        <div class="js-col-md-4">
            <a href="http://www.joomsky.com/index.php/products/js-jobs-1/js-jobs-pro" target="_blank">
                <img src="components/com_jssupportticket/include/images/aboutus_page/jobs.jpg" />
            </a>
        </div>
        <div class="js-col-md-4">
            <a href="http://www.joomsky.com/index.php/products/js-autoz-1/js-autoz-pro" target="_blank">
                <img src="components/com_jssupportticket/include/images/aboutus_page/autoz.jpg" />
            </a>
        </div>
        <div class="js-col-md-4">
            <a href="http://www.joomsky.com/index.php/products/js-support-ticket-1/js-supprot-ticket-pro-wp" target="_blank">
                <img src="components/com_jssupportticket/include/images/aboutus_page/tickets.jpg" />
            </a>
        </div>        
    </div>
    </div>
</div>
<div id="js-tk-copyright">
    <img width="85" src="http://www.joomsky.com/logo/jssupportticket_logo_small.png">&nbsp;Powered by <a target="_blank" href="http://www.joomsky.com">Joom Sky</a><br/>
    &copy;Copyright 2008 - <?php echo date('Y'); ?>, <a target="_blank" href="http://www.burujsolutions.com">Buruj Solutions</a>
</div>

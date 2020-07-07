<?php
/**
 * @Copyright Copyright (C) 2015 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:     Buruj Solutions
  + Contact:    www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:  May 22, 2015
  ^
   + Project:    JS Tickets
  ^
 */

defined('_JEXEC') or die('Restricted access');

    $color1 = "#333333"; /* header bk color */
    $color2 = "#29A7E4";/* header link hover , header bottom bk, content heading bottom line, content box hover bk+ border color , */
    $color3 = "#FDFDFD"; /* content box background color */
    $color4 = "#565354";/* every text color in content */
    $color5 = "#DEDFE0";/* border color and div  border line  */
    $color6 = "#F0F0F0"; /* Every button and tab background color*/
    $color7 = "#FFFFFF"; /* header top and bottom text color,sort text color,headind seprater and text color */
    $color8 = "#3D3D3D";/* subject and name color for ticket listing */
    $color9 = "";
    $color10 = "";

    $cssString = "
            div#jl_pagination{border:1px solid $color5;background:$color3;}
    form.js-tk-combinesearch{border: 1px solid $color5;background:$color3;}
    /******* Heading ******/
    div#js-tk-heading{border-bottom:3px solid $color2;}
    div#js-tk-heading.js-small{border-bottom:1px solid $color2;color:$color2;}
    /****** Controlpanel */
    div#js-maincp-area{background-color: $color3;border: 1px solid $color5;}
    div#js-maincp-area a.js-mnu-area{border: 2px solid $color5;background-color: $color7;}
    div#js-maincp-area a.js-mnu-area:hover {border: 2px solid $color2;}
    div#js-maincp-area a.js-mnu-area:hover div.js-mnu-arrowicon img{background:$color2;}
    div#js-maincp-area div.js-mnu-arrowicon img{background:$color5;}
    div#js-maincp-area div.js-mnu-text span{color: $color8;}
    /****** Form *******/
    div#js-tk-form-wraper{background:$color3;border:1px solid $color5;}
    div.js-tk-submit{border-top:2px solid $color5;}
    div.js-tk-submit input{background:$color6;color:$color4;border:1px solid $color5;}
    div.js-tk-submit input:hover{background:$color2;color:$color7;}
    div#tk_search_data button.tk_dft_btn{background:$color6;color:$color4;border:1px solid $color5;}
    div#tk_search_data button.tk_dft_btn:hover{background:$color2;color:$color7;}
    /***** File Attachment*/
    div#js-attachment-files {border: 1px solid $color5;}
    div#js-attachment-files span.js-value-text,span.tk_attachment_value_text{border: 1px solid $color5;}
    div#js-attachment-option span#js-attachment-add,span#tk_attachment_add{color: $color4;background-color: $color6;}
    div#js-attachment-option span#js-attachment-add:hover,span#tk_attachment_add:hover{color: $color7; background-color: $color2;}
    div#tk_mt_tabs a{color: $color4;border: 1px solid $color2;}
    div#tk_mt_tabs a:hover,
    div#tk_mt_tabs a.selected{color: $color7;background:$color2;}
    div#tk_mt_tabs_bottom_line{background-color: $color2;}
    div#tk_mt_tabs_bottom_line_full{background-color: $color2; }
    div#tk_mt_sort_wraper{background-color: $color1;border-top: 1px solid $color2;border-bottom: 1px solid $color5}
    div#tk_mt_sort_wraper ul#tk_mt_sorts_menu li.tk_mt_sorts_menu_link a {color: #FFFFFF;}
    div#tk_mt_sort_wraper ul#tk_mt_sorts_menu li.tk_mt_sorts_menu_link a:hover,div#tk_mt_sort_wraper ul#tk_mt_sorts_menu li.tk_mt_sorts_menu_link a.selected{background-color: $color2;color: $color7;}
    div.tk_mt_detail_main {border: 1px solid $color5;background:$color3;}
    div.tk_mt_detail_desc {border-left: 1px solid $color5}
    div.tk_mt_detail_desc span.tk_mt_detail_value_main {color:$color4;}
    div.tk_mt_detail_desc  span.tk_mt_detail_text {color:$color4;}
    div.tk_mt_detail_desc  span.tk_mt_detail_text_clr{background-color:$color3;color:$color2;}
    span.tk_mt_detail_status_new{background-color: $color2;}
    div.tk_mt_detail_info{border-left: 1px solid $color5;}
    div.tk_mt_detail_info div#tk_mt_detail_info_key_value_wraper  ,
    div.tk_mt_detail_info:hover div#tk_mt_detail_info_key_value_wraper {color:$color4;}
    div.tk_mt_detail_info div#tk_mt_detail_info_key_value_wraper span#tk_mt_priority.priority,
    div.tk_mt_detail_info:hover div#tk_mt_detail_info_key_value_wraper span#tk_mt_priority.priority {color: $color7;}
    div.tk_mt_detail_desc div.tk_mt_detail_desc_top span.tk_mt_detail_value_main a.tk_mt_detail_link{color:$color2;}
    /* ticket details */
    div#tk_detail_wraper div#tk_heading{background:$color8;color:$color7;}
    div#tk_detail_content_wraper{border: 1px solid $color5;background:$color3;}
    div#tk_detail_content_wraper div#tk-before-internalnote-wrapper {border: 1px solid $color5;}
    div#tk_detail_content_wraper div.js-tk-wrapper div.js-tk-value{border: 1px solid $color5;}
    div#tk_detail_content_wraper div#tk_detail_post{border-left: 1px solid $color5;}
    div#tk_detail_content_wraper div#tk_detail_post span#tk_detail_post_text{color:$color4;}
    div#tk_detail_content_wraper div#tk_detail_id{border-left: 1px solid $color5;}
    div#tk_detail_content_wraper div#tk_detail_id span{color:$color4;}
    div#tk_detail_content_wraper div#tk_detail_id span#tk_detail_id_value{border:1px solid $color5;}
    div#tk_detail_content_wraper div#tk_detail_reply span#tk_detail_id_value{border: 1px solid $color5;}
    div#tk_detail_content_wraper div#tk_detail_id span.tk_detail_id_perority{color: $color7;}
    div#tk_detail_content_wraper div#tk_detail_reply{border-left: 1px solid $color5;}
    div#tk_detail_content_wraper div#tk_detail_reply span{color:$color4;}
    div#tk_detail_info{color:$color4;border-top:1px solid $color5;}
    div#tk_detail_info_oc_btn,div#tk_detail_info_lu_btn{background: $color6;border: 1px solid $color5;}
    /* requestor info */
    div#tk_request_info_wrapper div#tk_request_detail div#tk_request_detail_man span#tk_request_detail_name_text,div#tk_request_info_wrapper div#tk_request_detail div#tk_request_detail_email span#tk_request_detail_email_text{color:$color4;}
    /* common key value css for tickets */
    div.tk_key_value_wraper div.tk_value_wraper span.tk_properties_value{border: 1px solid $color5;background:#FFFFFF;}
    div#tk_detail_reply_wraper{color:$color4;}
    div#tk_detail_reply_wraper div.tk_detail_reply div.tk_detail_reply_description{border: 1px solid $color5;background: #FFFFFF;}
    div#tk_detail_reply_wraper div.tk_detail_reply div.tk_detail_reply_description div.tk_detail_reply_description_left{border-bottom:1px solid $color2;}
    div#tk_detail_reply_wraper div.tk_detail_reply div.tk_detail_reply_description div.tk_detail_reply_description_left span.tk_detail_reply_description_subject{color:$color2;}
    div#tk_detail_reply_wraper div.tk_detail_reply div.tk_detail_reply_description:after {border-right-color:$color5;}
    div#tk_detail_reply_wraper div.tk_detail_reply div.tk_detail_reply_description:before {border-right-color:$color5;}
    div.tk_attachment_value_wrapper{border: 1px solid $color5;}
    span.tk_attachment_value_text{border: 1px solid $color5;}
    span.tk_attachments_add{border: 1px solid $color5; color: $color4;background-color:  $color6;}
    span.tk_attachments_addform{border: 1px solid $color5;color: $color4;background-color: $color6;}
    @media (max-width:480px){
        div#tk_detail_content_wraper div#tk_detail_post{border-left:0px;}
        div#tk_detail_content_wraper div#tk_detail_id{border-left:0px;}
        div#tk_detail_content_wraper div#tk_detail_reply{border-left:0px;}
    }
    ";

    $document = JFactory::getDocument();
    $document->addStyleDeclaration($cssString);


?>

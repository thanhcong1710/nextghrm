<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
  + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
  + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
  ^
  + Project: 		JS Jobs
 * File Name:	admin-----/views/applications/tmpl/users.php
  ^
 * Description: Template for users view
  ^
 * History:		NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');

$version = new JVersion;
$joomla = $version->getShortVersion();
if (substr($joomla, 0, 3) != '1.5') {
    JHtml::_('behavior.tooltip');
    JHtml::_('behavior.multiselect');
}
?>
<div id="js-tk-admin-wrapper">
    <div id="js-tk-leftmenu">
        <?php include_once('components/com_jssupportticket/views/menu.php'); ?>
    </div>
    <div id="js-tk-cparea">
        <div id="js-tk-heading">
            <h4><img id="js-admin-responsive-menu-link" src="components/com_jssupportticket/include/images/c_p/left-icons/menu.png" /><?php echo JText::_('Field Ordering'); ?></h4>
        </div>
        <form action="index.php" method="post" name="adminForm" id="adminForm">
            <?php
            if (!(empty($this->fields)) && is_array($this->fields)) {  ?>
                <div class="js-col-md-12">
                    <table id="js-table">
                        <thead>
                        <tr>
                            <th style="display:none;" class="center"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
                            <th class="center"><?php echo JText::_('S.No'); ?></th>
                            <th><?php echo JText::_('Field Title'); ?></th>
                            <th class="center"><?php echo JText::_('Published'); ?></th>
                            <th class="center"><?php echo JText::_('Required'); ?></th>
                            <th class="center"><?php echo JText::_('Ordering'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $k = 0;
                        $i = 0;
                        $uptask = 'fieldorderingup';
                        $upimg = 'uparrow.png';
                        $downtask = 'fieldorderingdown';
                        $downimg = 'downarrow.png';
                        $n = count($this->fields);
                        foreach ($this->fields AS $row) {
                            $checked = JHTML::_('grid.id', $k, $row->id);
                            $pubtask = $row->published ? 'fieldunpublished' : 'fieldpublished';
                            $reqtask = $row->required ? 'fieldnotrequired' : 'fieldrequired';
                            $pubimg = ($row->published == 0) ? 'no.png' : 'yes.png';
                            $reqimg = ($row->required == 0) ? 'no.png' : 'yes.png';
                            $alt = $row->published ? JText::_('Published') : JText::_('Unpublished');
                            $reqalt = $row->required ? JText::_('Required') : JText::_('Not required'); ?>
                            <tr>
                                <td style="display:none;" class="center"><?php echo $checked; ?></td>
                                <td class="center"><?php echo $k + 1 + $this->pagination->limitstart; ?></td>
                                <td>
                                    <?php 
                                        if ($row->fieldtitle) 
                                            echo getFieldTitle($row->field,$row->fieldtitle);
                                        else echo $row->userfieldtitle;
                                    ?>
                                </td>
                                <td class="center">
                                    <?php 
                                        if ($row->cannotunpublish == 1) { ?>
                                            <img src="components/com_jssupportticket/include/images/<?php echo $pubimg; ?>" width="16" height="16" border="0" title="<?php echo JText::_('Can Not Unpublished'); ?>" />
                                        <?php } else { ?>
                                            <a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $k; ?>', '<?php echo $pubtask; ?>')">
                                                <img src="components/com_jssupportticket/include/images/<?php echo $pubimg; ?>" width="16" height="16" border="0" title="<?php echo $alt; ?>" />
                                            </a> 
                                    <?php } ?>
                                </td>
                                <td class="center">
                                    <?php 
                                        if($row->cannotunpublish == 1){ ?>
                                            <img src="components/com_jssupportticket/include/images/<?php echo $reqimg; ?>" width="16" height="16" border="0" title="<?php echo JText::_('Can Not mark as not required'); ?>" />
                                    <?php }else{ ?>
                                        <a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $k; ?>', '<?php echo $reqtask; ?>')">
                                            <img src="components/com_jssupportticket/include/images/<?php echo $reqimg; ?>" width="16" height="16" border="0" title="<?php echo $reqalt; ?>" />
                                        </a> 
                                    <?php } ?>
                                </td>
                                <td class="center">
                                    <?php if ($k != 0) { ?>
                                        <a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $k; ?>', '<?php echo $downtask; ?>')">
                                            <img src="components/com_jssupportticket/include/images/<?php echo $upimg; ?>" width="16" height="16" border="0" title="Order Up" />
                                        </a> 
                                    <?php } else echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                        echo $row->ordering; ?>&nbsp;&nbsp; 
                                    <?php if ($k < $n - 1) { ?> 
                                        <a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $k; ?>', '<?php echo $uptask; ?>')">
                                            <img src="components/com_jssupportticket/include/images/<?php echo $downimg; ?>" width="16" height="16" border="0" alt="Order Down" />
                                        </a> 
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php
                            $k++;
                        } ?>
                        </tbody>
                    </table>
                </div>
                <div class="js-row js-tk-pagination">
                    <?php echo $this->pagination->getListFooter(); ?>
                </div>
            <?php 
            }else{
                messagesLayout::getRecordNotFound();
            } ?>
            <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
            <input type="hidden" name="task" value="view" />
            <input type="hidden" name="c" value="userfields" />
            <input type="hidden" name="boxchecked" value="0" />
            <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
            <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
            <input type="hidden" name="layout" value="fieldsordering" />
            <?php echo JHTML::_('form.token'); ?>
        </form>
    <div class="js-config-pro-version-text"><?php echo JText::_('* Pro version only'); ?></div>
    </div>
</div>
<div id="js-tk-copyright">
    <img width="85" src="http://www.joomsky.com/logo/jssupportticket_logo_small.png">&nbsp;Powered by <a target="_blank" href="http://www.joomsky.com">Joom Sky</a><br/>
    &copy;Copyright 2008 - <?php echo date('Y'); ?>, <a target="_blank" href="http://www.burujsolutions.com">Buruj Solutions</a>
</div>
<?php 
    function getFieldTitle($field,$fieldtitle){
        switch ($field) {
            case 'helptopic':
            case 'premade':
            case 'internalnotetitle':
            case 'assignto':
            case 'duedate':
                $fieldtitle .= ' <span class="js-config-pro">*</span>';
            break;
        }
        return $fieldtitle;
    }
?>
<script type="text/javascript">
    var headertext = [],
    headers = document.querySelectorAll("#js-table th"),
    tablerows = document.querySelectorAll("#js-table th"),
    tablebody = document.querySelector("#js-table tbody");

    for(var i = 0; i < headers.length; i++) {
      var current = headers[i];
      headertext.push(current.textContent.replace(/\r?\n|\r/,""));
    } 
    for (var i = 0, row; row = tablebody.rows[i]; i++) {
      for (var j = 0, col; col = row.cells[j]; j++) {
        col.setAttribute("data-th", headertext[j]);
      } 
    }
</script>
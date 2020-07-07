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
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'administrator/components/com_jssupportticket/include/css/jssupportticketadmin.css');
global $mainframe;
?>

<div id="js-tk-admin-wrapper">
    <div id="js-tk-leftmenu">
        <?php include_once('components/com_jssupportticket/views/menu.php'); ?>
    </div>
    <div id="js-tk-cparea">
        <div id="js-tk-heading">
            <h4><img id="js-admin-responsive-menu-link" src="components/com_jssupportticket/include/images/c_p/left-icons/menu.png" /><?php echo JText::_('System Errors'); ?></h4>      
        </div>
            <?php
            if (!(empty($this->systemerrors)) && is_array($this->systemerrors)) {  ?>
                <div class="js-col-md-12">
                    <table id="js-table">
                        <thead>
                        <tr>
                            <th class="center"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
                            <th><?php echo JText::_("Name"); ?></th>
                            <th><?php echo JText::_("Error"); ?></th>
                            <th><?php echo JText::_("View"); ?></th>
                            <th><?php echo JText::_("Created"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            foreach ($this->systemerrors AS $error) {
                                $checked = JHTML::_('grid.id', $i, $row->id);
                                $editlink = 'index.php?option=' . $this->option . '&c=systemerrors&task=showerror&cid=' . $error->id;
                                if($row->status == 1) $icon_status = 'yes.png'; else $icon_status = 'no.png'; ?>
                                <tr>
                                    <td class="center"><?php echo $checked; ?></td>
                                    <td><a href="<?php echo $editlink;?>"><?php if ($error->staffname != '') echo $error->staffname; else echo JText::_('User'); ?></a></td>
                                    <td><?php $err = substr($error->error, 0, 50) . '...'; if ($error->isview == 0) echo '<b>'; ?> <a href="<?php echo $link; ?>"><?php echo $err; ?></a><?php if ($error->isview == 0) echo '</b>'; ?></td>
                                    <td><?php if ($error->isview == 1) echo JText::_('Yes'); else echo JText::_('No'); ?></td>
                                    <td><?php JText::_('Created');echo " : "; ?></span><?php echo $error->created; ?></td>
                                </tr>
                                <?php
                                $i++;
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
            
            <input type="hidden" name="c" value="systemerrors" />
            <input type="hidden" name="layout" value="systemerrors" />
            <input type="hidden" name="boxchecked" value="0" />
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
        </form>
    </div>
</div>
<div id="js-tk-copyright">
    <img width="85" src="http://www.joomsky.com/logo/jssupportticket_logo_small.png">&nbsp;Powered by <a target="_blank" href="http://www.joomsky.com">Joom Sky</a><br/>
    &copy;Copyright 2008 - <?php echo date('Y'); ?>, <a target="_blank" href="http://www.burujsolutions.com">Buruj Solutions</a>
</div>
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
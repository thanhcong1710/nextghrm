<?php
/**
 * @version		$Id: edit_menu.php 198 2014-02-26 18:49:00Z michel $
 * @copyright	Copyright (C) 2014, Michael Liebler. All rights reserved.
 * @license #http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<fieldset class="panelform">
	<legend>
		<?php echo JText::_('Menu_Filter'); ?>
	</legend>
	<?php foreach ( $this->form->getFieldset( 'menus') as $field ): ?>
	<div class="control-group">
		<?php echo $field->label; ?>
		<div class="controls">
			<?php echo $field->input; ?>
		</div>
	</div>
	<?php endforeach; ?>
</fieldset>

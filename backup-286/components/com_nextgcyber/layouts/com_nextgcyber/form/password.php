<?php
/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://domain.com
 * @author Daniel.Vu
 */
defined('_JEXEC') or die('Restricted access');
$return = (!empty($displayData->return)) ? $displayData->return : base64_encode(JRoute::_('index.php'));
?>
<div>
    <form class="form-vertical" method="POST">
        <div class="alert alert-info">
            <?php echo $displayData->subtitle; ?>
        </div>
        <div class="form-group">
            <label for="InputPassword"><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_FORM_PASSWORD_LABEL'); ?></label>
            <input type="password" class="form-control nc-input" id="InputPassword" placeholder="<?php echo JText::_('COM_NEXTGCYBER_INSTANCE_FORM_PASSWORD_LABEL'); ?>" name="pwd">
        </div>
        <div class="form-group">
            <div>
                <button type="button" class="btn btn-primary nc-button" data-id="<?php echo $displayData->id; ?>" data-action="verifyPassword">
                    <?php echo JText::_('COM_NEXTGCYBER_INSTANCE_FORM_VERIFY_REQUEST_BUTTON'); ?>
                </button>
            </div>
        </div>
        <input type="hidden" value="com_nextgcyber" name="option" />
        <input type="hidden" value="<?php echo $displayData->id; ?>" name="id" />
        <input type="hidden" value="json" name="format" />
        <input type="hidden" value="instance.verifyPassword" name="task" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
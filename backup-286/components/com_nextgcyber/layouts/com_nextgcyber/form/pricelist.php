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
            <label><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_FORM_PAYMENT_PERIOD_LABEL'); ?></label>
            <select class="form-control" id="nc_input_pricelist_id">
                <?php
                foreach ($displayData->options as $option) {
                    echo '<option value="' . $option->id . '">' . $option->name . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label><?php echo JText::_('COM_NEXTGCYBER_PRICING_HAVE_PROMOTION_CODE'); ?></label>
            <input type="text" name="code" class="form-control" id="promotional_code_input" placeholder="" value="">
        </div>
        <div class="form-group">
            <div>
                <button type="button" class="btn btn-primary nc-button" data-id="<?php echo $displayData->id; ?>" data-action="upgrade" data-token="<?php echo $displayData->token; ?>">
                    <?php echo JText::_('COM_NEXTGCYBER_INSTANCE_FORM_SUBMIT_BUTTON'); ?>
                </button>
            </div>
        </div>
        <input type="hidden" value="com_nextgcyber" name="option" />
        <input type="hidden" value="<?php echo $displayData->id; ?>" name="id" />
        <input type="hidden" value="json" name="format" />
        <input type="hidden" value="instance.upgrade" name="task" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
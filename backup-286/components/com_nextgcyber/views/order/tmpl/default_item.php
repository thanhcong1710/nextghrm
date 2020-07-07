<?php
/**
 * @package pkg_nextgcyber NextgCyber for Joomla
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2015 NextgCyber . All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://nextgerp.com
 * @author NextG-ERP support@nextgerp.com
 */
defined('_JEXEC') or die;
JLoader::register('NextgCyberCurrencyHelper', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/currencyhelper.php');
$currency = NextgCyberCurrencyHelper::getCurrency();
$user_tz = NextgCyberCustomerHelper::getUserTimezone();
?>
<?php if (empty($this->item)): ?>
    <div class="alert alert-danger"><?php echo JText::_('COM_NEXTGCYBER_ERROR_ORDER_NOT_FOUND'); ?></div>
<?php else: ?>
    <?php echo JLayoutHelper::render('com_nextgcyber.orders.item', $this->item, JPATH_COMPONENT, array('active' => true)); ?>
<?php endif; ?>
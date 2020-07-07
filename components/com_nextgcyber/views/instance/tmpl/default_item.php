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
NextgCyberSiteHelper::loadLibrary();
$user_tz = NextgCyberCustomerHelper::getUserTimezone();
$session = JFactory::getSession();
$msg = $session->get('trial.message', '');
$session->set('trial.message', null);
?>
<?php if (empty($this->item)): ?>
    <div class="alert alert-danger"><?php echo JText::_('COM_NEXTGCYBER_ERROR_INSTANCE_NOT_FOUND'); ?></div>
<?php else: ?>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1 class="panel-title"><?php echo JText::sprintf('COM_NEXTGCYBER_INSTANCE_NUMBER_PREFIX', $this->item->name); ?></h1>
                </div>
            </div>
            <?php
            if ($msg) {
                echo '<div class="alert alert-success">' . $msg . '</div>';
            }
            ?>
    <?php echo JLayoutHelper::render('com_nextgcyber.instance.item', $this->item, JPATH_COMPONENT, array('view_more' => false)); ?>
        </div>
    </div>

<?php endif; ?>
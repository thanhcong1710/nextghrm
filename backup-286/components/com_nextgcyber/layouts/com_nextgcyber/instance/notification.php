<?php
/**
 * @package pkg_nextgcyber
 * @subpackage  com_nextgcyber
 *
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://domain.com
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;
JLoader::register('NextgCyberNumberHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/numberhelper.php');
JLoader::register('NextgCyberSiteHelper', JPATH_COMPONENT . '/helpers/sitehelper.php');
if (empty($displayData->used_user)) {
    $displayData->used_user = 0;
}
if (empty($displayData->used_bandwidth)) {
    $displayData->used_bandwidth = 0;
}
if (empty($displayData->used_storage)) {
    $displayData->used_storage = 0;
}
if (empty($displayData->used_backup)) {
    $displayData->used_backup = 0;
}
?>
<a href="#" class="list-group-item">
    <i class="fa fa-user fa-fw"></i><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_MAX_USER'); ?>: <?php echo $displayData->used_user; ?>/<?php echo $displayData->max_user; ?>
    <?php
    $percentage = ($displayData->max_user) ? number_format(($displayData->used_user / $displayData->max_user) * 100) : 0;
    ?>
    <div class="progress">
        <div class="progress-bar progress-bar-striped<?php echo NextgCyberSiteHelper::getProgressClass($percentage); ?>" role="progressbar" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percentage; ?>%;">
            <?php echo $percentage; ?>%
        </div>
    </div>
</a>
<a href="#" class="list-group-item" title="<?php echo NextgCyberNumberHelper::formatBytes($displayData->used_bandwidth); ?>">
    <?php
    $used_bandwidth = NextgCyberNumberHelper::toGb($displayData->used_bandwidth);
    $percentage = ($displayData->max_bandwidth) ? number_format(($used_bandwidth / $displayData->max_bandwidth) * 100) : 0;
    ?>
    <i class="fa fa-rocket fa-fw"></i><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_MAX_BANDWIDTH'); ?>: <?php echo $used_bandwidth; ?>/<?php echo $displayData->max_bandwidth; ?> GB
    <div class="progress">
        <div class="progress-bar progress-bar-striped<?php echo NextgCyberSiteHelper::getProgressClass($percentage); ?>" role="progressbar" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percentage; ?>%;">
            <?php echo $percentage; ?>%
        </div>
    </div>
</a>
<a href="#" class="list-group-item" title="<?php echo NextgCyberNumberHelper::formatBytes($displayData->used_storage); ?>">
    <?php
    $used_storage = NextgCyberNumberHelper::toGb($displayData->used_storage);
    $percentage = ($displayData->max_storage) ? number_format(($used_storage / $displayData->max_storage) * 100) : 0;
    ?>
    <i class="fa fa-server fa-fw"></i><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_MAX_SPACESIZE'); ?>: <?php echo $used_storage; ?>/<?php echo $displayData->max_storage; ?> GB
    <div class="progress">
        <div class="progress-bar progress-bar-striped<?php echo NextgCyberSiteHelper::getProgressClass($percentage); ?>" role="progressbar" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percentage; ?>%;">
            <?php echo $percentage; ?>%
        </div>
    </div>
</a>
<a href="#" class="list-group-item">
    <i class="fa fa-database fa-fw"></i><?php echo JText::_('COM_NEXTGCYBER_INSTANCE_MAX_BACKUP'); ?>: <?php echo $displayData->used_backup; ?>/<?php echo $displayData->max_backup; ?>
    <?php
    $percentage = ($displayData->max_backup) ? number_format(($displayData->used_backup / $displayData->max_backup) * 100) : 0;
    ?>
    <div class="progress">
        <div class="progress-bar progress-bar-striped<?php echo NextgCyberSiteHelper::getProgressClass($percentage); ?>" role="progressbar" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percentage; ?>%;">
            <?php echo $percentage; ?>%
        </div>
    </div>
</a>
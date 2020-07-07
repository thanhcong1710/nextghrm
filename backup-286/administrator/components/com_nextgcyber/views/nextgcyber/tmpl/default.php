<?php
/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://nextgcyber.com
 * @author Daniel.Vu
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
JLoader::register('NextgCyberDashboardHelper', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/dashboardhelper.php');
JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
$app = JFactory::getApplication();
$jform = $app->input->get('jform', array(), 'array');
$dashboardModel = NextgCyberHelper::getAdminModel('Dashboard');
?>
<?php if (!empty($this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
    <?php else : ?>
        <div id="j-main-container">
        <?php endif; ?>
        <div class="dashboard">

        </div>
    </div>
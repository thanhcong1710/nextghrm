<?php
/**
 * @package		EasyDiscuss
 * @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *
 * EasyDiscuss is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php if( $system->config->get( 'main_ranking' ) ){ ?>

<!-- User graph -->
<div class="discuss-user-graph">
	<div class="rank-bar mini" data-original-title="<?php echo $this->escape( DiscussHelper::getUserRanks( $userId ) ); ?>" rel="ed-tooltip">
		<div class="rank-progress" style="width: <?php echo DiscussHelper::getUserRankScore( $userId ); ?>%"></div>
	</div>
</div>
<?php } ?>
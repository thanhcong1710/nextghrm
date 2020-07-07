<?php
/**
 * @package        JFBConnect/JLinked
 * @copyright (C) 2011-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

if ($jfbcLogin != '' || $jlinkedLogin != '')
{
        ?>
        <div class="kiwi-login-module-social-login <?php echo $socialSpan . ' ' . $layout . ' ' . $orientation; ?>">
                <?php
                echo $jfbcLogin;

                if ($layout == 'vertical' && ($loginButtonType != 'icon_button' || $orientation == 'side') && $params->get('showLoginForm'))
                        echo '<div style="clear:both"></div>';
                echo $jlinkedLogin;
                ?>
        </div>
        <?php
}
<?php
/**
 * @package        Kiwi Slider
 * @copyright (C) 2015 by nextgerp.com - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
JLoader::register('NextgCyberHelperRoute', JPATH_SITE . '/components/com_nextgcyber/helpers/route.php');
$document = JFactory::getDocument();
$document->addScript(JUri::root(true) . '/modules/mod_kiwicreateinstance/assets/js/mod_kiwicreateinstance.js');
$document->addStyleSheet(JUri::root(true) . '/modules/mod_kiwicreateinstance/assets/css/mod_kiwicreateinstance.css');
$templateHtml = $params->get('template', '');
$placeholder = JText::_($params->get('placeholder', 'your-company-name'));
$basedomain_name = $params->get('domain', 'nextgerp.com');
$domain = $basedomain_name;
$action = JRoute::_(NextgCyberHelperRoute::getTryingRoute());
$loginUrl = JRoute::_('index.php?option=com_users&view=login');
$current_url = JRoute::_(JUri::getInstance()->toString());
$pricing_url = JRoute::_(NextgCyberHelperRoute::getPricingRoute());
$show_price = false;
$user = JFactory::getUser();
if ($user->guest) {
    $leftSpan = ($show_price) ? 'col-xs-8 col-sm-4 col-sm-offset-2' : 'col-xs-12 col-sm-4 col-sm-offset-4';
    $buttonLabel = JText::_('MOD_KIWICREATEINSTANCE_LOGIN_BUTTON');
    $button = '<a href="' . $action . '" class="btn btn-success2 btn-block"><strong>' . $buttonLabel . '</strong></a>';
    $form = '<div>
        <div class="form-group' . $leftSpan . '">
                ' . $button . '
        </div>';

    if ($show_price) {
        $form .= '
        <div class="form-group col-xs-4 col-sm-4">
                <a href="' . $pricing_url . '" class="btn btn-success btn-block"><strong>' . JText::_('MOD_KIWICREATEINSTANCE_PRICING_BUTTON') . '</strong></a>
        </div>';
    }
    $form .= '
</div>';
} else {
    $leftSpan = ($show_price) ? 'col-xs-8 col-sm-6' : 'col-xs-8 col-sm-6';
    $rightSpan = ($show_price) ? 'col-xs-4 col-sm-3' : 'col-xs-4 col-sm-6';
    $buttonLabel = JText::_($params->get('button', 'Try now!'));
    $button = '<button class="btn btn-success create_instance btn-block" type="submit"><strong>' . $buttonLabel . '</strong></button>';
    $form = '<div><form role="form" class="form-inline" action="' . $action . '">
        <div class="form-group ' . $leftSpan . '">
                        <div class="input-group col-md-12">
                                <input name="odoo_name" type="text" placeholder="' . $placeholder . '" class="form-control">
                                <span class="input-group-addon">' . $domain . '</span>
                        </div>
        </div>
        <div class="form-group ' . $rightSpan . '">
                ' . $button . '
        </div>';

    if ($show_price) {
        $form .= '
        <div class="form-group col-sm-3 hidden-xs">
                <a href="' . $pricing_url . '" class="btn btn-success2 col-xs-3 btn-block"><strong>' . JText::_('MOD_KIWICREATEINSTANCE_PRICING_BUTTON') . '</strong></a>
        </div>';
    }

    $form .= JHtml::_('form.token') . '
</form></div>';
}
?>
<div class="mod-create-instance">
    <?php
    $templateHtml = str_replace('{form}', $form, $templateHtml);
    echo $templateHtml;
    ModKiwiCreateInstanceHelper::addFacebookImage($templateHtml, $params);
    ?>
</div>

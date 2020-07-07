<?php
/**
 * @package pkg_nextgcyber
 * @subpackage  com_nextgcyber
 * @copyright Copyright (C) 2015 NextgCyber . All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://nextgerp.com
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;
JLoader::register('NextgCyberCurrencyHelper', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/currencyhelper.php');
NextgCyberSiteHelper::loadLibrary();
$currency = NextgCyberCurrencyHelper::getCurrency();
$user_tz = NextgCyberCustomerHelper::getUserTimezone();
$instanceModel = JModelLegacy::getInstance('Instance', 'NextgCyberModel');
$i = 0;
if (!empty($displayData)):
    ?>
    <div class="panel-group" id="accordion-instance-list" role="tablist" aria-multiselectable="true">
        <?php foreach ($displayData as $instance): ?>
            <?php $active = ($i == 0) ? ' in ' : ''; ?>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading<?php echo $instance->id; ?>">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion-instance-list" href="#instance-collapse<?php echo $instance->id; ?>" aria-expanded="false" aria-controls="instance-collapse<?php echo $instance->id; ?>">
                            <?php echo $instance->fullsubdomain_name; ?>
                        </a>
                    </h4>
                </div>
                <div id="instance-collapse<?php echo $instance->id; ?>" class="panel-collapse collapse<?php echo $active; ?>" role="tabpanel" aria-labelledby="heading<?php echo $instance->id; ?>">
                    <?php echo JLayoutHelper::render('com_nextgcyber.instance.item', $instance, JPATH_COMPONENT, null); ?>
                </div>
            </div>
            <?php
            $i++;
        endforeach;
        ?>
    </div>
<?php endif; ?>
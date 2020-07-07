<?php
/**
 * @package mod_kiwitemplate
 * @subpackage  mod_kiwitemplate
 *
 * @copyright Copyright (C) 2015 NextG-ERP. All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://erponline.co.nz
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('jquery.framework');
JHTML::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen', 'select');
$base_url = JUri::root(true);
$datas = array(
    0 => array(
        'template_name' => 'Web Builder',
        'demo_template_id' => 21,
        'template_id' => 4,
        'template_img' => $base_url . '/images/web-design.png',
        'modal_title' => 'Web Builder - Create free trial'
    ),
    1 => array(
        'template_name' => 'E-Commerce',
        'demo_template_id' => 22,
        'template_id' => 5,
        'template_img' => $base_url . '/images/e-commerce.png',
        'modal_title' => 'E-Commerce - Create free trial'
    ),
    2 => array(
        'template_name' => 'Service Company',
        'demo_template_id' => 23,
        'template_id' => 6,
        'template_img' => $base_url . '/images/service.png',
        'modal_title' => 'Service Company - Create free trial'
    ),
    3 => array(
        'template_name' => 'Manufacture Company',
        'demo_template_id' => 24,
        'template_id' => 7,
        'template_img' => $base_url . '/images/manufaturing.png',
        'modal_title' => 'Manufacture Company - Create free trial'
    ),
    4 => array(
        'template_name' => 'Point of Sale (POS)',
        'demo_template_id' => 25,
        'template_id' => 8,
        'template_img' => $base_url . '/images/pos.png',
        'modal_title' => 'Point of Sale - Create free trial'
    )
);

$col = 3;
$totalSpan = 0;
$span = 12 / $col;
$pricing_url = 'https://nextgerp.com/en/contact-us';
$return = base64_encode(JURI::getInstance()->toString());
$description = $params->get('description', '');
$liveDemo = JHTML::tooltip('admin / admin', 'Username / Password', '', 'Live Demo');
echo $description;
?>
<div class="row">
    <?php
    foreach ($datas as $key => $data):
        $offset = ($key == 0) ? ' col-md-offset-1' : '';
        ?>
        <div class="col-md-2<?php echo $offset; ?>">
            <div class="template-box">
                <div class="row">
                    <div class="template-img col-md-12 col-xs-6">
                        <img alt="<?php echo $data['template_name']; ?>" class="img img-responsive" src="<?php echo $data['template_img']; ?>"/>
                    </div>
                    <div class="col-md-12 col-xs-6">
                        <h3 class="template-name"><?php echo $data['template_name']; ?></h3>
                        <div class="template-buttons">
                            <?php
                            $attr = (empty($data['demo_template_id'])) ? ' onclick="return false;"' : '';
                            if (!empty($data['demo_template_id'])) {
                                $secretToken = '';
                                $demo_url = $pricing_url;
                                $demo_button_class = 'btn btn-success btn-block modal-button';
                            } else {
                                $demo_url = $pricing_url;
                                $demo_button_class = 'btn btn-success btn-block disabled';
                            }
                            ?>
                            <a<?php echo $attr; ?> href="<?php echo $demo_url; ?>"
                                                   class="<?php echo $demo_button_class; ?>"
                                                   modal-title="<?php echo $data['modal_title']; ?>">
                                                       <?php echo $liveDemo; ?>
                            </a>

                            <?php
                            if (!empty($data['template_id'])) {
                                $secretToken = '';
                                $trial_url = $pricing_url;
                                $trial_button_class = 'btn btn-success2 btn-block template-start-trial modal-button';
                            } else {
                                $trial_url = $pricing_url;
                                $trial_button_class = 'btn btn-success2 btn-block disabled';
                            }
                            ?>
                            <a href="<?php echo $trial_url; ?>"
                               class="<?php echo $trial_button_class; ?>"
                               modal-title="<?php echo $data['modal_title']; ?>">Start Trial</a>
                            <a href="<?php echo $pricing_url; ?>" class="btn btn-default btn-block"><span class="fa fa-shopping-cart"></span>&nbsp;Buy now</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    <?php endforeach; ?>
</div>

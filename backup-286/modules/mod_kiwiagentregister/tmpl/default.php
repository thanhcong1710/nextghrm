<?php
/**
 * @package mod_kiwiagentregister
 * @subpackage  mod_kiwiagentregister
 *
 * @copyright Copyright (C) 2015 NextG-ERP. All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://nextgerp.com
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
// Text Parameters
$myEmailLabel = $params->get('email_label', 'Email Address *');
$nameLabel = $params->get('name_label', 'Full Name *');
$phoneLabel = $params->get('phone_label', 'Phone number');
$addressLabel = $params->get('address_label', 'Address');
$agent_type_label = $params->get('agent_type_label', 'Select Partner Program *');
$mySubjectLabel = $params->get('subject_label', 'Subject *');
$mySubjectValue = $params->get('subject_value', '');
$myMessageLabel = $params->get('message_label', 'Your Information *');
$buttonText = $params->get('button_text', 'Send Message Now');

$option_html = "";
$agent_option = $params->get('agent_option', '');
if ($agent_option) {
        $agent_option = explode(',', $agent_option);

        foreach ($agent_option as $key => $value) {
                $option_html .= '<option value="' . $value . '">' . $value . '</option>';
        }
}

// URL Parameters
$exact_url = $params->get('exact_url', true);
$disable_https = $params->get('disable_https', true);
$fixed_url = $params->get('fixed_url', true);
$myFixedURL = $params->get('fixed_url_address', '');

// Anti-spam Parameters
$enable_anti_spam = $params->get('enable_anti_spam', true);
$myAntiSpamQuestion = $params->get('anti_spam_q', 'How many eyes has a typical person?');
$myAntiSpamAnswer = $params->get('anti_spam_a', '2');
$anti_spam_position = $params->get('anti_spam_position', 0);

// Contact description
$contact_description = $params->get('contact_description', '');
$contact_info = $params->get('contact_info', '');

// Module Class Suffix Parameter
$mod_class_suffix = $params->get('moduleclass_sfx', '');

if ($fixed_url) {
        $url = $myFixedURL;
} else {
        if (!$exact_url) {
                $url = JURI::current();
        } else {
                if (!$disable_https) {
                        $url = (!empty($_SERVER['HTTPS'])) ? "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] : "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                } else {
                        $url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                }
        }
}

$url = htmlentities($url, ENT_COMPAT, "UTF-8");
?>
<?php if (!empty($contact_description)): ?>
        <div class="contact-start">
                <?php echo $contact_description; ?>
        </div>
<?php endif; ?>

<?php if (!empty($contact_info)): ?>
        <div class="row">
                <div class="col-md-4">
                        <div class="contact-info">
                                <?php echo $contact_info; ?>
                        </div>
                </div>
                <div class="col-md-8">
                <?php endif; ?>
                <form action="<?php echo $url; ?>" method="POST">
                        <?php
                        // print anti-spam
                        if ($enable_anti_spam) {
                                if ($anti_spam_position == 0) {
                                        ?>
                                        <div class="form-group">
                                                <input type="text" class="form-control" name="rp_anti_spam_answer" placeholder="<?php echo $myAntiSpamQuestion; ?>"/>
                                        </div>
                                        <?php
                                }
                        }
                        ?>
                        <div class="form-group">
                                <div class="row">
                                        <div class="col-md-6 col-xs-6">
                                                <div class="input-group">
                                                        <input type="text" name="rp_name" class="form-control" placeholder="<?php echo $nameLabel; ?>"/>
                                                        <div class="input-group-addon"><span class="fa fa-user"></span></div>
                                                </div>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                                <div class="input-group">
                                                        <input type="text" name="rp_email" class="form-control" placeholder="<?php echo $myEmailLabel; ?>"/>
                                                        <div class="input-group-addon"><span class="fa fa-envelope"></span></div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                        <div class="form-group">
                                <div class="row">
                                        <div class="col-md-6 col-xs-6">
                                                <div class="input-group">
                                                        <input type="text" name="rp_phone" class="form-control" placeholder="<?php echo $phoneLabel; ?>"/>
                                                        <div class="input-group-addon"><span class="fa fa-phone"></span></div>
                                                </div>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                                <div class="input-group">
                                                        <input type="text" name="rp_address" class="form-control" placeholder="<?php echo $addressLabel; ?>"/>
                                                        <div class="input-group-addon"><span class="fa fa-map-marker"></span></div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                        <div class="form-group">
                                <select class="form-control" name="rp_agent_type">
                                        <option value=""><?php echo $agent_type_label; ?></option>
                                        <?php echo $option_html; ?>
                                </select>
                        </div>
                        <div class="form-group">
                                <input type="text" class="form-control" name="rp_subject" placeholder="<?php echo $mySubjectLabel; ?>" value="<?php $mySubjectValue; ?>"/>
                        </div>
                        <div class="form-group">
                                <textarea class="form-control" rows="6" name="rp_message" placeholder="<?php echo $myMessageLabel; ?>"></textarea>
                        </div>
                        <?php
                        // print anti-spam
                        if ($enable_anti_spam) {
                                if ($anti_spam_position == 1) {
                                        ?>
                                        <div class="form-group">
                                                <input type="text" class="form-control" name="rp_anti_spam_answer" placeholder="<?php echo $myAntiSpamQuestion; ?>"/>
                                        </div>
                                        <?php
                                }
                        }
                        ?>
                        <div class="response"></div>
                        <div class="form-group">
                                <button class="agentregister-button btn btn-default btn-block" type="submit"><span class="hidden fa fa-spinner fa-pulse fa-fw margin-bottom"></span><?php echo $buttonText; ?></button>
                        </div>
                </form>
                <?php if (!empty($contact_info)): ?>
                </div>
        </div>
<?php endif; ?>
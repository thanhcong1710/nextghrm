<?php

/**
 * @package mod_kiwicontact
 * @subpackage  mod_kiwicontact
 *
 * @copyright Copyright (C) 2015 NextG-ERP. All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://nextgerp.com
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

class ModKiwiContactHelper {

        public static function getAjax() {
                // Get module parameters
                jimport('joomla.application.module.helper');
                $input = JFactory::getApplication()->input;
                $module = JModuleHelper::getModule('kiwicontact');
                $params = new JRegistry();
                $params->loadString($module->params);
                $response = array();
                $response['error'] = true;
                $response['html'] = "";
                if ($input->get('cmd')) {
                        $recipient = $params->get('email_recipient', '');
                        $fromName = $params->get('from_name', 'KiwiERP Contact');
                        $fromEmail = $params->get('from_email', 'no-reply@erponline.co.nz');

                        if (empty($recipient) || empty($fromEmail) || empty($fromName)) {
                                $response['html'] = 'Invalid setting';
                                return $response;
                        }

                        $enable_anti_spam = $params->get('enable_anti_spam', true);
                        $pageText = $params->get('page_text', 'Thank you for your contact.');
                        $errorText = $params->get('error_text', 'Your message could not be sent. Please try again.');
                        $noEmail = $params->get('no_email', 'Please write your email');
                        $emptyField = $params->get('empty_field', 'Please write all required field');
                        $invalidEmail = $params->get('invalid_email', 'Please write a valid email');
                        $wrongantispamanswer = $params->get('wrong_antispam', 'Wrong anti-spam answer');
                        $myAntiSpamAnswer = $params->get('anti_spam_a', 0);
                        $data = $input->get('data', array(), 'array');
                        // Verify post data
                        if (empty($data['rp_name']) || empty($data['rp_email']) || empty($data['rp_subject']) || empty($data['rp_message'])) {
                                $response['html'] = $emptyField;
                                return $response;
                        }

                        // check anti-spam
                        if ($enable_anti_spam) {
                                if (empty($data["rp_anti_spam_answer"]) || $data["rp_anti_spam_answer"] != $myAntiSpamAnswer) {
                                        $response['html'] = $wrongantispamanswer;
                                        return $response;
                                }
                        }
                        // check email
                        if ($data["rp_email"] === "") {
                                $response['html'] = $noEmail;
                                return $response;
                        }

                        if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", strtolower($data["rp_email"]))) {
                                $response['html'] = $invalidEmail;
                                return $response;
                        }

                        $mySubject = $data["rp_subject"];
                        $emailTemplate = $params->get('email_template', '');
                        $myMessage = static::createEmail($emailTemplate, $data);

                        $mailSender = JFactory::getMailer();
                        $mailSender->isHTML(true);
                        $mailSender->Encoding = 'base64';
                        $mailSender->addRecipient($recipient);
                        $mailSender->setSender(array($fromEmail, $fromName));
                        $mailSender->addReplyTo(array($data["rp_email"], $data["rp_name"]));
                        $mailSender->setSubject($mySubject);
                        $mailSender->setBody($myMessage);

                        if ($mailSender->Send() !== true) {
                                $response['html'] = $errorText;
                        } else {
                                $response['error'] = false;
                                $response['html'] = $pageText;
                        }
                        return $response;
                }
                return $response;
        }

        /**
         * Method to prepare email template before send
         * @param string $template
         * @param array $data
         * @return string
         * @since 1.0
         */
        protected static function createEmail($template, $data) {
                if (empty($template)) {
                        $html = 'You received a message from ' . $data["rp_email"] . "\n\n" . $data['rp_name'] . "\n\n" . $data["rp_message"];
                } else {
                        $html = str_replace('{visitor_name}', $data['rp_name'], $template);
                        $html = str_replace('{visitor_email}', $data['rp_email'], $html);
                        $html = str_replace('{message}', $data['rp_message'], $html);
                }
                $html = static::setHtmlBody($html);
                return $html;
        }

        /**
         * Prepare html format
         * @param string $content
         * @return string
         * @since 3.0
         */
        protected static function setHtmlBody($content) {
                $html = '';
                $html .= '<!DOCTYPE html>'
                        . '<html>'
                        . '<head>'
                        . '<meta charset="UTF-8">'
                        . '</head>'
                        . '<body>'
                        . '<div class="mail-main">';
                $html .= $content;
                $html .= '</div>'
                        . '</body>'
                        . '</html>';
                return $html;
        }

}

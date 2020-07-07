<?php

/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2015 NextgCyber . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://nextgerp.com
 * @author Daniel.Vu
 */
// No direct access
defined('_JEXEC') or die;
JLoader::register('NextgCyberControllerBase', JPATH_COMPONENT . '/controllers/base.php');
JLoader::register('NextgCyberCustomerHelper', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/customerhelper.php');
JLoader::register('NextgCyberIPHelper', JPATH_COMPONENT . '/helpers/iphelper.php');

class NextgCyberControllerInstance extends NextgCyberControllerBase {

    public function getModel($name = 'Instance', $prefix = 'NextgCyberModel', $config = array('ignore_request' => true)) {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

    /**
     * Method get instance notification
     * AJAX Request
     * @since 1.0
     * @return JSON
     */
    public function getNotification() {
        ob_start();
        $response = [];
        // Invalid id
        if (!$instance_id = $this->input->get('id', null, 'int')) {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT');
            ob_end_clean();
            echo new JResponseJson($response);
            return false;
        }

        $user = JFactory::getUser();
        $partner_id = NextgCyberCustomerHelper::getPartnerIdByID($user->get('id'));
        // authorise user
        if (NextgCyberCustomerHelper::authoriseInstance($partner_id, $instance_id)) {
            /* @var $model NextgCyberModelInstance */
            $model = JModelLegacy::getInstance('Instance', 'NextgCyberModel');
            $instance = $model->getItem($instance_id);
            if ($instance) {
                $response['html'] = JLayoutHelper::render('com_nextgcyber.instance.notification', $instance, JPATH_COMPONENT);
            } else {
                $response['error'] = NextgCyberHelper::getErrorMessage(true);
            }
        } else {
            $response['error'] = JText::_('COM_NEXTGCYBER_PERMISSION_DENIED');
        }
        ob_end_clean();
        echo new JResponseJson($response);
    }

    /**
     * Check instance running
     * AJAX Request
     * @since 1.0
     * @return JSON
     */
    public function isRunning() {
        ob_start();
        $response = [];
        if (!$instance_id = $this->input->get('id', null, 'int')) {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT');
            ob_end_clean();
            echo new JResponseJson($response);
            return false;
        }

        $user = JFactory::getUser();
        $partner_id = NextgCyberCustomerHelper::getPartnerIdByID($user->get('id'));
        /* @var $model NextgCyberModelInstance */
        $instanceModel = JModelLegacy::getInstance('Instance', 'NextgCyberModel');
        if (NextgCyberCustomerHelper::authoriseInstance($partner_id, $instance_id)) {
            $instance = $instanceModel->getItem($instance_id);
            if ($instance->operation_state == 'run') {
                $response['html'] = '<p class="text-success">' . JText::_('COM_NEXTGCYBER_INSTANCE_IS_RUNNING') . '</p>';
                $response['status'] = 1;
            } else {
                $response['html'] = '<p class="text-danger">' . JText::_('COM_NEXTGCYBER_INSTANCE_WAS_STOPPED') . '</p>';
                $response['status'] = 0;
            }
        } else {
            $response['error'] = JText::_('COM_NEXTGCYBER_PERMISSION_DENIED');
            $response['status'] = -1;
        }
        ob_end_clean();
        echo new JResponseJson(($response));
    }

    /**
     * Method start Odoo
     * AJAX Request
     * @since 1.0
     * @return JSON
     */
    public function start() {
        ob_start();
        $response = [];
        if (!$instance_id = $this->input->get('id', null, 'int')) {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT');
            ob_end_clean();
            echo new JResponseJson($response);
            return false;
        }

        if (NextgCyberCustomerHelper::canStartInstance($instance_id)) {
            /* @var $model NextgCyberModelInstance */
            $model = NextgCyberHelper::getAdminModel('Instance');
            if ($model->start($instance_id)) {
                $response['html'] = '<div class="alert alert-success">' . JText::_('COM_NEXTGCYBER_INSTANCE_START_ODOO_SUCCESS') . '</div>';
            } else {
                $response['error'] = NextgCyberHelper::getErrorMessage(true);
            }
        } else {
            $response['error'] = JText::_('COM_NEXTGCYBER_PERMISSION_DENIED');
        }
        ob_end_clean();
        echo new JResponseJson(($response));
    }

    /**
     * Method stop Odoo
     * AJAX Request
     * @since 1.0
     * @return JSON
     */
    public function stop() {
        ob_start();
        $response = [];
        if (!$instance_id = $this->input->get('id', null, 'int')) {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT');
            ob_end_clean();
            echo new JResponseJson($response);
            return false;
        }

        if (NextgCyberCustomerHelper::canStopInstance($instance_id)) {
            /* @var $model NextgCyberModelInstance */
            $model = NextgCyberHelper::getAdminModel('Instance');
            if ($model->stop($instance_id)) {
                $response['html'] = '<div class="alert alert-success">' . JText::_('COM_NEXTGCYBER_INSTANCE_STOP_ODOO_SUCCESS') . '</div>';
            } else {
                $response['error'] = NextgCyberHelper::getErrorMessage(true);
            }
        } else {
            $response['error'] = JText::_('COM_NEXTGCYBER_PERMISSION_DENIED');
        }
        ob_end_clean();
        echo new JResponseJson(($response));
    }

    /**
     * Method stop Odoo
     * AJAX Request
     * @since 1.0
     * @return JSON
     */
    public function restart() {
        ob_start();
        $response = [];
        if (!$instance_id = $this->input->get('id', null, 'int')) {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT');
            ob_end_clean();
            echo new JResponseJson($response);
            return false;
        }

        if (NextgCyberCustomerHelper::canRestartInstance($instance_id)) {
            /* @var $model NextgCyberModelInstance */
            $model = NextgCyberHelper::getAdminModel('Instance');
            if ($model->restart($instance_id)) {
                $response['html'] = '<div class="alert alert-success">' . JText::_('COM_NEXTGCYBER_INSTANCE_RESTART_ODOO_SUCCESS') . '</div>';
            } else {
                $response['error'] = NextgCyberHelper::getErrorMessage(true);
            }
        } else {
            $response['error'] = JText::_('COM_NEXTGCYBER_PERMISSION_DENIED');
        }
        ob_end_clean();
        echo new JResponseJson(($response));
    }

    /**
     * Method to delete instance
     * @return JSON
     *
     * @since 1.0
     */
    public function delete() {
        ob_start();
        $response = [];
        $response['label'] = JText::_('COM_NEXTGCYBER_INSTANCE_DELETE_ODOO_HEADER');
        $token = $this->input->get('token', null, 'string');
        if (!($instance_id = $this->input->getInt('id')) || empty($token)) {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT');
            ob_end_clean();
            echo new JResponseJson($response);
            return false;
        }

        $user = JFactory::getUser();
        $app = JFactory::getApplication();
        // Verify Token
        $hashPassword = NextgCyberHelper::generateToken();
        $verifyToken = md5($instance_id . $user->get('username') . $hashPassword);
        if ($token != $verifyToken) {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT');
            ob_end_clean();
            echo new JResponseJson($response);
            return false;
        }
        if (NextgCyberCustomerHelper::canDeleteInstance($instance_id)) {
            /* @var $model NextgCyberModelInstance */
            $model = NextgCyberHelper::getAdminModel('Instance');
            if ($model->revoke($instance_id)) {
                $response['html'] = '<div class="alert alert-success">' . JText::_('COM_NEXTGCYBER_INSTANCE_DELETE_ODOO_SUCCESS') . '</div>';
                $ssl_config = ($app->get('force_ssl') == 2) ? 1 : 2;
                $response['redirect_url'] = JRoute::_(NextgCyberHelperRoute::getDashboardRoute(), true, $ssl_config);
            } else {
                $response['error'] = NextgCyberHelper::getErrorMessage(true);
            }
        } else {
            $response['error'] = JText::_('COM_NEXTGCYBER_PERMISSION_DENIED');
        }
        ob_end_clean();
        echo new JResponseJson($response);
    }

    /**
     * Method to redeploy instance
     * @return JSON
     *
     * @since 1.0
     */
    public function redeploy() {
        ob_start();
        $response = [];
        $response['label'] = JText::_('COM_NEXTGCYBER_INSTANCE_REDEPLOY_ODOO_HEADER');
        $token = $this->input->get('token', null, 'string');
        if (!($instance_id = $this->input->getInt('id')) || empty($token)) {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT');
            ob_end_clean();
            echo new JResponseJson($response);
            return false;
        }

        $user = JFactory::getUser();
        // Verify Token
        $hashPassword = NextgCyberHelper::generateToken();
        $verifyToken = md5($instance_id . $user->get('username') . $hashPassword);
        if ($token != $verifyToken) {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT');
            ob_end_clean();
            echo new JResponseJson($response);
            return false;
        }
        if (NextgCyberCustomerHelper::canRedeployInstance($instance_id)) {
            /* @var $model NextgCyberModelInstance */
            $model = NextgCyberHelper::getAdminModel('Instance');
            if ($model->redeploy($instance_id)) {
//                $response['html'] = '<div class="alert alert-success">' . JText::_('COM_NEXTGCYBER_INSTANCE_REDEPLOY_ODOO_SUCCESS') . '</div>';
//                $ssl_config = ($app->get('force_ssl') == 2) ? 1 : 2;
//                $response['redirect_url'] = JRoute::_(NextgCyberHelperRoute::getInstanceRoute($instance_id), true, $ssl_config);
                $model->isReady($instance_id);
                $response['percentage'] = 0;
                $response['progress'] = true;
                $response['instance_id'] = $instance_id;
            } else {
                $response['error'] = NextgCyberHelper::getErrorMessage(true);
            }
        } else {
            $response['error'] = JText::_('COM_NEXTGCYBER_PERMISSION_DENIED');
        }
        ob_end_clean();
        echo new JResponseJson($response);
    }

    /**
     * Method to get custom domain form
     * @return JSON
     * @since 1.0
     * @author Daniel.Vu
     */
    public function getCustomDomainForm() {
        $user = JFactory::getUser();
        $input = JFactory::getApplication()->input;
        $id = $input->getInt('id', 0);
        $response = [];
        if ($user->guest) {
            $response['label'] = JText::_('COM_NEXTGCYBER_INSTANCE_FORM_ERROR_GUEST_REQUEST');
            $response['html'] = JLayoutHelper::render('com_nextgcyber.form.loginform', null, JPATH_COMPONENT);
            echo new JResponseJson($response);
            return false;
        } else {
            $displayData = new stdClass();
            $displayData->id = $id;
            $displayData->subtitle = JText::_('COM_NEXTGCYBER_INSTANCE_FORM_CUSTOM_DOMAIN_FORM_SUBTITLE');
            $response['label'] = JText::_('COM_NEXTGCYBER_INSTANCE_FORM_CUSTOM_DOMAIN_FORM');
            $response['html'] = JLayoutHelper::render('com_nextgcyber.form.customdomain', $displayData, JPATH_COMPONENT);
            echo new JResponseJson($response);
            return false;
        }
    }

    public function addCustomDomain() {
        ob_start();
        $response = [];
        $response['label'] = JText::_('COM_NEXTGCYBER_INSTANCE_FORM_VERIFY_CUSTOM_DOMAIN');
        $instance_id = $this->input->get('id', null, 'int');
        $domain = $this->input->getString('domain');
        $customDomainModel = NextgCyberHelper::getAdminModel('CustomDomain');
        if (!$customDomainModel->isValidDomain($domain)) {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_FORM_INVALID_DOMAIN_NAME');
            echo new JResponseJson($response);
            return false;
        }

        $partner_id = NextgCyberCustomerHelper::getPartnerIdByID();
        $exist = $customDomainModel->isExist($domain, $partner_id);
        $data = [];
        if ($exist) {
            $data['id'] = $exist->id;
            if (!empty($exist->instance_id[0]) && $exist->instance_id[0] != $instance_id) {
                $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_FORM_DOMAIN_NAME_USED');
                echo new JResponseJson($response);
                return false;
            }
        }

        $data['name'] = $domain;
        $data['partner_id'] = NextgCyberCustomerHelper::getPartnerIdByID();
        if ($instance_id && NextgCyberCustomerHelper::canAddCustomDomain($instance_id)) {
            $data['instance_id'] = $instance_id;
        }

        if ($customDomainModel->save($data)) {
            $customdomainId = (!empty($data['id'])) ? $data['id'] : $customDomainModel->getState($customDomainModel->getName() . '.id');
            $customDomain = $customDomainModel->getItem($customdomainId);
            $response['html'] = JLayoutHelper::render('com_nextgcyber.form.verifydomain', $customDomain, JPATH_COMPONENT);
        } else {
            $response['error'] = JText::_('COM_NEXTGCYBER_PERMISSION_DENIED');
        }
        ob_end_clean();
        echo new JResponseJson(($response));
    }

    public function validateDomain() {
        ob_start();
        $response = [];
        $response['label'] = JText::_('COM_NEXTGCYBER_INSTANCE_FORM_VERIFY_CUSTOM_DOMAIN');
        $domain_id = $this->input->get('id', null, 'int');
        if ($domain_id) {
            $customDomainModel = NextgCyberHelper::getAdminModel('CustomDomain');
            $customDomain = $customDomainModel->getItem($domain_id);
            if ($customDomainModel->verify($domain_id)) {
                $response['html'] = '<div class="alert alert-success">' . JText::_('COM_NEXTGCYBER_INSTANCE_VERIFY_CUSTOM_DOMAIN_SUCCESS') . '</div>';
                $response['domain_id'] = $domain_id;
                $response['domain_name'] = $customDomain->name;
                if (!empty($customDomain->instance_id)) {
                    $app = JFactory::getApplication();
                    $ssl_config = ($app->get('force_ssl') == 2) ? 1 : 2;
                    $response['redirect_url'] = JRoute::_(NextgCyberHelperRoute::getInstanceRoute($customDomain->instance_id), true, $ssl_config);
                }
            } else {
                $response['html'] = JLayoutHelper::render('com_nextgcyber.form.verifydomain', $customDomain, JPATH_COMPONENT);
                //$response['html'] = '<div class="alert alert-success">' . JText::_('COM_NEXTGCYBER_INSTANCE_VERIFY_CUSTOM_DOMAIN_SUCCESS') . '</div>';
                //$response['domain_id'] = $domain_id;
            }
        } else {
            $response['error'] = JText::_('COM_NEXTGCYBER_PERMISSION_DENIED');
        }
        ob_end_clean();
        echo new JResponseJson(($response));
    }

    public function startTrial() {
        $app = JFactory::getApplication();
        $input = $app->input;
        $response = [];
        $response['label'] = JText::_('COM_NEXTGCYBER_PRICING_START_TRIAL');
        $user = JFactory::getUser();
        if ($user->guest) {
            $response['label'] = JText::_('COM_NEXTGCYBER_INSTANCE_FORM_ERROR_GUEST_REQUEST');
            $response['html'] = JLayoutHelper::render('com_nextgcyber.form.loginform', null, JPATH_COMPONENT);
            echo new JResponseJson($response);
            return false;
        }
        $domain = $input->getString('domain', '');
        $customdomain_id = $input->getInt('customdomain_id');
        $apps = $input->get('apps', array(), 'array');
        if ((empty($domain) && empty($customdomain_id)) || empty($apps)) {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT');
            ob_end_clean();
            echo new JResponseJson($response);
            return false;
        }

        if (!NextgCyberCustomerHelper::getTotalNumberFreeInstanceCanCreate()) {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_LIMIT_FREE_INSTANCE');
            ob_end_clean();
            echo new JResponseJson($response);
            return false;
        }

        if (NextgCyberCustomerHelper::canCreateNewTrial()) {
            /* @var $model NextgCyberModelInstance */
            $model = NextgCyberHelper::getAdminModel('Instance');
            $partner_id = NextgCyberCustomerHelper::getPartnerIdByID();
            if ($instance_id = $model->createInstance($domain, $partner_id, $apps, $customdomain_id, 'trial')) {
//                $full_domain = $model->getFullDomain($instance_id);
//                $response['html'] = '<div class="alert alert-success">' . JText::sprintf('COM_NEXTGCYBER_INSTANCE_DETAIL_PAGE_CREATE_TRIAL_SUCCCESS', '<a href="http://' . $full_domain . '" target="_blank">' . $full_domain . '</a>') . '</div>';
//                $ssl_config = ($app->get('force_ssl') == 2) ? 1 : 2;
//                $response['redirect_url'] = JRoute::_(NextgCyberHelperRoute::getInstanceRoute($instance_id), true, $ssl_config);
//                $session = JFactory::getSession();
//                $session->set('pricing.store', null);
//                $session->set('trial.message', JText::sprintf('COM_NEXTGCYBER_INSTANCE_DETAIL_PAGE_CREATE_TRIAL_SUCCCESS', '<a href="http://' . $full_domain . '" target="_blank">' . $full_domain . '</a>'));
                # get progress
                $model->isReady($instance_id);
                $response['percentage'] = 0;
                $response['progress'] = true;
                $response['instance_id'] = $instance_id;
            } else {
                $response['error'] = NextgCyberHelper::getErrorMessage(true);
            }
        } else {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT');
        }
        ob_end_clean();
        echo new JResponseJson($response);
        return false;
    }

    public function getProgress() {
        ob_start();
        $response = [];
        $response['label'] = JText::_('COM_NEXTGCYBER_INSTANCE_REDEPLOY_ODOO_HEADER');
        if (!($instance_id = $this->input->getInt('id'))) {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT');
            ob_end_clean();
            echo new JResponseJson($response);
            return false;
        }

        $app = JFactory::getApplication();
        $partner_id = NextgCyberCustomerHelper::getPartnerIdByID();
        if (NextgCyberCustomerHelper::authoriseInstance($partner_id, $instance_id)) {
            /* @var $model NextgCyberModelInstance */
            $model = NextgCyberHelper::getAdminModel('Instance');
            $percentage = $model->getInstallPercentage($instance_id);
            if ($model->isReady($instance_id) || $percentage >= 100) {
                $full_domain = $model->getFullDomain($instance_id);
                $response['html'] = '<div class="alert alert-success">' . JText::sprintf('COM_NEXTGCYBER_INSTANCE_DETAIL_PAGE_CREATE_TRIAL_SUCCCESS', '<a href="http://' . $full_domain . '" target="_blank">' . $full_domain . '</a>') . '</div>';
                $ssl_config = ($app->get('force_ssl') == 2) ? 1 : 2;
                $response['redirect_url'] = JRoute::_(NextgCyberHelperRoute::getInstanceRoute($instance_id), true, $ssl_config);
                $session = JFactory::getSession();
                $session->set('pricing.store', null);
                $session->set('trial.message', JText::sprintf('COM_NEXTGCYBER_INSTANCE_DETAIL_PAGE_CREATE_TRIAL_SUCCCESS', '<a href="http://' . $full_domain . '" target="_blank">' . $full_domain . '</a>'));
            }
            $response['id'] = $instance_id;
            $response['percentage'] = $percentage;
            $response['log'] = $model->getLastLog($instance_id);
        } else {
            $response['error'] = JText::_('COM_NEXTGCYBER_PERMISSION_DENIED');
        }
        ob_end_clean();
        echo new JResponseJson($response);
    }

    /**
     * Method upgrade instance from trial to normal
     * AJAX Request
     * @since 1.1
     * @return JSON
     */
    public function upgrade() {
        ob_start();
        $user = JFactory::getUser();
        if ($user->guest) {
            $response['label'] = JText::_('COM_NEXTGCYBER_INSTANCE_FORM_ERROR_GUEST_REQUEST');
            $response['html'] = JLayoutHelper::render('com_nextgcyber.form.loginform', null, JPATH_COMPONENT);
            echo new JResponseJson($response);
            return false;
        }
        $response = [];
        $response['label'] = JText::_('COM_NEXTGCYBER_INSTANCE_UPGRADE_ODOO_HEADER');
        $token = $this->input->get('token', null, 'string');
        $payment_period_id = $this->input->getInt('payment_period_id', null);
        $coupon_code = $this->input->getString('coupon_code', '');
        $return = $this->input->getString('return', '');

        if (!($instance_id = $this->input->getInt('id')) || empty($token) || empty($payment_period_id)) {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT');
            ob_end_clean();
            echo new JResponseJson($response);
            return false;
        }

        // Verify Token
        $hashPassword = NextgCyberHelper::generateToken();
        $verifyToken = md5($instance_id . $user->get('username') . $hashPassword);
        if ($token != $verifyToken) {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT');
            ob_end_clean();
            echo new JResponseJson($response);
            return false;
        }
        if (NextgCyberCustomerHelper::canUpgradeInstance($instance_id)) {

            if (!empty($coupon_code)) {
                $partner_id = NextgCyberCustomerHelper::getPartnerIdByID();
                $email = NextgCyberCustomerHelper::getEmail($partner_id);
                $couponCodeModel = NextgCyberHelper::getAdminModel('CouponCode');
                if (!$couponCodeModel->validate($coupon_code, null, $partner_id, $email)) {
                    $response['error'] = JText::_('COM_NEXTGCYBER_ERROR_INVALID_COUPONCODE');
                    ob_end_clean();
                    echo new JResponseJson($response);
                    return false;
                }
            }

            /* @var $model NextgCyberModelInstance */
            $model = NextgCyberHelper::getAdminModel('Instance');
            $without_tax = !NextgCyberIPHelper::useTax();
            if ($order_id = $model->upgrade_trial_2_normal($instance_id, $payment_period_id, $coupon_code, $without_tax)) {
                $orderModel = NextgCyberHelper::getAdminModel('Order');
                $order = $orderModel->getItem($order_id);
                $response['html'] = JLayoutHelper::render('com_nextgcyber.orders.item', $order, JPATH_COMPONENT, array('active' => true, 'ajax' => true, 'return' => $return, 'display_info' => false));
            } else {
                $response['error'] = NextgCyberHelper::getErrorMessage(true);
            }
        } else {
            $response['error'] = JText::_('COM_NEXTGCYBER_PERMISSION_DENIED');
        }
        ob_end_clean();
        echo new JResponseJson($response);
    }

    /**
     * Method to get pricelist form
     * AJAX Request
     * @since 1.1
     * @return JSON
     */
    public function getPriceList() {
        ob_start();
        $response = [];
        $response['label'] = JText::_('COM_NEXTGCYBER_INSTANCE_UPGRADE_ODOO_HEADER');
        $token = $this->input->get('token', null, 'string');
        $return = $this->input->getString('return', '');
        if (!($instance_id = $this->input->getInt('id')) || empty($token)) {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT');
            ob_end_clean();
            echo new JResponseJson($response);
            return false;
        }

        $user = JFactory::getUser();
        $app = JFactory::getApplication();
        // Verify Token
        $hashPassword = NextgCyberHelper::generateToken();
        $verifyToken = md5($instance_id . $user->get('username') . $hashPassword);
        if ($token != $verifyToken) {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT');
            ob_end_clean();
            echo new JResponseJson($response);
            return false;
        }

        if (NextgCyberCustomerHelper::canUpgradeInstance($instance_id)) {
            $displayData = new stdClass();
            $displayData->id = $instance_id;
            $displayData->token = $token;
            $displayData->subtitle = JText::_('COM_NEXTGCYBER_INSTANCE_UPGRADE_PRICELIST_SUBTITLE');
            $formModel = JModelLegacy::getInstance('Form', 'NextgCyberModel');
            $displayData->options = $formModel->getPaymentPeriod();
            $response['html'] = JLayoutHelper::render('com_nextgcyber.form.paymentperiod', $displayData, JPATH_COMPONENT, array('return' => $return));
        } else {
            $response['error'] = JText::_('COM_NEXTGCYBER_PERMISSION_DENIED');
        }
        ob_end_clean();
        echo new JResponseJson($response);
    }

    /**
     * Method to create instance and invoice
     * @return boolean
     * @since 1.0
     */
    public function payNow() {
        $user = JFactory::getUser();
        $app = JFactory::getApplication();
        $input = $app->input;
        $response = [];
        $response['label'] = JText::_('COM_NEXTGCYBER_PRICING_START_PAID_INSTANCE');
        if ($user->guest) {
            $response['label'] = JText::_('COM_NEXTGCYBER_INSTANCE_FORM_ERROR_GUEST_REQUEST');
            $response['html'] = JLayoutHelper::render('com_nextgcyber.form.loginform', null, JPATH_COMPONENT);
            echo new JResponseJson($response);
            return false;
        }
        $domain = $input->getString('domain', '');
        $customdomain_id = $input->getInt('customdomain_id');
        $apps = $input->get('apps', array(), 'array');
        $paymentperiod_id = $input->getInt('paymentperiod_id');
        if ((empty($domain) && empty($customdomain_id)) || empty($apps) || empty($paymentperiod_id)) {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT');
            ob_end_clean();
            echo new JResponseJson($response);
            return false;
        }

        if (NextgCyberCustomerHelper::canCreateNewPaidInstance()) {
            $session = JFactory::getSession();
            /* @var $model NextgCyberModelOrder */
            $model = NextgCyberHelper::getAdminModel('Order');
            $partner_id = NextgCyberCustomerHelper::getPartnerIdByID();
            $orderData = [];
            $orderData['id'] = 0;
            $orderData['products'] = $apps;
            $orderData['partner_id'] = $partner_id;
            $orderData['nc_payment_period_id'] = $paymentperiod_id;
            $orderData['nc_instance_subdomain'] = $domain;
            $orderData['nc_type'] = 'odoo_instance';
            $without_tax = !NextgCyberIPHelper::useTax();
            $orderData['nc_without_tax'] = $without_tax;
            if ($customdomain_id) {
                $customer_domain_ids = array();
                $customer_domain_ids[] = array(6, 0, array($customdomain_id));
                $orderData['nc_instance_customer_domain_ids'] = $customer_domain_ids;
            }

            $pricing_store = $session->get('pricing.store', null);
            $couponcode = (!empty($pricing_store['couponcode'])) ? $pricing_store['couponcode'] : null;
            $orderData['nc_coupon_code'] = (!empty($couponcode['code'])) ? $couponcode['code'] : null;

            if ($order_id = $model->save($orderData)) {
                if (!empty($orderData['nc_coupon_code'])) {
                    if (!$model->applyDiscount($order_id)) {
                        $model->unlink($order_id);
                        $response['error'] = JText::_('COM_NEXTGCYBER_ERROR_INVALID_COUPONCODE');
                        ob_end_clean();
                        echo new JResponseJson($response);
                        return false;
                    }
                }

                $invoice_id = $model->confirm($order_id);
                $response['html'] = '<div class="alert alert-success">' . JText::_('COM_NEXTGCYBER_INSTANCE_UPGRADE_TO_ORDER_SUCCESS') . '</div>';
                $ssl_config = ($app->get('force_ssl') == 2) ? 1 : 2;
                $response['redirect_url'] = JRoute::_(NextgCyberHelperRoute::getRegisterPaymentRoute($invoice_id), true, $ssl_config);
                $session->set('pricing.store', null);
            } else {
                $response['error'] = NextgCyberHelper::getErrorMessage(true);
            }
        } else {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT');
        }
        ob_end_clean();
        echo new JResponseJson($response);
        return false;
    }

    /**
     * Method to get addons form
     * @return JSON
     * @since 1.3
     * @author Daniel.Vu
     */
    public function getAddonsForm() {
        $user = JFactory::getUser();
        $response = [];
        if ($user->guest) {
            $response['label'] = JText::_('COM_NEXTGCYBER_INSTANCE_FORM_ERROR_GUEST_REQUEST');
            $response['html'] = JLayoutHelper::render('com_nextgcyber.form.loginform', null, JPATH_COMPONENT);
            echo new JResponseJson($response);
            return false;
        }
        $input = JFactory::getApplication()->input;
        $id = $input->getInt('id', 0);
        $token = $input->get('token', null, 'string');
        $return = $input->getString('return');
        $hashPassword = NextgCyberHelper::generateToken();
        $verifyToken = md5($id . $user->get('username') . $hashPassword);
        if ($token != $verifyToken) {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT');
            ob_end_clean();
            echo new JResponseJson($response);
            return false;
        }

        if (!NextgCyberCustomerHelper::canAddResourceIntoInstance($id)) {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT');
            ob_end_clean();
            echo new JResponseJson($response);
            return false;
        }

        $instanceModel = JModelLegacy::getInstance('Instance', 'NextgCyberModel');
        $instance = $instanceModel->getItem($id);
        $currentAppIds = [];
        if (!empty($instance->apps)) {
            foreach ($instance->apps as $odoo_app) {
                $currentAppIds[] = $odoo_app->id;
            }
        }

        $displayData = new stdClass();
        $displayData->id = $id;
        $displayData->subtitle = JText::_('COM_NEXTGCYBER_INSTANCE_ADDONS_FORM_SUBTITLE');
        $productsModel = JModelLegacy::getInstance('Pricing', 'NextgCyberModel');
        $productsModel->clearState();
        $productsModel->setState('filter.not', $currentAppIds);
        $displayData->apps = $productsModel->getItems();
        $displayData->return = $return;
        $response['label'] = JText::_('COM_NEXTGCYBER_INSTANCE_ADDONS_FORM_HEADER');
        $response['html'] = JLayoutHelper::render('com_nextgcyber.form.addons', $displayData, JPATH_COMPONENT);
        echo new JResponseJson($response);
        return false;
    }

    /**
     * Method to add addons into instance
     * @return JSON
     * @since 1.3
     * @author Daniel.Vu
     */
    public function addAddons() {
        ob_start();
        $user = JFactory::getUser();
        $response = [];
        if ($user->guest) {
            $response['label'] = JText::_('COM_NEXTGCYBER_INSTANCE_FORM_ERROR_GUEST_REQUEST');
            $response['html'] = JLayoutHelper::render('com_nextgcyber.form.loginform', null, JPATH_COMPONENT);
            echo new JResponseJson($response);
            return false;
        }

        $response['label'] = JText::_('COM_NEXTGCYBER_INSTANCE_ADDONS_FORM_HEADER');
        $instance_id = $this->input->get('instance_id', null, 'int');
        $apps = $this->input->get('apps', array(), 'array');
        $return = $this->input->getString('return');
        $partner_id = NextgCyberCustomerHelper::getPartnerIdByID();
        if (empty($apps) || empty($instance_id) || empty($partner_id)) {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT');
            ob_end_clean();
            echo new JResponseJson($response);
            return false;
        }

        if (NextgCyberCustomerHelper::canAddResourceIntoInstance($instance_id)) {
            // Remove all draft order
            $ordersModel = NextgCyberHelper::getAdminModel('Orders');
            $ordersModel->clearState();
            $ordersModel->setState('filter.state', 'draft');
            $ordersModel->setState('filter.nc_type', 'odoo_addons');
            $ordersModel->setState('filter.nc_instance_id', $instance_id);
            $orders = $ordersModel->getItems();
            if (!empty($orders)) {
                $orderModel = NextgCyberHelper::getAdminModel('Order');
                foreach ($orders as $order) {
                    $orderModel->unlink($order->id);
                }
            }

            $instanceModel = NextgCyberHelper::getAdminModel('Instance');
            $instance = $instanceModel->getItem($instance_id);
            /* @var $model NextgCyberModelOrder */
            $model = NextgCyberHelper::getAdminModel('Order');
            $partner_id = NextgCyberCustomerHelper::getPartnerIdByID();
            $orderData = [];
            $orderData['id'] = 0;
            $orderData['products'] = $apps;
            $orderData['partner_id'] = $partner_id;
            $orderData['nc_payment_period_id'] = $instance->payment_period_id;
            $orderData['nc_instance_id'] = $instance_id;
            $orderData['nc_type'] = 'odoo_addons';
            $without_tax = !NextgCyberIPHelper::useTax();
            $orderData['nc_without_tax'] = $without_tax;
            if ($order_id = $model->save($orderData)) {
                $model->applyDiscount($order_id);
                $orderModel = NextgCyberHelper::getAdminModel('Order');
                $order = $orderModel->getItem($order_id);
                $response['html'] = JLayoutHelper::render('com_nextgcyber.orders.item', $order, JPATH_COMPONENT, array('active' => true, 'ajax' => true, 'return' => $return, 'display_info' => false));
            } else {
                $response['error'] = NextgCyberHelper::getErrorMessage(true);
            }
        } else {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT');
        }
        ob_end_clean();
        echo new JResponseJson($response);
        return false;
    }

}

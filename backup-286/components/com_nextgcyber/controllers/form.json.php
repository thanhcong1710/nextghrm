<?php

/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://domain.com
 * @author Daniel.Vu
 */
// No direct access
defined('_JEXEC') or die;
JLoader::register('NextgCyberControllerBase', JPATH_COMPONENT . '/controllers/base.php');

class NextgCyberControllerForm extends NextgCyberControllerBase {

    /**
     *
     * @param type $name
     * @param type $prefix
     * @param type $config
     * @return NextgCyberModelForm
     */
    public function getModel($name = 'Form', $prefix = 'NextgCyberModel', $config = array('ignore_request' => true)) {
        return parent::getModel($name, $prefix, $config);
    }

    /**
     * Method to store current apps
     * @since 1.0
     * @return JSON
     * @author Daniel.Vu
     */
    public function store() {
        $session = JFactory::getSession();
        $input = JFactory::getApplication()->input;
        $response = [];
        $domain = $input->getString('domain');
        $apps = $input->get('apps', array(), 'array');
        if (empty($apps)) {
            $response['error'] = JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT');
            ob_end_clean();
            echo new JResponseJson($response);
            return false;
        }
        $store = $session->get('pricing.store', array());
        if (empty($store['type'])) {
            $store['type'] = 'pricing';
        }
        $store['domain'] = $domain;
        $store['apps'] = array();
        foreach ($apps as $app) {
            $store['apps'][$app['id']] = $app;
        }
        $session->set('pricing.store', $store);
        ob_end_clean();
        echo new JResponseJson($response);
        return false;
    }

    /**
     * Method to process promotion code
     * @since 1.0
     * @return void
     * @author Daniel.Vu
     */
    public function confirmPromotion() {
        $response = [];
        $code = $this->input->getString('code', null);
        if (empty($code)) {
            $response['error'] = JText::_('COM_NEXTGCYBER_ERROR_INVALID_COUPONCODE');
            ob_end_clean();
            echo new JResponseJson($response);
            return false;
        }
        $session = JFactory::getSession();
        $store = $session->get('pricing.store', array());
        $partner_id = NextgCyberCustomerHelper::getPartnerIdByID();
        $email = NextgCyberCustomerHelper::getEmail($partner_id);
        $couponCodeModel = NextgCyberHelper::getAdminModel('CouponCode');
        if ($id = $couponCodeModel->validate($code, null, $partner_id, $email)) {
            $store['couponcode'] = array();
            $store['couponcode']['id'] = $id;
            $store['couponcode']['code'] = $code;
            $store['couponcode']['msg'] = $id;
            $store['couponcode']['item'] = $couponCodeModel->getItem($id);
            $session->set('pricing.store', $store);
            $response['html'] = JText::_('COM_NEXTGCYBER_PRICING_APPLY_COUPONCODE_SUCCESS');
            $response['value'] = $store['couponcode']['item']->value;
        } else {
            $store['couponcode'] = null;
            $session->set('pricing.store', $store);
            $response['error'] = JText::_('COM_NEXTGCYBER_ERROR_INVALID_COUPONCODE');
            $response['value'] = 0;
        }
        ob_end_clean();
        echo new JResponseJson($response);
        return false;
    }

}

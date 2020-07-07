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
JLoader::register('NextgCyberCustomerHelper', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/customerhelper.php');

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
     * Method to process promotion code
     * @since 1.0
     * @return void
     * @author Daniel.Vu
     */
    public function confirmPromotion() {
        $code = $this->input->getString('code', null);
        $return = $this->input->getString('return', null);
        if (!empty($return)) {
            $redirectUrl = base64_decode($return);
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
            $store['couponcode_success_msg'] = JText::_('COM_NEXTGCYBER_PRICING_APPLY_COUPONCODE_SUCCESS');
            $session->set('pricing.store', $store);
        } else {
            $store['couponcode'] = null;
            $store['couponcode_error_msg'] = JText::_('COM_NEXTGCYBER_ERROR_INVALID_COUPONCODE');
            $session->set('pricing.store', $store);
            //JFactory::getApplication()->enqueueMessage(JText::_('COM_NEXTGCYBER_ERROR_INVALID_COUPONCODE'), 'error');
        }
        if (empty($return)) {
            $redirectUrl = NextgCyberHelperRoute::getPricingRoute();
        }
        $this->setRedirect(JRoute::_($redirectUrl));
    }

}

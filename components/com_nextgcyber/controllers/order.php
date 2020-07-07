<?php

/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2015 NextgCyber . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://nextgerp.com
 * @author NextG-ERP support@nextgerp.com
 */
// No direct access
defined('_JEXEC') or die;
JLoader::register('NextgCyberCustomerHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/profilehelper.php');
JLoader::register('NextgCyberPaypalHelper', JPATH_COMPONENT . '/helpers/paypalhelper.php');
JLoader::register('NextgCyberControllerBase', JPATH_COMPONENT . '/controllers/base.php');

class NextgCyberControllerOrder extends NextgCyberControllerBase {

    public function cancel() {
        $app = JFactory::getApplication();
        $partner_id = NextgCyberCustomerHelper::getPartnerIdByID();
        $order_id = $this->input->get('id', null, 'int');
        $return = $this->input->getString('return', '');
        if (empty($partner_id) || empty($order_id)) {
            $app->enqueueMessage(JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT'), 'error');
            $app->redirect(JRoute::_(NextgCyberHelperRoute::getOrdersRoute()));
            return false;
        }

        if (NextgCyberCustomerHelper::canCancelOrder($order_id)) {
            $orderModel = NextgCyberHelper::getAdminModel('Order');
            $orderModel->unlink($order_id);
        }
        if ($return) {
            $return = base64_decode($return);
            $app->redirect($return);
            return true;
        } else {
            $app->redirect(JRoute::_(NextgCyberHelperRoute::getOrdersRoute()));
            return true;
        }
    }

    public function confirm() {
        $app = JFactory::getApplication();
        $partner_id = NextgCyberCustomerHelper::getPartnerIdByID();
        $order_id = $this->input->get('id', null, 'int');
        if (empty($partner_id) || empty($order_id)) {
            $app->enqueueMessage(JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT'), 'error');
            $app->redirect(JRoute::_(NextgCyberHelperRoute::getOrdersRoute()));
            return false;
        }

        if (NextgCyberCustomerHelper::canConfirmOrder($order_id)) {
            $orderModel = NextgCyberHelper::getAdminModel('Order');
            $invoice_id = $orderModel->confirm($order_id);
            if (empty($invoice_id)) {
                $app->enqueueMessage(JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT'), 'error');
                $app->redirect(JRoute::_(NextgCyberHelperRoute::getOrdersRoute()));
                return false;
            }
            $registerPaymentUrl = NextgCyberHelperRoute::getRegisterPaymentRoute($invoice_id);
            $app->redirect($registerPaymentUrl);
            return true;
        }
        $app->redirect(JRoute::_(NextgCyberHelperRoute::getOrdersRoute()));
        return true;
    }

}

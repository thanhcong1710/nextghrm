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

class NextgCyberControllerInvoice extends NextgCyberControllerBase {

    public function registerPayment() {
        $app = JFactory::getApplication();
        $partner_id = NextgCyberCustomerHelper::getPartnerIdByID();
        $invoice_id = $this->input->get('id', null, 'int');
        if (empty($partner_id) || empty($invoice_id)) {
            $app->enqueueMessage(JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT'), 'error');
            $app->redirect(JRoute::_(NextgCyberHelperRoute::getInvoicesRoute()));
            return false;
        }

        $paypalUrl = NextgCyberPaypalHelper::sendRequest($invoice_id);
        if (empty($paypalUrl)) {
            $app->enqueueMessage(JText::_('COM_NEXTGCYBER_INSTANCE_ERROR_BAD_ARGUMENT'), 'error');
            $app->redirect(JRoute::_(NextgCyberHelperRoute::getInvoicesRoute()));
            return false;
        }

        $this->setRedirect($paypalUrl);
        return true;
    }

}

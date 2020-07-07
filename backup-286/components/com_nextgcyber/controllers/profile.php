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
JLoader::register('NextgCyberControllerBase', JPATH_COMPONENT . '/controllers/base.php');

class NextgCyberControllerProfile extends NextgCyberControllerBase {

    /**
     * Method to check out a user for editing and redirect to the edit form.
     *
     * @since   1.0
     *
     */
    public function edit() {
        // Redirect to the edit screen.
        $this->setRedirect(JRoute::_('index.php?option=com_nextgcyber&view=profile&layout=edit', false));
    }

    /**
     * Method to save a user's profile data.
     *
     * @return  void
     * @since   1.6
     */
    public function save() {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $app = JFactory::getApplication();
        $model = $this->getModel('Profile', 'NextgCyberModel');
        $user = JFactory::getUser();
        $userId = (int) $user->get('id');
        // Get the user data from form
        $data = $app->input->post->get('jform', array(), 'array');
        // Force the ID to this profile.
        $data['id'] = NextgCyberCustomerHelper::getPartnerIdByID($userId);
        // Validate the posted data.
        $form = $model->getForm();
        if (!$form) {
            JError::raiseError(500, $model->getError());
            return false;
        }
        // Validate the posted data.
        $data = $model->validate($form, $data);
        // Check for errors.
        if ($data === false) {
            $errors = $model->getErrors();
            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }
            $app->setUserState('com_nextgcyber.edit.profile.data', $data);
            // Redirect back to the edit screen.
            $this->setRedirect(JRoute::_('index.php?option=com_nextgcyber&view=profile&layout=edit', false));
            return false;
        }

        // Attempt to save the data.
        $return = $model->save($data);
        // Check for errors.
        if ($return === false) {
            // Save the data in the session.
            $app->setUserState('com_nextgcyber.edit.profile.data', $data);

            // Redirect back to the edit screen.
            $this->setMessage(JText::sprintf('COM_NEXTGCYBER_PROFILE_SAVE_FAILED', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_nextgcyber&view=profile&layout=edit', false));
            return false;
        }

        // Redirect the user and adjust session state based on the chosen task.
        switch ($this->getTask()) {
            case 'apply':
                // Check out the profile.
                $app->setUserState('com_nextgcyber.edit.profile.id', $return);
                $model->checkout($return);

                // Redirect back to the edit screen.
                $this->setMessage(JText::_('COM_NEXTGCYBER_PROFILE_SAVE_SUCCESS'), 'success');
                $this->setRedirect(JRoute::_(($redirect = $app->getUserState('com_users.edit.profile.redirect')) ? $redirect : 'index.php?option=com_nextgcyber&view=profile&layout=edit&hidemainmenu=1', false));
                break;

            default:
                // Check in the profile.
                $userId = (int) $app->getUserState('com_nextgcyber.edit.profile.id');
                if ($userId) {
                    $model->checkin($userId);
                }

                // Clear the profile id from the session.
                $app->setUserState('com_nextgcyber.edit.profile.id', null);

                // Redirect to the list screen.
                $this->setMessage(JText::_('COM_NEXTGCYBER_PROFILE_SAVE_SUCCESS'), 'success');
                $this->setRedirect(JRoute::_(($redirect = $app->getUserState('com_users.edit.profile.redirect')) ? $redirect : 'index.php?option=com_nextgcyber&view=profile&layout=edit', false));
                break;
        }

        // Flush the data from the session.
        $app->setUserState('com_nextgcyber.edit.profile.data', null);
    }

    /**
     * Function that allows child controller access to model data after the data has been saved.
     *
     * @param   JModelLegacy  $model      The data model object.
     * @param   array         $validData  The validated data.
     *
     * @return  void
     * @since   1.0
     */
    protected function postSaveHook(JModelLegacy $model, $validData = array()) {
        $item = $model->getData();
    }

}

<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template.system
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

$xml = new SimpleXMLElement(JPATH_THEMES . '/kiwierp/templateDetails.xml', NULL, TRUE);
$version = (string) $xml->version;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();

$tpath = $this->baseurl . '/templates/' . $this->template;

// load sheets and scripts
JHtml::_('bootstrap.framework');

$doc->addStyleSheet($tpath . '/css/bootstrap.min.css');
// $doc->addStyleSheet($tpath . '/css/bootstrap-theme.min.css');
$doc->addStyleSheet($tpath . '/css/font-awesome.min.css');
$doc->addStyleSheet($tpath . '/css/template.css?v=' . $version);

require_once JPATH_ADMINISTRATOR . '/components/com_users/helpers/users.php';

$twofactormethods = UsersHelper::getTwoFactorMethods();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
    <head>
        <jdoc:include type="head" />
    </head>
    <body>
        <jdoc:include type="message" />
        <div id="frame" class="outline col-md-4 col-md-offset-4">
            <div class="text-center">
                <?php if ($app->getCfg('offline_image') && file_exists($app->getCfg('offline_image'))) : ?>
                    <img src="<?php echo $app->getCfg('offline_image'); ?>" alt="<?php echo htmlspecialchars($app->getCfg('sitename')); ?>" />
                <?php endif; ?>
                <h1>
                    <?php echo htmlspecialchars($app->getCfg('sitename')); ?>
                </h1>
                <?php if ($app->getCfg('display_offline_message', 1) == 1 && str_replace(' ', '', $app->getCfg('offline_message')) != '') : ?>
                    <p>
                        <?php echo $app->getCfg('offline_message'); ?>
                    </p>
                <?php elseif ($app->getCfg('display_offline_message', 1) == 2 && str_replace(' ', '', JText::_('JOFFLINE_MESSAGE')) != '') : ?>
                    <p>
                        <?php echo JText::_('JOFFLINE_MESSAGE'); ?>
                    </p>
                <?php endif; ?>
            </div>
            <form class="form-horizontal" action="<?php echo JRoute::_('index.php', true); ?>" method="post" id="form-login">
                <fieldset class="input">
                    <div id="form-login-username" class="form-group">
                        <label for="username" class="col-sm-2 control-label">
                            <?php echo JText::_('JGLOBAL_USERNAME') ?>
                        </label>
                        <div class="col-sm-10">
                            <input name="username"
                                   id="username"
                                   type="text"
                                   class="form-control"
                                   alt="<?php echo JText::_('JGLOBAL_USERNAME') ?>"
                                   size="18"
                                   />
                        </div>
                    </div>
                    <div id="form-login-password" class="form-group">
                        <label for="passwd" class="col-sm-2 control-label">
                            <?php echo JText::_('JGLOBAL_PASSWORD') ?>
                        </label>
                        <div class="col-sm-10">
                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   size="18"
                                   alt="<?php echo JText::_('JGLOBAL_PASSWORD') ?>"
                                   id="passwd"
                                   />
                        </div>
                    </div>
                    <?php if (count($twofactormethods) > 1) : ?>
                        <div id="form-login-secretkey" class="form-group">
                            <label for="secretkey" class="col-sm-2 control-label">
                                <?php echo JText::_('JGLOBAL_SECRETKEY') ?>
                            </label>
                            <div class="col-sm-10">
                                <input type="text"
                                       name="secretkey"
                                       class="form-control"
                                       size="18"
                                       alt="<?php echo JText::_('JGLOBAL_SECRETKEY') ?>" id="secretkey"
                                       />
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
                        <div id="form-login-remember" class="col-sm-offset-2 col-sm-10">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"
                                           name="remember"
                                           value="yes"
                                           alt="<?php echo JText::_('JGLOBAL_REMEMBER_ME') ?>"
                                           id="remember"
                                           />
                                           <?php echo JText::_('JGLOBAL_REMEMBER_ME') ?>
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div id="submit-buton" class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" name="Submit" class="btn btn-default button login" value="<?php echo JText::_('JLOGIN') ?>" />
                        </div>
                    </div>

                    <input type="hidden" name="option" value="com_users" />
                    <input type="hidden" name="task" value="user.login" />
                    <input type="hidden" name="return" value="<?php echo base64_encode(JUri::base()) ?>" />
                    <?php echo JHtml::_('form.token'); ?>
                </fieldset>
            </form>
        </div>
    </body>
</html>
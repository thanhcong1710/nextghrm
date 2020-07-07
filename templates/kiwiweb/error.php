<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
$tpath = $this->baseurl . '/templates/' . $this->template;

$xml = new SimpleXMLElement(JPATH_THEMES . '/kiwiweb/templateDetails.xml', NULL, TRUE);
$version = (string) $xml->version;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$user = JFactory::getUser();
$this->language = $doc->language;
$this->direction = $doc->direction;

// Getting params from template
$params = $app->getTemplate(true)->params;

// Detecting Active Variables
$option = $app->input->getCmd('option', '');
$view = $app->input->getCmd('view', '');
$layout = $app->input->getCmd('layout', '');
$task = $app->input->getCmd('task', '');
$itemid = $app->input->getCmd('Itemid', '');
$sitename = $app->get('sitename');

if ($task == "edit" || $layout == "form") {
    $fullWidth = 1;
} else {
    $fullWidth = 0;
}


// load sheets and scripts
JHtml::_('bootstrap.framework', false);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title><?php echo $this->title; ?> <?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="<?php echo $tpath; ?>/css/bootstrap.min.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo $tpath; ?>/css/font-awesome.min.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo $tpath; ?>/css/template.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo $tpath; ?>/css/template.css" type="text/css" />
        <?php if ($app->get('debug_lang', '0') == '1' || $app->get('debug', '0') == '1') : ?>
            <link rel="stylesheet" href="<?php echo $this->baseurl; ?>/media/cms/css/debug.css" type="text/css" />
        <?php endif; ?>

        <link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
        <!--[if lt IE 9]>
                <script src="<?php echo $this->baseurl; ?>/media/jui/js/html5.js"></script>
        <![endif]-->
    </head>

    <body class="site <?php
    echo $option
    . ' view-' . $view
    . ($layout ? ' layout-' . $layout : ' no-layout')
    . ($task ? ' task-' . $task : ' no-task')
    . ($itemid ? ' itemid-' . $itemid : '')
    . ($params->get('fluidContainer') ? ' fluid' : '');
    ?>">

        <!-- Body -->
        <div class="body">

            <div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?>">

                <div class="row">
                    <div id="content" class="col-md-12">
                        <!-- Begin Content -->
                        <h1 class="page-header"><?php echo JText::_('JERROR_LAYOUT_PAGE_NOT_FOUND'); ?></h1>
                        <div class="well">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><?php echo JText::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST'); ?></strong></p>
                                    <p><?php echo JText::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?></p>
                                    <ul>
                                        <li><?php echo JText::_('JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE'); ?></li>
                                        <li><?php echo JText::_('JERROR_LAYOUT_MIS_TYPED_ADDRESS'); ?></li>
                                        <li><?php echo JText::_('JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING'); ?></li>
                                        <li><?php echo JText::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE'); ?></li>
                                    </ul>
                                </div>
                                <div class="col-md-6">

                                    <?php if (JModuleHelper::getModule('search')) : ?>
                                        <p><strong><?php echo JText::_('JERROR_LAYOUT_SEARCH'); ?></strong></p>
                                        <p><?php echo JText::_('JERROR_LAYOUT_SEARCH_PAGE'); ?></p>
                                        <?php echo $doc->getBuffer('module', 'search'); ?>
                                        <div class="clearfix"></div>
                                    <?php endif; ?>
                                    <p><?php echo JText::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?><br />
                                        <a href="<?php echo $this->baseurl; ?>/index.php"
                                           class="btn btn-default">
                                            <i class="fa fa-home"></i> <?php echo JText::_('JERROR_LAYOUT_HOME_PAGE'); ?>
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <hr />
                            <p><?php echo JText::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR'); ?></p>
                            <blockquote>
                                <span class="label label-default"><?php echo $this->error->getCode(); ?></span> <?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?>
                            </blockquote>
                        </div>
                        <!-- End Content -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <div class="footer">
            <div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?>">
                <hr />
                <?php echo $doc->getBuffer('modules', 'footer', array('style' => 'none')); ?>
                <p class="pull-right">
                    <a href="#top" id="back-top">
                        <?php echo JText::_('TPL_KIWI_WEB_BACKTOTOP'); ?>
                    </a>
                </p>
                <p>
                    &copy; <?php echo date('Y'); ?> <?php echo $sitename; ?>
                </p>
            </div>
        </div>
        <?php echo $doc->getBuffer('modules', 'debug', array('style' => 'none')); ?>
    </body>
</html>

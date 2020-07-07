<?php
/**
 * @author		NextG-ERP
 * @copyright           Copyright © 2015 NextG-ERP. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version		1.0.0
 * @see			http://nextgerp.com
 */
defined('_JEXEC') or die;
require_once (JPATH_ROOT . '/templates/' . $this->template . "/tools.php");
if (strtolower($this->language) == 'vi-vn') {
        $fb_lang = 'vi_VN';
        $gplus_lang = 'vi';
} else {
        $fb_lang = 'en_US';
        $gplus_lang = 'en-US';
}
$root_page = JUri::root();
$current_url = JURI::getInstance();
$current_title = JFactory::getDocument()->getTitle();
$current_desc = JFactory::getDocument()->getDescription();
$app = JFactory::getApplication();
$path = JURI::base(true) . '/templates/' . $app->getTemplate() . '/';
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns/fb#" xmlns:fb="http://www.facebook.com/2008/fbml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
        <head>
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <script src='https://www.google.com/recaptcha/api.js'></script>
                <jdoc:include type="head" />
                <meta property="og:image" content="<?php echo $tpath . '/nextgweb-200x200.jpg'; ?>" />
                <meta property="og:url" content="<?php echo $current_url; ?>" />
                <meta property="og:title" content="<?php echo $current_title; ?>" />
                <meta property="og:description" content="<?php echo $current_desc; ?>" />
                <link rel="shortcut icon" type="image/png" href="<?php echo $tpath; ?>/favicon.png" />
                <link rel="canonical" href="<?php echo JUri::getInstance(); ?>" />
                <!--[if lt IE 9]>
                        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
                <![endif]-->
                <?php
                if (!isLocal()) {
                        require_once("analyticstracking.php");
                        echo '<script src="' . $path . 'fbtracking.js" type="text/script"></script>';
                }
                ?>
        </head>
        <body>
                <div id="wrapper">
                        <div class="section site-header">
                                <div class="container">
                                        <div class="row">
                                                <div class="top-menu">
                                                        <?php if (isset($posLists['main-login'])): ?>
                                                                <jdoc:include type="modules" name="main-login" style="none" />
                                                        <?php endif; ?>
                                                </div>
                                                <div class="main-menu hidden-xs">
                                                        <div class="menu-left">
                                                                <jdoc:include type="modules" name="top-left-menu" style="none" />
                                                        </div>
                                                        <div class="logo"><a title="NextG-WEB" href="<?php echo $root_page; ?>"><?php echo $logo; ?></a></div>
                                                        <div class="menu-right">
                                                                <jdoc:include type="modules" name="top-right-menu" style="none" />
                                                        </div>
                                                </div>
                                                <?php if (isset($posLists['phone-menu'])): ?>
                                                        <div class="visible-xs">
                                                                <jdoc:include type="modules" name="phone-menu" style="none" />
                                                        </div>
                                                <?php endif; ?>
                                        </div>
                                </div>
                        </div>
                        <?php if (isset($posLists['home-slider'])): ?>
                                <div class="section site-slider">
                                        <jdoc:include type="modules" name="home-slider" style="none" />
                                </div>
                        <?php endif; ?>

                        <?php if (isset($posLists['section'])): ?>
                                <jdoc:include type="modules" name="section" style="section" />
                        <?php endif; ?>

                        <div class="section site-content">
                                <div class="container">
                                        <jdoc:include type="modules" name="breadcrumbs" style="none" />
                                        <?php if (isset($posLists['site-main']['left']) || isset($posLists['site-main']['right'])): ?>
                                                <div class="row">
                                                <?php endif; ?>
                                                <?php if (isset($posLists['site-main']['left'])): ?>
                                                        <div class="col-md-3">
                                                                <div class="left-section">
                                                                        <jdoc:include type="modules" name="left" style="well" />
                                                                </div>

                                                        </div>
                                                <?php endif; ?>

                                                <?php if ($centerWidth != 12): ?>
                                                        <div class="<?php echo 'col-md-' . $centerWidth; ?>">
                                                        <?php endif; ?>

                                                        <?php showQueuedMsgs($danger_msg); ?>
                                                        <?php showQueuedMsgs($warning_msg); ?>
                                                        <?php showQueuedMsgs($info_msg); ?>
                                                        <?php showQueuedMsgs($success_msg); ?>
                                                        <jdoc:include type="modules" name="main-top" style="none" />
                                                        <jdoc:include type="component" />
                                                        <jdoc:include type="modules" name="main-bottom" style="none" />
                                                        <?php if ($centerWidth != 12): ?>
                                                        </div>
                                                <?php endif; ?>

                                                <?php if (isset($posLists['site-main']['right'])): ?>
                                                        <?php if (isset($posLists['site-main']['right']['right-1'])): ?>
                                                                <div class="col-md-<?php echo $rightWidth1 ?>">
                                                                        <div class="right-section">
                                                                                <jdoc:include type="modules" name="right-1" style="default" />
                                                                        </div>
                                                                </div>
                                                        <?php endif; ?>

                                                        <?php if (isset($posLists['site-main']['right']['right-2'])): ?>
                                                                <div class="col-md-<?php echo $rightWidth2 ?>">
                                                                        <div class="right-section">
                                                                                <jdoc:include type="modules" name="right-2" style="default" />
                                                                        </div>

                                                                </div>
                                                        <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if (isset($posLists['site-main']['left']) || isset($posLists['site-main']['right'])): ?>
                                                </div>
                                        <?php endif; ?>
                                </div>
                        </div>

                        <?php if (isset($posLists['section2'])): ?>
                                <jdoc:include type="modules" name="section2" style="section" />
                        <?php endif; ?>
                        <div class="section site-footer">
                                <div class="footer-main">
                                        <div class="container">
                                                <?php if (isset($posLists['bottom']['bottom-1'])): ?>
                                                        <div class="row">
                                                                <jdoc:include type="modules" name="bottom-1" style="none" />
                                                        </div>
                                                <?php endif; ?>

                                                <?php if (isset($posLists['bottom']['bottom-2']) || isset($posLists['bottom']['bottom-3'])): ?>
                                                        <div class="row">
                                                                <?php if (isset($posLists['bottom']['bottom-2'])): ?>
                                                                        <div class="col-md-<?php echo $bottom2_width; ?>">
                                                                                <div class="footer-left">
                                                                                        <jdoc:include type="modules" name="bottom-2" style="none" />
                                                                                </div>
                                                                        </div>
                                                                <?php endif; ?>

                                                                <?php if (isset($posLists['bottom']['bottom-3'])): ?>
                                                                        <div class="col-md-<?php echo $bottom3_width; ?>">
                                                                                <div class="footer-right">
                                                                                        <jdoc:include type="modules" name="bottom-3" style="none" />
                                                                                </div>
                                                                        </div>
                                                                <?php endif; ?>
                                                        </div>
                                                <?php endif; ?>

                                                <?php if (isset($posLists['footer'])): ?>
                                                        <div class="footer-bottom hidden-sm hidden-xs">
                                                                <div class="row">
                                                                        <?php if (isset($posLists['footer']['footer-nav'])): ?>
                                                                                <div class="col-md-<?php echo $footer_width; ?>">
                                                                                        <jdoc:include type="modules" name="footer-nav" style="none" />
                                                                                </div>
                                                                        <?php endif; ?>

                                                                        <?php if (isset($posLists['footer']['footer-social'])): ?>
                                                                                <div class="col-md-<?php echo $footer_width; ?>">
                                                                                        <jdoc:include type="modules" name="footer-social" style="none" />
                                                                                </div>
                                                                        <?php endif; ?>
                                                                </div>
                                                        </div>
                                                <?php endif; ?>
                                        </div>
                                </div>
                        </div>
                        <?php if (isset($posLists['footer-copy-right'])): ?>
                                <div class="section site-footer-2">
                                        <div class="container">
                                                <jdoc:include type="modules" name="footer-copy-right" style="none" />
                                        </div>
                                </div>
                        <?php endif; ?>

                        <jdoc:include type="modules" name="debug" />
                </div>
        </body>
</html>
<?php
$search = array('mootools', 'caption.js');
// remove the js files
foreach ($this->_scripts as $key => $script) {
        foreach ($search as $findme) {
                if (stristr($key, $findme) !== false) {
                        unset($this->_scripts[$key]);
                }
        }
}
$scriptString = "new JCaption('img.caption');";
$this->_script = str_replace($scriptString, '', $this->_script);
?>
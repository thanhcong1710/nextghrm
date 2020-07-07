<?php
defined('_JEXEC') or die;
$xml = new SimpleXMLElement(JPATH_THEMES . '/kiwierp/templateDetails.xml', NULL, TRUE);
$version = (string) $xml->version;

// variables
$app = JFactory::getApplication();
$jinput = $app->input;
$doc = JFactory::getDocument();
$option = $jinput->get('option', '');
$view = $jinput->get('view', '');
$layout = $jinput->get('layout', '');
$task = $jinput->get('task', '');
$itemid = $jinput->get('Itemid', '');
$msgqueue = $app->getMessageQueue();
$danger_msg = [];
$info_msg = [];
$warning_msg = [];
$success_msg = [];
if (count($msgqueue) > 0) {
    foreach ($msgqueue as $val) {
        switch ($val['type']) {
            case 'error':
                $val['type'] = 'danger';
                $danger_msg[] = $val;
                break;

            case 'warning':
                $warning_msg[] = $val;
                break;
            case 'info':
                $info_msg[] = $val;
                break;
            case 'notice':
                $val['type'] = 'info';
                $info_msg[] = $val;
                break;
            default:
                $val['type'] = 'success';
                $success_msg[] = $val;
                break;
        }
    }
    unset($val);
    unset($msgqueue);
}

/**
 *
 * @param type $msg
 * @return type
 */
function getMsgHeader($msg) {
    switch ($msg['type']) {
        case 'danger':
            $msg_header = JText::_('ERROR');
            break;
        case 'warning':
            $msg_header = JText::_('WARNING');
            break;
        case ('info' || 'notice'):
            $msg_header = JText::_('NOTICE');
            break;

        default:
            $msg_header = JText::_('MESSAGE');
            break;
    }
    return $msg_header;
}

function isLocal() {
    $whitelist = array(
        '127.0.0.1',
        '::1'
    );
    if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
        return false;
    }
    return true;
}

/**
 *
 * @param type $messages
 * @return string
 */
function showQueuedMsgs($messages) {
    if (is_array($messages)) {
        $msg_count = count($messages);
        if ($msg_count <= 0) {
            return '';
        } elseif ($msg_count == 1) {
            ?>
            <div class="alert alert-<?php echo $messages[0]['type']; ?> alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><?php echo getMsgHeader($messages[0]); ?>!</h4>
                <p><?php echo $messages[0]['message']; ?></p>
            </div>
            <?php
        } else {
            ?>
            <div class="alert alert-<?php echo $messages[0]['type']; ?> alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><?php echo getMsgHeader($messages[0]); ?>!</h4>
                <?php foreach ($messages as $message): ?>
                    <p><?php echo $message['message']; ?></p>
                <?php endforeach; ?>
            </div>
            <?php
        }
    } return '';
}

$isDefaultPage = FALSE;
$menu = $app->getMenu();
$lang = JFactory::getLanguage();

if ($menu->getActive() == $menu->getDefault($lang->getTag())) {
    $isDefaultPage = TRUE;
}

$logo = "";
if ($this->params->get('logo')) {
    $logo = '<img class="erp-logo" src="' . JURI::root() . $this->params->get('logo') . '" alt="NextG-ERP" />';
}

$tpath = $this->baseurl . '/templates/' . $this->template;

$this->setGenerator(null);

// load sheets and scripts
JHtml::_('bootstrap.framework');
JHtml::_('bootstrap.tooltip');
$doc->addStyleSheet('https://fonts.googleapis.com/css?family=Open+Sans');
$doc->addStyleSheet('https://fonts.googleapis.com/css?family=Roboto');
if ($this->params->get('maxcdn_bootstrap')) {
    $doc->addStyleSheet('//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css');
} else {
    $doc->addStyleSheet($tpath . '/css/bootstrap.min.css');
}

if ($this->params->get('enable_bootstraptheme')) {
    $doc->addStyleSheet($tpath . '/css/bootstrap-theme.min.css');
}

if ($this->params->get('maxcdn_bootstrap')) {
    $doc->addStyleSheet('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
} else {
    $doc->addStyleSheet($tpath . '/css/font-awesome.min.css');
}

$doc->addStyleSheet($tpath . '/css/template.css?v=' . $version);
$doc->addScript($tpath . '/js/video.js');
$doc->addScript($tpath . '/js/sl.js');
$posLists = [
    'top-menu' => [
        'left' => $this->countModules('top-left-menu'),
        'right' => $this->countModules('top-right-menu'),
    ],
    'top' => [
        'top-1' => $this->countModules('top-1'),
        'top-2' => $this->countModules('top-2'),
        'top-3' => $this->countModules('top-3'),
    ],
    'phone-menu' => $this->countModules('phone-menu'),
    'slider' => [
        'home-slider' => $this->countModules('home-slider'),
        'home-menu' => $this->countModules('home-menu'),
        'home-login' => $this->countModules('home-login'),
    ],
    'user' => [
        'user-1' => $this->countModules('user-1'),
        'user-2' => $this->countModules('user-2'),
        'user-3' => $this->countModules('user-3'),
        'user-4' => $this->countModules('user-4'),
    ],
    'main-menu' => $this->countModules('main-menu'),
    'main-login' => $this->countModules('main-login'),
    'section-top' => $this->countModules('section-top'),
    'section' => $this->countModules('section'),
    'site-main' => [
        'left' => $this->countModules('left'),
        'right' => [
            'right-1' => $this->countModules('right-1'),
            'right-2' => $this->countModules('right-2'),
        ],
        'main-top' => $this->countModules('main-top'),
        'main-bottom' => $this->countModules('main-bottom')
    ],
    'section2' => $this->countModules('section2'),
    'bottom' => [
        'bottom-1' => $this->countModules('bottom-1'),
        'bottom-2' => $this->countModules('bottom-2'),
        'bottom-3' => $this->countModules('bottom-3'),
    ],
    'footer-extra' => [
        'footer-extra-1' => $this->countModules('footer-extra-1'),
        'footer-extra-2' => $this->countModules('footer-extra-2'),
        'footer-extra-3' => $this->countModules('footer-extra-3'),
        'footer-extra-4' => $this->countModules('footer-extra-4'),
    ],
    'footer' => [
        'footer-nav' => $this->countModules('footer-nav'),
        'footer-social' => $this->countModules('footer-social'),
    ],
    'footer-copy-right' => $this->countModules('footer-copy-right'),
];

cleanPos($posLists);

// Calculate width for top module positions
$count_top = isset($posLists['top']) ? count($posLists['top']) : 0;
if ($count_top == 1) {
    $top_item_width = 12;
} elseif ($count_top == 2) {
    $top_item_width = 4;
} else {
    $top_item_width = 3;
}
$top_width = 12;

// Calculate width for user postions
$count_user = isset($posLists['user']) ? count($posLists['user']) : 0;
$user_width = $count_user ? round(12 / $count_user) : 0;

// Calculate width for bottom postions
$count_footer_extra = isset($posLists['footer-extra']) ? count($posLists['footer-extra']) : 0;
$footer_extra_width = $count_footer_extra ? round(12 / $count_footer_extra) : 0;

// Calculate width for site-main positions
$count_ma_main = isset($posLists['site-main']) ? count($posLists['site-main']) : 0;

$leftWidth = (isset($posLists['site-main']['left'])) ? 3 : 0;
$rightWidth1 = (isset($posLists['site-main']['right']['right-1'])) ? 3 : 0;
$rightWidth2 = (isset($posLists['site-main']['right']['right-2'])) ? 2 : 0;
$rightWidth = $rightWidth1 + $rightWidth2;
$centerWidth = 12 - $leftWidth - $rightWidth;

// Calculate width for bottom postions
$bottom2_width = (isset($posLists['bottom']['bottom-2'])) ? 9 : 0;
$bottom3_width = 12 - $bottom2_width;

// Calculate width for user postions
$count_footer = isset($posLists['footer']) ? count($posLists['footer']) : 0;
$footer_width = $count_footer ? round(12 / $count_footer) : 0;

/**
 * Method to unset all the empty element of the $posList array
 *
 * @param array $posList
 */
function cleanPos(&$posList) {
    foreach ($posList as $key => &$pos) {
        // if array and not empty, execute recursive
        if (is_array($pos) && !empty($pos)) {
            cleanPos($pos);
        }

        // unset empty pos
        if (empty($pos)) {
            unset($posList[$key]);
        }
    }
}

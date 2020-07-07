<?php
/*-------------------------------------------------------------------------
# com_layer_slider - com_layer_slider
# -------------------------------------------------------------------------
# @ author    John Gera, George Krupa, Janos Biro
# @ copyright Copyright (C) 2014 Offlajn.com  All Rights Reserved.
# @ license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @ website   http://www.offlajn.com
-------------------------------------------------------------------------*/
$revision = '5.1.1.048';
?><?php
// No direct access
defined('_JEXEC') or die;

/**
 * Layer_slider helper.
 */
 

$document = JFactory::getDocument();
//$document->addStyleSheet(JURI::base() . '/components/com_layer_slider/assets/css/jquery-ui-1.10.4.custom.min.css');
$document->addStyleSheet(JURI::root() . '/components/com_layer_slider/base/static/css/dashicons.css');
$document->addStyleSheet(JURI::base() . '/components/com_layer_slider/assets/css/wp_specs.css');
$document->addStyleSheet(JURI::root() . '/components/com_layer_slider/base/static/css/global.css');
$document->addStyleSheet(JURI::root() . '/components/com_layer_slider/base/static/css/admin.css');
$document->addStyleSheet(JURI::root() . '/components/com_layer_slider/base/static/css/admin_new.css');
$document->addStyleSheet(JURI::root() . '/components/com_layer_slider/base/static/css/layerslider.css');
$document->addStyleSheet(JURI::root() . '/components/com_layer_slider/base/static/css/layerslider.transitiongallery.css');
$document->addStyleSheet(JURI::root() . '/components/com_layer_slider/base/static/js/minicolors/jquery.minicolors.css');

if($GLOBALS['j25']) {
  $document->addStyleSheet(JURI::base() . '/components/com_layer_slider/assets/css/layer_slider_25.css');
  $document->addScript('//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js');
} else JHtml::_('jquery.framework');

JHTML::_('behavior.modal');

$url = JURI::root(true);
if ($url !="/") $url.="/"; 
$document->addScriptDeclaration("joomla_base_url='".$url."';");
//$document->addScriptDeclaration("function jInsertEditorText(tag, name){jQuery('[name='+name+']').val(joomla_base_url+jQuery(tag).attr('src')); jQuery('[name='+name+']').parent().find('.ls-image img').attr('src', joomla_base_url+jQuery(tag).attr('src')); LayerSlider.willGeneratePreview( jQuery('.ls-box.active').index() );}; ajaxsaveurl = 'index.php?option=com_layer_slider&view=slider&task=save_slider';");

$document->addScript(JURI::base() . '/components/com_layer_slider/assets/js/jquery-ui-1.10.4.custom.min.js');
$document->addScript(JURI::base() . '/components/com_layer_slider/assets/js/wp_specs.js');

$document->addScript(JURI::root() . '/components/com_layer_slider/base/static/js/admin.js');
$document->addScript(JURI::root() . '/components/com_layer_slider/base/static/js/builder.js');
$document->addScriptDeclaration("jQuery.ui.slider.prototype.widgetEventPrefix = 'slider';");
$document->addScript(JURI::root() . '/components/com_layer_slider/base/static/js/layerslider.kreaturamedia.js');
$document->addScript(JURI::root() . '/components/com_layer_slider/base/static/js/layerslider.transitions.js');
$document->addScript('https://cdnjs.cloudflare.com/ajax/libs/gsap/1.11.2/TweenMax.min.js');
$document->addScript(JURI::root() . '/components/com_layer_slider/base/static/js/layerslider.transition.gallery.js');
$document->addScript(JURI::root() . '/components/com_layer_slider/base/static/js/minicolors/jquery.minicolors.js');


// 3rd-party: CodeMirror
$document->addStyleSheet(JURI::root() . '/components/com_layer_slider/base/static/codemirror/lib/codemirror.css');
$document->addStyleSheet(JURI::root() . '/components/com_layer_slider/base/static/codemirror/theme/solarized.mod.css');

$document->addScript(JURI::root() . '/components/com_layer_slider/base/static/codemirror/lib/codemirror.js');
$document->addScript(JURI::root() . '/components/com_layer_slider/base/static/codemirror/mode/css/css.js');
$document->addScript(JURI::root() . '/components/com_layer_slider/base/static/codemirror/mode/javascript/javascript.js');
$document->addScript(JURI::root() . '/components/com_layer_slider/base/static/codemirror/addon/fold/foldcode.js');
$document->addScript(JURI::root() . '/components/com_layer_slider/base/static/codemirror/addon/fold/foldgutter.js');
$document->addScript(JURI::root() . '/components/com_layer_slider/base/static/codemirror/addon/fold/brace-fold.js');
$document->addScript(JURI::root() . '/components/com_layer_slider/base/static/codemirror/addon/selection/active-line.js');


if (!defined('LS_ROOT_PATH')) define("LS_ROOT_PATH", JPATH_SITE."/components/com_layer_slider/base/");
if (!defined('LS_ROOT_URL')) define("LS_ROOT_URL", JURI::root()."components/com_layer_slider/base/" );

require_once JPATH_SITE.'/components/com_layer_slider/base/wp/hooks.php';
require_once JPATH_SITE.'/components/com_layer_slider/base/classes/class.ls.sliders.php';

if(!defined('NL')) { define("NL", "\r\n"); }
if(!defined('TAB')) { define("TAB", "\t"); }


  function get_option( $option, $default = false){
    // Get a db connection.
    $db = JFactory::getDbo();
    // Create a new query object.
    $query = $db->getQuery(true);
     
    // Select all records from the user profile table where key begins with "custom.".
    // Order it by the ordering field.
    $query->select("option_value");
    $query->from($db->quoteName('#__layerslider_options'));
    $query->where($db->quoteName('option_name')."='".$option."'");
    // Reset the query using our newly populated query object.
    $db->setQuery($query);
     
    // Load the results as a list of stdClass objects (see later for more options on retrieving data).
    $settings = $db->loadRow();
    
    if($settings)
      $default = json_decode($settings[0],true);
      
    return $default;
  }


  function update_option($option, $value){
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);

 
    $query->select('*');
    $query->from($db->quoteName('#__layerslider_options'));
    $query->where($db->quoteName('option_name')." = '".$option."'");
    $db->setQuery($query);
    $row = $db->loadRow();

    $datas = new stdClass();
    $datas->option_name = $option;
    $datas->option_value = json_encode($value);

    if(!$row){
      // Create an object.
      $result = JFactory::getDbo()->insertObject('#__layerslider_options', $datas);
    }else{
    	// Update obejct
      $result = JFactory::getDbo()->updateObject('#__layerslider_options', $datas, 'option_name');
    }
    
    return $value;
  }

  function ls_load_google_fonts() {
  
  	// Get font list
  	$fonts = get_option('ls-google-fonts', array());
  
  	// Check fonts if any
  	if(!empty($fonts) && is_array($fonts)) {
  		$lsFonts = array();
  		foreach($fonts as $item) {
  			if(!$item['admin']) {
  				$lsFonts[] = $item['param'];
  			} else {
  				$lsFonts[] = $item['param'];
  			}
  		}
  		$lsFonts = implode('|', $lsFonts);
  		$protocol = 'https';
  		$query_args = array(
  			'family' => $lsFonts,
  			'subset' => 'latin,latin-ext',
  		);
  
      $doc = JFactory::getDocument();
      $doc->addCustomTag( '<link id="ls-google-fonts-css" media="all" type="text/css" href="http://fonts.googleapis.com/css?family='.$query_args['family'].'&subset='.$query_args['subset'].'" rel="stylesheet">' );

  	}
  }  
  
  ls_load_google_fonts();
  
  function __($a, $b){
    return $a;
  }

  function _e($a, $b){
    echo $a;
  }

  function wp_nonce_url( $actionurl, $action = -1, $name = '_wpnonce' ) {
  	$actionurl = str_replace( '&amp;', '&', $actionurl );
  	return $actionurl;
  }
  
  function wp_nonce_field( $action = -1, $name = "_wpnonce", $referer = true , $echo = true ) {
  	$name = htmlspecialchars( $name );
  	$nonce_field = '<input type="hidden" id="' . $name . '" name="' . $name . '" value="' . $action  . '" />';
  
  	if ( $echo )
  		echo $nonce_field;
  
  	return $nonce_field;
  }

  define( 'MINUTE_IN_SECONDS', 60 );
	define( 'HOUR_IN_SECONDS',   60 * MINUTE_IN_SECONDS );
	define( 'DAY_IN_SECONDS',    24 * HOUR_IN_SECONDS   );
	define( 'WEEK_IN_SECONDS',    7 * DAY_IN_SECONDS    );
	define( 'YEAR_IN_SECONDS',  365 * DAY_IN_SECONDS    );
	
	function _n( $single, $plural, $number, $domain = 'default' ) {

  	return $number>1?$plural:$single;
  }
     
  function human_time_diff( $from, $to = '' ) {
	if ( empty( $to ) )
		$to = time();

	$diff = (int) abs( $to - $from );

	if ( $diff < HOUR_IN_SECONDS ) {
		$mins = round( $diff / MINUTE_IN_SECONDS );
		if ( $mins <= 1 )
			$mins = 1;
		/* translators: min=minute */
		$since = sprintf( _n( '%s min', '%s mins', $mins ), $mins );
	} elseif ( $diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS ) {
		$hours = round( $diff / HOUR_IN_SECONDS );
		if ( $hours <= 1 )
			$hours = 1;
		$since = sprintf( _n( '%s hour', '%s hours', $hours ), $hours );
	} elseif ( $diff < WEEK_IN_SECONDS && $diff >= DAY_IN_SECONDS ) {
		$days = round( $diff / DAY_IN_SECONDS );
		if ( $days <= 1 )
			$days = 1;
		$since = sprintf( _n( '%s day', '%s days', $days ), $days );
	} elseif ( $diff < 30 * DAY_IN_SECONDS && $diff >= WEEK_IN_SECONDS ) {
		$weeks = round( $diff / WEEK_IN_SECONDS );
		if ( $weeks <= 1 )
			$weeks = 1;
		$since = sprintf( _n( '%s week', '%s weeks', $weeks ), $weeks );
	} elseif ( $diff < YEAR_IN_SECONDS && $diff >= 30 * DAY_IN_SECONDS ) {
		$months = round( $diff / ( 30 * DAY_IN_SECONDS ) );
		if ( $months <= 1 )
			$months = 1;
		$since = sprintf( _n( '%s month', '%s months', $months ), $months );
	} elseif ( $diff >= YEAR_IN_SECONDS ) {
		$years = round( $diff / YEAR_IN_SECONDS );
		if ( $years <= 1 )
			$years = 1;
		$since = sprintf( _n( '%s year', '%s years', $years ), $years );
	}

	return $since;
}


function is_multisite() {
	return false;
}


function get_currentuserinfo() {
	return false;
}
function get_user_meta() {
	return false;
}
function add_user_meta() {
	return false;
}

class ls_screen {
  static $tabs = array();
  var $base = '';
  function ls_screen($view) {
    switch($view) {
      case 'slider': $_GET['action'] = 'edit';
      case 'sliders': $this->base = 'layerslider'; break;
      case 'transitionbuilder': $this->base = 'ls-transition-builder'; break;
      case 'skineditor': $this->base = 'ls-skin-editor'; break;
      case 'customstyleseditor': $this->base = 'ls-style-editor'; break;
    }
  }
  function add_help_tab($tab) {
    self::$tabs[] = $tab;
  }
  static function addHelp() {
    require_once LS_ROOT_PATH.'wp/help.php'; ?>
    <div class="metabox-prefs" id="screen-meta">
      <div class="no-sidebar" id="contextual-help-wrap" style="display:none">
				<div id="contextual-help-back"></div>
				<div id="contextual-help-columns">
					<div class="contextual-help-tabs">
						<ul>
            <?php foreach (self::$tabs as $tab): ?>
              <li id="tab-link-<?php echo $tab['id'] ?>">
                <a href="#tab-panel-<?php echo $tab['id'] ?>"><?php echo $tab['title'] ?></a>
              </li>
            <?php endforeach ?>
            </ul>
          </div>
          <div class="contextual-help-tabs-wrap">
          <?php foreach (self::$tabs as $tab): ?>
            <div class="help-tab-content" id="tab-panel-<?php echo $tab['id'] ?>"><?php echo $tab['content'] ?></div>
          <?php endforeach ?>
          </div>
        </div>
      </div>
    </div>
    <div id="screen-meta-links">
  		<div class="screen-meta-toggle" id="contextual-help-link-wrap">
  			<a class="show-settings screen-meta-active" id="contextual-help-link" href="#contextual-help-wrap">Help</a>
  		</div>
    </div>
    <?php
  }
}
function add_filter($filter, $func) {
  switch ($filter) {
    case 'contextual_help':
      $screen = new ls_screen(JRequest::getCmd('view'));
      call_user_func($func, 0, 0, $screen);
      break;
  }
}
function has_filter() {
	return false;
}
function get_categories() {
	return false;
}
function get_tags() {
	return false;
}
function get_taxonomies() {
	return false;
}
function wp_upload_dir() {
	return array("basedir"=>JPATH_SITE."/images","baseurl"=>JURI::root()."images");
}

function get_allowed_mime_types() {
	return array("jpg|jpeg|jpe" => "image/jpeg", "gif" => "image/gif", "png" => "image/png", "bmp" => "image/bmp", "tif|tiff" => "image/tiff", "ico" => "image/x-icon", "asf|asx" => "video/x-ms-asf", "wmv" => "video/x-ms-wmv", "wmx" => "video/x-ms-wmx", "wm" => "video/x-ms-wm", "avi" => "video/avi", "divx" => "video/divx", "flv" => "video/x-flv", "mov|qt" => "video/quicktime", "mpeg|mpg|mpe" => "video/mpeg", "mp4|m4v" => "video/mp4", "ogv" => "video/ogg", "webm" => "video/webm", "mkv" => "video/x-matroska", "txt|asc|c|cc|h" => "text/plain", "csv" => "text/csv", "tsv" => "text/tab-separated-values", "ics" => "text/calendar", "rtx" => "text/richtext", "css" => "text/css", "htm|html" => "text/html", "mp3|m4a|m4b" => "audio/mpeg", "ra|ram" => "audio/x-realaudio", "wav" => "audio/wav", "ogg|oga" => "audio/ogg", "mid|midi" => "audio/midi", "wma" => "audio/x-ms-wma", "wax" => "audio/x-ms-wax", "mka" => "audio/x-matroska", "rtf" => "application/rtf", "js" => "application/javascript", "pdf" => "application/pdf", "class" => "application/java", "tar" => "application/x-tar", "zip" => "application/zip", "gz|gzip" => "application/x-gzip", "rar" => "application/rar", "7z" => "application/x-7z-compressed", "doc" => "application/msword", "pot|pps|ppt" => "application/vnd.ms-powerpoint", "wri" => "application/vnd.ms-write", "xla|xls|xlt|xlw" => "application/vnd.ms-excel", "mdb" => "application/vnd.ms-access", "mpp" => "application/vnd.ms-project", "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "docm" => "application/vnd.ms-word.document.macroEnabled.12", "dotx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.template", "dotm" => "application/vnd.ms-word.template.macroEnabled.12", "xlsx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "xlsm" => "application/vnd.ms-excel.sheet.macroEnabled.12", "xlsb" => "application/vnd.ms-excel.sheet.binary.macroEnabled.12", "xltx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.template", "xltm" => "application/vnd.ms-excel.template.macroEnabled.12", "xlam" => "application/vnd.ms-excel.addin.macroEnabled.12", "pptx" => "application/vnd.openxmlformats-officedocument.presentationml.presentation", "pptm" => "application/vnd.ms-powerpoint.presentation.macroEnabled.12", "ppsx" => "application/vnd.openxmlformats-officedocument.presentationml.slideshow", "ppsm" => "application/vnd.ms-powerpoint.slideshow.macroEnabled.12", "potx" => "application/vnd.openxmlformats-officedocument.presentationml.template", "potm" => "application/vnd.ms-powerpoint.template.macroEnabled.12", "ppam" => "application/vnd.ms-powerpoint.addin.macroEnabled.12", "sldx" => "application/vnd.openxmlformats-officedocument.presentationml.slide", "sldm" => "application/vnd.ms-powerpoint.slide.macroEnabled.12", "onetoc|onetoc2|onetmp|onepkg" => "application/onenote", "odt" => "application/vnd.oasis.opendocument.text", "odp" => "application/vnd.oasis.opendocument.presentation", "ods" => "application/vnd.oasis.opendocument.spreadsheet", "odg" => "application/vnd.oasis.opendocument.graphics", "odc" => "application/vnd.oasis.opendocument.chart", "odb" => "application/vnd.oasis.opendocument.database", "odf" => "application/vnd.oasis.opendocument.formula", "wp|wpd" => "application/wordperfect", "key" => "application/vnd.apple.keynote", "numbers" => "application/vnd.apple.numbers", "pages" => "application/vnd.apple.pages" );
}

function get_home_path() {
  return preg_replace("/".addcslashes(JURI::root(true),'/')."\/?$/i", "", str_replace('\\','/',JPATH_SITE) );
}

function layerslider_builder_convert_numbers(&$item, $key) {
	if(is_numeric($item)) {
		$item = (float) $item;
	}
}

/**
* Sanitizes a filename replacing whitespace with dashes
*
* Removes special characters that are illegal in filenames on certain
* operating systems and special characters requiring special escaping
* to manipulate at the command line. Replaces spaces and consecutive
* dashes with a single dash. Trim period, dash and underscore from beginning
* and end of filename.
*
* @since 2.1.0
*
* @param string $filename The filename to be sanitized
* @return string The sanitized filename
*/
function sanitize_file_name( $filename ) {
  $filename_raw = $filename;
  $special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", chr(0));
  $special_chars = apply_filters('sanitize_file_name_chars', $special_chars, $filename_raw);
  $filename = str_replace($special_chars, '', $filename);
  $filename = preg_replace('/[\s-]+/', '-', $filename);
  $filename = trim($filename, '.-_');
  
  return $filename;
}



class posts {
  function getPostTypes(){
    return false;
  }
}

function wp_check_filetype( $filename, $mimes = null ) {
	if ( empty($mimes) )
		$mimes = get_allowed_mime_types();
	$type = false;
	$ext = false;
	foreach ( $mimes as $ext_preg => $mime_match ) {
		$ext_preg = '!\.(' . $ext_preg . ')$!i';
		if ( preg_match( $ext_preg, $filename, $ext_matches ) ) {
			$type = $mime_match;
			$ext = $ext_matches[1];
			break;
		}
	}

	return compact( 'ext', 'type' );
}

global $LSC;

$LSC = new stdClass();
$LSC->posts = new posts();

$GLOBALS['lsAutoUpdateBox'] = false;

//Version Joomla 3.+

$xml = JFactory::getXML(JPATH_ADMINISTRATOR .'/components/com_layer_slider/layer_slider.xml');
$version = (string)$xml->version;
define( 'LS_PLUGIN_VERSION', $version );


// User resources
$uploads = wp_upload_dir();
if(file_exists($uploads['basedir'].'/layerslider.custom.transitions.js')) {
//	$document->addScript($uploads['baseurl'].'/layerslider.custom.transitions.js');
  $document->addCustomTag( '<script id="ls-user-transitions" src="'.$uploads['baseurl'].'/layerslider.custom.transitions.js" type="text/javascript" ></script>' );
}

if(file_exists($uploads['basedir'].'/layerslider.custom.css')) {
//	wp_enqueue_style('ls-user-css', $uploads['baseurl'].'/layerslider.custom.css', false, LS_PLUGIN_VERSION );
  $document->addCustomTag( '<link id="ls-user-css" href="'.$uploads['baseurl'].'/layerslider.custom.css" type="text/css" rel="stylesheet" ></link>' );
}
 

 
class Layer_sliderHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_layer_slider';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}

function base64_url_encode($input) {
 return strtr(base64_encode($input), '+/=', '-_,');
}

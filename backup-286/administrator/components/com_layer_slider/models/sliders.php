<?php
/*-------------------------------------------------------------------------
# com_layer_slider - com_layer_slider
# -------------------------------------------------------------------------
# @ author    John Gera, George Krupa, Janos Biro
# @ copyright Copyright (C) 2014 Offlajn.com  All Rights Reserved.
# @ license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @ website   http://www.offlajn.com
-------------------------------------------------------------------------*/
?><?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

if (!class_exists('ZipArchive')) {
  jimport( 'joomla.filesystem.archive' );
  class ZipArchive {

    const CREATE = 1;
    const OVERWRITE = 8;

    private $archive;
    private $mode;
    private $files;

    public function open($archive, $mode = 0) {
      $this->archive = $archive;
      $this->mode = $mode;
      $this->files = array();
      if (!$mode) return file_exists($archive);
    }

    public function extractTo($tmpDir) {
      if (!$this->mode) {
        $zip = JArchive::getAdapter('zip');
        return $zip->extract($this->archive, $tmpDir);
      }
    }

    public function addFromString($file, $data) {
      $this->files[] = array('name' => $file, 'data' => $data);
    }

    public function addFile($filepath, $file) {
      $this->files[] = array('name' => $file, 'data' => file_get_contents($filepath));
    }

    public function close() {
      if ($this->mode) {
        $zip = JArchive::getAdapter('zip');
        $zip->create($this->archive, $this->files);
      }
    }

  }
}

/**
 * Methods supporting a list of Layer_slider records.
 */
class Layer_sliderModelSliders extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     */
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);

        

        // Load the parameters.
        $params = JComponentHelper::getParams('com_layer_slider');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.id', 'asc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param	string		$id	A prefix for the store id.
     * @return	string		A store id.
     * @since	1.6
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id.= ':' . $this->getState('filter.search');
        $id.= ':' . $this->getState('filter.state');

        return parent::getStoreId($id);
    }


	public function importSampleSlider($slider){
    include LS_ROOT_PATH.'/classes/class.ls.importutil.php';

    // Check reference
    if(!empty($_GET['slider']) && $_GET['slider'] == 'all') {
    	foreach(glob(LS_ROOT_PATH.'/demos/*') as $item) {
    		$item = basename($item);
    		if(substr($item, strrpos($item, '.')+1) == 'zip') {
    			$import = new LS_ImportUtil(LS_ROOT_PATH.'/demos/'.basename($item));
    		}
    	}
    } elseif(!empty($_GET['slider']) && is_string($_GET['slider'])) { 
    	if(file_exists(LS_ROOT_PATH.'/demos/'.basename($_GET['slider']))) {
    		$import = new LS_ImportUtil(LS_ROOT_PATH.'/demos/'.basename($_GET['slider']));
    	}
    }
    
  	header('Location: index.php?option=com_layer_slider');
    die();
  }

	public function importSlider(){
  	// Check export file if any
  	if(!is_uploaded_file($_FILES['import_file']['tmp_name'])) {
  		header('Location: '.$_SERVER['REQUEST_URI'].'&error=1&message=importSelectError');
  		die('No data received.');
  	}
  
  	include LS_ROOT_PATH.'/classes/class.ls.importutil.php';
  	$import = new LS_ImportUtil($_FILES['import_file']['tmp_name'], $_FILES['import_file']['name']);
  	
  	header('Location: index.php?option=com_layer_slider');
    die();
  }
  
  public function exportSlider(){
  
	if(isset($_POST['sliders'][0]) && $_POST['sliders'][0] == -1) {
		$sliders = LS_Sliders::find(array('limit' => 200));
	} elseif(!empty($_POST['sliders'])) {
		$sliders = LS_Sliders::find($_POST['sliders']);
	} else {
//		header('Location: admin.php?page=layerslider&error=1&message=exportSelectError');
		die('Invalid data received.');
	}
	
  if(class_exists('ZipArchive')) {
		include LS_ROOT_PATH.'/classes/class.ls.exportutil.php';
		$zip = new LS_ExportUtil;
	}

	foreach($sliders as $item) {

		// Slider settings array for fallback mode
		$data[] = $item['data'];

		// If ZipArchive is available
		if(class_exists('ZipArchive')) {

			// Add slider folder and settings.json
			$name = sanitize_file_name($item['name']);
			$zip->addSettings(json_encode($item['data']), $name);
			// Add images?
			if(isset($_POST['exportWithImages'])) {
				$images = $zip->getImagesForSlider($item['data']);
				$images = $zip->getFSPaths($images);
				$zip->addImage($images, $name);
			}
		}
	}
  	
	if(class_exists('ZipArchive')) {
		$zip->download();
	} else {
		$name = 'LayerSlider Export '.date('Y-m-d').' at '.date('H.i.s').'.json';
		header('Content-type: application/force-download');
		header('Content-Disposition: attachment; filename="'.str_replace(' ', '_', $name).'"');
		die(base64_encode(json_encode($data)));
	}
	
  }
  
  public function removeSlider($ids) {
    foreach ($ids as $id) {
    	// Check received data
    	if(empty($id)) { return false; }
    	// Remove the slider
    	LS_Sliders::remove( intval($id) );
    }

  	// Reload page
  	header('Location: index.php?option=com_layer_slider');
  	die();
  }
  
  public function deleteSlider($ids) {
  
    foreach ($ids as $id) {
    	// Check received data
    	if(empty($id)) { return false; }
    	// Remove the slider
    	LS_Sliders::delete( intval($id) );
    }

  	// Reload page
  	header('Location: index.php?option=com_layer_slider');
  	die();
  }
  
  public function restoreSlider($ids) {
  
    foreach ($ids as $id) {
    	// Check received data
    	if(empty($id)) { return false; }
    	// Remove the slider
    	LS_Sliders::restore( intval($id) );
    }

  	// Reload page
  	header('Location: index.php?option=com_layer_slider');
  	die();
  }

  public function mergeSliders($ids) {
		if($sliders = LS_Sliders::find($ids)) {
			foreach($sliders as $key => $item) {
				
				// Get IDs
				$ids[] = '#' . $item['id'];

				// Merge slides
				if($key === 0) { $data = $item['data']; }
				else { $data['layers'] = array_merge($data['layers'], $item['data']['layers']); }
			}

			// Save as new
			$name = 'Merged sliders of ' . implode(', ', $ids);
			$data['properties']['title'] = $name;
			LS_Sliders::add($name, $data);
		}

  	// Reload page
  	header('Location: index.php?option=com_layer_slider');
  	die();
  }
  
    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
  protected function getListQuery() {
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		return $query;
	}

	public function getItems() {
    $items = parent::getItems();
    return $items;
  }
  
  public function saveGoogleFonts($urlParams){
  	// Build object to save
  	$fonts = array();
  	if(isset($urlParams)) {
  		foreach($urlParams as $key => $val) {
  			if(!empty($val)) {
  				$fonts[] = array(
  					'param' => $val,
  					'admin' => isset($_POST['onlyOnAdmin'][$key]) ? true : false
  				);
  			}
  		}
  	}
  	update_option('ls-google-fonts', $fonts);
  	header('Location: index.php?option=com_layer_slider');

    return false;
  }
  
  public function saveAdvancedSetting($name,$data){
    update_option($name, $data);
  	header('Location: index.php?option=com_layer_slider');
  }
    

}

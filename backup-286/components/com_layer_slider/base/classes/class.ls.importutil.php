<?php
/*-------------------------------------------------------------------------
# com_layer_slider - com_layer_slider
# -------------------------------------------------------------------------
# @ author    John Gera, George Krupa, Janos Biro
# @ copyright Copyright (C) 2014 Offlajn.com  All Rights Reserved.
# @ license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @ website   http://www.offlajn.com
-------------------------------------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
?><?php 
class LS_ImportUtil {

	/**
	 * The managed ZipArchieve instance.
	 */
	private $zip;

	/**
	 * Target folders
	 */
	private $targetDir, $targetURL, $tmpDir;

	// Imported images
	private $imported = array();


	// Accepts $_FILES
	public function __construct($archive, $name = null) {

		if(empty($name)) {
			$name = $archive;
		}

		// TODO: check file extension to support old import method
		$type = wp_check_filetype(basename($name), array(
			'zip' => 'application/zip',
			'json' => 'application/json'
		));
		// Check for ZIP
		if(!empty($type['ext']) && $type['ext'] == 'zip') {
			if(class_exists('ZipArchive')) {

				$this->zip = new ZipArchive;
				
				if($this->zip->open($archive)) {
					if($this->unpack($archive)) {

						// Uploaded folders
						foreach(glob($this->tmpDir.'/*', GLOB_ONLYDIR) as $key => $dir) {

							$this->imported = array();
							$this->uploadMedia($dir);

							if(file_exists($dir.'/settings.json')) {
								$this->addSlider($dir.'/settings.json');
							}
						}

						// Finishing up
						$this->cleanup();
						return true;
					}

					// Close ZIP
					$this->zip->close();
				}
			} else {
				return false;
			}


		// Check for JSON
		} elseif(!empty($type['ext']) && $type['ext'] == 'json') {

			// Get decoded file data
			$data = base64_decode(file_get_contents($archive));

			// Parsing JSON or PHP object
			if(!$parsed = json_decode($data, true)) {
				$parsed = unserialize($data);
			}

			// Iterate over imported sliders
			if(is_array($parsed)) {

				// Import sliders
				foreach($parsed as $item) {

					// Fix for export issue in v4.6.4
					if(is_string($item)) { $item = json_decode($item, true); }

					LS_Sliders::add($item['properties']['title'], $item);
				}
			}
		}

		// Return false otherwise
		return false;
	}



	public function unpack($archive) {

		// Get uploads folder
		$uploads = wp_upload_dir();

		// Check if /uploads dir is writable
		if(is_writable($uploads['basedir'])) {
		
			// Get target folders
			$this->targetDir = $targetDir = $uploads['basedir'].'/layerslider';
			$root = JURI::root(true);
      if (!preg_match('/\/$/', $root)) $root.= '/';
			$this->targetURL = $root.'images/layerslider';
			$this->tmpDir = $tmpDir = $uploads['basedir'].'/layerslider/tmp';

			// Create necessary folders under /uploads
			if(!file_exists($targetDir)) { mkdir($targetDir, 0755); }
			if(!file_exists($targetDir)) { mkdir($targetDir, 0755); }

			// Unpack archive
			if($this->zip->extractTo($tmpDir)) {
				return true;
			}
		}

		return false;
	}




	public function uploadMedia($dir = null) {

		// Check provided data
		if(empty($dir) || !is_string($dir) || !file_exists($dir.'/uploads')) {
			return false;
		}

		// Create folder if it isn't exists already
		$targetDir = $this->targetDir . '/' . basename($dir);
		if(!file_exists($targetDir)) { mkdir($targetDir, 0755); }

		// Include image.php for media library upload
//		require_once(ABSPATH.'wp-admin/includes/image.php');

		// Iterate through directory
		foreach(glob($dir.'/uploads/*') as $filePath) {

			$fileName = sanitize_file_name(basename($filePath));
			
			$targetFile = $targetDir.'/'.$fileName;

			// Validate media
			$filetype = wp_check_filetype($fileName, null);
			
			if(!empty($filetype['ext']) && $filetype['ext'] != 'php') {
			
				// Move item to place
				rename($filePath, $targetFile);

				// Upload to media library

				$this->imported[$fileName] = array(
					
					'url' => $this->targetURL.'/'.basename($dir).'/'.$fileName
				);
			}
		}

		return true;
	}



	public function deleteDir($dirPath) {
		
		if(!is_dir($dirPath)) {
			return false;
		}
		if(substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		$files = glob($dirPath . '*', GLOB_MARK);
		foreach($files as $file) {
			if(is_dir($file)) {
				$this->deleteDir($file);
			} else {
				unlink($file);
			}
		}
		rmdir($dirPath);
	}



	public function addSlider($file) {

		// Get slider data and title
		$data = json_decode(file_get_contents($file), true);
		$title = $data['properties']['title'];
		
		// Slider settings
		if(!empty($data['properties']['backgroundimage'])) {
			$data['properties']['backgroundimage'] = $this->attachURLForImage(
				$data['properties']['backgroundimage']
			);
		}

		if(!empty($data['properties']['yourlogo'])) {
			$data['properties']['yourlogoId'] = '';
			$data['properties']['yourlogo'] = $this->attachURLForImage(
				$data['properties']['yourlogo']
			);
		}


		// Slides
		if(!empty($data['layers']) && is_array($data['layers'])) {
		foreach($data['layers'] as &$slide) {

			if(!empty($slide['properties']['background'])) {
				$slide['properties']['backgroundId'] = '';
				$slide['properties']['background'] = $this->attachURLForImage(
					$slide['properties']['background']
				);
			}

			if(!empty($slide['properties']['thumbnail'])) {
				$slide['properties']['thumbnailId'] = '';
				$slide['properties']['thumbnail'] = $this->attachURLForImage(
					$slide['properties']['thumbnail']
				);
			}

			// Layers
			if(!empty($slide['sublayers']) && is_array($slide['sublayers'])) {
			foreach($slide['sublayers'] as &$layer) {
					
				if(!empty($layer['image'])) {
					$layer['imageId'] = '';
					$layer['image'] = $this->attachURLForImage($layer['image']);
				}
			}}
		}}
		// Add slider
		LS_Sliders::add($title, $data);
	}



	public function attachURLForImage($file = '#') {

		if(isset($this->imported[basename($file)])) {
			return $this->imported[basename($file)]['url'];
		}

		return $file;
	}



	public function cleanup() {
		$this->deleteDir($this->tmpDir);
	}
}
?>
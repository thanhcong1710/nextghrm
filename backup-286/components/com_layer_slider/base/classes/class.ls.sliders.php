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

class LS_Sliders {

	/**
	 * @var array $results Array containing the result of the last DB query
	 * @access public
	 */
	public static $results = array();



	/**
	 * @var int $count Count of found sliders in the last DB query
	 * @access public
	 */
	public static $count = null;



	/**
	 * Private constructor to prevent instantiate static class
	 *
	 * @since 5.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {

	}



	/**
	 * Returns the count of found sliders in the last DB query
	 *
	 * @since 5.0.0
	 * @access public
	 * @return int Count of found sliders in the last DB query
	 */
	public static function count() {
		return self::$count;
	}



	/**
	 * Find sliders with the provided filters
	 *
	 * @since 5.0.0
	 * @access public
	 * @param mixed $args Find any slider with the provided filters
	 * @return mixed Array on success, false otherwise
	 */
	public static function find($args) {
		// Find by slider ID
		if(is_numeric($args) && intval($args) == $args) {
			return self::_getById( (int) $args );

		// Find by slider slug
		} elseif(is_string($args)) {
			return self::_getBySlug($args);

		// Find by list of slider IDs
		} elseif(is_array($args) && isset($args[0]) && is_numeric($args[0])) {
			return self::_getByIds($args);

		// Find by query
		} else {


			// Defaults
			$defaults = array(
				'columns' => '*',
				'where' => '',
				'exclude' => array('hidden', 'removed'),
				'orderby' => 'date_c',
				'order' => 'DESC',
				'limit' => 10,
				'page' => 1,
				'data' => true
			);

			// User data
			foreach($defaults as $key => $val) {
				if(!isset($args[$key])) { $args[$key] = $val; } }

			// Escape user data
			foreach($args as $key => $val) {
				$args[$key] = $val; }


			// Exclude
			if(!empty($args['exclude'])) {
				if(in_array('hidden', $args['exclude'])) {
					$exclude[] = "flag_hidden = '0'"; }

				if(in_array('removed', $args['exclude'])) {
					$exclude[] = "flag_deleted = '0'"; }

				$args['exclude'] = implode(' AND ', $exclude);
			}
			
			// Where
			$where = '';
			if(!empty($args['where']) && !empty($args['exclude'])) {
				$where = "({$args['exclude']}) AND ({$args['where']}) ";

			} elseif(!empty($args['where'])) {
				$where = "{$args['where']} ";

			} elseif(!empty($args['exclude'])) {
				$where = "{$args['exclude']} ";
			}

			// Some adjustments
			$args['limit'] = ($args['limit'] * $args['page'] - $args['limit']).', '.$args['limit'];
			

      // Get a db connection.
      $db = JFactory::getDbo();
       
      // Create a new query object.
      $query = $db->getQuery(true);
       
      // Select all records from the user profile table where key begins with "custom.".
      // Order it by the ordering field.
      $query->select("SQL_CALC_FOUND_ROWS {$args['columns']}");
      $query->from($db->quoteName('#__layerslider'));
      $query->where($where);
      $query->order("{$args['orderby']} {$args['order']}");
      $query->limit($args['limit']);
      // Reset the query using our newly populated query object.
      $db->setQuery($query);
       
      // Load the results as a list of stdClass objects (see later for more options on retrieving data).
      $sliders = $db->loadAssocList();
			
			// Set counter
			$query = $db->getQuery(true);
			$query->select("FOUND_ROWS()");
      $db->setQuery($query);
      $found = $db->loadRow();
      self::$count = (int) $found[0];
      
			// Return original value on error
			if(!is_array($sliders)) { return $sliders; };

			// Parse slider data
			if($args['data']) {
				foreach($sliders as $key => $val) {
					$sliders[$key]['data'] = json_decode($val['data'], true);
				}
			}

			// Return sliders
			return $sliders;
		}
	}


	/**
	 * Add slider with the provided name and optional slider data
	 *
	 * @since 5.0.0
	 * @access public
	 * @param string $title The title of the slider to create
	 * @param array $data The settings of the slider to create
	 * @return int The slider database ID inserted
	 */
	public static function add($title = 'Unnamed', $data = array()) {

    $db = JFactory::getDbo();
    $user = JFactory::getUser();

		// Slider data 
		$data = !empty($data) ? $data : array(
			'properties' => array('title' => $title),
			'layers' => array(array()),
		);

    // Create a new query object.
    $query = $db->getQuery(true);

    // Create and populate an object.
    $datas = new stdClass();
    $datas->author = $user->id;
    $datas->name = $title;
    $datas->slug = "";
    $datas->data = json_encode($data);
    $datas->date_c =time();
    $datas->date_m =time();
    
    // Insert the object into the user profile table.
    $result = JFactory::getDbo()->insertObject('#__layerslider', $datas);      		
    $sliderId = (int)$db->insertid();
		// Return insert database ID
		return $sliderId;
	}




	/**
	 * Marking a slider as removed without deleting it
	 * with its database ID.
	 *
	 * @since 5.0.0
	 * @access public
	 * @param int $id The database ID if the slider to remove
	 * @return bool Returns true on success, false otherwise
	 */
	public static function remove($id = null) {
    $db = JFactory::getDbo();
		if(!is_int($id)) { return false; }
    $query = $db->getQuery(true);

    // Create an object.
    $datas = new stdClass();
    $datas->id = $id;
    $datas->flag_deleted = 1;
    
  	// Remove slider
    $result = JFactory::getDbo()->updateObject('#__layerslider', $datas, 'id');

		return true;
	}


	/**
	 * Delete a slider by its database ID
	 *
	 * @since 5.0.0
	 * @access public
	 * @param int $id The database ID if the slider to delete
	 * @return bool Returns true on success, false otherwise
	 */
	public static function delete($id = null) {
	
    $db = JFactory::getDbo();
		if(!is_int($id)) { return false; }
    $query = $db->getQuery(true);
     
    $query->delete($db->quoteName('#__layerslider'));
    $query->where($db->quoteName('id') . '='.$id);
     
  	// Delete slider
    $db->setQuery($query);
    $result = $db->query();

		return true;
	}



	/**
	 * Restore a slider marked as removed previously by its database ID.
	 *
	 * @since 5.0.0
	 * @access public
	 * @param int $id The database ID if the slider to restore
	 * @return bool Returns true on success, false otherwise
	 */
	public static function restore($id = null) {

    $db = JFactory::getDbo();
		if(!is_int($id)) { return false; }
    $query = $db->getQuery(true);

    // Create an object.
    $datas = new stdClass();
    $datas->id = $id;
    $datas->flag_deleted = 0;
    
  	// Remove slider
    $result = JFactory::getDbo()->updateObject('#__layerslider', $datas, 'id');

		return true;		
	}




	private static function _getById($id = null) {

		// Check ID
		if(!is_int($id)) { return false; }

    // Get a db connection.
    $db = JFactory::getDbo();
     
    // Create a new query object.
    $query = $db->getQuery(true);
     
    // Select all records from the user profile table where key begins with "custom.".
    // Order it by the ordering field.
    $query->select("*");
    $query->from($db->quoteName('#__layerslider'));
    $query->where("id = ".$id);
    $query->limit("1");
    // Reset the query using our newly populated query object.
    $db->setQuery($query);
     
    $result = $db->loadAssoc();
  
		// Check return value
		if(!is_array($result)) { return false; }

		// Return result
		$result['data'] = json_decode($result['data'], true);
		return $result;
	}



	private static function _getByIds($ids = null) {

		// Check ID
		if(!is_array($ids)) { return false; }

    // Get a db connection.
    $db = JFactory::getDbo();
		$limit = count($ids);

		// Collect IDs
		if(is_array($ids) && !empty($ids)) {
			$tmp = array();
			foreach($ids as $id) {
				$tmp[] = 'id = \''.intval($id).'\'';
			}
			$ids = implode(' OR ', $tmp);
			unset($tmp);
		}

     
    // Create a new query object.
    $query = $db->getQuery(true);
     
    $query->select("*");
    $query->from($db->quoteName('#__layerslider'));
    $query->where($ids);
    $query->limit($limit);
    // Reset the query using our newly populated query object.
    $db->setQuery($query);
     
    $result = $db->loadAssocList();

		// Make the call
//		$result = $wpdb->get_results("SELECT * FROM $table WHERE $ids ORDER BY id DESC LIMIT $limit", ARRAY_A);

		// Decode slider data
		if(is_array($result) && !empty($result)) { 
			foreach($result as $key => $slider) {
				$result[$key]['data'] = json_decode($slider['data'], true);
			}

			return $result;

		// Failed query
		} else {
			return false;
		}
	}





	private static function _getBySlug($slug) {

		// Check slug
		if(empty($slug)) { return false; }
			else { $slug = esc_sql($slug); }

		// Get DB stuff
		global $wpdb;
		$table = $wpdb->prefix.LS_DB_TABLE;

		// Make the call
		$result = $wpdb->get_row("SELECT * FROM $table WHERE slug = '$slug' LIMIT 1", ARRAY_A);

		// Check return value
		if(!is_array($result)) { return false; }

		// Return result
		$result['data'] = json_decode($result['data'], true);
		return $result;
	}
}

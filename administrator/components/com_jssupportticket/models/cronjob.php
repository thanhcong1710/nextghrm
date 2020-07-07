<?php
/**
 * @Copyright Copyright (C) 2015 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	May 22, 2015
 ^
 + Project: 	JS Tickets
 ^ 
*/

defined('_JEXEC') or die('Restricted access');


class JSTicketsModelcronjob{

	protected static $crontime = '';
	

	public static function checkCronJob($crontime){
		self::$crontime = $crontime;
		if(self::$crontime < date('Y-m-d H:i:s')){ // run cron job
			self::runCronJob();
		}
		
		return true;
	}
	
	public function runCronJob(){
		$db = JFactory::getDBO();
		

		$query  = "SELECT configvalue FROM `#__js_ticket_config` WHERE configname = 'ticket_auto_close_indays'";
		$db->setQuery($query);
		$autoclose = $db->loadResult();
		
		$query = "UPDATE `#__js_ticket_tickets` AS ticket SET ticket.`status` = 4 
					WHERE ticket.`status` = 3 AND (DATE_ADD(ticket.lastreply,INTERVAL $autoclose DAY) < CURDATE() AND ticket.isanswered = 1)";
		$db->setQuery($query);
		$db->query();
		
		self::updateCronJobTime();
		return;
	}

	public static function getCronTime(){
		$db = JFactory::getDBO();
		$query  = "SELECT configvalue FROM `#__js_ticket_config` WHERE configname = 'cronjob_time'";
		$db->setQuery($query);
		$result = $db->loadResult();
		self::$crontime = $result;
		return $result;
	}

	public function updateCronJobTime(){
		$db = JFactory::getDBO();

        $spdate = explode(" ", self::$crontime);
        if ($spdate[1])
            $crontime = explode(':', $spdate[1]);

        $curdate = explode("-", date('Y-m-d'));

		$nextcrontime = date('Y-m-d H:i:s',strtotime("$curdate[0]-$curdate[1]-$curdate[2] $crontime[0]:$crontime[1]:$crontime[2]"));

		$nextcrontime = date('Y-m-d H:i:s',strtotime($nextcrontime." +1 days"));

		
		$query  = "UPDATE `#__js_ticket_config` SET configvalue = '".$nextcrontime."' WHERE configname = 'cronjob_time'";
		$db->setQuery($query);
		$result = $db->query();
		
		//clean the cache
		$cache = JFactory::getCache('com_jssupportticket');
		$cache->setCaching( 1 ); 
		$cache->clean();
		
		return;
	}
}
	// Get a reference to the global cache object.
	//$cache = JFactory::getCache();
	$cache = JFactory::getCache('com_jssupportticket');
	$cache->setCaching( 1 ); 
	//$cache->clean();
	 
	// Run the test with caching.
	$crontime  = $cache->call( array( 'JSTicketsModelcronjob', 'getCronTime' ) );
	JSTicketsModelcronjob::checkCronJob($crontime);

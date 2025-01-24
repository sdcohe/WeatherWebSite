<?php

require_once "config/config.php";
require_once "classes/localAlert.php";

class LocalAlerts
{
	private $cfg;
	private $url = "https://alert.montgomerycountymd.gov/rssfeed.php?d=2";
	private $file = 'weatherlogs/localalerts.xml';
	
	private $alertXML;
	
	private $alertItems = array();
	private $pubDate;
	
	function __construct()
	{
		$this->cfg = config::getInstance();
			
		if (file_exists($this->file) === false || time() - filemtime($this->file) > $this->cfg->getLocalAlertStaleTime() || filesize($this->file) == 0)
		{
			// retrieve a new copy of the file
			$contents = file_get_contents($this->url);
			file_put_contents($this->file, $contents);
		}
	
		libxml_use_internal_errors(true);
	
		if (file_exists($this->file) && $this->alertXML = simplexml_load_file($this->file))
		{
			$this->parseFile();
		}
	}

	public function getPublishDate()
	{
		return $this->pubDate;
	}  
	
	public function getAlertItems()
	{
		return $this->alertItems;
	}
	
	private function parseFile()
	{
		$today = date('Ymd'); 
		$this->pubDate = strtotime($this->alertXML->channel->lastBuildDate);
		
		$itemXML = $this->alertXML->xpath('//item');
		
		foreach($itemXML as $item)
		{
			$alert = new LocalAlert($item);
			// check if pub date is today.  If so add it to the array
//			if ($today == date('Ymd', $alert->getDate()))
			{
				$this->alertItems[] = $alert;
			}
		}
	}
	
}
?>
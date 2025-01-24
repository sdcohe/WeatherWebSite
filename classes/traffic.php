<?php

require_once "config/config.php";
require_once "classes/trafficitem.php";

class Traffic
{
	private $cfg;
	private $url = "http://cityrss.traffic.com/feeds/rss_washingtondc";
	private $file = 'weatherlogs/traffic.xml';
	
	private $trafficXML;
	
	private $trafficItems = array();
	private $pubDate;
	
	function __construct()
	{
		$this->cfg = config::getInstance();
			
		if (file_exists($this->file) === false || time() - filemtime($this->file) > 300 || filesize($this->file) == 0)
		{
			// retrieve a new copy of the file
			$contents = file_get_contents($this->url);
			file_put_contents($this->file, $contents);
		}
	
		libxml_use_internal_errors(true);
	
		if (file_exists($this->file) && $this->trafficXML = simplexml_load_file($this->file))
		{
			$this->parseFile();
		}
	}

	public function getPublishDate()
	{
		return $this->pubDate;
	}  
	
	public function getTrafficItems()
	{
		return $this->trafficItems;
	}
	
	private function parseFile()
	{
		$this->pubDate = strtotime($this->trafficXML->channel->pubDate);
		// 	print_r($stats->channel->pubDate);
		
		$itemXML = $this->trafficXML->xpath('//item');
		
		foreach($itemXML as $item)
		{
			$trafficItem = new trafficItem();
		
			// parse out jam factor from description and make 2 fields
			$pos = stripos($item->title, "JAMFACTOR");
			if ($pos!== false)
			{
				$trafficItem->jamfactor = substr($item->title, $pos + strlen("JAMFACTOR"));
				$trafficItem->title = substr($item->title, 0, $pos - 2);
			}
			else
			{
				$trafficItem->title = $item->title;
				$trafficItem->jamfactor = 0;
			}
		
			// parse description - remove trailing HTML
			$pos = stripos($item->description, "<div");
			if ($pos !== false)
			{
				$trafficItem->description = substr($item->description, 0, $pos);
			}
			else
			{
				//description
				$trafficItem->description = $item->description;
			}
		
			//link
			$trafficItem->link = $item->link;
		
			//date
			$trafficItem->date = strtotime($item->pubDate);
		
			$this->trafficItems[count($this->trafficItems)] = $trafficItem;
		}
		
		usort($this->trafficItems, array("trafficItem", "compareTrafficItem"));
		
	}
	
}
?>
<?php

require_once "config/config.php";

class GovernmentOperatingStatus
{
	private $cfg;
	private $url = 'http://www.opm.gov/About_OPM/ws/xmlstatus.aspx';
	private $file = 'weatherlogs/operatingstatus.xml';
	
	private $statusXML;
	
	private $statusTitle = "Operating Status Not Available";
	private $status = "Operating Status Not Available";
	private $pubDate = "";
	private $statusMessage = "Operating Status Not Available";
	
	function __construct()
	{
		$this->cfg = config::getInstance();
			
		if (file_exists($this->file) === false || time() - filemtime($this->file) > 300)
		{
			// retrieve a new copy of the file
			$contents = file_get_contents($this->url);
			file_put_contents($this->file, $contents);
		}
	
		libxml_use_internal_errors(true);
	
		if (file_exists($this->file) && $this->statusXML = simplexml_load_file($this->file))
		{
			$this->parseFile();
		}
	}
	
	public function getStatusTitle()
	{
		return $this->statusTitle;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function getPublishDate()
	{
		return $this->pubDate;
	}
	
	public function getStatusMessage()
	{
		return $this->statusMessage;
	}
	
	private function parseFile()
	{
		$this->statusTitle = $this->statusXML->StatusTitle;
		$this->status = $this->statusXML->OperatingStatus;
		$this->pubDate = strtotime($this->statusXML->DateStatusPosted);
		$this->statusMessage = $this->statusXML->LongStatusMessage;
	}
}
?>
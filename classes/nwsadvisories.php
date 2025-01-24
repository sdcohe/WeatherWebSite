<?php

require_once "config/config.php";
require_once "classes/advisory.php";
require_once "classes/weatherConversions.php";

class NWSAdvisories
{
	private $cfg;
	private $url = "";
	private $file = 'weatherlogs/advisories.xml';
	
	private $advisoriesXML;
	private $advisoryPubDate;
	
	// Warning
	private $warnings = array();
	
	// Advisory
	private $advisories = array();
	
	// Watch
	private $watches = array();
	
	// Statement
	private $statements = array();
	
	// Other
	private $other = array();
	
	function __construct()
	{
		$this->cfg = config::getInstance();
		date_default_timezone_set($this->cfg->getTimeZone());
		
		$this->getuRL();
// 		print "'" . $this->url . "'";
			
		libxml_use_internal_errors(true);
		
		try 
		{
			if (file_exists($this->file) === false || time() - filemtime($this->file) > $this->cfg->getAdvisoryStaleTime() || filesize($this->file) == 0)
			{
				// retrieve a new copy of the file
				ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)'); 
				$contents = file_get_contents($this->url);
				file_put_contents($this->file, $contents);
			}
		
			// TODO: not XML format anymore. Is JSON
			if (file_exists($this->file) && $this->advisoriesXML = simplexml_load_file($this->file))
			{
				$this->parseFile();
			}
		}
		catch(Exception $e)
		{
			$this->advisoryPubDate = time();
			$this->other[count($this->other)] = "Error retrievng advisories";
		}
	} 

	private function getuRL()
	{
		// $this->url = "http://alerts.weather.gov/cap/wwaatmget.php?x=" . $this->cfg->getNWSCounty(); // no longer works
		$this->url = "https://api.weather.gov/alerts/active.atom?zone=" . $this->cfg->getNWSCounty(); // referred to by old page
		// $this->url = "https://api.weather.gov/alerts/active?status=actual&zone=" . $this->cfg->getNWSCounty(); used by screensaver
	}
	
	public function getJSON()
	{
		$highestPriorityAdvisory = trim($this->getHighestPriorityAdvisory());
		$warningBoxColor = warningBoxColor($highestPriorityAdvisory);
		$backgroundColor = contrastingTextColor($warningBoxColor);
		
		$arr = array(
			'AdvisoryPublicationDate' => date("c", $this->getAdvisoryPublicationDate()),
			'HighestPriorityAdvisory' => $highestPriorityAdvisory,
			'NumberOfAdvisories' => $this->getNumberOfAdvisories(),
			'WarningBoxColor' => $warningBoxColor,
			'WarningBoxBackgroundColor' => $backgroundColor,
			'nwsWarnings' => $this->getWarnings(),
			'nwsWatches' => $this->getWatches(),
			'nwsAdvisories' => $this->getAdvisories(),
			'nwsStatements' => $this->getStatements(),
			'nwsOther' => $this->getOther(),
		);
		
		return json_encode($arr);
	}
	
	public function getAdvisoryPublicationDate()
	{
		return $this->advisoryPubDate;
	}
	
	public function getWarnings()
	{
		return $this->warnings;
	}
	
	public function getWatches()
	{
		return $this->watches;
	}
	
	public function getAdvisories()
	{
		return $this->advisories;
	}
	
	public function getStatements()
	{
		return $this->statements;
	}
	
	public function getOther()
	{
		return $this->other;
	}
	
	public function getNumberOfAdvisories()
	{
		return 	count($this->warnings) + 
				count($this->advisories) + 
				count($this->watches) + 
				count($this->statements) + 
				count($this->other);
	}
	
	public function getHighestPriorityAdvisory()
	{
		if (count($this->warnings) > 0 && strlen($this->warnings[0]->title) > 0)
		{
			return $this->warnings[0]->title;
//			return $this->warnings[0];
		}
		else if (count($this->advisories) > 0 && strlen($this->advisories[0]->title) > 0)
		{
 			return $this->advisories[0]->title;
//			return $this->advisories[0];
		}
		else if (count($this->watches) > 0 && strlen($this->watches[0]->title) > 0)
		{
			return $this->watches[0]->title;
// 			return $this->watches[0];
		}
		else if (count($this->statements) > 0 && strlen($this->statements[0]->title) > 0)
		{
			return $this->statements[0]->title;
// 			return $this->statements[0];
		}
		else if (count($this->other) > 0 && strlen($this->other[0]->title) > 0)
		{
			return $this->other[0]->title;
// 			return $this->other[0];
		}
		else
		{
			return "There are no current advisories";
// 			return new NWSAdvisory("");
		}
		
	}
	
	// TODO: pare JSON format rather than XML. XML no longer in use
	private function parseFile()
	{
		$this->advisoryPubDate = strtotime($this->advisoriesXML->updated);
		
		$entries = $this->advisoriesXML->entry;
		
		foreach($entries as $entry)
		{
			// 	print (' found entry '.$entry);
		
			// create new class instance 
			$newAdvisory = new NWSAdvisory($entry);
			$title = $newAdvisory->title;
				
			// determine what type of messge it is
			if (stripos($title, 'WARNING') !== false)
			{
				// 		print (' Inserting warning at position '.count($warnings).$newAdvisory->title);
				$this->warnings[count($this->warnings)] = $newAdvisory;
			}
			else if (stripos($title, 'WATCH') !== false)
			{
				// 		print ('watch');
				$this->watches[count($this->watches)] = $newAdvisory;
			}
			else if (stripos($title, 'ADVISORY') !== false)
			{
				// 		print ('advisory');
				$this->advisories[count($this->advisories)] = $newAdvisory;
			}
			else if (stripos($title, 'STATEMENT') !== false)
			{
				// 		print ('statement');
				$this->statements[count($this->statements)] = $newAdvisory;
			}
			else
			{
				// 		print ('other');
				$this->other[count($this->other)] = $newAdvisory;
			}
		}
	}
	
	
}
?>
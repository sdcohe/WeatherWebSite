<?php

require_once 'classes/weatherConversions.php';
require_once "classes/weatherData.php";
require_once "classes/weatherStatistics.php";

class WeatherHistory
{
	private $historyXML;
	private $entries = array();
	private $maxEntry;
	private $today;
	private $yesterday;
	private $todayStartRainCount;
	private $yesterdayStartRainCount;
	private $stats;
	private $todayDay;
	private $yesterdayDay;
	
	function __construct()
	{
		libxml_use_internal_errors(true);
	
		$retryCount = 3;
	
		while ((($this->historyXML = simplexml_load_file('weatherlogs/weatherLog.xml')) === false) && $retryCount > 0 )
		{
			// 	print "retrying ";
			$retryCount--;
			sleep(3);
		}
		
// 		print_r($this->historyXML);
		if ($this->historyXML !== false)
		{
			$this->parseData();
		}
	}

	public function getJSON()
	{
// 		$idx = 0;
// 		foreach($this->entries as $entry)
// 		{
// 			$data = new weatherData($entry);
// 			$arr[$idx++] = json_decode($data->getJSON());
// 		}
	
		$arr[0] = json_decode($this->getWeatherData(5)->getJSON());
		$arr[1] = json_decode($this->getWeatherData(10)->getJSON());
		$arr[2] = json_decode($this->getWeatherData(15)->getJSON());
		$arr[3] = json_decode($this->getWeatherData(20)->getJSON());
		$arr[4] = json_decode($this->getWeatherData(30)->getJSON());
		$arr[5] = json_decode($this->getWeatherData(45)->getJSON());
		$arr[6] = json_decode($this->getWeatherData(60)->getJSON());
		$arr[7] = json_decode($this->getWeatherData(75)->getJSON());
		$arr[8] = json_decode($this->getWeatherData(90)->getJSON());
		$arr[9] = json_decode($this->getWeatherData(105)->getJSON());
		$arr[10] = json_decode($this->getWeatherData(120)->getJSON());
		
		$JSONarr = array('History' => $arr);
		
		return json_encode($JSONarr);
	}
	
	public function getEntries()
	{
		return $this->entries;
	}

	public function getMaxIndex()
	{
		return $this->maxEntry;
	}
	
	public function getWeatherData($minutesAgo)
	{
		$index = $this->getPriorMinute($minutesAgo);
		$xml = $this->entries[$index];
		
// 		print "Temp: " . $xml->OUTDOORTEMPERATURE . "<br />";

		$data = new weatherData($xml);
		
		return $data;
	}
	
	public function getRainData($minutesAgo)
	{
		$data = $this->getWeatherData($minutesAgo);
		$day = date("d", $data->getDateTime());
//		print "Day " . $day . " today " . $this->todayDay . " yesterday " . $this->yesterdayDay . "<br />";

// 		print "data date " . date("m/d/y", $data->getDateTime()) . " Day " . $day . " today " . $this->todayDay . "<br />";
		
		// if today subtract today start count
		if ($day == $this->todayDay)
		{
			return $data->getRainCount() - $this->todayStartRainCount;
		}
		else
		// else use yesterday start count
		{
			return $data->getRainCount() - $this->yesterdayStartRainCount;
		}
	}
	
	private function parseData()
	{
		// get each entry into an array
		$this->entries = $this->historyXML->xpath("//WEATHERDATAENTRY");
		
		// find the most recent entry
		$maxX = 0;
		for ($x = 0; $x < count($this->entries); $x++)
		{
			if (strtotime($this->entries[$x]->DATETIME) > strtotime($this->entries[$maxX]->DATETIME))
			{
				$maxX = $x;
			}
		}
		
		$this->maxEntry = $maxX;
		
		// get some stats to help computations
		$this->stats = new weatherStatistics();
		$this->todayStartRainCount = $this->stats->getRainCounts()->getDayStartCount()->getCountValue();
		$this->today = $this->stats->getRainCounts()->getDayStartCount()->getCountTime();
		$this->yesterdayStartRainCount = $this->stats->getRainCounts()->getYesterdayStartCount()->getCountValue();
		$this->yesterday = $this->stats->getRainCounts()->getYesterdayStartCount()->getCountTime();
		$this->todayDay = date("d", $this->today);
		$this->yesterdayDay = date("d", $this->yesterday);
		
	//	print "Today " . $this->todayDay . " yesterday " . $this->yesterdayDay . "<br />";
	}

	private function getPriorMinute($priorMinute)
	{
// 		print "Max entry is $this->maxEntry";
		
		$logPos = $this->maxEntry - $priorMinute;
		
		if ($logPos < 0.0) 
		{
			$logPos += count($this->entries);
		}
		
		return $logPos;
	}
	
}

?>
<?php

require_once "config/config.php";
require_once "classes/hazard.php";

class NWSHazards
{
	private $cfg;
	private $hazardsHTML;
	
	private $pubDate;
	private $area;
	private $hazardList = array();
	
	// file and url information
	private $url = "http://forecast.weather.gov/product.php?site=NWS&issuedby=LWX&product=HWO&format=txt&version=1&glossary=0";
	private $file = 'weatherlogs/hazards.html';
	
	function __construct()
	{
		$this->cfg = config::getInstance();
			
		if (file_exists($this->file) === false || time() - filemtime($this->file) > $this->cfg->getHazardStaleTime() || filesize($this->file) == 0)
		{
			// retrieve a new copy of the file
			ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)');
			$contents = file_get_contents($this->url);
			file_put_contents($this->file, $contents);
		}
	
		libxml_use_internal_errors(true);
	
		if (file_exists($this->file) && $this->hazardsHTML = file_get_contents($this->file))
		{
			$this->parseFile();
		}
	}
	
	public function getJSON()
	{
		$arr = array(
				'PublishDate' => date("c", $this->getPublishDate()),
				'Area' => $this->getArea(),
				'Hazards' => $this->getHazards(),
		);
	
		return json_encode($arr);
	}
	
	private function parseFile()
	{
		// get just the hazards for our zone
		$matchtext = preg_replace("/[\s\S]*MDZ...>[\s\S]*?-$/m", "", $this->hazardsHTML); // strip off stuff before the hazards
		// 	print $matchtext . "<br />";
		$matchtext = preg_replace("/[\$]{2}[\s\S]*/m", "", $matchtext);		// strip off trailing junk
		// 	print $matchtext . "<br />";
		// remove blank lines
		$matchtext = preg_replace("/^$\n/m", "", $matchtext);
		
		// 	print $matchtext . "<br />";
		$matches = explode("\n", $matchtext);
		// 	print_r($matches);
		
		$i = 0;
		//	find the firsty line not ending in "-"
		while (preg_match("/-$/", $matches[$i]) && $i < count($matches))
		{
			// 		print $matches[$i] . "<br />";
			$i++;
		}
		
		// 1st non-blank line is date/time
		while($matches[$i] == "" && $i < count($matches))
		{
			$i++;
		}
		
		// reformat date so we can parse it using strtotime()
		$dt = $matches[$i];
		// 	print $dt . "<br />";
		$dt = preg_replace("/(^[0-9]+)([0-9]{2}) ([A-Za-z]{2} [A-Za-z]{3}) (.*)/", '$4 $1:$2 $3', $dt);
		// 	print $dt . "<br />";
		$this->pubDate = strtotime($dt);
		
		// collect together starting at next non-blank line up to a blank line.  This is area.  Replace ... with ", "
		$i++;
		while($matches[$i] == "")
		{
			$i++;
		}
		
		$area = "";
		while($i < count($matches) && !preg_match("/^\./", $matches[$i]))
		{
			$area = $area . " " . $matches[$i++];
		}
		
		$area = trim(preg_replace("/[\.]{3}/", ", ", $area));
		$area = preg_replace("/  /", " ", $area);
		// 	print "Area: $area <br />" . PHP_EOL;
		$this->area = ucwords(strtolower($area));
		
		// 	get day/hazard pairs
		
		$j = 0;
		while ($i < count($matches))
		{
			// if line starts with a period we have another hazard
			if (preg_match("/^\./", $matches[$i]))
			{
				$hazard = "";
				$period = $matches[$i++];
				$period = preg_replace("/^\..*[\.]{3}/", "", $period);
				while ($i < count($matches) && !(preg_match("/^\./", $matches[$i])))
				{
					$hazard = $hazard . " " . $matches[$i];
					$i++;
				}
				// just for now.  store in a better format
				$period = ucwords(strtolower($period));
				
				// $hazard = strtolower(trim($hazard));
				// ***deprecated $hazard = preg_replace("/(monday|tuesday|wednesday|thursday|friday|saturday|sunday)/e", 'ucfirst("$1")', $hazard);
				// ***deprecated $hazard = preg_replace_callback('/[.!?].*?\w/', create_function('$matches', 'return strtoupper($matches[0]);'),$hazard);
				// $hazard = ucfirst($hazard);
				if (strlen($period) == 0)
				{
					$period = "Other";
				}
				$value = new hazard($period, $hazard);
				$this->hazardList[$j] = $value;
				$j++;
			}
			else
			{
				// 			print "No match: $matches[$i] <br />";
				$i++;
			}
		}
	}

	// publish date
	public function getPublishDate()
	{
		return $this->pubDate;
	}
	
	// area
	public function getArea()
	{
		return $this->area;
	} 
	
	// hazards list
	public function getHazards()
	{
		return $this->hazardList;
	}
	
}

?>
<?php

require_once "config/config.php";
require_once 'classes/weatherConversions.php';
require_once 'classes/forecastEntry.php';

// use the following URL in a browser to test different options for retrieving the forecast XML
// http://graphical.weather.gov/xml/sample_products/browser_interface/ndfdXML.htm

class NWSForecast
{
	
// 	private $url = "http://forecast.weather.gov/MapClick.php?lat=39.1522&lon=-77.2679&FcstType=dwml";
	private $url = "";
	private $file = 'weatherlogs/forecast.xml';
	private $errorFile = 'weatherlogs/forecast-error.xml';
	private $iconFile = 'favicon.png';
	private $faviconFile = 'favicon.ico';
	
	private $forecastXML;
	private $productionCenter;
	private $credit;
	private $tempMin;
	private $tempMax;
	private $pop;
	private $summary;
	private $icons;
	private $wordedForecast;
	private $forecastPeriod;
	private $minPeriod;
	private $maxPeriod;
	private $forecastTimePeriod;
	private $maxTimePeriod;
	private $minTimePeriod;
	private $creationDate;
	
	private $forecastEntries;
	
	private $cfg;
	
	function __construct()
	{
		// set configuration and initialize the structure
		$this->cfg = config::getInstance();
		date_default_timezone_set($this->cfg->getTimeZone());
		$this->url = $this->getURL();
		$this->initForecast();
		 
		libxml_use_internal_errors(true);

		// check to see if we need to retrieve the latest forecast.  If we don't have a current one or
		// the current one is stale retrieve a new one
		if (file_exists($this->file) === false || time() - filemtime($this->file) > $this->cfg->getForecastStaleTime() || filesize($this->file) == 0)
		{
			// retrieve a new copy of the file from the NWS.  Set user agent to mimic Mozilla
			ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)'); 
			$contents = file_get_contents($this->url);
			
			// attempt to parse the xml.  If it fails don't replace the current forecast file.  Just save
			// it for any analysis we may need to do.  Otherwise save off the more current forecast
			$xml = simplexml_load_string($contents);
			if (!$xml)
			{
				file_put_contents($this->errorFile, $contents);
			}
			else
			{
				file_put_contents($this->file, $contents);
			}
			
			// if the forecast starts with XML it may be valid.  If not it definitely is not valid and should be discarded
			// $pattern = "/^\<\?xml/i";
			// if (preg_match($pattern, $contents))
			// {
				// file_put_contents($this->file, $contents);
			// }
			// else
			// {
				// file_put_contents($this->errorFile, $contents);
			// }
		}
		
		// if we have a valid forecast file we can now attempt to work with it
		if (file_exists($this->file) && filesize($this->file) > 0 && $this->forecastXML = simplexml_load_file($this->file))
		{
			try 
			{
				// this is where we actually build the forecast class from the data we retrieved
				$this->parseFile();
				
				// save off a favicon file that is the first icon in the weather forecast (curent conditions).
				if (file_exists($this->iconFile) === false || time() - filemtime($this->iconFile) > $this->cfg->getForecastStaleTime() || filesize($this->iconFile) == 0)
				{
					// retrieve favicon
					$iconURL = $this->getForecastIcons(0);
					$contents = file_get_contents($iconURL);
					file_put_contents($this->iconFile, $contents);
					exec('convert ' . $this->iconFile . ' -resize 32x32 ' . $this->faviconFile);
				}
			}
			catch (Exception $e)
			{
				$this->initForecast();
			}
		}
		else
		{
			// handle error condition in some way
			$this->initForecast();
		}
	}
	
	public function initForecast()
	{
		$this->productionCenter = "";
		$this->credit = "";
		$this->tempMin = array(); // array
		$this->tempMax = array(); // array
		$this->pop = array(); // array
		$this->summary = "Error loading forecast";
		$this->icons = array();  // should be an array
		$this->wordedForecast = array();
		$this->forecastPeriod = "";
		$this->minPeriod = "";
		$this->maxPeriod = "";
		$this->forecastTimePeriod = "";
		$this->maxTimePeriod = "";
		$this->minTimePeriod = "";
		$this->creationDate = time();
		
		$this->forecastEntries = array();
	}
	
	public function getJSON()
	{
		$arr = array(
			'ForecastCredit' => (string)$this->getCredit(),
			'ForecastCreationDate' => date("c", $this->getCreationDate()),
//			'ForecastDay' => $this->getForecastDayArray(),
//			'ForecastIcon' => $this->getForecastIconArray(),
//			'Forecast' => $this->getForecastArray(),
//			'ForecastSummary' => $this->getForecastSummaryArray(),
//			'ProbabilityofPrecipitation' => $this->getProbabilityOfPrecipitationArray(),
//			'HiLoType' => $this->getHiLoTypeArray(),
//			'HiLoTemp' => $this->getHiLoTempArray(),
//			'HTMLForecast' => HTMLFormatForecast($this),
			'ForecastEntries' => $this->getForecastEntries(),
		);

		return json_encode($arr);
	}
	
	private function getURL()
	{
		return 'http://forecast.weather.gov/MapClick.php?lat=' . $this->cfg->getLatitude() . '&lon=' . $this->cfg->getLongitude() . '&FcstType=dwml';
// 		return 'http://graphical.weather.gov/xml/sample_products/browser_interface/ndfdXMLclient.php?whichClient=NDFDgen&lat=' . $this->cfg->getLatitude() . '&lon=' . $this->cfg->getLongitude() . '&product=glance';
	}
	
	private function parseFile()
	{
		$tempDate = $this->forecastXML->xpath('//creation-date');
//		print $tempDate[0];
		$this->creationDate = strtotime($tempDate[0]);

		$this->productionCenter = $this->forecastXML->xpath('//production-center');
		$this->productionCenter = $this->productionCenter[0];
// 		print $this->productionCenter;
		
		$this->credit = $this->forecastXML->head->source->credit;
// 		print $this->credit;
		
		$this->tempMax = $this->forecastXML->xpath('//temperature[@type="maximum"]/value');
// 		print_r($this->tempMax);
		
		$this->tempMin = $this->forecastXML->xpath('//temperature[@type="minimum"]/value');
// 		print_r($this->tempMin);
		
		$this->pop = $this->forecastXML->xpath('//probability-of-precipitation/value');
// 		print_r($this->pop);
		
		$this->summary = $this->forecastXML->xpath('//weather/weather-conditions');
		$this->icons = $this->forecastXML->xpath('//conditions-icon/icon-link');
		$this->wordedForecast = $this->forecastXML->xpath('//wordedForecast/text');
// 		print_r($this->wordedForecast);
		
		$this->forecastPeriod = $this->forecastXML->data->parameters->weather;
		$this->minPeriod = $this->forecastXML->xpath('//temperature[@type="minimum"]');
		$this->minPeriod = $this->minPeriod[0]["time-layout"];
		$this->maxPeriod = $this->forecastXML->xpath('//temperature[@type="maximum"]');
		$this->maxPeriod = $this->maxPeriod[0]["time-layout"];
		
		$path = '//time-layout[layout-key="' . $this->forecastPeriod["time-layout"] . '"]/start-valid-time';
		$this->forecastTimePeriod = $this->forecastXML->xpath($path);

// 		print_r ($this->forecastTimePeriod);
// 		print count($this->forecastTimePeriod);
		
		$path = '//time-layout[layout-key="' . $this->maxPeriod . '"]/start-valid-time';
		$this->maxTimePeriod = $this->forecastXML->xpath($path);
//		print $maxTimePeriod[0];
		
		$path = '//time-layout[layout-key="' . $this->minPeriod . '"]/start-valid-time';
		$this->minTimePeriod = $this->forecastXML->xpath($path);
		
		// populate forecastEntries array
		for ($i = 0; $i < count($this->forecastTimePeriod); $i++)
		{
			$entry = new ForecastEntry();
			
			$entry->setTimePeriodName($this->getForecastDay($i));
			$entry->setTimePeriodRange((string)$this->getForecastTime($i));  // need to debug
			$entry->setPop($this->getProbabilityOfPrecipitation($i));
			$entry->setIconURL($this->getForecastIcons($i));
			$entry->setWeatherType($this->getForecastSummary($i));
			$entry->setWordedForecast($this->getForecast($i));
			$entry->setMinMaxType($this->getHiLoType($i));
			$entry->setMinMax($this->getHiLoTemp($i));

			$this->forecastEntries[] = $entry;
		}
	}

	public function getProductionCenter()
	{
		return $this->productionCenter;
	}
	
	public function getCredit()
	{
		return $this->credit;
	}
	
	public function getCreationDate()
	{
//		$creationDate = $this->forecastXML->xpath("//creation-date");
//		return strtotime($creationDate[0]);
		return $this->creationDate;
	}
	
	public function getForecastIconArray()
	{
		$arr = array();
		if (is_array($this->getForecastIconList()))
		{
			foreach($this->getForecastIconList() as $icon)
			{
				$arr[] = (string)$icon;
			}
		}
		
		return $arr;
		
	}
	
	public function getForecastIcons($period)
	{
		$icon = "";
		if ($period >= 0 && $period < count($this->icons))
		{
			$icon = (string)$this->icons[$period];
		}
		
		return $icon;
	}
	
	public function getForecastIconList()
	{
		return $this->icons;
	}
	
	public function getForecastArray()
	{
		$arr = array();
		if (is_array($this->wordedForecast))
		{
			foreach($this->wordedForecast as $forecast)
			{
				$arr[] = (string)$forecast;
			}
		}
		else
		{
			$idx = 0;
			for ($idx = 0; $idx < 10; $idx++)
			{
				$arr[] = "Error retrieving forecast";
			}
		}
		
		return $arr;
	}
	
	// worded forecast and 5 day forecast fill ins
	public function getForecast($period)
	{
		$forecast = "";
		if ($period >= 0 && $period < count($this->wordedForecast))
		{
			$forecast = (string)$this->wordedForecast[$period];
		}
		
		return $forecast;
	}
	
	public function getForecastList()
	{
		if (is_array($this->wordedForecast))
		{
			return $this->wordedForecast;
		}
		else
		{
			$arr = array();
			$idx = 0;
			for ($idx = 0; $idx < 10; $idx++)
			{
				$arr[] = "Error retrieving forecast";
			}
			return $arr;
		}
	}
	
	private function getForecastDayArray()
	{
 		$arr = array();
 		$idx = 0;
 		if (is_array($this->forecastTimePeriod))
 		{
 			for ($idx = 0; $idx < count($this->forecastTimePeriod); $idx++)
 			{
 				$arr[] = (string)$this->getForecastDay($idx);
 			}
 		}
 		else
 		{
 			for ($idx = 0; $idx < 10; $idx++)
 			{
 				$arr[] = (string)$this->getForecastDay($idx);
 			}
 		}

		return $arr;
	}
	
	// 5 day forecast day
	public function getForecastDay($period)
	{
		$forecastDay = "Unknown";
		try 
		{
			if (is_array($this->forecastTimePeriod) && is_numeric($period) && $period >= 0 && $period < count($this->forecastTimePeriod))
			{
				$forecastDay = $this->forecastTimePeriod[$period]["period-name"];
				// 			$forecastDay = $this->forecastTimePeriod[$period][0];
			}
		}
		catch (Exception $ex)
		{
			$forecastDay = "Unknown";
		}
		
		return (string)$forecastDay;
	}
	
// 	public function getForecastDay($period)
// 	{
// 			$index = (int)($period / 2);
// 		if (strtotime($this->getForecastTime(0)) == strtotime($this->minTimePeriod[0]))
// 		{
// 			if ((int)$period % 2 == 0 )
// 			{
// 				start with min
// 				return $this->minTimePeriod[$index]["period-name"];
// 			}
// 			else
// 			{
// 				start with max
// 				return $this->maxTimePeriod[$index]["period-name"];
// 			}
// 		}
// 		else
// 		{
// 			if ((int)$period % 2 == 0 )
// 			{
// 				start with max
// 				return $this->maxTimePeriod[$index]["period-name"];
// 			}
// 			else
// 			{
// 				start with min
// 				return $this->minTimePeriod[$index]["period-name"];
// 			}
// 		}
		
// 	}
	
	
	public function getForecastTime($period)
	{
		$forecastDay = "";
		if ($period >= 0 && $period < count($this->forecastTimePeriod))
		{
// 			$forecastDay = $this->forecastTimePeriod[$period]["period-name"];
			$forecastDay = $this->forecastTimePeriod[$period];
		}
	
		return $forecastDay;
	}
	
	private function getForecastSummaryArray()
	{
		$arr = array();
		$idx = 0;
	
		if (is_array($this->summary))
		{
			foreach ($this->summary as $summary)
			{
				$arr[count($arr)] = (string)$summary["weather-summary"];
			}
		}
		else
		{
			for ($idx = 0; $idx < 10; $idx++)
			{
				$arr[$idx] = "Unknown";
			}
		}
			
		return $arr;
	}
	
	
	public function getForecastSummary($period)
	{
		$forecastSummary = "Unknown";
		if (is_array($this->summary) && is_numeric($period) && $period >= 0 && $period < count($this->summary))
		{
			$forecastSummary = (string)$this->summary[$period]["weather-summary"];
		}
		
		return $forecastSummary;
	} 
	
	public function getProbabilityOfPrecipitationArray()
	{
		$arr = array();
		$idx = 0;
// 		while ($idx < count($this->wordedForecast))
		while ($idx < count($this->forecastTimePeriod))
		{
			$arr[$idx] = (string)$this->getProbabilityOfPrecipitation($idx);
			$idx++;
		}
		
		return $arr;
	}
	
	public function getProbabilityOfPrecipitation($period)
	{
		$pop = "0";
		if ($period >= 0 && $period < count($this->pop))
		{
			$pop = $this->pop[$period];
			if ($pop == "")
			{
				$pop = 0;
			}
		}
		
		return (string)$pop;
	}
	
	public function getHiLoTypeArray()
	{
		$arr = array();
		$idx = 0;
// 		while ($idx < count($this->wordedForecast))
		while ($idx < count($this->forecastTimePeriod))
		{
			$arr[$idx] = $this->getHiLoType($idx);
			$idx++;
		}
		
		return $arr;
		
	}
	// forecast HI/LO type, color, and value
	public function getHiLoType($period)
	{
// 		print "Forecast day" . $this->getForecastDay(0) . " MIN period " .  $this->minTimePeriod[0];
		 
		if (strtotime($this->getForecastTime(0)) == strtotime($this->minTimePeriod[0]))
		{
			// 1st is LO
			$even = "Lo";
			$odd = "Hi";
		}
		else
		{
			// 1st is HI
			$even = "Hi";
			$odd = "Lo";
		}
		
		if ((int)$period % 2 == 0)
		{
			return $even;
		}
		else
		{
			return $odd;
		}
	}
	
	public function getHiLoColor($period)
	{
		if (strtotime($this->getForecastTime(0)) == strtotime($this->minTimePeriod[0]))
		{
			// 1st is LO
			$even = $this->cfg->getBlue();
			$odd = $this->cfg->getRed();
		}
		else
		{
			// 1st is HI
			$even = $this->cfg->getRed();
			$odd = $this->cfg->getBlue();
		}
		
		if ((int)$period % 2 == 0)
		{
			return $even;
		}
		else
		{
			return $odd;
		}
	} 
	
	public function getHiLoTempArray()
	{
		$arr = array();
		$idx = 0;
// 		while ($idx < count($this->wordedForecast))
		while ($idx < count($this->forecastTimePeriod))
		{
			$arr[$idx] = (string)$this->getHiLoTemp($idx);
			$idx++;
		}
		
		return $arr;
		
	}
	
	public function getHiLoTemp($period)
	{
		$index = (int)($period / 2);
		if (strtotime($this->getForecastTime(0)) == strtotime($this->minTimePeriod[0]))
		{
			if ((int)$period % 2 == 0 )
			{
				// start with min
				return (string)$this->tempMin[$index];
			}
			else
			{
				// start with max
				return (string)$this->tempMax[$index];
			}
		}
		else
		{
			if ((int)$period % 2 == 0 )
			{
				// start with max
				return (string)$this->tempMax[$index];
			}
			else
			{
				// start with min
				return (string)$this->tempMin[$index];
			}
		}
	}
	
	public function getForecastEntries()
	{
		return $this->forecastEntries;
	}
}
?>
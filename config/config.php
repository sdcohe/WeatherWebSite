<?php
class config
{
	// required for singleton.  Don't change
	private static $instance;
	
	// the number of decimal places to display.  Change to display more or less precision.  Changing this
	//	will affect page layout 
	private $numberDecimalPlaces = 2;
	
	// colors for daily temps in forecast.  Red is high and blue is low 
	private $red = "#FF0000"; // - Red
	private $blue = "#0033CC"; // - Blue
	
	// change this for your station location.  
	// 	Note: Is this still needed in the code?  May want to keep for future use. 
	private $latitude = 39.1522;
	private $longitude = -77.2679;
	
	// National weather service zone and county.  These are defined by the NWS.  They are used by the scripts
	//	to retrieve the current NWS weather information, hazards, warnings, and alerts
	private $NWSZone = "MDZ009";
	private $NWSCounty = "MDC031";

	// Date and time related info.  Set the time zone and how to format date/time values
	private $myTimeZone = "America/New_York";
	private $dateTimeFormat = "D M d, Y H:i:s T";
	private $shortDateTimeFormat = "m/d/y H:i:s";
	private $veryShortDateTimeFormat = "m/d/y H:i";
	private $timeFormat = "H:i:s T";
	private $dateFormat = "m/d/Y";
	
	// Directory where weather data is stored.  This includes uploaded data from the weather station and data downloaded by
	// these scripts such as the forecast and advisories. 
	private $dataDir = "weatherlogs";
	
	// Source for webcam thumb nail image.  I use a different source when testing on my local network
	//	vs. when I deploy to my web hosting provider
//	private $webcamImage = "http://webcam.cloppermillweather.org:8888/out.jpg";	// my local network
	private $webcamImage = "http://www.cloppermillweather.org/images/Front.jpg";	// when deployed to web hosting
	
	/**********************************************
	 * Data retrieval function stale data times.
	 * these are specified in seconds.  When a file is older
	 * than this time a new copy is retrieved
	 *********************************************/
	// retrieve a new weather forecast if current file is older than this.  Default is 600 secs / 10 minutes
	private $forecastIsStale = 600; 
	
	// retrieve new weather advisories if current file is older than this.  Default is 600 secs / 10 minutes
	private $advisoryIsStale = 600; 
	
	// retrieve new weather discussion if current file is older than this.  Default is 1800 secs / 30 minutes
	private $discussionIsStale = 1800; 
	
	// retrieve new weather hazards if current file is older than this.  Default is 1800 secs / 30 minutes
	private $hazardIsStale = 1800; 
	
	// retrieve new local alerts if current file is older than this.  Default is 1800 secs / 30 minutes
	private $localAlertIsStale = 1800; 
	
	/*
	 * Data refresh rates for the JavaScript front end Web pages
	 * 
	 * Specify values in seconds
	 */
	private $dataRefreshRate = 15;
	private $statsRefreshRate = 60; 
	private $webcamRefreshRate = 30;
	private $forecastRefreshRate = 600;
	private $hazardsRefreshRate = 600;
	private $discussionRefreshRate = 1800;
	private $advisoriesRefreshRate = 600;
	private $historyRefreshRate = 60;
	private $faviconRefreshRate = 600;
	
	private function __construct()
	{
	}

	public static function getInstance()
	{
		if (!isset(self::$instance)) 
		{
			// echo 'Creating new instance.';
			$className = __CLASS__;
			self::$instance = new $className;
		}
		
		return self::$instance;
	}
	
	public function getDataDir()
	{
		return $this->dataDir;
	}
	
	public function getNWSZone()
	{
		return $this->NWSZone;
	}
	
	public function getNWSCounty()
	{
		return $this->NWSCounty;
	}
	
	public function getLatitude()
	{
		return $this->latitude;
	}
	
	public function getLongitude()
	{
		return $this->longitude;
	}
	
	public function getTimeZone()
	{
		return $this->myTimeZone;
	}
	
	public function getDateTimeFormat()
	{
		return $this->dateTimeFormat;
	}
	
	public function getShortDateTimeFormat()
	{
		return $this->shortDateTimeFormat;
	}
	
	public function getVeryShortDateTimeFormat()
	{
		return $this->veryShortDateTimeFormat;
	}
	
	public function getTimeFormat()
	{
		return $this->timeFormat;
	}
	
	public function getDateFormat()
	{
		return $this->dateFormat;
	}
	
	public function getNumberDecimalPlaces()
	{
		return $this->numberDecimalPlaces;
	}
	
	public function getRed()
	{
		return $this->red;
	}
	
	public function getBlue()
	{
		return $this->blue;
	}
	
	public function getForecastStaleTime()
	{
		return $this->forecastIsStale;
	}

	public function getAdvisoryStaleTime()
	{
		return $this->advisoryIsStale;
	}

	public function getDiscussionStaleTime()
	{
		return $this->discussionIsStale;
	}

	public function getHazardStaleTime()
	{
		return $this->hazardIsStale;
	}
	
	public function getDataRefreshRate()
	{
		return $this->dataRefreshRate;
	}

	public function getStatsRefreshRate()
	{
		return $this->statsRefreshRate;
	}

	public function getWebcamRefreshRate()
	{
		return $this->webcamRefreshRate;
	}

	public function getForecastRefreshRate()
	{
		return $this->forecastRefreshRate;
	}
	
	function getHazardsRefreshRate()
	{
		return $this->hazardsRefreshRate;
	}
	
	public function getDiscussionRefreshRate()
	{
		return $this->discussionRefreshRate;
	}
	
	public function getAdvisoriesRefreshRate()
	{
		return $this->advisoriesRefreshRate;
	}
	
	public function getHistoryRefreshRate()
	{
		return $this->historyRefreshRate;
	}

	public function getFaviconRefreshRate()
	{
		return $this->faviconRefreshRate;
	}
	
	public function getLocalAlertStaleTime()
	{
		return $this->localAlertIsStale;
	}
	
	public function getWebcamImageSource()
	{
		return $this->webcamImage;
	}
}
?>
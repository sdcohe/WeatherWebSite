<?php

require_once 'classes/weatherConversions.php';
require_once 'classes/cumulativeValues.php';
require_once 'classes/cumulativeCounts.php';
require_once 'classes/periodicValues.php';

class weatherStatistics
{
	private $data;
	
	function __construct($xml="")
	{
		libxml_use_internal_errors(true);
		
		if ($xml !== "")
		{
			$this->data = $xml;
		}
		else
		{
			$retryCount = 3;
			
			while ((($this->data = simplexml_load_file('weatherlogs/weatherStats.xml')) === false) && $retryCount > 0 )
			{
				// 	print "retrying ";
				$retryCount--;
				sleep(3);
			}
			
			if ($this->data !== false)
			{
// 				$this->initializeStats();
			}
		}
	
	}
	
	public function getJSON()
	{
		$arr = array(	
						'AverageWindDirection' => $this->getAverageWindDirection(),
						'AverageWindSpeed' => $this->getAverageWindSpeed(), 
						'MaxWindGustInterval' => $this->getMaxWindGustInterval(),
						'Windchill' => $this->getWindChill(), 
						'OutdoorTemperatureTrend' => $this->getOutdoorTemperatureTrend(), 
						'OutdoorHumidityTrend' => $this->getOutdoorHumidityTrend(), 
						'PressureTrend' => $this->getPressureTrend(), 
						'WindRun' => $this->getWindRun(),
						'SolarTrend' => $this->getSolarTrend(), 
						'IndoorTemperatureTrend' => $this->getIndoorTemperatureTrend(), 
						'IndoorHumidityTrend' => $this->getIndoorHumidityTrend(), 
						'StatsStartDate' => date("c", $this->getStatsStartDate()),
						'LastStartDate' => date("c", $this->getLastStartDate()),
						'LastSampleDate' => date("c", $this->getLastSampleDate()),
						'TempMaxGT86F' => $this->getTempMaxGT86F(),
						'TempMaxGT77F' => $this->getTempMaxGT77F(),
						'TempMaxLT32F' => $this->getTempMaxLT32F(),
						'TempMaxLT5F' => $this->getTempMaxLT5F(),
		// end of simple variable - need to add getJSON() to all contained classes
						'OutdoorTempStats' => json_decode($this->getOutdoorTempStats()->getJSON()),
						'OutdoorHumidityStats' => json_decode($this->getOutdoorHumidityStats()->getJSON()),
						'IndoorTempStats' => json_decode($this->getIndoorTempStats()->getJSON()),
						'IndoorHmidityStats' => json_decode($this->getIndoorHumidityStats()->getJSON()),
						'WindGustStats' => json_decode($this->getWindGustStats()->getJSON()),
						'WindSustainedStats' => json_decode($this->getWindSustainedStats()->getJSON()),
						'PressureStats' => json_decode($this->getPressureStats()->getJSON()),
						'SolarStats' => json_decode($this->getSolarStats()->getJSON()),
						'HeatIndexStats' => json_decode($this->getHeatIndexStats()->getJSON()),
						'WindChillStats' => json_decode($this->getWindChillStats()->getJSON()),
						'DewPointStats' => json_decode($this->getDewPointStats()->getJSON()),
						'DaytimeOutdoorTemperatureStats' => json_decode($this->getDaytimeOutdoorTemperatureStats()->getJSON()),
						'NighttimeOutdoorTemperatureStats' => json_decode($this->getNighttimeOutdoorTemperatureStats()->getJSON()),
						'RainCounts' => json_decode($this->getRainCounts()->getJSON()),
						'LightningCounts' => json_decode($this->getLightningCounts()->getJSON()),
						'WindRunCounts' => json_decode($this->getWindRunCounts()->getJSON()),
						'MaxAverageWindDirection' => json_decode($this->getMaxAverageWindDirection()->getJSON()),
						'MaxGustWindDirection' => json_decode($this->getMaxGustWindDirection()->getJSON()),
		);
		
		return json_encode($arr);
		
	}

	// 	AVERAGEWINDDIRECTION
	public function getAverageWindDirection()
	{
		static $val;
		if (!isset($val))
		{
			$val = (float)$this->data->WEATHERSTATISTICS->AVERAGEWINDDIRECTION;
		}
		
		return $val;
	}
	
	// 	WINDCHILL
	public function getWindChill()
	{
		return (float)$this->data->WEATHERSTATISTICS->WINDCHILL;
	}
	
	// 	WINDSUSTAINED
	public function getAverageWindSpeed()
	{
		return (float)$this->data->WEATHERSTATISTICS->WINDSUSTAINED;
	}
	
	// Max wind gust during the last interval (default 10 minutes)
	public function getMaxWindGustInterval()
	{
		return (float)$this->data->WEATHERSTATISTICS->MAXGUSTINTERVAL;
	}
	
	// 	OUTDOORTEMPTREND
	public function getOutdoorTemperatureTrend()
	{
		return (float)$this->data->WEATHERSTATISTICS->OUTDOORTEMPTREND;
	}
	
	// 	OUTDOORHUMIDITYTREND
	public function getOutdoorHumidityTrend()
	{
		return (float)$this->data->WEATHERSTATISTICS->OUTDOORHUMIDITYTREND;
	}
	
	// 	PRESSURETREND
	public function getPressureTrend()
	{
		return (float)$this->data->WEATHERSTATISTICS->PRESSURETREND;
	}
	
	// 	WINDRUN
	public function getWindRun()
	{
		return (float)$this->data->WEATHERSTATISTICS->WINDRUN;
	}
	
	// 	SOLARTREND
	public function getSolarTrend()
	{
		return (float)$this->data->WEATHERSTATISTICS->SOLARTREND;
	}
	
	// 	INDOORTEMPTREND
	public function getIndoorTemperatureTrend()
	{
		return (float)$this->data->WEATHERSTATISTICS->INDOORTEMPTREND;
	}
	
	// 	INDOORHUMIDITYTREND
	public function getIndoorHumidityTrend()
	{
		return (float)$this->data->WEATHERSTATISTICS->INDOORHUMIDITYTREND;
	}
	
	// 	STATSSTARTDATE
	public function getStatsStartDate()
	{
		return strtotime($this->data->WEATHERSTATISTICS->STATSSTARTDATE);
	}
	
	// 	LASTSTARTDATE
	public function getLastStartDate()
	{
		return strtotime($this->data->WEATHERSTATISTICS->LASTSTARTDATE);
	}
	
	// 	TEMPGT86DATE
// 	public function getTempMaxGT86FDate()
// 	{
// 		return strtotime($this->data->WEATHERSTATISTICS->TEMPGT86DATE);
// 	}
	
	// 	TEMPGT86
	public function getTempMaxGT86F()
	{
		return (float)$this->data->WEATHERSTATISTICS->TEMPGT86;
	}
	
	// 	TEMPGT77DATE
// 	public function getTempMaxGT77FDate()
// 	{
// 		return strtotime($this->data->WEATHERSTATISTICS->TEMPGT77DATE);
// 	}
	
	// 	TEMPGT77
	public function getTempMaxGT77F()
	{
		return (float)$this->data->WEATHERSTATISTICS->TEMPGT77;
	}
	
	// 	TEMPLT32DATE
// 	public function getTempMaxLT32FDate()
// 	{
// 		return strtotime($this->data->WEATHERSTATISTICS->TEMPLT32DATE);
// 	}
	
	// 	TEMPLT32
	public function getTempMaxLT32F()
	{
		return (float)$this->data->WEATHERSTATISTICS->TEMPLT32;
	}
	
	// 	TEMPLT5DATE
// 	public function getTempMaxLT5FDate()
// 	{
// 		return strtotime($this->data->WEATHERSTATISTICS->TEMPLT5DATE);
// 	}
	
	// 	TEMPLT5
	public function getTempMaxLT5F()
	{
		return (float)$this->data->WEATHERSTATISTICS->TEMPLT5;
	}
	
	// 	LASTSAMPLEDATE
	public function getLastSampleDate()
	{
		return strtotime($this->data->WEATHERSTATISTICS->LASTSAMPLEDATE);
	}
	
	// outdoor temp
	public function getOutdoorTempStats()
	{
		static $val;
		if (!isset($val))
		{
			$val = new CumulativeValues($this->data->WEATHERSTATISTICS->OUTDOORTEMPSTATS);
		}
		
		return $val;
	}
	
	// outdoor humidity
	public function getOutdoorHumidityStats()
	{
		static $val;
		if (!isset($val))
		{
			$val = new CumulativeValues($this->data->WEATHERSTATISTICS->OUTDOORHUMIDITYSTATS);
		}
		
		return $val;
	}
	
	// wind gust
	public function getWindGustStats()
	{
		static $val;
		if (!isset($val))
		{
			$val = new CumulativeValues($this->data->WEATHERSTATISTICS->WINDGUSTSTATS);
		}
		
		return $val;
	}
	
	// wind average
	public function getWindSustainedStats()
	{
		static $val;
		if (!isset($val))
		{
			$val = new CumulativeValues($this->data->WEATHERSTATISTICS->WINDSUSTAINEDSTATS);
		}
		
		return $val;
	}
	
	// indoor temp
	public function getIndoorTempStats()
	{
		static $val;
		if (!isset($val))
		{
			$val = new CumulativeValues($this->data->WEATHERSTATISTICS->INDOORTEMPSTATS);
		}
		
		return $val;
	}
	
	// indoor humidity
	public function getIndoorHumidityStats()
	{
		static $val;
		if (!isset($val))
		{
			$val = new CumulativeValues($this->data->WEATHERSTATISTICS->INDOORHUMIDITYSTATS);
		}
		
		return $val;
	}
	
	// barometric pressure
	public function getPressureStats()
	{
		static $val;
		if (!isset($val))
		{
			$val = new CumulativeValues($this->data->WEATHERSTATISTICS->PRESSURESTATS);
		}
		
		return $val;
	}
	
	// solar
	public function getSolarStats()
	{
		static $val;
		if (!isset($val))
		{
			$val = new CumulativeValues($this->data->WEATHERSTATISTICS->SOLARSTATS);
		}
		
		return $val;
	}
	
	// heat index
	public function getHeatIndexStats()
	{
		static $val;
		if (!isset($val))
		{
			$val = new CumulativeValues($this->data->WEATHERSTATISTICS->HEATINDEXSTATS);
		}
		
		return $val;
	}
	
	// wind chill
	public function getWindChillStats()
	{
		static $val;
		if (!isset($val))
		{
			$val = new CumulativeValues($this->data->WEATHERSTATISTICS->WINDCHILLSTATS);
		}
		
		return $val;
	}
	
	// dew point
	public function getDewPointStats()
	{
		static $val;
		if (!isset($val))
		{
			$val = new CumulativeValues($this->data->WEATHERSTATISTICS->DEWPOINTSTATS);
		}
		
		return $val;
	}
	
	// min/max daytime outdoor temp
	public function getDaytimeOutdoorTemperatureStats()
	{
		static $val;
		if (!isset($val))
		{
			$val = new CumulativeValues($this->data->WEATHERSTATISTICS->MINMAXDAY);
		}
		
		return $val;
	}
	
	// min/max night outdoor temp
	public function getNighttimeOutdoorTemperatureStats()
	{
		static $val;
		if (!isset($val))
		{
			$val = new CumulativeValues($this->data->WEATHERSTATISTICS->MINMAXNIGHT);
		}
		
		return $val;
	}
	
	
	// rain
	public function getRainCounts()
	{
		static $val;
		if (!isset($val))
		{
			$val = new CumulativeCounts($this->data->WEATHERSTATISTICS->RAINCOUNTS);
		}
		
		return $val;
	}
	
	// lightning
	public function getLightningCounts()
	{
		static $val;
		if (!isset($val))
		{
			$val = new CumulativeCounts($this->data->WEATHERSTATISTICS->LIGHTNINGCOUNTS);
		}
		
		return $val;
	}

	// wind run
	public function getWindRunCounts()
	{
		static $val;
		if (!isset($val))
		{
			$val = new CumulativeCounts($this->data->WEATHERSTATISTICS->WINDRUNCOUNTS);
		}
		
		return $val;
	}

	// max average wind direction
	public function getMaxAverageWindDirection()
	{
		static $val;
		if (!isset($val))
		{
			$val = new PeriodicValues($this->data->WEATHERSTATISTICS->MAXAVERAGEWINDDIRECTION);
		}
		
		return $val;
	}
		
	// max gust wind direction
	public function getMaxGustWindDirection()
	{
		static $val;
		if (!isset($val))
		{
			$val = new PeriodicValues($this->data->WEATHERSTATISTICS->MAXGUSTWINDDIRECTION);
		}
		
		return $val;
	}

}
	
?>
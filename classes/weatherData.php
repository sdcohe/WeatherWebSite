<?php

require_once 'config/config.php';
require_once 'weatherConversions.php';

$cfg = config::getInstance();
date_default_timezone_set($cfg->getTimeZone());

class weatherData
{
	private $data;
	
	function __construct($xml="") 
	{
		libxml_use_internal_errors(true);
	
		if ($xml !== "")
		{
// 			print "Loading passed in XML";
			$this->data = $xml;
// 			print "Temp: $this->data->OUTDOORTEMPERATURE";
//  			print_r($this->data);
		}
		else
		{
			$retryCount = 3;
			
			while ((($this->data = simplexml_load_file('weatherlogs/weatherData.xml')) === false) && $retryCount > 0 )
			{
				// 	print "retrying ";
				$retryCount--;
				sleep(3);
			}
			
// 			print_r($this->data);
			
			if ($this->data !== false)
			{
//  				print "Before1: " . $this->data->DATETIME;
//  				print "Before: " . $this->data->WEATHERDATAENTRY->DATETIME;
 				$this->data = $this->data->WEATHERDATAENTRY;
//  				print_r($this->data);
//  				print " After1: " . $this->data->DATETIME;
//  				print " After: " . $this->data->WEATHERDATAENTRY->DATETIME;
			}
		}
		
	}
	
	public function getJSON()
	{
		$arr = array(	'RainCount' => $this->getRainCount(), 
						'OutdoorTemperature' => $this->getOutdoorTemperature(), 
						'DateTime' => date("c", $this->getDateTime()), 
						'OutdoorHumidity' => $this->getOutdoorHumidity(), 
						'Pressure' => $this->getPressure(), 
						'WindSpeed' => $this->getWindSpeed(), 
						'WindDir' => $this->getWindDir(), 
						'AvgWindSpeed' => $this->getAvgWindSpeed(), 
						'AvgWindDir' => $this->getAvgWindDir(), 
						'Solar' => $this->getSolar(), 
						'DewPoint' => $this->getDewPoint(), 
						'CloudBaseHeight' => $this->getCloudBaseHeight(), 
						'WetBulb' => $this->getWetBulb(), 
						'HeatIndex' => $this->getHeatIndex(), 
						'WindChill' => $this->getWindchill(), 
						'FeelsLike' => $this->getFeelslike()
		);

		return json_encode($arr);
	}

// 	public function setData($data)
// 	{
// 		$this->data = $data;
// 	}
	
	public function getRainCount()
	{
		return (float)$this->data->RAINFALL;
// 		return (float)$this->getField('//WEATHERDATAENTRY/RAINFALL');
	}
	
	public function getOutdoorTemperature()
	{
		return (float)$this->data->OUTDOORTEMPERATURE;
// 		return (float)$this->getField('//WEATHERDATAENTRY/OUTDOORTEMPERATURE');
	}

	public function getDateTime()
	{
		return strtotime($this->data->DATETIME);
// 		return strtotime($this->getField('//WEATHERDATAENTRY/DATETIME'));
	}

	public function getOutdoorHumidity()
	{
		return (float)$this->data->OUTDOORHUMIDITY;
// 		return (float)$this->getField('//WEATHERDATAENTRY/OUTDOORHUMIDITY');
	}

	public function getPressure()
	{
		return (float)$this->data->PRESSURE;
// 		return (float)$this->getField('//WEATHERDATAENTRY/PRESSURE');
	}

	public function getWindSpeed()
	{
		return (float)$this->data->WINDSPEED;
// 		return (float)$this->getField('//WEATHERDATAENTRY/WINDSPEED');
	}
	
	public function getWindDir()
	{
		return (float)$this->data->WINDDIRECTION;
// 		return (float)$this->getField('//WEATHERDATAENTRY/WINDDIRECTION');
	}
	
	public function getAvgWindSpeed()
	{
		return (float)$this->data->AVERAGEWINDSPEED;
// 		return (float)$this->getField('//WEATHERDATAENTRY/AVERAGEWINDSPEED');
		
	}
	
	public function getAvgWindDir()
	{
		return (float)$this->data->AVERAGEWINDDIRECTION;
// 		return (float)$this->getField('//WEATHERDATAENTRY/AVERAGEWINDDIRECTION');
	}
	
	public function getSolar()
	{
		return (float)$this->data->SOLAR;
// 		return (float)$this->getField('//WEATHERDATAENTRY/SOLAR');
	}
	
	public function getDewPoint()
	{
		return calcDewpointFahrenheit($this->getOutdoorTemperature(), $this->getOutdoorHumidity());
	}
	
	public function getCloudBaseHeight()
	{
		return estimateCloudBaseHeightFahrenheit($this->getOutdoorTemperature(), $this->getDewPoint());		
	}
	
	public function getWetBulb()
	{
		return calcWetBulbF($this->getOutdoorTemperature(), $this->getOutdoorHumidity());
	}
	
	public function getHeatIndex()
	{
		return calcHeatIndexFahrenheit($this->getOutdoorTemperature(), $this->getOutdoorHumidity());
	}
	
	public function getWindchill()
	{
		return calcWindChillFahrenheit($this->getOutdoorTemperature(), $this->getAvgWindSpeed());
	}
	
	public function getFeelslike()
	{
		if ($this->getOutdoorTemperature() > 50.0)
		{
			return (float)$this->getHeatIndex();
		}
		else
		{
			return (float)$this->getWindchill();
		}
	}
	
	private function getField($field)
	{
		if ($this->data === false)
		{
			return "---";
		}
		else 
		{
			$retval = ($this->data->xpath($field));
			return $retval[0];
		}
	}
}

?>
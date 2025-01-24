<?php

class ForecastEntry
{
	public $timePeriodName;
	public $timePeriodRange;
	public $pop;
	public $iconURL;
	public $weatherType;
	public $wordedForecast;
	public $minMaxType;
	public $minMax;

	public function __construct()
	{
	}
	
	public function setTimePeriodName($data)
	{
		$this->timePeriodName = $data;
	}
	
	public function setTimePeriodRange($data)
	{
		$this->timePeriodRange = $data;
	}
	
	public function setPop($data)
	{
		$this->pop = $data;
	}
	
	public function setIconURL($data)
	{
		$this->iconURL = $data;
	}
	
	public function setWeatherType($data)
	{
		$this->weatherType = $data;
	}
	
	public function setWordedForecast($data)
	{
		$this->wordedForecast = $data;
	}
	
	public function setMinMaxType($data)
	{
		$this->minMaxType = $data;
	}
	
	public function setMinMax($data)
	{
		$this->minMax = $data;
	}

	
	public function getTimePeriodName()
	{
		return $this->timePeriodName;
	}
	
	public function getTimePeriodRange()
	{
		return $this->timePeriodRange;
	}
	
	public function getPop()
	{
		return $this->pop;
	}
	
	public function getIconURL()
	{
		return $this->iconURL;
	}
	
	public function getWeatherType()
	{
		return $this->weatherType;
	}
	
	public function getWordedForecast()
	{
		return $this->wordedForecast;
	}
	
	public function getMinMaxType()
	{
		return $this->minMaxType;
	}
	
	public function getMinMax()
	{
		return $this->minMax;
	}
		
}

?>
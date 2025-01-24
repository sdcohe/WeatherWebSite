<?php

class PeriodicValues
{
	private $xmlNode;
	
	public function __construct($xml)
	{
		$this->xmlNode = $xml;
	}
	
	public function getJSON()
	{
		$arr = array(	'HourValue' => $this->getHourValue(),
						'DayValue' => $this->getDayValue(), 
						'YesterdatValue' => $this->getYesterdayValue(), 
						'WeekValue' => $this->getWeekValue(), 
						'MonthValue' => $this->getMonthValue(), 
						'YearValue' => $this->getYearValue(), 
						'CumulativeValue' => $this->getCumulativeValue() 
		);
		
		return json_encode($arr);
		
	}
	// hourly
	public function getHourValue()
	{
		return (float)$this->xmlNode->HOURLY;
	}

	// daily
	public function getDayValue()
	{
		return (float)$this->xmlNode->DAILY;
	}

	// yesterday
	public function getYesterdayValue()
	{
		return (float)$this->xmlNode->YESTERDAY;
	}

	// weekly
	public function getWeekValue()
	{
		return (float)$this->xmlNode->WEEKLY;
	}

	// monthly
	public function getMonthValue()
	{
		return (float)$this->xmlNode->MONTHLY;
	}
			
	// year
	public function getYearValue()
	{
		return (float)$this->xmlNode->ANNUAL;
	}
	
	// cum
	public function getCumulativeValue()
	{
		return (float)$this->xmlNode->CUM;
	}
	
}
?>
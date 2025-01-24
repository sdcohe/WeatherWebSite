<?php

require_once 'classes/minMax.php';

class CumulativeValues
{
	// cum values
	private $m_cumValues;
	
	// annual values
	private $m_annualValues;
	
	// monthly values
	private $m_monthlyValues;
	
	// weekly values
	private $m_weeklyValues;
	
	// daily values
	private $m_dailyValues;
	
	// yesterday's values
	private $m_yesterdayValues;
	
	// hourly values
	private $m_hourlyValues;

	// XML representation of data
	private $xmlNode;
	
	public function __construct($xml)
	{
		$this->xmlNode = $xml;
// 		print_r($xml);
	}
	
	public function getJSON()
	{
		$arr = array(
			'HourlyValues' => json_decode($this->getHourlyValues()->getJSON()),
			'DailyValues' => json_decode($this->getDailyValues()->getJSON()),
			'YesterdayValues' => json_decode($this->getYesterdayValues()->getJSON()),
			'WeekylValues' => json_decode($this->getWeeklyValues()->getJSON()),
			'MonthlyValues' => json_decode($this->getMonthlyValues()->getJSON()),
			'AnnualValues' => json_decode($this->getAnnualValues()->getJSON()),
			'CumulativeValues' => json_decode($this->getCumulativeValues()->getJSON())
		);
		
		return json_encode($arr);
	}
	
	public function getHourlyValues()
	{
		$val = new MinMax();
		$val->fromXML($this->xmlNode->HOURLY);
		return $val;
// 		return new MinMax($this->xmlNode->HOURLY);  
	}
	
	public function getDailyValues()
	{
		$val = new MinMax();
		$val->fromXML($this->xmlNode->DAILY);
		return $val;
// 		return new MinMax($this->xmlNode->DAILY);
	}
	
	public function getYesterdayValues()
	{
		$val = new MinMax();
		$val->fromXML($this->xmlNode->YESTERDAY);
		return $val;
// 		return new MinMax($this->xmlNode->YESTERDAY);
	}
	
	public function getWeeklyValues()
	{
		$val = new MinMax();
		$val->fromXML($this->xmlNode->WEEKLY);
		return $val;
// 		return new MinMax($this->xmlNode->WEEKLY);
	}
	
	public function getAnnualValues()
	{
		$val = new MinMax();
		$val->fromXML($this->xmlNode->ANNUAL);
		return $val;
// 		return new MinMax($this->xmlNode->ANNUAL);
	}
	
	public function getMonthlyValues()
	{
		$val = new MinMax();
		$val->fromXML($this->xmlNode->MONTHLY);
		return $val;
// 		return new MinMax($this->xmlNode->MONTHLY);
	}
	
	public function getCumulativeValues()
	{
		$val = new MinMax();
		$val->fromXML($this->xmlNode->CUM);
		return $val;
// 		return new MinMax($this->xmlNode->CUM);
	}
	
}

?>
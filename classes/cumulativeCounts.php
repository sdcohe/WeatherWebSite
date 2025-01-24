<?php

require_once 'classes/countValue.php';
require_once 'classes/minMax.php';

class CumulativeCounts
{
	private $xmlNode;

	private $lastChangeDate;
	private $lastSampleDate;
	private $lastSampleValue;
	
	private $minuteStartCounts;
	private $hourStartCounts;
	private $maxRateHours;
	
	public function __construct($xml)
	{
		$this->xmlNode = $xml;
		$this->initialize();
	}
	
	public function getJSON()
	{
		$arr = array(
			'LastChangeDate' => date("c", $this->getLastChangeDate()),
			'LastSampleDate' => date("c", $this->getLastSampleDate()),
			'LastSampleValue' =>$this->getLastSampleValue(),
			'daysWithNoChange' => $this->daysWithNoChange(),
			'DayStartCount' => json_decode($this->getDayStartCount()->getJSON()),
			'YesterdayStartCount' => json_decode($this->getYesterdayStartCount()->getJSON()),
			'WeekStartCount' => json_decode($this->getWeekStartCount()->getJSON()),
			'MonthStartCount' => json_decode($this->getMonthStartCount()->getJSON()),
			'YearStartCount' => json_decode($this->getYearStartCount()->getJSON()),
			'CumulativeStartCount' => json_decode($this->getCumulativeStartCount()->getJSON()),
			'CountChangeDaysThisWeek' => $this->getCountChangeDaysThisWeek(),
			'CountChangeDaysThisMonth' => $this->getCountChangeDaysThisMonth(),
			'CountChangeDaysThisYear' => $this->getCountChangeDaysThisYear(),
			'CountChangeDaysCumulative' => $this->getCountChangeDaysCumulative(),
			'CurrentRatePerMinute' => $this->getCurrentRatePerMinute(),
			'CumulativeCount' => $this->getCumulativeCount(),
			'YearCount' => $this->getYearCount(),
			'MonthCount' => $this->getMonthCount(),
			'WeekCount' => $this->getWeekCount(),
			'DayCount' => $this->getDayCount(),
			'YesterdayCount' => $this->getYesterdayCount(),
			'MaxRateLastHour' => json_decode($this->getMaxRateLastHour()->getJSON()),
			'HighestDailyValueThisMonth' => json_decode($this->getHighestDailyValueThisMonth()->getJSON()),
			'HighestDailyValueThisWeek' => json_decode($this->getHighestDailyValueThisWeek()->getJSON()),
			'HighestDailyValueThisYear' => json_decode($this->getHighestDailyValueThisYear()->getJSON()),
			'HighestDailyValueCumulative' => json_decode($this->getHighestDailyValueCumulative()->getJSON()),
			'MaxRateToday' => json_decode($this->getMaxRateToday()->getJSON()),
			'MaxRateYesterday' => json_decode($this->getMaxRateYesterday()->getJSON()),
			'MaxRateThisWeek' => json_decode($this->getMaxRateThisWeek()->getJSON()),
			'MaxRateThisMonth' => json_decode($this->getMaxRateThisMonth()->getJSON()),
			'MaxRateThisYear' => json_decode($this->getMaxRateThisYear()->getJSON()),
			'MaxRateCumulative' => json_decode($this->getMaxRateCumulative()->getJSON()),
			'MinuteStartCounts' => $this->countValueArrayToJSON($this->minuteStartCounts),
			'HourStartCounts' => $this->countValueArrayToJSON($this->hourStartCounts),
			'MaxRateHours' => $this->minMaxArrayToJSON($this->maxRateHours),
		);
		
		return json_encode($arr);
	}
	
	private function minMaxArrayToJSON($arr)
	{
		$result = array();
		$idx = 0;
		
		foreach($arr as $item)
		{
			$val = array(	
				'Min' => $item->getMin(),
				'MinDate' => date("c", $item->getMinDate()), 
				'Max' => $item->getMax(), 
				'MaxDate' => date("c", $item->getMaxDate())
			);
			
			$result[$idx] = $val;
			$idx++;
		}
		
		return $result;
	}
	
	// array for countValues and array of minMax
	private function countValueArrayToJSON($arr)
	{
		$result = array();
		$idx = 0;
// 		print "\nItem count: " . count($arr) . "<br />\n";
		
		foreach($arr as $item)
		{
			$val = array(	'CountValue' => $item->getCountValue(),
							'CountTime' => date("c", $item->getCountTime())
			);
			$result[$idx] = $val;
			$idx++;
		}
		
// 		print "\nJSON encoded: " .  json_encode($result) . "<br />";
		return $result;
	}
	
	private function initialize()
	{
		$this->lastChangeDate = strtotime($this->xmlNode->LASTCHANGEDATE);
		$this->lastSampleDate = strtotime($this->xmlNode->LASTSAMPLEDATE);
		$this->lastSampleValue = (float)$this->xmlNode->LASTSAMPLEVALUE;

		// get minute start counts
		$minuteCounts = $this->xmlNode->xpath("*[starts-with(name(),'MINUTESTART')]");
		foreach($minuteCounts as $count)
		{
			$countValue = new CountValues($count->VALUE, $count->DATE);
			$this->minuteStartCounts[] = $countValue;
		}
		
		// get hour start counts
		$hourCounts = $this->xmlNode->xpath("*[starts-with(name(),'HOURSTART')]");
		foreach($hourCounts as $count)
		{
			$countValue = new CountValues($count->VALUE, $count->DATE);
			$this->hourStartCounts[] = $countValue;
		}
		
		// get max rates/hour
		$rates = $this->xmlNode->xpath("*[starts-with(name(),'MAXRATEHOUR')]");
		foreach($rates as $count)
		{
			$minmax = new MinMax();
			$minmax->fromXML($count);
			$this->maxRateHours[] = $minmax;
		}
		
	}
	
	// last change date
	public function getLastChangeDate()
	{
		return $this->lastChangeDate;
	}
	
	// last sample date
	public function getLastSampleDate()
	{
		return $this->lastSampleDate;
	}
	
	// last sample value
	public function getLastSampleValue()
	{
		return $this->lastSampleValue;
	}

	public function daysWithNoChange()
	{
		// difference in seconds
		$difference = $this->lastSampleDate - $this->lastChangeDate;
	
		return (int)($difference / 60 / 60 / 24);
	}

	// day value and time
	public function getDayStartCount()
	{
		$node = $this->xmlNode->DAYCOUNT;
// 		print "Day start count: " . $node->VALUE . " " . $node->DATE . "<br />";"
		return new CountValues((float)$node->VALUE, $node->DATE);
	}
	
	// yesterday value and time
	public function getYesterdayStartCount()
	{
		$node = $this->xmlNode->YESTERDAYCOUNT;
		return new CountValues((float)$node->VALUE, $node->DATE);
	}

	// week value and time
	public function getWeekStartCount()
	{
		$node = $this->xmlNode->WEEKCOUNT;
		return new CountValues((float)$node->VALUE, $node->DATE);
	}
	
	// month value and time
	public function getMonthStartCount()
	{
		$node = $this->xmlNode->MONTHCOUNT;
		return new CountValues((float)$node->VALUE, $node->DATE);
	}
	
	// year value and time
	public function getYearStartCount()
	{
		$node = $this->xmlNode->YEARCOUNT;
		return new CountValues((float)$node->VALUE, $node->DATE);
	}
	
	// cum value and time
	public function getCumulativeStartCount()
	{
		$node = $this->xmlNode->CUMCOUNT;
		return new CountValues((float)$node->VALUE, $node->DATE);
	}
	
	
	// minutes[0-59] value and time
	// hours[0-23] value and time
	
	// month max
	public function getHighestDailyValueThisMonth()
	{
		$minMax = new MinMax();
		$minMax->fromXML($this->xmlNode->MONTHMAXCOUNT);
		return $minMax;
// 		return new MinMax($this->xmlNode->MONTHMAXCOUNT);
	}
	
	// week max
	public function getHighestDailyValueThisWeek()
	{
		$minMax = new MinMax();
		$minMax->fromXML($this->xmlNode->WEEKMAXCOUNT);
		return $minMax;
// 		return new MinMax($this->xmlNode->WEEKMAXCOUNT);
	}
	
	// year max
	public function getHighestDailyValueThisYear()
	{
		$minMax = new MinMax();
		$minMax->fromXML($this->xmlNode->YEARMAXCOUNT);
		return $minMax;
// 		return new MinMax($this->xmlNode->YEARMAXCOUNT);
	}
	
	// cum max
	public function getHighestDailyValueCumulative()
	{
		$minMax = new MinMax();
		$minMax->fromXML($this->xmlNode->CUMMAXCOUNT);
		return $minMax;
// 		return new MinMax($this->xmlNode->CUMMAXCOUNT);
	}
	
	// max rates hour[0-23]
	
	// max rate today
	public function getMaxRateToday() 
	{
		$minMax = new MinMax();
		$minMax->fromXML($this->xmlNode->MAXRATETODAY);
		return $minMax;
// 		return new MinMax($this->xmlNode->MAXRATETODAY);
	}
		
	// max rate yesterday
	public function getMaxRateYesterday() 
	{
		$minMax = new MinMax();
		$minMax->fromXML($this->xmlNode->MAXRATEYESTERDAY);
		return $minMax;
// 		return new MinMax($this->xmlNode->MAXRATEYESTERDAY);
	}
		
	// max rate week
	public function getMaxRateThisWeek() 
	{
		$minMax = new MinMax();
		$minMax->fromXML($this->xmlNode->MAXRATETHISWEEK);
		return $minMax;
// 		return new MinMax($this->xmlNode->MAXRATETHISWEEK);
	}
	
	// max rate month
	public function getMaxRateThisMonth() 
	{
		$minMax = new MinMax();
		$minMax->fromXML($this->xmlNode->MAXRATETHISMONTH);
		return $minMax;
// 		return new MinMax($this->xmlNode->MAXRATETHISMONTH);
	}
	
	// max rate year
	public function getMaxRateThisYear() 
	{
		$minMax = new MinMax();
		$minMax->fromXML($this->xmlNode->MAXRATETHISYEAR);
		return $minMax;
// 		return new MinMax($this->xmlNode->MAXRATETHISYEAR);
	}
	
	// max rate cum
	public function getMaxRateCumulative() 
	{
		$minMax = new MinMax();
		$minMax->fromXML($this->xmlNode->MAXRATECUM);
		return $minMax;
// 		return new MinMax($this->xmlNode->MAXRATECUM);
	}
	
	// count change week
	public function getCountChangeDaysThisWeek() 
	{
		return (float)$this->xmlNode->COUNTCHANGEWEEK;
	}
	
	// count change month
	public function getCountChangeDaysThisMonth() 
	{
		return (float)$this->xmlNode->COUNTCHANGEMONTH;
	}
	
	// count change year
	public function getCountChangeDaysThisYear() 
	{
		return (float)$this->xmlNode->COUNTCHANGEYEAR;
	}
	
	// count change cum
	public function getCountChangeDaysCumulative() 
	{
		return (float)$this->xmlNode->COUNTCHANGECUM;
	}
	
	private function getCountValue($startValue)
	{
		$value = 0.0;
		
		if ($this->lastSampleValue != 1.4E-45 && $startValue != 1.4E-45)
		{
			$value = $this->lastSampleValue - $startValue;
		}
		
		return $value;
	}
	
	public function getCurrentRatePerMinute()
	{
		return $this->getAverageRatePerMinute(1);
	}
	
	public function getAverageRatePerMinute($numberOfMinutes)
	{
		$countValue = $this->getMinuteCount($numberOfMinutes);
		$rate = 0.0;
	
		if ($countValue !=  1.4E-45)
		{
			$rate = $countValue / (int)$numberOfMinutes;
		}
	
		return $rate;
	}
	
// 	public function getHourlyAverageRatePerMinute()
// 	{
// 		return $this->getHourlyAverageRatePerMinute(1);
// 	}
	
	public function getHourlyAverageRatePerMinute($numberOfHours = 1)
	{
		$countValue = $this->getHourCount($numberOfHours);
		$rate = 0.0;
	
		if ($countValue != 1.4E-45)
		{
			$rate = $countValue / ($numberOfHours * 60.0);
		}
	
		return $rate;
	}
	
	public function getCumulativeCount()
	{
		$cumStart = $this->getCumulativeStartCount();
		return $this->getCountValue($cumStart->getCountValue());
	}
	
	public function getYearCount()
	{
		$start = $this->getYearStartCount();
		return $this->getCountValue($start->getCountValue());
	}
	
	public function getMonthCount()
	{
		$start = $this->getMonthStartCount();
		return $this->getCountValue($start->getCountValue());
	}
	
	public function getWeekCount()
	{
		$start = $this->getWeekStartCount();
		return $this->getCountValue($start->getCountValue());
	}
	
	public function getDayCount()
	{
		$start = $this->getDayStartCount();
		return $this->getCountValue($start->getCountValue());
	}
	
	public function getYesterdayCount()
	{
		$value = 1.4E-45;
		$dayStartCount = $this->getDayStartCount();
		$yesterdayStartCount = $this->getYesterdayStartCount();
		
		if ($dayStartCount->getCountValue() != 1.4E-45 && $yesterdayStartCount->getCountValue() != 1.4E-45)
		{
			$value = $dayStartCount->getCountValue() - $yesterdayStartCount->getCountValue();
		}
		return $value;
	}
	
// 	public float getMinuteCount()
// 	{
// 		return getMinuteCount(1);
// 	}
	
	public function getMinuteCount($numberOfMinutes = 1)
	{
		$currentIndex = -1;
		$priorIndex = -1;
		$value = 0.0;
	
		if ($numberOfMinutes <= 60 && $numberOfMinutes > 0)
		{
			// get minute from last sample date
			$currentIndex = (int)date("i", $this->lastSampleDate);
			$priorIndex = $currentIndex - (int)$numberOfMinutes + 1;
			if ($priorIndex < 0)
			{
				$priorIndex += count($this->minuteStartCounts);
			}
	
			$currentValue = (float)$this->lastSampleValue;
			$priorValue = (float)$this->minuteStartCounts[$priorIndex]->getCountValue();
			if ($currentValue != 1.4E-45 && $priorValue != 1.4E-45)
			{
				$value = $currentValue - $priorValue;
			}
// 			print "Current value: $currentValue Prior Value: $priorValue Return $value <br />";
		}
	
		return $value;
	}
	
// 	public float getHourCount()
// 	{
// 		return getHourCount(1);
// 	}
	
	public function getHourCount($numberOfHours = 1)
	{
		$currentIndex;
		$priorIndex;
	
		$value = 0.0;
	
		if ($numberOfHours <= 24 && $numberOfHours > 0)
		{
			// set current index to current hour if the day
			$currentIndex = (int)date("G", $this->lastSampleDate);
			$priorIndex = $currentIndex - ($numberOfHours - 1);
			if ($priorIndex < 0)
			{
				$priorIndex += count($this->hourStartCounts);
			}
	
			$currentValue = $this->lastSampleValue;
			$priorValue = $this->hourStartCounts[$priorIndex]->getCountValue();
	
			while ($priorValue == 1.4E-45 && $priorIndex != $currentIndex)
			{
				$priorIndex++;
				$priorIndex %= count($this->hourStartCounts);
				$priorValue = $this->hourStartCounts[$priorIndex]->getCountValue();
			}
	
			if ($currentValue != 1.4E-45)
			{
				$value = (float)$currentValue - (float)$priorValue;
			}
		}
	
		return $value;
	}
	
	public function getMaxRateLastHour()
	{
		$hour = (int)date("G", $this->lastSampleDate);
		return $this->maxRateHours[$hour];
	}
	
	public function getMaxRateHour($numberOfHours)
	{
		$value = new MinMax();
	
		if ($numberOfHours > 0 && $numberOfHours <= count($this->maxRateHours))
		{
			$hour = (int)date("G", $this->lastSampleDate);
			$index = $hour;
			$i = 0;
	
			do
			{
				$value->updateValues($this->maxRateHours[$index]->getMax(), $this->maxRateHours[$index]->getMaxDate());
				$index--;
				if ($index < 0) $index += count($this->maxRateHours);
				$i++;
	
			}
			while ($i < $numberOfHours - 1);
	
		}
	
		return $value;
	}
	
}

?>
<?php

class MinMax
{
	private $m_minValue;
	private $m_maxValue;
	private $m_minDate;
	private $m_maxDate;
	
	public function __construct()
	{
		$this->m_maxValue =  1.4E-45;
		$this->m_minValue = 3.4028235E38;
	}

	public function fromXML($xml)
	{
		$this->m_minValue = $xml->MIN['value'];
		$this->m_minDate = $xml->MIN['date'];
		$this->m_maxValue = $xml->MAX['value'];
		$this->m_maxDate = $xml->MAX['date'];
	}
	
	public function getJSON()
	{
		$arr = array(	'Min' => $this->getMin(),
						'MinDate' => date("c", $this->getMinDate()), 
						'Max' => $this->getMax(), 
						'MaxDate' => date("c", $this->getMaxDate())
		);
		
		return json_encode($arr);
		
	}
	
	public function getMin()
	{
		return (float)$this->m_minValue;
	}

	public function getMinDate()
	{
		return strtotime($this->m_minDate);
	}

	public function getMax()
	{
		return (float)$this->m_maxValue;
	}

	public function getMaxDate()
	{
		return strtotime($this->m_maxDate);
	}
	
	public function updateValues($value, $date)
	{
		if ($value >$this-> m_maxValue || $this->m_maxValue ==  1.4E-45

		)
		{
			$this->m_maxValue = $value;
			$this->m_maxDate = $date;
		}
	
		if ((($value != 1.4E-45) && ($value < $this->m_minValue)) || $this->m_minValue == 3.4028235E38)
		{
			$this->m_minValue = $value;
			$this->m_minDate = $date;
		}
	}
	
}

?>
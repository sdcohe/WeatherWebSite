<?php

class CountValues
{
	private $countValue;
	private $countTime;
	
	public function __construct($value, $time)
	{
		$this->countValue = $value;
		$this->countTime = $time;
	}
	
	public function getJSON()
	{
		$arr = array(	'CountValue' => $this->getCountValue(),
						'CountTime' => date("c", $this->getCountTime()));
		
		return json_encode($arr);
		
	}
	
	public function getCountValue()
	{
		return (float)$this->countValue;
	}
	
	public function getCountTime()
	{
		return strtotime($this->countTime);
	}
	
}
?>
<?php

class LocalAlert
{
	public $title;
	public $description;
	public $date;
	public $link;
	
	public function __construct($data)
	{
		// parse out advisory data
		$this->title = trim($data->title);
		$this->link = urldecode($data->link);
		$this->date = date("c", strtotime($data->pubDate));
		$this->description = trim($data->description);
		
	}
	
	public function getJSON()
	{
		$arr = array(	
				'Title' => $this->getTitle(),
				'Description' => $this->getDescription(),
				'Date' => $this->getDate());
		
		return json_encode($arr);
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function getLink()
	{
		return $this->link;
	}
	
	public function getDescription()
	{
		return $this->description;
	}
	
	public function getDate()
	{
		return strtotime($this->date);
	}
}

?>
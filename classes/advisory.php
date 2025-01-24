<?php

class NWSAdvisory
{
	public $title;
	public $summary;
	public $description;
	public $instruction;
	public $date;

	private $link;
	
	// public function __construct($title, $summary, $link)
	public function __construct($data)
	{
		// parse out advisory data
		$this->title = trim($data->title);
		$this->summary = trim($data->summary);
		$this->link = urldecode($data->link[0]["href"]);;
		$this->date = date("c", strtotime($data->updated));
		
		// now retrieve any details based on the link provided 
		// retrieve a new copy of the file from the NWS
		$contents = file_get_contents($this->link);
		
		// validate the XML and parse it
		$xml = simplexml_load_string($contents);
		if (!$xml)
		{
			// XML wasn't valid so set defaults
			$description = "";
			$instructions = "";
		}
		else
		{
			// Good XML.  Try to retrieve the fields we want
			$this->description = (string)$xml->info->description;
			$this->instruction = (string)$xml->info->instruction;
		}

//		print_r($this->getDescription());
//		print_r($this->getInstruction());
		
		// for testing only.  Check to see what we got from the XML
//		file_put_contents("test.txt", "Summary type is " . gettype($this->getSummary()) . "-Description:(" . gettype($this->getDescription()) . "):" . $this->getDescription() . "\r\n" . $this->getInstruction());
	}
	
	public function getJSON()
	{
		$arr = array(	
				'Title' => $this->getTitle(),
				'Summary' => $this->getSummary(),
				'Description' => $this->getDescription(),
				'Instruction' => $this->getInstruction(),
//				'Date' => date("c", $this->getDate()));
				'Date' => $this->getDate());
		
		return json_encode($arr);
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function getSummary()
	{
		return $this->summary;
	}
	
	public function getLink()
	{
		return $this->link;
	}
	
	public function getDescription()
	{
		return $this->description;
	}
	
	public function getInstruction()
	{
		return $this->instruction;
	}
	
	public function getDate()
	{
		return strtotime($this->date);
	}
}

?>
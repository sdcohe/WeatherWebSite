<?php

require_once "config/config.php";
require_once "classes/discussion.php";

// file and url information
class NWSDiscussion
{
	private $cfg;
	
	private $url = "http://forecast.weather.gov/product.php?site=NWS&issuedby=LWX&product=AFD&format=txt&version=1&glossary=0";
	private $file = 'weatherlogs/discussion.html';
	
	private $discussionHTML;
	private $discussionList = array();
	private $pubDate;
	
	function __construct()
	{
		$this->cfg = config::getInstance();
			
		if (file_exists($this->file) === false || time() - filemtime($this->file) > $this->cfg->getDiscussionStaleTime() || filesize($this->file) == 0)
		{
			// retrieve a new copy of the file
			ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)');
			$contents = file_get_contents($this->url);
			file_put_contents($this->file, $contents);
		}
	
		libxml_use_internal_errors(true);
	
		if (file_exists($this->file) && $this->discussionHTML = file_get_contents($this->file))
		{
			$this->parseFile();
		}
	}
	
	public function getJSON()
	{
		$arr = array(
				'DiscussionPublishDate' => date("c", $this->getPublishDate()),
				'DiscussionList' => $this->getDiscussionList(),
		);
		
		return json_encode($arr);
		
	}
	
	private function parseFile()
	{
// 		print "parsing file";
		// parse out date on the 7th line
		preg_match("'<pre .*?>(.*?)</pre>'si", $this->discussionHTML, $text);
// 		print_r($text);
		// 	$text = preg_replace("/<pre ([\s\S]*) pre>/", '$1', $html);
		$text = $text[0];
		$convert = explode("\n", $text);
		$discussiondate = $convert[7];
		$discussiondate = preg_replace("/(^[0-9]+)([0-9]{2}) ([A-Za-z]{2} [A-Za-z]{3}) (.*)/", '$4 $1:$2 $3', $discussiondate);
		$this->pubDate = strtotime($discussiondate);
// 		$discussiondate = date("D M d, Y H:i:s T", $discussiondate);
		
		preg_match_all("/^\.[\s\S]*?\&\&$/m", $this->discussionHTML, $discussions);
		
		// 	print "Matches found: " . count($matches[0]) . "\n";
		
		$i = 0;
		foreach($discussions[0] as $discussion)
		{
			if (strlen($discussion) > 0)
			{
				$match = strtolower($discussion);

				$heading = preg_replace_callback("/^\.(.+)[\.]{3}[\s\S]*[\&]{2}/",  
				    function($matches){ return strtoupper($matches[1]);}, 
				    $discussion);
				$contents = preg_replace_callback("/^\..+[\.]{3}([\s\S]*)[\&]{2}/", 
				    function($matches){
                        $text = trim($matches[1]);
                        $text = $this->fixSentences($text);
                        $text = ucfirst($text); 
				        return $text;
				    }, 
				    $discussion);
				
				// $match = preg_replace("/^\./", "", $match);			// remove leading period on first line
				// $match = preg_replace("/[\.]{3}/", ": ", $match);	// remove "..." on first line
				// $match = preg_replace("/[\&]{2}/", "", $match);		// remove trailing "&&"
				// $match = preg_replace("/$\n/m", " ", $match);		// remove newlines
				// $this->discussionList[$i++] = $match;
				$this->discussionList[$i++] = new discussion($heading, $contents);
				// 	print "This is a match: $match\n";
                // 	print "Heading: " . $heading . " Contents: " . $contents . "\r\n";
			}
		}
		
	}
	
	private function fixSentences($text)
	{
	    $text = preg_replace_callback('/(monday|tuesday|wednesday|thursday|friday|saturday|sunday)/', 
	        function($matches) {
	            return ucfirst($matches[0]);
	        },
	        $text);
	    $text = preg_replace_callback('/(northern|central|maryland|virginia|new england|ohio|north|south|east|west|thanksgiving)/', 
	        function($matches) {
	            return ucfirst($matches[0]);
	        }, 
	        $text);
 	    $text = preg_replace_callback('/[.!?].*?\w/', 
 	        function($matches) {
 	            return strtoupper($matches[0]);
 	        },
 	        $text);
	    
	    return $text;
	}
	
	private function strtotitle($title) // Converts $title to Title Case, and returns the result. 
	{ 
	   // Our array of 'small words' which shouldn't be capitalised if 
	   // they aren't the first word. Add your own words to taste. 
	   $smallwordsarray = array( 'of','a','the','and','an','or','nor','but','is','if','then','else',
	       'when', 'at','from','by','on','off','for','in','out','over','to','into','with' );
	   
	   // Split the string into separate words 
	   $words = explode(' ', $title);
	   
	   foreach ($words as $key => $word) 
	   { 
	       // If this word is the first, or it's not one of our small words, capitalise it 
	       // with ucwords(). 
	       if ($key == 0 or !in_array($word, $smallwordsarray)) 
	           $words[$key] = ucwords($word); 
	   }
	   
	   // Join the words back into a string 
	   $newtitle = implode(' ', $words);
	   
	   return $newtitle;
    }
	
	public function getPublishDate()
	{
		return $this->pubDate;
	}
	
	public function getDiscussionList()
	{
		return $this->discussionList;
	}
	
}

?>
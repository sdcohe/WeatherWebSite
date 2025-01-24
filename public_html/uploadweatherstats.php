<?php

require_once 'config/config.php';
require_once "classes/weatherStatistics.php";

$cfg = config::getInstance();
date_default_timezone_set($cfg->getTimeZone());

// get the name and size of the file that was uploaded
// print_r($_FILES);

$fileName = $_FILES['file']['name'];
$fileSize = $_FILES['file']['size'];
$tempFileName = $_FILES['file']['tmp_name'];
//print_r($tempFileName);

$destDir = $cfg->getDataDir();
$dataFileName = 'weatherStats.xml';

// check fiule was uploaded by HTTP POST
if (is_uploaded_file($tempFileName)) 
{
	// validate data
	$data = simplexml_load_string(file_get_contents($tempFileName));
//	print_r($data);
//	print_r($data->WEATHERDATAENTRY);
	
	$wxStats = new weatherStatistics($data);
	$dataDate = date($cfg->getShortDateTimeFormat(), $wxStats->getLastSampleDate());	
	 
	// move data
	if (move_uploaded_file($tempFileName, "$destDir/$dataFileName"))
	{
		print("Stored $dataFileName containing stats from $dataDate\n");
	} 
	else 
	{
		// problem unable to move file
		print("Error storing $dataFileName\n");
	}
} 
else 
{
	// problem, file wasn't uploaded by HTTP POST
		print("Error $dataFileName not from POST\n");
}

?>

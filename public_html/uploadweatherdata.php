<?php
// generic page top allow uploading weather data to this website.  Current client upload script
// uses to curl to upload teh file as follows:
// curl -v -F"operation=upload" -F"file=@weatherLog.xml" http://www.cloppermillweather.org/uploadweatherdata.php
// This upload page currently will only accept files with 3 names.  It also makes a feeble attempt to ensure that the 
// file contents is correct by parsing the XML for a date field and then compares that date with the
// current date. 
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header("Cache-Control: no-cache, must-revalidate" );
header("Pragma: no-cache" );
header("Content-Type: text/plain");

require_once 'config/config.php';
require_once "classes/weatherData.php";
require_once "classes/weatherStatistics.php";

$cfg = config::getInstance();
date_default_timezone_set($cfg->getTimeZone());

// get the name and size of the file that was uploaded
// print_r($_FILES);

$fileName = $_FILES['file']['name'];
$fileSize = $_FILES['file']['size'];
$tempFileName = $_FILES['file']['tmp_name'];

$destDir = $cfg->getDataDir();
$dataFileName = $fileName;

// check file was uploaded by HTTP POST
if (is_uploaded_file($tempFileName)) 
{
	// load XML data
	$data = simplexml_load_string(file_get_contents($tempFileName));
//	print_r($data);
//	print_r($data->WEATHERDATAENTRY);
	
	// only permit specific files to be uploaded
	switch ($fileName)
	{
		case "weatherData.xml":
			// instantiate class and get a value 
			// print(" checking $fileName");
			$wxData = new weatherData($data->WEATHERDATAENTRY);
			$dataDate = $wxData->getDateTime();
			break;
		case "weatherStats.xml":
			// print(" checking $fileName");
			// instantiate class and get a value 
			$wxStats = new weatherStatistics($data);
			$dataDate = $wxStats->getLastSampleDate();	
			break;
		case "weatherLog.xml":
			// print(" checking $fileName");
			// get each entry into an array and check the date of the last entry
			$entries = $data->xpath("//WEATHERDATAENTRY");
			$dataDate = strtotime($entries[count($entries)-1]->DATETIME);
			break;
	}
	
	// check if date is valid and exit if not 
	if (isValidDate($dataDate))
	{
		// move data
		if (move_uploaded_file($tempFileName, "$destDir/$dataFileName"))
		{
			$displayDate = date($cfg->getShortDateTimeFormat(), $dataDate);
			print("Stored $dataFileName containing data from $displayDate\n");
		}
		else
		{
			// problem unable to move file
			print("Error storing $dataFileName\n");
		}
	}
	else
	{
		print("Invalid data in file $dataFileName from $dataDate\n");
	}
} 
else 
{
	// problem, file wasn't uploaded by HTTP POST
	print("Error $dataFileName not from POST\n");
}

// perform simplisitc date validity checking
function isValidDate($dateToCheck)
{
//	print(" checking $dateToCheck ");
	
	$todayMon = date('m');
	$todayDay = date('d');
	$todayYear = date('Y');

	$dataMon = date('m', $dateToCheck);
	$dataDay = date('d', $dateToCheck);
	$dataYear = date('Y', $dateToCheck);
	
//	print("today $todayMon $todayDay $todayYear ");
//	print("data $dataMon $dataDay $dataYear ");
	
	return ($todayMon == $dataMon && $todayDay == $dataDay && $todayYear == $dataYear);
}

?>

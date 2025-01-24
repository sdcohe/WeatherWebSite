<?php

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=weatherhistory.csv");
header("Pragma: no-cache");
header("Expires: 0");

require_once "config/config.php";
require_once "classes/weatherConversions.php";

$cfg = config::getInstance();
date_default_timezone_set($cfg->getTimeZone());

require_once "classes/weatherHistory.php";
$history = new WeatherHistory();
// include_once "weatherdata.inc.php";
// include_once "weatherstatistics.inc.php";
// include_once "weatherhistory.inc.php";

print "DATE,TIME,TEMP,AVGSPEED,AVGDIR,GUST,GUSTDIR,HUMIDITY,PRESSURE,RAIN,SOLAR\n";
$idx = 0;
$entries = $history->getEntries();

$numEntries = count($entries) - 1;

foreach($entries as $entry)
{
	$dt = strtotime($entry->DATETIME);
	print date("m/d/y", $dt) . ",";
	print date("H:i", $dt) . ",";
	print $entry->OUTDOORTEMPERATURE . ",";
	print $entry->AVERAGEWINDSPEED . "," . getWindDirStr($entry->AVERAGEWINDDIRECTION) . ",";
	print $entry->WINDSPEED . "," . getWindDirStr($entry->WINDDIRECTION) . ",";
	print $entry->OUTDOORHUMIDITY . ",";
	print $entry->PRESSURE . ",";
// 	print getRainCount($entries, $idx, $todayDOY, $todayStartCount, $yesterdayDOY, $yesterdayStartCount) . ",";
	print $history->getRainData($numEntries - $idx) . ",";
	print $entry->SOLAR;
	print "\n";

	$idx++;
}


?>
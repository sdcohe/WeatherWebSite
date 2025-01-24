<?php
//******************************************************
// Interface to AWEKAS.  Their servers will request this page
// periodically.  The URL of the page they request is set
// through their service.  They expect to receive a text file
// (content type text/plain) with specific format containing
// the weather data. 
//
// The interface description is located at the following URL:
// http://www.awekas.at/for2/index.php?page=Thread&threadID=229
//
// Update: 10/30/2012 - Date/time is local
// Update: 10/30/2012 - Rainfall is daily (reset at 00:00 local)
//******************************************************
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header("Cache-Control: no-cache, must-revalidate" );
header("Pragma: no-cache" );
header("Content-Type: text/plain");

require_once "config/config.php";
require_once "classes/weatherConversions.php";

$cfg = config::getInstance();
date_default_timezone_set($cfg->getTimeZone());

require_once "classes/weatherData.php";
require_once "classes/weatherStatistics.php";
require_once 'classes/weatherHistory.php';

$data = new weatherData();
$stats = new weatherStatistics();
$history = new WeatherHistory();

$tempc = "";
$pressure = "";
$rainDayInMM = "";
$barochange = "";

if (! isValueEmpty($data->getOutdoorTemperature()))
{
	$tempc =  periodToComma(formatNumberDP(($data->getOutdoorTemperature() - 32) * 5/9));
}

if (! isValueEmpty($data->getOutdoorHumidity()))
{
	$humidity =  periodToComma(formatNumberDP($data->getOutdoorHumidity()));
}

if (!isValueEmpty($data->getPressure()))
{
	$pressure = periodToComma(formatNumberDP($data->getPressure() * 33.8653));
	if (!isValueEmpty($history->getWeatherData(360)->getPressure()))
	{
		$barochange = periodToComma(formatNumberDP($data->getPressure() - $history->getWeatherData(360)->getPressure()));
	}
}

// print "new: " . $baroininches2dp . " " . " old: " . $baro360minuteago . "\n";
// 	print "wind dir is " . $winddir . "\n";

// $stats->getRainCounts()->getHourCount(24)
// if ($totalrainlast24hours != "---")
// {
$rainDayInMM = periodToComma(formatNumberDP($stats->getRainCounts()->getDayCount() * 25.4));

// confirmed with AWEKAS.  Time is local, not UTC
$sampletime = $data->getDateTime();
$hour = date("H", $sampletime);
$minute = date("i", $sampletime);

// convert sampletime to UTC
// $utc_str = gmdate("M d Y H:i:s", $sampletime);
// $sampletime = strtotime($utc_str);
  
// print "Wind dir '" . $winddir . "'\n";
$windkph = periodToComma(formatNumberDP($data->getAvgWindSpeed() * 1.60934));
// print "Wind speed '" . $windspeed . "' KPH '" . $windkph . "' calc '" . (float)$windspeed * 1.60934 . "'\n";
$winddir = windDirToCompass($data->getAvgWindDir());

print "\n";					// blank line
print "$tempc\n";	// outside temp (Celsius)
print $humidity . "\n";	// humidity %
print $pressure . "\n";	// barometric pressure millibars
print $rainDayInMM . "\n";	// precipitation (day in mm)
print $windkph . "\n";	// wind speed (km/h)
print $winddir . "\n";	// wind direction (compass)
print date("H:i", $sampletime) . "\n";	// time (HH:MM)
print date("d.m.Y", $sampletime) . "\n";	// date (DD.MM.YYYY)
print $barochange . "\n";	// change in air pressure last 6 hours

?>
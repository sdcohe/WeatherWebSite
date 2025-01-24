<html>

<?php
// $path = '/home/seth/classes';
// set_include_path(get_include_path() . PATH_SEPARATOR . $path);
require_once 'classes/weatherData.php';
require_once 'classes/weatherStatistics.php';
require_once 'config/config.php';

$cfg = config::getInstance();
date_default_timezone_set($cfg->getTimeZone());

$data = new weatherData();
$stats = new weatherStatistics();
?>

<head>
	<title>Class Test Page</title>
</head>

<body>
<?php
//yyyy-MM-dd'T'HH:mm:ssZ
	print "<h1>Date Test</h1>";
	$var = strtotime("2011-12-31T15:05:50+1000");
	print "Date: " . date($cfg->getDateTimeFormat(), $var);
	 
	print "<h1>Weather Data JSON</h1>";
	print  $data->getJSON(). "\n";
	
	print "<h1>Weather Stats JSON</h1>";
	print $stats->getJSON() . "\n";
	
	print "<h1>Outdoor Temp</h1>";
	print "Hourly Max Outdoor Temp: " . $stats->getOutdoorTempStats()->getHourlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorTempStats()->getHourlyValues()->getMaxDate()) . "<br />";
	print "Hourly Min Outdoor Temp: " . $stats->getOutdoorTempStats()->getHourlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorTempStats()->getHourlyValues()->getMinDate()) . "<br />";
	print "Daily Max Outdoor Temp: " . $stats->getOutdoorTempStats()->getDailyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorTempStats()->getDailyValues()->getMaxDate()) . "<br />";
	print "Daily Min Outdoor Temp: " . $stats->getOutdoorTempStats()->getDailyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorTempStats()->getDailyValues()->getMinDate()) . "<br />";
	print "Yesterday Max Outdoor Temp: " . $stats->getOutdoorTempStats()->getYesterdayValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorTempStats()->getYesterdayValues()->getMaxDate()) . "<br />";
	print "Yesterday Min Outdoor Temp: " . $stats->getOutdoorTempStats()->getYesterdayValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorTempStats()->getYesterdayValues()->getMinDate()) . "<br />";
	print "Weekly Max Outdoor Temp: " . $stats->getOutdoorTempStats()->getWeeklyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorTempStats()->getWeeklyValues()->getMaxDate()) . "<br />";
	print "Weekly Min Outdoor Temp: " . $stats->getOutdoorTempStats()->getWeeklyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorTempStats()->getWeeklyValues()->getMinDate()) . "<br />";
	print "Monthly Max Outdoor Temp: " . $stats->getOutdoorTempStats()->getMonthlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorTempStats()->getMonthlyValues()->getMaxDate()) . "<br />";
	print "Monthly Min Outdoor Temp: " . $stats->getOutdoorTempStats()->getMonthlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorTempStats()->getMonthlyValues()->getMinDate()) . "<br />";
	print "Annual Max Outdoor Temp: " . $stats->getOutdoorTempStats()->getAnnualValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorTempStats()->getAnnualValues()->getMaxDate()) . "<br />";
	print "Annual Min Outdoor Temp: " . $stats->getOutdoorTempStats()->getAnnualValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorTempStats()->getAnnualValues()->getMinDate()) . "<br />";
	print "Cumulative Max Outdoor Temp: " . $stats->getOutdoorTempStats()->getCumulativeValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorTempStats()->getCumulativeValues()->getMaxDate()) . "<br />";
	print "Cumulative Min Outdoor Temp: " . $stats->getOutdoorTempStats()->getCumulativeValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorTempStats()->getCumulativeValues()->getMinDate()) . "<br />";
	print "\n";
	
	print "<h1>Outdoor Humidity</h1>";
	print "Hourly Max Outdoor Humidity: " . $stats->getOutdoorHumidityStats()->getHourlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorHumidityStats()->getHourlyValues()->getMaxDate()) . "<br />";
	print "Hourly Min Outdoor Humidity: " . $stats->getOutdoorHumidityStats()->getHourlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorHumidityStats()->getHourlyValues()->getMinDate()) . "<br />";
	print "Daily Max Outdoor Humidity: " . $stats->getOutdoorHumidityStats()->getDailyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorHumidityStats()->getDailyValues()->getMaxDate()) . "<br />";
	print "Daily Min Outdoor Humidity: " . $stats->getOutdoorHumidityStats()->getDailyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorHumidityStats()->getDailyValues()->getMinDate()) . "<br />";
	print "Yesterday Max Outdoor Humidity: " . $stats->getOutdoorHumidityStats()->getYesterdayValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorHumidityStats()->getYesterdayValues()->getMaxDate()) . "<br />";
	print "Yesterday Min Outdoor Humidity: " . $stats->getOutdoorHumidityStats()->getYesterdayValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorHumidityStats()->getYesterdayValues()->getMinDate()) . "<br />";
	print "Weekly Max Outdoor Humidity: " . $stats->getOutdoorHumidityStats()->getWeeklyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorHumidityStats()->getWeeklyValues()->getMaxDate()) . "<br />";
	print "Weekly Min Outdoor Humidity: " . $stats->getOutdoorHumidityStats()->getWeeklyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorHumidityStats()->getWeeklyValues()->getMinDate()) . "<br />";
	print "Monthly Max Outdoor Humidity: " . $stats->getOutdoorHumidityStats()->getMonthlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorHumidityStats()->getMonthlyValues()->getMaxDate()) . "<br />";
	print "Monthly Min Outdoor Humidity: " . $stats->getOutdoorHumidityStats()->getMonthlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorHumidityStats()->getMonthlyValues()->getMinDate()) . "<br />";
	print "Annual Max Outdoor Humidity: " . $stats->getOutdoorHumidityStats()->getAnnualValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorHumidityStats()->getAnnualValues()->getMaxDate()) . "<br />";
	print "Annual Min Outdoor Humidity: " . $stats->getOutdoorHumidityStats()->getAnnualValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorHumidityStats()->getAnnualValues()->getMinDate()) . "<br />";
	print "Cumulative Max Outdoor Humidity: " . $stats->getOutdoorHumidityStats()->getCumulativeValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorHumidityStats()->getCumulativeValues()->getMaxDate()) . "<br />";
	print "Cumulative Min Outdoor Humidity: " . $stats->getOutdoorHumidityStats()->getCumulativeValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getOutdoorHumidityStats()->getCumulativeValues()->getMinDate()) . "<br />";
	print "\n";
	
	print "<h1>Wind Gust</h1>";
	print "Hourly Max Wind Gust: " . $stats->getWindGustStats()->getHourlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindGustStats()->getHourlyValues()->getMaxDate()) . "<br />";
	print "Hourly Min Wind Gust: " . $stats->getWindGustStats()->getHourlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindGustStats()->getHourlyValues()->getMinDate()) . "<br />";
	print "Daily Max Wind Gust: " . $stats->getWindGustStats()->getDailyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindGustStats()->getDailyValues()->getMaxDate()) . "<br />";
	print "Daily Min Wind Gust: " . $stats->getWindGustStats()->getDailyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindGustStats()->getDailyValues()->getMinDate()) . "<br />";
	print "Yesterday Max Wind Gust: " . $stats->getWindGustStats()->getYesterdayValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindGustStats()->getYesterdayValues()->getMaxDate()) . "<br />";
	print "Yesterday Min Wind Gust: " . $stats->getWindGustStats()->getYesterdayValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindGustStats()->getYesterdayValues()->getMinDate()) . "<br />";
	print "Weekly Max Wind Gust: " . $stats->getWindGustStats()->getWeeklyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindGustStats()->getWeeklyValues()->getMaxDate()) . "<br />";
	print "Weekly Min Wind Gust: " . $stats->getWindGustStats()->getWeeklyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindGustStats()->getWeeklyValues()->getMinDate()) . "<br />";
	print "Monthly Max Wind Gust: " . $stats->getWindGustStats()->getMonthlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindGustStats()->getMonthlyValues()->getMaxDate()) . "<br />";
	print "Monthly Min Wind Gust: " . $stats->getWindGustStats()->getMonthlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindGustStats()->getMonthlyValues()->getMinDate()) . "<br />";
	print "Annual Max Wind Gust: " . $stats->getWindGustStats()->getAnnualValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindGustStats()->getAnnualValues()->getMaxDate()) . "<br />";
	print "Annual Min Wind Gust: " . $stats->getWindGustStats()->getAnnualValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindGustStats()->getAnnualValues()->getMinDate()) . "<br />";
	print "Cumulative Max Wind Gust: " . $stats->getWindGustStats()->getCumulativeValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindGustStats()->getCumulativeValues()->getMaxDate()) . "<br />";
	print "Cumulative Min Wind Gust: " . $stats->getWindGustStats()->getCumulativeValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindGustStats()->getCumulativeValues()->getMinDate()) . "<br />";
	print "\n";
	
	print "<h1>Wind Sustained</h1>";
	print "Hourly Max Wind Sustained: " . $stats->getWindSustainedStats()->getHourlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindSustainedStats()->getHourlyValues()->getMaxDate()) . "<br />";
	print "Hourly Min Wind Sustained: " . $stats->getWindSustainedStats()->getHourlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindSustainedStats()->getHourlyValues()->getMinDate()) . "<br />";
	print "Daily Max Wind Sustained: " . $stats->getWindSustainedStats()->getDailyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindSustainedStats()->getDailyValues()->getMaxDate()) . "<br />";
	print "Daily Min Wind Sustained: " . $stats->getWindSustainedStats()->getDailyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindSustainedStats()->getDailyValues()->getMinDate()) . "<br />";
	print "Yesterday Max Wind Sustained: " . $stats->getWindSustainedStats()->getYesterdayValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindSustainedStats()->getYesterdayValues()->getMaxDate()) . "<br />";
	print "Yesterday Min Wind Sustained: " . $stats->getWindSustainedStats()->getYesterdayValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindSustainedStats()->getYesterdayValues()->getMinDate()) . "<br />";
	print "Weekly Max Wind Sustained: " . $stats->getWindSustainedStats()->getWeeklyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindSustainedStats()->getWeeklyValues()->getMaxDate()) . "<br />";
	print "Weekly Min Wind Sustained: " . $stats->getWindSustainedStats()->getWeeklyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindSustainedStats()->getWeeklyValues()->getMinDate()) . "<br />";
	print "Monthly Max Wind Sustained: " . $stats->getWindSustainedStats()->getMonthlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindSustainedStats()->getMonthlyValues()->getMaxDate()) . "<br />";
	print "Monthly Min Wind Sustained: " . $stats->getWindSustainedStats()->getMonthlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindSustainedStats()->getMonthlyValues()->getMinDate()) . "<br />";
	print "Annual Max Wind Sustained: " . $stats->getWindSustainedStats()->getAnnualValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindSustainedStats()->getAnnualValues()->getMaxDate()) . "<br />";
	print "Annual Min Wind Sustained: " . $stats->getWindSustainedStats()->getAnnualValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindSustainedStats()->getAnnualValues()->getMinDate()) . "<br />";
	print "Cumulative Max Wind Sustained: " . $stats->getWindSustainedStats()->getCumulativeValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindSustainedStats()->getCumulativeValues()->getMaxDate()) . "<br />";
	print "Cumulative Min Wind Sustained: " . $stats->getWindSustainedStats()->getCumulativeValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindSustainedStats()->getCumulativeValues()->getMinDate()) . "<br />";
	print "\n";
	
	print "<h1>Indoor Temp</h1>";
	print "Hourly Max Indoor Temp: " . $stats->getIndoorTempStats()->getHourlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorTempStats()->getHourlyValues()->getMaxDate()) . "<br />";
	print "Hourly Min Indoor Temp: " . $stats->getIndoorTempStats()->getHourlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorTempStats()->getHourlyValues()->getMinDate()) . "<br />";
	print "Daily Max Indoor Temp: " . $stats->getIndoorTempStats()->getDailyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorTempStats()->getDailyValues()->getMaxDate()) . "<br />";
	print "Daily Min Indoor Temp: " . $stats->getIndoorTempStats()->getDailyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorTempStats()->getDailyValues()->getMinDate()) . "<br />";
	print "Yesterday Max Indoor Temp: " . $stats->getIndoorTempStats()->getYesterdayValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorTempStats()->getYesterdayValues()->getMaxDate()) . "<br />";
	print "Yesterday Min Indoor Temp: " . $stats->getIndoorTempStats()->getYesterdayValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorTempStats()->getYesterdayValues()->getMinDate()) . "<br />";
	print "Weekly Max Indoor Temp: " . $stats->getIndoorTempStats()->getWeeklyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorTempStats()->getWeeklyValues()->getMaxDate()) . "<br />";
	print "Weekly Min Indoor Temp: " . $stats->getIndoorTempStats()->getWeeklyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorTempStats()->getWeeklyValues()->getMinDate()) . "<br />";
	print "Monthly Max Indoor Temp: " . $stats->getIndoorTempStats()->getMonthlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorTempStats()->getMonthlyValues()->getMaxDate()) . "<br />";
	print "Monthly Min Indoor Temp: " . $stats->getIndoorTempStats()->getMonthlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorTempStats()->getMonthlyValues()->getMinDate()) . "<br />";
	print "Annual Max Indoor Temp: " . $stats->getIndoorTempStats()->getAnnualValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorTempStats()->getAnnualValues()->getMaxDate()) . "<br />";
	print "Annual Min Indoor Temp: " . $stats->getIndoorTempStats()->getAnnualValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorTempStats()->getAnnualValues()->getMinDate()) . "<br />";
	print "Cumulative Max Indoor Temp: " . $stats->getIndoorTempStats()->getCumulativeValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorTempStats()->getCumulativeValues()->getMaxDate()) . "<br />";
	print "Cumulative Min Indoor Temp: " . $stats->getIndoorTempStats()->getCumulativeValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorTempStats()->getCumulativeValues()->getMinDate()) . "<br />";
	print "\n";
	
	print "<h1>Indoor Humidity</h1>";
	print "Hourly Max Indoor Humidity: " . $stats->getIndoorHumidityStats()->getHourlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorHumidityStats()->getHourlyValues()->getMaxDate()) . "<br />";
	print "Hourly Min Indoor Humidity: " . $stats->getIndoorHumidityStats()->getHourlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorHumidityStats()->getHourlyValues()->getMinDate()) . "<br />";
	print "Daily Max Indoor Humidity: " . $stats->getIndoorHumidityStats()->getDailyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorHumidityStats()->getDailyValues()->getMaxDate()) . "<br />";
	print "Daily Min Indoor Humidity: " . $stats->getIndoorHumidityStats()->getDailyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorHumidityStats()->getDailyValues()->getMinDate()) . "<br />";
	print "Yesterday Max Indoor Humidity: " . $stats->getIndoorHumidityStats()->getYesterdayValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorHumidityStats()->getYesterdayValues()->getMaxDate()) . "<br />";
	print "Yesterday Min Indoor Humidity: " . $stats->getIndoorHumidityStats()->getYesterdayValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorHumidityStats()->getYesterdayValues()->getMinDate()) . "<br />";
	print "Weekly Max Indoor Humidity: " . $stats->getIndoorHumidityStats()->getWeeklyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorHumidityStats()->getWeeklyValues()->getMaxDate()) . "<br />";
	print "Weekly Min Indoor Humidity: " . $stats->getIndoorHumidityStats()->getWeeklyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorHumidityStats()->getWeeklyValues()->getMinDate()) . "<br />";
	print "Monthly Max Indoor Humidity: " . $stats->getIndoorHumidityStats()->getMonthlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorHumidityStats()->getMonthlyValues()->getMaxDate()) . "<br />";
	print "Monthly Min Indoor Humidity: " . $stats->getIndoorHumidityStats()->getMonthlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorHumidityStats()->getMonthlyValues()->getMinDate()) . "<br />";
	print "Annual Max Indoor Humidity: " . $stats->getIndoorHumidityStats()->getAnnualValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorHumidityStats()->getAnnualValues()->getMaxDate()) . "<br />";
	print "Annual Min Indoor Humidity: " . $stats->getIndoorHumidityStats()->getAnnualValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorHumidityStats()->getAnnualValues()->getMinDate()) . "<br />";
	print "Cumulative Max Indoor Humidity: " . $stats->getIndoorHumidityStats()->getCumulativeValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorHumidityStats()->getCumulativeValues()->getMaxDate()) . "<br />";
	print "Cumulative Min Indoor Humidity: " . $stats->getIndoorHumidityStats()->getCumulativeValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getIndoorHumidityStats()->getCumulativeValues()->getMinDate()) . "<br />";
	print "\n";
	
	print "<h1>Pressure</h1>";
	print "Hourly Max Pressure: " . $stats->getPressureStats()->getHourlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getPressureStats()->getHourlyValues()->getMaxDate()) . "<br />";
	print "Hourly Min Pressure: " . $stats->getPressureStats()->getHourlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getPressureStats()->getHourlyValues()->getMinDate()) . "<br />";
	print "Daily Max Pressure: " . $stats->getPressureStats()->getDailyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getPressureStats()->getDailyValues()->getMaxDate()) . "<br />";
	print "Daily Min Pressure: " . $stats->getPressureStats()->getDailyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getPressureStats()->getDailyValues()->getMinDate()) . "<br />";
	print "Yesterday Max Pressure: " . $stats->getPressureStats()->getYesterdayValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getPressureStats()->getYesterdayValues()->getMaxDate()) . "<br />";
	print "Yesterday Min Pressure: " . $stats->getPressureStats()->getYesterdayValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getPressureStats()->getYesterdayValues()->getMinDate()) . "<br />";
	print "Weekly Max Pressure: " . $stats->getPressureStats()->getWeeklyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getPressureStats()->getWeeklyValues()->getMaxDate()) . "<br />";
	print "Weekly Min Pressure: " . $stats->getPressureStats()->getWeeklyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getPressureStats()->getWeeklyValues()->getMinDate()) . "<br />";
	print "Monthly Max Pressure: " . $stats->getPressureStats()->getMonthlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getPressureStats()->getMonthlyValues()->getMaxDate()) . "<br />";
	print "Monthly Min Pressure: " . $stats->getPressureStats()->getMonthlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getPressureStats()->getMonthlyValues()->getMinDate()) . "<br />";
	print "Annual Max Pressure: " . $stats->getPressureStats()->getAnnualValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getPressureStats()->getAnnualValues()->getMaxDate()) . "<br />";
	print "Annual Min Pressure: " . $stats->getPressureStats()->getAnnualValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getPressureStats()->getAnnualValues()->getMinDate()) . "<br />";
	print "Cumulative Max Pressure: " . $stats->getPressureStats()->getCumulativeValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getPressureStats()->getCumulativeValues()->getMaxDate()) . "<br />";
	print "Cumulative Min Pressure: " . $stats->getPressureStats()->getCumulativeValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getPressureStats()->getCumulativeValues()->getMinDate()) . "<br />";
	print "\n";
	
	print "<h1>Solar</h1>";
	print "Hourly Max Solar: " . $stats->getSolarStats()->getHourlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getSolarStats()->getHourlyValues()->getMaxDate()) . "<br />";
	print "Hourly Min Solar: " . $stats->getSolarStats()->getHourlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getSolarStats()->getHourlyValues()->getMinDate()) . "<br />";
	print "Daily Max Solar: " . $stats->getSolarStats()->getDailyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getSolarStats()->getDailyValues()->getMaxDate()) . "<br />";
	print "Daily Min Solar: " . $stats->getSolarStats()->getDailyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getSolarStats()->getDailyValues()->getMinDate()) . "<br />";
	print "Yesterday Max Solar: " . $stats->getSolarStats()->getYesterdayValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getSolarStats()->getYesterdayValues()->getMaxDate()) . "<br />";
	print "Yesterday Min Solar: " . $stats->getSolarStats()->getYesterdayValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getSolarStats()->getYesterdayValues()->getMinDate()) . "<br />";
	print "Weekly Max Solar: " . $stats->getSolarStats()->getWeeklyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getSolarStats()->getWeeklyValues()->getMaxDate()) . "<br />";
	print "Weekly Min Solar: " . $stats->getSolarStats()->getWeeklyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getSolarStats()->getWeeklyValues()->getMinDate()) . "<br />";
	print "Monthly Max Solar: " . $stats->getSolarStats()->getMonthlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getSolarStats()->getMonthlyValues()->getMaxDate()) . "<br />";
	print "Monthly Min Solar: " . $stats->getSolarStats()->getMonthlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getSolarStats()->getMonthlyValues()->getMinDate()) . "<br />";
	print "Annual Max Solar: " . $stats->getSolarStats()->getAnnualValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getSolarStats()->getAnnualValues()->getMaxDate()) . "<br />";
	print "Annual Min Solar: " . $stats->getSolarStats()->getAnnualValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getSolarStats()->getAnnualValues()->getMinDate()) . "<br />";
	print "Cumulative Max Solar: " . $stats->getSolarStats()->getCumulativeValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getSolarStats()->getCumulativeValues()->getMaxDate()) . "<br />";
	print "Cumulative Min Solar: " . $stats->getSolarStats()->getCumulativeValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getSolarStats()->getCumulativeValues()->getMinDate()) . "<br />";
	print "\n";
	
	print "<h1>Heat Index</h1>";
	print "Hourly Max Heat Index: " . $stats->getHeatIndexStats()->getHourlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getHeatIndexStats()->getHourlyValues()->getMaxDate()) . "<br />";
	print "Hourly Min Heat Index: " . $stats->getHeatIndexStats()->getHourlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getHeatIndexStats()->getHourlyValues()->getMinDate()) . "<br />";
	print "Daily Max Heat Index: " . $stats->getHeatIndexStats()->getDailyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getHeatIndexStats()->getDailyValues()->getMaxDate()) . "<br />";
	print "Daily Min Heat Index: " . $stats->getHeatIndexStats()->getDailyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getHeatIndexStats()->getDailyValues()->getMinDate()) . "<br />";
	print "Yesterday Max Heat Index: " . $stats->getHeatIndexStats()->getYesterdayValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getHeatIndexStats()->getYesterdayValues()->getMaxDate()) . "<br />";
	print "Yesterday Min Heat Index: " . $stats->getHeatIndexStats()->getYesterdayValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getHeatIndexStats()->getYesterdayValues()->getMinDate()) . "<br />";
	print "Weekly Max Heat Index: " . $stats->getHeatIndexStats()->getWeeklyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getHeatIndexStats()->getWeeklyValues()->getMaxDate()) . "<br />";
	print "Weekly Min Heat Index: " . $stats->getHeatIndexStats()->getWeeklyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getHeatIndexStats()->getWeeklyValues()->getMinDate()) . "<br />";
	print "Monthly Max Heat Index: " . $stats->getHeatIndexStats()->getMonthlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getHeatIndexStats()->getMonthlyValues()->getMaxDate()) . "<br />";
	print "Monthly Min Heat Index: " . $stats->getHeatIndexStats()->getMonthlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getHeatIndexStats()->getMonthlyValues()->getMinDate()) . "<br />";
	print "Annual Max Heat Index: " . $stats->getHeatIndexStats()->getAnnualValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getHeatIndexStats()->getAnnualValues()->getMaxDate()) . "<br />";
	print "Annual Min Heat Index: " . $stats->getHeatIndexStats()->getAnnualValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getHeatIndexStats()->getAnnualValues()->getMinDate()) . "<br />";
	print "Cumulative Max Heat Index: " . $stats->getHeatIndexStats()->getCumulativeValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getHeatIndexStats()->getCumulativeValues()->getMaxDate()) . "<br />";
	print "Cumulative Min Heat Index: " . $stats->getHeatIndexStats()->getCumulativeValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getHeatIndexStats()->getCumulativeValues()->getMinDate()) . "<br />";
	print "\n";
	
	print "<h1>Wind Chill</h1>";
	print "Hourly Max Wind Chill: " . $stats->getWindChillStats()->getHourlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindChillStats()->getHourlyValues()->getMaxDate()) . "<br />";
	print "Hourly Min Wind Chill: " . $stats->getWindChillStats()->getHourlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindChillStats()->getHourlyValues()->getMinDate()) . "<br />";
	print "Daily Max Wind Chill: " . $stats->getWindChillStats()->getDailyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindChillStats()->getDailyValues()->getMaxDate()) . "<br />";
	print "Daily Min Wind Chill: " . $stats->getWindChillStats()->getDailyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindChillStats()->getDailyValues()->getMinDate()) . "<br />";
	print "Yesterday Max Wind Chill: " . $stats->getWindChillStats()->getYesterdayValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindChillStats()->getYesterdayValues()->getMaxDate()) . "<br />";
	print "Yesterday Min Wind Chill: " . $stats->getWindChillStats()->getYesterdayValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindChillStats()->getYesterdayValues()->getMinDate()) . "<br />";
	print "Weekly Max Wind Chill: " . $stats->getWindChillStats()->getWeeklyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindChillStats()->getWeeklyValues()->getMaxDate()) . "<br />";
	print "Weekly Min Wind Chill: " . $stats->getWindChillStats()->getWeeklyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindChillStats()->getWeeklyValues()->getMinDate()) . "<br />";
	print "Monthly Max Wind Chill: " . $stats->getWindChillStats()->getMonthlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindChillStats()->getMonthlyValues()->getMaxDate()) . "<br />";
	print "Monthly Min Wind Chill: " . $stats->getWindChillStats()->getMonthlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindChillStats()->getMonthlyValues()->getMinDate()) . "<br />";
	print "Annual Max Wind Chill: " . $stats->getWindChillStats()->getAnnualValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindChillStats()->getAnnualValues()->getMaxDate()) . "<br />";
	print "Annual Min Wind Chill: " . $stats->getWindChillStats()->getAnnualValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindChillStats()->getAnnualValues()->getMinDate()) . "<br />";
	print "Cumulative Max Wind Chill: " . $stats->getWindChillStats()->getCumulativeValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindChillStats()->getCumulativeValues()->getMaxDate()) . "<br />";
	print "Cumulative Min Wind Chill: " . $stats->getWindChillStats()->getCumulativeValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getWindChillStats()->getCumulativeValues()->getMinDate()) . "<br />";
	print "\n";
	
	print "<h1>Dew Point</h1>";
	print "Hourly Max Dew Point: " . $stats->getDewPointStats()->getHourlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getDewPointStats()->getHourlyValues()->getMaxDate()) . "<br />";
	print "Hourly Min Dew Point: " . $stats->getDewPointStats()->getHourlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getDewPointStats()->getHourlyValues()->getMinDate()) . "<br />";
	print "Daily Max Dew Point: " . $stats->getDewPointStats()->getDailyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getDewPointStats()->getDailyValues()->getMaxDate()) . "<br />";
	print "Daily Min Dew Point: " . $stats->getDewPointStats()->getDailyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getDewPointStats()->getDailyValues()->getMinDate()) . "<br />";
	print "Yesterday Max Dew Point: " . $stats->getDewPointStats()->getYesterdayValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getDewPointStats()->getYesterdayValues()->getMaxDate()) . "<br />";
	print "Yesterday Min Dew Point: " . $stats->getDewPointStats()->getYesterdayValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getDewPointStats()->getYesterdayValues()->getMinDate()) . "<br />";
	print "Weekly Max Dew Point: " . $stats->getDewPointStats()->getWeeklyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getDewPointStats()->getWeeklyValues()->getMaxDate()) . "<br />";
	print "Weekly Min Dew Point: " . $stats->getDewPointStats()->getWeeklyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getDewPointStats()->getWeeklyValues()->getMinDate()) . "<br />";
	print "Monthly Max Dew Point: " . $stats->getDewPointStats()->getMonthlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getDewPointStats()->getMonthlyValues()->getMaxDate()) . "<br />";
	print "Monthly Min Dew Point: " . $stats->getDewPointStats()->getMonthlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getDewPointStats()->getMonthlyValues()->getMinDate()) . "<br />";
	print "Annual Max Dew Point: " . $stats->getDewPointStats()->getAnnualValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getDewPointStats()->getAnnualValues()->getMaxDate()) . "<br />";
	print "Annual Min Dew Point: " . $stats->getDewPointStats()->getAnnualValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getDewPointStats()->getAnnualValues()->getMinDate()) . "<br />";
	print "Cumulative Max Dew Point: " . $stats->getDewPointStats()->getCumulativeValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getDewPointStats()->getCumulativeValues()->getMaxDate()) . "<br />";
	print "Cumulative Min Dew Point: " . $stats->getDewPointStats()->getCumulativeValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getDewPointStats()->getCumulativeValues()->getMinDate()) . "<br />";
	print "\n";
	
	print "<h1>Daytime Outdoor Temperature</h1>";
	print "Hourly Max Daytime Outdoor Temperature: " . $stats->getDaytimeOutdoorTemperatureStats()->getHourlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getDaytimeOutdoorTemperatureStats()->getHourlyValues()->getMaxDate()) . "<br />";
	print "Hourly Min Daytime Outdoor Temperature: " . $stats->getDaytimeOutdoorTemperatureStats()->getHourlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getDaytimeOutdoorTemperatureStats()->getHourlyValues()->getMinDate()) . "<br />";
	print "Daily Max Daytime Outdoor Temperature: " . $stats->getDaytimeOutdoorTemperatureStats()->getDailyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getDaytimeOutdoorTemperatureStats()->getDailyValues()->getMaxDate()) . "<br />";
	print "Daily Min Daytime Outdoor Temperature: " . $stats->getDaytimeOutdoorTemperatureStats()->getDailyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getDaytimeOutdoorTemperatureStats()->getDailyValues()->getMinDate()) . "<br />";
	print "Yesterday Max Daytime Outdoor Temperature: " . $stats->getDaytimeOutdoorTemperatureStats()->getYesterdayValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getDaytimeOutdoorTemperatureStats()->getYesterdayValues()->getMaxDate()) . "<br />";
	print "Yesterday Min Daytime Outdoor Temperature: " . $stats->getDaytimeOutdoorTemperatureStats()->getYesterdayValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getDaytimeOutdoorTemperatureStats()->getYesterdayValues()->getMinDate()) . "<br />";
	print "Weekly Max Daytime Outdoor Temperature: " . $stats->getDaytimeOutdoorTemperatureStats()->getWeeklyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getDaytimeOutdoorTemperatureStats()->getWeeklyValues()->getMaxDate()) . "<br />";
	print "Weekly Min Daytime Outdoor Temperature: " . $stats->getDaytimeOutdoorTemperatureStats()->getWeeklyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getDaytimeOutdoorTemperatureStats()->getWeeklyValues()->getMinDate()) . "<br />";
	print "Monthly Max Daytime Outdoor Temperature: " . $stats->getDaytimeOutdoorTemperatureStats()->getMonthlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getDaytimeOutdoorTemperatureStats()->getMonthlyValues()->getMaxDate()) . "<br />";
	print "Monthly Min Daytime Outdoor Temperature: " . $stats->getDaytimeOutdoorTemperatureStats()->getMonthlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getDaytimeOutdoorTemperatureStats()->getMonthlyValues()->getMinDate()) . "<br />";
	print "Annual Max Daytime Outdoor Temperature: " . $stats->getDaytimeOutdoorTemperatureStats()->getAnnualValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getDaytimeOutdoorTemperatureStats()->getAnnualValues()->getMaxDate()) . "<br />";
	print "Annual Min Daytime Outdoor Temperature: " . $stats->getDaytimeOutdoorTemperatureStats()->getAnnualValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getDaytimeOutdoorTemperatureStats()->getAnnualValues()->getMinDate()) . "<br />";
	print "Cumulative Max Daytime Outdoor Temperature: " . $stats->getDaytimeOutdoorTemperatureStats()->getCumulativeValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getDaytimeOutdoorTemperatureStats()->getCumulativeValues()->getMaxDate()) . "<br />";
	print "Cumulative Min Daytime Outdoor Temperature: " . $stats->getDaytimeOutdoorTemperatureStats()->getCumulativeValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getDaytimeOutdoorTemperatureStats()->getCumulativeValues()->getMinDate()) . "<br />";
	print "\n";
	
	print "<h1>Nighttime Outdoor Temperature</h1>";
	print "Hourly Max Nighttime Outdoor Temperature: " . $stats->getNighttimeOutdoorTemperatureStats()->getHourlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getNighttimeOutdoorTemperatureStats()->getHourlyValues()->getMaxDate()) . "<br />";
	print "Hourly Min Nighttime Outdoor Temperature: " . $stats->getNighttimeOutdoorTemperatureStats()->getHourlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getNighttimeOutdoorTemperatureStats()->getHourlyValues()->getMinDate()) . "<br />";
	print "Daily Max Nighttime Outdoor Temperature: " . $stats->getNighttimeOutdoorTemperatureStats()->getDailyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getNighttimeOutdoorTemperatureStats()->getDailyValues()->getMaxDate()) . "<br />";
	print "Daily Min Nighttime Outdoor Temperature: " . $stats->getNighttimeOutdoorTemperatureStats()->getDailyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getNighttimeOutdoorTemperatureStats()->getDailyValues()->getMinDate()) . "<br />";
	print "Yesterday Max Nighttime Outdoor Temperature: " . $stats->getNighttimeOutdoorTemperatureStats()->getYesterdayValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getNighttimeOutdoorTemperatureStats()->getYesterdayValues()->getMaxDate()) . "<br />";
	print "Yesterday Min Nighttime Outdoor Temperature: " . $stats->getNighttimeOutdoorTemperatureStats()->getYesterdayValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getNighttimeOutdoorTemperatureStats()->getYesterdayValues()->getMinDate()) . "<br />";
	print "Weekly Max Nighttime Outdoor Temperature: " . $stats->getNighttimeOutdoorTemperatureStats()->getWeeklyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getNighttimeOutdoorTemperatureStats()->getWeeklyValues()->getMaxDate()) . "<br />";
	print "Weekly Min Nighttime Outdoor Temperature: " . $stats->getNighttimeOutdoorTemperatureStats()->getWeeklyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getNighttimeOutdoorTemperatureStats()->getWeeklyValues()->getMinDate()) . "<br />";
	print "Monthly Max Nighttime Outdoor Temperature: " . $stats->getNighttimeOutdoorTemperatureStats()->getMonthlyValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getNighttimeOutdoorTemperatureStats()->getMonthlyValues()->getMaxDate()) . "<br />";
	print "Monthly Min Nighttime Outdoor Temperature: " . $stats->getNighttimeOutdoorTemperatureStats()->getMonthlyValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getNighttimeOutdoorTemperatureStats()->getMonthlyValues()->getMinDate()) . "<br />";
	print "Annual Max Nighttime Outdoor Temperature: " . $stats->getNighttimeOutdoorTemperatureStats()->getAnnualValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getNighttimeOutdoorTemperatureStats()->getAnnualValues()->getMaxDate()) . "<br />";
	print "Annual Min Nighttime Outdoor Temperature: " . $stats->getNighttimeOutdoorTemperatureStats()->getAnnualValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getNighttimeOutdoorTemperatureStats()->getAnnualValues()->getMinDate()) . "<br />";
	print "Cumulative Max Nighttime Outdoor Temperature: " . $stats->getNighttimeOutdoorTemperatureStats()->getCumulativeValues()->getMax() . " at " . date($cfg->getDateTimeFormat(), $stats->getNighttimeOutdoorTemperatureStats()->getCumulativeValues()->getMaxDate()) . "<br />";
	print "Cumulative Min Nighttime Outdoor Temperature: " . $stats->getNighttimeOutdoorTemperatureStats()->getCumulativeValues()->getMin() . " at " . date($cfg->getDateTimeFormat(), $stats->getNighttimeOutdoorTemperatureStats()->getCumulativeValues()->getMinDate()) . "<br />";
	print "\n";
	
	print "<h1>Stats</h1>";
	print "Average wind direction " . $stats->getAverageWindDirection() . "<br />Speed: " . $stats->getAverageWindSpeed() . "<br />";
	print "Wind Chill " . $stats->getWindChill() . "<br />Wind Run: " . $stats->getWindRun() . "<br />";
	print "Outdoor Temp Trend: " . $stats->getOutdoorTemperatureTrend() . "<br />Humidity Trend " . $stats->getOutdoorHumidityTrend() . "<br />";
	print "Indoor Temp Trend: " . $stats->getIndoorTemperatureTrend() . "<br />Humidity Trend " . $stats->getIndoorHumidityTrend() . "<br />";
	print "Pressure Trend: " . $stats->getPressureTrend() . "<br />Solar Trend " . $stats->getSolarTrend() . "<br />";
	print "Last Start Date: " . date($cfg->getDateTimeFormat(), $stats->getLastStartDate()) . "<br />Stats Start Date " . date($cfg->getDateTimeFormat(), $stats->getStatsStartDate()) . "<br />Last Sample Date " . date($cfg->getDateTimeFormat(), $stats->getLastSampleDate()) . "<br />";
	print "Temp GT 86 : " . $stats->getTempMaxGT86F() . "<br />Temp GT 77 " . $stats->getTempMaxGT77F() . "<br />Temp LT 32 " . $stats->getTempMaxLT32F() . "<br />TempLT 5 " . $stats->getTempMaxLT5F() . "<br />";	
	print "\n";
	
	print "<h1>Max Avg Wind Direction</h1>";
	print "Hourly: " . $stats->getMaxAverageWindDirection()->getHourValue() . "<br />";
	print "Daily: " . $stats->getMaxAverageWindDirection()->getDayValue() . "<br />";
	print "Yesterday: " . $stats->getMaxAverageWindDirection()->getYesterdayValue() . "<br />";
	print "Weekly: " . $stats->getMaxAverageWindDirection()->getWeekValue() . "<br />";
	print "Monthly: " . $stats->getMaxAverageWindDirection()->getMonthValue() . "<br />";
	print "Annual: " . $stats->getMaxAverageWindDirection()->getYearValue() . "<br />";
	print "Cum: " . $stats->getMaxAverageWindDirection()->getCumulativeValue() . "<br />";
	print "\n";
	
	print "<h1>Max Gust Wind Direction</h1>";
	print "Hourly: " . $stats->getMaxGustWindDirection()->getHourValue() . "<br />";
	print "Daily: " . $stats->getMaxGustWindDirection()->getDayValue() . "<br />";
	print "Yesterday: " . $stats->getMaxGustWindDirection()->getYesterdayValue() . "<br />";
	print "Weekly: " . $stats->getMaxGustWindDirection()->getWeekValue() . "<br />";
	print "Monthly: " . $stats->getMaxGustWindDirection()->getMonthValue() . "<br />";
	print "Annual: " . $stats->getMaxGustWindDirection()->getYearValue() . "<br />";
	print "Cum: " . $stats->getMaxGustWindDirection()->getCumulativeValue() . "<br />";
	print "\n";
	
	print "<h1>Rain Counts</h1>";
	print "Last Change Date: " . date($cfg->getDateTimeFormat(), $stats->getRainCounts()->getLastChangeDate()) . "<br />";
	print "Last Sample Date: " . date($cfg->getDateTimeFormat(), $stats->getRainCounts()->getLastSampleDate()) . "<br />";
	print "Last Sample Value: " . $stats->getRainCounts()->getLastSampleValue() . "<br />";
	print "Days with no change: " . $stats->getRainCounts()->daysWithNoChange() . "<br />";
	print "Day start count : " . $stats->getRainCounts()->getDayStartCount()->getCountValue() . " Time: " . date($cfg->getDateTimeFormat(), $stats->getRainCounts()->getDayStartCount()->getCountTime()) . "<br />";
	print "Yesterday start count : " . $stats->getRainCounts()->getYesterdayStartCount()->getCountValue() . " Time: " . date($cfg->getDateTimeFormat(), $stats->getRainCounts()->getYesterdayStartCount()->getCountTime()) . "<br />";
	print "Week start count : " . $stats->getRainCounts()->getWeekStartCount()->getCountValue() . " Time: " . date($cfg->getDateTimeFormat(), $stats->getRainCounts()->getWeekStartCount()->getCountTime()) . "<br />";
	print "Month start count : " . $stats->getRainCounts()->getMonthStartCount()->getCountValue() . " Time: " . date($cfg->getDateTimeFormat(), $stats->getRainCounts()->getMonthStartCount()->getCountTime()) . "<br />";
	print "Year start count : " . $stats->getRainCounts()->getYearStartCount()->getCountValue() . " Time: " . date($cfg->getDateTimeFormat(), $stats->getRainCounts()->getYearStartCount()->getCountTime()) . "<br />";
	print "Cum start count : " . $stats->getRainCounts()->getCumulativeStartCount()->getCountValue() . " Time: " . date($cfg->getDateTimeFormat(), $stats->getRainCounts()->getCumulativeStartCount()->getCountTime()) . "<br />";
	print "Highest daily value Week: " . $stats->getRainCounts()->getHighestDailyValueThisWeek()->getMax() . " Time " . date($cfg->getDateTimeFormat(), $stats->getRainCounts()->getHighestDailyValueThisWeek()->getMaxDate()) . "<br />"; 
	print "Highest daily value Month: " . $stats->getRainCounts()->getHighestDailyValueThisMonth()->getMax() . " Time " . date($cfg->getDateTimeFormat(), $stats->getRainCounts()->getHighestDailyValueThisMonth()->getMaxDate()) . "<br />";
	print "Highest daily value Year: " . $stats->getRainCounts()->getHighestDailyValueThisYear()->getMax() . " Time " . date($cfg->getDateTimeFormat(), $stats->getRainCounts()->getHighestDailyValueThisYear()->getMaxDate()) . "<br />";
	print "Highest daily value Cum: " . $stats->getRainCounts()->getHighestDailyValueCumulative()->getMax() . " Time " . date($cfg->getDateTimeFormat(), $stats->getRainCounts()->getHighestDailyValueCumulative()->getMaxDate()) . "<br />";
	print "Max Rate Today: " . $stats->getRainCounts()->getMaxRateToday()->getMax()	. " Time " . date($cfg->getDateTimeFormat(), $stats->getRainCounts()->getMaxRateToday()->getMaxDate()) . "<br />";
	print "Max Rate Yesterday: " . $stats->getRainCounts()->getMaxRateYesterday()->getMax()	. " Time " . date($cfg->getDateTimeFormat(), $stats->getRainCounts()->getMaxRateYesterday()->getMaxDate()) . "<br />";
	print "Max Rate Week: " . $stats->getRainCounts()->getMaxRateThisWeek()->getMax()	. " Time " . date($cfg->getDateTimeFormat(), $stats->getRainCounts()->getMaxRateThisWeek()->getMaxDate()) . "<br />";
	print "Max Rate Month: " . $stats->getRainCounts()->getMaxRateThisMonth()->getMax()	. " Time " . date($cfg->getDateTimeFormat(), $stats->getRainCounts()->getMaxRateThisMonth()->getMaxDate()) . "<br />";
	print "Max Rate Year: " . $stats->getRainCounts()->getMaxRateThisYear()->getMax()	. " Time " . date($cfg->getDateTimeFormat(), $stats->getRainCounts()->getMaxRateThisYear()->getMaxDate()) . "<br />";
	print "Max Rate Cumulative: " . $stats->getRainCounts()->getMaxRateCumulative()->getMax()	. " Time " . date($cfg->getDateTimeFormat(), $stats->getRainCounts()->getMaxRateCumulative()->getMaxDate()) . "<br />";
	print "Count change days week: " . $stats->getRainCounts()->getCountChangeDaysThisWeek() . " Month " . $stats->getRainCounts()->getCountChangeDaysThisMonth() . " Year " . $stats->getRainCounts()->getCountChangeDaysThisYear() . " Cum " . $stats->getRainCounts()->getCountChangeDaysCumulative() . "<br />";
	for ($i = 1; $i <= 60; $i++)
	{
		print "Average rate/min last $i minutes: " . $stats->getRainCounts()->getAverageRatePerMinute($i) . "<br />"; 
	}
	for ($i = 1; $i <= 24; $i++)
	{
		print "Average hourly rate/min last $i hours: " . $stats->getRainCounts()->getHourlyAverageRatePerMinute($i) . "<br />";
	}
	print "Counts: Day " . $stats->getRainCounts()->getDayCount() . " Yesterday " . $stats->getRainCounts()->getYesterdayCount() . 
		" Week " . $stats->getRainCounts()->getWeekCount() . " Month " . $stats->getRainCounts()->getMonthCount() . " Year " . 
		$stats->getRainCounts()->getYearCount() . "Cum " . $stats->getRainCounts()->getCumulativeCount() . "<br />";
	for ($i = 1; $i <= 60; $i++)
	{
		print "Minute count last $i minutes: " . $stats->getRainCounts()->getMinuteCount($i) . "<br />";
	}
	for ($i = 1; $i <= 24; $i++)
	{
		print "Hour count last $i hours: " . $stats->getRainCounts()->getHourCount($i) . "<br />";
	}
	print "Max rate last hour : " . $stats->getRainCounts()->getMaxRateLastHour()->getMax() . "<br />";
	for ($i = 1; $i <= 24; $i++)
	{
		print "Max rate hour $i: " . $stats->getRainCounts()->getMaxRateHour($i)->getMax() . "<br />";
	}
	print "\n";
	
	print "<h1>Lightning Counts</h1>";
	print "Last Change Date: " . date($cfg->getDateTimeFormat(),$stats->getLightningCounts()->getLastChangeDate()) . "<br />";
	print "Last Sample Date: " . date($cfg->getDateTimeFormat(), $stats->getLightningCounts()->getLastSampleDate()) . "<br />";
	print "Last Sample Value: " . $stats->getLightningCounts()->getLastSampleValue() . "<br />";
	print "Days with no change: " . $stats->getLightningCounts()->daysWithNoChange() . "<br />";
	print "Day start count : " . $stats->getLightningCounts()->getDayStartCount()->getCountValue() . " Time: " . date($cfg->getDateTimeFormat(), $stats->getLightningCounts()->getDayStartCount()->getCountTime()) . "<br />";
	print "Yesterday start count : " . $stats->getLightningCounts()->getYesterdayStartCount()->getCountValue() . " Time: " . date($cfg->getDateTimeFormat(), $stats->getLightningCounts()->getYesterdayStartCount()->getCountTime()) . "<br />";
	print "Week start count : " . $stats->getLightningCounts()->getWeekStartCount()->getCountValue() . " Time: " . date($cfg->getDateTimeFormat(), $stats->getLightningCounts()->getWeekStartCount()->getCountTime()) . "<br />";
	print "Month start count : " . $stats->getLightningCounts()->getMonthStartCount()->getCountValue() . " Time: " . date($cfg->getDateTimeFormat(), $stats->getLightningCounts()->getMonthStartCount()->getCountTime()) . "<br />";
	print "Year start count : " . $stats->getLightningCounts()->getYearStartCount()->getCountValue() . " Time: " . date($cfg->getDateTimeFormat(), $stats->getLightningCounts()->getYearStartCount()->getCountTime()) . "<br />";
	print "Cum start count : " . $stats->getLightningCounts()->getCumulativeStartCount()->getCountValue() . " Time: " . date($cfg->getDateTimeFormat(), $stats->getLightningCounts()->getCumulativeStartCount()->getCountTime()) . "<br />";
	print "Highest daily value Week: " . $stats->getLightningCounts()->getHighestDailyValueThisWeek()->getMax() . " Time " . date($cfg->getDateTimeFormat(), $stats->getLightningCounts()->getHighestDailyValueThisWeek()->getMaxDate()) . "<br />";
	print "Highest daily value Month: " . $stats->getLightningCounts()->getHighestDailyValueThisMonth()->getMax() . " Time " . date($cfg->getDateTimeFormat(), $stats->getLightningCounts()->getHighestDailyValueThisMonth()->getMaxDate()) . "<br />";
	print "Highest daily value Year: " . $stats->getLightningCounts()->getHighestDailyValueThisYear()->getMax() . " Time " . date($cfg->getDateTimeFormat(), $stats->getLightningCounts()->getHighestDailyValueThisYear()->getMaxDate()) . "<br />";
	print "Highest daily value Cum: " . $stats->getLightningCounts()->getHighestDailyValueCumulative()->getMax() . " Time " . date($cfg->getDateTimeFormat(), $stats->getLightningCounts()->getHighestDailyValueCumulative()->getMaxDate()) . "<br />";
	print "Max Rate Today: " . $stats->getLightningCounts()->getMaxRateToday()->getMax()	. " Time " . date($cfg->getDateTimeFormat(), $stats->getLightningCounts()->getMaxRateToday()->getMaxDate()) . "<br />";
	print "Max Rate Yesterday: " . $stats->getLightningCounts()->getMaxRateYesterday()->getMax()	. " Time " . date($cfg->getDateTimeFormat(), $stats->getLightningCounts()->getMaxRateYesterday()->getMaxDate()) . "<br />";
	print "Max Rate Week: " . $stats->getLightningCounts()->getMaxRateThisWeek()->getMax()	. " Time " . date($cfg->getDateTimeFormat(), $stats->getLightningCounts()->getMaxRateThisWeek()->getMaxDate()) . "<br />";
	print "Max Rate Month: " . $stats->getLightningCounts()->getMaxRateThisMonth()->getMax()	. " Time " . date($cfg->getDateTimeFormat(), $stats->getLightningCounts()->getMaxRateThisMonth()->getMaxDate()) . "<br />";
	print "Max Rate Year: " . $stats->getLightningCounts()->getMaxRateThisYear()->getMax()	. " Time " . date($cfg->getDateTimeFormat(), $stats->getLightningCounts()->getMaxRateThisYear()->getMaxDate()) . "<br />";
	print "Max Rate Cumulative: " . $stats->getLightningCounts()->getMaxRateCumulative()->getMax()	. " Time " . date($cfg->getDateTimeFormat(), $stats->getLightningCounts()->getMaxRateCumulative()->getMaxDate()) . "<br />";
	print "Count change days week: " . $stats->getLightningCounts()->getCountChangeDaysThisWeek() . " Month " . $stats->getLightningCounts()->getCountChangeDaysThisMonth() . " Year " . $stats->getLightningCounts()->getCountChangeDaysThisYear() . " Cum " . $stats->getLightningCounts()->getCountChangeDaysCumulative() . "<br />";
	for ($i = 1; $i <= 60; $i++)
	{
		print "Average rate/min last $i minutes: " . $stats->getLightningCounts()->getAverageRatePerMinute($i) . "<br />";
	}
	for ($i = 1; $i <= 24; $i++)
	{
		print "Average hourly rate/min last $i hours: " . $stats->getLightningCounts()->getHourlyAverageRatePerMinute($i) . "<br />";
	}
	print "Counts: Day " . $stats->getLightningCounts()->getDayCount() . " Yesterday " . $stats->getLightningCounts()->getYesterdayCount() .
			" Week " . $stats->getLightningCounts()->getWeekCount() . " Month " . $stats->getLightningCounts()->getMonthCount() . " Year " . 
		$stats->getLightningCounts()->getYearCount() . "Cum " . $stats->getLightningCounts()->getCumulativeCount() . "<br />";
	for ($i = 1; $i <= 60; $i++)
	{
		print "Minute count last $i minutes: " . $stats->getLightningCounts()->getMinuteCount($i) . "<br />";
	}
	for ($i = 1; $i <= 24; $i++)
	{
		print "Hour count last $i hours: " . $stats->getLightningCounts()->getHourCount($i) . "<br />";
	}
	print "Max rate last hour : " . $stats->getLightningCounts()->getMaxRateLastHour()->getMax() . "<br />";
	for ($i = 1; $i <= 24; $i++)
	{
		print "Max rate hour $i: " . $stats->getLightningCounts()->getMaxRateHour($i)->getMax() . "<br />";
	}
	print "\n";
	
	print "<h1>Wind Run Counts</h1>";
	print "Last Change Date: " . date($cfg->getDateTimeFormat(),$stats->getWindRunCounts()->getLastChangeDate()) . "<br />";
	print "Last Sample Date: " . date($cfg->getDateTimeFormat(), $stats->getWindRunCounts()->getLastSampleDate()) . "<br />";
	print "Last Sample Value: " . $stats->getWindRunCounts()->getLastSampleValue() . "<br />";
	print "Days with no change: " . $stats->getWindRunCounts()->daysWithNoChange() . "<br />";
	print "Day start count : " . $stats->getWindRunCounts()->getDayStartCount()->getCountValue() . " Time: " . date($cfg->getDateTimeFormat(), $stats->getWindRunCounts()->getDayStartCount()->getCountTime()) . "<br />";
	print "Yesterday start count : " . $stats->getWindRunCounts()->getYesterdayStartCount()->getCountValue() . " Time: " . date($cfg->getDateTimeFormat(), $stats->getWindRunCounts()->getYesterdayStartCount()->getCountTime()) . "<br />";
	print "Week start count : " . $stats->getWindRunCounts()->getWeekStartCount()->getCountValue() . " Time: " . date($cfg->getDateTimeFormat(), $stats->getWindRunCounts()->getWeekStartCount()->getCountTime()) . "<br />";
	print "Month start count : " . $stats->getWindRunCounts()->getMonthStartCount()->getCountValue() . " Time: " . date($cfg->getDateTimeFormat(), $stats->getWindRunCounts()->getMonthStartCount()->getCountTime()) . "<br />";
	print "Year start count : " . $stats->getWindRunCounts()->getYearStartCount()->getCountValue() . " Time: " . date($cfg->getDateTimeFormat(), $stats->getWindRunCounts()->getYearStartCount()->getCountTime()) . "<br />";
	print "Cum start count : " . $stats->getWindRunCounts()->getCumulativeStartCount()->getCountValue() . " Time: " . date($cfg->getDateTimeFormat(), $stats->getWindRunCounts()->getCumulativeStartCount()->getCountTime()) . "<br />";
	print "Highest daily value Week: " . $stats->getWindRunCounts()->getHighestDailyValueThisWeek()->getMax() . " Time " . date($cfg->getDateTimeFormat(), $stats->getWindRunCounts()->getHighestDailyValueThisWeek()->getMaxDate()) . "<br />";
	print "Highest daily value Month: " . $stats->getWindRunCounts()->getHighestDailyValueThisMonth()->getMax() . " Time " . date($cfg->getDateTimeFormat(), $stats->getWindRunCounts()->getHighestDailyValueThisMonth()->getMaxDate()) . "<br />";
	print "Highest daily value Year: " . $stats->getWindRunCounts()->getHighestDailyValueThisYear()->getMax() . " Time " . date($cfg->getDateTimeFormat(), $stats->getWindRunCounts()->getHighestDailyValueThisYear()->getMaxDate()) . "<br />";
	print "Highest daily value Cum: " . $stats->getWindRunCounts()->getHighestDailyValueCumulative()->getMax() . " Time " . date($cfg->getDateTimeFormat(), $stats->getWindRunCounts()->getHighestDailyValueCumulative()->getMaxDate()) . "<br />";
	print "Max Rate Today: " . $stats->getWindRunCounts()->getMaxRateToday()->getMax()	. " Time " . date($cfg->getDateTimeFormat(), $stats->getWindRunCounts()->getMaxRateToday()->getMaxDate()) . "<br />";
	print "Max Rate Yesterday: " . $stats->getWindRunCounts()->getMaxRateYesterday()->getMax()	. " Time " . date($cfg->getDateTimeFormat(), $stats->getWindRunCounts()->getMaxRateYesterday()->getMaxDate()) . "<br />";
	print "Max Rate Week: " . $stats->getWindRunCounts()->getMaxRateThisWeek()->getMax()	. " Time " . date($cfg->getDateTimeFormat(), $stats->getWindRunCounts()->getMaxRateThisWeek()->getMaxDate()) . "<br />";
	print "Max Rate Year: " . $stats->getWindRunCounts()->getMaxRateThisYear()->getMax()	. " Time " . date($cfg->getDateTimeFormat(), $stats->getWindRunCounts()->getMaxRateThisYear()->getMaxDate()) . "<br />";
	print "Max Rate Cumulative: " . $stats->getWindRunCounts()->getMaxRateCumulative()->getMax()	. " Time " . date($cfg->getDateTimeFormat(), $stats->getWindRunCounts()->getMaxRateCumulative()->getMaxDate()) . "<br />";
	print "Count change days week: " . $stats->getWindRunCounts()->getCountChangeDaysThisWeek() . " Month " . $stats->getWindRunCounts()->getCountChangeDaysThisMonth() . " Year " . $stats->getWindRunCounts()->getCountChangeDaysThisYear() . " Cum " . $stats->getWindRunCounts()->getCountChangeDaysCumulative() . "<br />";
	for ($i = 1; $i <= 60; $i++)
	{
		print "Average rate/min last $i minutes: " . $stats->getWindRunCounts()->getAverageRatePerMinute($i) . "<br />";
	}
	for ($i = 1; $i <= 24; $i++)
	{
		print "Average hourly rate/min last $i hours: " . $stats->getWindRunCounts()->getHourlyAverageRatePerMinute($i) . "<br />";
	}
	print "Counts: Day " . $stats->getWindRunCounts()->getDayCount() . " Yesterday " . $stats->getWindRunCounts()->getYesterdayCount() .
			" Week " . $stats->getWindRunCounts()->getWeekCount() . " Month " . $stats->getWindRunCounts()->getMonthCount() . " Year " . 
		$stats->getWindRunCounts()->getYearCount() . "Cum " . $stats->getWindRunCounts()->getCumulativeCount() . "<br />";
	for ($i = 1; $i <= 60; $i++)
	{
		print "Minute count last $i minutes: " . $stats->getWindRunCounts()->getMinuteCount($i) . "<br />";
	}
	for ($i = 1; $i <= 24; $i++)
	{
		print "Hour count last $i hours: " . $stats->getWindRunCounts()->getHourCount($i) . "<br />";
	}
	print "Max rate last hour : " . $stats->getWindRunCounts()->getMaxRateLastHour()->getMax() . "<br />";
	for ($i = 1; $i <= 24; $i++)
	{
		print "Max rate hour $i: " . $stats->getWindRunCounts()->getMaxRateHour($i)->getMax() . "<br />";
	}
	print "\n";
	
	?>	
</body>
</html>
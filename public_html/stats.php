<!DOCTYPE html>
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : Exploitable 
Description: A two-column, fixed-width design with dark color scheme.
Version    : 1.0
Released   : 20090327

-->
<html lang="en">


<?php
require_once "config/config.php";

$cfg = config::getInstance();
date_default_timezone_set($cfg->getTimeZone());

require_once "classes/weatherData.php";
require_once "classes/weatherStatistics.php";
require_once "classes/weatherHistory.php";
require_once "classes/weatherConversions.php";

$data = new weatherData();
$stats = new weatherStatistics();
$history = new WeatherHistory();

$outdoorTempStats = $stats->getOutdoorTempStats();
$daytimeTempStats = $stats->getDaytimeOutdoorTemperatureStats();
$nighttimeTempStats = $stats->getNighttimeOutdoorTemperatureStats();
$heatIndexStats = $stats->getHeatIndexStats();
$windChillStats = $stats->getWindChillStats();
$pressureStats = $stats->getPressureStats();
$humidityStats = $stats->getOutdoorHumidityStats();
$rainCounts = $stats->getRainCounts();
$lightningCounts = $stats->getLightningCounts();

?>

<head>
<meta name="keywords" content="" />
<meta name="keywords" content="germantown,Clopper&#39;s,Mill,Clopper&#39;s Mill,weather" />
<meta name="description" content="Personal weather station located in Germantown, MD 20874." />

<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<script src="config/config.js.php"></script>
<script src="js/jQuery.js"></script>
<script src="js/jquery-migrate-1.2.1.min.js"></script>
<script src="js/jQueryTimer.js"></script>
<script src="js/weatherconversions.js"></script>
<script src='js/date.js'></script>
<script src='js/knockout.js'></script>
<script src='js/knockoutmapping.js'></script>
<script src='js/weathermodel.js'></script>

<title>Clopper&#39;s Mill East Weather</title>

<link href="css/style.css" rel="stylesheet" type="text/css" media="screen" />
<link id="favicon" rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

</head>

<body>
<script>

$(document).ready(function(){
	
	initWeatherDataModel();
	
	// get WX data every 15 secs
	$(document).everyTime(dataRefreshRate, retrieveWeatherData);

	//get WX stats every minute
	$(document).everyTime(statsRefreshRate, retrieveWeatherStats);

	//get WX history every minute
	$(document).everyTime(historyRefreshRate, retrieveWeatherHistory);

	//get update web cam image every 30 secs
	$("#webcamThumbnail").everyTime(webcamRefreshRate, updateWebcam);

	// update favicon every 10 minutes based on weather forecast
	$(document).everyTime(faviconRefreshRate, updateFavicon);
});

</script>

	<div id="wrapper">
		

<?php
include_once "pageheader.php";
include_once "pagenavigation.php";
?>

		<div id="page">
			 
<?php
include_once "pageadvisory.php";
?>

			<!--  		<div id="page-bgtop"> -->
			<div id="page-bgbtm">
				<div id="content">
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">Trends</h2>
								<p class="meta">
								</p>
								<div class="entry">
<table class="fullTable">

<tr class="table-top">
<td>TIME</td>
<td>TEMP</td>
<td>WIND AVG</td>
<td>WIND GUST</td>
<td>HUMIDITY</td>
<td>PRESSURE</td>
<td>RAIN</td>
<td>SOLAR</td>
</tr>

<tr class="column-light">
<td>Current</td>
<td><span data-bind="text: formatNumberDP(OutdoorTemperature())"><?php print formatNumberDP($data->getOutdoorTemperature())?></span> &deg;F</td>
<td><span data-bind="text: formatNumberDP(AvgWindSpeed())"><?php print formatNumberDP($data->getAvgWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(AvgWindDir())"><?php print getWindDirStr($data->getAvgWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(WindSpeed())"><?php print formatNumberDP($data->getWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(WindDir())"><?php print getWindDirStr($data->getWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(OutdoorHumidity())"><?php print formatNumberDP($data->getOutdoorHumidity())?></span>%</td>
<td><span data-bind="text: formatNumberDP(Pressure())"><?php print formatNumberDP($data->getPressure())?></span> in.</td>
<td><span data-bind="text: formatNumberDP(RainCounts.DayCount())"><?php print formatNumberDP($rainCounts->getDayCount())?></span> in.</td>
<td><span data-bind="text: formatNumberDP(Solar())"><?php print formatNumberDP($data->getSolar())?></span></td>
</tr>

<tr class="column-dark">
<td>5 minutes ago</td>
<?php $histData = $history->getWeatherData(5)?>
<td><span data-bind="text: formatNumberDP(History()[0].OutdoorTemperature())"><?php print formatNumberDP($histData->getOutdoorTemperature())?></span> &deg;F</td>
<td><span data-bind="text: formatNumberDP(History()[0].AvgWindSpeed())"><?php print formatNumberDP($histData->getAvgWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(History()[0].AvgWindDir())"><?php print getWindDirStr($histData->getAvgWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(History()[0].WindSpeed())"><?php print formatNumberDP($histData->getWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(History()[0].WindDir())"><?php print getWindDirStr($histData->getWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(History()[0].OutdoorHumidity())"><?php print formatNumberdP($histData->getOutdoorHumidity())?></span>%</td>
<td><span data-bind="text: formatNumberDP(History()[0].Pressure())"><?php print formatNumberDP($histData->getPressure())?></span> in.</td>
<td><span data-bind="text: formatNumberDP(getRainData(History()[0], RainCounts.DayStartCount.CountTime(), RainCounts.DayStartCount.CountValue(), RainCounts.YesterdayStartCount.CountValue()))"><?php print formatNumberDP($history->getRainData(5))?></span> in.</td>
<td><span data-bind="text: formatNumberDP(History()[0].Solar())"><?php print formatNumberDP($histData->getSolar())?></span></td>
</tr>

<tr class="column-light">
<td>10 minutes ago</td>
<?php $histData = $history->getWeatherData(10)?>
<td><span data-bind="text: formatNumberDP(History()[1].OutdoorTemperature())"><?php print formatNumberDP($histData->getOutdoorTemperature())?></span> &deg;F</td>
<td><span data-bind="text: formatNumberDP(History()[1].AvgWindSpeed())"><?php print formatNumberDP($histData->getAvgWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(History()[1].AvgWindDir())"><?php print getWindDirStr($histData->getAvgWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(History()[1].WindSpeed())"><?php print formatNumberDP($histData->getWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(History()[1].WindDir())"><?php print getWindDirStr($histData->getWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(History()[1].OutdoorHumidity())"><?php print formatNumberdP($histData->getOutdoorHumidity())?></span>%</td>
<td><span data-bind="text: formatNumberDP(History()[1].Pressure())"><?php print formatNumberDP($histData->getPressure())?></span> in.</td>
<td><span data-bind="text: formatNumberDP(getRainData(History()[1], RainCounts.DayStartCount.CountTime(), RainCounts.DayStartCount.CountValue(), RainCounts.YesterdayStartCount.CountValue()))"><?php print formatNumberDP($history->getRainData(10))?></span> in.</td>
<td><span data-bind="text: formatNumberDP(History()[1].Solar())"><?php print formatNumberDP($histData->getSolar())?></span></td>
</tr>

<tr class="column-dark">
<td>15 minutes ago</td>
<?php $histData = $history->getWeatherData(15)?>
<td><span data-bind="text: formatNumberDP(History()[2].OutdoorTemperature())"><?php print formatNumberDP($histData->getOutdoorTemperature())?></span> &deg;F</td>
<td><span data-bind="text: formatNumberDP(History()[2].AvgWindSpeed())"><?php print formatNumberDP($histData->getAvgWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(History()[2].AvgWindDir())"><?php print getWindDirStr($histData->getAvgWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(History()[2].WindSpeed())"><?php print formatNumberDP($histData->getWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(History()[2].WindDir())"><?php print getWindDirStr($histData->getWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(History()[2].OutdoorHumidity())"><?php print formatNumberdP($histData->getOutdoorHumidity())?></span>%</td>
<td><span data-bind="text: formatNumberDP(History()[2].Pressure())"><?php print formatNumberDP($histData->getPressure())?></span> in.</td>
<td><span data-bind="text: formatNumberDP(getRainData(History()[2], RainCounts.DayStartCount.CountTime(), RainCounts.DayStartCount.CountValue(), RainCounts.YesterdayStartCount.CountValue()))"><?php print formatNumberDP($history->getRainData(15))?></span> in.</td>
<td><span data-bind="text: formatNumberDP(History()[2].Solar())"><?php print formatNumberDP($histData->getSolar())?></span></td>
</tr>

<tr class="column-light">
<td>20 minutes ago</td>
<?php $histData = $history->getWeatherData(20)?>
<td><span data-bind="text: formatNumberDP(History()[3].OutdoorTemperature())"><?php print formatNumberDP($histData->getOutdoorTemperature())?></span> &deg;F</td>
<td><span data-bind="text: formatNumberDP(History()[3].AvgWindSpeed())"><?php print formatNumberDP($histData->getAvgWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(History()[3].AvgWindDir())"><?php print getWindDirStr($histData->getAvgWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(History()[3].WindSpeed())"><?php print formatNumberDP($histData->getWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(History()[3].WindDir())"><?php print getWindDirStr($histData->getWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(History()[3].OutdoorHumidity())"><?php print formatNumberdP($histData->getOutdoorHumidity())?></span>%</td>
<td><span data-bind="text: formatNumberDP(History()[3].Pressure())"><?php print formatNumberDP($histData->getPressure())?></span> in.</td>
<td><span data-bind="text: formatNumberDP(getRainData(History()[3], RainCounts.DayStartCount.CountTime(), RainCounts.DayStartCount.CountValue(), RainCounts.YesterdayStartCount.CountValue()))"><?php print formatNumberDP($history->getRainData(20))?></span> in.</td>
<td><span data-bind="text: formatNumberDP(History()[3].Solar())"><?php print formatNumberDP($histData->getSolar())?></span></td>
</tr>

<tr class="column-dark">
<td>30 minutes ago</td>
<?php $histData = $history->getWeatherData(30)?>
<td><span data-bind="text: formatNumberDP(History()[4].OutdoorTemperature())"><?php print formatNumberDP($histData->getOutdoorTemperature())?></span> &deg;F</td>
<td><span data-bind="text: formatNumberDP(History()[4].AvgWindSpeed())"><?php print formatNumberDP($histData->getAvgWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(History()[4].AvgWindDir())"><?php print getWindDirStr($histData->getAvgWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(History()[4].WindSpeed())"><?php print formatNumberDP($histData->getWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(History()[4].WindDir())"><?php print getWindDirStr($histData->getWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(History()[4].OutdoorHumidity())"><?php print formatNumberdP($histData->getOutdoorHumidity())?></span>%</td>
<td><span data-bind="text: formatNumberDP(History()[4].Pressure())"><?php print formatNumberDP($histData->getPressure())?></span> in.</td>
<td><span data-bind="text: formatNumberDP(getRainData(History()[4], RainCounts.DayStartCount.CountTime(), RainCounts.DayStartCount.CountValue(), RainCounts.YesterdayStartCount.CountValue()))"><?php print formatNumberDP($history->getRainData(30))?></span> in.</td>
<td><span data-bind="text: formatNumberDP(History()[4].Solar())"><?php print formatNumberDP($histData->getSolar())?></span></td>
</tr>

<tr class="column-light">
<td>45 minutes ago</td>
<?php $histData = $history->getWeatherData(45)?>
<td><span data-bind="text: formatNumberDP(History()[5].OutdoorTemperature())"><?php print formatNumberDP($histData->getOutdoorTemperature())?></span> &deg;F</td>
<td><span data-bind="text: formatNumberDP(History()[5].AvgWindSpeed())"><?php print formatNumberDP($histData->getAvgWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(History()[5].AvgWindDir())"><?php print getWindDirStr($histData->getAvgWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(History()[5].WindSpeed())"><?php print formatNumberDP($histData->getWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(History()[5].WindDir())"><?php print getWindDirStr($histData->getWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(History()[5].OutdoorHumidity())"><?php print formatNumberdP($histData->getOutdoorHumidity())?></span>%</td>
<td><span data-bind="text: formatNumberDP(History()[5].Pressure())"><?php print formatNumberDP($histData->getPressure())?></span> in.</td>
<td><span data-bind="text: formatNumberDP(getRainData(History()[5], RainCounts.DayStartCount.CountTime(), RainCounts.DayStartCount.CountValue(), RainCounts.YesterdayStartCount.CountValue()))"><?php print formatNumberDP($history->getRainData(45))?></span> in.</td>
<td><span data-bind="text: formatNumberDP(History()[5].Solar())"><?php print formatNumberDP($histData->getSolar())?></span></td>
</tr>

<tr class="column-dark">
<td>60 minutes ago</td>
<?php $histData = $history->getWeatherData(60)?>
<td><span data-bind="text: formatNumberDP(History()[6].OutdoorTemperature())"><?php print formatNumberDP($histData->getOutdoorTemperature())?></span> &deg;F</td>
<td><span data-bind="text: formatNumberDP(History()[6].AvgWindSpeed())"><?php print formatNumberDP($histData->getAvgWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(History()[6].AvgWindDir())"><?php print getWindDirStr($histData->getAvgWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(History()[6].WindSpeed())"><?php print formatNumberDP($histData->getWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(History()[6].WindDir())"><?php print getWindDirStr($histData->getWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(History()[6].OutdoorHumidity())"><?php print formatNumberdP($histData->getOutdoorHumidity())?></span>%</td>
<td><span data-bind="text: formatNumberDP(History()[6].Pressure())"><?php print formatNumberDP($histData->getPressure())?></span> in.</td>
<td><span data-bind="text: formatNumberDP(getRainData(History()[6], RainCounts.DayStartCount.CountTime(), RainCounts.DayStartCount.CountValue(), RainCounts.YesterdayStartCount.CountValue()))"><?php print formatNumberDP($history->getRainData(60))?></span> in.</td>
<td><span data-bind="text: formatNumberDP(History()[6].Solar())"><?php print formatNumberDP($histData->getSolar())?></span></td>
</tr>

<tr class="column-light">
<td>75 minutes ago</td>
<?php $histData = $history->getWeatherData(75)?>
<td><span data-bind="text: formatNumberDP(History()[7].OutdoorTemperature())"><?php print formatNumberDP($histData->getOutdoorTemperature())?></span> &deg;F</td>
<td><span data-bind="text: formatNumberDP(History()[7].AvgWindSpeed())"><?php print formatNumberDP($histData->getAvgWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(History()[7].AvgWindDir())"><?php print getWindDirStr($histData->getAvgWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(History()[7].WindSpeed())"><?php print formatNumberDP($histData->getWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(History()[7].WindDir())"><?php print getWindDirStr($histData->getWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(History()[7].OutdoorHumidity())"><?php print formatNumberdP($histData->getOutdoorHumidity())?></span>%</td>
<td><span data-bind="text: formatNumberDP(History()[7].Pressure())"><?php print formatNumberDP($histData->getPressure())?></span> in.</td>
<td><span data-bind="text: formatNumberDP(getRainData(History()[7], RainCounts.DayStartCount.CountTime(), RainCounts.DayStartCount.CountValue(), RainCounts.YesterdayStartCount.CountValue()))"><?php print formatNumberDP($history->getRainData(75))?></span> in.</td>
<td><span data-bind="text: formatNumberDP(History()[7].Solar())"><?php print formatNumberDP($histData->getSolar())?></span></td>
</tr>

<tr class="column-dark">
<td>90 minutes ago</td>
<?php $histData = $history->getWeatherData(90)?>
<td><span data-bind="text: formatNumberDP(History()[8].OutdoorTemperature())"><?php print formatNumberDP($histData->getOutdoorTemperature())?></span> &deg;F</td>
<td><span data-bind="text: formatNumberDP(History()[8].AvgWindSpeed())"><?php print formatNumberDP($histData->getAvgWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(History()[8].AvgWindDir())"><?php print getWindDirStr($histData->getAvgWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(History()[8].WindSpeed())"><?php print formatNumberDP($histData->getWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(History()[8].WindDir())"><?php print getWindDirStr($histData->getWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(History()[8].OutdoorHumidity())"><?php print formatNumberdP($histData->getOutdoorHumidity())?></span>%</td>
<td><span data-bind="text: formatNumberDP(History()[8].Pressure())"><?php print formatNumberDP($histData->getPressure())?></span> in.</td>
<td><span data-bind="text: formatNumberDP(getRainData(History()[8], RainCounts.DayStartCount.CountTime(), RainCounts.DayStartCount.CountValue(), RainCounts.YesterdayStartCount.CountValue()))"><?php print formatNumberDP($history->getRainData(90))?></span> in.</td>
<td><span data-bind="text: formatNumberDP(History()[8].Solar())"><?php print formatNumberDP($histData->getSolar())?></span></td>
</tr>

<tr class="column-light">
<td>105 minutes ago</td>
<?php $histData = $history->getWeatherData(105)?>
<td><span data-bind="text: formatNumberDP(History()[9].OutdoorTemperature())"><?php print formatNumberDP($histData->getOutdoorTemperature())?></span> &deg;F</td>
<td><span data-bind="text: formatNumberDP(History()[9].AvgWindSpeed())"><?php print formatNumberDP($histData->getAvgWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(History()[9].AvgWindDir())"><?php print getWindDirStr($histData->getAvgWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(History()[9].WindSpeed())"><?php print formatNumberDP($histData->getWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(History()[9].WindDir())"><?php print getWindDirStr($histData->getWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(History()[9].OutdoorHumidity())"><?php print formatNumberdP($histData->getOutdoorHumidity())?></span>%</td>
<td><span data-bind="text: formatNumberDP(History()[9].Pressure())"><?php print formatNumberDP($histData->getPressure())?></span> in.</td>
<td><span data-bind="text: formatNumberDP(getRainData(History()[9], RainCounts.DayStartCount.CountTime(), RainCounts.DayStartCount.CountValue(), RainCounts.YesterdayStartCount.CountValue()))"><?php print formatNumberDP($history->getRainData(105))?></span> in.</td>
<td><span data-bind="text: formatNumberDP(History()[9].Solar())"><?php print formatNumberDP($histData->getSolar())?></span></td>
</tr>

<tr class="column-dark">
<td>120 minutes ago</td>
<?php $histData = $history->getWeatherData(120)?>
<td><span data-bind="text: formatNumberDP(History()[10].OutdoorTemperature())"><?php print formatNumberDP($histData->getOutdoorTemperature())?></span> &deg;F</td>
<td><span data-bind="text: formatNumberDP(History()[10].AvgWindSpeed())"><?php print formatNumberDP($histData->getAvgWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(History()[10].AvgWindDir())"><?php print getWindDirStr($histData->getAvgWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(History()[10].WindSpeed())"><?php print formatNumberDP($histData->getWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(History()[10].WindDir())"><?php print getWindDirStr($histData->getWindDir())?></span></td>
<td><span data-bind="text: formatNumberDP(History()[10].OutdoorHumidity())"><?php print formatNumberdP($histData->getOutdoorHumidity())?></span>%</td>
<td><span data-bind="text: formatNumberDP(History()[10].Pressure())"><?php print formatNumberDP($histData->getPressure())?></span> in.</td>
<td><span data-bind="text: formatNumberDP(getRainData(History()[10], RainCounts.DayStartCount.CountTime(), RainCounts.DayStartCount.CountValue(), RainCounts.YesterdayStartCount.CountValue()))"><?php print formatNumberDP($history->getRainData(120))?></span> in.</td>
<td><span data-bind="text: formatNumberDP(History()[10].Solar())"><?php print formatNumberDP($histData->getSolar())?></span></td>
</tr>
</table>
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">Temperature Statistics</h2>
								<p class="meta">
								</p>
								<div class="entry">
<table class="fullTable">

<colgroup>
	<col id="statscol1" />
	<col id="statscol2" />
	<col id="statscol3" />
	<col id="statscol4" />
</colgroup>

<thead>
<tr class="table-top">
<td colspan="2" >TEMPERATURE HIGHS</td>
<td colspan="2" >TEMPERATURE LOWS</td>
</tr>
</thead>

<tr class="column-light">
<td>Today</td>
<td><span data-bind="fadeInText: formatNumberDP(OutdoorTempStats.DailyValues.Max())"><?php print formatNumberDP($outdoorTempStats->getDailyValues()->getMax())?></span> &deg;F at <span data-bind="fadeInText: new Date(Date.parse(OutdoorTempStats.DailyValues.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $outdoorTempStats->getDailyValues()->getMaxDate())?></span></td>
<td>Today</td>
<td><span data-bind="fadeInText: formatNumberDP(OutdoorTempStats.DailyValues.Min())"><?php print formatNumberDP($outdoorTempStats->getDailyValues()->getMin())?></span> &deg;F at <span data-bind="fadeInText: new Date(Date.parse(OutdoorTempStats.DailyValues.MinDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $outdoorTempStats->getDailyValues()->getMinDate())?></span></td>
</tr>

<tr class="column-dark">
<td >Yest.</td>
<td ><span data-bind="fadeInText: formatNumberDP(OutdoorTempStats.YesterdayValues.Max())"><?php print formatNumberDP($outdoorTempStats->getYesterdayValues()->getMax())?></span> &deg;F at <span data-bind="fadeInText: new Date(Date.parse(OutdoorTempStats.YesterdayValues.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $outdoorTempStats->getYesterdayValues()->getMaxDate())?></span></td>
<td >Yest.</td>
<td ><span data-bind="fadeInText: formatNumberDP(OutdoorTempStats.YesterdayValues.Min())"><?php print formatNumberDP($outdoorTempStats->getYesterdayValues()->getMin())?></span> &deg;F at <span data-bind="fadeInText: new Date(Date.parse(OutdoorTempStats.YesterdayValues.MinDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $outdoorTempStats->getYesterdayValues()->getMinDate())?></span></td>
</tr>

<tr class="column-light">
<td >Month</td>
<td ><span data-bind="fadeInText: formatNumberDP(OutdoorTempStats.MonthlyValues.Max())"><?php print formatNumberDP($outdoorTempStats->getMonthlyValues()->getMax())?></span> &deg;F on <span data-bind="fadeInText: new Date(Date.parse(OutdoorTempStats.MonthlyValues.MaxDate())).format(dateOnlyFormat)"><?php print date($cfg->getDateFormat(), $outdoorTempStats->getMonthlyValues()->getMaxDate())?></span></td>
<td >Month</td>
<td ><span data-bind="fadeInText: formatNumberDP(OutdoorTempStats.MonthlyValues.Min())"><?php print formatNumberDP($outdoorTempStats->getMonthlyValues()->getMin())?></span> &deg;F on <span data-bind="fadeInText: new Date(Date.parse(OutdoorTempStats.MonthlyValues.MinDate())).format(dateOnlyFormat)"><?php print date($cfg->getDateFormat(), $outdoorTempStats->getMonthlyValues()->getMinDate())?></span></td>
</tr>

<tr class="column-dark">
<td >Year</td>
<td ><span data-bind="fadeInText: formatNumberDP(OutdoorTempStats.AnnualValues.Max())"><?php print formatNumberDP($outdoorTempStats->getAnnualValues()->getMax())?></span> &deg;F on <span data-bind="fadeInText: new Date(Date.parse(OutdoorTempStats.AnnualValues.MaxDate())).format(dateOnlyFormat)"><?php print date($cfg->getDateFormat(), $outdoorTempStats->getAnnualValues()->getMaxDate())?></span></td>
<td >Year</td>
<td ><span data-bind="fadeInText: formatNumberDP(OutdoorTempStats.AnnualValues.Min())"><?php print formatNumberDP($outdoorTempStats->getAnnualValues()->getMin())?></span> &deg;F on <span data-bind="fadeInText: new Date(Date.parse(OutdoorTempStats.AnnualValues.MinDate())).format(dateOnlyFormat)"><?php print date($cfg->getDateFormat(), $outdoorTempStats->getAnnualValues()->getMinDate())?></span></td>
</tr>


<tr class="table-top">
<td colspan="2" >HOT DAYS THIS MONTH</td>
<td colspan="2" >COLD DAYS THIS MONTH</td>
</tr>

<tr class="column-light">
<td >Max &gt; 86 &deg;F</td>
<td ><span data-bind="fadeInText: TempMaxGT86F()"><?php print $stats->getTempMaxGT86F()?></span> day(s)</td>
<td >Min &lt; 32 &deg;F</td>
<td ><span data-bind="fadeInText: TempMaxLT32F()"><?php print $stats->getTempMaxLT32F()?></span> day(s)</td>
</tr>

<tr class="column-dark">
<td >Max &gt; 77 &deg;F</td>
<td ><span data-bind="fadeInText: TempMaxGT77F()"><?php print $stats->getTempMaxGT77F()?></span> day(s)</td>
<td >Min &lt; 5 &deg;F</td>
<td ><span data-bind="fadeInText: TempMaxLT5F()"><?php print $stats->getTempMaxLT5F()?></span> day(s)</td>
</tr>

<tr class="column-light">
<td ><span style="font-size: 10px;">Warmest day (6am - 6pm)</span></td>
<td ><span data-bind="fadeInText: formatNumberDP(DaytimeOutdoorTemperatureStats.MonthlyValues.Max())"><?php print formatNumberDP($daytimeTempStats->getMonthlyValues()->getMax())?></span> &deg;F on <span data-bind="fadeInText: new Date(Date.parse(DaytimeOutdoorTemperatureStats.MonthlyValues.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $daytimeTempStats->getMonthlyValues()->getMaxDate())?></span></td>
<td ><span style="font-size: 10px;">Coldest day (6am - 6pm)</span></td>
<td ><span data-bind="fadeInText: formatNumberDP(DaytimeOutdoorTemperatureStats.MonthlyValues.Min())"><?php print formatNumberDP($daytimeTempStats->getMonthlyValues()->getMin())?></span> &deg;F on <span data-bind="fadeInText: new Date(Date.parse(DaytimeOutdoorTemperatureStats.MonthlyValues.MinDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $daytimeTempStats->getMonthlyValues()->getMinDate())?></span></td>
</tr>

<tr class="column-dark">
<td ><span style="font-size: 10px;">Warmest night (6pm - 6am)</span></td>
<td ><span data-bind="fadeInText: formatNumberDP(NighttimeOutdoorTemperatureStats.MonthlyValues.Max())"><?php print formatNumberDP($nighttimeTempStats->getMonthlyValues()->getMax())?></span> &deg;F on <span data-bind="fadeInText: new Date(Date.parse(NighttimeOutdoorTemperatureStats.MonthlyValues.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $nighttimeTempStats->getMonthlyValues()->getMaxDate())?></span></td>
<td ><span style="font-size: 10px;">Coldest night (6pm - 6am)</span></td>
<td ><span data-bind="fadeInText: formatNumberDP(NighttimeOutdoorTemperatureStats.MonthlyValues.Min())"><?php print formatNumberDP($nighttimeTempStats->getMonthlyValues()->getMin())?></span> &deg;F on <span data-bind="fadeInText: new Date(Date.parse(NighttimeOutdoorTemperatureStats.MonthlyValues.MinDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $nighttimeTempStats->getMonthlyValues()->getMinDate())?></span></td>
</tr>

<tr class="table-top">
<td colspan="2" >HEAT INDEX HIGHS</td>
<td colspan="2" >WIND CHILL LOWS</td>
</tr>

<tr class="column-light">
<td >Today</td>
<td ><span data-bind="fadeInText: formatNumberDP(HeatIndexStats.DailyValues.Max())"><?php print formatNumberDP($heatIndexStats->getDailyValues()->getMax())?></span> &deg;F at <span data-bind="fadeInText: new Date(Date.parse(HeatIndexStats.DailyValues.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $heatIndexStats->getDailyValues()->getMaxDate())?></span></td>
<td >Today</td>
<td ><span data-bind="fadeInText: formatNumberDP(WindChillStats.DailyValues.Min())"><?php print formatNumberDP($windChillStats->getDailyValues()->getMin())?></span> &deg;F at <span data-bind="fadeInText: new Date(Date.parse(WindChillStats.DailyValues.MinDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $windChillStats->getDailyValues()->getMinDate())?></span></td>
</tr>

<tr class="column-dark">
<td >Yest.</td>
<td ><span data-bind="fadeInText: formatNumberDP(HeatIndexStats.YesterdayValues.Max())"><?php print formatNumberDP($heatIndexStats->getYesterdayValues()->getMax())?></span> &deg;F at <span data-bind="fadeInText: new Date(Date.parse(HeatIndexStats.YesterdayValues.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $heatIndexStats->getYesterdayValues()->getMaxDate())?></span></td>
<td >Yest.</td>
<td ><span data-bind="fadeInText: formatNumberDP(WindChillStats.YesterdayValues.Min())"><?php print formatNumberDP($windChillStats->getYesterdayValues()->getMin())?></span> &deg;F at <span data-bind="fadeInText: new Date(Date.parse(WindChillStats.YesterdayValues.MinDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $windChillStats->getYesterdayValues()->getMinDate())?></span></td>
</tr>

<tr class="column-light">
<td >Month</td>
<td ><span data-bind="fadeInText: formatNumberDP(HeatIndexStats.MonthlyValues.Max())"><?php print formatNumberDP($heatIndexStats->getMonthlyValues()->getMax())?></span> &deg;F at <span data-bind="fadeInText: new Date(Date.parse(HeatIndexStats.MonthlyValues.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $heatIndexStats->getMonthlyValues()->getMaxDate())?></span></td>
<td >Month</td>
<td ><span data-bind="fadeInText: formatNumberDP(WindChillStats.MonthlyValues.Min())"><?php print formatNumberDP($windChillStats->getMonthlyValues()->getMin())?></span> &deg;F at <span data-bind="fadeInText: new Date(Date.parse(WindChillStats.MonthlyValues.MinDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $windChillStats->getMonthlyValues()->getMinDate())?></span></td>
</tr>

<tr class="column-dark">
<td >Year</td>
<td ><span data-bind="fadeInText: formatNumberDP(HeatIndexStats.AnnualValues.Max())"><?php print formatNumberDP($heatIndexStats->getAnnualValues()->getMax())?></span> &deg;F at <span data-bind="fadeInText: new Date(Date.parse(HeatIndexStats.AnnualValues.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $heatIndexStats->getAnnualValues()->getMaxDate())?></span></td>
<td >Year</td>
<td ><span data-bind="fadeInText: formatNumberDP(WindChillStats.AnnualValues.Min())"><?php print formatNumberDP($windChillStats->getAnnualValues()->getMin())?></span> &deg;F at <span data-bind="fadeInText: new Date(Date.parse(WindChillStats.AnnualValues.MinDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $windChillStats->getAnnualValues()->getMinDate())?></span></td>
</tr>

</table>
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">Pressure and Humidity Statistics</h2>
								<p class="meta">
								</p>
								<div class="entry">
<table class="fullTable">
<colgroup>
	<col id="stats2col1" />
	<col id="stats2col2" />
	<col id="stats2col3" />
	<col id="stats2col4" />
</colgroup>

<thead>
<tr class="table-top">
<td colspan="2" >BAROMETER HIGHS</td>
<td colspan="2" >BAROMETER LOWS</td>
</tr>
</thead>

<tr class="column-light">
<td>Today</td>
<td><span data-bind="fadeInText: formatNumberDP(PressureStats.DailyValues.Max())"><?php print formatNumberDP($pressureStats->getDailyValues()->getMax())?></span> in. at <span data-bind="fadeInText: new Date(Date.parse(PressureStats.DailyValues.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $pressureStats->getDailyValues()->getMaxDate())?></span></td>
<td>Today</td>
<td><span data-bind="fadeInText: formatNumberDP(PressureStats.DailyValues.Min())"><?php print formatNumberDP($pressureStats->getDailyValues()->getMin())?></span> in. at <span data-bind="fadeInText: new Date(Date.parse(PressureStats.DailyValues.MinDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $pressureStats->getDailyValues()->getMinDate())?></span></td>
</tr>

<tr class="column-dark">
<td >Yest.</td>
<td><span data-bind="fadeInText: formatNumberDP(PressureStats.YesterdayValues.Max())"><?php print formatNumberDP($pressureStats->getYesterdayValues()->getMax())?></span> in. at <span data-bind="fadeInText: new Date(Date.parse(PressureStats.YesterdayValues.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $pressureStats->getYesterdayValues()->getMaxDate())?></span></td>
<td >Yest.</td>
<td><span data-bind="fadeInText: formatNumberDP(PressureStats.YesterdayValues.Min())"><?php print formatNumberDP($pressureStats->getYesterdayValues()->getMin())?></span> in. at <span data-bind="fadeInText: new Date(Date.parse(PressureStats.YesterdayValues.MinDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $pressureStats->getYesterdayValues()->getMinDate())?></span></td>
</tr>

<tr class="column-light">
<td >Month</td>
<td><span data-bind="fadeInText: formatNumberDP(PressureStats.MonthlyValues.Max())"><?php print formatNumberDP($pressureStats->getMonthlyValues()->getMax())?></span> in. at <span data-bind="fadeInText: new Date(Date.parse(PressureStats.MonthlyValues.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $pressureStats->getMonthlyValues()->getMaxDate())?></span></td>
<td >Month</td>
<td><span data-bind="fadeInText: formatNumberDP(PressureStats.MonthlyValues.Min())"><?php print formatNumberDP($pressureStats->getMonthlyValues()->getMin())?></span> in. at <span data-bind="fadeInText: new Date(Date.parse(PressureStats.MonthlyValues.MinDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $pressureStats->getMonthlyValues()->getMinDate())?></span></td>
</tr>

<tr class="column-dark">
<td >Year</td>
<td><span data-bind="fadeInText: formatNumberDP(PressureStats.AnnualValues.Max())"><?php print formatNumberDP($pressureStats->getAnnualValues()->getMax())?></span> in. at <span data-bind="fadeInText: new Date(Date.parse(PressureStats.AnnualValues.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $pressureStats->getAnnualValues()->getMaxDate())?></span></td>
<td >Year</td>
<td><span data-bind="fadeInText: formatNumberDP(PressureStats.AnnualValues.Min())"><?php print formatNumberDP($pressureStats->getAnnualValues()->getMin())?></span> in. at <span data-bind="fadeInText: new Date(Date.parse(PressureStats.AnnualValues.MinDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $pressureStats->getAnnualValues()->getMinDate())?></span></td>
</tr>

<tr class="table-top">
<td colspan="2" >HUMIDITY HIGHS</td>
<td colspan="2" >HUMIDITY LOWS</td>
</tr>

<tr class="column-light">
<td>Today</td>
<td><span data-bind="fadeInText: formatNumberDP(OutdoorHumidityStats.DailyValues.Max())"><?php print formatNumberDP($humidityStats->getDailyValues()->getMax())?></span> in. at <span data-bind="fadeInText: new Date(Date.parse(OutdoorHumidityStats.DailyValues.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $humidityStats->getDailyValues()->getMaxDate())?></span></td>
<td>Today</td>
<td><span data-bind="fadeInText: formatNumberDP(OutdoorHumidityStats.DailyValues.Min())"><?php print formatNumberDP($humidityStats->getDailyValues()->getMin())?></span> in. at <span data-bind="fadeInText: new Date(Date.parse(OutdoorHumidityStats.DailyValues.MinDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $humidityStats->getDailyValues()->getMinDate())?></span></td>
</tr>

<tr class="column-dark">
<td >Yest.</td>
<td><span data-bind="fadeInText: formatNumberDP(OutdoorHumidityStats.YesterdayValues.Max())"><?php print formatNumberDP($humidityStats->getYesterdayValues()->getMax())?></span> in. at <span data-bind="fadeInText: new Date(Date.parse(OutdoorHumidityStats.YesterdayValues.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $humidityStats->getYesterdayValues()->getMaxDate())?></span></td>
<td >Yest.</td>
<td><span data-bind="fadeInText: formatNumberDP(OutdoorHumidityStats.YesterdayValues.Min())"><?php print formatNumberDP($humidityStats->getYesterdayValues()->getMin())?></span> in. at <span data-bind="fadeInText: new Date(Date.parse(OutdoorHumidityStats.YesterdayValues.MinDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $humidityStats->getYesterdayValues()->getMinDate())?></span></td>
</tr>

<tr class="column-light">
<td >Month</td>
<td><span data-bind="fadeInText: formatNumberDP(OutdoorHumidityStats.MonthlyValues.Max())"><?php print formatNumberDP($humidityStats->getMonthlyValues()->getMax())?></span> in. at <span data-bind="fadeInText: new Date(Date.parse(OutdoorHumidityStats.MonthlyValues.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $humidityStats->getMonthlyValues()->getMaxDate())?></span></td>
<td >Month</td>
<td><span data-bind="fadeInText: formatNumberDP(OutdoorHumidityStats.MonthlyValues.Min())"><?php print formatNumberDP($humidityStats->getMonthlyValues()->getMin())?></span> in. at <span data-bind="fadeInText: new Date(Date.parse(OutdoorHumidityStats.MonthlyValues.MinDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $humidityStats->getMonthlyValues()->getMinDate())?></span></td>
</tr>

<tr class="column-dark">
<td >Year</td>
<td><span data-bind="fadeInText: formatNumberDP(OutdoorHumidityStats.AnnualValues.Max())"><?php print formatNumberDP($humidityStats->getAnnualValues()->getMax())?></span> in. at <span data-bind="fadeInText: new Date(Date.parse(OutdoorHumidityStats.AnnualValues.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $humidityStats->getAnnualValues()->getMaxDate())?></span></td>
<td >Year</td>
<td><span data-bind="fadeInText: formatNumberDP(OutdoorHumidityStats.AnnualValues.Min())"><?php print formatNumberDP($humidityStats->getAnnualValues()->getMin())?></span> in. at <span data-bind="fadeInText: new Date(Date.parse(OutdoorHumidityStats.AnnualValues.MinDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $humidityStats->getAnnualValues()->getMinDate())?></span></td>
</tr>

</table>
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">Wind and Rain Statistics</h2>
								<p class="meta">
								</p>
								<div class="entry">
<table class="fullTable">

<colgroup>
	<col id="stats3col1" />
	<col id="stats3col2" />
	<col id="stats3col3" />
	<col id="stats3col4" />
</colgroup>

<thead>
<tr class="table-top">
<td colspan="2" >WIND DETAILS</td>
<td colspan="2" >WIND RUN</td>
</tr>
</thead>

<tr class="column-light">
<td>Now</td>
<td><span data-bind="fadeInText: formatNumberDP(AvgWindSpeed())"><?php print formatNumberDP($data->getAvgWindSpeed())?></span> mph <span data-bind="fadeInText: getWindDirStr(AvgWindDir())"><?php print getWindDirStr($data->getAvgWindDir())?></span></td>
<td>Today</td>
<td><span data-bind="fadeInText: formatNumberDP(WindRunCounts.DayCount())"><?php print formatNumberDP($stats->getWindRunCounts()->getDayCount())?></span> miles</td>
</tr>

<tr class="column-dark">
<td >Gust</td>
<td ><span data-bind="fadeInText: formatNumberDP(WindSpeed())"><?php print formatNumberDP($data->getWindSpeed())?></span> mph <span data-bind="fadeInText: getWindDirStr(WindDir())"><?php print getWindDirStr($data->getWindDir())?></span></td>
<td >Month</td>
<td ><span data-bind="fadeInText: formatNumberDP(WindRunCounts.MonthCount())"><?php print formatNumberDP($stats->getWindRunCounts()->getMonthCount())?></span> miles</td>
</tr>

<tr class="column-light">
<td >Gust/hr</td>
<td ><span data-bind="fadeInText: formatNumberDP(WindGustStats.HourlyValues.Max())"><?php print formatNumberDP($stats->getWindGustStats()->getHourlyValues()->getMax())?></span> mph</td>
<td >Year</td>
<td ><span data-bind="fadeInText: formatNumberDP(WindRunCounts.YearCount())"><?php print formatNumberDP($stats->getWindRunCounts()->getYearCount())?></span> miles</td>
</tr>


<tr class="table-top">
<td colspan="2" >WIND GUST HIGHS</td>
<td colspan="2" >WIND AVERAGE HIGHS</td>
</tr>

<tr class="column-light">
<td >Today</td>
<td ><span data-bind="fadeInText: formatNumberDP(WindGustStats.DailyValues.Max())"><?php print formatNumberDP($stats->getWindGustStats()->getDailyValues()->getMax())?></span> mph <span data-bind="fadeInText: getWindDirStr(MaxGustWindDirection.DayValue())"><?php print getWindDirStr($stats->getMaxGustWindDirection()->getDayValue())?></span> at <span data-bind="fadeInText: new Date(Date.parse(WindGustStats.DailyValues.MaxDate())).format(veryShortDateTimeFormat)"><?php print date($cfg->getVeryShortDateTimeFormat(), $stats->getWindGustStats()->getDailyValues()->getMaxDate())?></span></td>
<td >Today</td>
<td ><span data-bind="fadeInText: formatNumberDP(WindSustainedStats.DailyValues.Max())"><?php print formatNumberDP($stats->getWindSustainedStats()->getDailyValues()->getMax())?></span> mph <span data-bind="fadeInText: getWindDirStr(MaxAverageWindDirection.DayValue())"><?php print getWindDirStr($stats->getMaxAverageWindDirection()->getDayValue())?></span> at <span data-bind="fadeInText: new Date(Date.parse(WindSustainedStats.DailyValues.MaxDate())).format(veryShortDateTimeFormat)"><?php print date($cfg->getVeryShortDateTimeFormat(), $stats->getWindSustainedStats()->getDailyValues()->getMaxDate())?></span></td>
</tr>

<tr class="column-dark">
<td >Yest.</td>
<td ><span data-bind="fadeInText: formatNumberDP(WindGustStats.YesterdayValues.Max())"><?php print formatNumberDP($stats->getWindGustStats()->getYesterdayValues()->getMax())?></span> mph <span data-bind="fadeInText: getWindDirStr(MaxGustWindDirection.DayValue())"><?php print getWindDirStr($stats->getMaxGustWindDirection()->getDayValue())?></span> at <span data-bind="fadeInText: new Date(Date.parse(WindGustStats.YesterdayValues.MaxDate())).format(veryShortDateTimeFormat)"><?php print date($cfg->getVeryShortDateTimeFormat(), $stats->getWindGustStats()->getYesterdayValues()->getMaxDate())?></span></td>
<td >Yest.</td>
<td ><span data-bind="fadeInText: formatNumberDP(WindSustainedStats.YesterdayValues.Max())"><?php print formatNumberDP($stats->getWindSustainedStats()->getYesterdayValues()->getMax())?></span> mph <span data-bind="fadeInText: getWindDirStr(MaxAverageWindDirection.DayValue())"><?php print getWindDirStr($stats->getMaxAverageWindDirection()->getDayValue())?></span> at <span data-bind="fadeInText: new Date(Date.parse(WindSustainedStats.YesterdayValues.MaxDate())).format(veryShortDateTimeFormat)"><?php print date($cfg->getVeryShortDateTimeFormat(), $stats->getWindSustainedStats()->getYesterdayValues()->getMaxDate())?></span></td>
</tr>

<tr class="column-light">
<td >Month</td>
<td ><span data-bind="fadeInText: formatNumberDP(WindGustStats.MonthlyValues.Max())"><?php print formatNumberDP($stats->getWindGustStats()->getMonthlyValues()->getMax())?></span> mph <span data-bind="fadeInText: getWindDirStr(MaxGustWindDirection.MonthValue())"><?php print getWindDirStr($stats->getMaxGustWindDirection()->getMonthValue())?></span> on <span data-bind="fadeInText: new Date(Date.parse(WindGustStats.MonthlyValues.MaxDate())).format(dateOnlyFormat)"><?php print date($cfg->getDateFormat(), $stats->getWindGustStats()->getMonthlyValues()->getMaxDate())?></span></td>
<td >Month</td>
<td ><span data-bind="fadeInText: formatNumberDP(WindSustainedStats.MonthlyValues.Max())"><?php print formatNumberDP($stats->getWindSustainedStats()->getMonthlyValues()->getMax())?></span> mph <span data-bind="fadeInText: getWindDirStr(MaxAverageWindDirection.MonthValue())"><?php print getWindDirStr($stats->getMaxAverageWindDirection()->getMonthValue())?></span> on <span data-bind="fadeInText: new Date(Date.parse(WindSustainedStats.MonthlyValues.MaxDate())).format(dateOnlyFormat)"><?php print date($cfg->getDateFormat(), $stats->getWindSustainedStats()->getMonthlyValues()->getMaxDate())?></span></td>
</tr>

<tr class="column-dark">
<td >Year</td>
<td ><span data-bind="fadeInText: formatNumberDP(WindGustStats.AnnualValues.Max())"><?php print formatNumberDP($stats->getWindGustStats()->getAnnualValues()->getMax())?></span> mph <span data-bind="fadeInText: getWindDirStr(MaxGustWindDirection.YearValue())"><?php print getWindDirStr($stats->getMaxGustWindDirection()->getYearValue())?></span> on <span data-bind="fadeInText: new Date(Date.parse(WindGustStats.AnnualValues.MaxDate())).format(dateOnlyFormat)"><?php print date($cfg->getDateFormat(), $stats->getWindGustStats()->getAnnualValues()->getMaxDate())?></span></td>
<td >Year</td>
<td ><span data-bind="fadeInText: formatNumberDP(WindSustainedStats.AnnualValues.Max())"><?php print formatNumberDP($stats->getWindSustainedStats()->getAnnualValues()->getMax())?></span> mph <span data-bind="fadeInText: getWindDirStr(MaxAverageWindDirection.YearValue())"><?php print getWindDirStr($stats->getMaxAverageWindDirection()->getYearValue())?></span> on <span data-bind="fadeInText: new Date(Date.parse(WindSustainedStats.AnnualValues.MaxDate())).format(dateOnlyFormat)"><?php print date($cfg->getDateFormat(), $stats->getWindSustainedStats()->getAnnualValues()->getMaxDate())?></span></td>
</tr>


<tr class="table-top">
<td colspan="2" >RAIN DETAILS</td>
<td colspan="2" >RAIN MEASURED</td>
</tr>

<tr class="column-light">
<td >Rain Today</td>
<td ><span data-bind="fadeInText: formatNumberDP(RainCounts.DayCount())"><?php print formatNumberDP($rainCounts->getDayCount())?></span> in.</td>
<td >Rain Last 1/Hr</td>
<td ><span data-bind="fadeInText: formatNumberDP(getHourCount(1, RainCounts.HourStartCounts(), RainCounts.LastSampleDate(), RainCounts.LastSampleValue()))"><?php print formatNumberDP($rainCounts->getHourCount(1))?></span> in.</td>
</tr>

<tr class="column-dark">
<td >Rain Yest.</td>
<td ><span data-bind="fadeInText: formatNumberDP(RainCounts.YesterdayCount())"><?php print formatNumberDP($rainCounts->getYesterdayCount())?></span> in.</td>
<td >Rain Last 3/Hrs</td>
<td ><span data-bind="fadeInText: formatNumberDP(getHourCount(3, RainCounts.HourStartCounts(), RainCounts.LastSampleDate(), RainCounts.LastSampleValue()))"><?php print formatNumberDP($rainCounts->getHourCount(3))?></span> in.</td>
</tr>

<tr class="column-light">
<td >Rain Month</td>
<td ><span data-bind="fadeInText: formatNumberDP(RainCounts.MonthCount())"><?php print formatNumberDP($rainCounts->getMonthCount())?></span> in.</td>
<td >Rain Last 6/Hrs</td>
<td ><span data-bind="fadeInText: formatNumberDP(getHourCount(6, RainCounts.HourStartCounts(), RainCounts.LastSampleDate(), RainCounts.LastSampleValue()))"><?php print formatNumberDP($rainCounts->getHourCount(6))?></span> in.</td>
</tr>

<tr class="column-dark">
<td >Rain Year</td>
<td ><span data-bind="fadeInText: formatNumberDP(RainCounts.YearCount())"><?php print formatNumberDP($rainCounts->getYearCount())?></span> in.</td>
<td >Rain Last 24/Hrs</td>
<td ><span data-bind="fadeInText: formatNumberDP(getHourCount(24, RainCounts.HourStartCounts(), RainCounts.LastSampleDate(), RainCounts.LastSampleValue()))"><?php print formatNumberDP($rainCounts->getHourCount(24))?></span> in.</td>
</tr>

<tr class="table-top">
<td colspan="2" >RAIN RATES</td>
<td colspan="2" >RAIN DAYS</td>
</tr>

<tr class="column-light">
<td >Current Rain Rate</td>
<td ><span data-bind="fadeInText: formatNumberDP(RainCounts.CurrentRatePerMinute())"><?php print formatNumberDP($rainCounts->getCurrentRatePerMinute())?></span> in./min</td>
<td >Days No Rain</td>
<td ><span data-bind="fadeInText: RainCounts.daysWithNoChange()"><?php print $rainCounts->daysWithNoChange()?></span> days</td>
</tr>

<tr class="column-dark">
<td >Max Rate Last 1/Hr</td>
<td ><span data-bind="fadeInText: formatNumberDP(getMaxRateHour(1, RainCounts.MaxRateHours(), RainCounts.LastSampleDate()))"><?php print formatNumberDP($rainCounts->getMaxRateHour(1)->getMax())?></span> in./min</td>
<td >Days Rain /Week</td>
<td ><span data-bind="fadeInText: RainCounts.CountChangeDaysThisWeek()"><?php print $rainCounts->getCountChangeDaysThisWeek()?></span> days</td>
</tr>

<tr class="column-light">
<td >Max Rate Last 6/Hrs</td>
<td ><span data-bind="fadeInText: formatNumberDP(getMaxRateHour(6, RainCounts.MaxRateHours(), RainCounts.LastSampleDate()))"><?php print formatNumberDP($rainCounts->getMaxRateHour(6)->getMax())?></span> in./min</td>
<td >Days Rain /Month</td>
<td ><span data-bind="fadeInText: RainCounts.CountChangeDaysThisMonth()"><?php print $rainCounts->getCountChangeDaysThisMonth()?></span> days</td>
</tr>

<tr class="column-dark">
<td >Max Rate Last 24/Hrs</td>
<td ><span data-bind="fadeInText: formatNumberDP(getMaxRateHour(24, RainCounts.MaxRateHours(), RainCounts.LastSampleDate()))"><?php print formatNumberDP($rainCounts->getMaxRateHour(24)->getMax())?></span> in./min</td>
<td >Days Rain /Year</td>
<td ><span data-bind="fadeInText: RainCounts.CountChangeDaysThisYear()"><?php print $rainCounts->getCountChangeDaysThisYear()?></span> days</td>
</tr>

<tr class="table-top">
<td colspan="2" >RAIN RECORDS</td>
<td colspan="2" >RAIN RATE RECORDS</td>
</tr>

<tr class="column-light">
<td >Week Record</td>
<td ><span data-bind="fadeInText: formatNumberDP(RainCounts.HighestDailyValueThisWeek.Max())"><?php print formatNumberDP($rainCounts->getHighestDailyValueThisWeek()->getMax())?></span> in. on <span data-bind="fadeInText: new Date(Date.parse(RainCounts.HighestDailyValueThisWeek.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $rainCounts->getHighestDailyValueThisWeek()->getMaxDate())?></span></td>
<td >Week Record</td>
<td ><span data-bind="fadeInText: formatNumberDP(RainCounts.MaxRateThisWeek.Max())"><?php print formatNumberDP($rainCounts->getMaxRateThisWeek()->getMax())?></span> in./min on <span data-bind="fadeInText: new Date(Date.parse(RainCounts.MaxRateThisWeek.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $rainCounts->getMaxRateThisWeek()->getMaxDate())?></span></td>
</tr>

<tr class="column-dark">
<td >Month Record</td>
<td ><span data-bind="fadeInText: formatNumberDP(RainCounts.HighestDailyValueThisMonth.Max())"><?php print formatNumberDP($rainCounts->getHighestDailyValueThisMonth()->getMax())?></span> in. on <span data-bind="fadeInText: new Date(Date.parse(RainCounts.HighestDailyValueThisMonth.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $rainCounts->getHighestDailyValueThisMonth()->getMaxDate())?></span></td>
<td >Month Record</td>
<td ><span data-bind="fadeInText: formatNumberDP(RainCounts.MaxRateThisMonth.Max())"><?php print formatNumberDP($rainCounts->getMaxRateThisMonth()->getMax())?></span> in./min on <span data-bind="fadeInText: new Date(Date.parse(RainCounts.MaxRateThisMonth.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $rainCounts->getMaxRateThisMonth()->getMaxDate())?></span></td>
</tr>

<tr class="column-light">
<td >Year Record</td>
<td ><span data-bind="fadeInText: formatNumberDP(RainCounts.HighestDailyValueThisYear.Max())"><?php print formatNumberDP($rainCounts->getHighestDailyValueThisYear()->getMax())?></span> in. on <span data-bind="fadeInText: new Date(Date.parse(RainCounts.HighestDailyValueThisYear.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $rainCounts->getHighestDailyValueThisYear()->getMaxDate())?></span></td>
<td >Year Record</td>
<td ><span data-bind="fadeInText: formatNumberDP(RainCounts.MaxRateThisYear.Max())"><?php print formatNumberDP($rainCounts->getMaxRateThisYear()->getMax())?></span> in./min on <span data-bind="fadeInText: new Date(Date.parse(RainCounts.MaxRateThisYear.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $rainCounts->getMaxRateThisYear()->getMaxDate())?></span></td>
</tr>

<tr class="column-dark">
<td >All-time Record</td>
<td ><span data-bind="fadeInText: formatNumberDP(RainCounts.HighestDailyValueCumulative.Max())"><?php print formatNumberDP($rainCounts->getHighestDailyValueCumulative()->getMax())?></span> in. on <span data-bind="fadeInText: new Date(Date.parse(RainCounts.HighestDailyValueCumulative.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $rainCounts->getHighestDailyValueCumulative()->getMaxDate())?></span></td>
<td >All-time Record</td>
<td ><span data-bind="fadeInText: formatNumberDP(RainCounts.MaxRateCumulative.Max())"><?php print formatNumberDP($rainCounts->getMaxRateCumulative()->getMax())?></span> in./min on <span data-bind="fadeInText: new Date(Date.parse(RainCounts.MaxRateCumulative.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $rainCounts->getMaxRateCumulative()->getMaxDate())?></span></td>
</tr>

</table>
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">Solar and Lightning Statistics</h2>
								<p class="meta">
								</p>
								<div class="entry">
<table class="fullTable">

<colgroup>
	<col id="stats4col1" />
	<col id="stats4col2" />
	<col id="stats4col3" />
	<col id="stats4col4" />
</colgroup>

<thead>
<tr class="table-top">
<td colspan="2" >SOLAR HIGHS</td>
<td colspan="2" >&nbsp;</td>
</tr>
</thead>

<tr class="column-light">
<td>Today</td>
<td><span data-bind="fadeInText: formatNumberDP(SolarStats.DailyValues.Max())"><?php print formatNumberDP($stats->getSolarStats()->getDailyValues()->getMax())?></span> at <span data-bind="fadeInText: new Date(Date.parse(SolarStats.DailyValues.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $stats->getSolarStats()->getDailyValues()->getMaxDate())?></span></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class="column-dark">
<td >Yest.</td>
<td><span data-bind="fadeInText: formatNumberDP(SolarStats.YesterdayValues.Max())"><?php print formatNumberDP($stats->getSolarStats()->getYesterdayValues()->getMax())?></span> at <span data-bind="fadeInText: new Date(Date.parse(SolarStats.YesterdayValues.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $stats->getSolarStats()->getYesterdayValues()->getMaxDate())?></span></td>
<td >&nbsp;</td>
<td >&nbsp;</td>
</tr>

<tr class="column-light">
<td >Month</td>
<td><span data-bind="fadeInText: formatNumberDP(SolarStats.MonthlyValues.Max())"><?php print formatNumberDP($stats->getSolarStats()->getMonthlyValues()->getMax())?></span> at <span data-bind="fadeInText: new Date(Date.parse(SolarStats.MonthlyValues.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $stats->getSolarStats()->getMonthlyValues()->getMaxDate())?></span></td>
<td >&nbsp;</td>
<td >&nbsp;</td>
</tr>

<tr class="column-dark">
<td >Year</td>
<td><span data-bind="fadeInText: formatNumberDP(SolarStats.AnnualValues.Max())"><?php print formatNumberDP($stats->getSolarStats()->getAnnualValues()->getMax())?></span> at <span data-bind="fadeInText: new Date(Date.parse(SolarStats.AnnualValues.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $stats->getSolarStats()->getAnnualValues()->getMaxDate())?></span></td>
<td >&nbsp;</td>
<td >&nbsp;</td>
</tr>


<tr class="table-top">
<td colspan="2" >LIGHTNING COUNTS</td>
<td colspan="2" >LIGHTNING RATES</td>
</tr>

<tr class="column-light">
<td >Today</td>
<td ><span data-bind="fadeInText: formatNumberDP(LightningCounts.DayCount())"><?php print formatNumberDP($lightningCounts->getDayCount())?></span></td>
<td >Current</td>
<td ><span data-bind="fadeInText: formatNumberDP(LightningCounts.CurrentRatePerMinute())"><?php print formatNumberdP($lightningCounts->getCurrentRatePerMinute())?></span>/min.</td>
</tr>

<tr class="column-dark">
<td >Yest.</td>
<td ><span data-bind="fadeInText: formatNumberDP(LightningCounts.YesterdayCount())"><?php print formatNumberDP($lightningCounts->getYesterdayCount())?></span></td>
<td >Max Rate Last 1/Hr</td>
<td ><span data-bind="fadeInText: formatNumberDP(getMaxRateHour(1, LightningCounts.MaxRateHours(), LightningCounts.LastSampleDate()))"><?php print formatNumberDP($lightningCounts->getMaxRateHour(1)->getMax())?></span>/min.</td>
</tr>

<tr class="column-light">
<td >Month</td>
<td ><span data-bind="fadeInText: formatNumberDP(LightningCounts.MonthCount())"><?php print formatNumberDP($lightningCounts->getMonthCount())?></span></td>
<td >Max Rate Last 6/Hrs</td>
<td ><span data-bind="fadeInText: formatNumberDP(getMaxRateHour(6, LightningCounts.MaxRateHours(), LightningCounts.LastSampleDate()))"><?php print formatNumberDP($lightningCounts->getMaxRateHour(6)->getMax())?></span>/min.</td>
</tr>

<tr class="column-dark">
<td >Year</td>
<td ><span data-bind="fadeInText: formatNumberDP(LightningCounts.YearCount())"><?php print formatNumberDP($lightningCounts->getYearCount())?></span></td>
<td >Max Rate Last 24/Hrs</td>
<td ><span data-bind="fadeInText: formatNumberDP(getMaxRateHour(24, LightningCounts.MaxRateHours(), LightningCounts.LastSampleDate()))"><?php print formatNumberDP($lightningCounts->getMaxRateHour(24)->getMax())?></span>/min.</td>
</tr>


<tr class="table-top">
<td colspan="2" >LIGHTNING RECORDS</td>
<td colspan="2" >LIGHTNING RATE RECORDS</td>
</tr>

<tr class="column-light">
<td >Week Record</td>
<td ><span data-bind="fadeInText: formatNumberDP(LightningCounts.HighestDailyValueThisWeek.Max())"><?php print formatNumberDP($lightningCounts->getHighestDailyValueThisWeek()->getMax())?></span> on <span data-bind="fadeInText: new Date(Date.parse(LightningCounts.HighestDailyValueThisWeek.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $lightningCounts->getHighestDailyValueThisWeek()->getMaxDate())?></span></td>
<td >Week Record</td>
<td ><span data-bind="fadeInText: formatNumberDP(LightningCounts.MaxRateThisWeek.Max())"><?php print formatNumberDP($lightningCounts->getMaxRateThisWeek()->getMax())?></span>/min. on <span data-bind="fadeInText: new Date(Date.parse(LightningCounts.MaxRateThisWeek.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $lightningCounts->getMaxRateThisWeek()->getMaxDate())?></span></td>
</tr>

<tr class="column-dark">
<td >Month Record</td>
<td ><span data-bind="fadeInText: formatNumberDP(LightningCounts.HighestDailyValueThisMonth.Max())"><?php print formatNumberDP($lightningCounts->getHighestDailyValueThisMonth()->getMax())?></span> on <span data-bind="fadeInText: new Date(Date.parse(LightningCounts.HighestDailyValueThisMonth.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $lightningCounts->getHighestDailyValueThisMonth()->getMaxDate())?></span></td>
<td >Month Record</td>
<td ><span data-bind="fadeInText: formatNumberDP(LightningCounts.MaxRateThisMonth.Max())"><?php print formatNumberDP($lightningCounts->getMaxRateThisMonth()->getMax())?></span>/min. on <span data-bind="fadeInText: new Date(Date.parse(LightningCounts.MaxRateThisMonth.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $lightningCounts->getMaxRateThisMonth()->getMaxDate())?></span></td>
</tr>

<tr class="column-light">
<td >Year Record</td>
<td ><span data-bind="fadeInText: formatNumberDP(LightningCounts.HighestDailyValueThisYear.Max())"><?php print formatNumberDP($lightningCounts->getHighestDailyValueThisYear()->getMax())?></span> on <span data-bind="fadeInText: new Date(Date.parse(LightningCounts.HighestDailyValueThisYear.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $lightningCounts->getHighestDailyValueThisYear()->getMaxDate())?></span></td>
<td >Year Record</td>
<td ><span data-bind="fadeInText: formatNumberDP(LightningCounts.MaxRateThisYear.Max())"><?php print formatNumberDP($lightningCounts->getMaxRateThisYear()->getMax())?></span>/min. on <span data-bind="fadeInText: new Date(Date.parse(LightningCounts.MaxRateThisYear.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $lightningCounts->getMaxRateThisYear()->getMaxDate())?></span></td>
</tr>

<tr class="column-dark">
<td >All-time Record</td>
<td ><span data-bind="fadeInText: formatNumberDP(LightningCounts.HighestDailyValueCumulative.Max())"><?php print formatNumberDP($lightningCounts->getHighestDailyValueCumulative()->getMax())?></span> on <span data-bind="fadeInText: new Date(Date.parse(LightningCounts.HighestDailyValueCumulative.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $lightningCounts->getHighestDailyValueCumulative()->getMaxDate())?></span></td>
<td >All-time Record</td>
<td ><span data-bind="fadeInText: formatNumberDP(LightningCounts.MaxRateCumulative.Max())"><?php print formatNumberDP($lightningCounts->getMaxRateCumulative()->getMax())?></span>/min. on <span data-bind="fadeInText: new Date(Date.parse(LightningCounts.MaxRateCumulative.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $lightningCounts->getMaxRateCumulative()->getMaxDate())?></span></td>
</tr>


</table>
								</div>
							</div>
						</div>
					</div>
					<div style="clear: both;">&nbsp;</div>
				</div>
				<!-- end #content -->
				
<?php
include_once "pagesidebar.php";
?>
					<div style="clear: both;">&nbsp;</div>
 				</div>
			<!-- 			</div>  -->
		</div>
		<!-- end #page -->
		
<?php
include_once "pagefooter.php";
?>	
</div>
</body>
</html>

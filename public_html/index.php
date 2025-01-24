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
require_once "classes/weatherConversions.php";

$cfg = config::getInstance();
date_default_timezone_set($cfg->getTimeZone());

require_once "classes/weatherData.php";
require_once "classes/weatherStatistics.php";
require_once "classes/nwsforecast.php";

$data = new weatherData();
$stats = new weatherStatistics();
$forecast = new NWSForecast();

$ForecastEntriesToDisplay = 9;
$entries = $forecast->getForecastEntries();

?>

<head>
<meta name="keywords" content="" />
<meta name="keywords" content="germantown,Clopper&#39;s,Mill,Clopper&#39;s Mill,weather" />
<meta name="description" content="Personal weather station located in Germantown, MD 20874." />
<meta name="google-site-verification" content="6bcuibzjXpslBKSBcx9n3l2T-mJgjoQNUEK4ObDLb6w" />

<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<script src="config/config.js.php"></script>
<script src="js/jQuery.js"></script>
<script src="js/jQueryTimer.js"></script>
<script src="js/weatherconversions.js"></script>
<script src='js/date.js'></script>
<script src='js/knockout.js'></script>
<script src='js/knockoutmapping.js'></script>
<script src='js/weathermodel.js'></script>

<title>Clopper&#39;s Mill East Weather</title>

<link href="css/style.css" rel="stylesheet" type="text/css" media="screen" />
<link id="favicon" rel="shortcut icon" href="favicon.ico">

</head>

<body>

<script>

$(document).ready(function(){

	initWeatherDataModel();

	// get WX data every 15 secs
	$(document).everyTime(dataRefreshRate, retrieveWeatherData);

	//get WX stats every minute
	$(document).everyTime(statsRefreshRate, retrieveWeatherStats);

	//get update web cam image every 30secs
	$("#webcamThumbnail").everyTime(webcamRefreshRate, updateWebcam);

	// get weather forecast every 10 minutes, automatically updates the favicon
	$(document).everyTime(forecastRefreshRate, retrieveNWSForecast);

	// update favicon every 10 minutes based on weather forecast.  See above
	// $(document).everyTime(600000, updateFavicon);

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
					<div class="post" id="forecast">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">
								<a data-bind="attr: { href: ForecastCredit() }" href="<?php print $forecast->getCredit()?>">NWS Five Day Forecast</a>
								</h2>
								<p class="meta">
									Last Updated:
									<span data-bind="text: new Date(Date.parse(ForecastCreationDate())).format(longDateTimeFormat)"><?php print date($cfg->getDateTimeFormat(), $forecast->getCreationDate())?></span>
								</p>
								<div class="entry">
									<table class="fullTable">
										<tr class="center" style="vertical-align: top;">
										<?php for ($i=0; $i < $ForecastEntriesToDisplay; $i++) { ?>
										  <td style="width: 11%;"><span style="font-size: 9pt;"><b><span <?php print "data-bind=\"html: HTMLSplitWords(ForecastEntries()[". $i. "].timePeriodName())\""?>>
											<?php print HTMLSplitWords($entries[$i]->getTimePeriodName())?></span></b></span></td>												
										<?php } ?>
										</tr>

										<tr class="center">
										<?php for ($i=0; $i < $ForecastEntriesToDisplay; $i++) { ?>
											<td style="width: 11%;"><span style="font-size: 8pt;"><img <?php print "data-bind=\"attr: { src: ForecastEntries()[" . $i . "].iconURL, alt: ForecastEntries()[" . $i . "].wordedForecast, title: ForecastEntries()[" . $i . "].wordedForecast }\""?>  
													src="<?php print $entries[$i]->getIconURL()?>" width="70" height="70"
													alt="<?php print $entries[$i]->getWordedForecast()?>"
													title="<?php print $entries[$i]->getWordedForecast()?>" />
											</span></td>
										<?php } ?>
										</tr>

										<tr class="center" style="vertical-align: top;">
										<?php for ($i=0; $i < $ForecastEntriesToDisplay; $i++) { ?>
											<td style="width: 11%;"><span style="font-size: 8pt;"><span <?php print "data-bind=\"html: HTMLSplitWords(ForecastEntries()[". $i. "].weatherType())\""?>>
											<?php print HTMLSplitWords($entries[$i]->getWeatherType())?></span></span></td>
										<?php } ?>
										</tr>

										<tr class="center">
										<?php for ($i=0; $i < $ForecastEntriesToDisplay; $i++) { ?>
											<td style="width: 11%;"><span <?php print "data-bind=\"text: ForecastEntries()[". $i. "].minMaxType()\""?>><?php print $entries[$i]->getMinMaxType()?></span>
												<span <?php print "data-bind=\"style: { color: getHiLoColor(ForecastEntries()[" . $i . "].minMaxType()) }\""?> style="color: <?php if ($entries[$i]->getMinMaxType() == "Hi") print $cfg->getRed(); else print $cfg->getBlue();?>;">
													<span <?php print "data-bind=\"text: ForecastEntries()[" . $i . "].minMax\""?>><?php print $entries[$i]->getMinMax()?></span>&deg;F</span>
												
										<?php } ?>
										</tr>

										<tr class="center">
										<?php for ($i=0; $i < $ForecastEntriesToDisplay; $i++) { ?>
											<td style="width: 11%;">PoP <span <?php print "data-bind=\"text: ForecastEntries()[" .  $i . "].pop\""?>><?php print $entries[$i]->getPop()?></span>%</td>
										<?php } ?>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">Local Weather Details</h2>
								<p class="meta">
									Last Updated: <span data-bind="fadeInText: new Date(Date.parse(DateTime())).format(longDateTimeFormat)"><?php print date($cfg->getDateTimeFormat(), $data->getDateTime())?></span>
								</p>
								<div class="entry">
									<table class="fullTable">
										<colgroup>
											<col id="measure" />
											<col id="current" />
											<col id="minmax" />
											<col id="rate" />
										</colgroup>
										<thead>
											<tr class="table-top">
												<td><b>MEASURE</b></td>
												<td><b>CURRENT</b></td>
												<td><b>MAX/MIN</b></td>
												<td><b>RATE</b></td>
											</tr>
										</thead>

										<tr class="column-light">
											<td><b>Temperature</b></td>
											<td><b><span data-bind="fadeInText: formatNumberDP(OutdoorTemperature())"><?php print formatNumberDP($data->getOutdoorTemperature())?></span> &deg;F</b></td>
											<td><span >
												<span data-bind="fadeInText: formatNumberDP(OutdoorTempStats.DailyValues.Max())"><?php print formatNumberDP($stats->getOutdoorTempStats()->getDailyValues()->getMax())?></span> &deg;F at 
												<span data-bind="fadeInText: new Date(Date.parse(OutdoorTempStats.DailyValues.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $stats->getOutdoorTempStats()->getDailyValues()->getMaxDate())?></span><br /> 
												<span data-bind="fadeInText: formatNumberDP(OutdoorTempStats.DailyValues.Min())"><?php print formatNumberDP($stats->getOutdoorTempStats()->getDailyValues()->getMin())?></span> &deg;F at 
												<span data-bind="fadeInText: new Date(Date.parse(OutdoorTempStats.DailyValues.MinDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $stats->getOutdoorTempStats()->getDailyValues()->getMinDate())?></span></span></td>
											<td><span ><span data-bind="fadeInText: formatNumberDP((OutdoorTemperatureTrend() * 60.0))"><?php print formatNumberDP($stats->getOutdoorTemperatureTrend() * 60.0)?></span> &deg;F /hr</span></td>
										</tr>

										<tr class="column-dark">
											<td><b>Humidity</b></td>
											<td><b><span data-bind="fadeInText: formatNumberDP(OutdoorHumidity())"><?php print formatNumberDP($data->getOutdoorHumidity())?></span>%</b></td>
											<td><span >
												<span data-bind="fadeInText: formatNumberDP(OutdoorHumidityStats.DailyValues.Max())"><?php print formatNumberDP($stats->getOutdoorHumidityStats()->getDailyValues()->getMax())?></span>% at 
												<span data-bind="fadeInText: new Date(Date.parse(OutdoorHumidityStats.DailyValues.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $stats->getOutdoorHumidityStats()->getDailyValues()->getMaxDate())?></span><br /> 
												<span data-bind="fadeInText: formatNumberDP(OutdoorHumidityStats.DailyValues.Min())"><?php print formatNumberDP($stats->getOutdoorHumidityStats()->getDailyValues()->getMin())?></span>% at 
												<span data-bind="fadeInText: new Date(Date.parse(OutdoorHumidityStats.DailyValues.MinDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $stats->getOutdoorHumidityStats()->getDailyValues()->getMinDate())?></span></span></td>
											<td><span><span data-bind="fadeInText: formatNumberDP((OutdoorHumidityTrend() * 60.0))"><?php print formatNumberDP($stats->getOutdoorHumidityTrend() * 60.0)?></span>% /hr</span></td>
										</tr>

										<tr class="column-light">
											<td><b>Pressure</b></td>
											<td><b><span data-bind="fadeInText: formatNumberDP(Pressure())"><?php print formatNumberDP($data->getPressure())?></span> in.</b></td>
											<td><span >
												<span data-bind="fadeInText: formatNumberDP(PressureStats.DailyValues.Max())"><?php print formatNumberDP($stats->getPressureStats()->getDailyValues()->getMax())?></span> in. at 
												<span data-bind="fadeInText: new Date(Date.parse(PressureStats.DailyValues.MaxDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $stats->getPressureStats()->getDailyValues()->getMaxDate())?></span><br /> 
												<span data-bind="fadeInText: formatNumberDP(PressureStats.DailyValues.Min())"><?php print formatNumberDP($stats->getPressureStats()->getDailyValues()->getMin())?></span> in. at 
												<span data-bind="fadeInText: new Date(Date.parse(PressureStats.DailyValues.MinDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $stats->getPressureStats()->getDailyValues()->getMinDate())?></span></span></td>
											<td><span >
												<span data-bind="fadeInText: (PressureTrend() * 180.0).toFixed(4)"><?php print formatNumber($stats->getPressureTrend() * 180.0, 4)?></span> in. /3 hr<br />
												<span data-bind="fadeInText: pressureTrendInInchesToLiteral(PressureTrend() * 180.0)"><?php print pressureTrendInInchesToLiteral($stats->getPressureTrend() * 180.0)?></span></span></td>
										</tr>

										<tr class="column-dark">
											<td><b>Wind</b></td>
											<td><b>Avg: </b><span data-bind="fadeInText: formatNumberDP(AvgWindSpeed())"><?php print formatNumberDP($data->getAvgWindSpeed())?></span> mph <span data-bind="fadeInText: getWindDirStr(AvgWindDir())"><?php print getWindDirStr($data->getAvgWindDir())?></span><br />
											<b>Gust: </b><span data-bind="fadeInText: formatNumberDP(MaxWindGustInterval())"><?php print formatNumberDP($stats->getMaxWindGustInterval())?></span> mph <span data-bind="fadeInText: getWindDirStr(WindDir())"><?php print getWindDirStr($data->getWindDir())?></span></td>
											<td><span ><b>Last hour:</b> 
												<span data-bind="fadeInText: formatNumberDP(WindGustStats.HourlyValues.Max())"><?php print formatnumberDP($stats->getWindGustStats()->getHourlyValues()->getMax())?></span> mph 
												<span data-bind="fadeInText: getWindDirStr(MaxGustWindDirection.HourValue())"><?php print getWindDirStr($stats->getMaxGustWindDirection()->getHourValue())?></span><br /><b>Max day:</b> 
												<span data-bind="fadeInText: formatNumberDP(WindGustStats.DailyValues.Max())"><?php print formatNumberDP($stats->getWindGustStats()->getDailyValues()->getMax())?></span> mph 
												<span data-bind="fadeInText: getWindDirStr(MaxGustWindDirection.DayValue())"><?php print getWindDirStr((float)$stats->getMaxGustWindDirection()->getDayValue())?></span></span></td>
											<td><span ><span data-bind="fadeInText: windSpeedToLiteralMPH(AvgWindSpeed())"><?php print windSpeedToLiteralMPH($data->getAvgWindSpeed())?></span></span></td>
										</tr>

										<tr class="column-light">
											<td><b>Rain</b> <span data-bind="fadeInText: new Date(Date.parse(RainCounts.LastChangeDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $stats->getRainCounts()->getLastChangeDate())?></span></td>
											<td><b><span data-bind="fadeInText: formatNumberDP(RainCounts.DayCount())"><?php print formatNumberDP($stats->getRainCounts()->getDayCount())?></span> in.</b></td>
											<td><span >
												<span data-bind="fadeInText: RainCounts.daysWithNoChange()"><?php print $stats->getRainCounts()->daysWithNoChange()?></span> day(s) without rain<br />
												<b>Month Rain:</b> <span data-bind="fadeInText: formatNumberDP(RainCounts.MonthCount())"><?php print formatNumberDP($stats->getRainCounts()->getMonthCount())?></span> in.</span></td>
											<td><span >
												<span data-bind="fadeInText: formatNumberDP(getHourCount(1, RainCounts.HourStartCounts(), RainCounts.LastSampleDate(), RainCounts.LastSampleValue()))"><?php print formatNumberDP($stats->getRainCounts()->getHourCount(1))?></span> in. /hr<br />
												<span data-bind="fadeInText: formatNumberDP(getHourCount(3, RainCounts.HourStartCounts(), RainCounts.LastSampleDate(), RainCounts.LastSampleValue()))"><?php  print formatNumberDP($stats->getRainCounts()->getHourCount(3))?></span> in. /3 hr</span></td>
										</tr>

										<tr class="column-dark">
											<td><b>Solar</b></td>
											<td><b><span data-bind="fadeInText: formatNumberDP(Solar())"><?php print formatNumberDP($data->getSolar())?></span></b></td>
											<td><span ><b>Last hour:</b> 
												<span data-bind="fadeInText: formatNumberDP(SolarStats.HourlyValues.Max())"><?php print formatNumberDP($stats->getSolarStats()->getHourlyValues()->getMax())?></span><br /><b>Max day:</b> 
												<span data-bind="fadeInText: formatNumberDP(SolarStats.DailyValues.Max())"><?php print formatNumberDP($stats->getSolarStats()->getDailyValues()->getMax())?></span></span></td>
											<td><span ><span data-bind="fadeInText: formatNumberDP((SolarTrend() * 60.0))"><?php print formatNumberDP($stats->getSolarTrend() * 60.0)?></span> /hr</span></td>
										</tr>

										<tr class="column-light">
											<td><b>Lightning</b></td>
											<td><b><span data-bind="fadeInText: formatNumberDP(LightningCounts.DayCount())"><?php print formatNumberDP($stats->getLightningCounts()->getDayCount())?></span></b></td>
											<td><span ><b>Last hour:</b> 
												<span data-bind="fadeInText: formatNumberDP(getHourCount(1, LightningCounts.HourStartCounts(), LightningCounts.LastSampleDate(), LightningCounts.LastSampleValue()))"><?php print formatNumberDP($stats->getLightningCounts()->getHourCount(1))?></span><br /><b>Last 3 hours:</b> 
												<span data-bind="fadeInText: formatNumberDP(getHourCount(3, LightningCounts.HourStartCounts(), LightningCounts.LastSampleDate(), LightningCounts.LastSampleValue()))"><?php print formatNumberDP($stats->getLightningCounts()->getHourCount(3))?></span></span></td>
											<td><span >
												<span data-bind="fadeInText: formatNumberDP(getHourlyAverageRatePerMinute(1, LightningCounts.HourStartCounts(), LightningCounts.LastSampleDate(), LightningCounts.LastSampleValue()))"><?php print formatNumberDP($stats->getLightningCounts()->getHourlyAverageRatePerMinute(1))?></span> /hr<br />
												<span data-bind="fadeInText: formatNumberDP(getHourlyAverageRatePerMinute(3, LightningCounts.HourStartCounts(), LightningCounts.LastSampleDate(), LightningCounts.LastSampleValue()))"><?php print formatNumberDP($stats->getLightningCounts()->getHourlyAverageRatePerMinute(3))?></span> /3 hr</span></td>
										</tr>

									</table>
									<div>
										<table class="borderTable">
										<colgroup>
											<col id="feelsLike" />
											<col id="dewPoint" />
											<col id="wetBulb" />
											<col id="cloudHeight" />
										</colgroup>
										<tr class="column-light">
											<td><span ><b>Feels Like:</b><br /><span data-bind="fadeInText: formatNumberDP(FeelsLike())"><?php print formatNumberDP($data->getFeelslike())?></span> &deg;F</span></td>
											<td><span ><b>Dew Point:</b><br /><span data-bind="fadeInText: formatNumberDP(DewPoint())"><?php print formatNumberDP($data->getDewPoint())?></span> &deg;F</span></td>
											<td><span ><b>Wet Bulb:</b><br /><span data-bind="fadeInText: formatNumberDP(WetBulb())"><?php print formatNumberDP($data->getWetBulb())?></span> &deg;F</span></td>
											<td><span ><b>Cloud Height:</b><br /><span data-bind="fadeInText: CloudBaseHeight().toFixed()"><?php print formatNumber($data->getCloudBaseHeight(), 0)?></span> ft</span></td>
										</tr>
										</table>
									</div>
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

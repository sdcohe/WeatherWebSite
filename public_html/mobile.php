<!DOCTYPE html>
<html>

<?php
require_once 'config/config.php';

$cfg = config::getInstance();
date_default_timezone_set($cfg->getTimeZone());

require_once 'classes/weatherData.php';
require_once 'classes/weatherStatistics.php';
require_once "classes/weatherConversions.php";
require_once "classes/nwsforecast.php";
require_once "classes/nwsadvisories.php";

$data = new weatherData();
$stats = new weatherStatistics();
$forecast = new NWSForecast();
$advisories = new NWSAdvisories();

$entries = $forecast->getForecastEntries();
$ForecastEntriesToDisplay = 8;

?>

<head>
	<title>Clopper&#39;s Mill East Weather</title>
	
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	
	<link rel="stylesheet" href="http://code.jquery.com/mobile/latest/jquery.mobile.css" />    	
	<link href="css/mobilestyle.css" rel="stylesheet" type="text/css" /> 
	
	<script type='text/javascript' src='config/config.js.php'></script>
	<script type="text/javascript" src="/min//?g=weatherjs"></script>
	<script src="http://code.jquery.com/mobile/latest/jquery.mobile.min.js"></script>

	<script type='text/javascript' src='js/date.js'></script>
	<script type='text/javascript' src='js/knockout.js'></script>
	<script type='text/javascript' src='js/knockoutmapping.js'></script>
	<script type='text/javascript' src='js/weathermodel.js'></script>

</head>

<body>

<script type="text/javascript">

$(document).ready(function(){
	
	$("#staticWarnings").css("display", "none");

	initWeatherDataModel();
	
	// get WX data every 15 secs
	$(document).everyTime(dataRefreshRate, retrieveWeatherData);

	//get WX stats every minute
	$(document).everyTime(statsRefreshRate, retrieveWeatherStats);

	//get update web cam image every 30 secs
	$("#webcamThumbnail").everyTime(webcamRefreshRate, updateWebcam);

	// get weather forecast every 10 minutes
	$(document).everyTime(forecastRefreshRate, retrieveNWSForecast);
	
	// get weather advisories every 10 minutes
	$(document).everyTime(advisoriesRefreshRate, retrieveNWSAdvisories);

	$("#dynamicWarnings").css("display", "inline");
	
});

</script>

<div data-role="page" id="home">

	<div data-role="header" data-theme="d"><h1 STYLE="font: 10pt/12pt sans-serif;" >Clopper&#39;s Mill East Weather</h1><h2 STYLE="font: 8pt/10pt sans-serif;" >Germantown, MD</h2> 
		<div data-role="navbar">
			<ul>
				<li><a href="#home" data-theme="b" class="ui-btn-active"><br />Home</a></li>
				<li><a href="#stats" data-theme="b"><br />Stats</a></li>
				<li><div><a href="#forecast" data-theme="b">Fore<br />cast</a></div></li>
				<li><div><a href="#webcam" data-theme="b">Web<br />Cam</a></div></li>
			</ul>
		</div><!-- /navbar -->

	</div> <!-- header -->


 	<div data-role="content">
	<div style="display: none;" data-bind="visible: isWarningInEffect(HighestPriorityAdvisory())" >
		<div data-bind="style: {'background': WarningBoxColor, 'color': WarningBoxBackgroundColor}" 
				class="advisoryBox" style="background:<?php print warningBoxColor($advisories->getHighestPriorityAdvisory())?>; color:<?php print contrastingTextColor(warningboxColor($advisories->getHighestPriorityAdvisory()))?>;">
			<a href="#alert" data-bind="style: {'color': WarningBoxBackgroundColor}" style="color:<?php print contrastingTextColor(warningboxColor($advisories->getHighestPriorityAdvisory()))?>;">				
				<b><span data-bind="text: HighestPriorityAdvisory()"><?php print trim($advisories->getHighestPriorityAdvisory())?></span></b>
			</a>
		</div>
	</div>
 	<table>
			<tr>
				<td class="heading">Data As Of</td>
				<td><span data-bind="text: new Date(Date.parse(DateTime())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $data->getDateTime())?></span></td>
			</tr>
			<tr>
				<td class="heading">Temperature</td>
				<td><span data-bind="text: OutdoorTemperature().toFixed(2)"><?php print formatNumberDP($data->getOutdoorTemperature())?></span> &deg;F
				<span> (<span data-bind="text: (OutdoorTemperatureTrend() * 60.0).toFixed(2)"><?php print formatNumberDP($stats->getOutdoorTemperatureTrend() * 60.0)?></span> &deg;F /hr.)</span></td>
			</tr>
			<tr>
				<td class="heading">Humidity</td>
				<td><span data-bind="text: OutdoorHumidity().toFixed(2)"><?php print formatNumberDP($data->getOutdoorHumidity())?></span>%
				<span> (<span data-bind="text: (OutdoorHumidityTrend() * 60.0).toFixed(2)"><?php print formatNumberDP($stats->getOutdoorHumidityTrend() * 60.0)?></span>% /hr.)</span></td>
			</tr>
			<tr>
				<td class="heading">Pressure</td>
				<td><span data-bind="text: Pressure().toFixed(2)"><?php print formatNumberDP($data->getPressure())?></span> in.
				<span data-bind="text: pressureTrendInInchesToLiteral(PressureTrend() * 180.0)"><?php print pressureTrendInInchesToLiteral($stats->getPressureTrend() * 180.0)?></span></td>
			</tr>
			<tr>
				<td class="heading">Wind (avg)</td>
				<td><span data-bind="text: AvgWindSpeed().toFixed(2)"><?php print formatNumberDP($data->getAvgWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(AvgWindDir())"><?php print getWindDirStr($data->getAvgWindDir())?></span></td>
			</tr>
			<tr>
				<td class="heading">Wind (Gust)</td>
				<td><span data-bind="text: WindSpeed().toFixed(2)"><?php print formatNumberDP($data->getWindSpeed())?></span> mph <span data-bind="text: getWindDirStr(WindDir())"><?php print getWindDirStr($data->getWindDir())?></span></td>
			</tr>
			<tr>
				<td class="heading">Rain</td>
				<td><span data-bind="text: RainCounts.DayCount().toFixed(2)"><?php print formatNumberDP($stats->getRainCounts()->getDayCount())?></span> in.</td>
			</tr>
			<tr>
				<td class="heading">Lightning</td>
				<td><span data-bind="text: LightningCounts.DayCount().toFixed(2)"><?php print formatNumberDP($stats->getLightningCounts()->getDayCount())?></span> in.</td>
			</tr>
			<tr>
				<td>Feels Like</td>
				<td><span data-bind="text: FeelsLike().toFixed(2)"><?php print formatNumberDP($data->getFeelslike())?></span> &deg;F</td>
			</tr>
			<tr>
				<td>Dew Point</td>
				<td><span data-bind="text: DewPoint().toFixed(2)"><?php print formatNumberDP($data->getDewPoint())?></span> &deg;F</td>
			</tr>
			<tr>
				<td>Wet Bulb</td>
				<td><span data-bind="text: WetBulb().toFixed(2)"><?php print formatNumberDP($data->getWetBulb())?></span> &deg;F</td>
			</tr>
			<tr>
				<td>Cloud Height</td>
				<td><span data-bind="text: CloudBaseHeight().toFixed()"><?php print formatNumber($data->getCloudBaseHeight(), 0)?></span> ft</td>
			</tr>
		</table>
	</div>
	
	<div data-role="footer" data-theme="d">
		<h4 STYLE="font: 8pt/10pt sans-serif;" >Copyright &copy; 2007-<?php print date('Y')?>, Clopper&#39;s Mill East Weather Website<br /> 
				Never base important decisions on this information.</h4>
 	</div>
</div>

<div data-role="page" id="stats">
	<div data-role="header" data-theme="d"><h1 STYLE="font: 10pt/12pt sans-serif;" >Clopper&#39;s Mill East Weather</h1><h2 STYLE="font: 8pt/10pt sans-serif;" >Germantown, MD</h2> 
		<div data-role="navbar">
			<ul>
				<li><a href="#home" data-theme="b"><br />Home</a></li>
				<li><a href="#stats" data-theme="b" class="ui-btn-active"><br />Stats</a></li>
				<li><div><a href="#forecast" data-theme="b">Fore<br />cast</a></div></li>
				<li><div><a href="#webcam" data-theme="b">Web<br />Cam</a></div></li>
			</ul>
		</div><!-- /navbar -->
	</div>

 	<div data-role="content">
	<div style="display: none;" data-bind="visible: isWarningInEffect(HighestPriorityAdvisory())" >
		<div data-bind="style: {'background': WarningBoxColor, 'color': WarningBoxBackgroundColor}" 
				class="advisoryBox" style="background:<?php print warningBoxColor($advisories->getHighestPriorityAdvisory())?>; color:<?php print contrastingTextColor(warningboxColor($advisories->getHighestPriorityAdvisory()))?>;">
			<a href="#alert" data-bind="style: {'color': WarningBoxBackgroundColor}" style="color:<?php print contrastingTextColor(warningboxColor($advisories->getHighestPriorityAdvisory()))?>;">				
				<b><span data-bind="text: HighestPriorityAdvisory()"><?php print trim($advisories->getHighestPriorityAdvisory())?></span></b>
			</a>
		</div>
	</div>
 	
 	<table>
			<tr>
				<td class="heading">Daily Stats</td>
				<td>As Of <span data-bind="text: new Date(Date.parse(LastSampleDate())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $stats->getLastSampleDate())?></span></td>
			</tr>
			<tr>
				<td colspan="2"><b>Temperature</b></td>
			</tr>
			<tr>
				<td>Max</td>
				<td>
					<span data-bind="text: OutdoorTempStats.DailyValues.Max().toFixed(2)"><?php print formatNumberDP($stats->getOutdoorTempStats()->getDailyValues()->getMax())?></span> &deg;F at 
					<span data-bind="text: new Date(Date.parse(OutdoorTempStats.DailyValues.MaxDate())).format(longTimeFormat)"><?php print date($cfg->getTimeFormat(), $stats->getOutdoorTempStats()->getDailyValues()->getMaxDate())?></span><br />
				</td>
			</tr>
			<tr>
				<td>Min</td>
				 <td>
					<span data-bind="text: OutdoorTempStats.DailyValues.Min().toFixed(2)"><?php print formatNumberDP($stats->getOutdoorTempStats()->getDailyValues()->getMin())?></span> &deg;F at 
					<span data-bind="text: new Date(Date.parse(OutdoorTempStats.DailyValues.MinDate())).format(longTimeFormat)"><?php print date($cfg->getTimeFormat(), $stats->getOutdoorTempStats()->getDailyValues()->getMinDate())?></span>
				</td>
			</tr>
			<tr>
				<td colspan="2"><b>Humidity</b></td>
			</tr>
			<tr>
				<td>Max</td>
				<td>
					<span data-bind="text: OutdoorHumidityStats.DailyValues.Max().toFixed(2)"><?php print formatNumberDP($stats->getOutdoorHumidityStats()->getDailyValues()->getMax())?></span>% at 
					<span data-bind="text: new Date(Date.parse(OutdoorHumidityStats.DailyValues.MaxDate())).format(longTimeFormat)"><?php print date($cfg->getTimeFormat(), $stats->getOutdoorHumidityStats()->getDailyValues()->getMaxDate())?></span>
				</td>
			</tr>
			<tr>
				<td>Min</td>
				<td> 
					<span data-bind="text: OutdoorHumidityStats.DailyValues.Min().toFixed(2)"><?php print formatNumberDP($stats->getOutdoorHumidityStats()->getDailyValues()->getMin())?></span>% at 
					<span data-bind="text: new Date(Date.parse(OutdoorHumidityStats.DailyValues.MinDate())).format(longTimeFormat)"><?php print date($cfg->getTimeFormat(), $stats->getOutdoorHumidityStats()->getDailyValues()->getMinDate())?></span>
				</td>
			</tr>
			<tr><td colspan="2"><b>Pressure</b></td></tr>
			<tr>
				<td>Max</td>
				<td>
					<span data-bind="text: PressureStats.DailyValues.Max().toFixed(2)"><?php print formatNumberDP($stats->getPressureStats()->getDailyValues()->getMax())?></span> in. at 
					<span data-bind="text: new Date(Date.parse(PressureStats.DailyValues.MaxDate())).format(longTimeFormat)"><?php print date($cfg->getTimeFormat(), $stats->getPressureStats()->getDailyValues()->getMaxDate())?></span>
				</td>
			<tr> 
				<td>Min</td>
				<td>
					<span data-bind="text: PressureStats.DailyValues.Min().toFixed(2)"><?php print formatNumberDP($stats->getPressureStats()->getDailyValues()->getMin())?></span> in. at 
					<span data-bind="text: new Date(Date.parse(PressureStats.DailyValues.MinDate())).format(longTimeFormat)"><?php print date($cfg->getTimeFormat(), $stats->getPressureStats()->getDailyValues()->getMinDate())?></span>
				</td>
			</tr>
			<tr>
				<td colspan="2"><b>Wind Gust</b></td>
			</tr>
			<tr>
				<td>Last hour</td>
				<td> 
					<span data-bind="text: WindGustStats.HourlyValues.Max().toFixed(2)"><?php print formatnumberDP($stats->getWindGustStats()->getHourlyValues()->getMax())?></span> mph 
					<span data-bind="text: getWindDirStr(MaxGustWindDirection.HourValue())"><?php print getWindDirStr($stats->getMaxGustWindDirection()->getHourValue())?></span>
				</td>
			</tr>
			<tr>
				<td>Max day</td>
				<td> 
					<span data-bind="text: WindGustStats.DailyValues.Max().toFixed(2)"><?php print formatNumberDP($stats->getWindGustStats()->getDailyValues()->getMax())?></span> mph 
					<span data-bind="text: getWindDirStr(MaxGustWindDirection.DayValue())"><?php print getWindDirStr((float)$stats->getMaxGustWindDirection()->getDayValue())?></span>
				</td>
			</tr>	
			<tr><td colspan="2"><b>Rain</b></td></tr>
			<tr>
				<td>Last Hour</td>
				<td><span data-bind="text: getHourCount(1, RainCounts.HourStartCounts(), RainCounts.LastSampleDate(), RainCounts.LastSampleValue()).toFixed(2)"><?php print formatNumberDP($stats->getRainCounts()->getHourCount(1))?></span> in.</td>
			</tr>
			<tr>
				<td>Last 3 Hours</td>
				<td><span data-bind="text: getHourCount(3, RainCounts.HourStartCounts(), RainCounts.LastSampleDate(), RainCounts.LastSampleValue()).toFixed(2)"><?php  print formatNumberDP($stats->getRainCounts()->getHourCount(3))?></span> in.</td>
			</tr>		
		</table>
	</div>
	
	<div data-role="footer" data-theme="d">
		<h4 STYLE="font: 8pt/10pt sans-serif;" >Copyright &copy; 2007-<?php print date('Y')?>, Clopper&#39;s Mill East Weather Website<br /> 
				Never base important decisions on this information.</h4>
 	</div>
</div>
 
<div data-role="page" id="webcam">
	<div data-role="header" data-theme="d"><h1 STYLE="font: 10pt/12pt sans-serif;" >Clopper&#39;s Mill East Weather</h1><h2 STYLE="font: 8pt/10pt sans-serif;" >Germantown, MD</h2> 
		<div data-role="navbar">
			<ul>
				<li><a href="#home" data-theme="b"><br />Home</a></li>
				<li><a href="#stats" data-theme="b"><br />Stats</a></li>
				<li><div><a href="#forecast" data-theme="b">Fore<br />cast</a></div></li>
				<li><div><a href="#webcam" data-theme="b" class="ui-btn-active">Web<br />Cam</a></div></li>
			</ul>
		</div><!-- /navbar -->
	</div>

 	<div data-role="content">
	<div style="display: none;" data-bind="visible: isWarningInEffect(HighestPriorityAdvisory())" >
		<div data-bind="style: {'background': WarningBoxColor, 'color': WarningBoxBackgroundColor}" 
				class="advisoryBox" style="background:<?php print warningBoxColor($advisories->getHighestPriorityAdvisory())?>; color:<?php print contrastingTextColor(warningboxColor($advisories->getHighestPriorityAdvisory()))?>;">
			<a href="#alert" data-bind="style: {'color': WarningBoxBackgroundColor}" style="color:<?php print contrastingTextColor(warningboxColor($advisories->getHighestPriorityAdvisory()))?>;">				
				<b><span data-bind="text: HighestPriorityAdvisory()"><?php print trim($advisories->getHighestPriorityAdvisory())?></span></b>
			</a>
		</div>
	</div>
 	 	
 	<img src="http://www.cloppermillweather.org/images/Front.jpg" width="220" height="165" id="webcamThumbnail" alt="Webcam image" />
	</div>
	
	<div data-role="footer" data-theme="d">
		<h4 STYLE="font: 8pt/10pt sans-serif;" >Copyright &copy; 2007-<?php print date('Y')?>, Clopper&#39;s Mill East Weather Website<br /> 
				Never base important decisions on this information.</h4>
 	</div>
</div>
 
<div data-role="page" id="forecast">
	<div data-role="header" data-theme="d"><h1 STYLE="font: 10pt/12pt sans-serif;" >Clopper&#39;s Mill East Weather</h1><h2 STYLE="font: 8pt/10pt sans-serif;" >Germantown, MD</h2> 
		<div data-role="navbar">
			<ul>
				<li><a href="#home" data-theme="b"><br />Home</a></li>
				<li><a href="#stats" data-theme="b"><br />Stats</a></li>
				<li><div><a href="#forecast" data-theme="b" class="ui-btn-active">Fore<br />cast</a></div></li>
				<li><div><a href="#webcam" data-theme="b">Web<br />Cam</a></div></li>
			</ul>
		</div><!-- /navbar -->
	</div>

 	<div data-role="content">
	<div style="display: none;" data-bind="visible: isWarningInEffect(HighestPriorityAdvisory())" >
		<div data-bind="style: {'background': WarningBoxColor, 'color': WarningBoxBackgroundColor}" 
				class="advisoryBox" style="background:<?php print warningBoxColor($advisories->getHighestPriorityAdvisory())?>; color:<?php print contrastingTextColor(warningboxColor($advisories->getHighestPriorityAdvisory()))?>;">
			<a href="#alert" data-bind="style: {'color': WarningBoxBackgroundColor}" style="color:<?php print contrastingTextColor(warningboxColor($advisories->getHighestPriorityAdvisory()))?>;">				
				<b><span data-bind="text: HighestPriorityAdvisory()"><?php print trim($advisories->getHighestPriorityAdvisory())?></span></b>
			</a>
		</div>
	</div>
 	 	
 	<a data-bind="attr: { href: ForecastCredit() }" href="<?php print $forecast->getCredit()?>">NWS Five Day Forecast</a><br />
		As Of:<span data-bind="text: new Date(Date.parse(ForecastCreationDate())).format(longDateTimeFormat)"><?php print date($cfg->getDateTimeFormat(), $forecast->getCreationDate())?></span>
		<table class="fullTable">
			<tr class="center">
				<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastEntries()[0].timePeriodName())"><?php print HTMLSplitWords($entries[0]->getTimePeriodName())?></span></b></span></td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastEntries()[0].iconURL, alt: ForecastEntries()[0].wordedForecast, title: ForecastEntries()[0].wordedForecast }"
						src="<?php print $entries[0]->getIconURL()?>" width="55" height="58"
						alt="<?php print $entries[0]->getWordedForecast()?>"
						title="<?php print $entries[0]->getWordedForecast()?>" />
				</span>
				</td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastEntries()[0].weatherType())"><?php print HTMLSplitWords($entries[0]->getWeatherType())?></span></span></td>
				<td style="width: 11%;"><span data-bind="text: ForecastEntries()[0].minMaxType()"><?php print $entries[0]->getMinMaxType()?></span>
					<span data-bind="style: { color: getHiLoColor(ForecastEntries()[0].minMaxType()) }" style="color: <?php if ($entries[0]->getMinMaxType() == "Hi") print $cfg->getRed(); else print $cfg->getBlue();?>;">
						<span data-bind="text: ForecastEntries()[0].minMax"><?php print $entries[0]->getMinMax()?></span>&deg;F</span>
				</td>
				<td style="width: 11%;">PoP <span data-bind="text: ForecastEntries()[0].pop"><?php print $entries[0]->getPop()?></span>%</td>
			</tr>

			<tr class="center">
				<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastEntries()[1].timePeriodName())"><?php print HTMLSplitWords($entries[1]->getTimePeriodName())?></span></b></span></td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastEntries()[1].iconURL, alt: ForecastEntries()[1].wordedForecast, title: ForecastEntries()[1].wordedForecast }"
						src="<?php print $entries[1]->getIconURL()?>" width="55" height="58"
						alt="<?php print $entries[1]->getWordedForecast()?>"
						title="<?php print $entries[1]->getWordedForecast()?>" />
				</span>
				</td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastEntries()[1].weatherType())"><?php print HTMLSplitWords($entries[1]->getWeatherType())?></span></span></td>
				<td style="width: 11%;"><span data-bind="text: ForecastEntries()[1].minMaxType()"><?php print $entries[1]->getMinMaxType()?></span>
					<span data-bind="style: { color: getHiLoColor(ForecastEntries()[1].minMaxType()) }" style="color: <?php if ($entries[1]->getMinMaxType() == "Hi") print $cfg->getRed(); else print $cfg->getBlue();?>;">
						<span data-bind="text: ForecastEntries()[1].minMax"><?php print $entries[1]->getMinMax()?></span>&deg;F</span>
				</td>
				<td style="width: 11%;">PoP <span data-bind="text: ForecastEntries()[1].pop"><?php print $entries[1]->getPop()?></span>%</td>
			</tr>
			
			<tr class="center">
				<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastEntries()[2].timePeriodName())"><?php print HTMLSplitWords($entries[2]->getTimePeriodName())?></span></b></span></td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastEntries()[2].iconURL, alt: ForecastEntries()[2].wordedForecast, title: ForecastEntries()[2].wordedForecast }"
						src="<?php print $entries[2]->getIconURL()?>" width="55" height="58"
						alt="<?php print $entries[2]->getWordedForecast()?>"
						title="<?php print $entries[2]->getWordedForecast()?>" />
				</span>
				</td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastEntries()[2].weatherType())"><?php print HTMLSplitWords($entries[2]->getWeatherType())?></span></span></td>
				<td style="width: 11%;"><span data-bind="text: ForecastEntries()[2].minMaxType()"><?php print $entries[2]->getMinMaxType()?></span>
					<span data-bind="style: { color: getHiLoColor(ForecastEntries()[2].minMaxType()) }" style="color: <?php if ($entries[2]->getMinMaxType() == "Hi") print $cfg->getRed(); else print $cfg->getBlue();?>;">
						<span data-bind="text: ForecastEntries()[2].minMax"><?php print $entries[2]->getMinMax()?></span>&deg;F</span>
				</td>
				<td style="width: 11%;">PoP <span data-bind="text: ForecastEntries()[2].pop"><?php print $entries[2]->getPop()?></span>%</td>
			</tr>
						
			<tr class="center">
				<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastEntries()[3].timePeriodName())"><?php print HTMLSplitWords($entries[3]->getTimePeriodName())?></span></b></span></td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastEntries()[3].iconURL, alt: ForecastEntries()[3].wordedForecast, title: ForecastEntries()[3].wordedForecast }"
						src="<?php print $entries[3]->getIconURL()?>" width="55" height="58"
						alt="<?php print $entries[3]->getWordedForecast()?>"
						title="<?php print $entries[3]->getWordedForecast()?>" />
				</span>
				</td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastEntries()[3].weatherType())"><?php print HTMLSplitWords($entries[3]->getWeatherType())?></span></span></td>
				<td style="width: 11%;"><span data-bind="text: ForecastEntries()[3].minMaxType()"><?php print $entries[3]->getMinMaxType()?></span>
					<span data-bind="style: { color: getHiLoColor(ForecastEntries()[3].minMaxType()) }" style="color: <?php if ($entries[3]->getMinMaxType() == "Hi") print $cfg->getRed(); else print $cfg->getBlue();?>;">
						<span data-bind="text: ForecastEntries()[3].minMax"><?php print $entries[3]->getMinMax()?></span>&deg;F</span>
				</td>
				<td style="width: 11%;">PoP <span data-bind="text: ForecastEntries()[3].pop"><?php print $entries[3]->getPop()?></span>%</td>
			</tr>
						
			<tr class="center">
				<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastEntries()[4].timePeriodName())"><?php print HTMLSplitWords($entries[4]->getTimePeriodName())?></span></b></span></td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastEntries()[4].iconURL, alt: ForecastEntries()[4].wordedForecast, title: ForecastEntries()[4].wordedForecast }"
						src="<?php print $entries[4]->getIconURL()?>" width="55" height="58"
						alt="<?php print $entries[4]->getWordedForecast()?>"
						title="<?php print $entries[4]->getWordedForecast()?>" />
				</span>
				</td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastEntries()[4].weatherType())"><?php print HTMLSplitWords($entries[4]->getWeatherType())?></span></span></td>
				<td style="width: 11%;"><span data-bind="text: ForecastEntries()[4].minMaxType()"><?php print $entries[4]->getMinMaxType()?></span>
					<span data-bind="style: { color: getHiLoColor(ForecastEntries()[4].minMaxType()) }" style="color: <?php if ($entries[4]->getMinMaxType() == "Hi") print $cfg->getRed(); else print $cfg->getBlue();?>;">
						<span data-bind="text: ForecastEntries()[4].minMax"><?php print $entries[4]->getMinMax()?></span>&deg;F</span>
				</td>
				<td style="width: 11%;">PoP <span data-bind="text: ForecastEntries()[4].pop"><?php print $entries[4]->getPop()?></span>%</td>
			</tr>
						
			<tr class="center">
				<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastEntries()[5].timePeriodName())"><?php print HTMLSplitWords($entries[5]->getTimePeriodName())?></span></b></span></td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastEntries()[5].iconURL, alt: ForecastEntries()[5].wordedForecast, title: ForecastEntries()[5].wordedForecast }"
						src="<?php print $entries[5]->getIconURL()?>" width="55" height="58"
						alt="<?php print $entries[5]->getWordedForecast()?>"
						title="<?php print $entries[5]->getWordedForecast()?>" />
				</span>
				</td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastEntries()[5].weatherType())"><?php print HTMLSplitWords($entries[5]->getWeatherType())?></span></span></td>
				<td style="width: 11%;"><span data-bind="text: ForecastEntries()[5].minMaxType()"><?php print $entries[5]->getMinMaxType()?></span>
					<span data-bind="style: { color: getHiLoColor(ForecastEntries()[5].minMaxType()) }" style="color: <?php if ($entries[5]->getMinMaxType() == "Hi") print $cfg->getRed(); else print $cfg->getBlue();?>;">
						<span data-bind="text: ForecastEntries()[5].minMax"><?php print $entries[5]->getMinMax()?></span>&deg;F</span>
				</td>
				<td style="width: 11%;">PoP <span data-bind="text: ForecastEntries()[5].pop"><?php print $entries[5]->getPop()?></span>%</td>
			</tr>
						
			<tr class="center">
				<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastEntries()[6].timePeriodName())"><?php print HTMLSplitWords($entries[6]->getTimePeriodName())?></span></b></span></td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastEntries()[6].iconURL, alt: ForecastEntries()[6].wordedForecast, title: ForecastEntries()[6].wordedForecast }"
						src="<?php print $entries[6]->getIconURL()?>" width="55" height="58"
						alt="<?php print $entries[6]->getWordedForecast()?>"
						title="<?php print $entries[6]->getWordedForecast()?>" />
				</span>
				</td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastEntries()[6].weatherType())"><?php print HTMLSplitWords($entries[6]->getWeatherType())?></span></span></td>
				<td style="width: 11%;"><span data-bind="text: ForecastEntries()[6].minMaxType()"><?php print $entries[6]->getMinMaxType()?></span>
					<span data-bind="style: { color: getHiLoColor(ForecastEntries()[6].minMaxType()) }" style="color: <?php if ($entries[6]->getMinMaxType() == "Hi") print $cfg->getRed(); else print $cfg->getBlue();?>;">
						<span data-bind="text: ForecastEntries()[6].minMax"><?php print $entries[6]->getMinMax()?></span>&deg;F</span>
				</td>
				<td style="width: 11%;">PoP <span data-bind="text: ForecastEntries()[6].pop"><?php print $entries[6]->getPop()?></span>%</td>
			</tr>
						
			<tr class="center">
				<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastEntries()[7].timePeriodName())"><?php print HTMLSplitWords($entries[7]->getTimePeriodName())?></span></b></span></td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastEntries()[7].iconURL, alt: ForecastEntries()[7].wordedForecast, title: ForecastEntries()[7].wordedForecast }"
						src="<?php print $entries[7]->getIconURL()?>" width="55" height="58"
						alt="<?php print $entries[7]->getWordedForecast()?>"
						title="<?php print $entries[7]->getWordedForecast()?>" />
				</span>
				</td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastEntries()[7].weatherType())"><?php print HTMLSplitWords($entries[7]->getWeatherType())?></span></span></td>
				<td style="width: 11%;"><span data-bind="text: ForecastEntries()[7].minMaxType()"><?php print $entries[7]->getMinMaxType()?></span>
					<span data-bind="style: { color: getHiLoColor(ForecastEntries()[7].minMaxType()) }" style="color: <?php if ($entries[7]->getMinMaxType() == "Hi") print $cfg->getRed(); else print $cfg->getBlue();?>;">
						<span data-bind="text: ForecastEntries()[7].minMax"><?php print $entries[7]->getMinMax()?></span>&deg;F</span>
				</td>
				<td style="width: 11%;">PoP <span data-bind="text: ForecastEntries()[7].pop"><?php print $entries[7]->getPop()?></span>%</td>
			</tr>
						
			<tr class="center">
				<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastEntries()[8].timePeriodName())"><?php print HTMLSplitWords($entries[8]->getTimePeriodName())?></span></b></span></td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastEntries()[8].iconURL, alt: ForecastEntries()[8].wordedForecast, title: ForecastEntries()[8].wordedForecast }"
						src="<?php print $entries[8]->getIconURL()?>" width="55" height="58"
						alt="<?php print $entries[8]->getWordedForecast()?>"
						title="<?php print $entries[8]->getWordedForecast()?>" />
				</span>
				</td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastEntries()[8].weatherType())"><?php print HTMLSplitWords($entries[8]->getWeatherType())?></span></span></td>
				<td style="width: 11%;"><span data-bind="text: ForecastEntries()[8].minMaxType()"><?php print $entries[8]->getMinMaxType()?></span>
					<span data-bind="style: { color: getHiLoColor(ForecastEntries()[8].minMaxType()) }" style="color: <?php if ($entries[8]->getMinMaxType() == "Hi") print $cfg->getRed(); else print $cfg->getBlue();?>;">
						<span data-bind="text: ForecastEntries()[8].minMax"><?php print $entries[8]->getMinMax()?></span>&deg;F</span>
				</td>
				<td style="width: 11%;">PoP <span data-bind="text: ForecastEntries()[8].pop"><?php print $entries[8]->getPop()?></span>%</td>
			</tr>
 			
		</table>
	</div>
	
	<div data-role="footer" data-theme="d">
		<h4 STYLE="font: 8pt/10pt sans-serif;" >Copyright &copy; 2007-<?php print date('Y')?>, Clopper&#39;s Mill East Weather Website<br /> 
				Never base important decisions on this information.</h4>
 	</div>
</div>
 
 <div data-role="page" id="alert">

	<div data-role="header" data-theme="d"><h1 STYLE="font: 10pt/12pt sans-serif;" >Clopper&#39;s Mill East Weather</h1><h2 STYLE="font: 8pt/10pt sans-serif;" >Germantown, MD</h2> 
		<div data-role="navbar">
			<ul>
				<li><a href="#home" data-theme="b"><br />Home</a></li>
				<li><a href="#stats" data-theme="b"><br />Stats</a></li>
				<li><div><a href="#forecast" data-theme="b">Fore<br />cast</a></div></li>
				<li><div><a href="#webcam" data-theme="b">Web<br />Cam</a></div></li>
			</ul>
		</div><!-- /navbar -->

	</div> <!-- header -->


 	<div data-role="content">
	<div style="display: none;" data-bind="visible: isWarningInEffect(HighestPriorityAdvisory())" >
		<div data-bind="style: {'background': WarningBoxColor, 'color': WarningBoxBackgroundColor}" 
				class="advisoryBox" style="background:<?php print warningBoxColor($advisories->getHighestPriorityAdvisory())?>; color:<?php print contrastingTextColor(warningboxColor($advisories->getHighestPriorityAdvisory()))?>;">
			<a href="#alert" data-bind="style: {'color': WarningBoxBackgroundColor}" style="color:<?php print contrastingTextColor(warningboxColor($advisories->getHighestPriorityAdvisory()))?>;">				
				<b><span data-bind="text: HighestPriorityAdvisory()"><?php print trim($advisories->getHighestPriorityAdvisory())?></span></b>
			</a>
		</div>
	</div>
 	
							<div id="staticWarnings">
							  <h2 class="title">Watches, Warnings, and Advisories</h2>
							  <p class="meta">As of <?php print date($cfg->getDateTimeFormat(), $advisories->getAdvisoryPublicationDate())?></p>
								<div class="entry">
									<?php print HTMLFormatAdvisories($advisories->getWarnings(), "Warnings")?>
							        <?php print HTMLFormatAdvisories($advisories->getAdvisories(), "Advisories")?>
							        <?php print HTMLFormatAdvisories($advisories->getWatches(), "Watches")?>
							        <?php print HTMLFormatAdvisories($advisories->getStatements(), "Statements")?>
							        <?php print HTMLFormatAdvisories($advisories->getOther(), "Other")?>
								</div>
							</div>
							<div class="post-bgbtm" id="dynamicWarnings" style="display: none;">
								<h2 class="title">Watches, Warnings, and Advisories</h2>
								<p class="meta">As of <span data-bind="text: new Date(Date.parse(AdvisoryPublicationDate())).format(longDateTimeFormat)"><?php print date($cfg->getDateTimeFormat(), $advisories->getAdvisoryPublicationDate())?></span></p>
								<div class="entry">
									<div style="display:none;" data-bind="visible: nwsWarnings().length > 0">
										<h2>Warnings</h2>
										<div data-bind="foreach: nwsWarnings">
											<h3 data-bind="text: title"></h3>
											<p class="meta" data-bind="text: new Date(Date.parse(date())).format(longDateTimeFormat)"></p>
											<div data-bind="visible: description().length == 0, text: summary"></div>
											<pre data-bind="text: description, visible: description().length > 0"></pre>
											<div data-bind="visible: instruction().length > 0 ">Instruction:<pre data-bind="text: instruction"> </pre></div>
										</div>
									</div>
									<div style="display:none;" data-bind="visible: nwsAdvisories().length > 0">
										<h2>Advisories</h2>
										<div data-bind="foreach: nwsAdvisories">
											<h3 data-bind="text: title"></h3>
											<p class="meta" data-bind="text: new Date(Date.parse(date())).format(longDateTimeFormat)"></p>
											<div data-bind="visible: description().length == 0, text: summary"></div>
											<pre data-bind="text: description, visible: description().length > 0"></pre>
											<div data-bind="visible: instruction().length > 0 ">Instruction:<pre data-bind="text: instruction"> </pre></div>										
										</div>
									</div> 
									<div style="display:none;" data-bind="visible: nwsWatches().length > 0">
										<h2>Watches</h2>
										<div data-bind="foreach: nwsWatches">
											<h3 data-bind="text: title"></h3>
											<p class="meta" data-bind="text: new Date(Date.parse(date())).format(longDateTimeFormat)"></p>
											<div data-bind="visible: description().length == 0, text: summary"></div>
											<pre data-bind="text: description, visible: description().length > 0"></pre>
											<div data-bind="visible: instruction().length > 0 ">Instruction:<pre data-bind="text: instruction"> </pre></div>										
										</div>
									</div>  
									<div style="display:none;" data-bind="visible: nwsStatements().length > 0">
										<h2>Statements</h2>
										<div data-bind="foreach: nwsStatements">
											<h3 data-bind="text: title"></h3>
											<p class="meta" data-bind="text: new Date(Date.parse(date())).format(longDateTimeFormat)"></p>
											<div data-bind="visible: description().length == 0, text: summary"></div>
											<pre data-bind="text: description, visible: description().length > 0"></pre>
											<div data-bind="visible: instruction().length > 0 ">Instruction:<pre data-bind="text: instruction"> </pre></div>										
										</div>
									</div>  
									<div style="display:none;" data-bind="visible: nwsOther().length > 0">
										<h2>Other</h2>
										<div data-bind="foreach: nwsOther">
											<h3 data-bind="text: title"></h3>
											<p class="meta" data-bind="text: new Date(Date.parse(date())).format(longDateTimeFormat)"></p>
											<div data-bind="visible: description().length == 0, text: summary"></div>
											<pre data-bind="text: description, visible: description().length > 0"></pre>
											<div data-bind="visible: instruction().length > 0 ">Instruction:<pre data-bind="text: instruction"> </pre></div>
										</div>
									</div>  
								</div>
							</div>		
	</div>
	
	<div data-role="footer" data-theme="d">
		<h4 STYLE="font: 8pt/10pt sans-serif;" >Copyright &copy; 2007-<?php print date('Y')?>, Clopper&#39;s Mill East Weather Website<br /> 
				Never base important decisions on this information.</h4>
 	</div>
</div>
</body>
</html>
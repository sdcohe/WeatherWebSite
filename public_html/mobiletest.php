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

$data = new weatherData();
$stats = new weatherStatistics();
$forecast = new NWSForecast();
?>

<head>
	<title>Clopper&#39;s Mill East Weather</title>
	
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<meta name="apple-mobile-web-app-capable" content="yes" />
	
	<link rel="stylesheet" href="http://code.jquery.com/mobile/latest/jquery.mobile.css" /> 	
	
	<script type="text/javascript" src="/min//?g=weatherjs"></script>
	<script src="http://code.jquery.com/mobile/latest/jquery.mobile.min.js"></script>

	<script type='text/javascript' src='js/knockout.js'></script>
	<script type='text/javascript' src='js/knockoutmapping.js'></script>
	<script type='text/javascript' src='js/weathermodel.js'></script>

</head>

<body>

<script type="text/javascript">

$(document).ready(function(){
	
	initWeatherDataModel();
	
	// get WX data every 15 secs
	$(document).everyTime(15000,retrieveWeatherData);

	//get WX stats every minute
	$(document).everyTime(60000, retrieveWeatherStats);

	//get update web cam image every 30 secs
	$("#webcamThumbnail").everyTime(30000, updateWebcam);

	// get weather forecast every 10 minutes
	$(document).everyTime(600000, retrieveNWSForecast);
	
});

</script>

<div data-role="page" id="home">

	<div data-role="header">
  		<div>Clopper&#39;s Mill East Weather<br />
		Germantown, MD<span id="loadericon"></span></div> 

		<div data-role="navbar">
			<ul>
				<li><a href="#home" class="ui-btn-active"><br />Home</a></li>
				<li><a href="#stats"><br />Stats</a></li>
				<li><div><a href="#forecast">Fore<br />cast</a></div></li>
				<li><div><a href="#webcam">Web<br />Cam</a></div></li>
			</ul>
		</div><!-- /navbar -->

	</div>

 	<div data-role="content">
		<table>
			<tr>
				<td class="heading">Data As Of</td>
				<td><span data-bind="text: new Date(Date.parse(DateTime())).format('mm/dd/yy HH:MM:ss')"><?php print date($cfg->getShortDateTimeFormat(), $data->getDateTime())?></span></td>
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
	
	<div data-role="footer">
		<div STYLE="font: 8pt/10pt sans-serif;" >Copyright &copy; 2007-<?php print date('Y')?>, Clopper&#39;s Mill East Weather Website<br /> 
				Never base important decisions on this information.</div>
 	</div>
</div>

<div data-role="page" id="stats">
	<div data-role="header">
  		<div>Clopper&#39;s Mill East Weather<br />
		Germantown, MD<span id="loadericon"></span></div> 
		<div data-role="navbar">
			<ul>
				<li><a href="#home"><br />Home</a></li>
				<li><a href="#stats"><br />Stats</a></li>
				<li><div><a href="#forecast">Fore<br />cast</a></div></li>
				<li><div><a href="#webcam">Web<br />Cam</a></div></li>
			</ul>
		</div><!-- /navbar -->
	</div>

 	<div data-role="content">
		<table>
			<tr>
				<td class="heading">Daily Stats</td>
				<td>As Of <span data-bind="text: new Date(Date.parse(LastSampleDate())).format('mm/dd/yy HH:MM:ss')"><?php print date($cfg->getShortDateTimeFormat(), $stats->getLastSampleDate())?></span></td>
			</tr>
			<tr><td colspan="2"><b>Temperature</b></td></tr>
			<tr>
				<td>Max</td>
				<td>
					<span data-bind="text: OutdoorTempStats.DailyValues.Max().toFixed(2)"><?php print formatNumberDP($stats->getOutdoorTempStats()->getDailyValues()->getMax())?></span> &deg;F at 
					<span data-bind="text: new Date(Date.parse(OutdoorTempStats.DailyValues.MaxDate())).format('HH:MM:ss Z')"><?php print date($cfg->getTimeFormat(), $stats->getOutdoorTempStats()->getDailyValues()->getMaxDate())?></span><br />
				</td>
			</tr>
			<tr>
				<td>Min</td>
				 <td>
					<span data-bind="text: OutdoorTempStats.DailyValues.Min().toFixed(2)"><?php print formatNumberDP($stats->getOutdoorTempStats()->getDailyValues()->getMin())?></span> &deg;F at 
					<span data-bind="text: new Date(Date.parse(OutdoorTempStats.DailyValues.MinDate())).format('HH:MM:ss Z')"><?php print date($cfg->getTimeFormat(), $stats->getOutdoorTempStats()->getDailyValues()->getMinDate())?></span>
				</td>
			</tr>
			<tr><td colspan="2"><b>Humidity</b></td></tr>
			<tr>
				<td>Max</td>
				<td>
					<span data-bind="text: OutdoorHumidityStats.DailyValues.Max().toFixed(2)"><?php print formatNumberDP($stats->getOutdoorHumidityStats()->getDailyValues()->getMax())?></span>% at 
					<span data-bind="text: new Date(Date.parse(OutdoorHumidityStats.DailyValues.MaxDate())).format('HH:MM:ss Z')"><?php print date($cfg->getTimeFormat(), $stats->getOutdoorHumidityStats()->getDailyValues()->getMaxDate())?></span>
				<td>
			</tr>
			<tr>
				<td>Min</td>
				<td> 
					<span data-bind="text: OutdoorHumidityStats.DailyValues.Min().toFixed(2)"><?php print formatNumberDP($stats->getOutdoorHumidityStats()->getDailyValues()->getMin())?></span>% at 
					<span data-bind="text: new Date(Date.parse(OutdoorHumidityStats.DailyValues.MinDate())).format('HH:MM:ss Z')"><?php print date($cfg->getTimeFormat(), $stats->getOutdoorHumidityStats()->getDailyValues()->getMinDate())?></span>
				</td>
			</tr>
			<tr><td colspan="2"><b>Pressure</b></td></tr>
			<tr>
				<td>Max</td>
				<td>
					<span data-bind="text: PressureStats.DailyValues.Max().toFixed(2)"><?php print formatNumberDP($stats->getPressureStats()->getDailyValues()->getMax())?></span> in. at 
					<span data-bind="text: new Date(Date.parse(PressureStats.DailyValues.MaxDate())).format('HH:MM:ss Z')"><?php print date($cfg->getTimeFormat(), $stats->getPressureStats()->getDailyValues()->getMaxDate())?></span>
				</td>
			<tr> 
				<td>Min</td>
				<td>
					<span data-bind="text: PressureStats.DailyValues.Min().toFixed(2)"><?php print formatNumberDP($stats->getPressureStats()->getDailyValues()->getMin())?></span> in. at 
					<span data-bind="text: new Date(Date.parse(PressureStats.DailyValues.MinDate())).format('HH:MM:ss Z')"><?php print date($cfg->getTimeFormat(), $stats->getPressureStats()->getDailyValues()->getMinDate())?></span>
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
	
	<div data-role="footer">
		<div STYLE="font: 8pt/10pt sans-serif;" >Copyright &copy; 2007-<?php print date('Y')?>, Clopper&#39;s Mill East Weather Website<br /> 
				Never base important decisions on this information.</div>
 	</div>
</div>
 
<div data-role="page" id="webcam">
	<div data-role="header">
  		<div>Clopper&#39;s Mill East Weather<br />
		Germantown, MD<span id="loadericon"></span></div> 
		<div data-role="navbar">
			<ul>
				<li><a href="#home"><br />Home</a></li>
				<li><a href="#stats"><br />Stats</a></li>
				<li><div><a href="#forecast">Fore<br />cast</a></div></li>
				<li><div><a href="#webcam">Web<br />Cam</a></div></li>
			</ul>
		</div><!-- /navbar -->
	</div>

 	<div data-role="content">
		<img src="http://www.cloppermillweather.org/images/Front.jpg" width="220" height="165" id="webcamThumbnail" alt="Webcam image"></img>
	</div>
	
	<div data-role="footer">
		<div STYLE="font: 8pt/10pt sans-serif;" >Copyright &copy; 2007-<?php print date('Y')?>, Clopper&#39;s Mill East Weather Website<br /> 
				Never base important decisions on this information.</div>
 	</div>
</div>
 
<div data-role="page" id="forecast">
	<div data-role="header">
  		<div>Clopper&#39;s Mill East Weather<br />
		Germantown, MD<span id="loadericon"></span></div> 
		<div data-role="navbar">
			<ul>
				<li><a href="#home"><br />Home</a></li>
				<li><a href="#stats"><br />Stats</a></li>
				<li><div><a href="#forecast">Fore<br />cast</a></div></li>
				<li><div><a href="#webcam">Web<br />Cam</a></div></li>
			</ul>
		</div><!-- /navbar -->
	</div>

 	<div data-role="content">
		<a data-bind="attr: { href: ForecastCredit() }" href="<?php print $forecast->getCredit()?>">NWS Five Day Forecast</a><br />
		As Of:<span data-bind="text: new Date(Date.parse(ForecastCreationDate())).format('ddd mmm dd, yyyy HH:MM:ss Z')"><?php print date($cfg->getDateTimeFormat(), $forecast->getCreationDate())?></span>
		<table class="fullTable">
			<tr class="center">
				<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastDay()[0])"><?php print HTMLSplitWords($forecast->getForecastDay(0))?></span></b></span></td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastIcon()[0], alt: Forecast()[0], title: Forecast()[0] }"
						src="<?php print $forecast->getForecastIcons(0)?>" width="55" height="58"
						alt="<?php print $forecast->getForecast(0)?>"
						title="<?php print $forecast->getForecast(0)?>" />
				</span>
				</td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastSummary()[0])"><?php print HTMLSplitWords($forecast->getForecastSummary(0))?></span></span></td>
				<td style="width: 11%;"><span data-bind="text: HiLoType()[0]"><?php print $forecast->getHiLoType(0)?></span>
					<span data-bind="style: { color: getHiLoColor(HiLoType()[0]) }" style="color: <?php print $forecast->getHiLoColor(0)?>;">
						<span data-bind="text: HiLoTemp()[0]"><?php print $forecast->getHiLoTemp(0)?></span>&deg;F</span>
				</td>
				<td style="width: 11%;">PoP <span data-bind="text: ProbabilityofPrecipitation()[0]"><?php print $forecast->getProbabilityOfPrecipitation(0)?></span>%</td>
			</tr>

			<tr class="center">
				<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastDay()[1])"><?php print HTMLSplitWords($forecast->getForecastDay(1))?></span></b></span></td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastIcon()[1], alt: Forecast()[1], title: Forecast()[1] }"
						src="<?php print $forecast->getForecastIcons(1)?>" width="55" height="58"
						alt="<?php print $forecast->getForecast(1)?>"
						title="<?php print $forecast->getForecast(1)?>" />
				</span>
				</td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastSummary()[1])"><?php print HTMLSplitWords($forecast->getForecastSummary(1))?></span></span></td>
				<td style="width: 11%;"><span data-bind="text: HiLoType()[1]"><?php print $forecast->getHiLoType(1)?></span>
					<span data-bind="style: { color: getHiLoColor(HiLoType()[1]) }" style="color: <?php print $forecast->getHiLoColor(1)?>;">
						<span data-bind="text: HiLoTemp()[1]"><?php print $forecast->getHiLoTemp(1)?></span>&deg;F</span>
				</td>
				<td style="width: 11%;">PoP <span data-bind="text: ProbabilityofPrecipitation()[1]"><?php print $forecast->getProbabilityOfPrecipitation(1)?></span>%</td>
			</tr>
			
			<tr class="center">
				<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastDay()[2])"><?php print HTMLSplitWords($forecast->getForecastDay(2))?></span></b></span></td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastIcon()[2], alt: Forecast()[2], title: Forecast()[2] }"
						src="<?php print $forecast->getForecastIcons(2)?>" width="55" height="58"
						alt="<?php print $forecast->getForecast(2)?>"
						title="<?php print $forecast->getForecast(2)?>" />
				</span>
				</td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastSummary()[2])"><?php print HTMLSplitWords($forecast->getForecastSummary(2))?></span></span></td>
				<td style="width: 11%;"><span data-bind="text: HiLoType()[2]"><?php print $forecast->getHiLoType(2)?></span>
					<span data-bind="style: { color: getHiLoColor(HiLoType()[2]) }" style="color: <?php print $forecast->getHiLoColor(2)?>;">
						<span data-bind="text: HiLoTemp()[2]"><?php print $forecast->getHiLoTemp(2)?></span>&deg;F</span>
				</td>
				<td style="width: 11%;">PoP <span data-bind="text: ProbabilityofPrecipitation()[2]"><?php print $forecast->getProbabilityOfPrecipitation(2)?></span>%</td>
			</tr>
			
			<tr class="center">
				<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastDay()[3])"><?php print HTMLSplitWords($forecast->getForecastDay(3))?></span></b></span></td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastIcon()[3], alt: Forecast()[3], title: Forecast()[3] }"
						src="<?php print $forecast->getForecastIcons(3)?>" width="55" height="58"
						alt="<?php print $forecast->getForecast(3)?>"
						title="<?php print $forecast->getForecast(3)?>" />
				</span>
				</td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastSummary()[3])"><?php print HTMLSplitWords($forecast->getForecastSummary(3))?></span></span></td>
				<td style="width: 11%;"><span data-bind="text: HiLoType()[3]"><?php print $forecast->getHiLoType(3)?></span>
					<span data-bind="style: { color: getHiLoColor(HiLoType()[3]) }" style="color: <?php print $forecast->getHiLoColor(3)?>;">
						<span data-bind="text: HiLoTemp()[3]"><?php print $forecast->getHiLoTemp(3)?></span>&deg;F</span>
				</td>
				<td style="width: 11%;">PoP <span data-bind="text: ProbabilityofPrecipitation()[3]"><?php print $forecast->getProbabilityOfPrecipitation(3)?></span>%</td>
			</tr>
			
			<tr class="center">
				<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastDay()[4])"><?php print HTMLSplitWords($forecast->getForecastDay(4))?></span></b></span></td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastIcon()[4], alt: Forecast()[4], title: Forecast()[4] }"
						src="<?php print $forecast->getForecastIcons(4)?>" width="55" height="58"
						alt="<?php print $forecast->getForecast(4)?>"
						title="<?php print $forecast->getForecast(4)?>" />
				</span>
				</td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastSummary()[4])"><?php print HTMLSplitWords($forecast->getForecastSummary(4))?></span></span></td>
				<td style="width: 11%;"><span data-bind="text: HiLoType()[4]"><?php print $forecast->getHiLoType(4)?></span>
					<span data-bind="style: { color: getHiLoColor(HiLoType()[4]) }" style="color: <?php print $forecast->getHiLoColor(4)?>;">
						<span data-bind="text: HiLoTemp()[4]"><?php print $forecast->getHiLoTemp(4)?></span>&deg;F</span>
				</td>
				<td style="width: 11%;">PoP <span data-bind="text: ProbabilityofPrecipitation()[4]"><?php print $forecast->getProbabilityOfPrecipitation(4)?></span>%</td>
			</tr>
			
			<tr class="center">
				<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastDay()[5])"><?php print HTMLSplitWords($forecast->getForecastDay(5))?></span></b></span></td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastIcon()[5], alt: Forecast()[5], title: Forecast()[5] }"
						src="<?php print $forecast->getForecastIcons(5)?>" width="55" height="58"
						alt="<?php print $forecast->getForecast(5)?>"
						title="<?php print $forecast->getForecast(5)?>" />
				</span>
				</td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastSummary()[5])"><?php print HTMLSplitWords($forecast->getForecastSummary(5))?></span></span></td>
				<td style="width: 11%;"><span data-bind="text: HiLoType()[5]"><?php print $forecast->getHiLoType(5)?></span>
					<span data-bind="style: { color: getHiLoColor(HiLoType()[5]) }" style="color: <?php print $forecast->getHiLoColor(5)?>;">
						<span data-bind="text: HiLoTemp()[5]"><?php print $forecast->getHiLoTemp(5)?></span>&deg;F</span>
				</td>
				<td style="width: 11%;">PoP <span data-bind="text: ProbabilityofPrecipitation()[5]"><?php print $forecast->getProbabilityOfPrecipitation(5)?></span>%</td>
			</tr>
			
			<tr class="center">
				<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastDay()[6])"><?php print HTMLSplitWords($forecast->getForecastDay(6))?></span></b></span></td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastIcon()[6], alt: Forecast()[6], title: Forecast()[6] }"
						src="<?php print $forecast->getForecastIcons(6)?>" width="55" height="58"
						alt="<?php print $forecast->getForecast(6)?>"
						title="<?php print $forecast->getForecast(6)?>" />
				</span>
				</td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastSummary()[6])"><?php print HTMLSplitWords($forecast->getForecastSummary(6))?></span></span></td>
				<td style="width: 11%;"><span data-bind="text: HiLoType()[6]"><?php print $forecast->getHiLoType(6)?></span>
					<span data-bind="style: { color: getHiLoColor(HiLoType()[6]) }" style="color: <?php print $forecast->getHiLoColor(6)?>;">
						<span data-bind="text: HiLoTemp()[6]"><?php print $forecast->getHiLoTemp(6)?></span>&deg;F</span>
				</td>
				<td style="width: 11%;">PoP <span data-bind="text: ProbabilityofPrecipitation()[6]"><?php print $forecast->getProbabilityOfPrecipitation(6)?></span>%</td>
			</tr>
			
			<tr class="center">
				<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastDay()[7])"><?php print HTMLSplitWords($forecast->getForecastDay(7))?></span></b></span></td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastIcon()[7], alt: Forecast()[7], title: Forecast()[7] }"
						src="<?php print $forecast->getForecastIcons(7)?>" width="55" height="58"
						alt="<?php print $forecast->getForecast(7)?>"
						title="<?php print $forecast->getForecast(7)?>" />
				</span>
				</td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastSummary()[7])"><?php print HTMLSplitWords($forecast->getForecastSummary(7))?></span></span></td>
				<td style="width: 11%;"><span data-bind="text: HiLoType()[7]"><?php print $forecast->getHiLoType(7)?></span>
					<span data-bind="style: { color: getHiLoColor(HiLoType()[7]) }" style="color: <?php print $forecast->getHiLoColor(7)?>;">
						<span data-bind="text: HiLoTemp()[7]"><?php print $forecast->getHiLoTemp(7)?></span>&deg;F</span>
				</td>
				<td style="width: 11%;">PoP <span data-bind="text: ProbabilityofPrecipitation()[7]"><?php print $forecast->getProbabilityOfPrecipitation(7)?></span>%</td>
			</tr>
			
			<tr class="center">
				<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastDay()[8])"><?php print HTMLSplitWords($forecast->getForecastDay(8))?></span></b></span></td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastIcon()[8], alt: Forecast()[8], title: Forecast()[8] }"
						src="<?php print $forecast->getForecastIcons(8)?>" width="55" height="58"
						alt="<?php print $forecast->getForecast(8)?>"
						title="<?php print $forecast->getForecast(8)?>" />
				</span>
				</td>
				<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastSummary()[8])"><?php print HTMLSplitWords($forecast->getForecastSummary(8))?></span></span></td>
				<td style="width: 11%;"><span data-bind="text: HiLoType()[8]"><?php print $forecast->getHiLoType(8)?></span>
					<span data-bind="style: { color: getHiLoColor(HiLoType()[8]) }" style="color: <?php print $forecast->getHiLoColor(8)?>;">
						<span data-bind="text: HiLoTemp()[8]"><?php print $forecast->getHiLoTemp(8)?></span>&deg;F</span>
				</td>
				<td style="width: 11%;">PoP <span data-bind="text: ProbabilityofPrecipitation()[8]"><?php print $forecast->getProbabilityOfPrecipitation(8)?></span>%</td>
			</tr>
		</table>
	</div>
	
	<div data-role="footer">
		<div STYLE="font: 8pt/10pt sans-serif;" >Copyright &copy; 2007-<?php print date('Y')?>, Clopper&#39;s Mill East Weather Website<br /> 
				Never base important decisions on this information.</div>
 	</div>
</div>
 
</body>
</html>
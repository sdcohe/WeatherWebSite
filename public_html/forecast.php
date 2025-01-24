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
require_once 'config/config.php';
require_once "classes/weatherData.php";
require_once "classes/weatherStatistics.php";
require_once "classes/nwsforecast.php";

$cfg = config::getInstance();
date_default_timezone_set($cfg->getTimeZone());

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

	// get WX stats every minute
	$(document).everyTime(statsRefreshRate, retrieveWeatherStats);

	// update web cam image every 30secs
	$("#webcamThumbnail").everyTime(webcamRefreshRate, updateWebcam);

	// get weather forecast every 10 minutes
	$(document).everyTime(forecastRefreshRate, retrieveNWSForecast);
	
	// update favicon every 10 minutes based on weather forecast
//	$(document).everyTime(faviconRefreshRate, updateFavicon);
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
					<!-- <div class="post">
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
										<tr class="center">
											<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastDay()[0])"><?php print HTMLSplitWords($forecast->getForecastDay(0))?></span></b></span></td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastDay()[1])"><?php print HTMLSplitWords($forecast->getForecastDay(1))?></span></b></span></td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastDay()[2])"><?php print HTMLSplitWords($forecast->getForecastDay(2))?></span></b></span></td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastDay()[3])"><?php print HTMLSplitWords($forecast->getForecastDay(3))?></span></b></span></td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastDay()[4])"><?php print HTMLSplitWords($forecast->getForecastDay(4))?></span></b></span></td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastDay()[5])"><?php print HTMLSplitWords($forecast->getForecastDay(5))?></span></b></span></td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastDay()[6])"><?php print HTMLSplitWords($forecast->getForecastDay(6))?></span></b></span></td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastDay()[7])"><?php print HTMLSplitWords($forecast->getForecastDay(7))?></span></b></span></td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><b><span data-bind="html: HTMLSplitWords(ForecastDay()[8])"><?php print HTMLSplitWords($forecast->getForecastDay(8))?></span></b></span></td>
										</tr>

										<tr class="center">
											<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastIcon()[0], alt: Forecast()[0], title: Forecast()[0] }"
													src="<?php print $forecast->getForecastIcons(0)?>" width="55" height="58"
													alt="<?php print $forecast->getForecast(0)?>"
													title="<?php print $forecast->getForecast(0)?>" />
											</span>
											</td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastIcon()[1], alt: Forecast()[1], title: Forecast()[1] }"
													src="<?php print $forecast->getForecastIcons(1)?>" width="55" height="58"
													alt="<?php print $forecast->getForecast(1)?>"
													title="<?php print $forecast->getForecast(1)?>" />
											</span>
											</td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastIcon()[2], alt: Forecast()[2], title: Forecast()[2] }"
													src="<?php print $forecast->getForecastIcons(2)?>" width="55" height="58"
													alt="<?php print $forecast->getForecast(2)?>"
													title="<?php print $forecast->getForecast(2)?>" />
											</span>
											</td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastIcon()[3], alt: Forecast()[3], title: Forecast()[3] }"
													src="<?php print $forecast->getForecastIcons(3)?>" width="55" height="58"
													alt="<?php print $forecast->getForecast(3)?>"
													title="<?php print $forecast->getForecast(3)?>" />
											</span>
											</td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastIcon()[4], alt: Forecast()[4], title: Forecast()[4] }"
													src="<?php print $forecast->getForecastIcons(4)?>" width="55" height="58"
													alt="<?php print $forecast->getForecast(4)?>"
													title="<?php print $forecast->getForecast(4)?>" />
											</span>
											</td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastIcon()[5], alt: Forecast()[5], title: Forecast()[5] }"
													src="<?php print $forecast->getForecastIcons(5)?>" width="55" height="58"
													alt="<?php print $forecast->getForecast(5)?>"
													title="<?php print $forecast->getForecast(5)?>" />
											</span>
											</td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastIcon()[6], alt: Forecast()[6], title: Forecast()[6] }"
													src="<?php print $forecast->getForecastIcons(6)?>" width="55" height="58"
													alt="<?php print $forecast->getForecast(6)?>"
													title="<?php print $forecast->getForecast(6)?>" />
											</span>
											</td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastIcon()[7], alt: Forecast()[7], title: Forecast()[7] }"
													src="<?php print $forecast->getForecastIcons(7)?>" width="55" height="58"
													alt="<?php print $forecast->getForecast(7)?>"
													title="<?php print $forecast->getForecast(7)?>" />
											</span>
											</td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><img data-bind="attr: { src: ForecastIcon()[8], alt: Forecast()[8], title: Forecast()[8] }"
													src="<?php print $forecast->getForecastIcons(8)?>" width="55" height="58"
													alt="<?php print $forecast->getForecast(8)?>"
													title="<?php print $forecast->getForecast(8)?>" />
											</span>
											</td>
										</tr>

										<tr class="center">
											<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastSummary()[0])"><?php print HTMLSplitWords($forecast->getForecastSummary(0))?></span></span></td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastSummary()[1])"><?php print HTMLSplitWords($forecast->getForecastSummary(1))?></span></span></td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastSummary()[2])"><?php print HTMLSplitWords($forecast->getForecastSummary(2))?></span></span></td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastSummary()[3])"><?php print HTMLSplitWords($forecast->getForecastSummary(3))?></span></span></td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastSummary()[4])"><?php print HTMLSplitWords($forecast->getForecastSummary(4))?></span></span></td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastSummary()[5])"><?php print HTMLSplitWords($forecast->getForecastSummary(5))?></span></span></td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastSummary()[6])"><?php print HTMLSplitWords($forecast->getForecastSummary(6))?></span></span></td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastSummary()[7])"><?php print HTMLSplitWords($forecast->getForecastSummary(7))?></span></span></td>
											<td style="width: 11%;"><span style="font-size: 8pt;"><span data-bind="html: HTMLSplitWords(ForecastSummary()[8])"><?php print HTMLSplitWords($forecast->getForecastSummary(8))?></span></span></td>
										</tr>

										<tr class="center">
											<td style="width: 11%;"><span data-bind="text: HiLoType()[0]"><?php print $forecast->getHiLoType(0)?></span>
												<span data-bind="style: { color: getHiLoColor(HiLoType()[0]) }" style="color: <?php print $forecast->getHiLoColor(0)?>;">
													<span data-bind="text: HiLoTemp()[0]"><?php print $forecast->getHiLoTemp(0)?></span>&deg;F</span>
											</td>
											<td style="width: 11%;"><span data-bind="text: HiLoType()[1]"><?php print $forecast->getHiLoType(1)?></span>
												<span data-bind="style: { color: getHiLoColor(HiLoType()[1]) }" style="color: <?php print $forecast->getHiLoColor(1)?>;">
													<span data-bind="text: HiLoTemp()[1]"><?php print $forecast->getHiLoTemp(1)?></span>&deg;F</span>
											</td>
											<td style="width: 11%;"><span data-bind="text: HiLoType()[2]"><?php print $forecast->getHiLoType(2)?></span>
												<span data-bind="style: { color: getHiLoColor(HiLoType()[2]) }" style="color: <?php print $forecast->getHiLoColor(2)?>;">
													<span data-bind="text: HiLoTemp()[2]"><?php print $forecast->getHiLoTemp(2)?></span>&deg;F</span>
											</td>
											<td style="width: 11%;"><span data-bind="text: HiLoType()[3]"><?php print $forecast->getHiLoType(3)?></span>
												<span data-bind="style: { color: getHiLoColor(HiLoType()[3]) }" style="color: <?php print $forecast->getHiLoColor(3)?>;">
													<span data-bind="text: HiLoTemp()[3]"><?php print $forecast->getHiLoTemp(3)?></span>&deg;F</span>
											</td>
											<td style="width: 11%;"><span data-bind="text: HiLoType()[4]"><?php print $forecast->getHiLoType(4)?></span>
												<span data-bind="style: { color: getHiLoColor(HiLoType()[4]) }" style="color: <?php print $forecast->getHiLoColor(4)?>;">
													<span data-bind="text: HiLoTemp()[4]"><?php print $forecast->getHiLoTemp(4)?></span>&deg;F</span>
											</td>
											<td style="width: 11%;"><span data-bind="text: HiLoType()[5]"><?php print $forecast->getHiLoType(5)?></span>
												<span data-bind="style: { color: getHiLoColor(HiLoType()[5]) }" style="color: <?php print $forecast->getHiLoColor(5)?>;">
													<span data-bind="text: HiLoTemp()[5]"><?php print $forecast->getHiLoTemp(5)?></span>&deg;F</span>
											</td>
											<td style="width: 11%;"><span data-bind="text: HiLoType()[6]"><?php print $forecast->getHiLoType(6)?></span>
												<span data-bind="style: { color: getHiLoColor(HiLoType()[6]) }" style="color: <?php print $forecast->getHiLoColor(6)?>;">
													<span data-bind="text: HiLoTemp()[6]"><?php print $forecast->getHiLoTemp(6)?></span>&deg;F</span>
											</td>
											<td style="width: 11%;"><span data-bind="text: HiLoType()[7]"><?php print $forecast->getHiLoType(7)?></span>
												<span data-bind="style: { color: getHiLoColor(HiLoType()[7]) }" style="color: <?php print $forecast->getHiLoColor(7)?>;">
													<span data-bind="text: HiLoTemp()[7]"><?php print $forecast->getHiLoTemp(7)?></span>&deg;F</span>
											</td>
											<td style="width: 11%;"><span data-bind="text: HiLoType()[8]"><?php print $forecast->getHiLoType(8)?></span>
												<span data-bind="style: { color: getHiLoColor(HiLoType()[8]) }" style="color: <?php print $forecast->getHiLoColor(8)?>;">
													<span data-bind="text: HiLoTemp()[8]"><?php print $forecast->getHiLoTemp(8)?></span>&deg;F</span>
											</td>
										</tr>

										<tr class="center">
											<td style="width: 11%;">PoP <span data-bind="text: ProbabilityofPrecipitation()[0]"><?php print $forecast->getProbabilityOfPrecipitation(0)?></span>%</td>
											<td style="width: 11%;">PoP <span data-bind="text: ProbabilityofPrecipitation()[1]"><?php print $forecast->getProbabilityOfPrecipitation(1)?></span>%</td>
											<td style="width: 11%;">PoP <span data-bind="text: ProbabilityofPrecipitation()[2]"><?php print $forecast->getProbabilityOfPrecipitation(2)?></span>%</td>
											<td style="width: 11%;">PoP <span data-bind="text: ProbabilityofPrecipitation()[3]"><?php print $forecast->getProbabilityOfPrecipitation(3)?></span>%</td>
											<td style="width: 11%;">PoP <span data-bind="text: ProbabilityofPrecipitation()[4]"><?php print $forecast->getProbabilityOfPrecipitation(4)?></span>%</td>
											<td style="width: 11%;">PoP <span data-bind="text: ProbabilityofPrecipitation()[5]"><?php print $forecast->getProbabilityOfPrecipitation(5)?></span>%</td>
											<td style="width: 11%;">PoP <span data-bind="text: ProbabilityofPrecipitation()[6]"><?php print $forecast->getProbabilityOfPrecipitation(6)?></span>%</td>
											<td style="width: 11%;">PoP <span data-bind="text: ProbabilityofPrecipitation()[7]"><?php print $forecast->getProbabilityOfPrecipitation(7)?></span>%</td>
											<td style="width: 11%;">PoP <span data-bind="text: ProbabilityofPrecipitation()[8]"><?php print $forecast->getProbabilityOfPrecipitation(8)?></span>%</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div> -->
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">
									<a href="<?php print $forecast->getCredit()?>">NWS Forecast Detail</a>
								</h2>
								<p class="meta">
									Last Updated: <span data-bind="text: new Date(Date.parse(ForecastCreationDate())).format(longDateTimeFormat)">
										<?php print date($cfg->getDateTimeFormat(), $forecast->getCreationDate())?></span>
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
								
								<div class="entry">
								<table class="fullTable">
								<?php 
//								print '<thead><tr class="table-top"><td></td><td><b>PERIOD</b></td><td><b>DESCRIPTION</b></td></tr></thead>';
								print '<thead><tr class="table-top"><td><b>Period</b></td><td><b>Description</b></td></tr></thead>';
								$i = 0;
								foreach ($entries as $entry)
								{
									$dark = '<tr class="column-dark" style="text-align: left; vertical-align: top;">';
									$light = '<tr class="column-light" style="text-align: left; vertical-align: top;">';
									
									if ($entry->getMinMaxType() == "Hi") print $dark; else print $light;
//									print '<td><img src="' . $entry->getIconURL() . '" width="55" height="58" alt="Forecast icon" />' . '</td>';
									print '<td><span data-bind="html: (ForecastEntries()[' . $i . '].timePeriodName())">' .  ($entry->getTimePeriodName()) . '</span></td>';
									print '<td style="width: 80%; border-collapse: collapse;"><span data-bind="text: ForecastEntries()[' . $i . '].wordedForecast">' . $entry->getWordedForecast() . '</span></td>';
									print '</tr>';
									$i++;
								}
								?>
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

<?php
require_once 'config/config.php';
require_once "classes/weatherData.php";
require_once "classes/weatherStatistics.php";
require_once "classes/weatherConversions.php";

$cfg = config::getInstance();
date_default_timezone_set($cfg->getTimeZone());

if (!isset($data)) 
{
	$data = new weatherData();
}

if (!isset($stats)) 
{
	$stats = new weatherStatistics();
}

?>
	<div id="header">
		<div id="logo">
			<h1>Clopper&#39;s Mill East Weather<br /><span class="small">Germantown, MD 20874</span></h1>
			<div class="floatright">
				<h2>Current Conditions</h2>
				<span class="meta">Last Updated: <span data-bind="fadeInText: new Date(Date.parse(DateTime())).format(shortDateTimeFormat)"><?php print date($cfg->getShortDateTimeFormat(), $data->getDateTime())?></span></span>
				<ul>
					<li>Temp: <span data-bind="fadeInText: formatNumberDP(OutdoorTemperature())"><?php print formatNumberDP($data->getOutdoorTemperature())?></span>&deg; F</li>
					<li>Humidity: <span data-bind="fadeInText: formatNumberDP(OutdoorHumidity())"><?php print formatNumberDP($data->getOutdoorHumidity())?></span>%</li>
					<li>Pressure: <span data-bind="fadeInText: formatNumberDP(Pressure())"><?php print formatNumberDP($data->getPressure())?></span> in.</li>
					<li>Wind: <span data-bind="fadeInText: formatNumberDP(AvgWindSpeed())"><?php print formatNumberDP($data->getAvgWindSpeed())?></span> mph <span data-bind="fadeInText: getWindDirStr(AvgWindDir())"><?php print getWindDirStr($data->getAvgWindDir())?></span></li>
					<li>Rain: <span data-bind="fadeInText: formatNumberDP(RainCounts.DayCount())"><?php print formatNumberDP($stats->getRainCounts()->getDayCount())?></span> in.</li>
				</ul>
			</div>
			<span class="floatright" id="loadericon"></span>
		</div>
	</div>
		
<!-- end #header -->
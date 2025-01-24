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

$cfg = config::getInstance();
date_default_timezone_set($cfg->getTimeZone());

$data = new weatherData();
$stats = new weatherStatistics();
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
		$(document).everyTime(dataRefreshRate,retrieveWeatherData);
	
		//get WX stats every minute
		$(document).everyTime(statsRefreshRate, retrieveWeatherStats);
	
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

<div id="page-bgbtm">
	<div id="content">
		<div class="post">
			<div class="post-bgtop">
				<div class="post-bgbtm">
					<h2 class="title">Site Map</h2>
					<p class="meta"></p>
					<div class="entry">
						<h3>Weather Conditions/Forecast</h3>
						<ul>
							<li><a href="about.php">About This Site</a></li>
							<li><a href="advisories.php">Weather advisories</a></li>
							<li><a href="almanac.php">Almanac</a></li>
							<li><a href="blog">Blog</a></li>
							<li><a href="forecast.php">Weather forecast</a></li>
							<li><a href="graphs.php">Station graphs</a></li>
							<li><a href="history.php">Station history</a></li>
							<li><a href="index.php">Landing page</a></li>
							<li><a href="links.php">Links</a></li>
							<li><a href="local.php">Local information</a></li>
							<li><a href="radar.php">Weather radar</a></li>
							<li><a href="satellite.php">Satellite images</a></li>
							<li><a href="software.php">This station software</a></li>
							<li><a href="stats.php">Station statistics</a></li>
							<li><a href="travel.php">Travel information</a></li>
						</ul>
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
</div>
<!-- end #page -->
		
<?php
include_once "pagefooter.php";
?>	
</div>
</body>
</html>

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
								<h2 class="title"><a href="http://www.findu.com/cgi-bin/wxpage.cgi?call=kb3hha-13&amp;last=24">24 Hour Graphs</a></h2>
								<p class="meta">
								</p>
								<div class="entry">
	<table class="fullTable" >
		<tr class="table-top">
			<td>Temperature/Dew Point</td>
			<td>Barometer (millibars)</td>
		</tr>
		<tr>
			<td><img src="http://www.findu.com/cgi-bin/temp.cgi?call=kb3hha-13&amp;last=24&amp;xsize=300&amp;ysize=200&amp;units=english" alt="Temperature Graph" /></td>
			<td><img src="http://www.findu.com/cgi-bin/baro.cgi?call=kb3hha-13&amp;last=24&amp;xsize=300&amp;ysize=200&amp;units=english" alt="Pressure Graph" /></td>
		</tr>
		<tr class="table-top">
			<td>Rain (inches)</td>
			<td>Wind (MPH)</td>
		</tr>
		<tr>
			<td><img src="http://www.findu.com/cgi-bin/rain.cgi?call=kb3hha-13&amp;last=24&amp;xsize=300&amp;ysize=200&amp;units=english" alt="Rain Graph" /></td>
			<td><img src="http://www.findu.com/cgi-bin/wind.cgi?call=kb3hha-13&amp;last=24&amp;xsize=300&amp;ysize=200&amp;units=english" alt="Wind Graph" /></td>
		</tr>
	</table>
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">
									<a href="http://www.findu.com/cgi-bin/wxpage.cgi?call=kb3hha-13&amp;last=48">48 Hour Graphs</a>
								</h2>
								<p class="meta">
								</p>
								<div class="entry">
	<table class="fullTable" >
		<tr class="table-top">
			<td>Temperature/Dew Point</td>
			<td>Barometer (millibars)</td>
		</tr>
		<tr>
			<td><img src="http://www.findu.com/cgi-bin/temp.cgi?call=kb3hha-13&amp;last=48&amp;xsize=300&amp;ysize=200&amp;units=english" alt="Temperature Graph" /></td>
			<td><img src="http://www.findu.com/cgi-bin/baro.cgi?call=kb3hha-13&amp;last=48&amp;xsize=300&amp;ysize=200&amp;units=english" alt="Pressure Graph" /></td>
		</tr>
		<tr class="table-top">
			<td>Rain (inches)</td>
			<td>Wind (MPH)</td>
		</tr>
		<tr>
			<td><img src="http://www.findu.com/cgi-bin/rain.cgi?call=kb3hha-13&amp;last=48&amp;xsize=300&amp;ysize=200&amp;units=english" alt="Rain Graph" /></td>
			<td><img src="http://www.findu.com/cgi-bin/wind.cgi?call=kb3hha-13&amp;last=48&amp;xsize=300&amp;ysize=200&amp;units=english" alt="Wind Graph" /></td>
		</tr>
	</table>
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">
									<a href="http://www.findu.com/cgi-bin/wxpage.cgi?call=kb3hha-13&amp;last=72">72 Hour Graphs</a>
								</h2>
								<p class="meta">
								</p>
								<div class="entry">
	<table class="fullTable" >
		<tr class="table-top">
			<td>Temperature/Dew Point</td>
			<td>Barometer (millibars)</td>
		</tr>
		<tr>
			<td><img src="http://www.findu.com/cgi-bin/temp.cgi?call=kb3hha-13&amp;last=72&amp;xsize=300&amp;ysize=200&amp;units=english" alt="Temperature Graph" /></td>
			<td><img src="http://www.findu.com/cgi-bin/baro.cgi?call=kb3hha-13&amp;last=72&amp;xsize=300&amp;ysize=200&amp;units=english" alt="Pressure Graph" /></td>
		</tr>
		<tr class="table-top">
			<td>Rain (inches)</td>
			<td>Wind (MPH)</td>
		</tr>
		<tr>
			<td><img src="http://www.findu.com/cgi-bin/rain.cgi?call=kb3hha-13&amp;last=72&amp;xsize=300&amp;ysize=200&amp;units=english" alt="Rain Graph" /></td>
			<td><img src="http://www.findu.com/cgi-bin/wind.cgi?call=kb3hha-13&amp;last=72&amp;xsize=300&amp;ysize=200&amp;units=english" alt="Wind Graph" /></td>
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

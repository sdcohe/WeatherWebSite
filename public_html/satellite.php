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
					<h2>All images on this page courtesy of NOAA and the National Hurricane Center</h2> 
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">GOES Atlantic Wide Visible</h2>
								<p class="meta"></p>
								<div class="entry">
									<a target="_blank" href="https://cdn.star.nesdis.noaa.gov/GOES16/ABI/SECTOR/taw/GEOCOLOR/1800x1080.jpg">
										<img class="sat-image" src="https://cdn.star.nesdis.noaa.gov/GOES16/ABI/SECTOR/taw/GEOCOLOR/1800x1080.jpg" width="450" height="270" />
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">GOES US Atlantic Coast Visible</h2>
								<p class="meta"></p>
								<div class="entry">
									<a target="_blank" href="https://cdn.star.nesdis.noaa.gov/GOES16/ABI/SECTOR/eus/GEOCOLOR/1000x1000.jpg">
										<img class="sat-image" src="https://cdn.star.nesdis.noaa.gov/GOES16/ABI/SECTOR/eus/GEOCOLOR/1000x1000.jpg" width="450" height="450" /> 
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">GOES Carribean Visible</h2>
								<p class="meta"></p>
								<div class="entry">
									<a target="_blank" href="https://cdn.star.nesdis.noaa.gov/GOES16/ABI/SECTOR/car/GEOCOLOR/1000x1000.jpg">
										<img class="sat-image" src="https://cdn.star.nesdis.noaa.gov/GOES16/ABI/SECTOR/car/GEOCOLOR/1000x1000.jpg" width="250" height="250" /> 
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">5 Day Tropical Weather Outlook</h2>
								<p class="meta"></p>
								<div class="entry">
									<img class="sat-image" src="https://www.nhc.noaa.gov/xgtwo/two_atl_5d0.png" alt="Atlantic tropical 5 day outlook" /> 
									<img class="sat-image" src="https://www.nhc.noaa.gov/xgtwo/two_pac_5d0.png" alt="Pacific tropical 5 day outlook" /> 
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

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
<html>

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

?>

<head>
<meta name="keywords" content="" />
<meta name="keywords" content="germantown,Clopper&#39;s,Mill,Clopper&#39;s Mill,weather" />
<meta name="description" content="Personal weather station located in Germantown, MD 20874." />

<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<title>Clopper&#39;s Mill East Weather</title>

<link href="css/style.css" rel="stylesheet" type="text/css" media="screen" />

</head>

<body>

<script type="text/javascript">

$(document).ready(function(){

	initWeatherDataModel();
	
	// get WX data every 15 secs
	$(document).everyTime(15000,retrieveWeatherData);

	//get WX stats every minute
	$(document).everyTime(60000, retrieveWeatherStats);

	//get update web cam image every 30secs
	$("#webcamThumbnail").everyTime(30000, updateWebcam);

	// get weather forecast every 10 minutes
	$(document).everyTime(600000, retrieveNWSForecast);
	
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
								<h1 class="title">
									<a href="#">Put your title here</a>
								</h1>
								<p class="meta">
									Put some meta data here
								</p>
								<div class="entry">
									Put your post here
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h1 class="title">
									<a href="#">Put your title here</a>
								</h1>
								<p class="meta">
									Put some meta data here
								</p>
								<div class="entry">
									Put your post here
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

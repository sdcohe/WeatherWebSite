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

<script type="text/javascript" src="config/config.js.php"></script>
<script type="text/javascript" src="js/jQuery.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="js/jQueryTimer.js"></script>
<script type="text/javascript" src="js/weatherconversions.js"></script>
<!--  <script type="text/javascript" src="/min//?g=weatherjs"></script>  -->
<script type='text/javascript' src='js/date.js'></script>
<script type='text/javascript' src='js/knockout.js'></script>
<script type='text/javascript' src='js/knockoutmapping.js'></script>
<script type='text/javascript' src='js/weathermodel.js'></script>

<title>Clopper&#39;s Mill East Weather</title>

<link href="css/style.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" href="/favicon.ico" type="image/x-icon">

</head>

<body>
<script type="text/javascript">

$(document).ready(function(){
	
	initWeatherDataModel();
	
	// get WX data every 15 secs
	$(document).everyTime(dataRefreshRate, retrieveWeatherData);

	//get WX stats every minute
	$(document).everyTime(statsRefreshRate, retrieveWeatherStats);

	//get update web cam image every 30 secs
	$("#webcamThumbnail").everyTime(webcamRefreshRate, updateWebcam);
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
								<h1 class="title">You May Have Broken the Internet</h1>
								<p class="meta"></p>
								<div class="errorentry">
								<h3>This probably isn't what you were expecting.</h3>  
								<div class="picfc"><img src="images/darth_vader_nooo_7675.jpg" alt="Darth Vader Nooooo" /> </div>
								<h3>We're sorry, the page you're looking for was not found</h3>
								<h3>If you'd like to make a call, please hang up and dial again</h3>
								<br />Subtle hint: You could try clicking on one of the links in the header above or on the sidebar to the left.
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

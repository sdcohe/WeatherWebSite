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

<?php
  // ***************
  // For comments only
  // ***************
  // why space weather/impacts
  // Travel forecast
  // Aurora forecast
  // Radio propagation forecast
    // sunspot numbers
    // solar cycle
    // 10.7 cm radio flux 
php?>

					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">Current Solar Image</h2>
								<p class="meta">From NASA.gov</p>
								<div class="entry">
									<img src="https://sdo.gsfc.nasa.gov/assets/img/latest/latest_170_0304.jpg" alt="Current Sun Image" />
								</div>
							</div>
						</div>
					</div>
					
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">Solar Cycle</h2>
								<p class="meta">From NOAA.gov</p>
								<div class="entry">
									<table width="99%" cellpadding="0" border="0" cellspacing="0">
    	                                <tr>
        		                            <td align="center">
                    		                    <a href="https://www.swpc.noaa.gov/phenomena/sunspotssolar-cycle" target="_blank">Sun Spot Number Progression</a><br />
                            		             <a href="https://services.swpc.noaa.gov/images/solar-cycle-sunspot-number.gif" target="_blank">
                                    		     <img src="https://services.swpc.noaa.gov/images/solar-cycle-sunspot-number.gif"
                                                 style="border:none;" width="320" height="240"
                                                 alt="Graph showing Sun Spot Number Progression"
                                                 title="Graph showing Sun Spot Number Progression" /></a><br />
                                                Solar Cycle Sun Spot Number Progression.
    		                                    </td>

                                                <td align="center">
                                                    <a href="https://www.swpc.noaa.gov/phenomena/f107-cm-radio-emissions" target="_blank">F10.7cm Radio Flux Progression</a><br />
                                                     <a href="https://services.swpc.noaa.gov/images/solar-cycle-10-cm-radio-flux.gif" target="_blank">
                                                     <img src="https://services.swpc.noaa.gov/images/solar-cycle-10-cm-radio-flux.gif"
                                                     style="border:none;" width="320" height="240"
                                                     alt="Graph showing F10.7cm Radio Flux Progression"
                                                     title="Graph showing F10.7cm Radio Flux Progression" /></a><br />
                                                    F10.7cm Radio Flux Progression.
                                                </td>
                            
				                    	</tr><tr>
                                            <td align="center">
                                                <a href="https://www.swpc.noaa.gov/communities/space-weather-enthusiasts" target="_blank">Ap Progression</a><br />
                                                 <a href="https://services.swpc.noaa.gov/images/solar-cycle-planetary-a-index.gif" target="_blank">
                                                 <img src="https://services.swpc.noaa.gov/images/solar-cycle-planetary-a-index.gif"
                                                 style="border:none;" width="320" height="240"
                                                 alt="Graph showing Ap Progression"
                                                 title="Graph showing Ap Progression" /></a><br />
                                                Solar Cycle Ap Progression.
                                            </td>
                    
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
</div>
<!-- end #page -->
		
<?php
include_once "pagefooter.php";
?>	
</div>
</body>
</html>

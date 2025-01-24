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
require_once "classes/weatherConversions.php";

$cfg = config::getInstance();
date_default_timezone_set($cfg->getTimeZone());

require_once "classes/weatherData.php";
require_once "classes/weatherStatistics.php";
require_once "classes/govtoperatingstatus.php";
require_once "classes/traffic.php";

$data = new weatherData();
$stats = new weatherStatistics();
$govStatus = new GovernmentOperatingStatus();
$traffic = new Traffic();

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
<!-- 					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title"><?php print $govStatus->getStatusTitle()?>: <?php print $govStatus->getStatus()?></h2>
								<p class="meta">
									As of <?php print date($cfg->getDateTimeFormat(), $govStatus->getPublishDate())?>
								</p>
								<div class="entry">
									<?php print $govStatus->getStatusMessage()?>
								</div>
							</div>
						</div>
					</div>
 -->					
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">Traffic Cams</h2>
								<p class="meta">
								</p>
								<div class="entry">
		  	  <table class="fullTable">
		  	   <tr class="center">
		  	   	<td>
			  		<h3>Maryland DOT Traffic Cam</h3> 
		  		</td>
			  	<td>
				  <h3>Montgomery County DPWT Traffic Cam</h3> 
			  	</td>
		  	   </tr>
		  	   <tr class="center">
		  	    <td>
		  	     <a href="http://www.chart.state.md.us/TravInfo/trafficCams.asp" >
	              <img src="http://www.50states.com/flag/image/nunst032.gif" width="160" height="120" style="border:1px; border-color:#000000; border-style:solid;" alt="thumbnail of Moving Cameras" />
	            </a>
		  	    </td>
		  	    <td>
		  	    <a href="http://atms.montgomerycountymd.gov/jpgcap/TL/">
	              <img src="images/MCFlag.png" width="160" height="120" style="border:1px; border-color:#000000; border-style:solid;" alt="thumbnail of Moving Cameras" />
	            </a>
	            </td>
		  	    </tr>
		  	    <tr class="center">
		  	     <td>
		            <a href="http://www.chart.state.md.us/TravInfo/trafficCams.asp">Click here for live web cam</a>
		  	     </td>
	           	<td>
	            	<a href="http://atms.montgomerycountymd.gov/jpgcap/TL/">Click here for live web cam</a>
	        	</td>
		  	    </tr>
	          </table>
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">Traffic Info</h2>
								<h3><a href="http://www.chart.state.md.us/rss/ProduceRSS.aspx?Type=TravelSpeedsWLoc&filter=ALL">Click here for the Maryland Highway Information traffic speeds page</a></h3>
<!-- 
								<p class="meta">
									As of <?php print date($cfg->getDateTimeFormat(), $traffic->getPublishDate())?>
								</p>
								<div class="entry">
									<?php
									if (count($traffic->getTrafficItems()) == 0)
									{
										print "<p>No Traffic</p>\n";
									} 
									else 
									{
										foreach($traffic->getTrafficItems() as $item)
										{
											if ($item->jamfactor > 0)
											{
												print '<p><b><span >' . '<a href="' . $item->link . '">' . $item->title . "</a></span></b><br />" . $item->description . "</p>\n";
											}
										}
									}
									?>
								</div>
 -->								
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

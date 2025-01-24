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

require_once "classes/weatherHistory.php";
$history = new WeatherHistory();

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
								<h2 class="title">Weather History</h2>
								<p class="meta">
									<a href="csvhistory.php">Download as CSV file</a>
								</p>
								<div class="entry">
    <table class="fullTable">
    
    <tr class="table-top">
    <td>DATE</td>
    <td>TIME</td>
    <td>TEMP</td>
    <td>WIND SPD</td>
    <td>WIND GUST</td>
    <td>HUMIDITY</td>
    <td>PRESSURE</td>
    <td>RAIN</td>
    <td>SOLAR</td>
    </tr>
    
    <?php
    $idx = 0;
    $max = $history->getMaxIndex();
    $numEntries = count($history->getEntries()) - 1;
    
    foreach($history->getEntries() as $entry)
    {
    	if ($idx % 2 == 1)
    	{
    		print '<tr class="column-dark">';
    	}
    	else
    	{
    		print '<tr class="column-light">';
    	}

    	$dt = strtotime($entry->DATETIME);
    	print '<td>' . date('m/d/y', $dt) . '</td>';
    	print '<td>' . date('H:i', $dt) . '</td>';
    	print '<td>' . formatNumberDP((float)$entry->OUTDOORTEMPERATURE) . ' &deg;F</td>';
		print '<td>' . formatNumberDP((float)$entry->AVERAGEWINDSPEED) . ' mph ' . getWindDirStr($entry->AVERAGEWINDDIRECTION) . '</td>';
		print '<td>' . formatNumberDP((float)$entry->WINDSPEED) . ' mph ' . getWindDirStr($entry->WINDDIRECTION) . '</td>';
		print '<td>' . formatNumberDP((float)$entry->OUTDOORHUMIDITY) . '%</td>';
		print '<td>' . formatNumberDP((float)$entry->PRESSURE) . ' in.</td>';
		print '<td>' . formatNumberDP((float)$history->getRainData($numEntries - $idx)) . ' in. </td>';
		print '<td>' . formatNumberDP((float)$entry->SOLAR) . '</td>';

		print '</tr>';
		
		$idx++;
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

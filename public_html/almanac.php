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
require_once "classes/astro.php";

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
<!--  <script src="/min//?g=weatherjs"></script>  -->
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
								<h2 class="title">Sun and Moon Phases</h2>
								<p class="meta">All times are EST/EDT as appropriate
								</p>
								<div class="entry">
  <table class="fullTable" >
  <tr>
    <td class="center"><h3>Sun</h3></td>
    <td class="center"><h3>Moon</h3></td>
  </tr>
  <tr>
    <td class="center">Image from NASA</td>
    <td class="center">Image from Naval Observatory</td>
  </tr>
  <tr>
    <td class="center"><a href="http://umbra.nascom.nasa.gov/" ><img src="http://umbra.nascom.nasa.gov/images/latest_aia_304_tn.gif" alt="Sun" title="Current image of the sun" /></a></td>
    <td class="center"><img src="http://tycho.usno.navy.mil/cgi-bin/phase.gif" width="128" height="128" alt="Age: <?php print $moonage?>" title="Age: <?php print $moonage?>" /></td>
  </tr>
  <tr>
    <td class="center">Sunrise: <?php print $sunrise?><br />Sunset: <?php print $sunset?><br />Civil Twilight (begin): <?php print $civiltwilightbegin?><br />
		Civil Twilight (end): <?php print $civiltwilightend?><br />Daylight: <?php print $hoursofpossibledaylight?></td>
    <td class="center">Moonrise: <?php print $moonrise?><br />Moonset: 
		<?php print $moonset?><br /><?php print $moonphaselit?><br /><?php print $moonillum?>% illuminated<br />Age: <?php print $moonage?><br /><!--  >Apogee: <?php print $moonapogee?><br />Perigee: <?php print $moonperigee?> --></td>
  </tr>
</table>
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">Lunar Events</h2>
								<p class="meta">
								</p>
								<div class="entry">
  <table class="fullTable">
    <tr>
      <td class="center">First Quarter Moon</td>
      <td class="center">Full Moon</td>
      <td class="center">Last Quarter Moon</td>
      <td class="center">New Moon</td>
    </tr>
    <tr>
      <td class="center">
      <img src="images/firstquartermoon.jpg" width="87" height="75" alt="First Quarter Moon" /></td>
      <td class="center">
      <img src="images/fullmoon.jpg" width="87" height="75" alt="Full Moon" /></td>
      <td class="center">
      <img src="images/lastquartermoon.jpg" width="87" height="75" alt="Last Quarter Moon" /></td>
      <td class="center">
      <img src="images/newmoon.jpg" width="87" height="75" alt="New Moon" /></td>
    </tr>
    <tr>
      <td class="center"><?php print $firstquarter?></td>
      <td class="center"><?php print $fullmoon?></td>
      <td class="center"><?php print $lastquarter?></td>
      <td class="center"><?php print $nextnewmoon?></td>
    </tr>
  </table>
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">Solar Events</h2>
								<p class="meta">
								</p>
								<div class="entry">
  <table class="fullTable">
    <tr>
      <td class="center">Vernal Equinox<br/><small>Start of Spring</small></td>
      <td class="center">Summer Solstice<br/><small>Start of Summer</small></td>
      <td class="center">Autumnal Equinox<br/><small>Start of Fall</small></td>
      <td class="center">Winter Solstice<br/><small>Start of Winter</small></td>
    </tr>
    <tr>
      <td class="center">
      <img src="images/earth-spring.jpg" width="87" height="75" alt="Vernal Equinox" /></td>
      <td class="center">
      <img src="images/earth-summer.jpg" width="87" height="75" alt="Summer Solstice" /></td>
      <td class="center">
      <img src="images/earth-fall.jpg" width="87" height="75" alt="Autumnal Equinox" /></td>
      <td class="center">
      <img src="images/earth-winter.jpg" width="87" height="75" alt="Winter Solstice" /></td>
    </tr>
    <tr>
      <td class="center"><?php print "$marchequinox"?></td>
      <td class="center"><?php print "$junesolstice"?></td>
      <td class="center"><?php print "$sepequinox"?></td>
      <td class="center"><?php print "$decsolstice"?></td>
    </tr>
  </table>
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">Astronomical Dates</h2>
								<p class="meta">
								</p>
								<div class="entry">
  <table>
<!--  <tr> -->
<!--       <td >Chinese New Year</td> -->
<!--       <td ><?php print $chinesenewyear?></td>  -->
<!--   </tr> -->
    <tr>
      <td >Easter</td>
      <td ><?php print $easterdate?></td>
    </tr>
    <tr>
      <td >Passover</td>
      <td ><?php print $pesachdate?></td>
    </tr>
  </table>
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title"><a href="http://cleardarksky.com/c/MCAOobMDkey.html">Clear Sky Calendar</a></h2>
								<p class="meta">
								</p>
								<div class="entry">
									<a href="http://cleardarksky.com/c/MCAOobMDkey.html">
									<img src="http://cleardarksky.com/csk/getcsk.php?id=MCAOobMD" alt="Clear Sky Map" /></a>
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

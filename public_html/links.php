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
					<h2 class="title">About The Weather</h2>
					<p class="meta"></p>
					<div class="entry">
						<h3>Weather Conditions/Forecast</h3>
						<ul>
							<li><a href="http://www.nws.noaa.gov/">NOAA's National Weather Service</a></li>
							<li><a href="http://www.weather.com/">The Weather Channel</a></li>
							<li><a href="http://www.accuweather.com/">AccuWeather.com Weather</a></li>
							<li><a title="Weather Underground" href="http://www.wunderground.com/">Weather Underground</a></li>
							<li><a href="http://www.wxqa.com/" title="CWOP">Citizen Weather Observer Program</a></li>
							<li><a href="http://www.anythingweather.com/">AnythingWeather.com</a></li>
							<li><a href="http://www.weatherforyou.com/">Weather For You</a></li>
							<li><a href="http://www.awekas.at/en/index.php">AWEKAS Automatic Weather Map System</a></li>
							<li><a href="http://weather.weatherbug.com/">Weather and forecast information on WeatherBug.com</a></li>
						</ul>
			
						<h3>Weather Education</h3> 
        				<ul>
          					<li><a href="http://glossary.ametsoc.org/wiki/Main_Page" title="Meteorology Terms">Glossary of Meteorology</a></li>
          					<li><a href="https://www.ofcm.gov/publications/fmh/allfmh2.htm" title="Handbook for US Meterology">Federal Meteorological Handbook No. 1</a></li>
          					<li><a href="https://scool.larc.nasa.gov/tutorial/clouds/newusers-CT.html" title="Cool audio/visual guide">S'COOL Cloud Types Audio/Visual Tutorial</a></li>
          					<li><a href="http://eo.ucar.edu/webweather/" title="Web Weather for Kids">Web Weather for Kids</a></li>
          					<li><a href="http://www.education.noaa.gov" title="NOAA Education Resources">NOAA Education Resources</a></li>
          					<li><a href="https://www.weather.gov/owlie/science_kt" title="NWS Weather Website for Kids">NWS Weather Website for Kids</a></li>
<!--           					<li><a href="http://www.usatoday.com/weather/resources/basics/wworks0.htm" title="Weather Basics">USA Today - Weather Basics</a></li>   -->          					
<!-- 							<li><a href="http://www.abc15.com/weather/index.asp?doc=calculators/index.html">Phoenix ABC15 Weather Calculators</a></li>   -->
        				</ul>
<!-- 
          				<div class="indented">
	          				<h4>These links are to pages located on commercial HVAC Web sites.</h4>
	          				While the sites are commercial in nature, the specific pages contain good weather related information.
          				</div>
          				<ul> 
	          				<li><a href="http://tradewindsheating.com/info/tropical-air-winds-and-rain-all-about-hurricanes" title="All About Hurricanes">Tropical Air, Winds, and Rain All About Hurricanes</a>
								<p class="attribution">Courtesy of Emma Kendall, Mrs. Walker, and her students at Elmgrove Community Center</p></li>
	          				<li><a href="http://midatlanticairconditioning.com/info/article/heating-and-cooling-air-causes-of-thunder"
								title="Heating and Cooling Air, Causes of Thunder"> Heating and Cooling Air, Causes of Thunder</a>
								<p class="attribution">Courtesy of Dana (High School volunteer) and Emma Garrison at the Charlotte Library</p></li>
						</ul>
 -->
						<h3>Weather Discussion/Forums</h3>
					        <ul>
					          	<li><a href="http://www.weather-watch.com/smf/" title="Weather Forums">Weather Watch Forums</a></li>
								<li><a href="http://www.easternuswx.com/">Eastern US Weather Forums</a></li>
								<li><a href="https://talkweather.com/index.php">Talkweather Forums</a></li>
<!--  								<li><a href="http://www.theweathervane.info/forums/">The Weather Vane Forums</a></li>  -->
<!--  								<li><a href="http://forums.accuweather.com/">AccuWeather.com (WeatherMatrix) Forums</a></li>  -->
					        </ul>
			
					</div>
				</div>
			</div>
		</div>
		
		<div class="post">
			<div class="post-bgtop">
				<div class="post-bgbtm">
					<h2 class="title">Personal Weather Stations</h2>
					<p class="meta"></p>
					<div class="entry">
						<h3>How To/Guides</h3>
						<ul>
				        	<li><a href="https://weather.gladstonefamily.net/CWOP_Guide.pdf" title="CWOP Station Setup Guide">CWOP Station Setup Guide</a> (PDF)</li>
				       		<li><a href="https://www.ambientweather.com/eaofin.html" title="Install Guide">Ambient Weather: Station Reviews and Install Guide</a></li>
							<li><a href="http://www.weathertoys.net/weathertoys/main.html"> Weather Toys - Build your own Weather Station</a></li>
							<li><a href="http://www.maxim-ic.com/products/ibutton/software/sdk/sdks.cfm">Software Development Kits - Maxim</a></li>
						</ul>

						<h3>Data Upload/Sharing</h3> 
				        <ul>
							<li><a href="http://www.wunderground.com/weatherstation/index.asp">Weather Underground Signup Page</a></li>
							<li><a href="http://www.wxqa.com/SIGN-UP.html">CWOP Signup Page</a></li>
<!-- 							<li><a href="http://www.anythingweather.com/contactjoinnetwork.aspx" title="AnythingWeather Signup Page">AnythingWeather Signup Page</a></li> -->
							<li><a href="https://www.pwsweather.com/register.php" title="WeatherForYou Signup">WeatherForYou Signup Page</a></li>
							<li><a href="https://www.awekas.at/wp/join/?lang=en" title="AWEKAS Signup">AWEKAS Signup Page</a></li>
<!-- 							<li><a href="http://backyard.weatherbug.com/group/developers" title="WeatherBug">WeatherBug</a></li> -->
				        </ul>
        
						<h3>Hardware</h3>
						<ul>
							<li><a href="http://www.maxim-ic.com/products/ibutton/">Dallas/Maxim iButton</a></li>
<!-- 							<li><a href="http://www.aagelectronica.com/">AAG Electronica 1-Wire Weather Instruments</a></li> -->
							<li><a href="https://hobbyboards.com/">Hobby Boards Complete 1wire Solutions</a></li>
							<li><a href="http://www.lacrossetechnology.com/" title="La Crosse Technology">La Crosse Technology</a></li>
				          	<li><a href="http://www.davisnet.com/weather/index.asp" title="Davis Instruments">Davis Instruments</a></li>
				          	<li><a href="https://store.oregonscientific.com/us" title="Oregon Scientific">Oregon Scientific</a></li>
				       		<li><a href="http://www.peetbros.com/shop/">Peet Bros. Company</a></li>
<!-- 							<li><a href="http://www.honeywellweatherstations.com/">Honeywell Weather Stations</a></li> -->
						</ul>
						<h3>Software</h3>
					    <ul>
				        	<li><a href="http://oww.sourceforge.net/">One Wire Weather</a></li>
				          	<li><a href="http://www.weather-display.com" title="Weather Station Software">Weather Display</a></li>
				          	<li><a href="http://www.ambientweather.com/software.html">Virtual Weather Station</a></li>
				          	<li><a href="http://www.weatherview32.com/">Weather View 32</a></li>
				      		<li><a href="http://cirrus.sprl.umich.edu/wxnet/software.php">University of Michigan Weather Software Library</a></li>
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

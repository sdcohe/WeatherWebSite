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

	<div id="page-bgbtm">
		<div id="content">
			<div class="post">
				<div class="post-bgtop">
					<div class="post-bgbtm">
						<h2 class="title">Station Software</h2>
							<p class="meta"></p>
							<div class="entry">
								<p>I have chosen to share my source code for this software in the hope that others find it useful or educational.
								It is a work in progress and always evolving.  It does not have a polished user interface or configuration front end.  
								There are several areas that could use improvement.  With that said, I have had this software in continual use running
								my weather station since 2007 and it has been very stable running under Linux on several different hardware platforms.
								Use it at your own risk, but please enjoy.  Also, send me an email of you try out the software.  I will attempt to provide some support
								as time permits.</p>
								
								<p>This software has several dependencies to other open source packages.  I have provided a list and the links
								to that software below.  I plan to eventually provide a configuration/administrator's guide if there is any demand for it, as
								the install and configuration is not automated at this point in time.  I will work towards creating a complete install package 
								that installs the daemon, along with the scripts that start the daemon, clean up logs, etc.  Drop me a line if you need this 
								information before I can make it available</p>
								
								<p>If you do choose to download this software, I ask that you do not use it to create a commercial product without my permission.
								I also request that you reference my original work on any software that you might derive from it.  Finally, if you make 
								any improvements/modifications/bug fixes, I request that you send them back to me so that I may incorporate them into the original.</p>
							</div> <!-- end #entry -->
						</div> <!-- end #post_bgbtm -->
					</div> <!-- end #post_bgtop -->
				</div> <!-- end #post -->

				<div class="post">
					<div class="post-bgtop">
						<div class="post-bgbtm">
							<h2 class="title">Weather Station Daemon</h2>
							<p class="meta"></p>
							<div class="entry">
							
								<p>This is the source code for the weather station daemon.  It includes the hardware data acquisition and data publishing code for several services such as
								CWOP, WeatherUnderground, WeatherBug and WeatherForYou.  This code is written in Java and works with several 1-Wire hardware components including hubs, anenometers, 
								temperature, humidity, barometer, lightning, and rain gauges.  I have also included an init script to start/stop the service and a sample configuration file</p>
								
								<ul>
									<li><a href="code/oneWireWeather.zip">One wire weather source code (zip file)</a></li>
									<li><a href="code/weatherd">Init script (starts/stops weatherd service.  Install in /etc/init.d)</a></li>
									<li><a href="code/Sample-weatherConfig.xml">sample configuration file</a></li>
								</ul>
								
							</div> <!-- end #entry -->
						</div> <!-- end #post_bgbtm -->
					</div> <!-- end #post_bgtop -->
				</div> <!-- end #post -->
							
				<div class="post">
					<div class="post-bgtop">
						<div class="post-bgbtm">
							<h2 class="title">Supporting Jar Files</h2>
							<p class="meta"></p>
							<div class="entry">

								<p>These are the support jar files used by the weather station daemon.  They are all open source and should be downloaded from their
								home sites.  Links are provided below</p>

								<p>Note: The versions listed are the versions I am currently running.  Newer version should work but haven't been tested.</p>

								<ul>
									<li><a href="http://www.maximintegrated.com/products/ibutton/software/1wire/1wire_api.cfm">OneWireAPI.jar</a></li>
									<li><a href="http://rxtx.qbang.org/wiki/index.php/Download">RXTXcomm.jar</a></li>
	 								<li><a href="http://commons.apache.org/proper/commons-collections/">commons-collections-3.2.1.jar</a></li>  
									<li><a href="http://commons.apache.org/proper/commons-configuration/">commons-configuration-1.6.jar</a></li>  
									<li><a href="http://commons.apache.org/proper/commons-lang/">commons-lang-2.6.jar</a></li>
									<li><a href="http://commons.apache.org/proper/commons-logging/">commons-logging-1.1.1.jar</a></li>      
									<li><a href="http://jdbc.postgresql.org/download.html">postgresql-9.2-1000.jdbc4.jar</a></li>
								</ul>
							</div> <!-- end #entry -->
						</div> <!-- end #post_bgbtm -->
					</div> <!-- end #post_bgtop -->
				</div> <!-- end #post -->
			
				<div class="post">
					<div class="post-bgtop">
						<div class="post-bgbtm">
							<h2 class="title">Data Upload and Log Cleanup</h2>
							<p class="meta"></p>
							<div class="entry">
								<p>These scripts are used to clean up the log files and upload the weather data to a Web/FTP site.  The scripts are run using cron, so a 
								sample crontab file is included.  I have also included a simple program to retrieve the weather data from the weather daemon.  Communication
								with the daemon is through a TCP port using a very simple protocol.  Most of these scripts are very rudimentary and lack error handling.  There's
								a lot of room for improvement in them, but they work and can be used as a strating point for anybody who wants to improve them.</p>
								
								<p> Most of these scripts are specific to my installation.  You will need to customize these scripts for your specific installation.</p>
								
								<ul>
									<li><a href="code/logrotate-weather">logrotate script (in /etc/logrotate.d/weather)</a></li>
									<li>Weather Upload Scripts
										<ul>
											<li><a href="code/uploadWeatherData.sh">uploadWeatherData.sh</a></li>
											<li><a href="code/uploadWeatherHistory.sh">uploadWeatherHistory.sh</a></li>
											<li><a href="code/uploadWeatherStats.sh">uploadWeatherStats.sh</a></li>
										</ul></li>
									<li><a href="code/LogRetriever.java">Log Retriever Application</a></li>
									<li><a href="http://commons.apache.org/proper/commons-cli/">commons-cli-1.2.jar (to process command line arguments)</a></li>
									<li><a href="code/crontab">crontab to run weather upload scripts</a></li>
								</ul>
							</div> <!-- end #entry -->
						</div> <!-- end #post_bgbtm -->
					</div> <!-- end #post_bgtop -->
				</div> <!-- end #post -->
				
				<div class="post">
					<div class="post-bgtop">
						<div class="post-bgbtm">
							<h2 class="title">Web Site Files</h2>
							<p class="meta"></p>
							<div class="entry">
								<p>These are the Javascript and PHP files used to power the Web site.  I may also include a template
								Web site to be built on.  Much of this is personalized to my specific needs so it will require modification
								to suit your own specific use cases.</p>
								
								<p>Note: These are my first attempts at PHP and Javascript coding.  If you see some beginner mistakes now you 
								know why :-)</p>
								
								<ul>
									<li><a href="code/WeatherWebSite.zip">Weather Web Site Code (zip file)</a></li>
									<li><a href="http://code.google.com/p/minify/">Minify (optional component to reduce the size of Javascript code)</a></li>
								</ul>
								
							</div> <!-- end #entry -->
						</div> <!-- end #post_bgbtm -->
					</div> <!-- end #post_bgtop -->
				</div> <!-- end #post -->
				
								<div style="clear: both;">&nbsp;</div>
			</div> <!-- end #content -->
				
<?php
include_once "pagesidebar.php";
?>
			<div style="clear: both;">&nbsp;</div>
		</div> <!-- end #page-bgbtm -->
</div> <!-- end #page -->
		
<?php
include_once "pagefooter.php";
?>	
</div> <!-- end #wrapper -->
</body>
</html>

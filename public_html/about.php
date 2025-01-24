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

			<!--  		<div id="page-bgtop"> -->
			<div id="page-bgbtm">
				<div id="content">
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">About This Web Site
								</h2>
								<p class="meta">
								</p>
								<div class="entry">
<p>This Web site was created both as a hobby and as a public service to Germantown, Maryland.  I do this mainly for my own education and entertainment, but also in the hope that others may benefit from my efforts.</p>
<p><b>This weather station and Web site are privately owned, and are not the officially recognized system of record for the local weather conditions.</b></p>
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">Station Hardware
								</h2>
								<p class="meta">
								</p>
								<div class="entry">
	<div class="picfr">
	<img src="images/06250_1.jpg" alt="Picture of weather station" />
	</div>
	<p>This station consists of a Davis Vantage Vue ISS and console.  The data is collected approximately every 
	5 seconds and recorded once per minute in a database.  The weather data displayed on this Web site is currently updated once every 15 seconds.  The data is also 
	published to the Weather Underground every 10 seconds, to the Citizen's Weather Observer Program (CWOP) every 10 minutes, as well as to several other weather Web sites.</p>
	
    <p>The weather sensors have been situated in the most optimal positions available to achieve the highest accuracy possible.  However, <b>accuracy is not guaranteed</b>, 
    as compromises were made during installation, and <b>no important decisions should be based on the information obtained from this Web site</b>.</p>
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">Station Software
								</h2>
								<p class="meta">
								</p>
								<div class="entry">
	<div class="picfr">
		<img src="images/tux.gif" alt="Linux icon" />
		<img src="images/java.png" alt="Java icon" />
		<img src="images/php-icon.gif" alt="PHP Icon" />
	</div>

	<p>This site's data is collected using software that I developed.  The majority of the software runs on the Linux operating system.  This portion of the software 
    was developed using the Java programming language for portability between Linux and Windows as I develop on Windows but deploy to Linux.  There is 
    additional Windows client software that was developed using the C# programming language.  The Web site uses PHP scripts and AJAX to format and display the weather data.</p>

	<p>The software consists of the following components:</p>

	<ul>
      <li>The <b>hardware data acquisition component</b> that polls the Davis weather hardware and publishes this information over a 
		local area network (LAN) via TCP/IP.  It also publishes data to several online services, including <a href="http://www.wunderground.com/">Weather Underground</a> (station 
		ID KMDGERMA9) and the <a href="http://www.wxqa.com/">Citizen's Weather Observer Program</a> (station ID AS787).</li>
		
      <li>The <b>forecast retrieval component</b> gathers <a href="http://www.noaa.gov/">NOAA</a> weather data and forecasts on demand for 
      a specified local zone and county.  The forecast is retrieved on demand, but not more often than every five minutes.  This retrieved data is stored for display on the  Web site.</li>
      
      <li>The <b>data storage component</b> gathers the weather data from the hardware data acquisition component once per minute and stores it in a
      database for later analysis and display on the Web site.</li>
      
      <li>The <b>Web site builder component</b> creates this Web site using PHP scripts and the data supplied by all of the other components.  
		This Web site is hosted by Bluehost.com and consists of a number of PHP and Javascript scripts used to format and update these Web pages.  All of the pages are dynamic, where the weather 
		data and statistics, as well as the forecast and other supporting information updating in near real time. The data on the Web pages is refreshed using AJAX.</li> 

    
      <li>The <b>weather Web service</b> which runs under Apache Tomcat and provides real-time weather data on a LAN.</li>
      
      <li>The <b>Windows client software</b> for the weather Web service.</li>
    </ul>

	<p>These are the basic software components in place so far.  Planned enhancements/additions include providing weather data through the <a href="http://www.aprs.net/">Automatic Position Reporting System</a> (APRS) 
    via radio and a satellite receiver to publish satellite images from the low Earth orbit weather satellites.</p>
    
    <h3>This software is available for download <a href="software.php">at this link</a></h3>
      
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">Station Computer
								</h2>
								<p class="meta">
								</p>
								<div class="entry">
	<div class="picfr">
	<img src="images/2358-01.jpg" alt="Raspberry Pi computer" />
	</div>

	<p>The current station computer is a Raspberry Pi running off of an external hard drive.  It runs the Hardware data acquisition, and weather Web service, along 
	with various other open source software packages.  The local Web server is <a href="http://httpd.apache.org/">Apache</a> and the Web service is provided 
	by <a href="http://tomcat.apache.org/">Tomcat</a>.</p>
	
	<p>The hardware data acquisition component was originally targeted to run an a TINI microprocessor.  However I never implemented it on that platform
	because I ended up using an old discarded computer instead as a cost saving measure.  Since then I have been through a number of station computers.
	All of them were low budget solutions.  I also made a few attempts at using low power solutions to save some energy.  </p>
	
	Here is a list of the station computers I have run in reverse chronological order (most recent at the top):
	<ul>
		<li>Raspberry Pi running the Raspbian O/S with an external hard drive</li>
		<li>Discarded AMD Athlon workstation running Centos (retired for low power Raspberry Pi solutiuon)</li>
		<li>Guruplug running Debian Linux (hardware failure after one year of operation)</li>
		<li>Discarded Pentium 3 450 workstation running Fedora Core (retired for low power Guruplug solution)</li> 
	</ul>    
	
	<p>The Windows Web service client runs on several workstations connected to a LAN running Windows 7 and 8. This client software uses the .NET Framework.</p>
    
    <p>The WEB cam is a Dahua IPC-HFW1320S 3MP HD Mini PoE Bullet Security Camera.  It is positioned beneath the soffit of my house facing generally towards the West.</p>
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">Station Data
								</h2>
								<p class="meta">
								</p>
								<div class="entry">
		<p>The weather data from this station is published to several online sources.  These sources include:</p>
		<ul>
			<li><a href="http://www.wunderground.com/weatherstation/WXDailyHistory.asp?ID=KMDGERMA9">Weather Underground</a> station ID KMDGERMA9 (5 second real time updates)</li> 
<!-- 			<li><a href="http://weather.weatherbug.com/MD/Germantown-weather.html?zcode=z6286&amp;stat=p15087">Weather Bug</a> station ID P15087 (5 minute updates)</li> -->
			<li><a href="http://weather.gladstonefamily.net/site/AS787">Citizen's Weather Observer Program</a> station ID AS787 (10 minutes updates)</li>
			<li><a href="http://www.pwsweather.com/obs/AS787.html">PWS Weather/Weather For You</a> station ID AS787 (30 minute updates)</li>
			<li><a href="http://www.hamweather.net/">HAM Weather</a></li>
			<li><a href="http://www.anythingweather.com/current.aspx?id=31018">Anything Weather</a></li>
			<li><a href="http://www.awekas.at/en/instrument.php?id=8827&amp;tempeh=f">AWEKAS</a></li>
		</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">About This City
								</h2>
								<p class="meta">
									Adapted from <a href="http://en.wikipedia.org/wiki/Wikipedia">Wikipedia</a>
								</p>
								<div class="entry">

<div class="picfr">
<object id="map" type="text/html" data="http://maps.google.com/maps?f=ie=UTF8&amp;t=h&amp;vpsrc=0&amp;source=embed&amp;ll=39.151829,-77.268047&amp;spn=0.027789,0.033817&amp;z=15&amp;output=embed" style="width:286px;height:234px;">
</object>
</div>

<!--[if lte IE 7]><script>(function(){var o=document.
getElementById('map');var i=document.createElement('iframe');i.setAttribute
('src',o.getAttribute('data'));i.style.width=o.style.width;i.style.height=o.style.
height;o.parentNode.replaceChild(i,o);})();</script><![endif]-->

	<p><b>Germantown</b> is an unincorporated but urbanized <a href="http://en.wikipedia.org/wiki/Census-designated_place" title="Census-designated place">
    census-designated place</a> in <a href="http://en.wikipedia.org/wiki/Montgomery_County,_Maryland" title="Montgomery County, Maryland">
    Montgomery County</a>, <a href="http://en.wikipedia.org/wiki/Maryland" title="Maryland">Maryland</a> 
    in the <a href="http://en.wikipedia.org/wiki/USA" title="USA">USA</a>.  It is the sixth most populous CDP in <a href="http://en.wikipedia.org/wiki/Maryland" title="Maryland">Maryland</a>. 
    If it were to incorporate, it would be the second largest city in Maryland <a href="http://www.washingtonpost.com/wp-dyn/content/article/2005/11/24/AR2005112400781_pf.html" 
    	class="external autonumber" title="http://www.washingtonpost.com/wp-dyn/content/article/2005/11/24/AR2005112400781_pf.html">[1]</a>.</p>  
    
    As a non incorporated region with no mayor or town council, Germantown is, however, divided up into six town sectors, or "villages," which are:
    <ul>
		<li>Churchill Village</li> 
		<li>Gunners Lake Village</li> 
		<li>Clopper's Mill Village</li> 
		<li>Kingsview Village</li> 
		<li>Middlebrook Village</li> 
		<li>Neelsville Village</li>
    </ul>
	

    <p>It is the only &quot;Germantown, Maryland&quot; recognized by the <a href="http://en.wikipedia.org/wiki/United_States_Postal_Service" title="United States Postal Service">
    United States Postal Service</a>, although there are technically three others, one each in <a href="http://en.wikipedia.org/wiki/Anne_Arundel_County" title="Anne Arundel County">
    Anne Arundel County</a>, <a href="http://en.wikipedia.org/wiki/Baltimore_County" title="Baltimore County">Baltimore County</a>, and
    <a href="http://en.wikipedia.org/wiki/Worcester_County,_Maryland" title="Worcester County, Maryland"> Worcester County</a>.  It has the assigned
    <a href="http://en.wikipedia.org/wiki/ZIP_Code" title="ZIP Code">ZIP Codes</a> of 20874 and 20876 for delivery and 20875 for <a href="http://en.wikipedia.org/wiki/Post_office_box" title="Post office box">
    post office boxes</a> only.</p>

	<p>In the <a href="http://en.wikipedia.org/wiki/1830s" title="1830s">1830s</a> and <a href="http://en.wikipedia.org/wiki/1840s" title="1840s">1840s</a>, a 
    large number of German business owners, some of whom were immigrants from <a href="http://en.wikipedia.org/wiki/Germany" title="Germany">Germany</a> 
    and others relocating from <a href="http://en.wikipedia.org/wiki/Pennsylvania" title="Pennsylvania">Pennsylvania</a>, settled near where what are 
    now known as Liberty Mill Road and Clopper Road intersect.&nbsp; While most of the local landowners and farmers were <a href="http://en.wikipedia.org/wiki/England" title="England">English</a>, 
    travelers remembered the accents of the shop-owners and called the area <i> Germantown.</i>  Germantown has experienced great 
    growth during the past few years and an urbanized town center has been built.</p>

	<p>Some Germantown trivia:</p>

	<ul>
	  <li>Germantown appeared in the game Fallout 3 by Bethesda Softworks</li>
      <li>Germantown has appeared in several episodes of <i><a href="http://en.wikipedia.org/wiki/The_X-Files" title="The X-Files">The 
      X-Files</a></i>.</li>
      <li><a href="http://en.wikipedia.org/wiki/George_Atzerodt" title="George Atzerodt">
      George Atzerodt</a>, a co-conspirator in the <a href="http://en.wikipedia.org/wiki/Abraham_Lincoln_assassination" title="Abraham Lincoln assassination">
      Abraham Lincoln assassination</a> was captured in Germantown on <a href="http://en.wikipedia.org/wiki/April_20" title="April 20">April 20</a>,
      <a href="http://en.wikipedia.org/wiki/1865" title="1865">1865</a>.  He was assigned by <a href="http://en.wikipedia.org/wiki/John_Wilkes_Booth" title="John Wilkes Booth">
      John Wilkes Booth</a> to assassinate <a href="http://en.wikipedia.org/wiki/Vice_President_of_the_United_States" title="Vice President of the United States">
      Vice President</a> <a href="http://en.wikipedia.org/wiki/Andrew_Johnson" title="Andrew Johnson">Andrew Johnson</a>, but lost his nerve and fled
      <a href="http://en.wikipedia.org/wiki/Washington,_D.C." title="Washington, D.C.">
      Washington, D.C.</a>, on the night of the Lincoln assassination.  He was captured at his cousin Frederick Richter's farm in Germantown. 
      Atzerodt was <a href="http://en.wikipedia.org/wiki/Hanged" title="Hanged">hanged</a> on <a href="http://en.wikipedia.org/wiki/July_7" title="July 7">
      July 7</a>, <a href="http://en.wikipedia.org/wiki/1865" title="1865">1865</a> along with <a href="http://en.wikipedia.org/wiki/Mary_Surratt" title="Mary Surratt">
      Mary Surratt</a>, <a href="http://en.wikipedia.org/wiki/Lewis_Powell" title="Lewis Powell">Lewis Powell</a>, and <a href="http://en.wikipedia.org/wiki/David_Herold" title="David Herold">
      David Herold</a> in Washington, D.C. <sup id="_ref-0" class="reference"><a href="http://en.wikipedia.org/wiki/Germantown,_Maryland#_note-0" title="">[1]</a></sup></li>
    </ul>

	<p>For much more detailed information about Germantown history, I highly recommend visiting the <a href="http://www.germantownmdhistory.org/">Germantown Historical 
    Society</a> Web site.&nbsp; They provide a detailed history of Germantown, a listing of historical sites, calendar of local events, and a sales catalog 
    that includes several books about Germantown and the railroad that helped fuel the growth of Germantown.</p> 
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">About These Statistics</h2>
								<p class="meta">
								</p>
								<div class="entry">
	<p>This software began collecting the statistics displayed on these pages on 
    <?php print date($cfg->getDateTimeFormat(), $stats->getStatsStartDate())?>.  The weather station software was last started on <?php print date($cfg->getDateTimeFormat(), $stats->getLastStartDate())?>.</p> 
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">Special Thanks</h2>
								<p class="meta">
								</p>
								<div class="entry">
	<p>This site is based on a template design by <a href="http://www.freecsstemplates.org/">Free CSS Templates</a> 
	The original site design prior to using the Free CSS Templates design was based on a template by <a href="http://www.carterlake.org">CarterLake.org</a>.  The current design still incorporates much of the page layout from that design</p>

	<p>This template is HTML 5 compliant. Validate the <a href="http://validator.w3.org/check/referer">HTML</a> and <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> of this page.</p>
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

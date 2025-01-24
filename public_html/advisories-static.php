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

require_once "classes/nwsadvisories.php";
$advisories = new NWSAdvisories();

require_once "classes/nwshazards.php";
$hazards = new NWSHazards();

require_once "classes/nwsdiscussion.php";
$discussion = new NWSDiscussion();
?>

<head>
<meta name="keywords" content="" />
<meta name="keywords" content="germantown,Clopper&#39;s,Mill,Clopper&#39;s Mill,weather" />
<meta name="description" content="Personal weather station located in Germantown, MD 20874." />

<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<script type="text/javascript" src="/min//?g=weatherjs"></script>

<script type='text/javascript' src='js/date.js'></script>
<script type='text/javascript' src='js/knockout.js'></script>
<script type='text/javascript' src='js/knockoutmapping.js'></script>
<script type='text/javascript' src='js/weathermodel.js'></script>

<title>Clopper&#39;s Mill East Weather</title>

<link href="css/style.css" rel="stylesheet" type="text/css" media="screen" />
<link id="favicon" rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

</head>

<body>
<script type="text/javascript">

$(document).ready(function(){
	
	initWeatherDataModel();
	
	// get WX data every 15 secs
	$(document).everyTime(15000,retrieveWeatherData);

	//get WX stats every minute
	$(document).everyTime(60000, retrieveWeatherStats);

	//get update web cam image every 30 secs
	$("#webcamThumbnail").everyTime(30000, updateWebcam);

	// update favicon every 10 minutes based on weather forecast
	$(document).everyTime(600000, updateFavicon);
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
					<h2>Information on this page courtesy of the National Weather Service</h2>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
							  <h2 class="title">Watches, Warnings, and Advisories</h2>
							  <p class="meta">As of <?php print date($cfg->getDateTimeFormat(), $advisories->getAdvisoryPublicationDate())?></p>
								<div class="entry">
									<?php print HTMLFormatAdvisories($advisories->getWarnings(), "Warnings")?>
							        <?php print HTMLFormatAdvisories($advisories->getAdvisories(), "Advisories")?>
							        <?php print HTMLFormatAdvisories($advisories->getWatches(), "Watches")?>
							        <?php print HTMLFormatAdvisories($advisories->getStatements(), "Statements")?>
							        <?php print HTMLFormatAdvisories($advisories->getOther(), "Other")?>
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">Hazardous Weather Outlook</h2>
								<p class="meta">
									 As of <?php print date($cfg->getDateTimeFormat(), $hazards->getPublishDate())?>
								</p>
								<div class="entry">
									<?php foreach($hazards->getHazards() as $hazard) print "<h3>" . $hazard->period . "</h3>" . $hazard->statement . "<br /><br />"?>
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm">
								<h2 class="title">Weather Discussion</h2>
								<p class="meta">
									As of <?php print date($cfg->getDateTimeFormat(), $discussion->getPublishDate())?>
								</p>
								<div class="entry">
									<?php foreach($discussion->getDiscussionList() as $item) {print "<h3>" . $item->heading. "</h3>" . "<pre>" . $item->statement . "</pre>"; }?>
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

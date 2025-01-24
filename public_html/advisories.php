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

	// Show a static display for browsers that don't have Javascript.
	// Hide it if Javascript is working 
	$("#staticWarnings").css("display", "none");
	$("#staticHazards").css("display", "none");
	$("#staticDiscussion").css("display", "none");
	
	initWeatherDataModel();
	
	// get WX data every 15 secs
	$(document).everyTime(dataRefreshRate, retrieveWeatherData);

	//get WX stats every minute
	$(document).everyTime(statsRefreshRate, retrieveWeatherStats);

	//get update web cam image every 30 secs
	$("#webcamThumbnail").everyTime(webcamRefreshRate, updateWebcam);

	// get weather hazards every 10 minutes
	$(document).everyTime(hazardsRefreshRate, retrieveHazards);
		
	// get weather discussion every 30 minutes
	$(document).everyTime(discussionRefreshRate, retrieveNWSDiscussion);
		
	// update favicon every 10 minutes based on weather forecast
	$(document).everyTime(faviconRefreshRate, updateFavicon);

	$("#dynamicWarnings").css("display", "inline");
	$("#dynamicHazards").css("display", "inline");
	$("#dynamicDiscussion").css("display", "inline");
		
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
<!-- typeof description is 'function' -->

			<!--  <div id="page-bgtop">  -->
			<div id="page-bgbtm">
				<div id="content">
					<h2>Information on this page courtesy of the National Weather Service</h2>
					<div class="post">
						<div class="post-bgtop">
						
							<div class="post-bgbtm" id="staticWarnings">
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
							<!-- <div data-bind="text: ko.toJSON($data, null, 2)"></div>  -->						
							<div class="post-bgbtm" id="dynamicWarnings" style="display: none;">
								<h2 class="title">Watches, Warnings, and Advisories</h2>
								<p class="meta">As of <span data-bind="text: new Date(Date.parse(AdvisoryPublicationDate())).format(longDateTimeFormat)"><?php print date($cfg->getDateTimeFormat(), $advisories->getAdvisoryPublicationDate())?></span></p>
								<div class="entry">
									<div style="display:none;" data-bind="visible: nwsWarnings().length > 0">
										<h2>Warnings</h2>
										<div data-bind="foreach: nwsWarnings">
											<h3 data-bind="text: title">Empty</h3>
											<p class="meta" data-bind="text: new Date(Date.parse(date())).format(longDateTimeFormat)"></p>
											<div data-bind="visible: description.length == 0, text: summary"></div>
											<pre data-bind="text: description, visible: description.length > 0"></pre>
											<div data-bind="visible: instruction.length > 0 ">Instruction:<pre data-bind="text: instruction"> </pre></div>
										</div>
									</div>
									<div style="display:none;" data-bind="visible: nwsAdvisories().length > 0">
										<h2>Advisories</h2>
										<div data-bind="foreach: nwsAdvisories">
											<h3 data-bind="text: title">Empty</h3>
											<p class="meta" data-bind="text: new Date(Date.parse(date())).format(longDateTimeFormat)"></p>
											<div data-bind="visible: description.length == 0, text: summary"></div>
											<pre data-bind="text: description, visible: description.length > 0"></pre>
											<div data-bind="visible: instruction.length > 0 ">Instruction:<pre data-bind="text: instruction"> </pre></div>										
										</div>
									</div> 
									<div style="display:none;" data-bind="visible: nwsWatches().length > 0">
										<h2>Watches</h2>
										<div data-bind="foreach: nwsWatches">
											<h3 data-bind="text: title">Empty</h3>
											<p class="meta" data-bind="text: new Date(Date.parse(date())).format(longDateTimeFormat)"></p>
											<div data-bind="visible: description.length == 0, text: summary"></div>
											<pre data-bind="text: description, visible: description.length > 0"></pre>
											<div data-bind="visible: instruction.length > 0 ">Instruction:<pre data-bind="text: instruction"> </pre></div>										
										</div>
									</div>  
									<div style="display:none;" data-bind="visible: nwsStatements().length > 0">
										<h2>Statements</h2>
										<div data-bind="foreach: nwsStatements">
											<h3 data-bind="text: title">Empty</h3>
											<p class="meta" data-bind="text: new Date(Date.parse(date())).format(longDateTimeFormat)"></p>
											<div data-bind="visible: description.length == 0, text: summary"></div>
											<pre data-bind="text: description, visible: description.length > 0"></pre>
											<div data-bind="visible: instruction.length > 0 ">Instruction:<pre data-bind="text: instruction"> </pre></div>										
										</div>
									</div>  
									<div style="display:none;" data-bind="visible: nwsOther().length > 0">
										<h2>Other</h2>
										<div data-bind="foreach: nwsOther">
											<h3 data-bind="text: title">Empty</h3>
											<p class="meta" data-bind="text: new Date(Date.parse(date())).format(longDateTimeFormat)"></p>
											<div data-bind="visible: description.length == 0, text: summary"></div>
											<pre data-bind="text: description, visible: description.length > 0"></pre>
											<div data-bind="visible: instruction.length > 0 ">Instruction:<pre data-bind="text: instruction"> </pre></div>
										</div>
									</div>  
								</div>
							</div>
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
						
							<div class="post-bgbtm" id="staticHazards">
								<h2 class="title">Hazardous Weather Outlook</h2>
								<p class="meta">
									 As of <?php print date($cfg->getDateTimeFormat(), $hazards->getPublishDate())?>
								</p>
								<div class="entry">
									<?php foreach($hazards->getHazards() as $hazard) print "<h3>" . $hazard->period . "</h3>" . $hazard->statement . "<br /><br />"?>
								</div>
							</div>
						
							<div class="post-bgbtm" id="dynamicHazards" style="display: none;">
								<h2 class="title">Hazardous Weather Outlook</h2>
								<p class="meta">As of 
									 <span data-bind="text: new Date(Date.parse(PublishDate())).format(longDateTimeFormat)"><?php print date($cfg->getDateTimeFormat(), $hazards->getPublishDate())?></span>
								</p>
								<div class="entry" data-bind="foreach: Hazards">
									<h3 data-bind="text: period">Hazards</h3> 
									<span data-bind="text:statement"></span><br /><br />
								</div>
							</div>  
						</div>
					</div>
					<div class="post">
						<div class="post-bgtop">
							<div class="post-bgbtm" id="staticDiscussion">
								<h2 class="title">Weather Discussion</h2>
								<p class="meta">
									As of <?php print date($cfg->getDateTimeFormat(), $discussion->getPublishDate())?>
								</p>
								<div class="entry">
									<?php foreach($discussion->getDiscussionList() as $item) {print "<h3>" . $item->heading. "</h3>" . "<pre>" . $item->statement . "</pre>"; }?>
								</div>
							</div>
						
							<div class="post-bgbtm" id="dynamicDiscussion" style="display: none;">
								<h2 class="title">Weather Discussion</h2>
								<p class="meta">As of 
									 <span data-bind="text: new Date(Date.parse(DiscussionPublishDate())).format(longDateTimeFormat)"><?php print date($cfg->getDateTimeFormat(), $hazards->getPublishDate())?></span>
								</p>
								<div class="entry" data-bind="foreach: DiscussionList">
									<h3 data-bind="text: heading">Discussion</h3>
									<pre data-bind="text: statement"></pre><br /><br />
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

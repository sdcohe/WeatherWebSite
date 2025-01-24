<!-- start #menu -->
<div id="menu">
	<ul>


	<?php

	// dynamically build the navigation bar
	$currentPage = basename($_SERVER['SCRIPT_NAME']);

	// list of navigable pages
	$items = array(
	array('link'=>'index.php', 'title'=> 'Web site front page', 'label'=>'Home'),
	array('link'=>'forecast.php', 'title'=> 'National Weather Service 5-day Forecast', 'label'=>'Forecast'),
	array('link'=>"radar.php", 'title'=>"Radar images", 'label'=>"Radar"),
	array('link'=>"satellite.php", 'title'=>"Satellite Images", 'label'=>"Satellite"),
	array('link'=>"advisories.php", 'title'=>"Watches, warnings, and advisories from the National Weather Service", 'label'=>"NWS Advisories"),
	array('link'=>"local.php", 'title'=>"Local and federal government alerts", 'label'=>"Local Alerts"),
	array('link'=>"almanac.php", 'title'=>"Astronomy related events", 'label'=>"Almanac"),
	array('link'=>"travel.php", 'title'=>"Travel and traffic information", 'label'=>"Travel"),
	array('link'=>"links.php", 'title'=>"Links to weather related information and sites", 'label'=>"Links"),
	array('link'=>"blog/", 'title'=>"Blog for this site", 'label'=>"Blog"),
	array('link'=>"about.php", 'title'=>"About this site", 'label'=>"About Us"),
	array('link'=>"software.php", 'title'=>"Software used by this site", 'label'=>"Software"),
	);

	// loop through the list of items and build the navigation menu
	foreach($items as $item)
	{
		// disable current page
		if ($currentPage == $item['link'])
		{
			print "<li><span class=\"disabled\">" . $item['label'] . "</span></li>\n";
		}
		else
		{
			print '<li><a href="' . $item['link'] . '" title="' . $item['title'] . '">' . $item['label'] . "</a></li>\n";

		}
	}
	?>
	</ul>
</div>
<!-- end #menu -->
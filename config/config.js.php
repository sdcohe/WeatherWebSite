<?php
if (headers_sent())
{
	// this is an error condition
	print "Header sent already";
}
else
{
	// try and compress the output
	ob_start("ob_gzhandler");

	header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
	header("Cache-Control: no-cache, must-revalidate" );
	header("Pragma: no-cache" );
	Header("content-type: application/x-javascript");
//	header("Content-Type: text/plain; charset=utf-8");
	
	require_once "config.php";

	$cfg = config::getInstance();
	date_default_timezone_set($cfg->getTimeZone());

	// output config values here. 

	/*
	 * Refresh rates for page data
	 */
	// weather data refresh rate
	print "var dataRefreshRate = " . $cfg->getDataRefreshRate() * 1000 . ";\n";
	
	// weather stats refresh rate
	print "var statsRefreshRate = " . $cfg->getStatsRefreshRate() * 1000 . ";\n";
	
	// web cam refresh rate
	print "var webcamRefreshRate = " . $cfg->getWebcamRefreshRate() * 1000 . ";\n";
	
	// forecast refresh rate
	print "var forecastRefreshRate = " . $cfg->getForecastRefreshRate() * 1000 . ";\n";
	
	// hazards refresh rate
	print "var hazardsRefreshRate = " . $cfg->getHazardsRefreshRate() * 1000 . ";\n";
	
	// discussion refresh rate
	print "var discussionRefreshRate = " . $cfg->getDiscussionRefreshRate() * 1000 . ";\n";
	
	// advisories refresh rate
	print "var advisoriesRefreshRate = " . $cfg->getAdvisoriesRefreshRate() * 1000 . ";\n";
	
	// history refresh rate
	print "var historyRefreshRate = " . $cfg->getHistoryRefreshRate() * 1000 . ";\n";
	
	// favicon refresh rate
	print "var faviconRefreshRate = " . $cfg->getFaviconRefreshRate() * 1000 . ";\n";
	
	/*
	 * Date and time formats
	 */
	// long date time format
	print "var longDateTimeFormat = \"ddd mmm dd, yyyy HH:MM:ss Z\";\n";
	
	// short date time
	print "var shortDateTimeFormat = \"mm/dd/yy HH:MM:ss\";\n";
	
	// very short date time format 'mm/dd/yy HH:MM'
	print "var veryShortDateTimeFormat = \"mm/dd/yy HH:MM\";\n";
	
	// Long time format
	print "var longTimeFormat = \"HH:MM:ss Z\";\n";
	
	// ISO time
	print "var ISOTimeFormat = \"HH:MM:ss\";\n";
	
	// Date only
	print "var dateOnlyFormat = \"mm/dd/yyyy\";\n";
		
	// Colors
	print "var  redHighlight = \"" . $cfg->getRed(). "\";\n";
	print "var blueHighlight = \"" . $cfg->getBlue(). "\";\n";
	
	// number formatting
 	print "var numberDecimalPlaces = \"" . $cfg->getNumberDecimalPlaces() . "\";\n";
 	
 	// latitude
 	print "var latitude = \"" . $cfg->getLatitude() . "\";\n";
 	
 	// longitude
 	print "var longitude = \"" . $cfg->getLongitude() . "\";\n";
 	
}

?>
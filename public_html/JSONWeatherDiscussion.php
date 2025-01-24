<?php
if (headers_sent())
{
	echo "Header sent already";
}
else
{
	// try and compress the output
	ob_start("ob_gzhandler");

	header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
	header("Cache-Control: no-cache, must-revalidate" );
	header("Pragma: no-cache" );
	header("Content-Type: text/plain; charset=utf-8");

	require_once "config/config.php";

	$cfg = config::getInstance();
	date_default_timezone_set($cfg->getTimeZone());

	require_once "classes/nwsdiscussion.php";

	$data = new NWSDiscussion();

	print $data->getJSON();

// 	print '{';
// 	print '"DiscussionPublishDate": "2014-02-08T15:10:00-05:00",';
// 	print '"DiscussionList": [';
	
// 	print '{';
// 	print '"heading": "SYNOPSIS",';
// 	print '"statement": "Several low pressure systems will move adjacent to the region and\noffshore during the remainder of the weekend. High pressure will\nreturn for the first part of the upcoming week. Low pressure may\naffect the region again during midweek."';
// 	print '}';
	
// 	print ']';
// 	print '}';
	
}

?>
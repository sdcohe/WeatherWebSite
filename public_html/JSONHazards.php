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

	require_once "classes/nwshazards.php";

	$data = new NWSHazards();

	print $data->getJSON();

}

?>
<?php

require_once "config/config.php";
require 'classes/Astro_MoonPhase.php';

$cfg = config::getInstance();

define('MEAN_TROPICAL_YEAR', 365.242189);

// set default time zone to eastern time
date_default_timezone_set($cfg->getTimeZone());

include "classes/moon.php";

$year = date("Y");
$month = date("n");
$day = date("j");
// $moon = Moon::calculateMoonTimes($month, $day, $year, 39.1522, -77.2679);
$moon = Moon::calculateMoonTimes($month, $day, $year, $cfg->getLatitude(), $cfg->getLongitude());
// print date("m/d H:i:s", $moon->moonrise);
// print date("m/d H:i:s", $moon->moonset);

// $sunInfo = date_sun_info(time(), 39.1522, -77.2679);
$sunInfo = date_sun_info(time(), $cfg->getLatitude(), $cfg->getLongitude());
$secsDaylight = $sunInfo["sunset"] - $sunInfo["sunrise"];
$hoursDaylight = (int)($secsDaylight / 3600);
$minutesDaylight = (int)(($secsDaylight % 3600) / 60);

// get moon phases
$phases = array();
$phases = Astro_MoonPhase::phasehunt(time());

// get rise/set time for moon
$moondata = Astro_MoonPhase::phase(time());

//$localtimezone = "";
$moonAgeDays = (int)($moondata[2]);
$moonAgeHours = (int)(($moondata[2] - $moonAgeDays) * 24);
$moonAgeMinutes = number_format((((($moondata[2] - $moonAgeDays) * 24) - $moonAgeHours) * 60),2);

$moonimage = "moon" . getMoonIcon($moondata[0] * 100, $moondata[2]) . ".gif";
$moonage = "$moonAgeDays days, $moonAgeHours hours, $moonAgeMinutes minutes";
$sunrise = date("H:i:s T", $sunInfo["sunrise"]);
$sunset = date("H:i:s T", $sunInfo["sunset"]);
$civiltwilightbegin  = date("H:i:s T", $sunInfo["civil_twilight_begin"]);// civil
$civiltwilightend  = date("H:i:s T", $sunInfo["civil_twilight_end"]);// civil
$hoursofpossibledaylight = "$hoursDaylight hours, $minutesDaylight minutes";

if (date("j", $moon->moonrise) == $day)
{
	$moonrise = date("H:i:s T", $moon->moonrise);
}
else 
{
	$moonrise = "None today";
}
if (date("j", $moon->moonset) == $day)
{
	$moonset = date("H:i:s T", $moon->moonset);
}
else
{
	$moonset = "None today";
}
$moonphase = number_format($moondata[0] * 100);
$moonillum = number_format($moondata[1] * 100);
$moonphaselit = getPhaseLiteral($moonphase);
$firstquarter = date("D M d, Y H:i:s T", $phases[1]);;
$fullmoon = date("D M d, Y H:i:s T", $phases[2]);;
$lastquarter = date("D M d, Y H:i:s T", $phases[3]);;
$nextnewmoon = date("D M d, Y H:i:s T", $phases[4]);

$decsolstice = date("D M d, Y H:i:s T",jdtolocaltime(getWinterSolstice(date("Y"))));
$sepequinox = date("D M d, Y H:i:s T",jdtolocaltime(getAutumnalEquinox(date("Y"))));
$marchequinox = date("D M d, Y H:i:s T",jdtolocaltime(getVernalEquinox(date("Y"))));//vernal
$junesolstice = date("D M d, Y H:i:s T",jdtolocaltime(getSummerSolstice(date("Y"))));

$easterdate = date("D M d, Y", easter_date($year));
$pesachdate = date("D M d, Y", getPesach($year));

$chinesenewyear = "";
$moonapogee = "";
$moonperigee = "";

function jdtolocaltime($jd)
{
	// $jd is an array 0=>year, 1=>month, 2=>day with fraction for hms
	$year = $jd[0];
	$month = $jd[1];
	$day = floor($jd[2]);
	$hour = ($jd[2] - $day) * 24;
	$min = ($hour - floor($hour)) * 60;
	$sec = ($min - floor($min)) * 60;
	
	$dt = new DateTime($year . "-" . $month . "-"  . $day . "T" . floor($hour) . ":" . floor($min) . ":" . floor($sec), new DateTimeZone("GMT"));
	$dt->setTimezone(new DateTimeZone(date_default_timezone_get()));
	
	return mktime(floor($hour), floor($min), $sec, $month, $day, $year) + $dt->getOffset();
// 	return $dt->getTimestamp();  - not available until PHP 5.3
}

function getPesach($year)
{
	$C = (int)floor(($year / 100));
	$S = (int)floor(((3.0 * (int)$C - 5.0) / 4.0));
	$a = (int) floor(((12.0 * $year + 12.0) % 19));
	$b = (int)($year % 4);
	$Q = -1.904412361576 + 1.554241796621 * (int)$a + 0.25 * (int)$b - 0.003177794022 * $year + $S;
	$j = (int) floor(((((int)$Q) + 3.0 * $year + 5.0 * (int)$b + 2.0 - $S) % 7));
	$r = $Q - (int)$Q;

	if ($year < 1583)
	{
		$S = 0;
	}
	if ($j ==2 || $j ==4 || $j ==6)
	{
		$D = (int)$Q + 23;
	}
	else if ($j == 1 && $a > 6 && $r >= 0.632870370)
	{
		$D = (int)$Q + 24;
	}
	else if ($j == 0 && $a > 11 && $r >= 0.897723765)
	{
		$D = (int)$Q + 23;
	}
	else
	{
		$D = (int)$Q + 22;
	}

	$month = 3;
	if ($D > 31)
	{
		$month = 4;
		$D = $D - 31;
	}

	return mktime(9, 9, 9, $month, $D, $year);
}

function getMoonIcon($phase, $age)
{
	$value = number_format($phase / 100 * 30 - 1);
	if ($value < 0) $value = 0;
	return $value;
	
	
// 	set moon icon = 0 to 29 (30 values)
// 	if ($age <= ASTRO_SYNMONTH/2)
// 	{
// 		$iconValue = (int)($phase * 15/100);
// 		if ($iconValue == 15)
// 		{
// 			$iconValue = 14;
// 		}
// 	}
// 	else
// 	{
// 		$iconValue = (int)(100 + (100-$phase) * 15/100);
// 		if ($iconValue == 30)
// 		{
// 			$iconValue = 29;
// 		}
// 	}
	
// 	return $iconValue;
}

function getPhaseLiteral($phase)
{
// January -- Moon after Yule
// February -- Snow Moon
// March -- Sap Moon
// April -- Grass Moon
// May -- Planting Moon
// June -- Honey Moon
// July -- Thunder Moon
// August -- Grain Moon
// September -- Fruit Moon (or Harvest Moon)
// October -- Hunter's Moon (or Harvest Moon)
// November -- Frosty Moon
// December -- Moon before Yule

	// 0 = new moon
	// 1-24crescent
	// 25 = 1st quarter
	// gibbous
	// 50 = full
	// gibbous
	// 75 = 3rd quarter
	// crescent

	if ($phase == 0 || $phase == 100)
	{
		return "New";
	}
	else if($phase > 0 && $phase < 24)
	{
		return "Waxing Crescent";
	}
	else if ($phase >= 24 && $phase <= 26)
	{
		return "First Quarter";
	}
	else if($phase > 26 && $phase < 49)
	{
		return "Waxing Gibbous";
	}
	else if($phase >= 49 && $phase <= 51)
	{
		return "Full";
	}
	else if ($phase > 51 && $phase < 74)
	{
		return "Waning Gibbous";
	}
	else if ($phase >= 74 && $phase <= 76)
	{
		return "Third Quarter";
	}
	else if ($phase > 76 && $phase < 100)
	{
		return "Waning Crescent";
	}
	
}

function getY($year)
{
	$Y;

	if ($year > 1000)
	{
		$Y = ($year - 2000.0) / 1000.0;
	}
	else
	{
		$Y = $year / 1000.0;
	}

	return $Y;
}

function getVernalEquinox($year)
{
	$Y = getY($year);
	$JDE0 = 0.0;

	if ($year > 1000)
	{
		$JDE0 = 2451623.80984 + $Y * (365242.37404 + $Y * (0.05169  - $Y * (0.00411 - ($Y * 0.00057))));
	}
	else
	{
		$JDE0 = 1721139.2918 + $Y*(365242.13740 + $Y*(0.06134 + $Y *(0.00111 - ($Y*0.00071))));
	}

	return getSolarEvent($JDE0);
}

function getAutumnalEquinox($year)
{
	$Y = getY($year);
	$JDE0 = 0.0;

	if ($year > 1000)
	{
		$JDE0 = 2451810.21715 + $Y * (365242.01767 - $Y * (0.11575  + $Y * (0.00337 + ($Y * 0.00078))));
	}
	else
	{
		$JDE0 = 1721325.70455 + $Y*(365242.49558 - $Y*(0.11677 - $Y *(0.00297 + ($Y*0.00074))));
	}

	return getSolarEvent($JDE0);
}

function getSummerSolstice($year)
{
	$Y = getY($year);
	$JDE0 = 0.0;

	if ($year > 1000)
	{
		$JDE0 = 2451716.56767 + $Y * (365241.62603 + $Y * (0.00325  + $Y * (0.00888 - ($Y * 0.00030))));
	}
	else
	{
		$JDE0 = 1721233.25401 + $Y*(365241.72562 - $Y*(0.05323 + $Y *(0.00907 + ($Y*0.00025))));
	}

	return getSolarEvent($JDE0);
}

function getWinterSolstice($year)
{
	$Y = getY($year);
	$JDE0 = 0.0;

	if ($year > 1000)
	{
		$JDE0 = 2451900.05952 + $Y * (365242.74049 - $Y * (0.06223  - $Y * (0.00823 + ($Y * 0.00032))));
	}
	else
	{
		$Y = $year / 1000.0;
	}

	return getSolarEvent($JDE0);
}

function getSolarEvent($JDE0)
{
	$solsticeEquinoxTable = array(
		array(485.0, 324.96, 1934.136),
		array(203.0, 337.23, 32964.467),
		array(199.0, 342.08, 20.186),
		array(182.0, 27.85, 445267.112),
		array(156.0, 73.14, 45036.886),
		array(136.0, 171.52, 22518.443),
		array(77.0, 222.54, 65928.934),
		array(74.0, 296.72, 3034.96),
		array(70.0, 243.58, 9037.513),
		array(58.0, 119.81, 33718.147),
		array(52.0, 297.17, 150.678),
		array(50.0, 21.02, 2281.226)
	);
	
	$T = ($JDE0 - 2451545.0) / 36525.0;
	$W = 35999.373 * $T - 2.47;
	$delta = 1.0 + 0.0334 * cos(deg2rad($W)) + 0.0007 * cos(deg2rad(2.0 * $W));
	$S = 0.0;
	for ($i = 0; $i < count($solsticeEquinoxTable); $i++)
	{
		$S += $solsticeEquinoxTable[$i][0] * cos(deg2rad($solsticeEquinoxTable[$i][1] + $solsticeEquinoxTable[$i][2] * $T));
	}

	$JDE = ($JDE0 + ((0.00001 * $S) / $delta));
	return jyear($JDE0);
}

function jyear($td)  
{
	$td += 0.5;    // astronomical to civil.
	$z = floor( $td );
	$f = $td - $z;

	if ( $z < 2299161.0 )  
	{
		$a = $z;
	}
	else  
	{
		$alpha = floor( ($z - 1867216.25) / 36524.25 );
		$a = $z + 1 + $alpha - floor( $alpha / 4 );
	}

	$b = $a + 1524;
	$c = floor( ($b - 122.1) / 365.25 );
	$d = floor( 365.25 * $c );
	$e = floor( ($b - $d) / 30.6001 );

	$dd = $b - $d - floor( 30.6001 * $e ) + $f;
	$mm = $e < 14 ? $e - 1 : $e - 13;
	$yy = $mm > 2 ? $c - 4716 : $c - 4715;
	
	return array($yy, $mm, $dd);
}

?>
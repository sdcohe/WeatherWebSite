<?php

function calcWetBulbF($tempF, $rh)
{
	$interim = (($tempF - 32) / 1.8);
	$wetBulb = ((-5.806 + 0.672 * $interim - 0.006 * $interim * $interim
	+ (0.061 + 0.004 * $interim + 0.000099 * $interim * $interim) * $rh + (-0.000033
	- 0.000005 * $interim - 0.0000001 * $interim * $interim)
	* $rh * $rh) * 1.8) + 32;
	return $wetBulb;
}

function calcHeatIndexFahrenheit($tempFahrenheit, $humidity)
{
	if ($tempFahrenheit < 80 || $humidity < 40)
	{
		return $tempFahrenheit;
	}

	$tempSquared = $tempFahrenheit * $tempFahrenheit;
	$tempCubed = $tempSquared * $tempFahrenheit;
	$humiditySquared = $humidity * $humidity;
	$humidityCubed = $humiditySquared * $humidity;

	return (16.923 + (0.185212 * $tempFahrenheit)
	+ (5.37941 * $humidity) - (0.100254 * $tempFahrenheit * $humidity)
	+ ((0.941695e-2) * $tempSquared)
	+ ((0.728898e-2) * $humiditySquared)
	+ ((0.345372e-3) * $tempSquared * $humidity)
	- ((0.814971e-3) * $tempFahrenheit * $humiditySquared)
	+ ((0.102102e-4) * $tempSquared * $humiditySquared)
	- ((0.38646e-4) * $tempCubed) + ((0.291583e-4) * $humidityCubed)
	+ ((0.142721e-5) * $tempCubed * $humidity)
	+ ((0.197483e-6) * $tempFahrenheit * $humidityCubed)
	- ((0.218429e-7) * $tempCubed * $humiditySquared)
	+ ((0.843296e-9) * $tempSquared * $humidityCubed) - ((0.481975e-10) * $tempCubed * $humidityCubed));
}

function calcWindChillFahrenheit($temperature, $windSpeed)
{
	$windChillTemperature = $temperature;

	if ($temperature <= 50.0 && $windSpeed >= 3.0)
	{
		// 		Windchill (ºF) = 35.74 + 0.6215T - 35.75(V^0.16) +
		// 		0.4275T(V^0.16)
		$windChillTemperature = (35.74 + (0.6215 * $temperature)
		- (35.75 * pow( $windSpeed, 0.16)) + (0.4275 * $temperature * pow($windSpeed, 0.16)));
		if ($windChillTemperature > $temperature)
		{
			$windChillTemperature = $temperature;
		}
	}
	else
	{
		$windChillTemperature = $temperature;
	}

	return $windChillTemperature;
}


function calcDewpointFahrenheit($tempFahrenheit, $hum)
{
	if ($tempFahrenheit == 1.4E-45 || $hum == 1.4E-45)
		return 1.4E-45;

	// 	compute the dew point from relative humidity & temperature

	// 	convert to degrees K
	$tempK = fahrenheitToKelvin($tempFahrenheit);

	// 	calc dewpoint
	$dp = $tempK / ((-0.0001846 * log($hum / 100.0) * $tempK) + 1.0);
	
	// 	convert back to degrees F
	return kelvinToFahrenheit((float)$dp);
}

function kelvinToFahrenheit($tempKelvin)
{
	return centigradeToFahrenheit(kelvinToCentigrade($tempKelvin));
}

function kelvinToCentigrade($tempKelvin)
{
	return $tempKelvin - 273.15;
}

function centigradeToFahrenheit($tempCentigrade)
{
	return ($tempCentigrade * 9 / 5) + 32;
}

function fahrenheitToKelvin($tempFahrenheit)
{
	return centigradeToKelvin(fahrenheitToCentigrade($tempFahrenheit));
}

function fahrenheitToCentigrade($tempFahrenheit)
{
	return (($tempFahrenheit - 32.0) / 9.0 * 5.0);
}

function centigradeToKelvin($tempCentigrade)
{
	return $tempCentigrade + 273.15;
}

function estimateCloudBaseHeightFahrenheit($tempF, $dewPointF)
{
	$spread = $tempF - $dewPointF;
	$spread = $spread / 4.4;
	return $spread * 1000;
}


function getWindDirStr($input)
{
	$direction = array(
		" N ", "NNE", "NE ", "ENE", " E ", "ESE", "SE ", "SSE", " S ", "SSW",
		"SW ", "WSW", " W ", "WNW", "NW ", "NNW", " ---"
	);

	// 		valid inputs 0 thru 16
	if ($input < 0 || $input >= 16)
	{
		$input = 16;
	}

	return $direction[(int)$input];
}


function windSpeedToLiteralMPH($speedMPH)
{
	$value = "";

	if ($speedMPH < 1.0)
	{
		$value = "calm";
	}
	else if ($speedMPH >= 1.0 && $speedMPH < 4.0)
	{
		$value = "light air";
	}
	else if ($speedMPH >= 4.0 && $speedMPH < 8.0)
	{
		$value = "slight breeze";
	}
	else if ($speedMPH >= 8.0 && $speedMPH < 13.0)
	{
		$value = "gentle breeze";
	}
	else if ($speedMPH >= 13.0 && $speedMPH < 19.0)
	{
		$value = "moderate breeze";
	}
	else if ($speedMPH >= 19.0 && $speedMPH < 25.0)
	{
		$value = "fresh breeze";
	}
	else if ($speedMPH >= 25.0 && $speedMPH < 32.0)
	{
		$value = "strong breeze";
	}
	else if ($speedMPH >= 32.0 && $speedMPH < 39.0)
	{
		$value = "moderate gale";
	}
	else if ($speedMPH >= 39.0 && $speedMPH < 47.0)
	{
		$value = "fresh gale";
	}
	else if ($speedMPH >= 47.0 && $speedMPH < 55.0)
	{
		$value = "strong gale";
	}
	else if ($speedMPH >= 55 && $speedMPH < 64.0)
	{
		$value = "whole gale";
	}
	else if ($speedMPH >= 64.0 && $speedMPH < 75.0)
	{
		$value = "storm";
	}
	else if ($speedMPH >= 75.0)
	{
		$value = "hurricane";
	}

	return $value;
}

function formatNumber($value, $decimals = 0)
{
	if ($value == 3.4028235E38 || $value == 1.4E-45)
	{
		return "---";
	}
	else
	{
		return number_format($value, $decimals);
	}
}

function isValueEmpty($value)
{
	if ($value == 3.4028235E38 || $value == 1.4E-45)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function formatNumberDP($value)
{
	return formatNumber($value, config::getInstance()->getNumberDecimalPlaces());
}

function pressureTrendInInchesToLiteral($trend)
{
	$value = "";

	if ($trend >= 0.06)
	{
		$value = "rising rapidly";
	}
	else if ($trend >= 0.02 && $trend < 0.06)
	{
		$value = "rising slowly";
	}
	else if ($trend < 0.02 && $trend > -0.02)
	{
		$value = "steady";
	}
	else if ($trend <= -0.02 && $trend > -0.06)
	{
		$value = "falling slowly";
	}
	else if ($trend <= -0.06)
	{
		$value = "falling rapidly";
	}

	return $value;
}

function HTMLSplitWords($string)
{
	return preg_replace("/ /", "<br />", $string);
}

function HTMLFormatForecast($forecast)
{
	$dark = '<tr class="column-dark" style="text-align: left;">';
	$light = '<tr class="column-light" style="text-align: left;">';
	$i = 0;
//	$detailedForecast = '<thead><tr class="table-top"><td><b>PERIOD</b></td><td><b>DESCRIPTION</b></td></tr></thead>';
	$detailedForecast = '<thead><tr class="table-top"><td></td><td><b>PERIOD</b></td><td><b>DESCRIPTION</b></td></tr></thead>';
	
	foreach ($forecast->getForecastList() as $item)
	{
		$detailedForecast = $detailedForecast . (($i % 2 == 1) ? $dark : $light);
//		$detailedForecast = $detailedForecast . '<td style="width: 20%; border-collapse: collapse;"><b><span >' .  $forecast->getForecastDay($i) . '</span></b>' . 
//			'<br /><img src="' . $forecast->getForecastIcons($i) . '" width="55" height="58" \>' . '</td>';
		$detailedForecast = $detailedForecast . '<td><img src="' . $forecast->getForecastIcons($i) . '" width="55" height="58" alt="Forecast icon" />' . '</td>';
		$detailedForecast = $detailedForecast . '<td><b><span >' .  HTMLSplitWords($forecast->getForecastDay($i)) . '</span></b></td>'; 
		$detailedForecast = $detailedForecast . '<td style="width: 80%; border-collapse: collapse;"><span >' . $item . '</span></td>';
		$detailedForecast = $detailedForecast . '</tr>';
		$i++;
	}
	
	return $detailedForecast;
}

function HTMLFormatAdvisories($advisories, $title)
{
	$result = "";

	foreach ($advisories as $advisory)
	{
		$pubDate = trim(date(config::getInstance()->getDateTimeFormat(), $advisory->getDate()));
		$result = $result . '<h3>' . $advisory->getTitle() . '</h3><p class="meta">' . $pubDate . '</p>';
		
		if (strlen($advisory->getDescription()) > 0)
		{
			$result = $result . "<pre>" .  trim($advisory->getDescription()) . "</pre>";
			if (strlen($advisory->getInstruction()) > 0)
			{
				$result = $result . "Instruction:<pre>" .  trim($advisory->getInstruction()) . "</pre>";
			}
		}
		else if (strlen($advisory->getSummary()) > 0)
		{
			$result = $result . "<span>" .  trim($advisory->getSummary()) . "</span>";
		}
		// $result = $result . '<br /><span class = "meta">' . $pubDate . "</span><br />";
//		$result = $result . '<br />' . $pubDate . "<br />";
	}

	if (strlen($result) != 0)
	{
		$result = '<h2>' . $title . '</h2>' . $result;
	}

	return $result;
}

function periodToComma($value)
{
	$value = preg_replace("/---/", "", $value);
	$value = preg_replace("/,/", "", $value);
	return preg_replace("/\./", ",", $value);
}

function windDirToCompass($windDir)
{
	return periodToComma($windDir * 360 / 16);
}

function contrastingTextColor($bgColor)
{
	// bgcolor is a string specified as #RRGGBB
	// parse out r, g, and b values of bgcolor

	// don't know why,but if we compare directly with $bgColor[0] it always fails
	// but if we grab the first char and compare it works OK  
	$firstChar = $bgColor[0];
	
	// strip off leading '#'
	if ($bgColor[0] == '#')
//	if ($firstChar == '#')
	{
		$bgColor = substr($bgColor, 1);
	}
	else
	{
		return '#EFEFEF';
	}

	// if we have 6 characters go ahead and parse it
	if (strlen($bgColor) == 6)
	{
		list($red, $green, $blue) = 
			array(hexdec($bgColor[0] . $bgColor[1]),
				  hexdec($bgColor[2] . $bgColor[3]),
				  hexdec($bgColor[4] . $bgColor[5]));
	}
	else
	{
		// error in input string
		return "#FEFEFE";
	}
	
	// calculate color difference between background and black text
	$colorDifference = (max($red, 255) - min($red, 255)) + (max($green, 255) - min($green, 255)) + (max($blue, 255) - min($blue, 255));
	
	// if difference is >= 500 then return white else return black
	if ($colorDifference >= 500)
	{
		return "#FFFFFF"; // white
	}
	else
	{
		return "#000000"; // black
	}
}

function warningBoxColor($warning)
{
	$ucaseWarning = strtoupper($warning);

	if (strpos($ucaseWarning, "911 TELEPHONE OUTAGE") !== false) { return"#C0C0C0"; }
	if (strpos($ucaseWarning, "ADMINISTRATIVE MESSAGE") !== false) { return"#FFFFFF"; }
	if (strpos($ucaseWarning, "AIR QUALITY ALERT") !== false) { return"#808080"; }
	if (strpos($ucaseWarning, "AIR STAGNATION ADVISORY") !== false) { return"#808080"; }
	if (strpos($ucaseWarning, "ARROYO AND SMALL STREAM FLOOD ADVISORY") !== false) { return"#00FF7F"; }
	if (strpos($ucaseWarning, "ASHFALL ADVISORY") !== false) { return"#696969"; }
	if (strpos($ucaseWarning, "ASHFALL WARNING") !== false) { return"#A9A9A9"; }
	if (strpos($ucaseWarning, "AVALANCHE ADVISORY") !== false) { return"#CD853F"; }
	if (strpos($ucaseWarning, "AVALANCHE WARNING") !== false) { return"#1E90FF"; }
	if (strpos($ucaseWarning, "AVALANCHE WATCH") !== false) { return"#F4A460"; }
	if (strpos($ucaseWarning, "BEACH HAZARDS STATEMENT") !== false) { return"#40E0D0"; }
	if (strpos($ucaseWarning, "BLIZZARD WARNING") !== false) { return"#FF4500"; }
	if (strpos($ucaseWarning, "BLIZZARD WATCH") !== false) { return"#ADFF2F"; }
	if (strpos($ucaseWarning, "BLOWING DUST ADVISORY") !== false) { return"#BDB76B"; }
	if (strpos($ucaseWarning, "BRISK WIND ADVISORY") !== false) { return"#D8BFD8"; }
	if (strpos($ucaseWarning, "CHILD ABDUCTION EMERGENCY") !== false) { return"#FFD700"; }
	if (strpos($ucaseWarning, "CIVIL DANGER WARNING") !== false) { return"#FFB6C1"; }
	if (strpos($ucaseWarning, "CIVIL EMERGENCY MESSAGE") !== false) { return"#FFB6C1"; }
	if (strpos($ucaseWarning, "COASTAL FLOOD ADVISORY") !== false) { return"#7CFC00"; }
	if (strpos($ucaseWarning, "COASTAL FLOOD STATEMENT") !== false) { return"#6B8E23"; }
	if (strpos($ucaseWarning, "COASTAL FLOOD WARNING") !== false) { return"#228B22"; }
	if (strpos($ucaseWarning, "COASTAL FLOOD WATCH") !== false) { return"#66CDAA"; }
	if (strpos($ucaseWarning, "DENSE FOG ADVISORY") !== false) { return"#708090"; }
	if (strpos($ucaseWarning, "DENSE SMOKE ADVISORY") !== false) { return"#F0E68C"; }
	if (strpos($ucaseWarning, "DUST STORM WARNING") !== false) { return"#FFE4C4"; }
	if (strpos($ucaseWarning, "EARTHQUAKE WARNING") !== false) { return"#8B4513"; }
	if (strpos($ucaseWarning, "EVACUATION - IMMEDIATE") !== false) { return"#7FFF00"; }
	if (strpos($ucaseWarning, "EXCESSIVE HEAT WARNING") !== false) { return"#C71585"; }
	if (strpos($ucaseWarning, "EXCESSIVE HEAT WATCH") !== false) { return"#800000"; }
	if (strpos($ucaseWarning, "EXTREME COLD WARNING") !== false) { return"#0000FF"; }
	if (strpos($ucaseWarning, "EXTREME COLD WATCH") !== false) { return"#0000FF"; }
	if (strpos($ucaseWarning, "EXTREME FIRE DANGER") !== false) { return"#E9967A"; }
	if (strpos($ucaseWarning, "EXTREME WIND WARNING") !== false) { return"#FF8C00"; }
	if (strpos($ucaseWarning, "FIRE WARNING") !== false) { return"#A0522D"; }
	if (strpos($ucaseWarning, "FIRE WEATHER WATCH") !== false) { return"#FFDEAD"; }
	if (strpos($ucaseWarning, "FLASH FLOOD STATEMENT") !== false) { return"#8B0000"; }
	if (strpos($ucaseWarning, "FLASH FLOOD WARNING") !== false) { return"#8B0000"; }
	if (strpos($ucaseWarning, "FLASH FLOOD WATCH") !== false) { return"#2E8B57"; }
	if (strpos($ucaseWarning, "FLOOD ADVISORY") !== false) { return"#00FF7F"; }
	if (strpos($ucaseWarning, "FLOOD STATEMENT") !== false) { return"#00FF00"; }
	if (strpos($ucaseWarning, "FLOOD WARNING") !== false) { return"#00FF00"; }
	if (strpos($ucaseWarning, "FLOOD WATCH") !== false) { return"#2E8B57"; }
	if (strpos($ucaseWarning, "FREEZE WARNING") !== false) { return"#00FFFF"; }
	if (strpos($ucaseWarning, "FREEZE WATCH") !== false) { return"#4169E1"; }
	if (strpos($ucaseWarning, "FREEZING FOG ADVISORY") !== false) { return"#008080"; }
	if (strpos($ucaseWarning, "FREEZING RAIN ADVISORY") !== false) { return"#DA70D6"; }
	if (strpos($ucaseWarning, "FREEZING SPRAY ADVISORY") !== false) { return"#00BFFF"; }
	if (strpos($ucaseWarning, "FROST ADVISORY") !== false) { return"#6495ED"; }
	if (strpos($ucaseWarning, "GALE WARNING") !== false) { return"#DDA0DD"; }
	if (strpos($ucaseWarning, "GALE WATCH") !== false) { return"#FFC0CB"; }
	if (strpos($ucaseWarning, "HARD FREEZE WARNING") !== false) { return"#0000FF"; }
	if (strpos($ucaseWarning, "HAZARDOUS MATERIALS WARNING") !== false) { return"#4B0082"; }
	if (strpos($ucaseWarning, "HAZARDOUS SEAS WARNING") !== false) { return"#D8BFD8"; }
	if (strpos($ucaseWarning, "HAZARDOUS SEAS WATCH") !== false) { return"#483D8B"; }
	if (strpos($ucaseWarning, "HAZARDOUS WEATHER OUTLOOK") !== false) { return"#EEE8AA"; }
	if (strpos($ucaseWarning, "HEAT ADVISORY") !== false) { return"#FF7F50"; }
	if (strpos($ucaseWarning, "HEAVY FREEZING SPRAY WARNING") !== false) { return"#00BFFF"; }
	if (strpos($ucaseWarning, "HEAVY FREEZING SPRAY WATCH") !== false) { return"#BC8F8F"; }
	if (strpos($ucaseWarning, "HIGH SURF ADVISORY") !== false) { return"#BA55D3"; }
	if (strpos($ucaseWarning, "HIGH SURF WARNING") !== false) { return"#228B22"; }
	if (strpos($ucaseWarning, "HIGH WIND WARNING") !== false) { return"#DAA520"; }
	if (strpos($ucaseWarning, "HIGH WIND WATCH") !== false) { return"#B8860B"; }
	if (strpos($ucaseWarning, "HURRICANE FORCE WIND WARNING") !== false) { return"#CD5C5C"; }
	if (strpos($ucaseWarning, "HURRICANE FORCE WIND WATCH") !== false) { return"#9932CC"; }
	if (strpos($ucaseWarning, "HURRICANE LOCAL STATEMENT") !== false) { return"#FFE4B5"; }
	if (strpos($ucaseWarning, "HURRICANE WARNING") !== false) { return"#DC143C"; }
	if (strpos($ucaseWarning, "HURRICANE WATCH") !== false) { return"#FF00FF"; }
	if (strpos($ucaseWarning, "HYDROLOGIC ADVISORY") !== false) { return"#00FF7F"; }
	if (strpos($ucaseWarning, "HYDROLOGIC OUTLOOK") !== false) { return"#90EE90"; }
	if (strpos($ucaseWarning, "ICE STORM WARNING") !== false) { return"#8B008B"; }
	if (strpos($ucaseWarning, "LAKE EFFECT SNOW ADVISORY") !== false) { return"#48D1CC"; }
	if (strpos($ucaseWarning, "LAKE EFFECT SNOW WARNING") !== false) { return"#008B8B"; }
	if (strpos($ucaseWarning, "LAKE EFFECT SNOW WATCH") !== false) { return"#87CEFA"; }
	if (strpos($ucaseWarning, "LAKE WIND ADVISORY") !== false) { return"#D2B48C"; }
	if (strpos($ucaseWarning, "LAKESHORE FLOOD ADVISORY") !== false) { return"#7CFC00"; }
	if (strpos($ucaseWarning, "LAKESHORE FLOOD STATEMENT") !== false) { return"#6B8E23"; }
	if (strpos($ucaseWarning, "LAKESHORE FLOOD WARNING") !== false) { return"#228B22"; }
	if (strpos($ucaseWarning, "LAKESHORE FLOOD WATCH") !== false) { return"#66CDAA"; }
	if (strpos($ucaseWarning, "LAW ENFORCEMENT WARNING") !== false) { return"#C0C0C0"; }
	if (strpos($ucaseWarning, "LOCAL AREA EMERGENCY") !== false) { return"#C0C0C0"; }
	if (strpos($ucaseWarning, "LOW WATER ADVISORY") !== false) { return"#A52A2A"; }
	if (strpos($ucaseWarning, "MARINE WEATHER STATEMENT") !== false) { return"#FFDAB9"; }
	if (strpos($ucaseWarning, "NUCLEAR POWER PLANT WARNING") !== false) { return"#4B0082"; }
	if (strpos($ucaseWarning, "RADIOLOGICAL HAZARD WARNING") !== false) { return"#4B0082"; }
	if (strpos($ucaseWarning, "RED FLAG WARNING") !== false) { return"#FF1493"; }
	if (strpos($ucaseWarning, "RIP CURRENT STATEMENT") !== false) { return"#40E0D0"; }
	if (strpos($ucaseWarning, "SEVERE THUNDERSTORM WARNING") !== false) { return"#FFA500"; }
	if (strpos($ucaseWarning, "SEVERE THUNDERSTORM WATCH") !== false) { return"#DB7093"; }
	if (strpos($ucaseWarning, "SEVERE WEATHER STATEMENT") !== false) { return"#00FFFF"; }
	if (strpos($ucaseWarning, "SHELTER IN PLACE WARNING") !== false) { return"#FA8072"; }
	if (strpos($ucaseWarning, "SHORT TERM FORECAST") !== false) { return"#98FB98"; }
	if (strpos($ucaseWarning, "SMALL CRAFT ADVISORY") !== false) { return"#D8BFD8"; }
	if (strpos($ucaseWarning, "SMALL CRAFT ADVISORY FOR HAZARDOUS SEAS") !== false) { return"#D8BFD8"; }
	if (strpos($ucaseWarning, "SMALL CRAFT ADVISORY FOR ROUGH BAR") !== false) { return"#D8BFD8"; }
	if (strpos($ucaseWarning, "SMALL CRAFT ADVISORY FOR WINDS") !== false) { return"#D8BFD8"; }
	if (strpos($ucaseWarning, "SMALL STREAM FLOOD ADVISORY") !== false) { return"#00FF7F"; }
	if (strpos($ucaseWarning, "SPECIAL MARINE WARNING") !== false) { return"#FFA500"; }
	if (strpos($ucaseWarning, "SPECIAL WEATHER STATEMENT") !== false) { return"#FFE4B5"; }
	if (strpos($ucaseWarning, "STORM WARNING") !== false) { return"#9400D3"; }
	if (strpos($ucaseWarning, "STORM WATCH") !== false) { return"#FFE4B5"; }
	if (strpos($ucaseWarning, "TEST") !== false) { return"#F0FFFF"; }
	if (strpos($ucaseWarning, "TORNADO WARNING") !== false) { return"#FF0000"; }
	if (strpos($ucaseWarning, "TORNADO WATCH") !== false) { return"#FFFF00"; }
	if (strpos($ucaseWarning, "TROPICAL DEPRESSION LOCAL STATEMENT") !== false) { return"#FFE4B5"; }
	if (strpos($ucaseWarning, "TROPICAL STORM LOCAL STATEMENT") !== false) { return"#FFE4B5"; }
	if (strpos($ucaseWarning, "TROPICAL STORM WARNING") !== false) { return"#B22222"; }
	if (strpos($ucaseWarning, "TROPICAL STORM WATCH") !== false) { return"#F08080"; }
	if (strpos($ucaseWarning, "TSUNAMI ADVISORY") !== false) { return"#D2691E"; }
	if (strpos($ucaseWarning, "TSUNAMI WARNING") !== false) { return"#FD6347"; }
	if (strpos($ucaseWarning, "TSUNAMI WATCH") !== false) { return"#FF00FF"; }
	if (strpos($ucaseWarning, "TYPHOON LOCAL STATEMENT") !== false) { return"#FFE4B5"; }
	if (strpos($ucaseWarning, "TYPHOON WARNING") !== false) { return"#DC143C"; }
	if (strpos($ucaseWarning, "TYPHOON WATCH") !== false) { return"#FF00FF"; }
	if (strpos($ucaseWarning, "URBAN AND SMALL STREAM FLOOD ADVISORY") !== false) { return"#00FF7F"; }
	if (strpos($ucaseWarning, "VOLCANO WARNING") !== false) { return"#2F4F4F"; }
	if (strpos($ucaseWarning, "WIND ADVISORY") !== false) { return"#D2B48C"; }
	if (strpos($ucaseWarning, "WIND CHILL ADVISORY") !== false) { return"#AFEEEE"; }
	if (strpos($ucaseWarning, "WIND CHILL WARNING") !== false) { return"#B0C4DE"; }
	if (strpos($ucaseWarning, "WIND CHILL WATCH") !== false) { return"#5F9EA0"; }
	if (strpos($ucaseWarning, "WINTER STORM WARNING") !== false) { return"#FF69B4"; }
	if (strpos($ucaseWarning, "WINTER STORM WATCH") !== false) { return"#4682B4"; }
	if (strpos($ucaseWarning, "WINTER WEATHER ADVISORY") !== false) { return"#7B68EE"; }
	
	// default value
	return "#FFCC00";
}

function warningBoxType($warning)
{
	$ucaseWarning = strtoupper($warning);

	if (strpos($ucaseWarning, "WARNING") !== false)
	{
		return "warningBox";
	}
	else if (strpos($ucaseWarning,"ADVISORY") !== false)
	{
		return "advisoryBox";
	}
	else
	{
		return "watchBox";
	}
}
?>
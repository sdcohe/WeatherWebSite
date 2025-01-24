function formatNumberDP(number)
{
	if (number == 3.4028235E38 || number == 1.4E-45)
	{
		return "---";
	}
	else
	{
		return number.toFixed(numberDecimalPlaces);
	}
}

function getWindDirStr(input)
{
	var direction = [" N ", "NNE", "NE ", "ENE", " E ", "ESE", "SE ", "SSE", " S ", "SSW",
		"SW ", "WSW", " W ", "WNW", "NW ", "NNW", " ---"];

	// valid inputs 0 thru 16
	if (input < 0 || input >= 16)
	{
		input = 16;
	}

	return direction[input];
}

function pressureTrendInInchesToLiteral(trend)
{
	var value = "";

	if (trend >= 0.06)
	{
		value = "rising rapidly";
	}
	else if (trend >= 0.02 && trend < 0.06)
	{
		value = "rising slowly";
	}
	else if (trend < 0.02 && trend > -0.02)
	{
		value = "steady";
	}
	else if (trend <= -0.02 && trend > -0.06)
	{
		value = "falling slowly";
	}
	else if (trend <= -0.06)
	{
		value = "falling rapidly";
	}

	return value;
}

function windSpeedToLiteralMPH(speedMPH)
{
	var value = "";

	if (speedMPH < 1.0)
	{
		value = "calm";
	}
	else if (speedMPH >= 1.0 && speedMPH < 4.0)
	{
		value = "light air";
	}
	else if (speedMPH >= 4.0 && speedMPH < 8.0)
	{
		value = "slight breeze";
	}
	else if (speedMPH >= 8.0 && speedMPH < 13.0)
	{
		value = "gentle breeze";
	}
	else if (speedMPH >= 13.0 && speedMPH < 19.0)
	{
		value = "moderate breeze";
	}
	else if (speedMPH >= 19.0 && speedMPH < 25.0)
	{
		value = "fresh breeze";
	}
	else if (speedMPH >= 25.0 && speedMPH < 32.0)
	{
		value = "strong breeze";
	}
	else if (speedMPH >= 32.0 && speedMPH < 39.0)
	{
		value = "moderate gale";
	}
	else if (speedMPH >= 39.0 && speedMPH < 47.0)
	{
		value = "fresh gale";
	}
	else if (speedMPH >= 47.0 && speedMPH < 55.0)
	{
		value = "strong gale";
	}
	else if (speedMPH >= 55 && speedMPH < 64.0)
	{
		value = "whole gale";
	}
	else if (speedMPH >= 64.0 && speedMPH < 75.0)
	{
		value = "storm";
	}
	else if (speedMPH >= 75.0)
	{
		value = "hurricane";
	}

	return value;
}

function getHourCount(numberOfHours, hourStartCounts, lastSampleDate, lastSampleValue)
{
	//alert("In 2nd func: Hours " + numberOfHours + " Last sample date: " + lastSampleDate + " Value: " + lastSampleValue);
	
	var currentIndex;
	var priorIndex;
	var value = 0.0;

	if (numberOfHours <= 24 && numberOfHours > 0)
	{
		//alert("setting indexes");
		// set current index to current hour if the day
		currentIndex = new Date(Date.parse(lastSampleDate)).getHours();
		priorIndex = currentIndex - (numberOfHours - 1);
		if (priorIndex < 0)
		{
			priorIndex += hourStartCounts.length;
		}
		//alert("Current index = " + currentIndex + " Prior index = " + priorIndex);

		var currentValue = lastSampleValue;
		var priorValue = hourStartCounts[priorIndex].CountValue();  //parens needed?

		while (priorValue == 1.4E-45 && priorIndex != currentIndex)
		{
			priorIndex++;
			priorIndex %= hourStartCounts.length;
			priorValue = hourStartCounts[priorIndex].CountValue(); // parens needed?
		}

		if (currentValue != 1.4E-45)
		{
			value = currentValue - priorValue;
		}
	}

	return value;
}

function getHourlyAverageRatePerMinute(numberOfHours, hourStartCounts, lastSampleDate, lastSampleValue)
{
	// alert("In 1st func: Hours " + numberOfHours + " Last sample date: " + lastSampleDate + " Value: " + lastSampleValue);
	var countValue = getHourCount(numberOfHours, hourStartCounts, lastSampleDate, lastSampleValue);
	var rate = 0.0;

	// alert("Rate: " + rate + " Countvalue: " + countValue + " Num hrs " + numberOfHours);

	if (countValue != 1.4E-45)
	{
		rate = countValue / (numberOfHours * 60.0);
	}

	return rate;
}

function getRainData(weatherData, todayDate, todayStartCount, yesterdayStartCount)
{
	var todayDay = new Date(Date.parse(todayDate)).getDate();
	var sampleDay = new Date(Date.parse(weatherData.DateTime())).getDate();
	
	// if today subtract today start count
	if (sampleDay == todayDay)
	{
		return weatherData.RainCount() - todayStartCount;
	}
	else
	// else use yesterday start count
	{
		return weatherData.RainCount() - yesterdayStartCount;
	}
}

function getMaxRateHour(numberOfHours, maxRateHours, lastSampleDate)
{
	var value = -1.0;

	if (numberOfHours > 0 && numberOfHours <= maxRateHours.length)
	{
		var hour = new Date(Date.parse(lastSampleDate)).getHours();
		var index = hour;
		var i = 0;

		do
		{
			if (value < maxRateHours[index].Max())
			{
				value = maxRateHours[index].Max();
			}
			index--;
			if (index < 0) 
			{ 
				index += maxRateHours.length; 
			}
			i++;

		}
		while (i < numberOfHours - 1);

	}

	return value;
}

function HTMLSplitWords(strToSplit)
{
	var result = "";
	var i;
	var splitArray = strToSplit.split(" ");
	for(i = 0; i < splitArray.length; i++)
	{
		if (result != "")
		{
			 result += "<br />";
		}
		result += splitArray[i];
	}
	
	return result;
}

function getHiLoColor(hiLo)
{
	if (hiLo == "Lo")
	{
		return blueHighlight;
	}
	else
	{
		return redHighlight;
	}
}

function isWarningInEffect(warning)
{
	var ucaseWarning = warning.toUpperCase();
	
	return (ucaseWarning.indexOf("NO ACTIVE") < 0 && 
			ucaseWarning.indexOf("NO CURRENT ADVISORIES") < 0 && 
			ucaseWarning.indexOf("SHORT TERM FORECAST") < 0); // && 
			//ucaseWarning.indexOf("SPECIAL WEATHER STATEMENT") < 0);

}

function warningBoxColor(warning)
{
	var ucaseWarning = warning.toUpperCase();
	
	if (ucaseWarning.indexOf("911 TELEPHONE OUTAGE") >= 0) { return"#C0C0C0"; }
	if (ucaseWarning.indexOf("ADMINISTRATIVE MESSAGE") >= 0) { return"#FFFFFF"; }
	if (ucaseWarning.indexOf("AIR QUALITY ALERT") >= 0) { return"#808080"; }
	if (ucaseWarning.indexOf("AIR STAGNATION ADVISORY") >= 0) { return"#808080"; }
	if (ucaseWarning.indexOf("ARROYO AND SMALL STREAM FLOOD ADVISORY") >= 0) { return"#00FF7F"; }
	if (ucaseWarning.indexOf("ASHFALL ADVISORY") >= 0) { return"#696969"; }
	if (ucaseWarning.indexOf("ASHFALL WARNING") >= 0) { return"#A9A9A9"; }
	if (ucaseWarning.indexOf("AVALANCHE ADVISORY") >= 0) { return"#CD853F"; }
	if (ucaseWarning.indexOf("AVALANCHE WARNING") >= 0) { return"#1E90FF"; }
	if (ucaseWarning.indexOf("AVALANCHE WATCH") >= 0) { return"#F4A460"; }
	if (ucaseWarning.indexOf("BEACH HAZARDS STATEMENT") >= 0) { return"#40E0D0"; }
	if (ucaseWarning.indexOf("BLIZZARD WARNING") >= 0) { return"#FF4500"; }
	if (ucaseWarning.indexOf("BLIZZARD WATCH") >= 0) { return"#ADFF2F"; }
	if (ucaseWarning.indexOf("BLOWING DUST ADVISORY") >= 0) { return"#BDB76B"; }
	if (ucaseWarning.indexOf("BRISK WIND ADVISORY") >= 0) { return"#D8BFD8"; }
	if (ucaseWarning.indexOf("CHILD ABDUCTION EMERGENCY") >= 0) { return"#FFD700"; }
	if (ucaseWarning.indexOf("CIVIL DANGER WARNING") >= 0) { return"#FFB6C1"; }
	if (ucaseWarning.indexOf("CIVIL EMERGENCY MESSAGE") >= 0) { return"#FFB6C1"; }
	if (ucaseWarning.indexOf("COASTAL FLOOD ADVISORY") >= 0) { return"#7CFC00"; }
	if (ucaseWarning.indexOf("COASTAL FLOOD STATEMENT") >= 0) { return"#6B8E23"; }
	if (ucaseWarning.indexOf("COASTAL FLOOD WARNING") >= 0) { return"#228B22"; }
	if (ucaseWarning.indexOf("COASTAL FLOOD WATCH") >= 0) { return"#66CDAA"; }
	if (ucaseWarning.indexOf("DENSE FOG ADVISORY") >= 0) { return"#708090"; }
	if (ucaseWarning.indexOf("DENSE SMOKE ADVISORY") >= 0) { return"#F0E68C"; }
	if (ucaseWarning.indexOf("DUST STORM WARNING") >= 0) { return"#FFE4C4"; }
	if (ucaseWarning.indexOf("EARTHQUAKE WARNING") >= 0) { return"#8B4513"; }
	if (ucaseWarning.indexOf("EVACUATION - IMMEDIATE") >= 0) { return"#7FFF00"; }
	if (ucaseWarning.indexOf("EXCESSIVE HEAT WARNING") >= 0) { return"#C71585"; }
	if (ucaseWarning.indexOf("EXCESSIVE HEAT WATCH") >= 0) { return"#800000"; }
	if (ucaseWarning.indexOf("EXTREME COLD WARNING") >= 0) { return"#0000FF"; }
	if (ucaseWarning.indexOf("EXTREME COLD WATCH") >= 0) { return"#0000FF"; }
	if (ucaseWarning.indexOf("EXTREME FIRE DANGER") >= 0) { return"#E9967A"; }
	if (ucaseWarning.indexOf("EXTREME WIND WARNING") >= 0) { return"#FF8C00"; }
	if (ucaseWarning.indexOf("FIRE WARNING") >= 0) { return"#A0522D"; }
	if (ucaseWarning.indexOf("FIRE WEATHER WATCH") >= 0) { return"#FFDEAD"; }
	if (ucaseWarning.indexOf("FLASH FLOOD STATEMENT") >= 0) { return"#8B0000"; }
	if (ucaseWarning.indexOf("FLASH FLOOD WARNING") >= 0) { return"#8B0000"; }
	if (ucaseWarning.indexOf("FLASH FLOOD WATCH") >= 0) { return"#2E8B57"; }
	if (ucaseWarning.indexOf("FLOOD ADVISORY") >= 0) { return"#00FF7F"; }
	if (ucaseWarning.indexOf("FLOOD STATEMENT") >= 0) { return"#00FF00"; }
	if (ucaseWarning.indexOf("FLOOD WARNING") >= 0) { return"#00FF00"; }
	if (ucaseWarning.indexOf("FLOOD WATCH") >= 0) { return"#2E8B57"; }
	if (ucaseWarning.indexOf("FREEZE WARNING") >= 0) { return"#00FFFF"; }
	if (ucaseWarning.indexOf("FREEZE WATCH") >= 0) { return"#4169E1"; }
	if (ucaseWarning.indexOf("FREEZING FOG ADVISORY") >= 0) { return"#008080"; }
	if (ucaseWarning.indexOf("FREEZING RAIN ADVISORY") >= 0) { return"#DA70D6"; }
	if (ucaseWarning.indexOf("FREEZING SPRAY ADVISORY") >= 0) { return"#00BFFF"; }
	if (ucaseWarning.indexOf("FROST ADVISORY") >= 0) { return"#6495ED"; }
	if (ucaseWarning.indexOf("GALE WARNING") >= 0) { return"#DDA0DD"; }
	if (ucaseWarning.indexOf("GALE WATCH") >= 0) { return"#FFC0CB"; }
	if (ucaseWarning.indexOf("HARD FREEZE WARNING") >= 0) { return"#0000FF"; }
	if (ucaseWarning.indexOf("HAZARDOUS MATERIALS WARNING") >= 0) { return"#4B0082"; }
	if (ucaseWarning.indexOf("HAZARDOUS SEAS WARNING") >= 0) { return"#D8BFD8"; }
	if (ucaseWarning.indexOf("HAZARDOUS SEAS WATCH") >= 0) { return"#483D8B"; }
	if (ucaseWarning.indexOf("HAZARDOUS WEATHER OUTLOOK") >= 0) { return"#EEE8AA"; }
	if (ucaseWarning.indexOf("HEAT ADVISORY") >= 0) { return"#FF7F50"; }
	if (ucaseWarning.indexOf("HEAVY FREEZING SPRAY WARNING") >= 0) { return"#00BFFF"; }
	if (ucaseWarning.indexOf("HEAVY FREEZING SPRAY WATCH") >= 0) { return"#BC8F8F"; }
	if (ucaseWarning.indexOf("HIGH SURF ADVISORY") >= 0) { return"#BA55D3"; }
	if (ucaseWarning.indexOf("HIGH SURF WARNING") >= 0) { return"#228B22"; }
	if (ucaseWarning.indexOf("HIGH WIND WARNING") >= 0) { return"#DAA520"; }
	if (ucaseWarning.indexOf("HIGH WIND WATCH") >= 0) { return"#B8860B"; }
	if (ucaseWarning.indexOf("HURRICANE FORCE WIND WARNING") >= 0) { return"#CD5C5C"; }
	if (ucaseWarning.indexOf("HURRICANE FORCE WIND WATCH") >= 0) { return"#9932CC"; }
	if (ucaseWarning.indexOf("HURRICANE LOCAL STATEMENT") >= 0) { return"#FFE4B5"; }
	if (ucaseWarning.indexOf("HURRICANE WARNING") >= 0) { return"#DC143C"; }
	if (ucaseWarning.indexOf("HURRICANE WATCH") >= 0) { return"#FF00FF"; }
	if (ucaseWarning.indexOf("HYDROLOGIC ADVISORY") >= 0) { return"#00FF7F"; }
	if (ucaseWarning.indexOf("HYDROLOGIC OUTLOOK") >= 0) { return"#90EE90"; }
	if (ucaseWarning.indexOf("ICE STORM WARNING") >= 0) { return"#8B008B"; }
	if (ucaseWarning.indexOf("LAKE EFFECT SNOW ADVISORY") >= 0) { return"#48D1CC"; }
	if (ucaseWarning.indexOf("LAKE EFFECT SNOW WARNING") >= 0) { return"#008B8B"; }
	if (ucaseWarning.indexOf("LAKE EFFECT SNOW WATCH") >= 0) { return"#87CEFA"; }
	if (ucaseWarning.indexOf("LAKE WIND ADVISORY") >= 0) { return"#D2B48C"; }
	if (ucaseWarning.indexOf("LAKESHORE FLOOD ADVISORY") >= 0) { return"#7CFC00"; }
	if (ucaseWarning.indexOf("LAKESHORE FLOOD STATEMENT") >= 0) { return"#6B8E23"; }
	if (ucaseWarning.indexOf("LAKESHORE FLOOD WARNING") >= 0) { return"#228B22"; }
	if (ucaseWarning.indexOf("LAKESHORE FLOOD WATCH") >= 0) { return"#66CDAA"; }
	if (ucaseWarning.indexOf("LAW ENFORCEMENT WARNING") >= 0) { return"#C0C0C0"; }
	if (ucaseWarning.indexOf("LOCAL AREA EMERGENCY") >= 0) { return"#C0C0C0"; }
	if (ucaseWarning.indexOf("LOW WATER ADVISORY") >= 0) { return"#A52A2A"; }
	if (ucaseWarning.indexOf("MARINE WEATHER STATEMENT") >= 0) { return"#FFDAB9"; }
	if (ucaseWarning.indexOf("NUCLEAR POWER PLANT WARNING") >= 0) { return"#4B0082"; }
	if (ucaseWarning.indexOf("RADIOLOGICAL HAZARD WARNING") >= 0) { return"#4B0082"; }
	if (ucaseWarning.indexOf("RED FLAG WARNING") >= 0) { return"#FF1493"; }
	if (ucaseWarning.indexOf("RIP CURRENT STATEMENT") >= 0) { return"#40E0D0"; }
	if (ucaseWarning.indexOf("SEVERE THUNDERSTORM WARNING") >= 0) { return"#FFA500"; }
	if (ucaseWarning.indexOf("SEVERE THUNDERSTORM WATCH") >= 0) { return"#DB7093"; }
	if (ucaseWarning.indexOf("SEVERE WEATHER STATEMENT") >= 0) { return"#00FFFF"; }
	if (ucaseWarning.indexOf("SHELTER IN PLACE WARNING") >= 0) { return"#FA8072"; }
	if (ucaseWarning.indexOf("SHORT TERM FORECAST") >= 0) { return"#98FB98"; }
	if (ucaseWarning.indexOf("SMALL CRAFT ADVISORY") >= 0) { return"#D8BFD8"; }
	if (ucaseWarning.indexOf("SMALL CRAFT ADVISORY FOR HAZARDOUS SEAS") >= 0) { return"#D8BFD8"; }
	if (ucaseWarning.indexOf("SMALL CRAFT ADVISORY FOR ROUGH BAR") >= 0) { return"#D8BFD8"; }
	if (ucaseWarning.indexOf("SMALL CRAFT ADVISORY FOR WINDS") >= 0) { return"#D8BFD8"; }
	if (ucaseWarning.indexOf("SMALL STREAM FLOOD ADVISORY") >= 0) { return"#00FF7F"; }
	if (ucaseWarning.indexOf("SPECIAL MARINE WARNING") >= 0) { return"#FFA500"; }
	if (ucaseWarning.indexOf("SPECIAL WEATHER STATEMENT") >= 0) { return"#FFE4B5"; }
	if (ucaseWarning.indexOf("STORM WARNING") >= 0) { return"#9400D3"; }
	if (ucaseWarning.indexOf("STORM WATCH") >= 0) { return"#FFE4B5"; }
	if (ucaseWarning.indexOf("TEST") >= 0) { return"#F0FFFF"; }
	if (ucaseWarning.indexOf("TORNADO WARNING") >= 0) { return"#FF0000"; }
	if (ucaseWarning.indexOf("TORNADO WATCH") >= 0) { return"#FFFF00"; }
	if (ucaseWarning.indexOf("TROPICAL DEPRESSION LOCAL STATEMENT") >= 0) { return"#FFE4B5"; }
	if (ucaseWarning.indexOf("TROPICAL STORM LOCAL STATEMENT") >= 0) { return"#FFE4B5"; }
	if (ucaseWarning.indexOf("TROPICAL STORM WARNING") >= 0) { return"#B22222"; }
	if (ucaseWarning.indexOf("TROPICAL STORM WATCH") >= 0) { return"#F08080"; }
	if (ucaseWarning.indexOf("TSUNAMI ADVISORY") >= 0) { return"#D2691E"; }
	if (ucaseWarning.indexOf("TSUNAMI WARNING") >= 0) { return"#FD6347"; }
	if (ucaseWarning.indexOf("TSUNAMI WATCH") >= 0) { return"#FF00FF"; }
	if (ucaseWarning.indexOf("TYPHOON LOCAL STATEMENT") >= 0) { return"#FFE4B5"; }
	if (ucaseWarning.indexOf("TYPHOON WARNING") >= 0) { return"#DC143C"; }
	if (ucaseWarning.indexOf("TYPHOON WATCH") >= 0) { return"#FF00FF"; }
	if (ucaseWarning.indexOf("URBAN AND SMALL STREAM FLOOD ADVISORY") >= 0) { return"#00FF7F"; }
	if (ucaseWarning.indexOf("VOLCANO WARNING") >= 0) { return"#2F4F4F"; }
	if (ucaseWarning.indexOf("WIND ADVISORY") >= 0) { return"#D2B48C"; }
	if (ucaseWarning.indexOf("WIND CHILL ADVISORY") >= 0) { return"#AFEEEE"; }
	if (ucaseWarning.indexOf("WIND CHILL WARNING") >= 0) { return"#B0C4DE"; }
	if (ucaseWarning.indexOf("WIND CHILL WATCH") >= 0) { return"#5F9EA0"; }
	if (ucaseWarning.indexOf("WINTER STORM WARNING") >= 0) { return"#FF69B4"; }
	if (ucaseWarning.indexOf("WINTER STORM WATCH") >= 0) { return"#4682B4"; }
	if (ucaseWarning.indexOf("WINTER WEATHER ADVISORY") >= 0) { return"#7B68EE"; }

	// default value
	return "#FFCC00";
}

function warningBoxType(warning)
{
	var ucaseWarning = warning.toUpperCase();
	
	if (ucaseWarning.indexOf("WARNING") >= 0)
	{
		return "warningBox";
	}
	else if (ucaseWarning.indexOf("ADVISORY") >= 0) 
	{
		return "advisoryBox";
	}
	else  
	{
		return "watchBox";
	}
}

function getStringValue(data)
{
	try
	{
		return data[0];
	}
	catch ($e)
	{
		return "";
	}
}
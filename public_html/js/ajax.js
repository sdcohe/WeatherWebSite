function ajaxRefresh()
{
	// send request to server
	var loader = new net.ContentLoader("getWeatherData.php?"+new Date(), dataReady, displayError);
	loader.send();

	// update display to show request in progress
//	updateDisplay("Update in progress...");
	
	displayIcon(true);
	
	// update webcam thumbnail
	updateWebcam();
}

function updateWebcam()
{
	var webCamThumbnailField = document.getElementById('webcamThumbnail');
	if (webCamThumbnailField != null)
	{
		webCamThumbnailField.src = 'http://www.cloppermillweather.org/images/Front.jpg?' + new Date();
	}
}

function updateDisplay(messageText)
{
	var messageField = document.getElementById('statusText');
	if (messageField != null)
	{
		messageField.innerHTML = messageText;
	}
}

function displayIcon(display)
{
	var iconField = document.getElementById("loadericon");
	if (iconField != null)
	{
		if (display == false)
		{
			iconField.innerHTML="";
		}
		else
		{
			iconField.innerHTML='<img src="images/ajax-loader.gif"></img>';
		}
	}
}

function displayError()
{
	// set next interval
	setTimeout(ajaxRefresh, 15000);

	displayIcon(false);
	
	// update display to show error message
	 var message = "Error fetching data!"
			 +" readyState:" + this.req.readyState
			 +" status: " + this.req.status;

	 updateDisplay(message);
}

function dataReady(req)
{
	// retrieve data
//	var xmlDoc=this.req.responseXML;
	var xmlDoc = req.responseXML;

	// set next interval
	setTimeout(ajaxRefresh, 15000);

	displayIcon(false);

	// update display to show request complete
//	updateDisplay("Update complete");
	updateDisplay("");
	
	// get variables from XML in an associative array (actually a JS Object)
	var values = getValues(xmlDoc);
	
	// get all fields in HTML document to fill in
	var tags = getElementsByClass("ajax"); // returns HTMLCollection

	// for each fill in field
	for (var x = 0; x < tags.length; x++)
	{
		// get item and ID
		var item = tags[x];
		var id = item.id;
		
		// get value from associative array and fill in field
		if (id != null)
		{
			var value = values[id];
			if (value != item.innerHTML)
			{
				// set color to show change
				item.className = "ajax change";
			}
			else
			{
				// set color to show no change
				item.className = "ajax";
			}
			item.innerHTML = value;
		}
	}
}

function getValues(xmlDoc)
{
	var map = getLookup();
	
	var values = new Object();
	
	for (key in map)
	{
		try
		{
			var value = map[key];
//			values[value] = xmlDoc.getElementsByTagName(key)[0].childNodes[0].nodeValue;
			values[key] = xmlDoc.getElementsByTagName(value)[0].childNodes[0].nodeValue;
		}
		catch (err)
		{
			updateDisplay(err.message);
		}
	}
	
	return values;
}

function getLookup()
{
	var map = 
	{
			'hdrupdateDateTime' : 'DATETIME',
			'hdrtemp' : 'OUTDOORTEMPERATURE',
			'hdrhumidity' : 'OUTDOORHUMIDITY',
			'hdrbaroininches2dp' : 'PRESSURE',
			'hdravgspd' : 'AVERAGESPEED',
			'hdrdirlabel' : 'WINDDIRECTION',
			'hdrdayrnusa' : 'RAINFALL',
			
			'updateDateTime' : 'DATETIME',
			'temp' : 'OUTDOORTEMPERATURE',
			'tempnodp' : 'OUTDOORTEMPERATURENODP',
			'humidity' : 'OUTDOORHUMIDITY',
			'baroininches2dp' : 'PRESSURE',
			'gstspd' : 'WINDSPEED',
			'dirlabel' : 'WINDDIRECTION',
			'dayrnusa' : 'RAINFALL',
			'solar' : 'SOLAR',
			'lightning' : 'LIGHTNING',
			'wind0minuteago' : 'AVERAGEWINDSPEED',
			'avgdir0minuteago' : 'AVERAGEWINDDIRECTION',
			'bftspeedtext' : 'SPEEDTEXT',
			'dew' : 'DEWPOINT',
			'cloudheightfeet' : 'CLOUDHEIGHT',
			'wetbulb' : 'WETBULB',
			'feelslike' : 'FEELSLIKE',
			'maxtemp' : 'MAXOUTDOORTEMPDAY',
			'maxtempt' : 'MAXOUTDOORTEMPTIMEDAY',
			'mintemp' : 'MINOUTDOORTEMPDAY',
			'mintempt' : 'MINOUTDOORTEMPTIMEDAY',
			'tempchangelasthourfaren' : 'OUTDOORTEMPTREND',
			'highhum' : 'MAXOUTDOORHUMIDITYDAY',
			'highhumt' : 'MAXOUTDOORHUMIDITYTIMEDAY',
			'lowhum' : 'MINOUTDOORHUMIDITYDAY',
			'lowhumt' : 'MINOUTDOORHUMIDITYTIMEDAY',
			'humchangelasthour' : 'OUTDOORHUMIDITYTREND',
			'highbaro' : 'PRESSUREHIGH',
			'lowbaro' : 'PRESSURELOW',
			'highbarot' : 'PRESSUREHIGHDATE',
			'lowbarot' : 'PRESSURELOWDATE',
			'trend' : 'PRESSURETREND',
			'pressuretrendname' : 'PRESSURETRENDNAME',
			'avgspd' : 'AVERAGESPEED',
			'avgdir' : 'AVERAGEDIRECTION',
			'maxgsthr' : 'MAXGUSTHOUR',
			'maxgsthrdirectionletter' : 'MAXGUSTHOURDIRECTION',
			'maxgst' : 'MAXGUST',
			'maxgstdirectionletter' : 'MAXGUSTDIRECTION',
			'timeoflastrain' : 'TIMELASTRAIN',
			'dayswithnorain' : 'DAYSNORAIN',
			'monthrn' : 'MONTHRAIN',
			'hourrn' : 'HOURRAIN',
			'totalrainlast3hours' : 'RAINLASTTHREEHOURS',
			'solarmaxhr' : 'SOLARMAXHOUR',
			'solarmaxday' : 'SOLARMAXDAY',
			'solarchangelasthour' : 'SOLARCHANGELASTHOUR',
			'lightningmaxhr' : 'LIGHTNINGMAXHOUR',
			'lightningmax3hr' : 'LIGHTNINGMAXTHREEHOUR',
			'lightninghr' : 'LIGHTNINGHOUR',
			'lightning3hr' : 'LIGHTNINGTHREEHOUR',

			'temp0minuteago' : 'OUTDOORTEMPZEROMINUTEAGO',
			'temp5minuteago' : 'OUTDOORTEMPFIVEMINUTEAGO',
			'temp10minuteago' : 'OUTDOORTEMPTENMINUTEAGO',
			'temp15minuteago' : 'OUTDOORTEMPFIFTEENMINUTEAGO',
			'temp20minuteago' : 'OUTDOORTEMPTWENTYMINUTEAGO',
			'temp30minuteago' : 'OUTDOORTEMPTHIRTYMINUTEAGO',
			'temp45minuteago' : 'OUTDOORTEMPFORTYFIVEMINUTEAGO',
			'temp60minuteago' : 'OUTDOORTEMPSIXTYMINUTEAGO',
			'temp75minuteago' : 'OUTDOORTEMPSEVENTYFIVEMINUTEAGO',
			'temp90minuteago' : 'OUTDOORTEMPNINETYMINUTEAGO',
			'temp105minuteago' : 'OUTDOORTEMPONEHUNDREDFIVEMINUTEAGO',
			'temp120minuteago' : 'OUTDOORTEMPONEHUNDREDTWENTYMINUTEAGO',
	
			'wind0minuteago' : 'WINDZEROMINUTEAGO',
			'wind5minuteago' : 'WINDFIVEMINUTEAGO',
			'wind10minuteago' : 'WINDTENMINUTEAGO',
			'wind15minuteago' : 'WINDFIFTEENMINUTEAGO',
			'wind20minuteago' : 'WINDTWENTYMINUTEAGO',
			'wind30minuteago' : 'WINDTHIRTYMINUTEAGO',
			'wind45minuteago' : 'WINDFORTYFIVEMINUTEAGO',
			'wind60minuteago' : 'WINDSIXTYMINUTEAGO',
			'wind75minuteago' : 'WINDSEVENTYFIVEMINUTEAGO',
			'wind90minuteago' : 'WINDNINETYMINUTEAGO',
			'wind105minuteago' : 'WINDONEHUNDREDFIVEMINUTEAGO',
			'wind120minuteago' : 'WINDONEHUNDREDTWENTYMINUTEAGO',
	
			'dir0minuteago' : 'WINDDIRZEROMINUTEAGO',
			'dir5minuteago' : 'WINDDIRFIVEMINUTEAGO',
			'dir10minuteago' : 'WINDDIRTENMINUTEAGO',
			'dir15minuteago' : 'WINDDIRFIFTEENMINUTEAGO',
			'dir20minuteago' : 'WINDDIRTWENTYMINUTEAGO',
			'dir30minuteago' : 'WINDDIRTHIRTYMINUTEAGO',
			'dir45minuteago' : 'WINDDIRFORTYFIVEMINUTEAGO',
			'dir60minuteago' : 'WINDDIRSIXTYMINUTEAGO',
			'dir75minuteago' : 'WINDDIRSEVENTYFIVEMINUTEAGO',
			'dir90minuteago' : 'WINDDIRNINETYMINUTEAGO',
			'dir105minuteago' : 'WINDDIRONEHUNDREDFIVEMINUTEAGO',
			'dir120minuteago' : 'WINDDIRONEHUNDREDTWENTYMINUTEAGO',
	
			'avgdir0minuteago' : 'AVGDIRZEROMINUTEAGO',
			'avgdir5minuteago' : 'AVGDIRFIVEMINUTEAGO',
			'avgdir10minuteago' : 'AVGDIRTENMINUTEAGO',
			'avgdir15minuteago' : 'AVGDIRFIFTEENMINUTEAGO',
			'avgdir20minuteago' : 'AVGDIRTWENTYMINUTEAGO',
			'avgdir30minuteago' : 'AVGDIRTHIRTYMINUTEAGO',
			'avgdir45minuteago' : 'AVGDIRFORTYFIVEMINUTEAGO',
			'avgdir60minuteago' : 'AVGDIRSIXTYMINUTEAGO',
			'avgdir75minuteago' : 'AVGDIRSEVENTYFIVEMINUTEAGO',
			'avgdir90minuteago' : 'AVGDIRNINETYMINUTEAGO',
			'avgdir105minuteago' : 'AVGDIRONEHUNDREDFIVEMINUTEAGO',
			'avgdir120minuteago' : 'AVGDIRONEHUNDREDTWENTYMINUTEAGO',

			'gust0minuteago' : 'GUSTZEROMINUTEAGO',
			'gust5minuteago' : 'GUSTFIVEMINUTEAGO',
			'gust10minuteago' : 'GUSTTENMINUTEAGO',
			'gust15minuteago' : 'GUSTFIFTEENMINUTEAGO',
			'gust20minuteago' : 'GUSTTWENTYMINUTEAGO',
			'gust30minuteago' : 'GUSTTHIRTYMINUTEAGO',
			'gust45minuteago' : 'GUSTFORTYFIVEMINUTEAGO',
			'gust60minuteago' : 'GUSTSIXTYMINUTEAGO',
			'gust75minuteago' : 'GUSTSEVENTYFIVEMINUTEAGO',
			'gust90minuteago' : 'GUSTNINETYMINUTEAGO',
			'gust105minuteago' : 'GUSTONEHUNDREDFIVEMINUTEAGO',
			'gust120minuteago' : 'GUSTONEHUNDREDTWENTYMINUTEAGO',
	
			'hum0minuteago' : 'OUTDOORHUMIDITYZEROMINUTEAGO',
			'hum5minuteago' : 'OUTDOORHUMIDITYFIVEMINUTEAGO',
			'hum10minuteago' : 'OUTDOORHUMIDITYTENMINUTEAGO',
			'hum15minuteago' : 'OUTDOORHUMIDITYFIFTEENMINUTEAGO',
			'hum20minuteago' : 'OUTDOORHUMIDITYTWENTYMINUTEAGO',
			'hum30minuteago' : 'OUTDOORHUMIDITYTHIRTYMINUTEAGO',
			'hum45minuteago' : 'OUTDOORHUMIDITYFORTYFIVEMINUTEAGO',
			'hum60minuteago' : 'OUTDOORHUMIDITYSIXTYMINUTEAGO',
			'hum75minuteago' : 'OUTDOORHUMIDITYSEVENTYFIVEMINUTEAGO',
			'hum90minuteago' : 'OUTDOORHUMIDITYNINETYMINUTEAGO',
			'hum105minuteago' : 'OUTDOORHUMIDITYONEHUNDREDFIVEMINUTEAGO',
			'hum120minuteago' : 'OUTDOORHUMIDITYONEHUNDREDTWENTYMINUTEAGO',
			
			'baro0minuteago' : 'PRESSUREZEROMINUTEAGO',
			'baro5minuteago' : 'PRESSUREFIVEMINUTEAGO',
			'baro10minuteago' : 'PRESSURETENMINUTEAGO',
			'baro15minuteago' : 'PRESSUREFIFTEENMINUTEAGO',
			'baro20minuteago' : 'PRESSURETWENTYMINUTEAGO',
			'baro30minuteago' : 'PRESSURETHIRTYMINUTEAGO',
			'baro45minuteago' : 'PRESSUREFORTYFIVEMINUTEAGO',
			'baro60minuteago' : 'PRESSURESIXTYMINUTEAGO',
			'baro75minuteago' : 'PRESSURESEVENTYFIVEMINUTEAGO',
			'baro90minuteago' : 'PRESSURENINETYMINUTEAGO',
			'baro105minuteago' : 'PRESSUREONEHUNDREDFIVEMINUTEAGO',
			'baro120minuteago' : 'PRESSUREONEHUNDREDTWENTYMINUTEAGO',
	
			'rain0minuteago' : 'RAINZEROMINUTEAGO',
			'rain5minuteago' : 'RAINFIVEMINUTEAGO',
			'rain10minuteago' : 'RAINTENMINUTEAGO',
			'rain15minuteago' : 'RAINFIFTEENMINUTEAGO',
			'rain20minuteago' : 'RAINTWENTYMINUTEAGO',
			'rain30minuteago' : 'RAINTHIRTYMINUTEAGO',
			'rain45minuteago' : 'RAINFORTYFIVEMINUTEAGO',
			'rain60minuteago' : 'RAINSIXTYMINUTEAGO',
			'rain75minuteago' : 'RAINSEVENTYFIVEMINUTEAGO',
			'rain90minuteago' : 'RAINNINETYMINUTEAGO',
			'rain105minuteago' : 'RAINONEHUNDREDFIVEMINUTEAGO',
			'rain120minuteago' : 'RAINONEHUNDREDTWENTYMINUTEAGO',
			
			'solar0minuteago' : 'SOLARZEROMINUTEAGO',
			'solar5minuteago' : 'SOLARFIVEMINUTEAGO',
			'solar10minuteago' : 'SOLARTENMINUTEAGO',
			'solar15minuteago' : 'SOLARFIFTEENMINUTEAGO',
			'solar20minuteago' : 'SOLARTWENTYMINUTEAGO',
			'solar30minuteago' : 'SOLARTHIRTYMINUTEAGO',
			'solar45minuteago' : 'SOLARFORTYFIVEMINUTEAGO',
			'solar60minuteago' : 'SOLARSIXTYMINUTEAGO',
			'solar75minuteago' : 'SOLARSEVENTYFIVEMINUTEAGO',
			'solar90minuteago' : 'SOLARNINETYMINUTEAGO',
			'solar105minuteago' : 'SOLARONEHUNDREDFIVEMINUTEAGO',
			'solar120minuteago' : 'SOLARONEHUNDREDTWENTYMINUTEAGO',
	
			'maxtempyest' : 'MAXOUTDOORTEMPYESTERDAY',
			'maxtempyestt' : 'MAXOUTDOORTEMPTIMEYESTERDAY',
			'mintempyest' : 'MINOUTDOORTEMPYESTERDAY',
			'mintempyestt' : 'MINOUTDOORTEMPTIMEYESTERDAY',
			'mrecordhightemp' : 'MAXOUTDOORTEMPMONTH',
			'mrecordhightempmonth' : 'MAXOUTDOORTEMPMONTHMON',
			'mrecordhightempday' : 'MAXOUTDOORTEMPMONTHDAY',
			'mrecordhightempyear' : 'MAXOUTDOORTEMPMONTHYEAR',
			'mrecordlowtemp' : 'MINOUTDOORTEMPMONTH',
			'mrecordlowtempmonth' : 'MINOUTDOORTEMPMONTHMON',
			'mrecordlowtempday' : 'MINOUTDOORTEMPMONTHDAY',
			'mrecordlowtempyear' : 'MINOUTDOORTEMPMONTHYEAR',
			'yrecordhightemp' : 'MAXOUTDOORTEMPYEAR',
			'yrecordhightempmonth' : 'MAXOUTDOORTEMPYEARMON',
			'yrecordhightempday' : 'MAXOUTDOORTEMPYEARDAY',
			'yrecordhightempyear' : 'MAXOUTDOORTEMPYEARYEAR',
			'yrecordlowtemp' : 'MINOUTDOORTEMPYEAR',
			'yrecordlowtempmonth' : 'MINOUTDOORTEMPYEARMON',
			'yrecordlowtempday' : 'MINOUTDOORTEMPYEARDAY',
			'yrecordlowtempyear' : 'MINOUTDOORTEMPYEARYEAR',
			'daysTmaxgt30C' : 'DAYSOUTDOORTEMPGT30C',
			'daysTminlt0C' : 'DAYSOUTDOORTEMPLT0C',
			'daysTmaxgt25C' : 'DAYSOUTDOORTEMPGT25C',
			'daysTminltminus15C' : 'DAYSOUTDOORTEMPLTMINUS15C',
			
			'warmestdayonrecordmonth' : 'WARMESTDAYONRECORDMONTH',
			'warmestdayonrecordmonthdt' : 'WARMESTDAYONRECORDMONTHSATE',
			'coldestdayonrecordmonth' : 'COLDESTDAYONRECORDMONTH',
			'coldestdayonrecordmonthdt' : 'COLDESTDAYONRECORDMONTHDATE',
			'warmestnightonrecordmonth' : 'WARMESTNIGHTONRECORDMONTH',
			'warmestnightonrecordmonthdt' : 'WARMESTNIGHTONRECORDMONTHDATE',
			'coldestnightonrecordmonth' : 'COLDESTNIGHTONRECORDMONTH',
			'coldestnightonrecordmonthdt' : 'COLDESTNIGHTONRECORDMONTHDATE',
			'maxheat' : 'MAXHEATINDEX',
			'maxheatt' : 'MAXHEATINDEXTIME',
			'minwindch' : 'MINWINDCHILL',
			'minwindcht' : 'MINWINDCHILLTIME',
			'maxheatyest' : 'MAXHEATINDEXYESTERDAY',
			'maxheatyestt' : 'MAXHEATINDEXYESTERDAYTIME',
			'minchillyest' : 'MINWINDCHILLYESTERDAY',
			'minchillyestt' : 'MINWINDCHILLYESTERDAYTIME',
			'mrecordhighheatindex' : 'MAXHEATINDEXMONTH',
			'mrecordhighheatindexmonth' : 'MAXHEATINDEXMONTHMON',
			'mrecordhighheatindexday' : 'MAXHEATINDEXMONTHDAY',
			'mrecordhighheatindexyear' : 'MAXHEATINDEXMONTHYEAR',
			'mrecordlowchill' : 'MINWINDCHILLMONTH',
			'mrecordlowchillmonth' : 'MINWINDCHILLMONTHMON',
			'mrecordlowchillday' : 'MINWINDCHILLMONTHDAY',
			'mrecordlowchillyear' : 'MINWINDCHILLMONTHYEAR',
			'yrecordhighheatindex' : 'MAXHEATINDEXYEAR',
			'yrecordhighheatindexmonth' : 'MAXHEATINDEXYEARMON',
			'yrecordhighheatindexday' : 'MAXHEATINDEXYEARDAY',
			'yrecordhighheatindexyear' : 'MAXHEATINDEXYEARYEAR',
			'yrecordlowchill' : 'MINWINDCHILLYEAR',
			'yrecordlowchillmonth' : 'MINWINDCHILLYEARMON',
			'yrecordlowchillday' : 'MINWINDCHILLYEARDAY',
			'yrecordlowchillyear' : 'MINWINDCHILLYEARYEAR',
			
			'maxbaroyest' : 'MAXBAROMETERYESTERDAY',
			'maxbaroyestt' : 'MAXBAROMETERYESTERDAYDATE',
			'minbaroyest' : 'MINBAROMETERYESTERDAY',
			'minbaroyestt' : 'MINBAROMETERYESTERDAYDATE',
			'mrecordhighbaro' : 'MAXBAROMETERMONTH',
			'mrecordhighbaromonth' : 'MAXBAROMETERMONTHMON',
			'mrecordhighbaroday' : 'MAXBAROMETERMONTHDAY',
			'mrecordhighbaroyear' : 'MAXBAROMETERMONTHYEAR',
			'mrecordlowbaro' : 'MINBAROMETERMONTH',
			'mrecordlowbaromonth' : 'MINBAROMETERMONTHMON',
			'mrecordlowbaroday' : 'MINBAROMETERMONTHDAY',
			'mrecordlowbaroyear' : 'MINBAROMETERMONTHYEAR',
			'yrecordhighbaro' : 'MAXBAROMETERYEAR',
			'yrecordhighbaromonth' : 'MAXBAROMETERYEARMON',
			'yrecordhighbaroday' : 'MAXBAROMETERYEARDAY',
			'yrecordhighbaroyear' : 'MAXBAROMETERYEARYEAR',
			'yrecordlowbaro' : 'MINBAROMETERYEAR',
			'yrecordlowbaromonth' : 'MINBAROMETERYEARMON',
			'yrecordlowbaroday' : 'MINBAROMETERYEARDAY',
			'yrecordlowbaroyear' : 'MINBAROMETERYEARYEAR',
			'maxhumyest' : 'MAXOUTDOORHUMIDITYYESTERDAY',
			'maxhumyestt' : 'MAXOUTDOORHUMIDITYYESTERDAYDATE',
			'minhumyest' : 'MINOUTDOORHUMIDITYYESTERDAY',
			'minhumyestt' : 'MINOUTDOORHUMIDITYYESTERDAYDATE',
			'mrecordhighhum' : 'MAXOUTDOORHUMIDITYMONTH',
			'mrecordhighhummonth' : 'MAXOUTDOORHUMIDITYMONTHMON',
			'mrecordhighhumday' : 'MAXOUTDOORHUMIDITYMONTHDAY',
			'mrecordhighhumyear' : 'MAXOUTDOORHUMIDITYMONTHYEAR',
			'mrecordlowhum' : 'MINOUTDOORHUMIDITYMONTH',
			'mrecordlowhummonth' : 'MINOUTDOORHUMIDITYMONTHMON',
			'mrecordlowhumday' : 'MINOUTDOORHUMIDITYMONTHDAY',
			'mrecordlowhumyear' : 'MINOUTDOORHUMIDITYMONTHYEAR',
			'yrecordhighhum' : 'MAXOUTDOORHUMIDITYYEAR',
			'yrecordhighhummonth' : 'MAXOUTDOORHUMIDITYYEARMON',
			'yrecordhighhumday' : 'MAXOUTDOORHUMIDITYYEARDAY',
			'yrecordhighhumyear' : 'MAXOUTDOORHUMIDITYYEARYEAR',
			'yrecordlowhum' : 'MINOUTDOORHUMIDITYYEAR',
			'yrecordlowhummonth' : 'MINOUTDOORHUMIDITYYEARMON',
			'yrecordlowhumday' : 'MINOUTDOORHUMIDITYYEARDAY',
			'yrecordlowhumyear' : 'MINOUTDOORHUMIDITYYEARYEAR',

			'windruntoday' : 'WINDRUNDAY',
			'windruntodatethismonth' : 'WINDRUNMONTH',
			'windruntodatethisyear' : 'WINDRUNYEAR',
			'maxgstt' : 'MAXGUSTDATE',
			'maxavgspd' : 'MAXAVGSPEEDDAY',
			'maxavgdirectionletter' : 'MAXAVGSPEEDDAYDIR',
			'maxavgspdt' : 'MAXAVGSPEEDDATE',
			'maxgustyest' : 'MAXGUSTYESTERDAY',
			'maxgustyestdirectionletter' : 'MAXGUSTYESTERDAYDIR',
			'maxgustyestt' : 'MAXGUSTYESTERDAYDATE',
			'maxaverageyest' : 'MAXAVGSPEEDYESTERDAY',
			'maxaverageyestdirectionletter' : 'MAXAVGSPEEDYESTERDAYDIR',
			'maxaverageyestt' : 'MAXAVGSPEEDYESTERDAYDATE',
			'mrecordwindgust' : 'MAXGUSTMONTH',
			'mrecordwindgustdirectionletter' : 'MAXGUSTMONTHDIR',
			'mrecordhighgustmonth' : 'MAXGUSTMONTHMON',
			'mrecordhighgustday' : 'MAXGUSTMONTHDAY',
			'mrecordhighgustyear' : 'MAXGUSTMONTHYEAR',
			'mrecordwindspeed' : 'MAXSUSTAINEDWINDMONTH',
			'mrecordwindspeeddirectionletter' : 'MAXSUSTAINEDWINDDIRMONTH',
			'mrecordhighavwindmonth' : 'MAXAVERAGEWINDMONTHMON',
			'mrecordhighavwindday' : 'MAXAVERAGEWINDMONTHDAY',
			'mrecordhighavwindyear' : 'MAXAVERAGEWINDMONTHYEAR',
			'yrecordwindgust' : 'MAXGUSTYEAR',
			'yrecordwindgustdirectionletter' : 'MAXGUSTDIRYEAR',
			'yrecordhighgustmonth' : 'MAXGUSTYEARMON',
			'yrecordhighgustday' : 'MAXGUSTYEARDAY',
			'yrecordhighgustyear' : 'MAXGUSTYEARYEAR',
			'yrecordwindspeed' : 'MAXSUSTAINEDWINDYEAR',
			'yrecordwindspeeddirectionletter' : 'MAXSUSTAINEDWINDDIRYEAR',
			'yrecordhighavwindmonth' : 'MAXAVERAGEWINDYEARMON',
			'yrecordhighavwindday' : 'MAXAVERAGEWINDYEARDAY',
			'yrecordhighavwindyear' : 'MAXAVERAGEWINDYEARYEAR',
			
			'yesterdayrain' : 'RAINYESTERDAY',
			'totalrainlast3hours' : 'RAINLASTTHREEHOURS',
			'totalrainlast6hours' : 'RAINLASTSIXHOURS',
			'yearrn' : 'RAINYEAR',
			'totalrainlast24hours' : 'RAINLASTTWENTYFOURHOURS',
			'currentrainrate' : 'CURRENTRAINRATE',
			'dayswithnorain' : 'DAYSNORAIN',
			'maxrainminlasthour' : 'MAXRAINRATEHOUR',
			'dayswithrainweek' : 'DAYSRAINWEEK',
			'maxrainminlast6hours' : 'MAXRAINRATESIXHOURS',
			'dayswithrain' : 'DAYSRAIN',
			'maxrainminlast24hours' : 'MAXRAINRATETWENTYFOURHOURS',
			'dayswithrainyear' : 'DAYSRAINYEAR',
			'recordhighrainweek' : 'MAXRAINWEEK',
			'recordhighrainweekdt' : 'MAXRAINWEEKDATE',
			'recordhighrainrateweek' : 'MAXRAINRATEWEEK',
			'recordhighrainrateweekdt' : 'MAXRAINRATEWEEKDATE',
			'recordhighrainmth' : 'MAXRAINMONTH',
			'recordhighrainmthdt' : 'MAXRAINMONTHDATE',
			'recordhighrainratemth' : 'MAXRAINRATEMONTH',
			'recordhighrainratemthdt' : 'MAXRAINRATEMONTHDATE',
			'recordhighrainyr' : 'MAXRAINYEAR',
			'recordhighrainyrdt' : 'MAXRAINYEARDATE',
			'recordhighrainrateyr' : 'MAXRAINRATEYEAR',
			'recordhighrainrateyrdt' : 'MAXRAINRATEYEARDATE',
			'recordhighrain' : 'MAXRAIN',
			'recordhighraindt' : 'MAXRAINDATE',
			'recordhighrainrate' : 'MAXRAINRATE',
			'recordhighrainratedt' : 'MAXRAINRATEDATE',
			
			'solarmaxday' : 'MAXSOLARDAY',
			'solarmaxdayt' : 'MAXSOLARDAYTIME',
			'solarmaxyest' : 'MAXSOLARYESTERDAY',
			'solarmaxyestt' : 'MAXSOLARYESTERDAYTIME',
			'solarmaxmonth' : 'MAXSOLARMONTH',
			'solarmaxmontht' : 'MAXSOLARMONTHTIME',
			'solarmaxyear' : 'MAXSOLARYEAR',
			'solarmaxyeart' : 'MAXSOLARYEARTIME',
			
			'lightningday' : 'LIGHTNINGDAY',
			'lightningrate' : 'LIGHTNINGRATEDAY',
			'lightningyest' : 'LIGHTNINGYESTERDAY',
			'lightningratelasthour' : 'LIGHTNINGRATELASTHOUR',
			'lightningmonth' : 'LIGHTNINGMONTH',
			'lightningratelast6hours' : 'LIGHTNINGRATESIXHOURS',
			'lightningyear' : 'LIGHTNINGYEAR',
			'lightningratelast24hours' : 'LIGHTNINGRATETWENTYFOURHOURS',
			'lightningmaxweek' : 'MAXLIGHTNINGWEEK',
			'lightningmaxweekdt' : 'MAXLIGHTNINGWEEKDATE',
			'lightningmaxrateweek' : 'MAXLIGHTNINGRATEWEEK',
			'lightningmaxrateweekdt' : 'MAXLIGHTNINGRATEWEEKDATE',
			'lightningmaxmonth' : 'MAXLIGHTNINGMONTH',
			'lightningmaxmonthdt' : 'MAXLIGHTNINGMONTHDATE',
			'lightningmaxratemonth' : 'MAXLIGHTNINGRATEMONTH',
			'lightningmaxratemonthdt' : 'MAXLIGHTNINGRATEMONTHDATE',
			'lightningmaxyear' : 'MAXLIGHTNINGYEAR',
			'lightningmaxyeardt' : 'MAXLIGHTNINGYEARDATE',
			'lightningmaxrateyear' : 'MAXLIGHTNINGRATEYEAR',
			'lightningmaxrateyeardt' : 'MAXLIGHTNINGRATEYEARDATE',
			'lightningmax' : 'MAXLIGHTNINGCUM',
			'lightningmaxdt' : 'MAXLIGHTNINGCUMDATE',
			'lightningmaxrate' : 'MAXLIGHTNINGRATECUM',
			'lightningmaxratedt' : 'MAXLIGHTNINGRATECUMDATE',
			
	};
	
	return map;

}

function getElementsByClass(className) 
{
	// search all the tags and return the list with the specified class in it
	var elem = document.getElementsByTagName('*');
	
	var arr = new Array();
	var iarr = 0;
	
	for(var i = 0; i < elem.length; i++) 
	{
		var att = elem[i].getAttribute("class");

		if (att != null && att.indexOf(className) != -1)
//		if(att == className) 
		{
			arr[iarr] = elem[i];
			iarr++;
		}
	}

	return arr;

}
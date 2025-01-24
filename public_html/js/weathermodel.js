var wxDataModel;
var dataDone = false;
var statsDone = false;
var historyDone = false;
var forecastDone = false;
var advisoriesDone = false;
var hazardsDone = false;
var discussionDone = false;
var firstTime = true;

function initWeatherDataModel()
{
	console.log("initWeatherModel: Start");
	
	retrieveWeatherData();
	retrieveWeatherStats();
	retrieveWeatherHistory();
	retrieveNWSForecast();
	retrieveNWSAdvisories();
	retrieveHazards();
	retrieveNWSDiscussion();

	console.log("initWeatherModel: Done");
}

function initDoneHandler()
{
//	console.log("initDoneHandler");
//	console.log("dataDone " + dataDone + " statsDone " + statsDone + " historyDone " + historyDone + " forecastDone " + forecastDone 
//			+ " advisoriesDone " + advisoriesDone + " hazardsDone " + hazardsDone + " discussionDone " + discussionDone);
	
	// don't apply bindings until everything has been retrieved at least once
	if (dataDone == true 
			&& statsDone == true 
			&& historyDone == true 
			&& forecastDone == true 
			&& advisoriesDone == true 
			&& hazardsDone == true
			&& discussionDone == true
		)
	{
		try
		{
//			console.log("initDone:Applying bindings");
			ko.applyBindings(wxDataModel);
//			console.log("initDone:Done applying bindings");
			firstTime = false;
		}
		catch(e)
		{
			alert("Exception: " + e);
		}
	}
}

function updateDataModel(data)
{
	if (typeof wxDataModel == "undefined")
	{
		wxDataModel = ko.mapping.fromJS(data);
	}
	else
	{
		ko.mapping.fromJS(data, wxDataModel);
	}
}

function weatherDataReceived(data)
{
	console.log("weatherDataReceived");
	try
	{
		displayIcon(false);
		updateDataModel(data);
	}
	catch(e)
	{
		alert("Exception in weatherDataReceived() " + e);
	}
	
	try
	{
		if (dataDone == false)
		{
			dataDone = true;
			console.log("triggering data done");
			initDoneHandler();
		}
	}
	catch(e)
	{
		alert("Exception " + e);
	}
}

function weatherStatsReceived(data)
{
	console.log("weatherStatsReceived");
	displayIcon(false);
	updateDataModel(data);

	try
	{
		if (statsDone == false)
		{
			statsDone = true;
			initDoneHandler();
		}
	}
	catch(e)
	{
		alert("Exception " + e);
	}
}

function weatherHistoryReceived(data)
{
	console.log("weatherHistoryReceived");
	displayIcon(false);
	updateDataModel(data);
	
	try
	{
		if (historyDone == false)
		{
			historyDone = true;
			initDoneHandler();
		}
	}
	catch(e)
	{
		alert("Exception " + e);
	}
}

function weatherForecastReceived(data)
{
	console.log("weatherForecastReceived");
	displayIcon(false);
	updateDataModel(data);

	try
	{
		if (forecastDone == false)
		{
			forecastDone = true;
			initDoneHandler();
		}
	}
	catch(e)
	{
		alert("Exception " + e);
	}
	
	updateFavicon();
}

function weatherAdvisoriesReceived(data)
{
	console.log("weatherAdvisoriesReceived");
	displayIcon(false);
	updateDataModel(data);

	try
	{
		if (advisoriesDone == false)
		{
			advisoriesDone = true;
			initDoneHandler();
		}
	}
	catch(e)
	{
		alert("Exception " + e);
	}
}

function hazardsReceived(data)
{
	console.log("hazardsReceived");
	displayIcon(false);
	updateDataModel(data);

	try
	{
		if (hazardsDone == false)
		{
			hazardsDone = true;
			initDoneHandler();
		}
	}
	catch(e)
	{
		alert("Exception " + e);
	}
}

function weatherDiscussionReceived(data)
{
	console.log("discussionReceived");
	displayIcon(false);
	updateDataModel(data);

	try
	{
		if (discussionDone == false)
		{
			discussionDone = true;
			initDoneHandler();
		}
	}
	catch(e)
	{
		alert("Exception " + e);
	}
}

function retrieveWeatherData()
{
	displayIcon(true);
	$.getJSON('JSONWeatherData.php?' + new Date(), weatherDataReceived)
//		.done(function() { console.log('getJSON request succeeded!'); })
		.fail(function(jqXHR, textStatus, errorThrown) { console.log('getJSON request failed! ' + textStatus); })
//		.always(function() { console.log('getJSON request ended!'); });
}

function retrieveWeatherStats()
{
	displayIcon(true);
	$.getJSON('JSONWeatherStats.php?' + new Date(), weatherStatsReceived)
//		.done(function() { console.log('getJSON request succeeded!'); })
		.fail(function(jqXHR, textStatus, errorThrown) { console.log('getJSON request failed! ' + textStatus); })
//		.always(function() { console.log('getJSON request ended!'); });
}

function retrieveWeatherHistory()
{
	displayIcon(true);
	$.getJSON('JSONWeatherHistory.php?' + new Date(), weatherHistoryReceived)
//		.done(function() { console.log('getJSON request succeeded!'); })
		.fail(function(jqXHR, textStatus, errorThrown) { console.log('getJSON request failed! ' + textStatus); })
//		.always(function() { console.log('getJSON request ended!'); });
	
}

function retrieveNWSForecast()
{
	displayIcon(true);
	$.getJSON('JSONWeatherForecast.php?' + new Date(), weatherForecastReceived)
//		.done(function() { console.log('getJSON request succeeded!'); })
		.fail(function(jqXHR, textStatus, errorThrown) { console.log('getJSON request failed! ' + textStatus); })
//		.always(function() { console.log('getJSON request ended!'); });

	// updateFavicon();
}

function retrieveNWSAdvisories()
{
	displayIcon(true);
	$.getJSON('JSONWeatherAdvisories.php?' + new Date(), weatherAdvisoriesReceived)
//		.done(function() { console.log('getJSON request succeeded!'); })
		.fail(function(jqXHR, textStatus, errorThrown) { console.log('getJSON request failed! ' + textStatus); })
//		.always(function() { console.log('getJSON request ended!'); });

}

function retrieveHazards()
{
	displayIcon(true);
	$.getJSON('JSONHazards.php?' + new Date(), hazardsReceived)
//		.done(function() { console.log('getJSON request succeeded!'); })
		.fail(function(jqXHR, textStatus, errorThrown) { console.log('getJSON request failed! ' + textStatus); })
//		.always(function() { console.log('getJSON request ended!'); });

}

function retrieveNWSDiscussion()
{
	displayIcon(true);
	$.getJSON('JSONWeatherDiscussion.php?' + new Date(), weatherDiscussionReceived)
//		.done(function() { console.log('getJSON request succeeded!'); })
		.fail(function(jqXHR, textStatus, errorThrown) { console.log('getJSON request failed! ' + textStatus); })
//		.always(function() { console.log('getJSON request ended!'); });

}

function updateFavicon()
{
	// remove existing icon
	$("#favicon").remove();
				
	// add new icon
	$('<link id="favicon" type="image/x-icon" rel="shortcut icon" href="/favicon.ico?' + new Date() + '" />').appendTo('head');
		
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

function updateWebcam()
{
	var webCamThumbnailField = document.getElementById('webcamThumbnail');
	if (webCamThumbnailField != null)
	{
		webCamThumbnailField.src = 'http://www.cloppermillweather.org/images/Front.jpg?' + new Date();
	}
}

ko.bindingHandlers.fadeInText = {
	    'update': function(element, valueAccessor, allBindings, viewModel, bindingContext) {
//	        $(element).fadeOut();
	        ko.bindingHandlers.text.update(element, valueAccessor, allBindings, viewModel, bindingContext);
//	        $(element).fadeIn();
	    }
};

ko.bindingHandlers.slideVisible = {
		update: function(element, valueAccessor, allBindings, viewModel, bindingContext) {
	        // First get the latest data that we're bound to
	        var value = valueAccessor();
	 
	        // Next, whether or not the supplied model property is observable, get its current value
//	        var valueUnwrapped = ko.unwrap(value);
	        var valueUnwrapped = ko.utils.unwrapObservable(value);
	        
	        // Grab some more data from another binding property
	        var duration = allBindings.get('slideDuration') || 400; // 400ms is default duration unless otherwise specified
	 
	        // Now manipulate the DOM element
	        if (valueUnwrapped == true)
	            $(element).slideDown(duration); // Make the element visible
	        else
	            $(element).slideUp(duration);   // Make the element invisible
		}
};


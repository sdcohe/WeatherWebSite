/**
 * 
 */

//var JSONData = "{\"AdvisoryPublicationDate\":\"2014-02-08T22:19:51-05:00\",\"HighestPriorityAdvisory\":\"There are no active watches, warnings or advisories\",\"nwsWarnings\":[{\"title\":\"There are no active watches, warnings or advisories\",\"summary\":\"\",\"description\":\"\",\"instruction\":\"\",\"date\":\"2014-02-08T22:19:51-05:00\"}],\"nwsWatches\":[],\"nwsAdvisories\":[],\"nwsStatements\":[],\"nwsOther\":[]}";
//var JSONData = "{\"HighestPriorityAdvisory\":\"There are no active watches, warnings or advisories\"}"
//var wxDataModel;

function ReservationsViewModel() {
	
    var self = this;

    // Editable data
    self.nwsStatements = ko.observableArray([
	  { title: "Standard (sandwich)", summary: "This is a test" },
      { title: "Premium (lobster)", summary: "Another test" },
      { title: "Ultimate (whole zebra)", summary: "Test 3"}
    ]);

}

function retrieveNWSAdvisories()
{
	ko.applyBindings(new ReservationsViewModel());
	
//	console.log("Retrieving weather data");
//	
//	$.getJSON('JSONWeatherAdvisories.php?' + new Date(), weatherAdvisoriesReceived)
//		.done(function() { console.log('getJSON request succeeded!'); })
//		.fail(function(jqXHR, textStatus, errorThrown) { console.log('getJSON request failed! ' + textStatus); })
//		.always(function() { console.log('getJSON request ended!'); });

}

function weatherAdvisoriesReceived(data)
{
	console.log("weatherAdvisoriesReceived");
	console.log(ko.toJSON(data));
	wxDataModel = ko.mapping.fromJS(data);

	try
	{
		ko.applyBindings(wxDataModel);
	}
	catch(e)
	{
		alert("Exception " + e);
	}
}

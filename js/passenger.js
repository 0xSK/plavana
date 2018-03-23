/**
* @Author: ananayarora
* @Date:   2017-05-03T18:14:11+05:30
* @Last modified by:   ananayarora
* @Last modified time: 2017-05-03T18:18:58+05:30
*/

var planeMarker;
var flightId;
var map;
var lat;
var lng;
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function getFlightLocation(id) {
	$.get("api/location.php?flight="+id, function(r){
		return r;
	});
}

function getFlightCoordinates(id) {
	$.get("api/coordinates.php?flight="+id, function(r){
		lat = r.split(',')[0];
		lng = r.split(',')[1];
		planeMarker.setPosition(new google.maps.LatLng(lat, lng));
	});
}

function getFlightTrail(id) {

	$.get("api/getTrail.php?flight="+id, function(r){
		var flightPlanCoordinates = jQuery.parseJSON(r);
    	var flightPath = new google.maps.Polyline({
	      path: flightPlanCoordinates,
	      geodesic: true,
	      strokeColor: '#FF0000',
	      strokeOpacity: 1.0,
	      strokeWeight: 2
    	});
    	flightPath.setMap(map);
	});
}

function getPOI(flightId) {
	swal("Please Wait", "Loading...","info");
	$.get("api/getPOI.php?flight="+flightId, function(r){
		swal({
			title: "Places of interest",
			text: r,
			type: "info",
			html: true
		});
	});
}


$(document).ready(function(){


	flightId = getParameterByName("flight");

	map = new google.maps.Map(document.getElementById('map'), {
	    center: {lat: 0, lng: 0},
		zoom: 3,
	    mapTypeId: 'satellite'
	});  	
	
	map.setTilt(60);

	getFlightCoordinates(flightId);

	planeMarker = new google.maps.Marker({
		position: new google.maps.LatLng(0,0),
		map: map
	});

	$(".poi").click(function(){
		getPOI(flightId);
	});

	$(".geo").click(function(){
		$(".pilot").show();
	});

	$(".close").click(function(){
		$(".pilot").hide();
	});

	$(".pilotinfo").click(function(){
		swal({
			title: "Pilot Info",
			text: 'Pilot – Ananay Arora <br /> Co-Pilot – Aditya Sengupta',
			type: "info",
			html: true
		});
	});

	$(".dys").click(function(){
		$(".sidebar").toggleClass("dys-style");
	});


	// Keep updating the flight info

	setInterval(function(){
		var location = getFlightLocation(flightId);
		$(".currentlocation").html(location);
	}, 4000);

	setInterval(function(){
		getFlightCoordinates(flightId);
	}, 1000);

	setInterval(function(){
		getFlightTrail(flightId);
	}, 4000);
	
});

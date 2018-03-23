<?php
/**
 * @Author: ananayarora
 * @Date:   2017-05-05 20:59:05
 * @Last Modified by:   ananayarora
 * @Last Modified time: 2017-05-05 21:40:01
 */

	require("getFlights.php");

	$f = new Flight();
	$flightArray = $f->getFlight($_GET['flight']);
	$coords = $f->getFlightCoordinates($flightArray);
	$poi = $f->getPlaces($coords['lat'],$coords['lng']);
	if ($poi == "") {
		echo "No places of interest nearby.";
	} else {
		echo $poi;
	}

?>
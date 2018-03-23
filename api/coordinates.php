<?php
/**
 * @Author: ananayarora
 * @Date:   2017-05-05 18:34:27
 * @Last Modified by:   ananayarora
 * @Last Modified time: 2017-05-05 18:36:17
 */

	require("getFlights.php");
	$f = new Flight();
	$id = $_GET['flight'];
	$flight = $f->getFlight($id);
	$array = $f->getFlightCoordinates($flight);
	echo $array['lat'].",".$array['lng'];

?>
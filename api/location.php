<?php
/**
 * @Author: ananayarora
 * @Date:   2017-05-05 18:22:25
 * @Last Modified by:   ananayarora
 * @Last Modified time: 2017-05-05 18:22:45
 */


	require("getFlights.php");
	$f = new Flight();
	$id = $_GET['flight'];
	$flight = $f->getFlight($id);
	echo $f->getFlightLocation($flight);

?>
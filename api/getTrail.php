<?php
/**
 * @Author: ananayarora
 * @Date:   2017-05-05 18:54:20
 * @Last Modified by:   ananayarora
 * @Last Modified time: 2017-05-05 19:03:54
 */

	require("getFlights.php");
	$f = new Flight();
	$id = $_GET['flight'];
	$flight = $f->getFlight($id);
	print_r($f->getTrail($flight));
?>
<?php
/**
 * @Author: ananayarora
 * @Date:   2017-05-05 17:36:40
 * @Last Modified by:   ananayarora
 * @Last Modified time: 2017-05-05 21:52:45
 */

	require("getFlights.php");
	$f = new Flight();
	$id = urlencode($_GET['name']);
	echo "<pre>";
	$f->placeInfo($id);
	echo "</pre>";
?>
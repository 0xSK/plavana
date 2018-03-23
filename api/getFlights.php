<?php

class Flight {
	
	public function getRandomFlight() {
		$url = "https://data-live.flightradar24.com/zones/fcgi/feed.js";
		$json = file_get_contents($url);
		$array = (array) json_decode($json);
		$random = array_rand($array);
		return $random;
	}

	public function getFlight($flight) {
		$url = "https://data-live.flightradar24.com/clickhandler/?version=1.5&flight=".$flight;
		$c = file_get_contents($url);
		return (array) json_decode($c);
	}

	public function getDestination($flightArray) {
		$country = $flightArray['airport']->destination->position->country->name;
		$city = $flightArray['airport']->destination->position->region->city;
		return $city . ", " . $country;
	}

	public function getFlightNumber($flightArray) {
		return $flightArray['identification']->callsign;
	}

	public function check($flightArray) {
		if (strtolower($flightArray['identification']->callsign) == "blocked") {
			return 0;
		} else {
			return 1;
		}
	}

	public function getFlightCoordinates($flightArray) {
		$loc['lat'] = $flightArray['trail'][0]->lat;
		$loc['lng'] = $flightArray['trail'][0]->lng;
		return $loc;
	}

	public function getFlightLocation($flightArray) {
		$lat = $flightArray['trail'][0]->lat;
		$lng = $flightArray['trail'][0]->lng;
		$url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$lng.'&sensor=true&key=AIzaSyDCobO4v0gsYoPKsodPJvgwuVLAi2rkM6A';
		$r = file_get_contents($url);
		$json = (array) json_decode($r);
		if (isset($json['results']) && sizeof($json['results']) >= 1) {
				return $json['results'][0]->formatted_address;
		} else {
			return "Water";
		}
	}

	public function getTrail($flightArray) {
		$trail = $flightArray['trail'];
		$trailCoordinates = Array();
		foreach ($trail as $key => $value) {
			$trailCoords['lat'] = $value->lat;
			$trailCoords['lng'] = $value->lng;
			array_push($trailCoordinates, $trailCoords);
		}
		return json_encode($trailCoordinates);
	}

	public function getPlaces($lat, $lng) {
		$url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?key=AIzaSyDCobO4v0gsYoPKsodPJvgwuVLAi2rkM6A&location=".$lat.",".$lng."&radius=500";
		$arr = (array) json_decode(file_get_contents($url));
		$html = "";
		if ($arr['results'] == "") {
			return "No places of interest nearby.";
		} else {
			foreach ($arr['results'] as $key => $value) {
				$html .= "<b>".$value->name."</b>" .  " – " . $this->placeInfo(urlencode($value->name)) . "<br />";
			}
			return $html;
		}
	}

	public function placeInfo($name) {
		$data = file_get_contents("https://kgsearch.googleapis.com/v1/entities:search?query=".$name."&key=AIzaSyB8k27tf2sDv3Hh2Lk3RcZM1i1tv8BpGfQ&limit=1&indent=True");
		$json = json_decode($data);
		if (isset($json->itemListElement[0])) {
			if (isset($json->itemListElement[0]->result->detailedDescription->articleBody)) {
				return $json->itemListElement[0]->result->detailedDescription->articleBody;
			} else {
				return $json->itemListElement[0]->result->description;
			}
		}
	}

}

?>
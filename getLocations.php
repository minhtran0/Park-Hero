<?php

include_once 'global.php';

$success = true;
if (!isset($_SESSION['userid'])) {
	$success = false;
	header("Location: index.php");
	exit(0);
}

if ($success) {
	if (isset($_POST['lat'])) {
		$lat = $_POST['lat'];
		$lng = $_POST['lng'];
		$radius = $_POST['radius'];

		$query = "SELECT *, ( 3959 * acos( cos( radians(".$lat.") ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(".$lng.") ) + sin( radians(".$lat.") ) * sin( radians( lat ) ) ) ) AS distance FROM places HAVING distance < ".$radius." ORDER BY distance LIMIT 0 , 20";
		$result = $conn->query($query);

		$destinationArray = array();
		$time = array();
		$places = array();
		while ($row = $result->fetch_assoc()) {
			// Add to destination string and distance matrix for each destination
			$destinationArray[] = $row['lat'].','.$row['lng'];
			$places[] = $row;
		}
		$destinations = implode('|', $destinationArray);

		$url = 'https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&';
		$url .= 'origins='.$lat.','.$lng.'&destinations='.$destinations;
		$url .= '&mode=walking&key=AIzaSyD7-vyw15-bP7znVZNloW_lQZMZoTC66Hc';
		$response = json_decode(CallAPI('POST', $url));

		foreach ($response['rows']['elements'] as $elem) {
			$time[] = $elem['duration']['text'];

			$letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$count = 0;

			$post = "<div class=\"row item\">					<!-- START Content Row -->
				<div class=\"col-md-1 col1\">	<!-- Col 1 -->
					<div class=\"row label\">".
						substr($letters, $count, 1); $count++;
					."</div>
					<div class=\"row distance\">
						
					</div>
				</div>
				<div class=\"col-md-3 col2\">	<!-- Col 2 -->
					<div class=\"row price\"> 	<!-- price -->".
						'$'.$row['price'].'/hour';
					."</div>
					<div class=\"row rating\">	<!-- rating -->".
						$row['rating'].' stars';
					."</div>
				</div>
				<div class=\"col-md-8 col3\">	<!-- Col 3 -->
					<div class=\"row title\">	<!-- title -->".
						$row['title'];
					."</div>
					<div class=\"row description\">	<!-- description -->".
						$row['description'];
					."</div>
					<div class=\"row address\">	<!-- address -->".
						$row['address'];
					."</div>
				</div>
			</div>							<!-- END Content Row-->"
		}


	unset($_POST);
}
}

function CallAPI($method, $url, $data = false)
{
	$curl = curl_init();

	switch ($method)
	{
		case "POST":
		curl_setopt($curl, CURLOPT_POST, 1);

		if ($data)
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		break;
		case "PUT":
		curl_setopt($curl, CURLOPT_PUT, 1);
		break;
		default:
		if ($data)
			$url = sprintf("%s?%s", $url, http_build_query($data));
	}

    // Optional Authentication:
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($curl, CURLOPT_USERPWD, "username:password");

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	$result = curl_exec($curl);

	curl_close($curl);

	return $result;
}

?>
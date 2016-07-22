<?php

include_once 'global.php';

$success = true;
if (!isset($_SESSION['userid'])) {
	$success = false;
	header("Location: index.php");
	exit(0);
}

if ($success) {
	if (isset($_POST['submit'])) {
		$title = $_POST['title'];
		$description = $_POST['description'];
		$price = $_POST['price'];

		$url = 'https://maps.googleapis.com/maps/api/geocode/json?address'.implode('+',$_POST['address']).'=&key=AIzaSyD7-vyw15-bP7znVZNloW_lQZMZoTC66Hc';
		$response = json_decode(CallAPI('POST', $url));
		$lat = $response[0]['geometry']['lat'];
		$lng = $response[0]['geometry']['lng'];
		$address = $response[0]['formatted_address'];

		$query = "INSERT INTO places (title, description, address, lat, lng, price, rating, user_id, datetime_posted) VALUES ";
		if ($stmt = $conn->prepare($query."(?, ?, '$address', '$lat', '$lng', ?, 0, '".$_SESSION['userid']."', NOW())")) {
			$stmt->bind_param('ssss',$title,$description,$price);
			$stmt->execute();
			$stmt->close();
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
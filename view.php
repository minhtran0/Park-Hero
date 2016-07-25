<?php

require_once "global.php";

$success = true;
if (!isset($_SESSION['userid'])) {
	$success = false;
}
if (!$success) {
	header("Location: index.php");
	exit(0);
}

?>
<html>
<head>
	<title>park</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="main.js"></script>
	<link href='https://fonts.googleapis.com/css?family=Chivo' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
</head>
<body>
	<!-- Fixed navbar -->
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Project name</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li class="active"><a href="#">Home</a></li>
					<li><a href="#about">About</a></li>
					<li><a href="#contact">Contact</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="#">Action</a></li>
							<li><a href="#">Another action</a></li>
							<li><a href="#">Something else here</a></li>
							<li role="separator" class="divider"></li>
							<li class="dropdown-header">Nav header</li>
							<li><a href="#">Separated link</a></li>
							<li><a href="#">One more separated link</a></li>
						</ul>
					</li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="../navbar/">Default</a></li>
					<li><a href="../navbar-static-top/">Static top</a></li>
					<li class="active"><a href="./">Fixed top <span class="sr-only">(current)</span></a></li>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</nav>


	<div class="container">
		<div class="col-md-6">	<!-- Maps column -->
			<div id="map"></div>
		</div>
		<div class="col-md-6" id="contentbox">	<!-- List column -->

			<div class="container">
				<div class="col-md-6">	<!-- Maps column -->
					<div id="map"></div>
				</div>
				<div class="col-md-6" id="contentbox">	<!-- List column -->

					

				</div>								<!-- END List Column-->
			</div>

			<!-- Latest compiled and minified JavaScript -->
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
			<script>
	      // Note: This example requires that you consent to location sharing when
			// prompted by your browser. If you see the error "The Geolocation service
			// failed.", it means you probably did not give permission for the browser to
			// locate you.

			function initMap() {
				var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
				var labelIndex = 0;
				var map = new google.maps.Map(document.getElementById('map'), {
					center: {lat: -34.397, lng: 150.644},
					zoom: 14
				});
				var infoWindow = new google.maps.InfoWindow({map: map});
				var bounds = map.getBounds();

				var center = bounds.getCenter();
				var ne = bounds.getNorthEast();

				// r = radius of the earth in statute miles
				var r = 3963.0;  

				// Convert lat or lng from decimal degrees into radians (divide by 57.2958)
				var lat1 = center.lat() / 57.2958; 
				var lon1 = center.lng() / 57.2958;
				var lat2 = ne.lat() / 57.2958;
				var lon2 = ne.lng() / 57.2958;

				// distance = circle radius from center to Northeast corner of bounds
				var dis = r * Math.acos(Math.sin(lat1) * Math.sin(lat2) + 
					Math.cos(lat1) * Math.cos(lat2) * Math.cos(lon2 - lon1));


			  // Try HTML5 geolocation.
			  if (navigator.geolocation) {
			  	navigator.geolocation.getCurrentPosition(function(position) {
			  		var pos = {
			  			lat: position.coords.latitude,
			  			lng: position.coords.longitude
			  		};
			  		map.setCenter(pos);
			  		var marker = new google.maps.Marker({
			  			position: pos,
			  			map: map
			  		});
			  		$.ajax({
			  			url: 'getLocations.php',
			  			type: 'POST',
			  			data: {'lat':pos[lat], 'lng':pos[lng], 'radius':dis},
			  			dataType: "json",
			  			success: function(result){
			  				$.each(result, function(idx, pos){
			  					placeMarker(pos);
			  				});
			  			},
			  			error: function(xhr, desc, err) {
			  				console.log(xhr);
			  				console.log("Details: " + desc + "\nError:" + err);
			  			}
			  		});
			  	});
			  } else {
			    // Browser doesn't support Geolocation
			    handleLocationError(false, infoWindow, map.getCenter());
			}

			function placeMarker(location) {
				var marker = new google.maps.Marker({
					position: location, 
					label: labels[labelIndex++ % labels.length],
					map: map
				});
			}
		}

		function handleLocationError(browserHasGeolocation, infoWindow, pos) {
			infoWindow.setPosition(pos);
			infoWindow.setContent(browserHasGeolocation ?
				'Error: The Geolocation service failed.' :
				'Error: Your browser doesn\'t support geolocation.');
		}
	</script>
	<script async defer
	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7-vyw15-bP7znVZNloW_lQZMZoTC66Hc&callback=initMap">
</script>
</body>
</html>
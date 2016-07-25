<?php

require_once "global.php";

if (isset($_SESSION['userid'])) {
	header("Location: view.php");
	exit(1);
}

$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
	try {
    // Proceed knowing you have a logged in user who's authenticated.
		$user_profile = $facebook->api('/me');
	} catch (FacebookApiException $e) {
		error_log($e);
		$user = null;
	}
}

// Login or logout url will be needed depending on current user state.
if ($user) {
	$logoutUrl = $facebook->getLogoutUrl();
	header("Location: view.php");
} else {
	$loginUrl = $facebook->getLoginUrl(array(
		'scope' => 'public_profile, email'
		));
}
if ($user) {
	$_SESSION['account'] = 1;
}
// do a check for google+
else {
	$_SESSION['account'] = 3;
}
// SESSION['account']: 1 is facebook, 2 is google+, 3 is email
if (isset($_SESSION['account']) && $_SESSION['account'] > 0) {
	header("Location: view.php");
	exit(1);
}

?>

<html>
<head>
	<title>park</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<style type="text/css">
		body {
			background-image: url('urban.jpg');
			margin: 0;
			background-size: 100%;
			background-repeat:no-repeat;
			display: compact;
		}
		a:link, a:visited, a:hover, a:active {
			color: white;
			text-decoration: none;
		}
	</style>
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div id="wrapper" class="jumbotron">
				<div class="row header">
					<h1>Park</h1>
				</div>
				<hr>
				<div class="row facebook">
					<button type="button" class="btn btn-primary btn-lg"><?php echo "<a href=\"" . htmlspecialchars($loginUrl) . "\">Sign in with Facebook</a>"; ?></button>
				</div>
				<div class="row google">
					<button type="button" class="btn btn-primary btn-lg">Sign in with Google+</button>
				</div>
				<div class="row email">
					<button type="button" class="btn btn-primary btn-lg">Sign in with Email</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</body>
</html>
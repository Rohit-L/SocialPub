<?php
session_start();
require_once 'functions.php';

$userstr = ' (Guest)';

if (isset($_SESSION['user'])) {
	$user = $_SESSION['user'];
	$loggedin = TRUE;
	$userstr = " ($user)";
} else {
	$loggedin = FALSE;
}

echo <<<_END
<!DOCTYPE html>
 	<html lang="en">
 		<head>
 			<meta charset="utf-8">
 			<meta http-equiv="X-UA-Compatible" content='IE=edge'>
 			<meta name="viewport" content="width=device-width, initial-scale=1">
			<title>Social Pub$userstr</title>
			<link href="css/bootstrap.css" rel="stylesheet">
			<link href="css/bootstrap-theme.css" rel="stylesheet">
			<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
			<script src='js/bootstrap.js'></script>
			<script src='javascript.js'></script>
	</head>
	<body>
		<nav class="navbar navbar-default" style='margin-bottom: 0'>
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle Navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href='index.php'><span class="glyphicon glyphicon-link" aria-hidden="true"></span> Social Pub</a>
				</div>
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
_END;

if ($loggedin) {
	echo <<<_END
							<li><a href='members.php?view=$user'>Home</a></li>
							<li><a href='members.php'>Members</a></li>
							<li><a href='friends.php'>Friends</a></li>
							<li><a href='messages.php'>Messages</a></li>
							<li><a href='profile.php'>Edit Profile</a></li>
						</ul>
						<div class='nav navbar-right'>
						<a href='logout.php'><button type='button' class='btn btn-default navbar-btn'>Log out</button></a>
						<p class="navbar-text">Signed in as $user</p></div>
					</div> <!-- Collapse -->
				</div> <!-- Container -->
			</nav>
_END;
} else {
	echo <<<_END
							<li class="active"><a href='index.php'>Home</a></li>
						</ul>

						<form method='post' action='login.php' class="navbar-form navbar-right" role="search">
        					<div class="form-group">
        						<label class="sr-only">Username</label>
          						<input type="text" class="form-control" name='user' value='$user' placeholder="Username">
        					</div>
        					<div class="form-group">
        						<label class="sr-only">Username</label>
        						<input type='password' class="form-control" name='pass' value='$pass' placeholder='Password'>
        					</div>
        					<button type="submit" class="btn btn-success">Login</button>
      					</form>
					</div> <!-- Collapse -->
				</div> <!-- Container -->
			</nav>
_END;
}
?>
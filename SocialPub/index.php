<?php
session_start();
if (isset($_SESSION['user'])) {
	$user = $_SESSION['user'];
	header("Location: members.php?view=$user");
}
require_once 'header.php';

$error = $user = $pass = "";
if (isset($_POST['user'])) {
	$user = cleanString($_POST['user']);
	$pass = cleanString($_POST['pass']);
	$first = cleanString($_POST['first']);
	$second = cleanString($_POST['second']);

	if ($user == "" || $pass == "" || $first == "" || $second == "") {
		$error = "<div class='col-sm-4 alert alert-danger' role='alert'><b>Please try again: </b>Not all field were entered</div>";
	} 
	else {
		$result = mysqlQuery("SELECT * FROM members WHERE user = '$user'");

		if ($result->num_rows) {
			$error = "<div class='col-sm-4 alert alert-danger' role='alert'><b>Please try again: </b>That username already exists</div>";
		} else {
			mysqlQuery("INSERT INTO members VALUES ('$user', '$pass', '$first', '$second')");
			echo <<<_END
	<div class='jumbotron'>
	<div class='container'>
		<div class='row'><h2><span class='label label-success'>Congratulations, your account has been created!</span></h2></div>
		<div class='row'><h4>Please log in below to continue.</h4></div>

		<div class='row'>
		<form method='post' action='login.php' class='form-inline'>
			<div class='form-group'>
				<label class="sr-only">Username</label>
				<input type="text" class="form-control input-lg" maxlength='16' name='user' value='$user' placeholder="Username">
			</div>
			<div class='form-group'>
				<label class="sr-only">Password</label>
				<input type='password' class='form-control input-lg' maxlength='16' name='pass' placeholder='Password'>
			</div>
			<button type="submit" class="btn btn-success btn-lg">Log in</button>
		</form>
	</div>
	</div>
	</div>
_END;
require_once 'footer.php';
			die();
		}
	}
}

if(!$loggedin) {
	echo <<<_END
	<script>
		function checkUser(user) {
			if (user.value == '') {
				O('info').innerHTML = ''
				return
			}

			params = "user=" + user.value
			request = new ajaxRequest()
			request.open("POST", "checkuser.php", true)
			request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
			request.setRequestHeader("Content-length", params.length)
			request.setRequestHeader("Connection", "close")

			request.onreadystatechange = function() {
				if (this.readyState == 4) {
					if (this.status == 200) {
						if (this.responseTest != null) {
							O('info').innerHTML = this.responseText
						}
					}
				}
			}

			request.send(params)
		}

		function ajaxRequest() {
			try { var request = new XMLHttpRequest() }
			catch(e1) {
				try { request = new ActiveXObject("Msxml2.XMLHTTP") }
				catch(e2) {
					try { request = new ActiveXObject("Microsoft.XMLHTTP") }
					catch(e3) {
						request = false
					}
				}
			}
			return request
		}
	</script>
_END;
}

echo <<<_END
	<div class='jumbotron'>
		<div class='container'>
			<h1><span class='label label-success'>Welcome to Social Pub</span></h1>
			<div class='row'><div class='col-md-offset-1'><h2><small>A Super Simple Social Network</small></h2></div></div>
		</div>
	</div>

	<div class='container'>
		<div class='row'><h3><span class='label label-success'>Place Enter Your Details to Sign Up</span></h3></div><br>
		<div class='row'>$error</div>
		<div class='row'>
		<form method='post' action='index.php' class='form-horizontal'>
			<div class='form-group'>
				<label class='col-sm-2 control-label'>Firstname</label>
				<div class='col-sm-5'>
					<input type='text' class='form-control input-lg' name='first' maxlength='32' value='$first' placeholder='Enter Firstname'>
				</div>
			</div>
			<div class='form-group'>
				<label class='col-sm-2 control-label'>Lastname</label>
				<div class='col-sm-5'>
					<input type='text' class='form-control input-lg' name='second' maxlength='32' value='$second' placeholder='Enter Lastname'>
				</div>
			</div>
			<div class='form-group'>
				<label class="col-sm-2 control-label">Username</label>
				<div class='col-sm-5'>
					<input type="text" class="form-control input-lg" maxlength='16' name='user' value='$user' onBlur='checkUser(this)' placeholder="Enter Username">
					<span id='info'></span>
				</div>
			</div>
			<div class='form-group'>
				<label class="col-sm-2 control-label">Password</label>
				<div class='col-sm-5'>
					<input type='password' class='form-control input-lg' maxlength='16' name='pass' value='$pass' placeholder='Enter Password'>
				</div>
			</div>
			<div class='form-group'>
				<div class='col-sm-offset-2 col-sm-10'>				
					<button type="submit" class="btn btn-success btn-lg">Sign up</button>
				</div>
			</div>
		</form>
	</div>
_END;
require_once 'footer.php'
?>
</body>
</html>
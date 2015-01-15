<?php
session_start();
if (isset($_SESSION['user'])) {
	$user = $_SESSION['user'];
	header("Location: members.php?view=$user");
}
require 'functions.php';

$error = $user = $pass = "";
if (isset($_POST['user'])) {
	$user = cleanString($_POST['user']);
	$pass = cleanString($_POST['pass']);

	if ($user == "" || $pass == "") {
		$error = "<div class='col-sm-4 alert alert-danger' role='alert'><b>Please try again: </b>Not all field were entered</div>";
	} 
	else {
		$result = mysqlQuery("SELECT user,pass FROM members WHERE user='$user' AND pass='$pass'");
		if ($result->num_rows == 0) {
			$error = "<div class='col-sm-4 alert alert-danger' role='alert'>Username/Password Invalid</div>";
		} 
		else {
			$_SESSION['user'] = $user;
			$_SESSION['pass'] = $pass;
			header("Location: members.php?view=$user");
		}
	}
}

require_once 'header.php';

echo <<<_END
<div class='jumbotron'>
	<div class='container'>
		<div class='row'>$error</div>
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
<div class='container'>
_END;
require_once 'footer.php';
echo "</div>";

die();

?>

</body></html>

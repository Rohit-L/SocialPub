<?php
$sqlhost = 'SSSNDB.db.11269592.hostedresource.com';
$sqlname = 'SSSNDB';
$sqlusername = 'SSSNDB';
$sqlpassword = 'Sssn123#';
$connect = new mysqli($sqlhost, $sqlusername, $sqlpassword, $sqlname);
if ($connect->connect_error) die($connect->connect_error);
function createTable($name, $query) {
	mysqlQuery("CREATE TABLE IF NOT EXISTS $name($query)");
	echo "Table '$name' created or already exists.<br>";
}
function mysqlQuery($query) {
	global $connect;
	$result = $connect->query($query);
	if (!$result) die($connect->error);
	return $result;
}
function destroySession() {
	$_SESSION = array();
	if (session_id() != "" || isset($_COOKIE[session_name()])){
		setcookie(session_name(), '', time()-2592000, '/');
	}
	session_destroy();
}
function cleanString($var) {
	global $connect;
	$var = stripslashes(htmlentities(strip_tags($var)));
	return $connect->real_escape_string($var);
}
function showProfile($user) {
	$random = rand();
	if (file_exists("images/profiles/$user.jpg")) {
		echo <<<_END
		<div class='col-md-3'>
			<a href="members.php?view=$user" class="thumbnail">
				<img src='images/profiles/$user.jpg?random=$random' class='img-thumbnail'>
			</a>
		</div>
_END;
	}
	else {
		echo <<<_END
		<div class='col-md-4'>
			<a href="members.php?view=$user" class="thumbnail">
				<img src='images/placeholderimage/placeholder.jpg?random=$random' class='img-thumbnail'>
			</a>
		</div>
_END;
	}

	$result = mysqlQuery("SELECT * FROM profiles WHERE user='$user'");
	$names = mysqlQuery("SELECT firstname,lastname FROM members WHERE user='$user'");
	if ($names->num_rows) {
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$name = $names->fetch_array(MYSQLI_ASSOC);
		$firstname = $name['firstname'];
		$lastname = $name['lastname'];
		$bio = stripslashes($row['text']);

		echo <<<_END
		<div class='col-md-offset-1 col-md-7'>
			<div class='row'>
				<table class='table'>
					<thead>
						<tr>
							<th>Firstname</th>
							<th>Lastname</th>
							<th>Username</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>$firstname</td>
							<td>$lastname</td>
							<td>$user</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class='row'>
				<table class='table'>
					<thead>
						<tr>
							<th>Bio</th>
						</tr>
					</thead>
				</table>
				<p class='lead' style='white-space: pre-wrap'>$bio</p>
			</div>
		</div>
_END;
	}
}
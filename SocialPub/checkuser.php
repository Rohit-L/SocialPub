<?php
require_once 'header.php';

if (isset($_POST['user'])) {
	$user = cleanString($_POST['user']);
	$result = mysqlQuery("SELECT * FROM members WHERE user='$user'");

	if ($reuslt->num_rows) {
		echo "<span class='taken'">&nbsp;&#x2718; " . 
			"This username is taken</span>";
	} else {
		echo "<span class='available'">&nbsp;&#x2714; " .
			"This username is available</span>";
	}
}
?>
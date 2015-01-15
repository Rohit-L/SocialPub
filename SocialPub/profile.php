<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: index.php");
}

require_once 'header.php';

$result = mysqlQuery("SELECT * FROM profiles WHERE user='$user'");

/* ***EDIT BIO CODE*** */
if (isset($_POST['text'])) {
	$text = cleanString($_POST['text']);
	$text = preg_replace('/\s\s+/', ' ', $text);

	if ($result->num_rows) {
		mysqlQuery("UPDATE profiles SET text='$text' where user='$user'");
	} 
	else {
		mysqlQuery("INSERT INTO profiles VALUES('$user', '$text')");
	}
}
else {

	if ($result->num_rows) {
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$text = stripslashes($row['text']);
	} else {
		$text = "";
	}
}
/* ***** */

/* Code to View Profile */
echo <<<_END
<div class='container'>
  <div class='panel panel-default'>
    <div class='panel-heading'>
      <h2><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Your Profile</h2>
    </div>
    <div class='panel-body'>
      <div clas='row'>
_END;
        showProfile($user);
     
echo <<<_END
      </div>
    </div>
  </div>
_END;

$text = stripslashes(preg_replace('/\s\s+/', ' ', $text));

echo <<<_END
<div class='row'>
	<div class='col-md-6'>
		<form method='post' action='image_upload.php' enctype='multipart/form-data'>
			<div class='form-group'>
				<label><h4><span class="glyphicon glyphicon-open" aria-hidden="true"></span> Upload a Profile Image</h4></label>
				<input type='file' name='image'>
			</div>
			<button type="submit" class="btn btn-success btn-lg">Upload Image</button>
		</form>
	</div>
	<div class='col-md-6'>
		<form method='post' action='profile.php'>
			<div class='form-group'>
				<label><h4><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Edit Bio</h4></label>
				<textarea name='text' class='form-control' rows='3' maxlength='240'>$text</textarea>
			</div>
			<button type="submit" class="btn btn-success btn-lg">Update Bio</button>
		</form>
	</div>
</div>
_END;
require_once 'footer.php';
?>
</div>
</body>
</html>
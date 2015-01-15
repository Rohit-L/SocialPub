<?php
if(isset($_POST['user'])) {
	$user = $_POST['user'];
	$x = $_POST['x1'];
	$y = $_POST['y1'];
	$w = $_POST['w'];
	$h = $_POST['h'];

	$filename = "images/profiles/$user.jpg";
	$src = imagecreatefromjpeg($filename);

	$tmp = imagecreatetruecolor(225, 300);
	imagecopyresampled($tmp, $src, 0, 0, $x, $y, 225, 300, $w, $h);
	imagejpeg($tmp, $filename, 100);
	imagedestroy($tmp);
	imagedestroy($src);

}

header("Location: profile.php");

?>
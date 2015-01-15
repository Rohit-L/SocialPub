<?php
require_once 'header.php';
echo <<<_END
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.Jcrop.min.js"></script>
		<link rel="stylesheet" href="css/jquery.Jcrop.css" type="text/css" />
_END;

if(isset($_FILES['image']['name'])) {
	$saveto = "images/profiles/$user.jpg";
	move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
	$typeok = TRUE;

	switch($_FILES['image']['type']) {
		case "image/gif": $src = imagecreatefromgif($saveto); break;
		case "image/jpeg": $src = imagecreatefromjpeg($saveto); break;
		case "image/pjpeg": $src = imagecreatefromjpeg($saveto); break;
		case "image/png": $src = imagecreatefrompng($saveto); break;
		default: $typeok = FALSE; break;
	}

	if ($typeok) {
		list($w, $h) = getimagesize($saveto);

		$max = 900;
		$nw = $w;
		$nh = $h;

		if ($w > $h && $max < $w) {
			$nw = $max;
			$nh = ($max / $w) * $h;
		} elseif ($h > $w && $max < $h) {
			$nh = $max;
			$nw = ($max / $h) * $w;
		} elseif ($max < $w) {
			$nw = $nh = $max;
		}

	$tmp = imagecreatetruecolor($nw, $nh);
	imagecopyresampled($tmp, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
	imagejpeg($tmp, $saveto, 100);
	imagedestroy($tmp);
	imagedestroy($src);
	
	}
}
$random = rand();
echo <<<_END
<div class='container'>
	<div class='panel panel-default'>
		<div class='panel-heading'>
			<center>
				<h1 class='panel-title'>Crop Image</h1>
			</center>
		</div>
		<div class='panel-body'>
			<center>
				<img src="/images/profiles/$user.jpg?random=$random" id='target' class='img-thumbnail'>
			</center>
		</div>

<script language="Javascript">
    jQuery(function($) {
        $('#target').Jcrop({
        	onSelect: showCoords,
            onChange: showCoords,
        	aspectRatio: 3 / 4,
        	setSelect:   [ 10, 10, 70, 90 ]
        });
    });

	function showCoords(c) {
		jQuery('#x1').val(c.x);
		jQuery('#y1').val(c.y);
		jQuery('#w').val(c.w);
		jQuery('#h').val(c.h);
	};
</script>
		
		<div class='panel-footer'>
			<form method='post' action='image_crop.php'>
				<input type='hidden' name='x1' id='x1'>
				<input type='hidden' name='y1' id='y1'>
				<input type='hidden' name='w' id='w'>
				<input type='hidden' name='h' id='h'>
				<input type='hidden' name='user' value="$user">
				<button type="submit" class="btn btn-success btn-lg btn-block">Set Profile Picture</button>
			</form>
		</div>
	</div>
</div>
_END;
?>$

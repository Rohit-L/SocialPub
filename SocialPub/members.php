<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: index.php");
}
require_once 'header.php';

/****************************/
/** FOR A SPECIFIC PROFILE **/
/**************************/
if (isset($_GET['view'])) {

  /* CODE TO VIEW PROFILE */
  $view = cleanString($_GET['view']); 
  if ($view == $user) $name = "Your";
  else                $name = "$view's";
  echo <<<_END
    <div class='container'>
      <div class='panel panel-default'>
        <div class='panel-heading'>
          <h2><span class="glyphicon glyphicon-user" aria-hidden="true"></span> $name Profile</h2>
        </div>
        <div class='panel-body'>
          <div clas='row'>
_END;
            showProfile($view);        
  echo <<<_END
          </div>
        </div>
        <div class='panel-footer'>
          <a href='messages.php?view=$view'><button type="button" class="btn btn-default btn-lg btn-block">
            <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> View $name Messages</button></a>
        </div>
      </div>
_END;
	require_once 'footer.php';
	echo "</div>";
	die();
}

/**********************************/
/** IF VIEWING ALL OTHER MEMBERS **/
/**********************************/

if (isset($_GET['add'])) { // ADDING A FRIEND
  $add = cleanString($_GET['add']);

  $result = mysqlQuery("SELECT * FROM friends WHERE user='$add' AND friend='$user'"); // CURRENT USER IS FRIEND
  if (!$result->num_rows)
    mysqlQuery("INSERT INTO friends VALUES ('$add', '$user')");
}
elseif (isset($_GET['remove'])) { // REMOVING A FRIEND
  $remove = cleanString($_GET['remove']);
  mysqlQuery("DELETE FROM friends WHERE user='$remove' AND friend='$user'"); // CURRENT USER IS FRIEND
}

$result = mysqlQuery("SELECT user FROM members ORDER BY user");
$num    = $result->num_rows;

  echo <<<_END
    <div class='container'>
      <div class='panel panel-default'>
        <div class='panel-heading'>
          <h3><span class="glyphicon glyphicon-globe" aria-hidden="true"></span> Other Members</h3>
        </div>
        <div class='panel-body'>
        <div class='row'>
_END;
$random = rand();
  for ($j = 0 ; $j < $num ; ++$j) {
    $row = $result->fetch_array(MYSQLI_ASSOC);
    if ($row['user'] == $user) continue;
    echo "<div class='col-md-3'>";
    echo "<div class='thumbnail'>";
    $current = $row['user'];

    if (file_exists("images/profiles/$current.jpg")) {
    echo "<a href='members.php?view=$current'><img src='images/profiles/$current.jpg?random=$random'>";
    }
    else {
      echo "<a href='members.php?view=$current'><img src='images/placeholderimage/placeholder.jpg'></a>";
    }

    echo "<div class='caption'>";
    echo "<center><h5><a href='members.php?view=$current'>$current</a></h5>";
    $follow = "follow";

    $result1 = mysqlQuery("SELECT * FROM friends WHERE user='$current' AND friend='$user'"); // USER IS FOLLOWING
    $t1      = $result1->num_rows;
    $result2 = mysqlQuery("SELECT * FROM friends WHERE user='$user' AND friend='$current'"); // USER IS BEING FOLLOWED
    $t2      = $result2->num_rows;

    if (($t1 + $t2) > 1) echo "<h4><span class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></span> Mutual Friend</h4>";
    elseif ($t1)         echo "<h4><span class='glyphicon glyphicon-hand-left' aria-hidden='true'></span> Following</h4>";
    elseif ($t2)         echo "<h4><span class='glyphicon glyphicon-hand-right' aria-hidden='true'></span> Follower</h4>";
    else                 echo "<h4><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> No Connection</h4>";

    if (!$t1) echo "<p><a href='members.php?add=$current' class='btn btn-primary' role='button'>Follow</a></p>";
    else      echo "<p><a href='members.php?remove=$current' class='btn btn-primary' role='button'>Unfollow</a></p>";
    echo "</center></div></div></div>";
  }

echo <<<_END
          </div> <!-- row -->
        </div> <!-- panel-body -->
      </div> <!-- panel -->
_END;
require_once 'footer.php';
?>
    </div> <!-- container -->
  </body>
</html>

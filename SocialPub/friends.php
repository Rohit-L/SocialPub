<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: index.php");
}

require_once 'header.php';

if (isset($_GET['view'])) $view = sanitizeString($_GET['view']);
else                      $view = $user;

if ($view == $user) {
  $name1 = $name2 = "Your";
  $name3 =          "You are";
}
else {
  $name1 = "<a href='members.php?view=$view'>$view</a>'s";
  $name2 = "$view's";
  $name3 = "$view is";
}

// Uncomment this line if you wish the user’s profile to show here
// showProfile($view);

$followers = array(); // INITIALIZING ARRAYS
$following = array(); // INITIALIZING ARRAYS

/* SUPPLYING FOLLOWERS TO FOLLOWERS ARRAY */
$result = mysqlQuery("SELECT * FROM friends WHERE user='$view'");
$num    = $result->num_rows;
for ($j = 0 ; $j < $num ; ++$j) {
  $row           = $result->fetch_array(MYSQLI_ASSOC);
  $followers[$j] = $row['friend'];
}

/* SUPPLYING FOLLOWING TO FOLLOWING ARRAY */
$result = mysqlQuery("SELECT * FROM friends WHERE friend='$view'");
$num    = $result->num_rows;
for ($j = 0 ; $j < $num ; ++$j) {
  $row           = $result->fetch_array(MYSQLI_ASSOC);
  $following[$j] = $row['user'];
}

/* DIFFERING ARRAY TO FIND DISTING MUTUAL FRIENDS, FOLLOWERS, & FOLLOWING */
$mutual    = array_intersect($followers, $following);
$followers = array_diff($followers, $mutual);
$following = array_diff($following, $mutual);
$friends   = FALSE;
$random    = rand();

echo "<div class='container'>";


/******************************/
/** PANEL FOR MUTUAL FRIENDS **/
/******************************/
echo <<<_END
  <div class='row'>
    <div class='panel panel-default'>
      <div class='panel-heading'>
        <h3>Mutual Friends</h3>
      </div>
      <div class='panel-body'>
        <div class='row'>
_END;

if (sizeof($mutual)) {
    foreach($mutual as $friend) {
      echo "<div class='col-md-3'>";
      echo "<div class='thumbnail'>";

      if (file_exists("images/profiles/$friend.jpg")) {
        echo "<a href='members.php?view=$friend'><img src='images/profiles/$friend.jpg?random=$random'>";
      }
      else {
        echo "<a href='members.php?view=$friend'><img src='images/placeholderimage/placeholder.jpg'></a>";
      }
      echo "<div class='caption'>";
      echo "<center><h5><a href='members.php?view=$friend'>$friend</a></h5>";
      echo "<h4><span class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></span> Mutual Friend</h4>";
      echo "<p><a href='members.php?remove=$friend' class='btn btn-primary' role='button'>Unfollow</a></p>";
      echo "</center></div></div></div>";
    }
    $friends = TRUE;
}
else {
      echo "<div class='col-md-3'><h1><label class='label label-success'>No Mutual Friends</label></h1></div>";
}
echo "</div></div></div></div>";


/*************************/
/** PANEL FOR FOLLOWERS **/
/*************************/
echo <<<_END
  <div class='row'>
    <div class='panel panel-default'>
      <div class='panel-heading'>
        <h3>Followers</h3>
      </div>
      <div class='panel-body'>
        <div class='row'>
_END;

if (sizeof($followers)) {
    foreach($followers as $friend) {
      echo "<div class='col-md-3'>";
      echo "<div class='thumbnail'>";

      if (file_exists("images/profiles/$friend.jpg")) {
        echo "<a href='members.php?view=$friend'><img src='images/profiles/$friend.jpg?random=$random'>";
      }
      else {
        echo "<a href='members.php?view=$friend'><img src='images/placeholderimage/placeholder.jpg'></a>";
      }
      echo "<div class='caption'>";
      echo "<center><h5><a href='members.php?view=$friend'>$friend</a></h5>";
      echo "<h4><span class='glyphicon glyphicon-hand-right' aria-hidden='true'></span> Follower</h4>";
      echo "<p><a href='members.php?add=$friend' class='btn btn-primary' role='button'>Follow</a></p>";
      echo "</center></div></div></div>";
    }
    $friends = TRUE;
}
else {
      echo "<div class='col-md-3'><h1><label class='label label-success'>No Followers</label></h1></div>";
}
echo "</div></div></div></div>";


/*************************/
/** PANEL FOR FOLLOWING **/
/*************************/
echo <<<_END
  <div class='row'>
    <div class='panel panel-default'>
      <div class='panel-heading'>
        <h3>Following</h3>
      </div>
      <div class='panel-body'>
        <div class='row'>
_END;

if (sizeof($following)) {
    foreach($following as $friend) {
      echo "<div class='col-md-3'>";
      echo "<div class='thumbnail'>";

      if (file_exists("images/profiles/$friend.jpg")) {
        echo "<a href='members.php?view=$friend'><img src='images/profiles/$friend.jpg?random=$random'>";
      }
      else {
        echo "<a href='members.php?view=$friend'><img src='images/placeholderimage/placeholder.jpg'></a>";
      }
      echo "<div class='caption'>";
      echo "<center><h5><a href='members.php?view=$friend'>$friend</a></h5>";
      echo "<h4><span class='glyphicon glyphicon-hand-left' aria-hidden='true'></span> Following</h4>";
      echo "<p><a href='members.php?remove=$friend' class='btn btn-primary' role='button'>Unfollow</a></p>";
      echo "</center></div></div></div>";
    }
    $friends = TRUE;
}
else {
      echo "<div class='col-md-3'><h1><label class='label label-success'>You Are Not Following Anybody</label></h1></div>";
}
echo "</div></div></div></div>";
require_once 'footer.php';
?>

</div><br>
</body>
</html>

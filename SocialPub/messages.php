<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: index.php");
}

require_once 'header.php';

if (isset($_GET['view'])) $view = cleanString($_GET['view']);
else                      $view = $user;

if (isset($_POST['text'])) {
  $text = cleanString($_POST['text']);

  if ($text != "") {
    $pm   = substr(cleanString($_POST['pm']),0,1); //0 is public, 1 is private
    $time = time();
    mysqlQuery("INSERT INTO messages VALUES(NULL, '$user', '$view', '$pm', $time, '$text')");
  }
}

if ($view != "") {
  if ($view == $user) $name1 = $name2 = "Your";
  else {
    $name1 = "<a href='members.php?view=$view'>$view</a>'s";
    $name2 = "$view's";
  }

  if (isset($_GET['erase'])) {
    $erase = cleanString($_GET['erase']);
    mysqlQuery("DELETE FROM messages WHERE id=$erase AND recip='$user'");
  }

  echo <<<_END
    <div class='container'>
      <div class='panel panel-default'>
        <div class='panel-heading'>
          <h2><span class="glyphicon glyphicon-user" aria-hidden="true"></span> $name1 Profile</h2>
        </div>
        <div class='panel-body'>
          <div clas='row'>
_END;
            showProfile($view);
         
  echo <<<_END
          </div>
        </div>
      </div> <!-- Panel -->
      <div class='panel panel-default'>
        <div class='panel-heading'>
          <h3><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> $name1 Messages</h3>
        </div>
_END;

  $query  = "SELECT * FROM messages WHERE recip='$view' ORDER BY time DESC";
  $result = mysqlQuery($query);
  $num    = $result->num_rows;

  echo <<<_END
            <table class='table table-striped'>
              <thead>
                <tr>
                  <th>Time</th>
                  <th>Public/Private</th>
                  <th>User</th>
                  <th>Message</th>
_END;
  if ($user == $view) echo "<th>Erase</th>";
  echo <<<_END
                </tr>
              </thead>
              <tbody>
_END;
    
  for ($j = 0 ; $j < $num ; ++$j) {
    $row = $result->fetch_array(MYSQLI_ASSOC);

    if ($row['pm'] == 0 || $row['auth'] == $user || $row['recip'] == $user)
    {
      echo "<tr><td>" . date('M jS \'y g:ia:', $row['time']) . "</td>"; // Time

      if ($row['pm'] == 0) // Public or Private
        echo "<td>Public</td>";
      else
        echo "<td>Private</td>";

      // User
      echo "<td><a href='messages.php?view=" . $row['auth'] . "'>" . $row['auth']. "</a></td>";

      echo "<td>&quot;" . $row['message'] . "&quot;</td>"; // Message

      if ($row['recip'] == $user)
        echo "<td><a href='messages.php?view=$view&erase=" . $row['id'] . "'><span class='glyphicon glyphicon-trash' aria-hidden'true'></span></a></td></tr>";
    }
  }

  echo <<<_END
            </tbody>
          </table>
_END;
  echo "<div class='panel-body'>";
  if (!$num) echo "<h2><span class='label label-success'>No messages yet</span></h2><br>";
  echo "<a class='btn btn-success' href='messages.php?view=$view'><span class='glyphicon glyphicon-refresh' aria-hidden='true'></span> Refresh Messages</a>";

    echo <<<_END
    </div>
    <div class='panel-footer'>
    <form method='post' action='messages.php?view=$view'>
    <h4>Leave a Message <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></h4>
      <div class='row'>
        <div class='col-md-6'>
          <div class='form-group'>
            <textarea name='text' class='form-control' rows='3' placeholder='Write to $view'></textarea>
          </div>
        </div>
        <div class='col-md-6'>
          <div class='radio'>
            <label>
              <input type="radio" name="pm" value="0" checked='checked'> Public
            </label>
          </div>
          <div class='radio'>
            <label>
              <input type="radio" name="pm" value="1"> Private
            </label>
          </div>
        </div>
      </div>
    <button type="submit" class="btn btn-success btn-lg">Post Message</button>
    </form>
    </div> <!-- Panel Footer -->
    </div> <!-- Panel -->
_END;
}
require_once 'footer.php';
?>
  </div>
  </body>
</html>

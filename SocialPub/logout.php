<?php
  require_once 'functions.php';
  session_start();

  if (isset($_SESSION['user']))
  {
    destroySession();
  }
  header('Location: index.php');
?>

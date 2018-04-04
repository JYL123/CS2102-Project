<?php
  session_start();

  if(isset($_POST['logout'])) {
    echo "Logout Successfully ";
    session_unset();
    session_destroy();   // function that Destroys Session
    header("Location: index.php");
  }
?>

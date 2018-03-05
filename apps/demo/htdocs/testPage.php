<!DOCTYPE html>  
<head>
  <title>UPDATE PostgreSQL data with PHP</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style>li {list-style: none;}</style>
</head>
<body>
  <h2>Login Page</h2>
  <ul>
    <form name="display" action="testpage.php" method="POST" >
      <li>Insert the following information to login:</li>
      <li>username:</li>
      <li><input type="text" name="username" /></li>
      <li>password:</li>
      <li><input type="text" name="userpassword" /></li>
      <li><input type="submit" name="submit" /></li>
      <li><input type="submit" name="next" value="next" /></li>
    </form>
  </ul>
  <?php
  	// Connect to the database. Please change the password in the following line accordingly
    $db     = pg_connect("host=localhost port=5431 dbname=Project1 user=postgres password=psql");	
    $result = pg_query($db, "SELECT icnum FROM users where username = '$_POST[username]' and userpassword = '$_POST[userpassword]'");		// Query template
    $row    = pg_fetch_assoc($result);		// To store the result row

    if (!empty($row[icnum])) {
        echo "You have successfully logged in!";
        header('Location: AdminPage.php'); 
        exit();
    } else {
        echo "Did you sign up?";
    }
    
    ?>  
</body>
</html>

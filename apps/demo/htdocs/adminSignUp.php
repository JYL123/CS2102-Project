<!DOCTYPE html>  
<head>
  <title>UPDATE PostgreSQL data with PHP</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style>li {list-style: none;}</style>
</head>
<body>
  <h2>Admin Sign Up Page</h2>
  <ul>
    <form name="display" action="adminSignUp.php" method="POST" >
      <li>Insert the following information to sign up:</li>
      <li>username:</li>
      <li><input type="text" name="username" /></li>
      <li>password:</li>
      <li><input type="text" name="userpassword" /></li>
      <li>IC Number:</li>
      <li><input type="text" name="icnum" /></li>
      <li>First Name:</li>
      <li><input type="text" name="firstname" /></li>
      <li>Last Name:</li>
      <li><input type="text" name="lastname" /></li>
      <li>Email:</li>
      <li><input type="text" name="email" /></li>
      <li>Phone Number:</li>
      <li><input type="text" name="phonenum" /></li>

      <li><input type="submit" name="submit" /></li>
      <li><input type="submit" name="next" value="Jump to login page" /></li>
    </form>
  </ul>
  <?php
  	// Connect to the database. Please change the password in the following line accordingly
    $db = pg_connect("host=localhost port=5431 dbname=Project1 user=postgres password=psql");	
    
    if (isset($_POST['submit'])) {

    $result = pg_query($db, "INSERT INTO administrators (username, userpassword, icnum, firstname, lastname, email, phonenum) VALUES ('$_POST[username]', '$_POST[userpassword]', '$_POST[icnum]',  
    '$_POST[firstname]', '$_POST[lastname]', '$_POST[email]', '$_POST[phonenum]')");		// Query template
    
      if (!$result) {
          echo "Sorry, your sign up has failed. But you can try again!";
      } else {
          echo "You have successfully signed up! You can log in now, yay!";
      }
    }

    if (isset($_POST['next'])) {  
    //   header('Location: testpage.php'); 
    //   exit(); 
    }

    
    ?>  
</body>
</html>
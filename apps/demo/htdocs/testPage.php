<!DOCTYPE html>  
<head>
  <title>UPDATE PostgreSQL data with PHP</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style>li {list-style: none;}
  #nav {
  background-color: #C08374;
  border: 1px solid #A76358;
  text-align: center;
  }
  ul {
  list-style: none;
  display: inline-block;
  }
  ul li {
  float: none;
  margin: 0 20px;
  }
  ul li a {
  color: white;
  }
  </style>
  <link rel="stylesheet" href="background.css">
</head>
<body>
<div id="nav">
  <h2>Admin Login Page</h2>
</div>
<div align="center">
  <ul>
    <form name="display" action="testpage.php" method="POST" >
      <li>Insert the following information to login:</li>
      <li>username:</li>
      <li><input type="text" name="username" /></li>
      <li>password:</li>
      <li><input type="text" name="userpassword" /></li>
      <li><input type="submit" name="submit" /></li>
      <li><input type="submit" name="next" value="Sign Up" /></li>
    </form>
  </ul>
  </div>
  <?php
  	// Connect to the database. Please change the password in the following line accordingly
    $db     = pg_connect("host=localhost port=5431 dbname=Project1 user=postgres password=psql");	
    $result = pg_query($db, "SELECT icnum FROM administrators where username = '$_POST[username]' and userpassword = '$_POST[userpassword]'");		// Query template
    $row    = pg_fetch_assoc($result);		// To store the result row

    if (!empty($row[icnum])) {
        echo "You have successfully logged in!";
        header('Location: AdminPage.php'); 
        exit();
    } else {
        //echo "Did you sign up?";
        
    }

    if (isset($_POST['next'])) {  
      header('Location: adminSignUp.php'); 
      exit(); 
    }
    
    ?>  
</body>
</html>

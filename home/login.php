<?php
  require_once('connectvars.php');

  // Start the session
  session_start();

  // Clear the error message
  $error_msg = "";

  // If the user isn't logged in, try to log them in
  if (!isset($_SESSION['user_id'])) {
    if (isset($_POST['submit'])) {
      // Connect to the database
      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
      // Grab the user-entered log-in data
      $user_username = mysqli_real_escape_string($dbc, trim($_POST['username']));
      $user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));

      if (!empty($user_username) && !empty($user_password)) {
        // Look up the username and password in the database
        $query = "SELECT user_id,username,role_id FROM staff_login WHERE username = '$user_username' AND pwd = SHA('$user_password')";
        $data = mysqli_query($dbc, $query);

        if (mysqli_num_rows($data) == 1) {
          // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the home page
          $row = mysqli_fetch_array($data);
          $_SESSION['user_id'] = $row['user_id'];
          $_SESSION['username'] = $row['username'];
    		  $_SESSION['role_id'] = $row['role_id'];
          $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php?'.SID;
          header('Location: ' . $home_url);
        }
        else {
          // The username/password are incorrect so set an error message
          $error_msg = 'Sorry, you must enter a valid username and password to log in.';
        }
      }
      else {
        // The username/password weren't entered so set an error message
        $error_msg = 'Sorry, you must enter your username and password to log in.';
      }
    }
  }
?>

<html>
<head>
  <title>NASC Result Analysis</title>
  <link rel="stylesheet" type="text/css" href="s.css" />
  <style>
    body{
  }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
  <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />-->
</head>
<body>
  <center><meta name="viewport" content="width=device-width, initial-scale=1">
  <img src="/result/images/NASCLOGO2.png" align="center">
  <h1 align=center style="color:#0E3657;">NEHRU ARTS AND SCIENCE COLLEGE KANHANGAD, KASARAGOD</h1>
  <h1 align=center style="color:#0E3657; font-size:40px;">RESULT ANALYSIS SYSTEM</h1>
  <?php
    if (empty($_SESSION['user_id'])) {
      echo '<p class="error" align=center>' . $error_msg . '</p>';
  ?>
  <br>
  <div class="form" style="height: 100px; background:#b9d0fa; align:center;">
    <form method="post" class="login-form"  width="300px" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <div align=center>
      </div>
      <div class="input-container">
        <i style="padding-bottom:8%;" class="fa fa-user"></i>
        <input type="text" name="username" placeholder="username"  style="height: 40px;" value="<?php if (!empty($user_username)) echo $user_username; ?>" /><br /><br/>
      </div>
      <div class="input-container">
        <i style="padding-bottom:8%;" class="fa fa-lock"></i>
        <input type="password" name="password" placeholder="password" style="height: 40px;" required=""/><br/><br>
      </div>
      <button class="upload-button1" type="submit" value="Log In" name="submit">Login</button><br/>
    </form>
  </div> 
  <center>
<?php
  }
  else {
    echo('<p class="login" align=center>You are logged in as ' . $_SESSION['username'] . '.</p>');
    echo '<p align=center><a href="index.php">Go Home</a></p>';
  }
?>

</body>
</html>
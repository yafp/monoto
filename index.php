<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title>monoto notes</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="jumbotron.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">monoto</a>
        </div>
        <div class="navbar-collapse collapse">
        
        	<!-- Login Form -->
          <form class="navbar-form navbar-right" role="form" name="login" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
              <input type="text" placeholder="Email" class="form-control" name="username" required="required">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control" name="password" required="required">
            </div>
            <button type="submit" class="btn btn-success" name="doLogin">Sign in</button>
          </form>
          
          
          
        </div><!--/.navbar-collapse -->
      </div>
    </div>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
      <br><br>
      <!--
        <h1>Welcome to monoto</h1>
        
        <p>monoto is on open source web based notes software....</p>
        -->

        <center>
			<img src="images/random_logout/bomb.gif" width="400">
			</center>
			
			

			<!--
        <p><a class="btn btn-primary btn-lg" role="button">Learn more &raquo;</a></p>
        -->
      </div>
    </div>

    <div class="container">
    
    
      <!-- Example row of columns -->
      <!--
      <div class="row">
        <div class="col-md-4">
          <h2>Fulltext search</h2>
          
          <p>monoto notes is offering a full-text features search function over your entire text-based notes </p>

          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>

        </div>
        <div class="col-md-4">
          <h2>Rich text editor</h2>
          <p>CKEditor is a ready-for-use HTML text editor designed to simplify web content creation. It's a WYSIWYG editor that brings common word processor features directly to your web pages. Enhance your website experience with our community maintained editor.</p>

          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>

       </div>
        <div class="col-md-4">
          <h2>Open source</h2>
          <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>

          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>

        </div>
        
      </div>
      -->

      <hr>

      <footer>
        <?php include 'inc/footer.php'; ?>
      </footer>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
  </body>
</html>




<?php
	session_start();
	if($_SESSION['valid'] == 1)				// check if the user-session is valid or not
	{	
		header('Location: notes.php');		// if session is valid - redirect to main-notes interface.
	}
	else 									// no valid session - show login form
	{
		include 'inc/html_head.php';			// include the new header
	}
?>



<?php
//
// try to login
//
if ( isset($_POST["doLogin"]) ) 
{
	include 'conf/config.php';
	include 'inc/db.php';		// connect to db
	connectToDB();

	// get data
	$username = $_POST['username'];
	$password = $_POST['password'];
	$username = mysql_real_escape_string($username);
	$_SESSION['username'] = $username;									// add session-info
	$owner = $_SESSION['username'];
	
	
	// check if there is a user with matching data
	$query = "SELECT password, salt FROM m_users WHERE username = '$username';";
	$result = mysql_query($query);
	if(mysql_num_rows($result) < 1)  										//no such user exists
	{
	    header('Location: redirect.php');
	    echo '<script type="text/javascript">var n = noty({text: "Login failed.", type: "error"});</script>';
	}

	// user exists
	$userData = mysql_fetch_array($result, MYSQL_ASSOC);
	$hash = hash('sha256', $userData['salt'] . hash('sha256', $password) );

	// check if user-account is locked already cause it had 3 failed logins in a row
	$sql="SELECT failed_logins_in_a_row FROM m_users WHERE username='".$_SESSION['username']."'  ";
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result)) 					
	{
		$failCounterInARow = $row[0];
	}

	if($failCounterInARow < 3)		// try to login
	{
		//check for incorrect password
		if($hash != $userData['password']) 
		{
			// log incorrect login attempt - date
			$sql="UPDATE m_users SET date_last_login_fail = now() WHERE username='".$_SESSION['username']."' ";
			$result = mysql_query($sql);

			// get current fail-login-count
	    	$sql="SELECT failed_logins FROM m_users WHERE username='".$_SESSION['username']."'  ";
			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result)) 					
			{
				$failCounter = $row[0];
			}
			$failCounter = $failCounter +1;
			$failCounterInARow = $failCounterInARow +1;

			// update failcounter
			$sql="UPDATE m_users SET failed_logins='".$failCounter."' WHERE username='".$_SESSION['username']."' ";
			$result = mysql_query($sql);

			// update failcounterInARow - for account-lock-checking
			$sql="UPDATE m_users SET failed_logins_in_a_row='".$failCounterInARow."' WHERE username='".$_SESSION['username']."' ";
			$result = mysql_query($sql);

			// record to log - that we had a successfull user login
			$event = "login error";
			$details = "User: <b>".$username."</b> failed to login.";
			$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(),'$owner' )";
			$result = mysql_query($sql);

		    header('Location: redirect.php');									// redirect user 
		    echo '<script type="text/javascript">var n = noty({text: "Login failed.", type: "error"});</script>';
		}
		else //login successful
		{	
	    	$_SESSION['valid'] = 1;
	    	echo '<script language=javascript>$.cookie("lastAction", "Logged in.");</script>';	// store last Action in cookie

	    	// if user is admin - add the info to our session 
			$query = "SELECT is_admin FROM m_users WHERE username = '$username';";
			$result = mysql_query($query);
			while($row = mysql_fetch_array($result))
			{
				if($row[0] == 1)
				{ 
					$_SESSION['admin'] = 1; 
				}
			}

	    	// get current login-count
	    	$sql="SELECT login_counter FROM m_users WHERE username='".$_SESSION['username']."'  ";
			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result)) 					
			{
				$loginCounter = $row[0];
			}
			$loginCounter = $loginCounter +1;

			// check if its first login - if so: save the first login date to db
			if($loginCounter == 1)
			{
				$sql="UPDATE m_users SET date_first_login= now() WHERE username='".$_SESSION['username']."' ";
				$result = mysql_query($sql);
			}

			// update last login date
			$sql="UPDATE m_users SET date_last_login= now()  WHERE username='".$_SESSION['username']."' ";
			$result = mysql_query($sql);

			// update logincounter
			$sql="UPDATE m_users SET login_counter='".$loginCounter."' WHERE username='".$_SESSION['username']."' ";
			$result = mysql_query($sql);

	    	// record to log - that we had a successfull user login
			$event = "login";
			$details = "User: <b>".$username."</b> logged in successfully.";
			$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(),'$owner' )";
			$result = mysql_query($sql);

			// reset failedLoginsInARow entry in database
			$sql="UPDATE m_users SET failed_logins_in_a_row='0' WHERE username='".$_SESSION['username']."' ";
			$result = mysql_query($sql);

	    	echo '<script type="text/javascript">window.location="notes.php"</script>';		// whyever that works - but header not anymore. must be related to our header rework
	    	echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';
		}
	} 
	else 		// login is not possible anymore - admin must remove the login lock
	{
		echo '<script type="text/javascript">var n = noty({text: "Account is locked.", type: "error"});</script>';
		echo("<script language=javascript>alert('This account is locked, please contact your monoto-admin.');</script>");
	}
}
?>

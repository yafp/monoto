<?php
	session_start();
	if($_SESSION['valid'] == 1)				// check if the user-session is valid or not
	{	
		header('Location: notes.php');		// if session is valid - redirect to main-notes interface.
	}
	else 									// no valid session - show login form
	{
		include 'html_head.php';			// include the new header
?>
		<!-- continue the header -->
		<!-- ################### -->
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="css/table.css" />
		<link rel="stylesheet" type="text/css" href="css/page01.css" title="default" />
		<link rel="alternate stylesheet" type="text/css" href="css/page02.css" title="alt" />
		<!-- fade in on pageload via jquery -->
		<script type="text/javascript" language="javascript">
			$(document).ready(function(){	
				$('#page_effect').fadeIn(1500);
			});
		</script>
	</head>

	<!-- BODY -->
	<body id="dt_example">
		<div id="container">
			<!-- HEADER & NAV -->
			
			<?php include 'header.php'; ?>

			<!-- CONTENT -->
			<div id="noteContentCo">
			<?php
				include 'conf/config.php';	
				if($s_enable_maintenance_mode == true)			// show maintenance mode
				{
			?>
				<!-- MAINTENANCE -->
				<h2><a name="core">maintenance mode</a></h2>
					<table style="width: 100%">
						<tr><td style="text-align: center;">This monoto installation is currently in maintenance mode. User-logins are disabled. Shit happens.</td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td style="text-align: center;"><img src="images/icons/firefox1.png" width="70" title="monoto is only tested with Mozillas Firefox so far. If you realize issues feel free to report them via github." onmouseover="this.src='images/icons/firefox2.png'" onmouseout="this.src='images/icons/firefox1.png'"></td></tr>
						<tr><td>&nbsp;</td></tr>
					</table>
			<?php
				}
				else 											 // show real login form
				{
			?>
				<!-- LOGIN -->
				<h2><a name="core">login</a></h2>
				<form name="login" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
					<div id="page_effect" style="display:none;"> <!-- blend in -->
					<table style="width: 100%">
						<tr><td style="text-align: center;"><input type="text" name="username" placeholder="Username" /></td></tr>
						<tr><td style="text-align: center;"><input type="password" name="password" placeholder="Password" /></td></tr>
						<tr><td style="text-align: center;"><input type="submit" value="Login" name="doLogin" /></td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td style="text-align: center;"><img src="images/icons/firefox1.png" width="70" title="monoto is only tested with Mozillas Firefox so far. If you realize issues feel free to report them via github." onmouseover="this.src='images/icons/firefox2.png'" onmouseout="this.src='images/icons/firefox1.png'"></td></tr>
					</table>
					</div>
				</form>
				
			<?php
				}
			?>	
			<noscript>monoto heavily depends on Javascript, which seems to be disabled in your browser. consider enabling it to use monoto.</noscript>
			</div>

			<!-- SPACER -->
			<div class="spacer">&nbsp;</div>
		</div>

		<!--  FOOTER -->
		<?php include 'footer.php'; ?>
	</body>
</html>

<?php

}


//
// try to login
//
if ( isset($_POST["doLogin"]) ) 
{
	include 'conf/config.php';
	include 'scripts/db.php';		// connect to db
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
	}

	// user exists
	$userData = mysql_fetch_array($result, MYSQL_ASSOC);
	$hash = hash('sha256', $userData['salt'] . hash('sha256', $password) );

	//incorrect password
	if($hash != $userData['password']) 
	{
		//echo '<script type="text/javascript">alert("Error: Wrong password.")</script>';			// notify user about wrong password

		// log incorrect login attempt - date
		$sql="UPDATE m_users SET date_last_login_fail = now() WHERE username='".$_SESSION['username']."' ";
		$result = mysql_query($sql);

		// log failed logins via counter
		// get current fail-login-count
    	$sql="SELECT failed_logins FROM m_users WHERE username='".$_SESSION['username']."'  ";
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result)) 					
		{
			$failCounter = $row[0];
		}
		$failCounter = $failCounter +1;

		// update failcounter
		$sql="UPDATE m_users SET failed_logins='".$failCounter."' WHERE username='".$_SESSION['username']."' ";
		$result = mysql_query($sql);

		// record to log - that we had a successfull user login
		$event = "login error";
		$details = "User: <b>".$username."</b> failed to login.";
		$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(),'$owner' )";
		$result = mysql_query($sql);

	    header('Location: redirect.php');														// redirect user 
	}
	else //login successful
	{	
		session_start();
		session_regenerate_id (); 											//this is a security measure ..is it?
    	$_SESSION['valid'] = 1;
		ini_set('session.gc_maxlifetime', '3600');							// sec

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

    	//header('Location: notes.php');												// redirect to the main page ...seems broken right now ...whyever
    	echo '<script type="text/javascript">window.location="notes.php"</script>';		// whyever that works - but header not anymore. must be related to our header rework
	}
}
?>
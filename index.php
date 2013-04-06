<?php
	session_start();
	if($_SESSION['valid'] == 1)				// check if the user-session is valid or not
	{	
		header('Location: notes.php');		// if session is valid - redirect to main-notes interface.
	}
	else 									// no valid session - show login form
	{
		include 'inc/html_head.php';			// include the new header
?>
		<!-- continue the header -->
		<!-- ################### -->

		<!-- fade in on pageload via jquery -->
		<script type="text/javascript" language="javascript">
			$(document).ready(function(){	
				$('#page_effect').fadeIn(1500);
			});
		</script>
	</head>

	<body id="dt_example">
		<div id="container">
			<!-- HEADER & NAV -->
			<?php include 'inc/header.php'; ?>

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
					</table>
			<?php
				}
				else 											 // show real login form
				{
			?>
				<!-- LOGIN -->
				<h2><a name="core">login</a></h2>
				<form name="login" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
					<div id="page_effect" style="display:none;"> <!-- blend in -->
					<table style="width: 100%">
						<tr><td style="text-align: center;"><input type="text" name="username" placeholder="Username" required="required" /></td></tr>
						<tr><td style="text-align: center;"><input type="password" name="password" placeholder="Password" required="required" /></td></tr>
						<tr><td style="text-align: center;"><input type="submit" value="Login" name="doLogin" /></td></tr>
						<tr><td>&nbsp;</td></tr>
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
		<?php include 'inc/footer.php'; ?>
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
		}
		else //login successful
		{	
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

			// reset failedLoginsInARow entry in database
			$sql="UPDATE m_users SET failed_logins_in_a_row='0' WHERE username='".$_SESSION['username']."' ";
			$result = mysql_query($sql);

	    	echo '<script type="text/javascript">window.location="notes.php"</script>';		// whyever that works - but header not anymore. must be related to our header rework
		}
	} 
	else 		// login is not possible anymore - admin must remove the login lock
	{
		echo("<script language=javascript>alert('This account is locked, please contact your monoto-admin.');</script>");
	}
}
?>
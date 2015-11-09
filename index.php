<!DOCTYPE html>
<html lang="en">
	<head>
		<title>monoto notes</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" 	content="IE=edge">
		<meta name="viewport" 				content="width=device-width, initial-scale=1">
		<meta name="description" 			content="Welcome to monoto notes - a self-hostable web-based notes software">
		<meta name="keywords" 				content="note,notes,web,self-hostable,free,monoto,yafp,web-based,notes software">
		<meta name="author" 				content="florian poeck">
		<!-- CSS -->
		<link rel="shortcut icon" href="images/fav.ico" >
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/page01.css" >
		<!-- JS -->
		<script type="text/javascript" src="js/jquery/jquery-2.1.3.min.js"></script>
		<script type="text/javascript" src="js/noty/jquery.noty.js"></script>
		<script type="text/javascript" src="js/noty/layouts/topRight.js"></script>
		<script type="text/javascript" src="js/noty/themes/default.js"></script>
		<script type="text/javascript" src="js/monoto/m_initNoty.js"></script>
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
					<a class="navbar-brand" href="index.php"><img src="images/icons/monoto_logo_white.png" width="63" height="25"></a>
				</div>
				<div class="navbar-collapse collapse">
					<form class="navbar-form navbar-right" role="form" name="login" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
						<div class="form-group"><input type="text" placeholder="username" class="form-control" name="username" required="required"></div>
						<div class="form-group"><input type="password" placeholder="password" class="form-control" name="password" required="required"></div>
						<button type="submit" class="btn btn-success" name="doLogin">Sign in</button>
					</form>
				</div>
			</div>
		</div>

		<div class="jumbotron">
			<div class="container">
				<br><br>
				<?php
					require 'conf/config.php';
					$f_contents = file($s_quotes_file);  						// define quotes source
		 			$line = $f_contents[rand(0, count($f_contents) - 1)];		// get random line
		 			list($author, $quote) = explode(';', $line);				// split string into author and quote
				?>
					<blockquote><p><?php echo $quote; ?></p></blockquote>
					<cite><?php echo $author; ?></cite>
				</div>
			</div>

			<div class="container">
			<hr>
			<footer><?php require 'inc/footer.php'; ?></footer>
		</div>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>


<?php
	session_start();
	if (isset($_SESSION['valid']))
	{
		header('Location: notes.php');		// if session is valid - redirect to main-notes interface.
	}


//
// try to login
//
if (isset($_POST["doLogin"]) )
{
	require 'conf/config.php';
	require 'inc/helperFunctions.php';
	require 'inc/db.php';		// connect to db
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
		displayNoty("Login failed.","error");
	}
	else // user does not exist
	{
		// user exists
		$userData = mysql_fetch_array($result, MYSQL_ASSOC);
		$hash = hash('sha256', $userData['salt'] . hash('sha256', $password) );

		// check if user-account is locked already cause it had 3 failed logins in a row
		$sql="SELECT failed_logins_in_a_row FROM m_users WHERE username='".$_SESSION['username']."'  ";
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result))
		{
			$failCounterInARow = $row[0]; // get amount of failed-logins-in-a-row of this account
		}

		if($failCounterInARow < 3)		// try to login
		{
			if($hash != $userData['password']) 		//check for incorrect password
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

				displayNoty("Login failed.","error");
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

				// store language setting in session variable (#210)
				$query = "SELECT language FROM m_users WHERE username = '$username';";
				$result = mysql_query($query);
				while($row = mysql_fetch_array($result))
				{
					$_SESSION['lang'] = $row[0];
				}

				// store servers getText sitaution in session variable for later usage (#211)
				if (!function_exists("gettext")) // gettext is not installed - fallback
				{
					$_SESSION['getText'] = 0;
				}
				else // gettext is installed - translate
				{
					$_SESSION['getText'] = 1;
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
			displayNoty("Account is locked","error");
		}
	}
}
?>

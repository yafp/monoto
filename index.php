<?php

	session_start();

	// check if the user-session is valid or not
	if($_SESSION['valid'] == 1)
	{
		header('Location: notes.php');
	}
	else
	{
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" type="image/ico" href="http://www.yafp.de/favicon.ico" />
		
		<title>monoto - your webbased notes-keeper</title>
		<style type="text/css" title="currentStyle">
			@import "css/page.css";
			@import "css/table.css";
		</style>

		<!-- SHOW/HIDE DIV -->
		<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="js/m_showHide.js"></script>
	</head>
	
	
	<body id="dt_example">
		<div id="container">
			<!-- HEADER & NAV -->
			<?php include 'header.php'; ?>

			<div id="noteContentCo">
				
				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>

				<!-- CORE SETTINGS -->
				<h2><a name="core">login</a></h2>

				<img src="images/login.png" align="right" width="140px">

				<form name="login" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
    				Username: <input type="text" name="username" placeholder="Username" />
    				Password: <input type="password" name="password" placeholder="Password" />
    				<input type="submit" value="Login" name="doLogin" />
				</form>

				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>
			</div>

			<!--  FOOTER -->
			<?php include 'footer.php'; ?>

				</span>
			</div>
		</div>
	</body>
</html>



	<?php
	}


?>





<?php
//
// try to login
//
if ( isset($_POST["doLogin"]) ) 
{
	include 'conf/config.php';

	// connect to mysql
	$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db($mysql_db, $con);									// select db

	

	// get data
	$username = $_POST['username'];
	$password = $_POST['password'];
	$username = mysql_real_escape_string($username);

	$_SESSION['username'] = $username;									// add session-info

	// check if there is a user with matching data
	$query = "SELECT password, salt FROM m_users WHERE username = '$username';";
	$result = mysql_query($query);
	if(mysql_num_rows($result) < 1)  //no such user exists
	{
	    mysql_close($con);													// close sql connection
	    header('Location: redirect.php');
	}

	$userData = mysql_fetch_array($result, MYSQL_ASSOC);
	$hash = hash('sha256', $userData['salt'] . hash('sha256', $password) );

	//incorrect password
	if($hash != $userData['password']) 
	{
	    mysql_close($con);													// close sql connection
	    header('Location: redirect.php');
	}
	else //login successful
	{	
		session_start();
		session_regenerate_id (); //this is a security measure
    	$_SESSION['valid'] = 1;
    	$_SESSION['userid'] = $userid;

    	// get current login-count
    	$sql="SELECT login_counter FROM m_users WHERE username='".$_SESSION['username']."'  ";
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result)) 					
		{
			$loginCounter = $row[0];
		}
		$loginCounter = $loginCounter +1;

		// update logincounter
		$sql="UPDATE m_users SET login_counter='".$loginCounter."' WHERE username='".$_SESSION['username']."' ";
		$result = mysql_query($sql);

    	// record to log - that we had a successfull user login
    	// update m_log
		$event = "login";
		$details = "User: <b>".$username."</b> logged in successfully.";
		$sql="INSERT INTO m_log (event, details, activity_date) VALUES ('$event', '$details', now() )";
		$result = mysql_query($sql);

		mysql_close($con);													// close sql connection

    	header('Location: notes.php');
	}
}

?>
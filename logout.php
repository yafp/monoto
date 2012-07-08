<?php
	session_start();

	include 'conf/config.php';

	$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);		
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}

	$owner = $_SESSION['username'];

	mysql_select_db($mysql_db, $con);				// select db

    // record to log - that we had a successfull user logout
	// get current logout count-value
    $sql="SELECT logout_counter FROM m_users WHERE username='".$_SESSION['username']."'  ";
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result)) 					
	{
		$logoutCounter = $row[0];
	}
	$logoutCounter = $logoutCounter +1;

	// update logoutcounter
	$sql="UPDATE m_users SET logout_counter='".$logoutCounter."' WHERE username='".$_SESSION['username']."' ";
	$result = mysql_query($sql);


    // update m_log
    $username = "dummy";
	$event = "logout";
	$details = "User: <b>".$username."</b> logged out successfully.";
	//$sql="INSERT INTO m_log (event, details, activity_date) VALUES ('$event', '$details', now() )";
	$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(), '$owner' )";
	$result = mysql_query($sql);

	mysql_close($con);													// close sql connection

	$_SESSION = array(); //destroy all of the session variables
    session_destroy();
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
				<h2><a name="core">logout</a></h2>

				<img src="images/login.png" align="right">

				<!-- REDIRECT TO LOGIN -->
				<?php	header( "refresh:5;url=index.php" ); ?>

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

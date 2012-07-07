<?php
	session_start();

	include 'conf/config.php';

	$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);		
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}

	mysql_select_db($mysql_db, $con);				// select db

    // record to log - that we had a successfull user login

    // update m_log
    $username = "dummy";
	$event = "logout";
	$details = "User: <b>".$username."</b> logged out successfully.";
	$sql="INSERT INTO m_log (event, details, activity_date) VALUES ('$event', '$details', now() )";
	$result = mysql_query($sql);

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

<?php
	session_start();
	include 'conf/config.php';

	// connect to db
	include ('scripts/db.php');
	connectToDB();
	$owner = $_SESSION['username'];
	mysql_select_db($mysql_db, $con);				// select get

	// db current logout count-value
    $sql="SELECT logout_counter FROM m_users WHERE username='".$owner."'  ";
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
	$event = "logout";
	$details = "User: <b>".$_SESSION['username']."</b> logged out successfully.";
	$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(), '".$_SESSION['username']."' )";
	$result = mysql_query($sql);

	disconnectFromDB();

	$_SESSION = array(); //destroy all of the session variables
    session_destroy();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" type="image/ico" href="http://www.yafp.de/favicon.ico" />
		<title>monoto - your web-based notes-keeper</title>
		<style type="text/css" title="currentStyle">
			@import "css/page.css";
			@import "css/table.css";
		</style>
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
				<?php	header( "refresh:2;url=index.php" ); ?>

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
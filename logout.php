<?php
	session_start();
	include 'conf/config.php';
	include 'scripts/db.php';
	connectToDB();
	$owner = $_SESSION['username'];

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

    include 'html_head.php';			// include the new header
?>

	</head>
	<!-- BODY -->
	<body id="dt_example">
		<div id="container">
			<!-- HEADER & NAV -->
			<?php include 'header.php'; ?>
			<div id="noteContentCo">
				
				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>

				<!-- CORE SETTINGS -->
				<h2><a name="core">logout</a></h2>

				<img src="images/icons/logout.gif">

				<!-- REDIRECT TO LOGIN -->
				<?php	header( "refresh:3;url=index.php" ); ?>

				<!-- SPACER -->
				<div id="spacer">&nbsp;<br>&nbsp;</br></div>
			</div>
		</div>
		<!--  FOOTER -->
		<?php include 'footer.php'; ?>
	</body>
</html>
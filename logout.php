<?php
	session_start();
	include 'conf/config.php';
	include 'inc/db.php';
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

	$_SESSION = array(); 				//destroy all of the session variables
    session_destroy();

    include 'inc/html_head.php';			// include the new header
?>
	</head>
	<!-- BODY -->
	<body id="dt_example">
		<div id="container">
			<!-- HEADER & NAV -->
			<?php include 'inc/header.php'; ?>
			<div id="noteContentCo">
				
				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>

				<!-- CORE SETTINGS -->
				<h2><a name="core">logout</a></h2>

				<?php
					// define logout image
					if($s_enable_random_logout_gif == false)
					{
						$logoutImage = "images/icons/logout.gif";
					}
					else // pick random from folder
					{
						$imagesDir = 'images/random_logout/';
						$images = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
						$logoutImage = $images[array_rand($images)];
					}
				?>

				<table style="width: 100%"><tr><td style="text-align: center;"><img src="<?php echo $logoutImage; ?>"></td></tr></table>
				<!-- REDIRECT TO LOGIN -->
				<?php header("refresh:10;url=index.php"); ?>
				
				<!-- SPACER -->
				<div id="spacer">&nbsp;<br>&nbsp;</br></div>
			</div>
		</div>
		<!--  FOOTER -->
		<?php include 'inc/footer.php'; ?>
	</body>
</html>
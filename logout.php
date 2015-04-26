<?php
	session_start();
	require 'conf/config.php';
	if($_SESSION['valid'] != 1)			// check if the user-session is valid or not
	{
		header('Location: redirect.php');
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="shortcut icon" type="image/ico" href="images/favicon.ico" />
		<title>monoto notes</title>
		
		<!-- META STUFF -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="monoto notes">
		<meta name="author" content="florian poeck">

		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="css/table.css" />
		<link rel="stylesheet" type="text/css" href="css/page01.css" title="default" /> 
		<link rel="stylesheet" href="images/font-awesome-4.3.0/css/font-awesome.min.css">
		<link href="css/bootstrap.min.css" rel="stylesheet">		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap-theme.min.css" rel="stylesheet">		<!-- Bootstrap theme -->
	</head>

	<body role="document">
		<!-- Fixed navbar -->
		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="notes.php"><img src="images/icons/monoto_logo_white.png" height="25"></a>
				</div>
			</div>
		</div>
		<div class="container theme-showcase" role="main">

		<div id="container">
			<div id="noteContentCo">
				<div class="spacer">&nbsp;</div>
				<div class="spacer">&nbsp;</div>
				<?php
					if($s_enable_random_logout_gif == false)		// define logout image
					{
						$logoutImage = "images/icons/logout.gif";
					}
					else // or ...pick random from folder
					{
						$imagesDir = 'images/random_logout/';
						$images = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
						$logoutImage = $images[array_rand($images)];
					}
				?>
				<table style="width: 100%"><tr><td style="text-align: center;"><img src="<?php echo $logoutImage; ?>"></td></tr></table>
				
				<!-- REDIRECT TO LOGIN -->
				<?php 
					session_destroy(); // destroy the user session
					header("refresh:5;url=index.php"); 
				?>
				
				<div id="spacer">&nbsp;<br>&nbsp;</br></div>
				
			</div>
			<div class="spacer">&nbsp;</div>
		</div>
	</div> <!-- /container -->

	<!-- JS-->
	<script type="text/javascript" src="js/jquery/jquery-2.1.3.min.js"></script>
	<script type="text/javascript" src="js/jquery.cookie.js"></script>

	<!-- loading the other scripts via LAB.js  ... without load-blocking so far -->
	<script type="text/javascript" src="js/LAB.js"></script>
	<script>
		$LAB
		.script("js/bootstrap.min.js") 						// Bootstrap core JavaScript
		.script("js/monoto/m_reallyLogout.js") 				// ask really-logout question if configured by admin
		.script("js/monoto/m_disableRightClick.js")			// disabled the right-click contextmenu
		.script("js/monoto/m_keyPressAll.js")				// keyboard shortcuts
	</script>
	
	<!-- noty - notifications -->
	<script type="text/javascript" src="js/noty/jquery.noty.js"></script>
	<script type="text/javascript" src="js/noty/layouts/topRight.js"></script>
	<script type="text/javascript" src="js/noty/themes/default.js"></script>
	<script type="text/javascript" src="js/monoto/m_initNoty.js"></script>

	<script type="text/javascript">
		// alert
		// information
		// error
		// warning
		// notification
		// success
		//
		var n = noty({text: 'Logout, redirecting to login', type: 'notification'});
	</script>

	</body>
</html>

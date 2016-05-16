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
		<!-- HTML Head -->
		<?php include 'inc/coreIncludesHTMLHead.php'; ?>

		<!-- CSS -->
		<?php include 'inc/coreIncludesCSS.php'; ?>
		<link rel="stylesheet" type="text/css" href="css/table.css" />
		<link rel="stylesheet" type="text/css" href="css/page01.css" title="default" />

		<!-- JS-->
		<?php include 'inc/coreIncludesJS.php'; ?>
	</head>

	<body role="document">
		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="notes.php"><img src="images/icons/monoto_logo_white.png" width="63" height="25"></a>
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

				<script>
					$('#container').delay(5000).fadeOut(5000)
				</script>

				<!-- REDIRECT TO LOGIN -->
				<?php
					session_destroy(); // destroy the user session
					header("refresh:10;url=index.php");
				?>

				<div id="spacer">&nbsp;<br>&nbsp;</br></div>
			</div>
			<div class="spacer">&nbsp;</div>
		</div>
	</div> <!-- /container -->

	<!-- JS-->
	<script type="text/javascript" src="js/jquery.cookie.js"></script>
	<script type="text/javascript" src="js/monoto/m_keyPressAll.js"></script>
	</body>
</html>

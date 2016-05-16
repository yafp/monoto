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

		<!-- JS -->
		<?php include 'inc/coreIncludesJS.php'; ?>
	</head>

	<body role="document">
		<?php require "inc/getText.php"; ?>
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
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li><a href="notes.php"><i class="fa fa-pencil-square-o fa-1x"></i> <?php echo translateString("Notes"); ?></a></li>
						<li><a href="mymonoto.php"><i class="fa fa-user fa-1x"></i> <?php echo translateString("MyMonoto") ?></a></li>
						<li><a href="keyboard.php"><i class="fa fa-keyboard-o fa-1x"></i> <?php echo translateString("Keyboard"); ?></a></li>
						<?php
							if($_SESSION['admin'] == 1) // show admin-section
							{
								echo '<li><a href="admin.php"><i class="fa fa-cogs fa-1x"></i> ';
								echo translateString("Admin");
								echo '</a></li>';
							}
						?>
						<li><a href="logout.php"><i class="fa fa-power-off fa-1x"></i> <?php echo translateString("Logout"); ?></a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="container theme-showcase" role="main">

		<div id="container">
			<div id="noteContentCo">
				<div class="spacer">&nbsp;</div>
				<div class="spacer">&nbsp;</div>
				<div class="spacer">&nbsp;</div>
				<center>
					<div class="spacer">&nbsp;</div>
					<?php
						// define image dir
						$dir = "images/random_logout/";

						// select a random image from that dir
						$images = scandir($dir);
						$i = rand(2, sizeof($images)-1);

						// build path
						$filePath = $dir.$images[$i];

						// get width of image
						list($imageWidth, $height, $type, $attr) = getimagesize($filePath);

						// define a max image width
						if ($imageWidth > 600)
						{
							$imageWidth = 600;
						}
					?>

					<a href="i.php"><img src="<?php echo $filePath; ?>" alt="random image"  width="<?php echo $imageWidth; ?>" /></a>
					<div class="spacer">&nbsp;</div>
				</center>
			</div>
			<div class="spacer">&nbsp;</div>
			<center><small><font color="grey"><?php echo translateString("JumpToNextImage"); ?></font></small></center>
		</div>
	</div> <!-- /container -->

	<!-- JS-->
	<script type="text/javascript" src="js/jquery.cookie.js"></script>
	<script type="text/javascript" src="js/monoto/m_keyPressAll.js"></script>
	<script type="text/javascript" src="js/monoto/m_keyPressI.js"></script>
	</body>
</html>

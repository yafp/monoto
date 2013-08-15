<?php
	session_start();
	if($_SESSION['valid'] == 1)		// check if the user-session is valid or not
	{
		include 'inc/html_head.php';			// include the new header
?>
		<!-- continue the header -->
		<!-- ################### -->
		<script type="text/javascript" language="javascript" src="js/m_keyPressAll.js"></script>				<!--  m_keyPressAll-->
		
	</head>

	<body id="dt_example">
		<div id="container">
			<!-- HEADER & NAV -->
			<?php include 'inc/header.php'; ?>
			<!-- CONTENT -->
			<div id="noteContentCo">
				<?php include ('conf/config.php'); ?>
				<h2>random image</h2>
				
				<?php
						$imagesDir = 'images/random_logout/';
						$images = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
						$logoutImage = $images[array_rand($images)];	
				?>

				<div id="randomImage">
					<a href="random.php">
						<img src="images/icons/reload_icon.png" width="50" align="right">
						<img src="<?php echo $logoutImage; ?>">
					</a>
				</div>

				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>
			</div>
		</div>
		
		<!--  FOOTER -->
		<?php include 'inc/footer.php'; ?>
	</body>
</html>


<?php
	}
	else 				//session is NOT valid - redirect to login
	{	
		header('Location: redirect.php'); 	
	}
?>

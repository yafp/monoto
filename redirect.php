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
					<a class="navbar-brand" href="notes.php"><img src="images/icons/monoto_logo_white.png" width="63" height="25"></a>
				</div>
			</div>
		</div>
		<div class="container theme-showcase" role="main">


		<div id="container">
			<div id="noteContentCo">
				<div class="spacer">&nbsp;</div>
				<div class="spacer">&nbsp;</div>
				<table style="width: 100%"><tr><td style="text-align: center;"><img src="images/icons/redirect.gif"></td></tr></table>
				<div id="spacer">&nbsp;</div>
				<?php header( "refresh:1;url=index.php" ); ?>
			</div>
			<div class="spacer">&nbsp;</div>
		</div>
	</div> <!-- /container -->

	<!-- JS-->
	<script type="text/javascript" src="js/jquery.cookie.js"></script>
	<script type="text/javascript" src="js/monoto/m_keyPress.js"></script>

	</body>
</html>

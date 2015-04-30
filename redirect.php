<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="shortcut icon" type="image/ico" href="images/favicon.ico" />
		<title>monoto notes</title>
		
		<!-- META STUFF -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="florian poeck">

		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="css/table.css" />
		<link rel="stylesheet" type="text/css" href="css/page01.css" title="default" /> 
		<link rel="stylesheet" type="text/css" href="images/font-awesome-4.3.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">		<!-- Bootstrap core CSS -->
		<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">		<!-- Bootstrap theme -->
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
				<table style="width: 100%"><tr><td style="text-align: center;"><img src="images/icons/redirect.gif"></td></tr></table>
				<div id="spacer">&nbsp;</div>
				<?php header( "refresh:1;url=index.php" ); ?>
			</div>
			<div class="spacer">&nbsp;</div>
		</div>
	</div> <!-- /container -->


	<!-- JS-->
	<script type="text/javascript" src="js/jquery/jquery-2.1.0.min.js"></script>
	<script type="text/javascript" src="js/jquery.cookie.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>

	<!-- loading the other scripts via LAB.js  ... without load-blocking so far -->
	<script type="text/javascript" src="js/LAB.js"></script>
	<script>
		$LAB
		.script("js/monoto/m_disableRightClick.js")					// disabled the right-click contextmenu
		.script("js/monoto/m_keyPress.js")					// keyboard shortcuts
	</script>
	</body>
</html>

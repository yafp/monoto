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
		<meta name="author" content="">

		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="css/table.css" />
		<link rel="stylesheet" type="text/css" href="css/page01.css" title="default" /> 
		<link rel="stylesheet" href="images/font-awesome-4.0.3/css/font-awesome.min.css">
		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<!-- Bootstrap theme -->
		<link href="css/bootstrap-theme.min.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="theme.css" rel="stylesheet">
		<!-- Just for debugging purposes. Don't actually copy this line! -->
		<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
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
					<a class="navbar-brand" href="notes.php">monoto</a>
				</div>
				<!--
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li><a href="notes.php"><i class="fa fa-pencil-square-o fa-1x"></i> Notes</a></li>
						<li><a href="mymonoto.php"><i class="fa fa-user fa-1x"></i> MyMonoto</a></li>
						<li><a href="keyboard.php"><i class="fa fa-keyboard-o fa-1x"></i> Keyboard</a></li>
						<li><a href="admin.php"><i class="fa fa-cogs fa-1x"></i> Admin</a></li>
						<li class="active"><a href="template.php"><i class="fa fa-cogs fa-1x"></i> Template</a></li>
						<li><a href="logout.php"><i class="fa fa-power-off fa-1x"></i> Logout</a></li>
					</ul>
				</div>
				-->
			</div>
		</div>
		<div class="container theme-showcase" role="main">



		<div id="container">
			<div id="noteContentCo">
				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>
				<div class="spacer">&nbsp;</div>
				
				<!-- DISPLAY REDIRECT IMAGE AND TEXT -->
				<table style="width: 100%"><tr><td style="text-align: center;"><img src="images/icons/redirect.gif"></td></tr></table>
				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>
				<!-- REDIRECT TO LOGIN -->
				<?php header( "refresh:3;url=index.php" ); ?>
				
				
				
			</div>
			<!-- SPACER -->
			<div class="spacer">&nbsp;</div>
		</div>
	</div> <!-- /container -->


	<!-- JS-->
	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.cookie.js"></script>
	<!-- Bootstrap core JavaScript -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="js/bootstrap.min.js"></script>
	<script src="js/docs.min.js"></script>
	<!-- loading the other scripts via LAB.js  ... without load-blocking so far -->
	<script type="text/javascript" src="js/LAB.js"></script>
	<script>
		$LAB
		.script("js/m_disableRightClick.js")					// disabled the right-click contextmenu
		.script("js/m_keyPress.js")					// keyboard shortcuts
	</script>

	</body>
</html>


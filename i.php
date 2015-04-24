<?php
	session_start();
	include 'conf/config.php';
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
		<link rel="stylesheet" href="images/font-awesome-4.0.3/css/font-awesome.min.css">
		<link href="css/bootstrap.min.css" rel="stylesheet">	<!-- Bootstrap core CSS -->
		<link href="css/bootstrap-theme.min.css" rel="stylesheet">	<!-- Bootstrap theme -->
		
		<script type="text/javascript" src="js/jquery/jquery-2.1.3.min.js"></script>
	</head>


	<body role="document">
		<?php require "inc/getText.php"; ?>
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
				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>
				<div class="spacer">&nbsp;</div>
				<div class="spacer">&nbsp;</div>

				<center>
					<div class="spacer">&nbsp;</div>
					<?php
						$dir = "images/random_logout/";
						$images = scandir($dir);
						$i = rand(2, sizeof($images)-1);
					?>
					<a href="i.php"><img src="images/random_logout/<?php echo $images[$i]; ?>" alt="random image" /></a>
					<div class="spacer">&nbsp;</div>
					<a href="i.php"><i class="fa fa-refresh fa-2x"></i></a>
				</center>
			</div>

			<!-- SPACER -->
			<div class="spacer">&nbsp;</div>
		</div>
	</div> <!-- /container -->


	<!-- JS-->
	<script type="text/javascript" src="js/jquery.cookie.js"></script>
	
	<!-- Bootstrap core JavaScript -->
	<script src="js/bootstrap.min.js"></script>

	<!-- loading the other scripts via LAB.js  ... without load-blocking so far -->
	<script type="text/javascript" src="js/LAB.js"></script>
	<script>
		$LAB
		.script("js/m_reallyLogout.js") 							// ask really-logout question if configured by admin
		.script("js/m_disableRightClick.js")					// disabled the right-click contextmenu
		.script("js/m_keyPressAll.js")								// keyboard shortcuts
	</script>
	
	<!-- noty - notifications -->
	<script type="text/javascript" src="js/noty/jquery.noty.js"></script>
	<script type="text/javascript" src="js/noty/layouts/topRight.js"></script>
	<script type="text/javascript" src="js/noty/themes/default.js"></script>
	<!-- init noty -->
	<script>
		$.noty.defaults = {
		  layout: 'topRight',
		  theme: 'defaultTheme',
		  type: 'alert',
		  text: '',
		  dismissQueue: true, // If you want to use queue feature set this true
		  template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
		  animation: {
		    open: {height: 'toggle'},
		    close: {height: 'toggle'},
		    easing: 'swing',
		    speed: 500 // opening & closing animation speed
		  },
		  timeout: 5000, // delay for closing event. Set false for sticky notifications
		  force: false, // adds notification to the beginning of queue when set to true
		  modal: false,
		  closeWith: ['click'], // ['click', 'button', 'hover']
		  callback: {
		    onShow: function() {},
		    afterShow: function() {},
		    onClose: function() {},
		    afterClose: function() {}
		  },
		  buttons: false // an array of buttons
		};
	</script>
	</body>
</html>

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
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li><a href="notes.php"><i class="fa fa-pencil-square-o fa-1x"></i> Notes</a></li>
						<li><a href="mymonoto.php"><i class="fa fa-user fa-1x"></i> MyMonoto</a></li>
						<li class="active"><a href="keyboard.php"><i class="fa fa-keyboard-o fa-1x"></i> Keyboard</a></li>
						<?php
							if($_SESSION['admin'] == 1) // show admin-section
							{
								echo '<li><a href="admin.php"><i class="fa fa-cogs fa-1x"></i> Admin</a></li>';
							}
						?>
						<li><a href="#" onclick="reallyLogout();"><i class="fa fa-power-off fa-1x"></i> Logout</a></li>
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
				<span>monoto-wide</span>
				<table style="width:100%">
					<tr><th>Key</th><th style="float:left">Function</th></tr>
					<tr><td>F1</td><td>Opens the monoto online documentation.</td></tr>
					<tr><td>(Shift) + Alt + n</td><td>Loads notes page per accesskey.</td></tr>
					<tr><td>(Shift) + Alt + m</td><td>Loads my monoto per accesskey.</td></tr>
					<tr><td>(Shift) + Alt + d</td><td>Loads admin page per accesskey.</td></tr>
					<tr><td>(Shift) + Alt + l</td><td>Logout from monoto per accesskey.</td></tr>
				</table>
				<br><br>
				<span>notes page</span>
				<table style="width:100%">
					<tr><th>Key</th><th style="float:left">Function</th></tr>
					<tr><td>ESC</td><td>Resets all input fields and sets focus to search.</td></tr>
	
					<tr><td>F5</td><td>Reloads all notes from db.</td></tr>
					<tr><td>F9</td><td>Saves a selected note.</td></tr>
					<tr><td>Del</td><td>Deletes the selected note.</td></tr>
					<tr><td>Arrow Down (in search)</td><td>Selects the top record.</td></tr>
					<tr><td>Arrow Down (if record selected)</td><td>Selects the next record.</td></tr>
					<tr><td>Arrow Up (in search)</td><td>Moves the focus to newNoteTitle.</td></tr>
					<tr><td>Arrow Up (if record selected)</td><td>Selects the previous record.</td></tr>
				</table>
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
	<!-- loading the other scripts via LAB.js  ... without load-blocking so far -->
	<script type="text/javascript" src="js/LAB.js"></script>
	<script>
		$LAB
		.script("js/m_reallyLogout.js") 						// ask really-logout question if configured by admin
		.script("js/m_disableRightClick.js")					// disabled the right-click contextmenu
		.script("js/m_keyPress.js")					// keyboard shortcuts
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
	
	<script type="text/javascript">
		// alert
		// information
		// error
		// warning
		// notification
		// success
		//
		var n = noty({text: 'Loaded Keyboard section.', type: 'notification'});
	</script>

	</body>
</html>


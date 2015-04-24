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
		<link rel="stylesheet" href="images/font-awesome-4.3.0/css/font-awesome.min.css">
		<link href="css/bootstrap.min.css" rel="stylesheet">		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap-theme.min.css" rel="stylesheet">		<!-- Bootstrap theme -->
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
						<li class="active"><a href="keyboard.php"><i class="fa fa-keyboard-o fa-1x"></i> <?php echo translateString("Keyboard"); ?></a></li>
						<?php
							if($_SESSION['admin'] == 1) // show admin-section
							{
								echo '<li><a href="admin.php"><i class="fa fa-cogs fa-1x"></i> ';
								echo translateString("Admin");
								echo '</a></li>';
							}
						?>
						<li><a href="#" onclick="reallyLogout();"><i class="fa fa-power-off fa-1x"></i> <?php echo translateString("Logout"); ?></a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="container theme-showcase" role="main">

		<div id="container">
			<div id="noteContentCo">
				<div class="spacer">&nbsp;</div>
				<div class="spacer">&nbsp;</div>	
				<div class="panel-group" id="accordion">
					<div class="panel panel-default">
						<div class="panel-heading"><h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse1">monoto-wide</a></h4></div>
						<div id="collapse1" class="panel-collapse collapse in">
							<div class="panel-body">
								<table style="width:100%">
									<tr><th width="25%">Key</th><th style="float:left">Function</th></tr>
									<tr><td>F1</td><td>Opens the monoto online documentation.</td></tr>
								</table>
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading"><h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse2">notes-page</a></h4></div>
						<div id="collapse2" class="panel-collapse collapse">
							<div class="panel-body">
								<table style="width:100%">
									<tr><th width="25%">Key</th><th style="float:left">Function</th></tr>
									<tr><td>ESC</td><td>Resets all input fields and sets focus to search.</td></tr>
									<tr><td>F2</td><td>Toggle maximize of editor.</td></tr>
									<tr><td>F5</td><td>Reloads all notes from db.</td></tr>
									<tr><td>F9</td><td>Saves a selected note.</td></tr>
									<tr><td>Del</td><td>Deletes the selected note.</td></tr>
									<tr><td>Arrow Down (in search)</td><td>Selects the top record.</td></tr>
									<tr><td>Arrow Down (if record selected)</td><td>Selects the next record.</td></tr>
									<tr><td>Arrow Up (in search)</td><td>Moves the focus to newNoteTitle.</td></tr>
									<tr><td>Arrow Up (if record selected)</td><td>Selects the previous record.</td></tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div> <!-- /container -->

	<!-- JS-->
	<script type="text/javascript" src="js/jquery/jquery-2.1.3.min.js"></script>
	<script type="text/javascript" src="js/jquery.cookie.js"></script>
	<!-- Bootstrap core JavaScript -->
	<script src="js/bootstrap.min.js"></script>
	<!-- loading the other scripts via LAB.js  ... without load-blocking so far -->
	<script type="text/javascript" src="js/LAB.js"></script>
	<script>
		$LAB
		.script("js/monoto/m_reallyLogout.js") 			// ask really-logout question if configured by admin
		.script("js/monoto/m_disableRightClick.js")		// disabled the right-click contextmenu
		.script("js/monoto/m_keyPressAll.js")			// keyboard shortcuts
	</script>

	<!-- noty - notifications -->
	<script type="text/javascript" src="js/noty/jquery.noty.js"></script>
	<script type="text/javascript" src="js/noty/layouts/topRight.js"></script>
	<script type="text/javascript" src="js/noty/themes/default.js"></script>
	<script type="text/javascript" src="js/monoto/m_initNoty.js"></script>

	</body>
</html>

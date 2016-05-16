<?php
	session_start();
	require 'conf/config.php';
	require "inc/getText.php";

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
						<div class="panel-heading"><h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse1"><?php echo translateString("monoto-wide"); ?></a></h4></div>
						<div id="collapse1" class="panel-collapse collapse in">
							<div class="panel-body">
								<table style="width:100%">
									<tr><th width="25%"><?php echo translateString("Key"); ?></th><th style="float:left"><?php echo translateString("Function"); ?></th></tr>
									<tr><td>F1</td><td><?php echo translateString("Opens the monoto online documentation"); ?></td></tr>
								</table>
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading"><h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse2"><?php echo translateString("notes-page"); ?></a></h4></div>
						<div id="collapse2" class="panel-collapse collapse">
							<div class="panel-body">
								<table style="width:100%">
									<tr><th width="25%"><?php echo translateString("Key"); ?></th><th style="float:left"><?php echo translateString("Function"); ?></th></tr>
									<tr><td>ESC</td><td><?php echo translateString("Resets all input fields and sets focus to search"); ?></td></tr>
									<tr><td>F2</td><td><?php echo translateString("Toggle maximize of editor"); ?></td></tr>
									<tr><td>F5</td><td><?php echo translateString("Reloads all notes from db"); ?></td></tr>
									<tr><td>F9</td><td><?php echo translateString("Saves a selected note"); ?></td></tr>
									<tr><td><?php echo translateString("Del"); ?></td><td><?php echo translateString("Deletes the selected note"); ?></td></tr>
									<tr><td><?php echo translateString("Arrow Down (in search)"); ?></td><td><?php echo translateString("Selects the top record"); ?></td></tr>
									<tr><td><?php echo translateString("Arrow Down (if record selected)"); ?></td><td><?php echo translateString("Selects the next record"); ?></td></tr>
									<tr><td><?php echo translateString("Arrow Up (in search)"); ?></td><td><?php echo translateString("Moves the focus to newNoteTitle"); ?></td></tr>
									<tr><td><?php echo translateString("Arrow Up (if record selected)"); ?></td><td><?php echo translateString("Selects the previous record"); ?></td></tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div> <!-- /container -->

	<!-- JS-->
	<script type="text/javascript" src="js/jquery.cookie.js"></script>
	<script type="text/javascript" src="js/monoto/m_keyPressAll.js"></script>
	</body>
</html>

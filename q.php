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
		<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.min.css" />
		<link rel="stylesheet" type="text/css" href="css/page01.css" title="default" />

		<!-- JS -->
		<?php include 'inc/coreIncludesJS.php'; ?>
		<script type="text/javascript" language="javascript" src="js/datatables/jquery.dataTables.min.js"></script>

		<script>
		$(document).ready(function() {

			$('#example').dataTable( {
				"sPaginationType": "full_numbers",
				"iDisplayLength" : -1,
				"aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]]
			} );

			$('.tabs .tab-links a').on('click', function(e)  {
				var currentAttrValue = $(this).attr('href');
				// Show/Hide Tabs
				$('.tabs ' + currentAttrValue).show().siblings().hide();
				// Change/remove current tab to active
				$(this).parent('li').addClass('active').siblings().removeClass('active');
				e.preventDefault();
			});
		});
		</script>
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

				<table cellpadding="0" cellspacing="0" class="display" id="example" width="100%">
					<thead align="left"><tr><th>author</th><th>quote</th></tr></thead>
					<tbody>
					<?php
						$handle = fopen($s_quotes_file, "r");
						if ($handle)
						{
							while (($line = fgets($handle)) !== false)
							{
								list($author, $quote) = explode(';', $line);					// split string
								echo "<tr><td>".$author."</td><td>".$quote."</td></tr>";	// process the line read
							}
						}
						fclose($handle);
					?>
					</tbody>
					</table>
			</div>
			<div class="spacer">&nbsp;</div>
		</div>
	</div> <!-- /container -->


	<!-- JS-->
	<script type="text/javascript" src="js/jquery.cookie.js"></script>

	<!-- loading the other scripts via LAB.js  ... without load-blocking so far -->
	<script type="text/javascript" src="js/LAB.js"></script>
	<script>
		$LAB
		.script("js/monoto/m_reallyLogout.js") 				// ask really-logout question if configured by admin
		.script("js/monoto/m_disableRightClick.js")			// disabled the right-click contextmenu
		.script("js/monoto/m_keyPressAll.js")					// keyboard shortcuts
	</script>

	<!-- noty - notifications -->
	<script type="text/javascript" src="js/noty/jquery.noty.js"></script>
	<script type="text/javascript" src="js/noty/layouts/topRight.js"></script>
	<script type="text/javascript" src="js/noty/themes/default.js"></script>
	<script type="text/javascript" src="js/monoto/m_initNoty.js"></script>

	</body>
</html>

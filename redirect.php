<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" type="image/ico" href="images/favicon.ico" />
		<title>monoto-notes</title>
		<style type="text/css" title="currentStyle">
			@import "css/page.css";
			@import "css/table.css";
		</style>
	</head>
	<body id="dt_example">
		<div id="container">
			<!-- HEADER & NAV -->
			<?php include 'header.php'; ?>
			<div id="noteContentCo">
				
				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>

				<!-- ERROR MESSAGE -->
				<h2><a name="redirect">redirect</a></h2>
				You must login first before you can use monoto. Gonna redirect you to the login dialog in some seconds.

				<!-- REDIRECT TO LOGIN -->
				<?php header( "refresh:3;url=index.php" ); ?>

				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>
			</div>
		</div>
		<!--  FOOTER -->
		<?php include 'footer.php'; ?>
	</body>
</html>
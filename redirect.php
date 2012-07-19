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
		<!-- jquery -->
		<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
		<!--  m_disableRightClick-->
		<script type="text/javascript" language="javascript" src="js/m_disableRightClick.js"></script>
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
				Something went wrong ... gonna redirect you in some seconds.

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
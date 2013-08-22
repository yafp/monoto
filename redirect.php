<?php include 'inc/html_head.php'; ?>
	</head>

	<body id="dt_example">
		<div id="container">
			<div id="newHead">
			<!-- HEADER & NAV -->
			<?php include 'inc/header.php'; ?>
			</div>
			<div id="noteContentCo">
				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>
				<!-- DISPLAY REDIRECT IMAGE AND TEXT -->
				<table style="width: 100%"><tr><td style="text-align: center;"><img src="images/icons/redirect.gif"></td></tr></table>
				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>
				<!-- REDIRECT TO LOGIN -->
				<?php header( "refresh:3;url=index.php" ); ?>
			</div>
		</div>
		<!--  FOOTER -->
		<?php include 'inc/footer.php'; ?>
	</body>
</html>
<?php include 'inc/html_head.php'; ?>
	</head>

	<body id="dt_example">
		<div id="container">

			<!-- HEADER & NAV -->
			<?php include 'inc/header.php'; ?>
			<div id="noteContentCo">
				
				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>

				<!-- ERROR MESSAGE -->
				<h2><a name="redirect">redirect</a></h2>
				<table style="width: 100%"><tr><td style="text-align: center;"><img src="images/icons/redirect.gif"></td></tr></table>

				<!-- REDIRECT TO LOGIN -->
				<?php header( "refresh:3;url=index.php" ); ?>

				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>
			</div>
		</div>
		
		<!--  FOOTER -->
		<?php include 'inc/footer.php'; ?>
	</body>
</html>
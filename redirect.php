<?php include 'html_head.php'; ?>

	<!-- jquery -->
	<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
	<!--  m_disableRightClick-->
	<script type="text/javascript" language="javascript" src="js/m_disableRightClick.js"></script>
	</head>
	
	<!-- BODY -->
	<body id="dt_example">
		<div id="container">
			<!-- HEADER & NAV -->
			<?php include 'header.php'; ?>
			<div id="noteContentCo">
				
				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>

				<!-- ERROR MESSAGE -->
				<h2><a name="redirect">redirect</a></h2>
				<table style="width: 100%">
					<tr><td style="text-align: center;"><img src="images/icons/redirect.gif"></td></tr>
				</table>

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
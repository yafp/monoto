<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" type="image/ico" href="images/favicon.ico" />
		<title>monoto-notes</title>

		<!-- CSS -->
		<!-- general -->
		<link rel="stylesheet" type="text/css" href="css/table.css" />
		<link rel="stylesheet" type="text/css" href="css/page01.css" title="default" /> 
		<link rel="stylesheet" href="images/font-awesome-4.0.3/css/font-awesome.min.css">
				
		<!-- JS which apply to all pages -->
		<!-- ########################### -->
		<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="js/jquery.cookie.js"></script>

		<!-- datatables -->
		<script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="js/LAB.js"></script>

		<!-- noty - notifications -->
		<script type="text/javascript" src="js/noty/jquery.noty.js"></script>
		<script type="text/javascript" src="js/noty/layouts/bottom.js"></script>
		<script type="text/javascript" src="js/noty/themes/default.js"></script>
		<!-- init noty -->
		<script>
		$.noty.defaults = {
		  layout: 'bottom',
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


		<!-- loading the other scripts via LAB.js  ... without load-blocking so far -->
		<script>
		   $LAB
		   .script("js/m_reallyLogout.js") 						// ask really-logout question if configured by admin
		   .script("js/m_disableRightClick.js")					// disabled the right-click contextmenu
		</script>
	
		<!-- closing </head> inside each single php file to be able to load other js files inside the head -->

		<?php
		if($_SESSION['valid'] == 1)				// check if the user-session is valid or not
		{
		?>
			<!-- SESSION TIMEOUT WARNING -->
			<script type="text/javascript">
			var lefttime = "<?php echo get_cfg_var('max_execution_time');  ?>"; /* get server-sided php timeout value in minutes */
			var interval;
			interval = setInterval('change()',60000);

			function change()
			{
				lefttime--;
				//alert(lefttime);
			   	if(lefttime<=0)
			   	{		
			   		window.location = "logout.php"
			   	}
			   	else
			   	{
			   		if(lefttime == 2) 
				   	{
				   		alert("Are you still there? Timeout might happen in "+lefttime+" minute(s). Do something.");
				   	}
			   	}
			}
			</script>
		<?php
		}
		?>	

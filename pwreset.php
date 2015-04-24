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
	
		<!-- JS -->
		<script src="js/jquery/jquery-2.1.3.min.js"></script>

		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="css/table.css" />
		<link rel="stylesheet" type="text/css" href="css/page01.css" title="default" /> 
		<link rel="stylesheet" href="images/font-awesome-4.0.3/css/font-awesome.min.css">
		<link href="css/bootstrap.min.css" rel="stylesheet">	<!-- Bootstrap core CSS -->
		<link href="css/bootstrap-theme.min.css" rel="stylesheet">	<!-- Bootstrap theme -->
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
					<a class="navbar-brand" href="index.php"><img src="images/icons/monoto_logo_white.png" height="25"></a>
				</div>
			</div>
		</div>
		<div class="container theme-showcase" role="main">

		<div id="container">
			<div id="noteContentCo">
				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>
				<div class="spacer">&nbsp;</div>
				<h3>Password reset</h3>
				<hr>
				<p>Please enter your email-address and trigger the reset-process. This will generate a new random password which you will receive by email.</p>

				<form name="reset" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
				<table border="0">
					<tr><td><input type="email" name="email" placeholder="Email" required="required" /></td><td><input type="submit" value="Reset" name="doPWReset" /></td></tr>
				</table>
			</form>
			</div>

			<!-- SPACER -->
			<div class="spacer">&nbsp;</div>
		</div>
	</div> <!-- /container -->


	<!-- JS-->
	<script type="text/javascript" src="js/jquery.cookie.js"></script>
	
	<!-- Bootstrap core JavaScript -->
	<script src="js/bootstrap.min.js"></script>

	<!-- loading the other scripts via LAB.js  ... without load-blocking so far -->
	<script type="text/javascript" src="js/LAB.js"></script>
	<script>
		$LAB
		.script("js/m_disableRightClick.js")					// disabled the right-click contextmenu
	</script>
	
	<!-- noty - notifications -->
	<script type="text/javascript" src="js/noty/jquery.noty.js"></script>
	<script type="text/javascript" src="js/noty/layouts/bottomCenter.js"></script>
	<script type="text/javascript" src="js/noty/themes/default.js"></script>
	<!-- init noty -->
	<script>
		$.noty.defaults = {
		  layout: 'bottomCenter',
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
		var n = noty({text: 'Loaded password reset interface', type: 'notification'});
	</script>

	</body>
</html>



<?php
	// creating the initial admin-account
	if ( isset($_POST["doPWReset"]) )
	{
		echo "<script type='text/javascript'>var n = noty({text: 'Processing reset', type: 'notification'});</script>";
		$resetEmail = $_POST['email'];

		include 'conf/config.php';
		include 'inc/db.php'; // connect to db
		connectToDB();

		$result = mysql_query("SELECT id FROM m_users WHERE email='$resetEmail' "); 			// run the mysql query
		if (mysql_num_rows($result)==0) // we found no useraccount with this mail-address
		{
			echo "<script type='text/javascript'>var n = noty({text: 'Unknown email, cancelling reset', type: 'error'});</script>"; 	
		}
		else // found a matching user-account
		{
			// remember affected username 
			$affectedAccount=$row[0];

			// generate a random password
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$generatedPW = substr(str_shuffle($chars),0,16);

			// generate hash and salt based on password
			$hash = hash('sha256', $generatedPW);							
			function createSalt() 											
			{
				$string = md5(uniqid(rand(), true));
				return substr($string, 0, 3);
			}
			$salt = createSalt();
			$hash = hash('sha256', $salt . $hash);

			// update db record for this user
			$query = "UPDATE m_users SET password='$hash', salt='$salt' WHERE id='$affectedAccount'";			
			mysql_query($query);

			// prepare notification email
			$to = $resetEmail;
			$subject = "monoto-notes password reset";
			$body = "Hi,
					\nSomeone triggered a password reset for your account at: 
					\n".$invite_target."		 									
					\n\n\n\nThe new generated password is as follows:
					\nPassword: ".$generatedPW."
					\n\n\nPlease change your password after your first visit.
					\n\nHave fun.";
				
			// try to send notification email
			if(@mail($to, $subject, $body))
			{
				echo "<script type='text/javascript'>var n = noty({text: 'Notification email has been sent.', type: 'success'});</script>"; 
			}
			else
			{
				echo "<script type='text/javascript'>var n = noty({text: 'Unable to sent notification mail.', type: 'error'});</script>"; 	
			}
			echo "<script type='text/javascript'>var n = noty({text: 'Password reset finished', type: 'success'});</script>"; 
		}
	}
?>

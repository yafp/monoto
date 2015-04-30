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
		<link rel="stylesheet" type="text/css" href="images/font-awesome-4.3.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">	<!-- Bootstrap core CSS -->
		<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">	<!-- Bootstrap theme -->
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
				<div class="spacer">&nbsp;</div>
				<div class="spacer">&nbsp;</div>
				<h3>Password reset</h3>
				<hr>
				<p>Please enter your email-address and trigger the reset-process. This will generate a new random password which you will receive by email.</p>
				<form name="reset" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
				<table border="0"><tr><td><input type="email" name="email" placeholder="Email" required="required" /></td><td><input type="submit" value="Reset" name="doPWReset" /></td></tr></table>
			</form>
			</div>
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
		.script("js/monoto/m_disableRightClick.js")			// disabled the right-click contextmenu
	</script>

	<!-- noty - notifications -->
	<script type="text/javascript" src="js/noty/jquery.noty.js"></script>
	<script type="text/javascript" src="js/noty/layouts/topRight.js"></script>
	<script type="text/javascript" src="js/noty/themes/default.js"></script>
	<script type="text/javascript" src="js/monoto/m_initNoty.js"></script>
	</body>
</html>



<?php
	require 'inc/helperFunctions.php';

	// creating the initial admin-account
	if ( isset($_POST["doPWReset"]) )
	{
		displayNoty("Processing reset","notification");

		$resetEmail = $_POST['email'];

		require 'conf/config.php';
		require 'inc/db.php'; // connect to db
		connectToDB();

		$result = mysql_query("SELECT id FROM m_users WHERE email='$resetEmail' "); 			// run the mysql query
		if (mysql_num_rows($result)==0) // we found no useraccount with this mail-address
		{
			displayNoty("Unknown email, cancelling reset","error");
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
				
			if(@mail($to, $subject, $body))		// try to send notification email
			{
				displayNoty("Notification email has been sent.","success");
			}
			else
			{
				displayNoty("Unable to sent notification mail.","error");
			}
			displayNoty("Password reset finished","notification");
		}
	}
?>

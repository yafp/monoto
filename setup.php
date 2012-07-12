<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" type="image/ico" href="http://www.yafp.de/favicon.ico" />
		
		<title>monoto - your webbased notes-keeper</title>
		<style type="text/css" title="currentStyle">
			@import "css/page.css";
			@import "css/table.css";
		</style>
	</head>
	
	<body id="dt_example">
		<div id="container">
			<div id="noteContentCo">
				
				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>

				<!-- CREATE ADMIN -->
				<h2><a name="core">create your monoto admin-user-account</a></h2>
				This script is supposed to be used to create your first monoto-user account.<br>Please delete this file after having setup your monoto-server.<br><br><br>

			
				<form name="login" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
					<table border="0">
						<tr>
							<td>Adminname:</td>
							<td><input type="text" name="username" placeholder="Username" /></td>
						</tr>
						<tr>
							<td>Password:</td>
							<td><input type="password" name="password1" placeholder="Password" /></td>
						</tr>
						<tr>
							<td>Repeat Password:</td>
							<td><input type="password" name="password2" placeholder="Password" /></td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" value="Create" name="doCreateAdminAccount" /></td>
						</tr>
					</table>
				</form>

				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>
			</div>

			<!--  FOOTER -->
			<?php include 'footer.php'; ?>

				</span>
			</div>
		</div>
	</body>
</html>



<?php
//
// create admin-account
//
if ( isset($_POST["doCreateAdminAccount"]) ) 
{
	include 'conf/config.php';

	// connect to mysql
	$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db($mysql_db, $con);									// select db

	// get data
	$username = $_POST['username'];
	$password1 = $_POST['password1'];
	$password2 = $_POST['password2'];
	$username = mysql_real_escape_string($username);

	// compare passwords
	if($password1 == $password2)
	{
		// playing with hash
		$hash = hash('sha256', $password1);
		// playing with salt - creates a 3 character sequence
		function createSalt()
		{
	    	$string = md5(uniqid(rand(), true));
	    	return substr($string, 0, 3);
		}
		$salt = createSalt();
		$hash = hash('sha256', $salt . $hash);

		// connect to mysql
		$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);		
		if (!$con)
		{
			die('Could not connect: ' . mysql_error());
		}

		mysql_select_db($mysql_db, $con);				// select db
		$query = "INSERT INTO m_users ( username, password, salt, is_admin ) VALUES ( '$username' , '$hash' , '$salt', '1' );";
		mysql_query($query);

		mysql_close($con); 								// close sql connection

		echo "<font color=red>Admin-Account created.</font>";

		header('Location: index.php');
	}
	else
	{
		echo "<font color=red>Password mismatch.<br>Canceling the setup script at this point.</font>";
	}
}

?>
	



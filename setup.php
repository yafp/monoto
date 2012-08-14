<?php
	include 'html_head.php';
?>
		<!-- continue the header -->
		<!-- ################### -->
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="css/table.css" />
		<link rel="stylesheet" type="text/css" href="css/page.css" title="default" />
		<link rel="alternate stylesheet" type="text/css" href="css/page02.css" title="alt" />
	</head>
	<body id="dt_example">
		<div id="container">
			<?php include 'header.php'; ?>
			<div id="noteContentCo">
				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>

				<!-- CREATE ADMIN -->
				<h2><a name="core">create your monoto admin-user-account</a></h2>
				This script is supposed to be used to create your first monoto-user account.<br>Please delete this file after having setup your monoto-server.<br><br><br>

				<form name="login" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
					<table border="0">
						<tr><td>Adminname:</td><td><input type="text" name="username" placeholder="Username" /></td></tr>
						<tr><td>Mail:</td><td><input type="text" name="email" placeholder="Email" /></td></tr>
						<tr><td>Password:</td><td><input type="password" name="password1" placeholder="Password" /></td></tr>
						<tr><td>Repeat Password:</td><td><input type="password" name="password2" placeholder="Password" /></td></tr>
						<tr><td></td><td><input type="submit" value="Create" name="doCreateAdminAccount" /></td></tr>
					</table>
				</form>

				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>
			</div>
		</div>
		<!--  FOOTER -->
		<?php include 'footer.php'; ?>
	</body>
</html>


<?php
// creating the initial admin-account
if ( isset($_POST["doCreateAdminAccount"]) ) 
{
	include 'conf/config.php';
	include 'scripts/db.php';  							// connect to db
	connectToDB();

	// check if user has already manually created the tables: m_users
	$val = mysql_query('select 1 from `m_users`')
	if($val !== FALSE)
	{
   		// table m_users EXISTS - get the data
		$username = $_POST['username'];
		$email = $_POST['email'];
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		$username = mysql_real_escape_string($username);

		// compare passwords
		if($password1 == $password2)											// both passwords do match
		{
			// playing with hash
			$hash = hash('sha256', $password1);
			function createSalt()												// playing with salt - creates a 3 character sequence
			{
		    	$string = md5(uniqid(rand(), true));
		    	return substr($string, 0, 3);
			}
			$salt = createSalt();
			$hash = hash('sha256', $salt . $hash);

			$query = "INSERT INTO m_users ( username, password, salt, is_admin, email, admin_note ) VALUES ( '$username' , '$hash' , '$salt', '1', '$email', 'monoto-admin' );";
			mysql_query($query);
			mysql_close($con); 													// close sql connection
			echo '<script type="text/javascript">log.infd("Admin account created. You will be redirected to monoto now.");</script>';					// blackbird js logging
			header('Location: index.php');										// redirect to main page
		}
		else 																	// Password mismatch
		{
			echo '<script type="text/javascript">log.error("Password mismatch, canceling setup script.");</script>';					// blackbird js logging
		}
	}
	else // mysql tables dont exist
	{
    	echo '<script type="text/javascript">log.error("Table m_users does not exist.");</script>';					// blackbird js logging
	}	
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- HTML Head -->
		<?php include 'inc/coreIncludesHTMLHead.php'; ?>

		<!-- JS -->
		<?php include 'inc/coreIncludesJS.php'; ?>

		<!-- CSS -->
		<?php include 'inc/coreIncludesCSS.php'; ?>
		<link rel="stylesheet" type="text/css" href="css/table.css" />
		<link rel="stylesheet" type="text/css" href="css/page01.css" title="default" />

	</head>

	<body role="document">
		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="index.php"><img src="images/icons/monoto_logo_white.png" width="63" height="25"></a>
				</div>
				<div class="navbar-collapse collapse"></div><!--/.navbar-collapse -->
			</div>
		</div>

		<!-- ... -->
		<div class="jumbotron">
			<div class="container">
				<h3>installer</h3>
				<hr>
				This install script creates your first monoto user account (with admin privileges).
				<div class="spacer">&nbsp;</div>
				<h4>Step 1: Prepare database</h4>
				Please create a database and all related tables according to the instructions in <span class="label label-default">doc/INSTALL.txt</span> and adjust the values in  <span class="label label-default">conf/config.php</span> according to it.
				<div class="spacer">&nbsp;</div>
				<h4>Step 2: Create user</h4>
				<form name="login" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
					<table border="0">
						<tr><td>Adminname:</td><td><input type="text" name="username" placeholder="Username" required="required"/></td></tr>
						<tr><td>Mail:</td><td><input type="email" name="email" placeholder="Email" required="required" /></td></tr>
						<tr><td>Password:</td><td><input type="password" name="password1" placeholder="Password" required="required" /></td></tr>
						<tr><td>Repeat Password:</td><td><input type="password" name="password2" placeholder="Password" required="required" /></td></tr>
						<tr><td></td><td><input type="submit" value="Create" name="doCreateAdminAccount" /></td></tr>
					</table>
				</form>
				<div class="spacer">&nbsp;</div>
				<div class="alert alert-danger"><strong>Warning:</strong> &nbsp;Please delete <i>setup.php</i> after finishing the install procedure. It is a risk to keep that file.</div>
			</div>
		</div>

		<div class="container">
			<hr>
			<footer><?php require 'inc/footer.php'; ?></footer>
		</div> <!-- /container -->
	</body>
</html>



<?php
	// creating the initial admin-account
	if ( isset($_POST["doCreateAdminAccount"]) )
	{
		require 'conf/config.php';
		require 'inc/db.php'; // connect to db
		connectToDB();

		// check if user has already manually created the tables: m_users
		$val = mysql_query('select 1 from `m_users`');
		if($val !== FALSE)
		{
			// table m_users EXISTS - get the data
			$username = $_POST['username'];
			$email = $_POST['email'];
			$password1 = $_POST['password1'];
			$password2 = $_POST['password2'];
			$username = mysql_real_escape_string($username);

			// compare passwords
			if($password1 == $password2)	// both passwords do match
			{
				// playing with hash
				$hash = hash('sha256', $password1);
				function createSalt()	// playing with salt - creates a 3 character sequence
				{
					$string = md5(uniqid(rand(), true));
					return substr($string, 0, 3);
				}
				$salt = createSalt();
				$hash = hash('sha256', $salt . $hash);

				$query = "INSERT INTO m_users ( username, password, salt, is_admin, email, admin_note ) VALUES ( '$username' , '$hash' , '$salt', '1', '$email', 'monoto-admin' );";
				mysql_query($query);
				mysql_close($con); // close sql connection
				header('Location: index.php');	// redirect to main page
			}
			else // Password mismatch
			{
				echo "<script type=\"text/javascript\">alert('Error: password mismatch');</script>";
			}
		}
		else // mysql tables dont exist
		{
			echo "<script type=\"text/javascript\">alert('Error: mysql table doesnt exist');</script>";
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="shortcut icon" href="images/favicon.ico">
		<title>monoto notes</title>
		
		<!-- META STUFF -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="monoto notes">
		<meta name="author" content="florian poeck">
		
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="css/table.css" />
		<link rel="stylesheet" type="text/css" href="css/page01.css" title="default" /> 
		<link rel="stylesheet" href="images/font-awesome-4.0.3/css/font-awesome.min.css">
		<link href="css/bootstrap.min.css" rel="stylesheet">		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap-theme.min.css" rel="stylesheet">		<!-- Bootstrap theme -->
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
					<a class="navbar-brand" href="index.php"><img src="images/icons/monoto_logo01.png" height="25"></a>
				</div>
			<div class="navbar-collapse collapse">
				
			</form>
		</div><!--/.navbar-collapse -->
	</div>
    </div>

		<!-- ... -->
		<div class="jumbotron">
			<div class="container">
				<h3>installer</h3>
				<hr>
				You can create your first monoto user account using this install script. This account will have admin priviledges.
				<div class="alert alert-danger">
					<strong>Warning:</strong> &nbsp;Please delete <i>setup.php</i> after finishing the install procedure. It is a risk to keep that file.
				</div>
			
			<br>
			<form name="login" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
				<table border="0">
					<tr><td>Adminname:</td><td><input type="text" name="username" placeholder="Username" required="required"/></td></tr>
					<tr><td>Mail:</td><td><input type="email" name="email" placeholder="Email" required="required" /></td></tr>
					<tr><td>Password:</td><td><input type="password" name="password1" placeholder="Password" required="required" /></td></tr>
					<tr><td>Repeat Password:</td><td><input type="password" name="password2" placeholder="Password" required="required" /></td></tr>
					<tr><td></td><td><input type="submit" value="Create" name="doCreateAdminAccount" /></td></tr>
				</table>
			</form>

				</div>
			</div>

			<div class="container">
			<hr>
			<footer>
				<?php include 'inc/footer.php'; ?>
			</footer>
		</div> <!-- /container -->


		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>





<?php
	// creating the initial admin-account
	if ( isset($_POST["doCreateAdminAccount"]) )
	{
		include 'conf/config.php';
		include 'inc/db.php'; // connect to db
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
				alert("Error: Password mismatch.");
			}
		}
		else // mysql tables dont exist
		{
			alert("Error: MySQL table doesnt exist.");
		}
	}
?>

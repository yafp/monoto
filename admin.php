<?php
	session_start();

	// check if the user-session is valid or not
	if($_SESSION['valid'] == 1)
	{
?>

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

		<!-- SHOW/HIDE DIV -->
		<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="js/m_showHide.js"></script>

		<!--  m_reallyLogout-->
		<script type="text/javascript" language="javascript" src="js/m_reallyLogout.js"></script>

	</head>
	
	
	<body id="dt_example">
		<div id="container">
			<!-- HEADER & NAV -->
			<?php include 'header.php'; ?>

			<div id="noteContentCo">
				
				<?php
					include 'config.php';
					if($s_enable_toc == true)
					{
						echo '<h2>admin toc</h2>';
						echo '<small>';
						echo '<ul>';
							echo '<li><a href="#users">users</a></li>';
							echo '<li><a href="#invites">invites</a></li>';
						echo '</ul>';
						echo '</small>';
						
					}
				?>

				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>




				<!-- GENERAL -->
				<h2><a name="users">users</a></h2>
					<table width="100%" border="0">
						<tbody>
							<tr>
								<td><b>ID</b></td>
								<td><b>Name</b></td>
								<td><b>Logins</b></td>
								<td><b>Logouts</b></td>
								<td><b>Notes</b></td>
								<td><b>First Login</b></td>
								<td><b>Last Login</b></td>
							</tr>

							<!-- get user data & display it -->
							<?php
								include 'conf/config.php';

								// connect to mysql
								$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);		
								if (!$con)
								{
									die('Could not connect: ' . mysql_error());
								}

								mysql_select_db($mysql_db, $con);				// select db

								// query user-data and display it
								$result = mysql_query("SELECT id, username, login_counter, logout_counter  FROM m_users ORDER by id "); 					// run the mysql query
								while($row = mysql_fetch_array($result)) 																					// fetch data and file table as a second step later on
								{
									echo "<tr>";
										echo "<td>";
											echo $row[0];
										echo "</td>";
										echo "<td>";
											echo $row[1];
										echo "</td>";
										echo "<td>";
											echo $row[2];
										echo "</td>";
										echo "<td>";
											echo $row[3];
										echo "</td>";
										echo "<td>";
											echo $row[3];
										echo "</td>";
										echo "<td>";
											echo $row[3];
										echo "</td>";
										echo "<td>";
											echo $row[3];
										echo "</td>";
									echo "</tr>";
									
								}

							?>
						</tbody>
					</table>
					List all pending invites and offer an option to delete those users - including sending a delete mail notification.

				

				<h2><a name="invites">invites</a></h2>
					<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
						You can create new user accounts here - newly created users will get a notification regarding the newly created monoto-account including login instructions and all relevant informations.<br><br>
						<table width="100%" border="0">
							<tr>
								<td>Username:</td> 
								<td><input type="input" name="newUsername" placeholder="Insert new username" /></td>
							</tr>
							<tr>
								<td>Mail:</td> 
								<td><input type="input" name="newUserMail" placeholder="Insert email" /></td>
							</tr>
							<tr>
								<td>Password:</td> 
								<td><input type="password" name="newPassword1" placeholder="Please enter the new password" /></td>
							</tr>
							<tr>
								<td>Repeat Password:</td> 
								<td><input type="password" name="newPassword2" placeholder="Please enter the new password again" /></td>
							</tr>
							<tr>
								<td>Optional:</td> 
								<td><input type="checkbox" name="sendNotification" value="sendNotification" /></td>
							</tr>
							<tr>
								<td><input type="submit" name="doCreateNewUserAccount" value="Invite" /></td> 
								<td></td>
							</tr>
						</table>					
					</form>
					checkbox: send email to new user (optional)<br>
					Hint: account will be created with a creation-timestamp. Admin-interface will list pending invites and offer an option to delete them.

				



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
	}
	else
	{
		//session is NOT valid
		header('Location: redirect.php');
	}
   
?>








<?php

include 'conf/config.php';


// CASES
// 1. Create new user account and send invite



// connect to mysql
$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);		
if (!$con)
{
	die('Could not connect: ' . mysql_error());
}
mysql_select_db($mysql_db, $con);				// select db






// 
// CREATE NEW USER
//
if ( isset($_POST["doCreateNewUserAccount"]) ) 
{
	echo "Create user account dummy:<br>";

	// store values on vars
	$newPassword1 	= $_POST['newPassword1'];
	$newPassword2 	= $_POST['newPassword2'];
	$newUsername 	= $_POST['newUsername'];
	$newUserMail 	= $_POST['newUserMail'];
	$sendNotification = $_POST['sendNotification'];


	// check if password is ok
	if($newPassword1 == $newPassword2) //& passwords match - we can continue trying to create this user
	{
		// check if account-name is available
		$result = mysql_query("SELECT count(username) FROM m_users WHERE username='$newUsername' "); 					// run the mysql query
		while($row = mysql_fetch_array($result)) 																					// fetch data and file table as a second step later on
		{
			if($row[0] == 0)  //username is free
			{
				// create the new user account
				$username	= $newUsername;
				$password 	= $newPassword1;
				// playing with hash
				$hash = hash('sha256', $password);
				// playing with salt - creates a 3 character sequence
				function createSalt()
				{
			    	$string = md5(uniqid(rand(), true));
			    	return substr($string, 0, 3);
				}
				$salt = createSalt();
				$hash = hash('sha256', $salt . $hash);

				$query = "INSERT INTO m_users ( username, password, salt ) VALUES ( '$username' , '$hash' , '$salt' );";
				mysql_query($query);
				echo "Account created.<br><br>Checking if we should send an invite letter as well.";
				// user created - well done



				// we should log that to m_notes -> admin only.





				// check if we should send a notification as well
				if($sendNotification == 'sendNotification' )
				{
					echo "we should send an account-created notification";
					if($newUserMail != '')
					{
						echo "DUMMY: sending invite to".$newUserMail;

						$to = "fidel@vido.info";
 						$subject = "monoto invited you to use fidels latest playground - a web-based notes system.";
 						$body = "Hi,\n\ni've created a new account on my web-based notes solution called 'monoto'. In case you would like to test it as well is asking for you and your notes.";
 						if (mail($to, $subject, $body)) 
 						{
   							echo("<p>Message successfully sent!</p>");
  						} 
  						else 
  						{
   							echo("<p>Message delivery failed...</p>");
  						}
					}
				}
				else
				{
					echo "NO notification wanted.";
				}
			}
			else // username already in use - cancel and inform the admin
			{
				echo "CANCEL - Username exists already<br>";
			}
		}
	}	
	else // passwords not matching
	{
		echo "CANCEL - You need to submit a password twice - not 2 different passwords.<br>";
	}
}






// disconnect from mysql
mysql_close($con);									// close sql connection

?>
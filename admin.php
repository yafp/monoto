<?php
	session_start();
	include 'conf/config.php';
	if($_SESSION['valid'] != 1)			// check if the user-session is valid or not
	{
		header('Location: redirect.php');
	}
	else
	{
		include 'conf/config.php';	// db informations
		include 'conf/build.php';	// version informations
		include 'inc/db.php';		// connect to db
		connectToDB();
	}
?>

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

		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="css/table.css" />
		<link rel="stylesheet" type="text/css" href="css/page01.css" title="default" /> 
		<link rel="stylesheet" href="images/font-awesome-4.3.0/css/font-awesome.min.css">
		<link href="css/bootstrap.min.css" rel="stylesheet">		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap-theme.min.css" rel="stylesheet">		<!-- Bootstrap theme -->
		
		<!-- JS-->
		<script type="text/javascript" src="js/jquery/jquery-2.1.3.min.js"></script>		<!-- jquery itself -->
		<script type="text/javascript" language="javascript" src="js/datatables/jquery.dataTables.min.js"></script>		<!-- datatables -->
		
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('#example').dataTable();
			} );
		</script>
	</head>

	<body role="document">
		<?php require "inc/getText.php"; ?>
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
					<a class="navbar-brand" href="notes.php"><img src="images/icons/monoto_logo_white.png" height="25"></a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li><a href="notes.php"><i class="fa fa-pencil-square-o fa-1x"></i> <?php echo translateString("Notes"); ?></a></li>
						<li><a href="mymonoto.php"><i class="fa fa-user fa-1x"></i> <?php echo translateString("MyMonoto") ?></a></li>
						<li><a href="keyboard.php"><i class="fa fa-keyboard-o fa-1x"></i> <?php echo translateString("Keyboard"); ?></a></li>
						<?php
							if($_SESSION['admin'] == 1) // show admin-section
							{
								echo '<li class="active"><a href="admin.php"><i class="fa fa-cogs fa-1x"></i> ';
								echo translateString("Admin");
								echo '</a></li>';
							}
						?>
						<li><a href="#" onclick="reallyLogout();"><i class="fa fa-power-off fa-1x"></i> <?php echo translateString("Logout"); ?></a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="container theme-showcase" role="main">

		<div id="container">
			<div id="noteContentCo">
				<div class="spacer">&nbsp;</div>
				<div class="spacer">&nbsp;</div>
				<div class="panel-group" id="accordion">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Config</a></h4>
						</div>
						<div id="collapse1" class="panel-collapse collapse in">
							<div class="panel-body">
							<?php
								if (file_exists('setup.php')) 	// check if setup.php still exists - if so - display a warning
								{
									echo '<div class="alert alert-danger">';
									echo '<strong>Warning:</strong> &nbsp;Please delete <i>setup.php</i>. It is a risk to keep that file.';
									echo '</div>';
								}
							?>
							The following values are based on <span class='badge'>conf/config.php</span><br><br>
				<table style="width: 60%">
					<tbody>
						<tr>
							<td><i class="fa fa-warning fa-1x"></td>
							<td>maintenance mode</td>
							<td style="width: 30%"><?php if($s_enable_maintenance_mode == false){ echo "<span class='badge'>false</span>";}else{echo "<span class='badge'>true</span>";} ?></td>
						</tr>
						<tr>
							<td><i class="fa fa-random fa-1x"></td>
							<td>random logout images</td>
							<td><?php if($s_enable_random_logout_gif == false){ echo "<span class='badge'>false</span>";}else{echo "<span class='badge'>true</span>";} ?></td>
						</tr>
					</tbody>
				</table>
            </div>
          </div>
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Version</a>
            </h4>
          </div>
          <div id="collapse2" class="panel-collapse collapse">
            <div class="panel-body">
            <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
					<table style="width: 100%">				
						<tr>
							<td>
							<button type="submit" name="doUpdateCheck" value="Software Update" class="btn btn-sm btn-default" style="width:120px" title="checks online for monoto updates"  id="doUpdateCheck"><i class="fa fa-cloud-download fa-1x"></i> Check for updates </button>
							</td>
							<td></td>
						</tr>
						<tr>
							<td><b>your version:</b></td>
							<td><span class='badge'><?php echo $m_build; ?></td>
						</tr>
						<tr>
							<td><b>latest version:</b></td>
							<td><div id="curStable01"><i>please run the check</i></div></td>
						</tr>
					</table>
				</form>
            </div>
          </div>
        </div>
        

        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Notes</a>
            </h4>
          </div>
          <div id="collapse3" class="panel-collapse collapse">
            <div class="panel-body">
            <?php
					// User: amount of all notes 
					$result = mysql_query("SELECT count(*) FROM m_notes "); 				// run the mysql query
					while($row = mysql_fetch_array($result)) 								// fetch data and file table as a second step later on
					{
						echo 'Your entire monoto installation has currently <span>'.$row[0].'</span> notes.<br>';
					}

					// get notes count per user  and display them in a table
					echo '<table style="width: 20%">';
					echo "<tr><th>notes</td><th>creator</td></tr>";

					$result = mysql_query("SELECT distinct owner, count(*) FROM m_notes GROUP by owner ORDER by COUNT(*) DESC LIMIT 0 , 30 "); // m_notes
					while($row = mysql_fetch_array($result))   // fill datatable
					{
						echo '<tr><td>'.$row[1].'</td><td>'.$row[0].'</td></tr>';		// fill table
					}
				?>
				</table>
            
            </div>
          </div>
        </div>
        
        
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">Users</a>
            </h4>
          </div>
          <div id="collapse4" class="panel-collapse collapse">
            <div class="panel-body">
            <table cellpadding="0" cellspacing="0" class="display" id="example" style="width: 100%">
					<thead><tr><th>id</th><th>username</th><th>logins</th><th>logouts</th><th>failed logins</th><th>current failed logins</th><th>invite date</th><th>first login</th><th>last login</th><th>last failed login</th><th>mail</th><th>admin</th><th>comment</th></tr></thead>
					<tbody>
						<?php
									$result = mysql_query("SELECT id, username, login_counter, logout_counter, failed_logins, date_invite, date_first_login, date_last_login, date_last_login_fail, email, is_admin, admin_note, failed_logins_in_a_row FROM m_users ORDER by id "); // m_log
									while($row = mysql_fetch_array($result))   // fill datatable
									{
										echo '<tr class="odd gradeU"><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td><td>'.$row[4].'</td><td>'.$row[12].'</td><td>'.$row[5].'</td><td>'.$row[6].'</td><td>'.$row[7].'</td><td>'.$row[8].'</td><td>'.$row[9].'</td><td>'.$row[10].'</td><td>'.$row[11].'</td></tr>';
									}
							?>
					</tbody>
					<tfoot><tr><th>id</th><th>username</th><th>logins</th><th>logouts</th><th>failed logins</th><th>current failed logins</th><th>invite date</th><th>first login</th><th>last login</th><th>last failed login</th><th>mail</th><th>admin</th><th>comment</th></tr></tfoot>
				</table>

				<!-- DELETE USER -->
				<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
				<br><br>
				<b>Delete existing account</b><br>	
				<table style="width: 100%">
					<tr>
						<td width='30%'>Select a user:</td> 
						<td>
							<select name="userDeleteSelector" required>
									<option value="" disabled selected>Select a username</option>
									<?php
									$result = mysql_query("SELECT id, username  FROM m_users ORDER by id ");
									while($row = mysql_fetch_array($result))   // fill user-select box
									{
										echo '<option value="'.$row[0].'">'.$row[1].'</option>';
									}
									?>
						</select>
								</td>
							</tr>
							<tr>
								<td>Enter CONFIRM (uppercase)</td> 
								<td><input type="text" name="confirmDeleteUser" placeholder="no" required></td>
							</tr>
							<tr>
								<td>Press the delete button to delete the user and all his notes plus all user-related events in the log</td> 
								<td><button type="submit" name="doDeleteUser"><i class="fa fa-trash-o fa-1x"></i> Delete</button> </td>
							</tr>
						</table>
						</form>
						
					
					<br>
					<b>Invite new user</b><br>
						<form id="inviteForm" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">	
							<table style="width: 100%">
								<tr>
									<td width='30%'>Username:</td> 
									<td><input type="text" name="newUsername" placeholder="Username" required="required" /></td>
								</tr>
								<tr>
									<td>Mail:</td> 
									<td><input type="email" name="newUserMail" placeholder="Email" required="required" /></td>
								</tr>
								<tr>
									<td>Password:</td> 
									<td><input type="password" name="newPassword1" placeholder="Password" required="required" autocomplete="off" /></td>
								</tr>
								<tr>
									<td>Repeat Password:</td> 
									<td><input type="password" name="newPassword2" placeholder="Repeat password" required="required" autocomplete="off" /></td>
								</tr>
								<tr>
									<td>Send notification mail to new user: (optional)</td> 
									<td><input type="checkbox" name="sendNotification" value="sendNotification" /></td>
								</tr>
								<tr>
									<td>Admin note about this invite or user: (optional)</td> 
									<td><input type="text" name="newUserNote" placeholder="Comment" /></td>
								</tr>
								<tr>
									<td><button type="submit" name="doCreateNewUserAccount" value="Invite" title="Starts the add user function if all informations are provided."><i class="fa fa-envelope-o fa-1x"></i> Invite</button></td> 
									<td>&nbsp;</td>
								</tr>
							</table>
						</form>
            </div>
          </div>
        </div>
        
        
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse5">Broadcast message</a>
            </h4>
          </div>
          <div id="collapse5" class="panel-collapse collapse">
            <div class="panel-body">
			Send an email to all monoto-accounts.
            <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
				<input type="text" placeholder="Subject" name="broadcastSubject" style="width:100%"><br>
				<textarea rows="4" cols="50" style="width:100%" placeholder="Insert your broadcast message text here" name="broadcastMessage"></textarea><br> 
				<input type="submit" name="doSendBroastcast" value="Send" style="width:200px" title="Sends a broadcast email to all users." />
			</form>
            
            </div>
          </div>
        </div>



<div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse6">Tasks</a>
            </h4>
          </div>
          <div id="collapse6" class="panel-collapse collapse">
            <div class="panel-body">
            <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">	
						<input type="submit" name="doOptimize" value="Optimize" style="width:200px" title="Executes an optimize command on the tables if needed." />This will optimize your entire monoto mysql database.
						<br><br>
						<input type="submit" name="doTruncateEvents" value="Truncate events" style="width:200px" title="Deletes the entire content of the event-table. Affects all users. Be careful with that." /> Warning: This will delete <b>ALL events</b> from the table: m_events.
						<br>
						<input type="submit" name="doTruncateNotes" value="Truncate notes" style="width:200px" title="Deletes the entire content of the notes-table. Affects all users. Be careful with that too." /> Warning: This will delete <b>ALL notes</b> from the table: m_notes.
					</form>
            
            </div>
          </div>
        </div>

      </div>

					</div>
			</div>
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
		.script("js/monoto/m_reallyLogout.js") 			// ask really-logout question if configured by admin
		.script("js/monoto/m_disableRightClick.js")		// disabled the right-click contextmenu
		.script("js/monoto/m_keyPressAll.js")			// keyboard shortcuts
	</script>
	
	<!-- noty - notifications -->
	<script type="text/javascript" src="js/noty/jquery.noty.js"></script>
	<script type="text/javascript" src="js/noty/layouts/topRight.js"></script>
	<script type="text/javascript" src="js/noty/themes/default.js"></script>
	<script type="text/javascript" src="js/monoto/m_initNoty.js"></script>
	</body>
</html>





<?php
	require 'conf/config.php';

	// Send broastcast to all users (email)
	//
	if ( isset($_POST["doSendBroastcast"]) ) 
	{
		$messageSubject = $_POST["broadcastSubject"];
		$messageText 	= $_POST["broadcastMessage"];
		if (($messageText != "") && ($messageSubject != ""))
		{
			// select all users & email-data
			$query = "SELECT username, email FROM m_users;";
			$result = mysql_query($query);
			while($row = mysql_fetch_array($result))
			{
				$username = $row[0];
				$email = $row[1];
				
				// try to send notification email
				if(@mail($email, $messageSubject, $messageText))
				{
					echo "<script type='text/javascript'>var n = noty({text: 'Notification email has been sent.', type: 'success'});</script>"; 
				}
				else
				{
					echo "<script type='text/javascript'>var n = noty({text: 'Unable to sent notification mail.', type: 'error'});</script>"; 	
				}
			}
		}
		else
		{
			echo "<script type='text/javascript'>var n = noty({text: 'Error: Please enter a message text', type: 'error'});</script>";	// notification 
		}
	}




	// UpdateCheck
	//
	// http://wuxiaotian.com/2009/09/php-check-for-updates-script/
	if ( isset($_POST["doUpdateCheck"]) ) 
	{
		session_start();
		
		// assume everything is good
		$critical = FALSE;
		$update = FALSE;
		
		$url = "https://raw.github.com/yafp/monoto/master/conf/vCurrentVersion.csv";
		$fp = @fopen ($url, 'r') or print ('UPDATE SERVER OFFLINE');
		$read = fgetcsv ($fp);
		fclose ($fp); //always a good idea to close the file connection

		// its critical
		if (($read[0] > $m_build) && ($read[2] == "1")) 
		{  $critical = TRUE; }
			
		// normal update
		if ($read[0] > $m_build) 
		{  $update = TRUE; }

		if ($critical) 
		{ 
	   		echo '<script type="text/javascript">
	   				var r=confirm("There is a critical update available. Should i download the latest version?")
					if (r==true)
	  				{ window.location = "https://raw.github.com/yafp/monoto/master/versionCheck.csv","_blank"; } </script>';

			die(); //terminate the script
		}
		else if ($update)
		{

		}
		else // uptodate
		{

		}

		// update div with stable informations
		//echo '<script type="text/javascript">document.getElementById("curStable01").innerHTML = "'.$read[0].'";</script>';
		echo '<script type="text/javascript">document.getElementById("curStable01").innerHTML = "'.$read[0].'";</script>';
		
		$urlDLStable = "<a href='$read[3]'>Download</a>";
		
		// open - tab2
		echo '<script type="text/javascript">        
			$("#collapse2").collapse({
				toggle: true
			});   </script>';
	}


	//
	// DELETE USER
	//
	if ( isset($_POST["doDeleteUser"]) ) 
	{
		$userID 		= $_POST['userDeleteSelector'];
		$confirmText	= $_POST['confirmDeleteUser'];

		if ($userID !="")
		{
			if($confirmText == "CONFIRM")
			{
				// get username to selected ID
				$query = "SELECT username FROM m_users WHERE id = '$userID';";
				$result = mysql_query($query);
				while($row = mysql_fetch_array($result)) 					
				{
					$usernameToDelete = $row[0];
				}

				// delete user
				$sql="DELETE FROM m_users WHERE id='$userID'";
				$result = mysql_query($sql);
				if (!$result) 
				{
			 		die('Error: ' . mysql_error());
				}
				else  // update m_log
				{
					$event = "User delete";
					$details = "User: <b>".$userID." </b>is now gone.";
					$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(), '$owner' )";
					$result = mysql_query($sql);

					// delete his notes as well
					$sql="DELETE FROM m_notes WHERE owner='$usernameToDelete'";
					$result = mysql_query($sql);

					// delete his log as well
					$sql="DELETE FROM m_log WHERE owner='$usernameToDelete'";
					$result = mysql_query($sql);
					
					echo "<script type='text/javascript'>var n = noty({text: 'Deleted user, his notes and the related log entries', type: 'notification'});</script>";	// notification 
				}
				mysql_close($con); 								// close sql connection
			}
			else // user hasnt entered CONFIRM
			{
				echo '<script>alert("Enter CONFIRM and try it again.");</script>';		// alert user that he hasnt entered CONFIRM
				echo "<script type='text/javascript'>var n = noty({text: 'Error: Please enter CONFIRM in the related field and try it again'});</script>";	// notification 
			}
			// reload page
		}
		else
		{
			echo '<script>alert("Please select a user first");</script>';		// alert user that he hasnt entered CONFIRM
			echo "<script type='text/javascript'>var n = noty({text: 'Error: Please select a user first', type: 'error'});</script>";	// notification 
		}
	}


	//
	// OPTIMIZE MYSQL TABLES
	//
	if ( isset($_POST["doOptimize"]) ) 
	{
		connectToDB();  // connect to mysql

		// select all table with (> 10% overhead) AND at (least > 100k free space)
		$res = mysql_query('SHOW TABLE STATUS WHERE Data_free / Data_length > 0.1 AND Data_free > 102400');
		while($row = mysql_fetch_assoc($res)) 
		{
  			mysql_query('OPTIMIZE TABLE ' . $row['Name']);
		}
		echo "<script type='text/javascript'>var n = noty({text: 'Database optimized', type: 'notification'});</script>";	// notification 
	}

	//
	// TRUNCATE EVENTS
	//
	if ( isset($_POST["doTruncateEvents"]) ) 
	{
		connectToDB();  								// connect to mysql
		mysql_query('TRUNCATE TABLE m_log');			// truncate log-/events-table
		echo "<script type='text/javascript'>var n = noty({text: 'Truncated all eventlog entries', type: 'notification'});</script>";	// notification 
	}

	//
	// TRUNCATE NOTES
	//
	if ( isset($_POST["doTruncateNotes"]) ) 
	{
		connectToDB();  								// connect to mysql
		mysql_query('TRUNCATE TABLE m_notes');			// truncate notes-table
		echo "<script type='text/javascript'>var n = noty({text: 'Truncated all user notes', type: 'notification'});</script>";	// notification 
	}

	//
	// CREATE NEW USER
	//
	if ( isset($_POST["doCreateNewUserAccount"]) ) 
	{
		connectToDB();  // connect to mysql

		$invite_from 	= $_SESSION['username'];
		// need  full page url for link in the invite mail 
		$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
		if ($_SERVER["SERVER_PORT"] != "80")
		{
    		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} 
		else 
		{
    		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		//$invite_target 	= $_SERVER['SERVER_NAME'];
		$invite_target 	= $pageURL;
		// store values on vars
		$newPassword1 	= $_POST['newPassword1'];
		$newPassword2 	= $_POST['newPassword2'];
		$newUsername 	= $_POST['newUsername'];
		$newUserMail 	= $_POST['newUserMail'];
		$sendNotification = $_POST['sendNotification'];		// optional
		$newUserNote = $_POST['newUserNote'];				// optional

		// check if password is ok
		if($newPassword1 == $newPassword2) //& passwords match - we can continue trying to create this user
		{
			// check if account-name is available
			$result = mysql_query("SELECT count(username) FROM m_users WHERE username='$newUsername' "); 					// run the mysql query
			while($row = mysql_fetch_array($result)) 																					// fetch data and file table as a second step later on
			{
				if($row[0] == 0)  // username is free
				{
					// check if we got an emailaddress
					if(strlen($newUserMail) > 0)
					{
						// create the new user account
						$username	= $newUsername;
						$password 	= $newPassword1;
						$hash = hash('sha256', $password);   // playing with hash
						function createSalt()   			// playing with salt - creates a 3 character sequence
						{
					    	$string = md5(uniqid(rand(), true));
					    	return substr($string, 0, 3);
						}
						$salt = createSalt();
						$hash = hash('sha256', $salt . $hash);

						$query = "INSERT INTO m_users ( username, password, salt, date_invite, email, admin_note ) VALUES ( '$username' , '$hash' , '$salt' , now() , '$newUserMail', '$newUserNote');";
						mysql_query($query);
						
						echo "<script type='text/javascript'>var n = noty({text: 'Created new user account', type: 'notification'});</script>";	// notification 
						echo '<script>$.cookie("lastAction", "Note "+modifiedNoteTitle+" saved.");</script>';		// store last Action in cookie
						
						// we should log that to m_notes -> admin only.
						// check if we should send a notification as well
						if($sendNotification == 'sendNotification' )
						{
							if($newUserMail != '')
							{
								$to = $newUserMail;
		 						$subject = "monoto-notes invite";
		 						$body = "Hi,
		 									\n".$invite_from." invited you to monoto - his web-based notes solution.
		 									\nFeel free to use it as your personal notes keeper as well.
		 									\n\nYou can get some general informations about monoto here: https://github.com/macfidelity/monoto/wiki.
		 									\n\n\n\nThe login credentials are as follows:
		 									\nUsername: ".$username."
		 									\nPassword: ".$password."
		 									\n\n\nPlease change your password after your first visit at:
		 									\n".$invite_target."
		 									\n\nHave fun.";
		 						if (mail($to, $subject, $body)) 
		 						{
		  						} 
		  						else 
		  						{
		  						}
		  						echo "<script type='text/javascript'>var n = noty({text: 'Notification mail send', type: 'notification'});</script>";	// notification 
							}
						}
					}
					else // no usermail-adress defined while trying to create new account
					{
						echo "<script type='text/javascript'>var n = noty({text: 'No mail address defined.', type: 'error'});</script>";	// notification 
					}
				}
				else // username already in use - cancel and inform the admin
				{
					echo "<script type='text/javascript'>var n = noty({text: 'This mail-adress is already in use', type: 'error'});</script>";	// notification 
				}
			}
		}	
		else // passwords not matching
		{
			echo "<script type='text/javascript'>var n = noty({text: 'Error: passwords are not matching', type: 'error'});</script>";	// notification 
		}
	}
?>

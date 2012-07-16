<?php
	session_start();
	if($_SESSION['valid'] == 1)		// check if the user-session is valid or not
	{
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" type="image/ico" href="images/favicon.ico" />
		<title>monoto-notes</title>
		<style type="text/css" title="currentStyle">
			@import "css/page.css";
			@import "css/table.css";
		</style>
		<!--  m_reallyLogout-->
		<script type="text/javascript" language="javascript" src="js/m_reallyLogout.js"></script>
		<!-- jquery -->
		<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
		<!-- datatables -->
		<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
		<!-- toc/info/help -->
		<script type="text/javascript">
			$(document).ready(function(){
				
				$(".accordion h3:first").addClass("active");
				//$(".accordion p:not(:first)").hide();
				$(".accordion p").hide();

				$(".accordion h3").click(function(){
					$(this).next("p").slideToggle("slow")
					.siblings("p:visible").slideUp("slow");
					$(this).toggleClass("active");
					$(this).siblings("h3").removeClass("active");
				});

			});
		</script>

		<!-- main js for table etc -->
		<script type="text/javascript" charset="utf-8">
			var oTable;
			var giRedraw = false;

			$(document).ready(function() 
			{
				/* Init the table */
				oTable = $('#example').dataTable( 
				{ 
				/* "oSearch": {"sSearch": "Initial search"}, */
				"sPaginationType": "full_numbers",
				"iDisplayLength": 5,					/* default rows */
				"bLengthChange": false,
				"bPaginate": true , 					/* pagination  - BREAKS SELECTED ROW - copy content function right now*/
				"aaSorting": [[ 0, "asc" ]],				/* sorting */
				"aoColumns"   : [					/* visible columns */
							{ "bSearchable": true, "bVisible": true }, 	/* note-id */
							{ "bSearchable": true, "bVisible": true },	/* note-title */
							{ "bSearchable": true, "bVisible": true }, 	/* note-content */
							{ "bSearchable": true, "bVisible": true }, 	/* note-id */
							{ "bSearchable": true, "bVisible": true },	/* note-title */
							{ "bSearchable": true, "bVisible": true }, 	/* note-content */
							{ "bSearchable": true, "bVisible": true } 	/* note-content */	
						],
				});
		} );
		</script>
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
				?>
					<h2><a name="desc">admin toc</a></h2>
						<div class="accordion">
							<h3>01. admin settings <a href="#basic">#</a></h3>
							<p>the <a href="#basic">admin </a>section shows all server-wide monoto-settings. Those settings are configurable by the adminvia conf/config.php.</p>
							<h3>02. user list <a href="#users">#</a></h3>
							<p>the <a href="#users">users </a>section lists all existing user accounts ........</p>
							<h3>03. invites <a href="#invites">#</a></h3>
							<p>the <a href="#invites">invites </a>section allows you to create new user accounts.</p>
						</div>
				<?php
					}
				?>

				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>

				<?php
					include ('scripts/db.php');		// connect to db
					connectToDB();
				?>
				
				<!-- BASICS -->
				<h2><a name="basic">admin settings</a></h2>
					<table width="100%">
					<tbody>
						<tr>
							<td colspan="2" width="50%"><b>General</b></td>
							<td colspan="2" width="50%"><b>Page specific</b></td>
						</tr>
						<tr>
							<td width="30%">- enable toc:</td>
							<td width="20%"><?php if($s_enable_toc == false){ echo "<i>false</i>";}else{echo "<i>true</i>";} ?></td>
							<td width="30%">- enable about section on info page:</td>
							<td width="20%"><?php if($enable_info_about_section == false){ echo "<i>false</i>";}else{echo "<i>true</i>";} ?></td>
						</tr>
						<tr>
							<td>- show tagline in header:</td>
							<td><?php if($s_enable_header_tagline == false){ echo "<i>false</i>";}else{echo "<i>true</i>";} ?></td>
							<td>- enable changelog on info page:</td>
							<td><?php if($enable_info_version_changelog_section == false){ echo "<i>false</i>";}else{echo "<i>true</i>";} ?></td>
						</tr>
						<tr>
							<td>- enable really delete question:</td>
							<td><?php if($s_enable_really_delete == false){ echo "<i>false</i>";}else{echo "<i>true</i>";} ?></td>
							<td>- enable stats section on info page:</td>
							<td><?php if($enable_info_stats_section == false){ echo "<i>false</i>";}else{echo "<i>true</i>";} ?></td>
						</tr>
						<tr>
							<td>- enable user icon:</td>
							<td><?php if($s_enable_user_icon == false){ echo "<i>false</i>";}else{echo "<i>true</i>";} ?></td>
							<td>- enable keyboard section on info page:</td>
							<td><?php if($enable_info_keyboard_section == false){ echo "<i>false</i>";}else{echo "<i>true</i>";} ?></td>
						</tr>
						<tr>
							<td>- enable really logout question:</td>
							<td><?php if($s_enable_really_logout == false){ echo "<i>false</i>";}else{echo "<i>true</i>";} ?></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>- enable welcome message:</td>
							<td><?php if($enable_welcome_message == false){ echo "<i>false</i>";}else{echo "<i>true</i>";} ?></td>
							<td></td>
							<td></td>
						</tr>
					</tbody>
					</table>

				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>

				<!-- USERS -->
				<h2><a name="users">users</a></h2>
				<!-- datatables showing our users -->
				<table cellpadding="0" cellspacing="0" class="display" id="example" width="100%">
					<thead><tr><th>id</th><th>username</th><th>logins</th><th>logouts</th><th>invite date</th><th>first login</th><th>last login</th></tr></thead>
					<tbody>
					<?php
							$result = mysql_query("SELECT id, username, login_counter, logout_counter, date_invite, date_first_login, date_last_login  FROM m_users ORDER by id "); // m_log
							while($row = mysql_fetch_array($result))   // fill datatable
							{
								echo '<tr class="odd gradeU"><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td><td>'.$row[4].'</td><td>'.$row[5].'</td><td>'.$row[6].'</td></tr>';
							}
					?>
					</tbody>
					<tfoot><tr><th>id</th><th>username</th><th>logins</th><th>logouts</th><th>invite date</th><th>first login</th><th>last login</th></tr></tfoot>
				</table>

				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>
				<h2><a name="invites">invites</a></h2>
					<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">	
						<table width="100%">
							<tr>
								<td width='30%'>Username:</td> 
								<td><input type="text" name="newUsername" placeholder="Insert new username" /></td>
								<td rowspan="6"><img src="images/default_user_icon_trans.png" border="1"></td>
							</tr>
							<tr>
								<td>Mail:</td> 
								<td><input type="text" name="newUserMail" placeholder="Insert email" /></td>
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
								<td>Send notification mail to new user (optional)</td> 
								<td><input type="checkbox" name="sendNotification" value="sendNotification" /></td>
							</tr>
							<tr>
								<td><input type="submit" name="doCreateNewUserAccount" value="Invite" /></td> 
								<td></td>
							</tr>
						</table>

					</form>
					
				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>				
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
	else  //session is NOT valid
	{
		header('Location: redirect.php');
	}
   
?>


<?php
	include 'conf/config.php';

	// CREATE NEW USER
	//
	if ( isset($_POST["doCreateNewUserAccount"]) ) 
	{
		connectToDB();  // connect to mysql
		
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
					$hash = hash('sha256', $password);   // playing with hash
					function createSalt()   			// playing with salt - creates a 3 character sequence
					{
				    	$string = md5(uniqid(rand(), true));
				    	return substr($string, 0, 3);
					}
					$salt = createSalt();
					$hash = hash('sha256', $salt . $hash);

					$query = "INSERT INTO m_users ( username, password, salt, date_invite ) VALUES ( '$username' , '$hash' , '$salt' , now() );";
					mysql_query($query);
					echo "Account created.<br><br>Checking if we should send an invite letter as well.";

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
		// disconnect from mysql
		disconnectFromDB();

		// reload page
	}
?>
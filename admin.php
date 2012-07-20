<?php
	session_start();
	if(($_SESSION['valid'] == 1)	&& ($_SESSION['admin'] == 1))	// check if the user-session is valid or not AND if its an admin account.
	{
?>
<!DOCTYPE html>
<html>
	<head>
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="css/table.css" />
		<link rel="stylesheet" type="text/css" href="css/page.css" title="default" />
		<link rel="alternate stylesheet" type="text/css" href="css/page02.css" title="alt" />

		<!--  m_reallyLogout-->
		<script type="text/javascript" language="javascript" src="js/m_reallyLogout.js"></script>
		<!-- jquery -->
		<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
		<!-- datatables -->
		<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
		<!--  m_disableRightClick-->
		<script type="text/javascript" language="javascript" src="js/m_disableRightClick.js"></script>
		<!-- scroll up -->
		<script type="text/javascript" language="javascript" src="js/m_scrollUp.js"></script>
		<!-- m_accordionToc -->
		<script type="text/javascript" language="javascript" src="js/m_accordionToc.js"></script>
		<!-- styleswitcher -->
		<script type="text/javascript" language="javascript" src="js/styleswitcher.js"></script>
		<!-- flot graphs -->
		<script language="javascript" type="text/javascript" src="js/jquery.flot.js"></script>
    	<script language="javascript" type="text/javascript" src="js/jquery.flot.pie.js"></script>

		<!-- main js for table etc -->
		<script type="text/javascript">
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
					<h2><a name="desc">admin</a></h2>
						<div class="accordion">
							<h3>admin settings [<a href="#basic">...</a>]</h3>
							<p><img src="images/info_icon.png" alt="info icon" width="40" align="right">the <a href="#basic">admin</a> section shows all server-wide monoto-settings. Those settings are configurable by the admin only and apply to all user accounts. The admin can modify those settings via 'conf/config.php'.</p>
							<h3>notes [<a href="#notes">...</a>]</h3>
							<p><img src="images/info_icon.png" alt="info icon" width="40" align="right">the <a href="#basic">notes</a> section gives a quick overview about the total amount of notes in the mysql database.</p>
							<h3>user list [<a href="#users">...</a>]</h3>
							<p><img src="images/info_icon.png" alt="info icon" width="40" align="right">the <a href="#users">users</a> section lists all existing user accounts. The table features the user-id, username, amout of logins and logouts, the invite date, the date of the first and the last login.</p>
							<h3>invites [<a href="#invites">...</a>]</h3>
							<p><img src="images/info_icon.png" alt="info icon" width="40" align="right">the <a href="#invites">invites</a> section allows you to create new user accounts. The admin can optional send a notification mail to the new user.</p>
							<h3>mysql [<a href="#mysql">...</a>]</h3>
							<p><img src="images/info_icon.png" alt="info icon" width="40" align="right">the <a href="#mysql">mysql</a> section allows you to to optimize or truncate your tables.</p>
							<h3>misc [<a href="#misc">...</a>]</h3>
							<p><img src="images/info_icon.png" alt="info icon" width="40" align="right">the <a href="#mysql">misc</a> section collects unsorted stuff.</p>
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
							<td width="20%"><?php if($s_enable_toc == false){ echo "<span>false</span>";}else{echo "<span>true</span>";} ?></td>
							<td width="30%">- enable about section on info page:</td>
							<td width="20%"><?php if($s_enable_info_about_section == false){ echo "<span>false</span>";}else{echo "<span>true</span>";} ?></td>
						</tr>
						<tr>
							<td>- enable really delete question:</td>
							<td><?php if($s_enable_really_delete == false){ echo "<span>false</span>";}else{echo "<span>true</span>";} ?></td>
							<td>- enable welcome message on info page:</td>
							<td><?php if($s_enable_welcome_message == false){ echo "<span>false</span>";}else{echo "<span>true</span>";} ?></td>
						</tr>
						<tr>
							<td>- enable really logout question:</td>
							<td><?php if($s_enable_really_logout == false){ echo "<span>false</span>";}else{echo "<span>true</span>";} ?></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>- enable user icon:</td>
							<td><?php if($s_enable_user_icon == false){ echo "<span>false</span>";}else{echo "<span>true</span>";} ?></td>
						</tr>
					</tbody>
					</table>



				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>

				<!-- USERS -->
				<h2><a name="notes">notes</a></h2>
				<?php
					// User: amount of all notes 
					$result = mysql_query("SELECT count(*) FROM m_notes "); 				// run the mysql query
					while($row = mysql_fetch_array($result)) 								// fetch data and file table as a second step later on
					{
						echo 'Your entire monoto installation has currently <span>'.$row[0].'</span> notes.<br>';
					}
				?>

				<table width="20%">
					<tr align="left"><th>notes</td><th>creator</td></tr>
					<!-- get notes count per user -->
					<?php
						// define array for our flot pie graph
						$whatArray = array();
						$howMuchArray = array();

						$result = mysql_query("SELECT distinct owner, count(*) FROM m_notes GROUP by owner ORDER by COUNT(*) DESC LIMIT 0 , 30 "); // m_notes
						while($row = mysql_fetch_array($result))   // fill datatable
						{
							// fill table
							echo '<tr><td>'.$row[1].'</td><td>'.$row[0].'</td></tr>';

							// fill array for graph
							array_push($whatArray, $row[0]);
							array_push($howMuchArray, $row[1]);
						}
					?>
				</table>

				<!-- placeholder for flot pie-chart -->
				<div id="placeholder" style="height:200px;"></div>


				<!-- generate our flot pie chart -->
				<script type="text/javascript">

				arr01 = ["<?php echo implode ('","', $whatArray); ?>"]
				arr02 = ["<?php echo implode ('","', $howMuchArray); ?>"]

				var data = [];
				var series = 10;

				for( var i = 0; i<series; i++)
				{
					data[i] = { 
							label: arr01[i],
							data: parseFloat(arr02[i])
							}
				}


				// PLOT
				$.plot($("#placeholder"), data, {
				    series: {
				        pie: {
				            show: true,
				            radius: 1,
				            label: {
				                show: true,
				                radius: 1,
				                formatter: function(label, series) {
				                    return '<div style="font-size:11px; text-align:center; padding:2px; color:white;">'+label+'<br/>'+Math.round(series.percent)+'%</div>';
				                },
				                background: {
				                    opacity: 0.8,
				                    color: '#444'
				                }
				            }
				        }
				    },
				    legend: {
				        show: false
				    }
				});
				</script>

				
				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>

				<!-- USERS -->
				<h2><a name="users">users</a></h2>
				<!-- datatables showing our users -->
				<table cellpadding="0" cellspacing="0" class="display" id="example" width="100%">
					<thead><tr><th>id</th><th>username</th><th>logins</th><th>logouts</th><th>invite date</th><th>first login</th><th>last login</th><th>mail</th><th>admin</th><th>comment</th></tr></thead>
					<tbody>
					<?php
							$result = mysql_query("SELECT id, username, login_counter, logout_counter, date_invite, date_first_login, date_last_login, email, is_admin, admin_note  FROM m_users ORDER by id "); // m_log
							while($row = mysql_fetch_array($result))   // fill datatable
							{
								echo '<tr class="odd gradeU"><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td><td>'.$row[4].'</td><td>'.$row[5].'</td><td>'.$row[6].'</td><td>'.$row[7].'</td><td>'.$row[8].'</td><td>'.$row[9].'</td></tr>';
							}
					?>
					</tbody>
					<tfoot><tr><th>id</th><th>username</th><th>logins</th><th>logouts</th><th>invite date</th><th>first login</th><th>last login</th><th>mail</th><th>admin</th><th>comment</th></tr></tfoot>
				</table>

				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>

				<!-- INVITES -->
				<h2><a name="invites">invites</a></h2>
					<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">	
						<table width="100%">
							<tr>
								<td width='30%'>Username:</td> 
								<td><input type="text" name="newUsername" placeholder="Required - Insert new username" /></td>
								<td rowspan="6"><img src="images/default_user_icon_trans.png" alt="user_icon" border="1"></td>
							</tr>
							<tr>
								<td>Mail:</td> 
								<td><input type="text" name="newUserMail" placeholder="Required - Insert email" /></td>
							</tr>
							<tr>
								<td>Password:</td> 
								<td><input type="password" name="newPassword1" placeholder="Required - Please enter the new password" /></td>
							</tr>
							<tr>
								<td>Repeat Password:</td> 
								<td><input type="password" name="newPassword2" placeholder="Required - Please enter the new password again" /></td>
							</tr>
							<tr>
								<td>Send notification mail to new user: (optional)</td> 
								<td><input type="checkbox" name="sendNotification" value="sendNotification" /></td>
							</tr>
							<tr>
								<td>Admin note about this invite or user: (optional)</td> 
								<td><input type="text" name="newUserNote" placeholder="Optional - note about user" /></td>
							</tr>
							<tr>
								<td><input type="submit" name="doCreateNewUserAccount" value="Invite" /></td> 
								<td></td>
							</tr>
						</table>

					</form>
					
				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>

				<!-- MYSQL -->
				<h2><a name="mysql">mysql</a></h2>
				<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">	
					<input type="submit" name="doOptimize" value="Optimize" style="width:200px" />This will optimize your entire monoto mysql database.
					<br><br>
					<input type="submit" name="doTruncateEvents" value="Truncate events" style="width:200px" /> This will delete all entries from the table: m_events.
					<br>
					<input type="submit" name="doTruncateNotes" value="Truncate notes" style="width:200px" /> This will delete all notes from the table: m_notes.
				</form>

				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>

				<!-- MYSQL -->
				<h2><a name="misc">misc</a></h2>
					<!-- SHOW jquery version -->
					jquery version
					<div id="myResults"></div>
					<script type="text/javascript">
						$("#myResults").html(jQuery.fn.jquery);
					</script>

				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>	
			</div>
		</div>

		<!-- back to top -->
		<div id="message"><a href="#container">scroll to top</a></div>

		<!--  FOOTER -->
		<?php include 'footer.php'; ?>
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
		echo '<script type="text/javascript">alert("Notification: Tables optimized")</script>';
		disconnectFromDB();
	}




	//
	// TRUNCATE EVENTS
	//
	if ( isset($_POST["doTruncateEvents"]) ) 
	{
		connectToDB();  // connect to mysql

		// truncate table
		$res = mysql_query('TRUNCATE TABLE m_log');
		while($row = mysql_fetch_assoc($res)) 
		{		
		}
		echo '<script type="text/javascript">alert("Notification: Table m_log truncated.")</script>';
		disconnectFromDB();
	}




	//
	// TRUNCATE NOTES
	//
	if ( isset($_POST["doTruncateNotes"]) ) 
	{
		connectToDB();  // connect to mysql

		// truncate table
		$res = mysql_query('TRUNCATE TABLE m_notes');
		while($row = mysql_fetch_assoc($res)) 
		{		
		}
		echo '<script type="text/javascript">alert("Notification: Table m_notes truncated.")</script>';
		disconnectFromDB();
	}




	// CREATE NEW USER
	//
	if ( isset($_POST["doCreateNewUserAccount"]) ) 
	{
		connectToDB();  // connect to mysql

		$invite_from 	= $_SESSION['username'];
		$invite_target 	= $_SERVER['SERVER_NAME'];
		
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
					// check if we got an emailadress
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
						echo '<script type="text/javascript">alert("Notification: New account successfully created.")</script>';

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
		 									\n\nFeel free to use it as your personal notes keeper as well.
		 									\n\nYou can get some general informations about monoto here: https://github.com/macfidelity/monoto/wiki.
		 									\n\n\n\nThe login credentials are as follows:
		 									\n\nUsername: ".$username."
		 									\nPassword: ".$password."
		 									\n\n\nPlease change your password after your first visit at:
		 									\n".$invite_target."
		 									\n\nHave fun.";
		 						if (mail($to, $subject, $body)) 
		 						{
		   							echo '<script type="text/javascript">alert("Notification: Mail was sent.")</script>';
		  						} 
		  						else 
		  						{
		   							echo '<script type="text/javascript">alert("Error: Mail was NOT sent.")</script>';
		  						}
							}
						}
					}
					else // no usermail-adress defined while trying to create new account
					{
						echo '<script type="text/javascript">alert("Error: No email defined.")</script>';
					}
				}
				else // username already in use - cancel and inform the admin
				{
					echo '<script type="text/javascript">alert("Error: Username already exists.")</script>';
				}
			}
		}	
		else // passwords not matching
		{
			echo '<script type="text/javascript">alert("Error: You need to submit a password twice - not 2 different passwords. Classic typo i guess.")</script>';
		}
		disconnectFromDB();				// disconnect from mysql
	}
?>
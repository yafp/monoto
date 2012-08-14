<?php
	session_start();
	if(($_SESSION['valid'] == 1)	&& ($_SESSION['admin'] == 1))	// check if the user-session is valid or not AND if its an admin account.
	{
		include 'html_head.php';			// include the new header
?>
		<!-- continue the header -->
		<!-- ################### -->
		<!-- datatables -->
		<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
		<!-- m_accordionToc -->
		<script type="text/javascript" language="javascript" src="js/m_accordionToc.js"></script>
		<!-- flot graphs -->
		<script language="javascript" type="text/javascript" src="js/jquery.flot.js"></script>
    	<script language="javascript" type="text/javascript" src="js/jquery.flot.pie.js"></script>
		<!-- Example: http://datatables.net/release-datatables/examples/api/select_single_row.html -->
		<script type="text/javascript">
			var oTable;
	 
			$(document).ready(function() 
			{
			    /* Add a click handler to the rows - this could be used as a callback */
			    $("#example tbody tr").click( function( e ) {
			        if ( $(this).hasClass('row_selected') ) {
			            $(this).removeClass('row_selected');
			        }
			        else {
			            oTable.$('tr.row_selected').removeClass('row_selected');
			            $(this).addClass('row_selected');
			        }
			    });
			     
			    /* Add a click handler for the delete row */
			    $('#delete').click( function() {
			        var anSelected = fnGetSelected( oTable );
			        if ( anSelected.length !== 0 ) 
			        {
			            oTable.fnDeleteRow( anSelected[0] );
			            // mysql part is missing to really delete the user
			        }
			    } );
			     
			    /* Init the table */
			    oTable = $('#example').dataTable( );
			} );
			 
			 
			/* Get the rows which are currently selected */
			function fnGetSelected( oTableLocal )
			{
			    return oTableLocal.$('tr.row_selected');
			}
		</script>
	</head>

	<!-- BODY -->
	<body id="dt_example">
		<div id="container">
			<!-- HEADER & NAV -->
			<?php include 'header.php'; ?>
			<!-- CONTENT -->
			<div id="noteContentCo">
				<?php
					include 'conf/config.php';
					if($s_enable_toc == true)
					{
				?>
					<h2><a name="desc">admin</a></h2>
						<div class="accordion">
							<h3>admin settings [<a href="#basic">...</a>]</h3>
							<p><img src="images/info_icon.png" alt="info icon" width="40" style="float:right">the <a href="#basic">admin</a> section shows all server-wide monoto-settings. Those settings are configurable by the admin only and apply to all user accounts. The admin can modify those settings via 'conf/config.php'.</p>
							<h3>notes [<a href="#notes">...</a>]</h3>
							<p><img src="images/info_icon.png" alt="info icon" width="40" style="float:right">the <a href="#basic">notes</a> section gives a quick overview about the total amount of notes in the mysql database.</p>
							<h3>user list [<a href="#users">...</a>]</h3>
							<p><img src="images/info_icon.png" alt="info icon" width="40" style="float:right">the <a href="#users">users</a> section lists all existing user accounts. The table features the user-id, username, amout of logins and logouts, the invite date, the date of the first and the last login.</p>
							<h3>invites [<a href="#invites">...</a>]</h3>
							<p><img src="images/info_icon.png" alt="info icon" width="40" style="float:right">the <a href="#invites">invites</a> section allows you to create new user accounts. The admin can optional send a notification mail to the new user.</p>
							<h3>mysql [<a href="#mysql">...</a>]</h3>
							<p><img src="images/info_icon.png" alt="info icon" width="40" style="float:right">the <a href="#mysql">mysql</a> section allows you to to optimize or truncate your tables.</p>
							<h3>misc [<a href="#misc">...</a>]</h3>
							<p><img src="images/info_icon.png" alt="info icon" width="40" style="float:right">the <a href="#mysql">misc</a> section collects unsorted stuff.</p>
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
					<table style="width: 100%">
					<tbody>
						<tr>
							<td colspan="2" style="width:50%"><b>General</b></td>
							<td colspan="2" style="width:50%"><b>Page specific</b></td>
						</tr>
						<tr>
							<td style="width:30%">- enable toc:</td>
							<td style="width:20%"><?php if($s_enable_toc == false){ echo "<span>false</span>";}else{echo "<span>true</span>";} ?></td>
							<td style="width:30%">- enable about section on info page:</td>
							<td style="width:20%"><?php if($s_enable_info_about_section == false){ echo "<span>false</span>";}else{echo "<span>true</span>";} ?></td>
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
						<tr>
							<td>- enable unstable sources:</td>
							<td><?php if($s_enable_UnstableSources == false){ echo "<span>false</span>";}else{echo "<span>true</span>";} ?></td>
						</tr>
						<tr>
							<td>- enable random logout images:</td>
							<td><?php if($s_enable_random_logout_gif == false){ echo "<span>false</span>";}else{echo "<span>true</span>";} ?></td>
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

				<table style="width: 20%">
					<tr style="float:left"><th>notes</td><th>creator</td></tr>
					<!-- get notes count per user -->
					<?php
						$whatArray = array();			// define arrays for our flot pie graph
						$howMuchArray = array();

						$result = mysql_query("SELECT distinct owner, count(*) FROM m_notes GROUP by owner ORDER by COUNT(*) DESC LIMIT 0 , 30 "); // m_notes
						while($row = mysql_fetch_array($result))   // fill datatable
						{
							echo '<tr><td>'.$row[1].'</td><td>'.$row[0].'</td></tr>';		// fill table

							array_push($whatArray, $row[0]);								// fill array for graph
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
				<!--
				<p><a href="javascript:void(0)" id="delete">Dummy: Delete selected user (only hides it right now)</a></p>
				-->
				<!-- datatables showing our users -->
				<table cellpadding="0" cellspacing="0" class="display" id="example" style="width: 100%">
					<thead><tr><th>id</th><th>username</th><th>logins</th><th>logouts</th><th>failed logins</th><th>invite date</th><th>first login</th><th>last login</th><th>last failed login</th><th>mail</th><th>admin</th><th>comment</th></tr></thead>
					<tbody>
					<?php
							$result = mysql_query("SELECT id, username, login_counter, logout_counter, failed_logins, date_invite, date_first_login, date_last_login, date_last_login_fail, email, is_admin, admin_note  FROM m_users ORDER by id "); // m_log
							while($row = mysql_fetch_array($result))   // fill datatable
							{
								echo '<tr class="odd gradeU"><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td><td>'.$row[4].'</td><td>'.$row[5].'</td><td>'.$row[6].'</td><td>'.$row[7].'</td><td>'.$row[8].'</td><td>'.$row[9].'</td><td>'.$row[10].'</td><td>'.$row[11].'</td></tr>';
							}
					?>
					</tbody>
					<tfoot><tr><th>id</th><th>username</th><th>logins</th><th>logouts</th><th>failed logins</th><th>invite date</th><th>first login</th><th>last login</th><th>last failed login</th><th>mail</th><th>admin</th><th>comment</th></tr></tfoot>
				</table>

				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>

				<!-- INVITES -->
				<h2><a name="invites">invites</a></h2>
					<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">	
						<table style="width: 100%">
							<tr>
								<td width='30%'>Username:</td> 
								<td><input type="text" name="newUsername" placeholder="Required - Insert new username" /></td>
								<td rowspan="6"><img src="images/default_user_icon_trans.png" alt="user_icon"></td>
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
					<input type="submit" name="doTruncateEvents" value="Truncate events" style="width:200px" /> Warning: This will delete <b>ALL events</b> from the table: m_events.
					<br>
					<input type="submit" name="doTruncateNotes" value="Truncate notes" style="width:200px" /> Warning: This will delete <b>ALL notes</b> from the table: m_notes.
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
		echo '<script type="text/javascript">log.info("MySQL tables optimized.");</script>';					// blackbird js logging
	}

	//
	// TRUNCATE EVENTS
	//
	if ( isset($_POST["doTruncateEvents"]) ) 
	{
		connectToDB();  								// connect to mysql
		mysql_query('TRUNCATE TABLE m_log');			// truncate log-/events-table
		echo '<script type="text/javascript">log.info("Table m_log truncated.");</script>';					// blackbird js logging
	}

	//
	// TRUNCATE NOTES
	//
	if ( isset($_POST["doTruncateNotes"]) ) 
	{
		connectToDB();  								// connect to mysql
		mysql_query('TRUNCATE TABLE m_notes');			// truncate notes-table
		echo '<script type="text/javascript">log.info("Table m_notes truncated. Insane.");</script>';					// blackbird js logging
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
						echo '<script type="text/javascript">log.info("New user account created");</script>';					// blackbird js logging

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
		   							echo '<script type="text/javascript">log.info("Invite mail sent.");</script>';					// blackbird js logging
		  						} 
		  						else 
		  						{
		   							echo '<script type="text/javascript">log.warn("Sending invite mail failed.");</script>';					// blackbird js logging
		  						}
							}
						}
					}
					else // no usermail-adress defined while trying to create new account
					{
						echo '<script type="text/javascript">log.error("No mail address defined.");</script>';					// blackbird js logging
					}
				}
				else // username already in use - cancel and inform the admin
				{
					echo '<script type="text/javascript">log.error("Username already exists.");</script>';					// blackbird js logging
				}
			}
		}	
		else // passwords not matching
		{
			echo '<script type="text/javascript">log.error("You need to submit a password twice - not 2 different passwords. Classic typo i guess.");</script>';					// blackbird js logging
		}
	}
?>
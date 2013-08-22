<?php
	session_start();
	if(($_SESSION['valid'] == 1)	&& ($_SESSION['admin'] == 1))	// check if the user-session is valid or not AND if its an admin account.
	{
		include 'inc/html_head.php';			// include the new header
?>
		<!-- continue the header -->
		<!-- ################### -->
		<!--  m_keyPressAll-->
		<script type="text/javascript" language="javascript" src="js/m_keyPressAll.js"></script>

		<link rel="stylesheet" href="js/jquery-ui/jquery-ui.css" />
		<script src="js/jquery-ui/jquery-ui.js"></script>
		<script>
		$(function() {
		$( "#tabs" ).tabs();
		});
		</script>


		<!-- flot graphs -->
		<script language="javascript" type="text/javascript" src="js/jquery.flot.min.js"></script>
    	<script language="javascript" type="text/javascript" src="js/jquery.flot.pie.min.js"></script>
		<!-- Example: http://datatables.net/release-datatables/examples/api/select_single_row.html -->
		<script type="text/javascript">
			var oTable;
	 
			$(document).ready(function() 
			{
				// alert
				// information
				// error
				// warning
				// notification
				// success
				//
				var n = noty({text: 'Loaded admin', type: 'notification'});

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
			<div id="newHead">
			<?php include 'inc/header.php'; ?>
			</div>

			<!-- CONTENT -->
			<div id="noteContentCo">

				<?php
					include 'conf/config.php';
					include ('inc/db.php');		// connect to db
					connectToDB();

					if (file_exists('setup.php')) 	// check if setup.php still exists - if so - display a warning
					{
						echo "<br><br><font color='red'><b>Warning:</b>&nbsp;Please delete <i>setup.php</i>. It is a risk to keep that file.</font>";
					}
				?>
				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>

				<div id="tabs">
					<ul>
						<li><a href="#tabs-1">settings</a></li>
						<li><a href="#tabs-2">version</a></li>
						<li><a href="#tabs-3">notes</a></li>
						<li><a href="#tabs-4">users</a></li>
						<li><a href="#tabs-5">invites</a></li>
						<li><a href="#tabs-6">mysql</a></li>
						<li><a href="#tabs-7">misc</a></li>
					</ul>

					<div id="tabs-1">
						The following values are based on <i>/conf/config.php</i><br><br>
						<table style="width: 100%">
							<tbody>
								<tr>
									<td>- enable really delete question:</td>
									<td style="width: 50%"><?php if($s_enable_really_delete == false){ echo "<span>false</span>";}else{echo "<span>true</span>";} ?></td>
								</tr>
								<tr>
									<td>- enable really logout question:</td>
									<td><?php if($s_enable_really_logout == false){ echo "<span>false</span>";}else{echo "<span>true</span>";} ?></td>
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
								<tr>
									<td>- enable random images:</td>
									<td><?php if($s_enable_random_image == false){ echo "<span>false</span>";}else{echo "<span>true</span>";} ?></td>
								</tr>
							</tbody>
						</table>
					</div>

					<div id="tabs-2">
						<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
							<table style="width: 100%">									
								<tr>
									<td><b>build:</b></td>
									<td><span><?php echo $m_build; if($m_stable == false) { echo "</span>&nbsp;<font color='red'>Development Version (unstable)</font>"; } ?></td>
								<tr>
									<td><b>milestone:</b></td>
									<td><span><?php echo $m_milestone."</span> <i>aka</i> <span>".$m_milestone_title.""; ?></span></td>
								</tr>
								<tr>
									<td colspan="3">&nbsp;</td>
								</tr>
								<tr>
									<td><input type="submit" name="doUpdateCheck" value="Software Update" title="checks online for monoto updates" /></td>
									<td>
										<?php 
											if($s_enable_UnstableSources == true)
											{
												echo "Searching for <span>stable</span> and <span>unstable</span> versions";
											}
											else
											{
												echo "Searching only for <span>stable</span> versions.";
											}
									 	?>
									</td>
								</tr>
								<tr>
									<td><b>current stable:</b></td>
									<td><div id="curStable01"><i>please run the check</i></div></td>
									<td><div id="curStable02"><i>&nbsp;</i></div></td>
								</tr>
								<tr>
									<td><b>current unstable:</b></td>
									<td><div id="curUnstable01"><i>please run the check</i></div></td>
									<td><div id="curUnstable02"><i>&nbsp;</i></div></td>
									<td style="width: 30%">&nbsp;</td>
								</tr>
							</table>
						</form>

						<!-- SPACER -->
						<div class="spacer">&nbsp;</div>

						<!-- CHANGELOG-->
						<b>changelog</b>
						<textarea name="changes" style="width:100%" rows=20 disabled>
						<?=file_get_contents ('doc/CHANGELOG.txt');?>					
						</textarea>
					</div>

					<div id="tabs-3">
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
					</div>

					<div id="tabs-4">
						<!-- datatables showing our users -->
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
									<select name="userDeleteSelector">
									<?php
									$result = mysql_query("SELECT id, username  FROM m_users ORDER by id ");
									while($row = mysql_fetch_array($result))   // fill user-select box
									{
										//echo '<tr class="odd gradeU"><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td><td>'.$row[4].'</td><td>'.$row[5].'</td><td>'.$row[6].'</td><td>'.$row[7].'</td><td>'.$row[8].'</td><td>'.$row[9].'</td><td>'.$row[10].'</td><td>'.$row[11].'</td></tr>';
										echo '<option value="'.$row[0].'">'.$row[1].'</option>';
									}
									?>
						</select>
								</td>
							</tr>
							<tr>
								<td>Enter CONFIRM (uppercase)</td> 
								<td><input type="text" name="confirmDeleteUser" placeholder="no"></td>
							</tr>
							<tr>
								<td>Press the delete button to delete the user and all his notes plus all user-related events in the log</td> 
								<td><button type="submit" name="doDeleteUser">Delete</button> </td>
							</tr>
						</table>
						</form>
					</div>

					<div id="tabs-5">
						<form id="inviteForm" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">	
							<table style="width: 100%">
								<tr>
									<td width='30%'>Username:</td> 
									<td><input type="text" name="newUsername" placeholder="Required - Insert new username" required="required" /></td>
								</tr>
								<tr>
									<td>Mail:</td> 
									<td><input type="email" name="newUserMail" placeholder="Required - Insert email" required="required" /></td>
								</tr>
								<tr>
									<td>Password:</td> 
									<td><input type="password" name="newPassword1" placeholder="Required - Please enter the new password" required="required" /></td>
								</tr>
								<tr>
									<td>Repeat Password:</td> 
									<td><input type="password" name="newPassword2" placeholder="Required - Please enter the new password again" required="required" /></td>
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
									<td><input type="submit" name="doCreateNewUserAccount" value="Invite" title="Starts the add user function if all informations are provided." /></td> 
									<td>&nbsp;</td>
								</tr>
							</table>
						</form>
					</div>

					<div id="tabs-6">
						<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">	
							<input type="submit" name="doOptimize" value="Optimize" style="width:200px" title="Executes an optimize command on the tables if needed." />This will optimize your entire monoto mysql database.
							<br><br>
							<input type="submit" name="doTruncateEvents" value="Truncate events" style="width:200px" title="Deletes the entire content of the event-table. Affects all users. Be careful with that." /> Warning: This will delete <b>ALL events</b> from the table: m_events.
							<br>
							<input type="submit" name="doTruncateNotes" value="Truncate notes" style="width:200px" title="Deletes the entire content of the notes-table. Affects all users. Be careful with that too." /> Warning: This will delete <b>ALL notes</b> from the table: m_notes.
						</form>
					</div>

					<div id="tabs-7">
						<!-- SHOW jquery version -->
						jquery version
						<div id="myResults"></div>
						<script type="text/javascript">
							$("#myResults").html(jQuery.fn.jquery);
						</script>
					</div>
				</div>

				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>
		
			</div>
		</div>

		<!--  FOOTER -->
		<?php include 'inc/footer.php'; ?>
	</body>
</html>


<?php
	}
	else  //session is NOT valid
	{
		header('Location: redirect.php');
	}
   


	include 'conf/config.php';

	// UpdateCheck
	//
	// http://wuxiaotian.com/2009/09/php-check-for-updates-script/
	if ( isset($_POST["doUpdateCheck"]) ) 
	{
		session_start();
		include 'conf/config.php';
		
		// assume everything is good
		$critical = FALSE;
		$update = FALSE;

		//$url = "https://raw.github.com/macfidelity/monoto/master/vStable.csv";
		$url = "https://raw.github.com/macfidelity/monoto/master/conf/vStable.csv";
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
	  				{ window.location = "https://raw.github.com/macfidelity/monoto/master/versionCheck.csv","_blank"; } </script>';

			die(); //terminate the script
		}
		else if ($update)
		{

		}
		else // uptodate
		{

		}

		// update div with stable informations
		echo '<script type="text/javascript">document.getElementById("curStable01").innerHTML = "'.$read[0].'";</script>';
		$urlDLStable = "<a href='$read[3]'>Download</a>";
		echo '<script type="text/javascript">document.getElementById("curStable02").innerHTML = "'.$urlDLStable.'";</script>';

		//
		// check for unstable versions as well
		//
		if($s_enable_UnstableSources == true)
		{
			// assume everything is good
			$critical = FALSE;
			$update = FALSE;

			// check the csv file
			$url = "https://raw.github.com/macfidelity/monoto/master/conf/vDev.csv";
			$fp = @fopen ($url, 'r') or print ('UPDATE SERVER OFFLINE');
			$read = fgetcsv ($fp);
			fclose ($fp); 																//always a good idea to close the file connection

			// its critical
			if (($read[0] > $m_build) && ($read[2] == "1")) 
			{ $critical = TRUE; }
				
			// normal update
			if ($read[0] > $m_build) 
			{ $update = TRUE; }

			if ($critical) 
			{ 
		   		echo '<script type="text/javascript">
		   				var r=confirm("There is a critical dev update available. Should i download the latest version?")
						if (r==true)
		  				{ window.location = "https://raw.github.com/macfidelity/monoto/master/versionCheck.csv","_blank"; } </script>';

				die(); //terminate the script
			}
			else if ($update)
			{ 
				
			}
			else // uptodate
			{ 
				
			}

			// update div with unstable informations
			echo '<script type="text/javascript">document.getElementById("curUnstable01").innerHTML = "'.$read[0].'";</script>';
			$urlDLUnstable = "<a href='$read[3]'>Download</a>";
			echo '<script type="text/javascript">document.getElementById("curUnstable02").innerHTML = "'.$urlDLUnstable.'";</script>';
		}
	}


	//
	// DELETE USER
	//
	if ( isset($_POST["doDeleteUser"]) ) 
	{
		$userID 		= $_POST['userDeleteSelector'];
		$confirmText	= $_POST['confirmDeleteUser'];

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
			}
			mysql_close($con); 								// close sql connection
		}
		else // user hasnt entered CONFIRM
		{
			echo '<script>alert("Enter CONFIRM and try it again.");</script>';		// alert user that he hasnt entered CONFIRM
		}
		// reload page
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
	}

	//
	// TRUNCATE EVENTS
	//
	if ( isset($_POST["doTruncateEvents"]) ) 
	{
		connectToDB();  								// connect to mysql
		mysql_query('TRUNCATE TABLE m_log');			// truncate log-/events-table
	}

	//
	// TRUNCATE NOTES
	//
	if ( isset($_POST["doTruncateNotes"]) ) 
	{
		connectToDB();  								// connect to mysql
		mysql_query('TRUNCATE TABLE m_notes');			// truncate notes-table
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
							}
						}
					}
					else // no usermail-adress defined while trying to create new account
					{
					}
				}
				else // username already in use - cancel and inform the admin
				{
				}
			}
		}	
		else // passwords not matching
		{
		}
	}
?>
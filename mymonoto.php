<?php
	session_start();
	if($_SESSION['valid'] == 1)		// check if the user-session is valid or not
	{
		include 'inc/html_head.php';			// include the new header
?>
		<!-- continue the header -->
		<!-- ################### -->
		<script type="text/javascript" language="javascript" src="js/m_keyPressAll.js"></script>				<!--  m_keyPressAll-->
		<script type="text/javascript" language="javascript" src="js/digitalspaghetti.password.js"></script>	<!-- password -->


 		<link rel="stylesheet" href="js/jquery-ui/jquery-ui.css" />
		<script src="js/jquery-ui/jquery-ui.js"></script>
		<script>
		$(function() {
		$( "#tabs" ).tabs();
		});
		</script>


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
				"iDisplayLength": 50,									/* default rows */
				"bLengthChange": false,
				"bPaginate": true , 									/* pagination */
				"aaSorting": [[ 3, "desc" ]],							/* sorting */
				"aoColumns"   : [										/* visible columns */
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
			<?php include 'inc/header.php'; ?>
			<!-- CONTENT -->
			<div id="noteContentCo">
				
				<?php include ('conf/config.php'); ?>

				<h2>my monoto</h2>
				<div id="tabs">
					<ul>
						<li><a href="#tabs-1">profile</a></li>
						<li><a href="#tabs-2">stats</a></li>
						<li><a href="#tabs-3">logs</a></li>
						<li><a href="#tabs-4">importer</a></li>
						<li><a href="#tabs-5">exporter</a></li>
						<li><a href="#tabs-6">eraser</a></li>
					</ul>

					<div id="tabs-1">
						<!-- PROFILE -->
						<table style="width: 100%">
							<tr>
								<td style="width:25%" colspan="2"><img src="images/icons/user-14.png" alt="dummy user icon" title="Dummy user-profile-image"></td>
								<td style="width:5%"></td>
								<td rowspan="5">
									<!-- CHANGE USER PASSWORD BUTTON -->
									<b>Changing password:</b><br>Please enter your new password twice and confirm that change by pressing the <span>Update</span> button.
									<form id="changePassword" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
												<input type="password" id="newPassword1" name="newPassword1" placeholder="Please enter your new password" required="required" />
												<input type="password" id="newPassword2" name="newPassword2" placeholder="Repeat new password" required="required" />
												<script type="text/javascript">jQuery('#newPassword1').pstrength();</script><br>
												<input type="submit" name="doChangeUserPW" value="Update" style="width:140px" title="Starts the change password function if the user provided the new password twice." />					
									</form>
									<!-- CHANGE USER ICON BUTTON -->
									<br><b>Changing the user icon:</b><br>Select your new user icon via the <span>Browse...</span> button and confirm that change by pressing the <span>Upload</span> button. This will store your image in the users-table - but the image itself is not used so far.
									<form id="changeIcon" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
												<input name="MAX_FILE_SIZE" value="102400" type="hidden">
												<input name="image" accept="image/jpeg" type="file"><br>
												<input value="Upload" type="submit" name="doChangeUserIcon" style="width:140px" title="Starts the user icon image upload function if the user provided a valid path to an image." >					
									</form>
								</td>
							</tr>
							<tr>
								<td>name:</td>
								<td><?php echo "<span>".$_SESSION['username']."</span>" ?></td>
								<td></td>
							</tr>
							<tr>
								<td>mail:</td>
								<td>
									<?php
										include 'inc/db.php';						// connect to db
										connectToDB();

										$sql="SELECT email FROM m_users WHERE username='".$_SESSION['username']."' ";				// mail
										$result = mysql_query($sql);
										while($row = mysql_fetch_array($result)) 					
										{
											echo "<span>".$row[0]."</span>";
										}
									?>
								</td>
								<td></td>
							</tr>
							<tr>
								<td>logins:</td>
								<td>
									<?php
										$sql="SELECT login_counter FROM m_users WHERE username='".$_SESSION['username']."' ";		// login_counter
										$result = mysql_query($sql);
										while($row = mysql_fetch_array($result)) 					
										{
											echo "<span>".$row[0]."</span>";
										}
									?>
								</td>
								<td></td>
							</tr>
							<tr>
								<td>since:</td>
								<td>
									<?php
										$sql="SELECT date_first_login FROM m_users WHERE username='".$_SESSION['username']."' ";	// date first login
										$result = mysql_query($sql);
										while($row = mysql_fetch_array($result)) 					
										{
											echo "<span>".$row[0]."</span>";
										}
									?>
								</td>
								<td></td>
							</tr>
						</table>
					</div>

					<div id="tabs-2">
						<?php
							connectToDB();
							$owner = $_SESSION['username'];
								
							// User: amount of notes 
							$result = mysql_query("SELECT count(*) FROM m_notes WHERE owner='".$owner."' "); 					// run the mysql query
							while($row = mysql_fetch_array($result)) 								// fetch data and file table as a second step later on
							{
									// If current User < 1 note - is it worth displaying the stats at all?
									if($row[0] == 0)
									{
										echo "Lazy ass award goes to you as you havent created a single note .....erm yes ... ".$row[0]." notes in your monoto database."; 	// blame user that he has no notes
										// is someone else storing notes?
										$result = mysql_query("SELECT count(*) FROM m_notes");
										while($row = mysql_fetch_array($result)) 
										{
											if($row[0] == 0)
											{ echo " Even worse ... there is not a single note by any other user.<br>"; }
											else
											{ echo " But at least other users seem to save notes to the database. Give it some love dude.<br>"; }
										}
									}
									else
									{
										echo "- You have <span>".$row[0]." personal notes</span> in the monoto database<br>"; 	// output amount of notes
										// SQL-SECTION
										//
										// amount of activity-events
										$result = mysql_query("SELECT count(*) FROM m_log WHERE owner='".$owner."' "); 
										while($row = mysql_fetch_array($result)) 					
										{ $stats_events_of_current_user = $row[0]; }
										// amount of create-events
										$result = mysql_query("SELECT count(*) FROM m_log WHERE event='create' and owner='".$owner."' "); 
										while($row = mysql_fetch_array($result)) 					
										{ $stats_amount_of_creates = $row[0]; }
										// amount of create-error events
										$result = mysql_query("SELECT count(*) FROM m_log WHERE event='create error' and owner='".$owner."' "); 
										while($row = mysql_fetch_array($result)) 					
										{ $stats_amount_of_creates_errors = $row[0]; }
										// amount of import-events
										$result = mysql_query("SELECT count(*) FROM m_log WHERE event='import' and owner='".$owner."' "); 
										while($row = mysql_fetch_array($result)) 					
										{ $stats_amount_of_imports = $row[0]; }
										// amount of edits-events
										$result = mysql_query("SELECT count(*) FROM m_log WHERE event='save' and owner='".$owner."' "); 
										while($row = mysql_fetch_array($result)) 					
										{ $stats_amount_of_changes = $row[0]; }
										// amount of delete-events
										$result = mysql_query("SELECT count(*) FROM m_log WHERE event='delete' and owner='".$owner."' "); 
										while($row = mysql_fetch_array($result)) 					
										{ $stats_amount_of_deletes = $row[0]; }
										// amount of logins and logouts
										$result = mysql_query("SELECT login_counter, logout_counter FROM m_users WHERE username='".$_SESSION['username']."' "); 
										while($row = mysql_fetch_array($result)) 					
										{ 
											$stats_amount_of_logins = $row[0];
											$stats_amount_of_logouts = $row[1];
										}
										//  version: highest note-version (most used note)
										$result = mysql_query("SELECT id, title, save_count FROM m_notes WHERE owner='".$owner."'ORDER BY save_count DESC LIMIT 1"); 
										while($row = mysql_fetch_array($result)) 					
										{
											$stats_highest_note_version_id = $row[0];
											$stats_highest_note_version_title = $row[1];
											$stats_highest_note_version_versions = $row[2];
										}
										//  shortest and longest note-content
										$result = mysql_query("SELECT MIN( LENGTH( content ) ) AS shortest, id FROM m_notes WHERE owner='".$owner."'"); 
										while($row = mysql_fetch_array($result)) 					
										{
											$stats_note_with_shortest_content_id = $row[1];
											$stats_note_with_shortest_content_chars = $row[0];
										}
										//  longest note-content
										$result = mysql_query("SELECT ( LENGTH( content ) ) AS longest, id FROM m_notes WHERE owner='".$owner."' ORDER BY longest DESC LIMIT 1"); 
										while($row = mysql_fetch_array($result)) 					
										{
											$stats_note_with_longest_content_id = $row[1];
											$stats_note_with_longest_content_chars = $row[0];
										}
										//  oldest created note
										$result = mysql_query("SELECT DATEDIFF(CURDATE(), date_create) AS intval, date_create, id, title FROM m_notes WHERE owner='".$owner."' ORDER BY date_create ASC LIMIT 1"); 
										while($row = mysql_fetch_array($result)) 					
										{
											$stats_oldest_created_note_age = $row[0];
											$stats_oldest_created_note_date = $row[1];
											$stats_oldest_created_note_id = $row[2];
										}
										//  newest/latest created note
										$result = mysql_query("SELECT DATEDIFF(CURDATE(), date_create) AS intval, date_create, save_count, title, id FROM m_notes WHERE save_count = '1' and owner='".$owner."' ORDER BY date_create DESC LIMIT 1"); 
										while($row = mysql_fetch_array($result)) 					
										{
											$stats_latest_created_note_age = $row[0];
											$stats_latest_created_note_date =  $row[1];
											$stats_latest_created_note_id = $row[4];
											$stats_latest_created_note_title = $row[3];
										}
										//  latest edited note
										$result = mysql_query("SELECT DATEDIFF(CURDATE(), date_create) AS intval, date_mod, save_count, title, id FROM m_notes ORDER BY date_create DESC LIMIT 1"); 
										while($row = mysql_fetch_array($result)) 					
										{
											$stats_last_edited_note_age = $row[0];
											$stats_last_edited_note_id = $row[4];
											$stats_last_edited_note_title = $row[3];
											$stats_last_edited_note_date = $row[1];
										}
										//  overall_note_content_words
										$result = mysql_query("SELECT SUM( LENGTH( content ) - LENGTH( REPLACE( content, ' ', '' ) ) +1 ) FROM m_notes WHERE owner='".$owner."' "); 
										while($row = mysql_fetch_array($result)) 					
										{
											$stats_overall_content_words = $row[0];
										}
										//  overall_note_title_words
										$result = mysql_query("SELECT SUM( LENGTH( title ) - LENGTH( REPLACE( title, ' ', '' ) ) +1 ) FROM m_notes WHERE owner='".$owner."' "); 
										while($row = mysql_fetch_array($result)) 					
										{
											$stats_overall_title_words = $row[0];
										}
										//  entire db size
										$result = mysql_query("SELECT sum( data_length + index_length ) /1024 /1024 FROM information_schema.TABLES WHERE table_schema = '".$mysql_db."' "); 
										while($row = mysql_fetch_array($result)) 					
										{
											$stats_entire_monoto_db_size = $row[0];
										}
										// DISPLAY  stats
										//
										// EVENTS
										// - $stats_events_of_current_user
										// 		- $stats_amount_of_creates
										//		- $stats_amount_of_creates_errors
										//		- $stats_amount_of_imports
										//		- $stats_amount_of_changes
										//		- $stats_amount_of_deletes
										//		- $stats_amount_of_logins
										//		- $stats_amount_of_logouts
										//
										// HIGHEST NOTE per VERSION:
										// - $stats_highest_note_version_id 
										// - $stats_highest_note_version_title 
										// - $stats_highest_note_version_versions
										//
										// SHORTEST NOTE per char
										// - $stats_note_with_shortest_content_id 
										// - $stats_note_with_shortest_content_chars
										//
										// LONGEST NOTE per char
										// - $stats_note_with_longest_content_id 
										// - $stats_note_with_longest_content_chars
										//
										// OLDEST NOTE
										// - $stats_oldest_created_note_age
										// - $stats_oldest_created_note_date
										// - $stats_oldest_created_note_id
										//
										// LATEST CREATED
										// - $stats_latest_created_note_age
										// - $stats_latest_created_note_date
										// - $stats_latest_created_note_id
										// - $stats_latest_created_note_title
										//
										// LAST EDITED
										// $stats_last_edited_note_age
										// $stats_last_edited_note_id
										// $stats_last_edited_note_title
										// $stats_last_edited_note_date
										//
										// WORDS
										// - $stats_overall_content_words
										// - $stats_overall_title_words
										//
										// DB-SIZE
										// - $stats_entire_monoto_db_size
										//
										// Use our variables to create some kind of LOG text - should be informative but still funny if possible.
										echo "- Those notes are using <span>".$stats_overall_title_words." words</span> for titles and overall <span>".$stats_overall_content_words." words</span> for the content.<br>";
										echo "- The personal event log has recorded <span>".$stats_events_of_current_user." events</span> for this account.<br>";
										echo "- Those can be devided into <span>".$stats_amount_of_creates." notes creations</span>, <span>".$stats_amount_of_changes." note-editings</span> and <span>".$stats_amount_of_deletes." notes-deletions</span>.<br>";
										echo "- In addition to those numbers your account has <span>".$stats_amount_of_imports." note-import events</span> logged. But keep in mind that 1 import event can contain more then 1 note.<br>";
										echo "- Plus <span>".$stats_amount_of_creates_errors."</span> failed create errors.<br>";
										echo "- Well in case numbers still dont match up - add <span>".$stats_amount_of_logins." logins</span> and <span>".$stats_amount_of_logouts." logouts</span>.<br>";
										echo "- Your highest note id is currently <span>".$stats_highest_note_version_id."</span>, with the title <span>".$stats_highest_note_version_title."</span>. This specific note has <span>revision number ".$stats_highest_note_version_versions."</span>.<br>";
										echo "- Your shortest note so far is note <span>number ".$stats_note_with_shortest_content_id."</span>, it is <span>using ".$stats_note_with_shortest_content_chars." chars</span> for its entire content.<br>";
										echo "- Lets compare that with your longest note which has the <span>id ".$stats_note_with_longest_content_id."</span> and is <span>".$stats_note_with_longest_content_chars." long</span>.<br>";
										echo "- Looking for dates? Let's face it: your oldest note has an <span>age of ".$stats_oldest_created_note_age." days</span>. It was created <span>".$stats_oldest_created_note_date."</span> with the <span>id ".$stats_oldest_created_note_id."</span>.<br>";
										echo "- In comparison - your latest created note has the <span>age of ".$stats_latest_created_note_age." days</span>, has the <span>id ".$stats_latest_created_note_id."</span>, the title <span>".$stats_latest_created_note_title."</span> and a creation date of <span>".$stats_latest_created_note_date."</span>.<br>";
										echo "- The last note you actually edited was note <span>".$stats_last_edited_note_id."</span> with the title <span>".$stats_last_edited_note_title."</span>. This edit is <span>".$stats_last_edited_note_age." days</span> old - from <span>".$stats_last_edited_note_date."</span> in case you bother.<br>";
										echo "- Lets come to the end - the entire monoto db has a size of <span>".$stats_entire_monoto_db_size."  MB</span>.<br>";
									}
							}
					?>
					</div>

					<div id="tabs-3">
						<table style="width:100%">
						<thead><tr><th style="float:left" style="width:20%">event</th><th style="float:left" style="width:60%">description</th><th style="float:left" style="width:20%">count</th></tr></thead>
						<tbody>
							<tr>
								<td>create</td>
								<td>Note was created, version counter = 1, date created and modified set</td>
								<td>
								<?php
									$owner = $_SESSION['username'];
									$result = mysql_query("SELECT count(event) FROM m_log WHERE event = 'create'  and owner='".$owner."'   "); 
									while($row = mysql_fetch_array($result))
									{ echo $row[0]; }
								?>
								</td>
							</tr>
							<tr>
								<td>create error</td>
								<td>Error while trying to create a note. The title was already in use.</td>
								<td>
								<?php
									$owner = $_SESSION['username'];
									$result = mysql_query("SELECT count(event) FROM m_log WHERE event = 'create error'  and owner='".$owner."'   "); 
									while($row = mysql_fetch_array($result))
									{ echo $row[0]; }
								?>
								</td>
							</tr>
							<tr>
								<td>import</td>
								<td>Note was imported using the importer, version counter = 1, date created and modified set</td>
								<td>
									<?php
										$result = mysql_query("SELECT count(event) FROM m_log WHERE event = 'import' and owner='".$owner."' "); 
										while($row = mysql_fetch_array($result))
										{ echo $row[0]; }
								?>
								</td>
							</tr>
							<tr>
								<td>save</td>
								<td>Content was changed, note saved, version counter +1, date modified set</td>
								<td>
									<?php
										$result = mysql_query("SELECT count(event) FROM m_log WHERE event = 'save' and owner='".$owner."' "); 
										while($row = mysql_fetch_array($result))
										{ echo $row[0]; }
								?>
								</td>
							</tr>
							<tr>
								<td>delete</td>
								<td>Note was deleted, id/number is doomed forever.</td>
								<td>
									<?php
										$result = mysql_query("SELECT count(event) FROM m_log WHERE event = 'delete' and owner='".$owner."' "); 
										while($row = mysql_fetch_array($result))
										{ echo $row[0]; }
								?>
								</td>
							</tr>
							<tr>
								<td>login</td>
								<td>User login - logincounter +1</td>
								<td>
									<?php							
										$result = mysql_query("SELECT count(event) FROM m_log WHERE event = 'login' and owner='".$owner."' "); 
										while($row = mysql_fetch_array($result))
										{ echo $row[0]; }
								?>
								</td>
							</tr>
							<tr>
								<td>login failed </td>
								<td>User login - failcounter +1</td>
								<td>
									<?php							
										$result = mysql_query("SELECT count(event) FROM m_log WHERE event = 'login error' and owner='".$owner."' "); 
										while($row = mysql_fetch_array($result))
										{ echo $row[0]; }
								?>
								</td>
							</tr>
							<tr>
								<td>logout</td>
								<td>User logout - logoutcounter +1</td>
								<td>
									<?php							
										$result = mysql_query("SELECT count(event) FROM m_log WHERE event = 'logout' and owner='".$owner."' "); 
										while($row = mysql_fetch_array($result))
										{ echo $row[0]; }
								?>
								</td>
							</tr>
							<tr>
								<td>notes eraser</td>
								<td>All user notes deleted</td>
								<td>
									<?php							
										$result = mysql_query("SELECT count(event) FROM m_log WHERE event = 'notes eraser' and owner='".$owner."' "); 
										while($row = mysql_fetch_array($result))
										{ echo $row[0]; }
								?>
								</td>
							</tr>
							<tr>
								<td>events eraser</td>
								<td>All user events deleted</td>
								<td>
									<?php							
										$result = mysql_query("SELECT count(event) FROM m_log WHERE event = 'events eraser' and owner='".$owner."' "); 
										while($row = mysql_fetch_array($result))
										{ echo $row[0]; }
								?>
								</td>
							</tr>
						</tbody>
						</table>
						
						<!-- ACTIVITY/EVENT LOG -->
						<table cellpadding="0" cellspacing="0" class="display" id="example" style="width:100%">
							<thead>
								<tr><th>id</th><th>event</th><th>details</th><th>timestamp</th></tr>
							</thead>
							<tbody>
							<?php
									$result = mysql_query("SELECT * FROM m_log WHERE owner='".$owner."' "); // m_log
									while($row = mysql_fetch_array($result))   // fill datatable
									{
										echo '<tr class="odd gradeU"><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td></tr>';
									}
							?>
							</tbody>
							<tfoot><tr><th>id</th><th>event</th><th>details</th><th>timestamp</th></tr></tfoot>
						</table>

					</div>

					<div id="tabs-4">
						<!-- IMPORTER - http://stackoverflow.com/questions/5593473/how-to-upload-and-parse-a-csv-file-in-php -->
						<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data" name="importerForm">
							<input type="file" name="file[]" multiple id="file[]" /><br>
							<input type="submit" name="doImport" value="Import"  style="width:140px" title="Starts the import function if the user provided a valid selection of files. Might break with bigger amount of text-notes." />
							<textarea class="database" disabled="disabled" id="importLog" style="width:100%" name="importLog" cols="110" rows="5" placeholder="Output of impoter will be displayed here."></textarea>
						</form>
					</div>

					<div id="tabs-5">
						<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
							<input type="submit" name="doExport" value="Export" style="width:140px" title="Exports all your notes into a .csv file which might be useful" />
						</form>
					</div>

					<div id="tabs-6">
						<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
							<input type="submit" name="doDelAllNotes" value="Delete Notes" style="width:140px" title="Deletes all notes from your account. Be careful with that" /><br>
							<input type="submit" name="doDelAllEvents" value="Delete Events" style="width:140px" title="Deletes all log events from your account. Be careful with that too" />
						</form>
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
	else 				//session is NOT valid - redirect to login
	{	
		header('Location: redirect.php'); 	
	}
?>


<?php
// CASES
//
// - Do Delete all Notes
// - Do Delete all Events
// - Do Export
// - Do Import
// - Do Change USerpassword
include 'conf/config.php';

if ( isset($_POST["doChangeUserPW"]) ) 
{
	include 'conf/config.php';
	connectToDB();

	// get values
	$owner = $_SESSION['username'];
	$newPassword1 = $_POST['newPassword1'];
	$newPassword2 = $_POST['newPassword2'];
	$password = $newPassword1;
	$username = $owner;

	// Check if user entered two times the same new password
	if($newPassword1 == $newPassword2)
	{
		$hash = hash('sha256', $password);								// playing with hash
		function createSalt() 											// playing with salt - creates a 3 character sequence
		{
    		$string = md5(uniqid(rand(), true));
    		return substr($string, 0, 3);
		}
		$salt = createSalt();
		$hash = hash('sha256', $salt . $hash);

		$query = "UPDATE m_users SET  password='$hash', salt='$salt' WHERE username='$owner'";			// change pw
		mysql_query($query);
	}
	else // User entered 2 different password - cant change pw like that.
	{
	}
}


//
// delAllNotes - button was pressed
//                 
if ( isset($_POST["doDelAllNotes"]) ) 
{	
	include ('conf/config.php');			// connect to db
	connectToDB();
	$owner = $_SESSION['username'];

	$sql="DELETE FROM m_notes WHERE owner='$owner'";		// update m_notes = delete notes
	$result = mysql_query($sql);

	// update m_log
	$event = "notes eraser";
	$details = "All user notes deleted with eraser.";
	$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(), '$owner' )";
	$result = mysql_query($sql);
}


//
// delAllEvents - button was pressed
//                 
if ( isset($_POST["doDelAllEvents"]) ) 
{	
	include ('conf/config.php');
	connectToDB();
	$owner = $_SESSION['username'];

	// update m_notes = delete events
	$sql="DELETE FROM m_log WHERE owner='".$owner."' ";
	$result = mysql_query($sql);

	// update m_log
	$event = "events eraser";
	$details = "All user events deleted with eraser.";
	$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(), '$owner' )";
	$result = mysql_query($sql);
}


//
// exporter - submit button was pressed - open download in new tab/window
//
if ( isset($_POST["doExport"]) ) 
{
	echo '<script type="text/javascript" language="javascript">window.open("inc/expNotes.php", "width=400,height=500,top=50,left=280,resizable,toolbar,scrollbars,menubar,");</script>';				
}



//
// importer submit button was pressed
//
if ( isset($_POST["doImport"]) ) 
{
	connectToDB();
	$owner = $_SESSION['username'];
	$good_counter = 0;

	// loop it for each note
	foreach($_FILES['file']['name'] as $key => $value)
	{
		echo "<font color='white'>Trying to import: ".$newNoteTitle = $_FILES["file"]["name"][$key]."<br></font>";

		//if file already exists
	   	if (file_exists("upload/" . $_FILES["file"]["name"])) 
	    {
	     	echo $_FILES["file"]["name"] . " already exists. ";
	   	}
	    else 
	    {
			// define insert vars
			$newNoteTitle = $_FILES["file"]["name"][$key];
			$newNoteTitle = preg_replace("/\\.[^.\\s]{3,4}$/", "", $newNoteTitle);					// we need to cut the extension from filename - ugly hack
			$newNoteContent = file_get_contents($_FILES['file']['tmp_name'][$key]);

			// check if there is already a note with this title - as we dislike having > 1 note with the same title ...yes we do
			if(mysql_num_rows(mysql_query("SELECT title FROM m_notes WHERE title = '$newNoteTitle'")))
			{
				?>
				<script type="text/javascript">
					var newtext = '<?php echo "Error - there is already a note with the title: ".$newNoteTitle.". Import of that specific note was skipped."; ?>';
					document.importerForm.importLog.value += newtext;
				</script>
				<?php

				// add log entry that importing failed cause title is already in use
				$newNoteContentSummary = substr($newNoteContent, 0, 10);
				$event = "import";
				$details = "Note: <b>".$newNoteTitle."</b> with content: <b>".$newNoteContentSummary."...</b> was NOT imported as the title is already in use.";
				$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event','$details', now(), '$owner' )";
				$result = mysql_query($sql);
			}
			else // we can create it - update notes: m_notes
				{
					$sql="INSERT INTO m_notes (title, content, save_count,  date_create, date_mod, owner) VALUES ('$newNoteTitle', '$newNoteContent', '1',now(), now(), '$owner' )";
					$result = mysql_query($sql);
					if (!$result) 
					{	
						die('Error: ' . mysql_error());	
					}
					else  // update event-log: m_log
					{
						$newNoteContentSummary = substr($newNoteContent, 0, 10);
						$event = "import";
						$details = "Note: <b>".$newNoteTitle."</b> with content: <b>".$newNoteContentSummary."...</b>";
						$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event','$details', now(), '$owner' )";
						$result = mysql_query($sql);
						?>
							<script type="text/javascript">
								var newtext = '<?php echo "Note: ".$newNoteTitle." successfully imported. "; ?>';
								document.importerForm.importLog.value += newtext;
							</script>
		<?php
					$good_counter = $good_counter +1;
					}					
				} 	
	      	}
		}

		// output summary
		$amount_of_import_files = $key +1;
		if($good_counter == $amount_of_import_files)
		{
		?>
				<script type="text/javascript">
					var newtext = '<?php echo "Finished importing ".$amount_of_import_files." notes - all got imported without issues."; ?>';
					document.importerForm.importLog.value += newtext;
				</script>
		<?php
		}
		else
		{
			?>
				<script type="text/javascript">
					var newtext = '<?php echo "Finished importing. Importer was only able to import".$good_counter." from ".$amount_of_import_files." notes. Sorry for the trouble.<br>"; ?>';
					document.importerForm.importLog.value += newtext;
				</script>
			<?php
		}
} 


//
// Changing User icon
//
if ( isset($_POST["doChangeUserIcon"]) ) 
{
	connectToDB();
	$owner = $_SESSION['username'];

	// is there a new file at all?
	if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) 
	{ 
        $tmpName  = $_FILES['image']['tmp_name'];  		// Temporary file name stored on the server
           
        // Read the file 
        $fp     = fopen($tmpName, 'r');
        $data = fread($fp, filesize($tmpName));
        $data = addslashes($data);
        fclose($fp);
             
        $query = "UPDATE m_users SET  user_icon='$data' WHERE username='$owner'";						// update user record 
		mysql_query($query);
	}
	else // no image defined.
	{
	}
}
?>
<?php
	session_start();
	include 'conf/config.php';
	if($_SESSION['valid'] != 1)			// check if the user-session is valid or not
	{
		header('Location: redirect.php');
	}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="shortcut icon" type="image/ico" href="images/favicon.ico" />
		<title>monoto notes</title>
		
		<!-- META STUFF -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="monoto notes">
		<meta name="author" content="florian poeck">

		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="css/table.css" />
		<link rel="stylesheet" type="text/css" href="css/page01.css" title="default" /> 
		<link rel="stylesheet" href="images/font-awesome-4.0.3/css/font-awesome.min.css">
		<link href="css/bootstrap.min.css" rel="stylesheet">		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap-theme.min.css" rel="stylesheet">		<!-- Bootstrap theme -->
		
		<!-- JS-->
		<script type="text/javascript" src="js/jquery/jquery-2.1.0.min.js"></script>		<!-- jquery itself -->
		<script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>		<!-- datatables -->
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('#example').dataTable();
			} );
		</script>
	</head>



	<body role="document">
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
					<a class="navbar-brand" href="notes.php"><img src="images/icons/monoto_logo01.png" height="25"></a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li><a href="notes.php"><i class="fa fa-pencil-square-o fa-1x"></i> Notes</a></li>
						<li class="active"><a href="mymonoto.php"><i class="fa fa-user fa-1x"></i> MyMonoto</a></li>
						<li><a href="keyboard.php"><i class="fa fa-keyboard-o fa-1x"></i> Keyboard</a></li>
						<?php
							if($_SESSION['admin'] == 1) // show admin-section
							{
								echo '<li><a href="admin.php"><i class="fa fa-cogs fa-1x"></i> Admin</a></li>';
							}
						?>
						<li><a href="#" onclick="reallyLogout();"><i class="fa fa-power-off fa-1x"></i> Logout</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="container theme-showcase" role="main">



		<div id="container">
			<div id="noteContentCo">
				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>
				<div class="spacer">&nbsp;</div>
				<h1><i class="fa fa-user fa-1x"></i> MyMonoto</h1>
				<!-- PROFILE -->
				<h3>Profile</h3>
				<hr>
				<table style="width: 100%" border="0"">
					<tr>
						<td style="width:25%" colspan="2"></td>
						<td style="width:5%"></td>
						<td rowspan="5">
						<!-- CHANGE USER PASSWORD BUTTON -->
						<b>Changing password:</b><br>Please enter your new password twice and confirm that change by pressing the <span>Update</span> button.
						<form id="changePassword" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
							<input type="password" id="newPassword1" name="newPassword1" placeholder="Please enter your new password" required="required" />
							<input type="password" id="newPassword2" name="newPassword2" placeholder="Repeat new password" required="required" />
							<input type="submit" name="doChangeUserPW" value="Update" style="width:140px" title="Starts the change password function if the user provided the new password twice." />					
						</form>
						
						</td>
						</tr>
						<tr>
							<td>name:</td>
							<td><?php echo "<span class='blue'>".$_SESSION['username']."</span>" ?></td>
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
										echo "<span class='blue'>".$row[0]."</span>";
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
										echo "<span class='blue'>".$row[0]."</span>";
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
										echo "<span class='blue'>".$row[0]."</span>";
									}
								?>
							</td>
							<td></td>
						</tr>
				</table>
				
				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>
				
				<h3>Stats</h3>
				<hr>
				<!-- STATS -->
					<div id="tabs-2">
						<?php
							connectToDB();
							$owner = $_SESSION['username'];
								
							// User: amount of notes 
							$result = mysql_query("SELECT count(*) FROM m_notes WHERE owner='".$owner."' "); 					// run the mysql query
							while($row = mysql_fetch_array($result)) 								// fetch data and file table as a second step later on
							{
									echo "Some facts about your notes:<br><br>";

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
										echo "- You have <span class='blue'>".$row[0]." personal notes</span> in the monoto database<br>"; 	// output amount of notes
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
										echo "- Those notes are using <span class='blue'>".$stats_overall_title_words." words</span> for titles and overall <span class='blue'>".$stats_overall_content_words." words</span> for the content.<br>";
										echo "- The personal event log has recorded <span class='blue'>".$stats_events_of_current_user." events</span> for this account.<br>";
										echo "- Those can be devided into <span class='blue'>".$stats_amount_of_creates." notes creations</span>, <span class='blue'>".$stats_amount_of_changes." note-editings</span> and <span class='blue'>".$stats_amount_of_deletes." notes-deletions</span>.<br>";
										echo "- In addition to those numbers your account has <span class='blue'>".$stats_amount_of_imports." note-import events</span> logged. But keep in mind that 1 import event can contain more then 1 note.<br>";
										echo "- Plus <span class='blue'>".$stats_amount_of_creates_errors."</span> failed create errors.<br>";
										echo "- Well in case numbers still dont match up - add <span class='blue'>".$stats_amount_of_logins." logins</span> and <span class='blue'>".$stats_amount_of_logouts." logouts</span>.<br>";
										echo "- Your highest note id is currently <span class='blue'>".$stats_highest_note_version_id."</span>, with the title <span class='blue'>".$stats_highest_note_version_title."</span>. This specific note has <span class='blue'>revision number ".$stats_highest_note_version_versions."</span>.<br>";
										echo "- Your shortest note so far is note <span class='blue'>number ".$stats_note_with_shortest_content_id."</span>, it is <span class='blue'>using ".$stats_note_with_shortest_content_chars." chars</span> for its entire content.<br>";
										echo "- Lets compare that with your longest note which has the <span class='blue'>id ".$stats_note_with_longest_content_id."</span> and is <span class='blue'>".$stats_note_with_longest_content_chars." long</span>.<br>";
										echo "- Looking for dates? Let's face it: your oldest note has an <span class='blue'>age of ".$stats_oldest_created_note_age." days</span>. It was created <span class='blue'>".$stats_oldest_created_note_date."</span> with the <span class='blue'>id ".$stats_oldest_created_note_id."</span>.<br>";
										echo "- In comparison - your latest created note has the <span class='blue'>age of ".$stats_latest_created_note_age." days</span>, has the <span class='blue'>id ".$stats_latest_created_note_id."</span>, the title <span class='blue'>".$stats_latest_created_note_title."</span> and a creation date of <span class='blue'>".$stats_latest_created_note_date."</span>.<br>";
										echo "- The last note you actually edited was note <span class='blue'>".$stats_last_edited_note_id."</span> with the title <span class='blue'>".$stats_last_edited_note_title."</span>. This edit is <span class='blue'>".$stats_last_edited_note_age." days</span> old - from <span class='blue'>".$stats_last_edited_note_date."</span> in case you bother.<br>";
										echo "- Lets come to the end - the entire monoto db of all users has a size of <span class='blue'>".$stats_entire_monoto_db_size."  MB</span>.<br>";
									}
							}
					?>





				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>



				<!-- ACTIVITY/EVENT LOG -->
				<h3>Activity Log</h3>
				<hr>
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
				<br>
				<br>
				<div class="row">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Legend</h3>
						</div>
						<div class="panel-body">
							<table style="width:100%">
								<thead><tr><th style="width:20%">event</th><th style="width:60%">description</th></tr></thead>
								<tbody>
							<tr>
								<td>create</td>
								<td>Note was created, version counter = 1, date created and modified set</td>
							</tr>
							<tr>
								<td>create error</td>
								<td>Error while trying to create a note. The title was already in use.</td>
							</tr>
							<tr>
								<td>import</td>
								<td>Note was imported using the importer, version counter = 1, date created and modified set</td>
							</tr>
							<tr>
								<td>save</td>
								<td>Content was changed, note saved, version counter +1, date modified set</td>
							</tr>
							<tr>
								<td>delete</td>
								<td>Note was deleted, id/number is doomed forever.</td>
							</tr>
							<tr>
								<td>login</td>
								<td>User login - logincounter +1</td>
							</tr>
							<tr>
								<td>login failed </td>
								<td>User login - failcounter +1</td>
							</tr>
							<tr>
								<td>logout</td>
								<td>User logout - logoutcounter +1</td>
							</tr>
							<tr>
								<td>notes eraser</td>
								<td>All user notes deleted</td>
							</tr>
							<tr>
								<td>events eraser</td>
								<td>All user events deleted</td>
							</tr>
						</tbody>
				</table>
			</div>
			</div>
		</div><!-- /.col-sm-4 -->
	</div>
				
				
				
				
				
				



				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>



				<!-- IMPORTER -->
				<h3>Importer</h3>
				<hr>
				<!-- IMPORTER - http://stackoverflow.com/questions/5593473/how-to-upload-and-parse-a-csv-file-in-php -->
				<p>You can import plain-text files. Select a folder and press the 'Import' button.</p>
				<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data" name="importerForm">
					<input type="file" name="file[]" multiple id="file[]" />
					<br>
					<input type="submit" name="doImport" value="Import"  style="width:140px" title="Starts the import function if the user provided a valid selection of files. Might break with bigger amount of text-notes." />
					<textarea class="database" disabled="disabled" id="importLog" style="width:100%" name="importLog" cols="110" rows="5" placeholder="Output of impoter will be displayed here."></textarea>
				</form>


				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>



				<!-- EXPORTER -->
				<h3>Exporter</h3>
				<hr>
				<p>You can export your notes in .csv format. Press the 'Export' button.</p>
				<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
					<input type="submit" name="doExport" value="Export" style="width:140px" title="Exports all your notes into a .csv file which might be useful" />
				</form>


				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>


				<!-- ERASER -->
				<h3>Eraser</h3>
				<hr>
				<p>You can delete your notes and events here. Keep in mind: there is no restore option.</p>
				<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
					<input type="submit" name="doDelAllNotes" value="Delete Notes" style="width:140px" title="Deletes all notes from your account. Be careful with that" /><br>
					<input type="submit" name="doDelAllEvents" value="Delete Events" style="width:140px" title="Deletes all log events from your account. Be careful with that too" />
				</form>


				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>
		</div>
	</div> <!-- /container -->


	<!-- JS-->
	<script type="text/javascript" src="js/jquery.cookie.js"></script>
	<!-- Bootstrap core JavaScript -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="js/bootstrap.min.js"></script>
	<!-- loading the other scripts via LAB.js  ... without load-blocking so far -->
	<script type="text/javascript" src="js/LAB.js"></script>
	<script>
		$LAB
		.script("js/m_reallyLogout.js") 						// ask really-logout question if configured by admin
		.script("js/m_disableRightClick.js")					// disabled the right-click contextmenu
		.script("js/m_keyPress.js")					// keyboard shortcuts
	</script>
	
	<!-- noty - notifications -->
	<script type="text/javascript" src="js/noty/jquery.noty.js"></script>
	<script type="text/javascript" src="js/noty/layouts/bottomCenter.js"></script>
	<script type="text/javascript" src="js/noty/themes/default.js"></script>
	<!-- init noty -->
	<script>
		$.noty.defaults = {
		  layout: 'bottomCenter',
		  theme: 'defaultTheme',
		  type: 'alert',
		  text: '',
		  dismissQueue: true, // If you want to use queue feature set this true
		  template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
		  animation: {
		    open: {height: 'toggle'},
		    close: {height: 'toggle'},
		    easing: 'swing',
		    speed: 500 // opening & closing animation speed
		  },
		  timeout: 5000, // delay for closing event. Set false for sticky notifications
		  force: false, // adds notification to the beginning of queue when set to true
		  modal: false,
		  closeWith: ['click'], // ['click', 'button', 'hover']
		  callback: {
		    onShow: function() {},
		    afterShow: function() {},
		    onClose: function() {},
		    afterClose: function() {}
		  },
		  buttons: false // an array of buttons
		};
	</script>
	
	<script type="text/javascript">
		// alert
		// information
		// error
		// warning
		// notification
		// success
		//
		var n = noty({text: 'Loaded MyMonoto section.', type: 'notification'});
	</script>
	
	<script type="text/javascript" language="javascript" src="js/m_keyPressAll.js"></script>				<!--  m_keyPressAll-->

	</body>
</html>











<?php
// CASES
//
// - Do Delete all Notes
// - Do Delete all Events
// - Do Export
// - Do Import
// - Do Change Userpassword
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
?>


<?php
	session_start();
	require 'conf/config.php';
	require 'inc/helperFunctions.php';
	require "inc/getText.php";
	if($_SESSION['valid'] != 1)			// check if the user-session is valid or not
	{
		header('Location: redirect.php');
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="shortcut icon" type="image/ico" href="images/fav.ico" />
		<title>monoto notes</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="monoto notes">
		<meta name="author" content="florian poeck">
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="css/table.css" />
		<link rel="stylesheet" type="text/css" href="css/page01.css" title="default" />
		<link rel="stylesheet" type="text/css" href="images/font-awesome-4.3.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">		<!-- Bootstrap core CSS -->
		<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">		<!-- Bootstrap theme -->
		<!-- JS-->
		<script type="text/javascript" src="js/jquery/jquery-2.1.3.min.js"></script>		<!-- jquery itself -->
		<script type="text/javascript" language="javascript" src="js/datatables/jquery.dataTables.min.js"></script>		<!-- datatables -->
		<script type="text/javascript" charset="utf-8">
			$(document).ready( function () {
			  $('#example').dataTable( {
				 "bSort": false,		// dont sort - trust the sql-select and its sort-order
				 "iDisplayLength" : 25
			  } );
			} );
		</script>




		<script type="text/javascript">
			function deleteAllMyUserEvents()
			{
				var x = noty({
					text: 'Really delete all your events from log?',
					type: 'confirm',
					dismissQueue: false,
					layout: 'topRight',
					theme: 'defaultTheme',
					buttons: [
						{addClass: 'btn btn-primary', text: 'Ok', onClick: function($noty) {
							$noty.close();
							$.post("inc/delMyUserEvents.php");
							$.cookie("lastAction", "Deleted all your event-entries.");	// store last Action in cookie
							noty({text: 'Deleted all events from log', type: 'success'});
							location.reload();
						}
						},
    					{addClass: 'btn btn-danger', text: 'Cancel', onClick: function($noty) {
							$noty.close();
							noty({text: 'Aborted', type: 'error'});
						}
					}
					]
				})
			}

			function deleteAllMyUserNotes()
			{
				var x = noty({
					text: 'Really delete all your notes?',
					type: 'confirm',
					dismissQueue: false,
					layout: 'topRight',
					theme: 'defaultTheme',
					buttons: [
						{addClass: 'btn btn-primary', text: 'Ok', onClick: function($noty) {
							$noty.close();
							$.post("inc/delMyUserNotes.php");
							$.cookie("lastAction", "Deleted all your notes.");	// store last Action in cookie
							noty({text: 'Deleted all notes', type: 'success'});
							location.reload();
						}
						},
    					{addClass: 'btn btn-danger', text: 'Cancel', onClick: function($noty) {
							$noty.close();
							noty({text: 'Aborted', type: 'error'});
						}
					}
					]
				})
			}
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
					<a class="navbar-brand" href="notes.php"><img src="images/icons/monoto_logo_white.png" width="63" height="25"></a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li><a href="notes.php"><i class="fa fa-pencil-square-o fa-1x"></i> <?php echo translateString("Notes"); ?></a></li>
						<li class="active"><a href="mymonoto.php"><i class="fa fa-user fa-1x"></i> <?php echo translateString("MyMonoto") ?></a></li>
						<li><a href="keyboard.php"><i class="fa fa-keyboard-o fa-1x"></i> <?php echo translateString("Keyboard"); ?></a></li>
						<?php
							if($_SESSION['admin'] == 1) // show admin-section
							{
								echo '<li><a href="admin.php"><i class="fa fa-cogs fa-1x"></i> ';
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
				<!-- Profile-->
				<div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse1"><?php echo translateString("Profile"); ?></a>
            </h4>
          </div>
          <div id="collapse1" class="panel-collapse collapse in">
            <div class="panel-body">
            <table style="width: 100%" border="0">
					<tr>
						<td style="width:25%" colspan="2"></td>
						<td style="width:5%"></td>
						<td rowspan="5">
						<!-- CHANGE USER PASSWORD BUTTON -->
						<b><?php echo translateString("Changing password:"); ?></b><br>
						<form id="changePassword" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
							<input type="password" id="newPassword1" name="newPassword1" placeholder="New password" required="required" autocomplete="off" />
							<input type="password" id="newPassword2" name="newPassword2" placeholder="Repeat new password" required="required" autocomplete="off" />
							<button type="submit" name="doChangeUserPW" value="Update"  style="width:140px" title="Starts the change password function if the user provided the new password twice."><i class="fa fa-save"></i> Update</button>
						</form>
						</td>
						</tr>
						<tr>
							<td><?php echo translateString("username:"); ?></td>
							<td><?php echo "<span class='badge'>".$_SESSION['username']."</span>" ?></td>
							<td></td>
						</tr>
						<tr>
							<td><?php echo translateString("mail:"); ?></td>
							<td>
								<?php
									require 'inc/db.php';						// connect to db
									connectToDB();
									$sql="SELECT email FROM m_users WHERE username='".$_SESSION['username']."' ";				// mail
									$result = mysql_query($sql);
									while($row = mysql_fetch_array($result))
									{
										echo "<span class='badge'>".$row[0]."</span>";
									}
								?>
							</td>
							<td></td>
						</tr>
						<tr>
							<td><?php echo translateString("logins:"); ?></td>
							<td>
								<?php
									$sql="SELECT login_counter FROM m_users WHERE username='".$_SESSION['username']."' ";		// login_counter
									$result = mysql_query($sql);
									while($row = mysql_fetch_array($result))
									{
										echo "<span class='badge'>".$row[0]."</span>";
									}
								?>
							</td>
							<td></td>
						</tr>
						<tr>
							<td><?php echo translateString("since:"); ?></td>
							<td>
								<?php
									$sql="SELECT date_first_login FROM m_users WHERE username='".$_SESSION['username']."' ";	// date first login
									$result = mysql_query($sql);
									while($row = mysql_fetch_array($result))
									{
										echo "<span class='badge'>".$row[0]."</span>";
									}
								?>
							</td>
							<td></td>
						</tr>
						<tr><td colspan="5">&nbsp;</td></tr>
						<tr>
							<td colspan="3"></td>
							<td colspan="2">
							<form id="changeLanguage" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
							<b><?php echo translateString("Language"); ?></b><br>
							<select name="s_languageSelector" id="s_languageSelector">
								<option value="de_DE">de_DE</option>
								<option value="en_US">en_US</option>
							</select>
							<button type="submit" name="doChangeUserLanguage" value="Update"  style="width:140px" title="Starts the change language function if the user provided the new language selection."><i class="fa fa-save"></i> Update</button>
							</form>
							</td>
						<tr>

						<!--
						<tr><td colspan="5">&nbsp;</td></tr>
						<tr><td colspan="5"><input type="checkbox" name="cb_EnableDesktopNotifications" aria-label=""> Enable desktop notifications</td></tr>
					-->


				</table>
            </div>
          </div>
        </div>

        <!-- Stats-->
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse2"><?php echo translateString("Stats"); ?></a>
            </h4>
          </div>
          <div id="collapse2" class="panel-collapse collapse">
            <div class="panel-body">
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
										echo "- You have <span class='badge'>".$row[0]." personal notes</span> in the monoto database<br>"; 	// output amount of notes
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
										echo "- Those notes are using <span class='badge'>".$stats_overall_title_words." words</span> for titles and overall <span class='badge'>".$stats_overall_content_words." words</span> for the content.<br>";
										echo "- The personal event log has recorded <span class='badge'>".$stats_events_of_current_user." events</span> for this account.<br>";
										echo "- Those can be devided into <span class='badge'>".$stats_amount_of_creates." notes creations</span>, <span class='badge'>".$stats_amount_of_changes." note-editings</span> and <span class='badge'>".$stats_amount_of_deletes." notes-deletions</span>.<br>";
										echo "- In addition to those numbers your account has <span class='badge'>".$stats_amount_of_imports." note-import events</span> logged. But keep in mind that 1 import event can contain more then 1 note.<br>";
										echo "- Plus <span class='badge'>".$stats_amount_of_creates_errors."</span> failed create errors.<br>";
										echo "- Well in case numbers still dont match up - add <span class='badge'>".$stats_amount_of_logins." logins</span> and <span class='badge'>".$stats_amount_of_logouts." logouts</span>.<br>";
										echo "- Your highest note id is currently <span class='badge'>".$stats_highest_note_version_id."</span>, with the title <span class='badge'>".$stats_highest_note_version_title."</span>. This specific note has <span class='badge'>revision number ".$stats_highest_note_version_versions."</span>.<br>";
										echo "- Your shortest note so far is note <span class='badge'>number ".$stats_note_with_shortest_content_id."</span>, it is <span class='badge'>using ".$stats_note_with_shortest_content_chars." chars</span> for its entire content.<br>";
										echo "- Lets compare that with your longest note which has the <span class='badge'>id ".$stats_note_with_longest_content_id."</span> and is <span class='badge'>".$stats_note_with_longest_content_chars." long</span>.<br>";
										echo "- Looking for dates? Let's face it: your oldest note has an <span class='badge'>age of ".$stats_oldest_created_note_age." days</span>. It was created <span class='badge'>".$stats_oldest_created_note_date."</span> with the <span class='badge'>id ".$stats_oldest_created_note_id."</span>.<br>";
										echo "- In comparison - your latest created note has the <span class='badge'>age of ".$stats_latest_created_note_age." days</span>, has the <span class='badge'>id ".$stats_latest_created_note_id."</span>, the title <span class='badge'>".$stats_latest_created_note_title."</span> and a creation date of <span class='badge'>".$stats_latest_created_note_date."</span>.<br>";
										echo "- The last note you actually edited was note <span class='badge'>".$stats_last_edited_note_id."</span> with the title <span class='badge'>".$stats_last_edited_note_title."</span>. This edit is <span class='badge'>".$stats_last_edited_note_age." days</span> old - from <span class='badge'>".$stats_last_edited_note_date."</span> in case you bother.<br>";
										echo "- Lets come to the end - the entire monoto db of all users has a size of <span class='badge'>".$stats_entire_monoto_db_size."  MB</span>.<br>";
									}
							}
					?>
            </div>
          </div>
        </div>

        <!-- Activity Log-->
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse3"><?php echo translateString("Activity Log"); ?></a>
            </h4>
          </div>
          <div id="collapse3" class="panel-collapse collapse">
            <div class="panel-body">
           <table cellpadding="0" cellspacing="0" class="display" id="example" style="width:100%">
					<thead>
						<tr><th>id</th><th>event</th><th>details</th><th>timestamp</th></tr>
					</thead>
					<tbody>
						<?php
							$result = mysql_query("SELECT * FROM m_log WHERE owner='".$owner."' ORDER BY activity_date DESC"); // m_log
							while($row = mysql_fetch_array($result))   // fill datatable
							{
								echo '<tr class="odd gradeU"><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td></tr>';
							}
						?>
					</tbody>
					<tfoot><tr><th>id</th><th>event</th><th>details</th><th>timestamp</th></tr></tfoot>
				</table>
            </div>
          </div>
        </div>

			<!-- Importer - Textfiles-->
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse4"><?php echo translateString("Importer (Textfiles)"); ?></a>
            </h4>
          </div>
          <div id="collapse4" class="panel-collapse collapse">
            <div class="panel-body">
            <!-- IMPORTER - http://stackoverflow.com/questions/5593473/how-to-upload-and-parse-a-csv-file-in-php -->

				<p><?php echo translateString("You can import plain-text files. Select a folder and press the 'Import' button."); ?></p>
				<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data" name="importerForm">
					<input type="file" name="file[]" multiple id="file[]" />
					<br>
					<button type="submit" name="doImport" value="Import"  style="width:140px" title="Starts the import function if the user provided a valid selection of files. Might break with bigger amount of text-notes." disabled ><i class="fa fa-sign-in"></i> <?php echo translateString("Import"); ?></button>
					<textarea class="database" disabled="disabled" id="importLog" style="width:100%" name="importLog" cols="110" rows="5" placeholder="<?php echo translateString("Output of impoter will be displayed here"); ?>"></textarea>
				</form>
            </div>
          </div>
        </div>


			<!-- Importer-->
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse5"><?php echo translateString("Importer (.csv)"); ?></a>
            </h4>
          </div>
          <div id="collapse5" class="panel-collapse collapse">
            <div class="panel-body">
				<!-- Importer V2 -->
				<p>You can import notes in .csv format (coming from the exporter).</p>
				<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
				<!-- <form action="inc/importer.php" method="post" enctype="multipart/form-data"> -->
					<input type="file" name="importerFile" id="importerFile"/>
					<br>
					<button type="submit" name="doImportCSV" value="Import"  style="width:140px" title="Starts the import function if the user provided a valid selection of files. Might break with bigger amount of text-notes."><i class="fa fa-sign-in"></i> Import</button>
					<textarea class="database" disabled="disabled" id="importLogCSV" style="width:100%" name="importLogCSV" cols="110" rows="5" placeholder="Output of impoter will be displayed here."></textarea>
				</form>
            </div>
          </div>
        </div>

        <!-- Exporter-->
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse6"><?php echo translateString("Exporter (.csv)"); ?></a>
            </h4>
          </div>
          <div id="collapse6" class="panel-collapse collapse">
            <div class="panel-body">
            <p>You can export your notes in .csv format. Press the 'Export' button.</p>
				<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
					<button type="submit" name="doExport" value="Export" style="width:140px" title="Exports all your notes into a .csv file which might be useful" ><i class="fa fa-sign-out"></i> Export</button>
				</form>

            </div>
          </div>
        </div>

        <!-- Eraser-->
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse7"><?php echo translateString("Eraser"); ?></a>
            </h4>
          </div>
          <div id="collapse7" class="panel-collapse collapse">
            <div class="panel-body">
            <p><?php echo translateString("You can delete your notes and events here. Keep in mind: there is no restore option."); ?></p>
				<button type="button" style="width:140px" class="btn btn-sm btn-danger" title="Deletes all your user events from the db" name="delete" id="delete" value="delete" onClick="deleteAllMyUserEvents();"><i class="fa fa-trash-o fa-1x"></i> <?php echo translateString("Delete events"); ?></button>
				<button type="button" style="width:140px" class="btn btn-sm btn-danger" title="Deletes all your user notes from the db" name="delete" id="delete" value="delete" onClick="deleteAllMyUserNotes();"><i class="fa fa-trash-o fa-1x"></i> <?php echo translateString("Delete notes"); ?></button>
            </div>
          </div>
        </div>
      </div>
		</div>
	</div> <!-- /container -->


	<!-- JS-->
	<script type="text/javascript" src="js/jquery.cookie.js"></script>
	<script type="text/javascript" src="js/LAB.js"></script>
	<script>
		$LAB
		.script("js/bootstrap.min.js")						// Bootstrap core JavaScript
		.script("js/monoto/m_reallyLogout.js") 				// ask really-logout question if configured by admin
		.script("js/monoto/m_disableRightClick.js")			// disabled the right-click contextmenu
		.script("js/monoto/m_keyPressAll.js")				// keyboard shortcuts
	</script>
	<!-- noty - notifications -->
	<script type="text/javascript" src="js/noty/jquery.noty.js"></script>
	<script type="text/javascript" src="js/noty/layouts/topRight.js"></script>
	<script type="text/javascript" src="js/noty/themes/default.js"></script>
	<script type="text/javascript" src="js/monoto/m_initNoty.js"></script>

	<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		var lang = '<?php echo $_SESSION["lang"]; ?>';
		$('#s_languageSelector').val(lang); // selects "Two"
	});
	</script>
	</body>
</html>





<?php
// CASES
//
// - Do Delete all Notes
// - Do Delete all Events
// - Do Export
// - Do Import
// - Do Change Language
// - Do Change Userpassword
require 'conf/config.php';


// -----------------------------------------------------------------------
// doImportCSV (START)
// -----------------------------------------------------------------------
if ( isset($_POST["doImportCSV"]) )
{
	// Toggle Importer-Tab (open it)
	echo '<script type="text/javascript">
		$("#collapse5").collapse({
			toggle: true
		});   </script>';

	// Toggle Profile (close it - its open by default)
	echo '<script type="text/javascript">
		$("#collapse1").collapse({
			toggle: true
		});   </script>';

	$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);		// connect to mysql
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db($mysql_db, $con);						// select db

	$owner = $_SESSION['username'];
	$target_dir = "";
	$target_file = $target_dir . basename($_FILES["importerFile"]["name"]);
	$uploadOk = 1;
	$fileExtension = pathinfo($target_file,PATHINFO_EXTENSION);

	if($fileExtension == "csv")
	{
		if(($handle = fopen($_FILES['importerFile']['tmp_name'], 'r')) !== FALSE) 		// read linewise and import if note doesnt exist already
		{
			echo "<hr>";
			set_time_limit(0);
			$row = 0;
			while(($data = fgetcsv($handle, 1000, "\t")) !== FALSE)
			{
				$col_count = count($data);				// number of fields in the csv

				// get the values from the csv
				$csv[$row]['col1'] = $data[0];
				$csv[$row]['col2'] = $data[1];
				$csv[$row]['col3'] = $data[2];

				$newNoteTitle = $data[1];
				$newNoteContent = $data[2];

				// TODO:
				// check if there is already a note with this title

				$sql="INSERT INTO m_notes (title, content, date_create, date_mod, owner, save_count) VALUES ('$newNoteTitle', '$newNoteContent', now(), now(), '$owner', '1' )";
				$result = mysql_query($sql);
				if (!$result)
				{
					die('Error: ' . mysql_error());// display error output
				}
				else
				{
					// write text to textarea
					echo '<script type="text/javascript">$("#importLogCSV").append("Imported: '.$newNoteTitle.'.\n"); </script>';
				}
				// inc the row
				$row++;
			}
			fclose($handle);
		}
		else
		{
			echo "ERROR - unable to open the file";
		}
	}
	else
	{
		echo "Aborting - as file has unexpected format.";
	}

	// write text to textarea
	echo '<script type="text/javascript">$("#importLogCSV").append("\n\nFinished importing notes.");</script>';
}
// -----------------------------------------------------------------------
// doImportCSV (END)
// -----------------------------------------------------------------------






// -----------------------------------------------------------------------
// doChangeUserLanguage (START)
// -----------------------------------------------------------------------
if ( isset($_POST["doChangeUserLanguage"]) )
{
	$selectedLang = $_POST['s_languageSelector'];
	$query = "UPDATE m_users SET language='$selectedLang' WHERE username='$owner'";			// language
	mysql_query($query);
	$_SESSION['lang'] = $selectedLang; 			// store as session variable
	displayNoty('Language set to: '.$selectedLang,'notification');
}
// -----------------------------------------------------------------------
// doChangeUserLanguage (END)
// -----------------------------------------------------------------------





// -----------------------------------------------------------------------
// doChangeUserPW (START)
// -----------------------------------------------------------------------
if ( isset($_POST["doChangeUserPW"]) )
{
	require 'conf/config.php';
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
		displayNoty('Password mismatch','error');
	}
}
// -----------------------------------------------------------------------
// doChangeUserPW (END)
// -----------------------------------------------------------------------




// -----------------------------------------------------------------------
// doExport (START)
// -----------------------------------------------------------------------
if ( isset($_POST["doExport"]) )
{
	echo '<script type="text/javascript" language="javascript">window.open("inc/expNotes.php", "width=400,height=500,top=50,left=280,resizable,toolbar,scrollbars,menubar,");</script>';
}
// -----------------------------------------------------------------------
// doExport (STOP)
// -----------------------------------------------------------------------





//
// importer submit button was pressed
//
if ( isset($_POST["doImport"]) )
{
	// TODO: files selected at all????
	if (empty($_FILES))
	{
	}
	else
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
}
?>

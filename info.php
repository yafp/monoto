<?php
	session_start();
	if($_SESSION['valid'] == 1) 			// check if the user-session is valid or not
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
		<!-- data tables -->
		<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
		<!--  m_disableRightClick-->
		<script type="text/javascript" language="javascript" src="js/m_disableRightClick.js"></script>
		<!--  m_reallyLogout-->
		<script type="text/javascript" language="javascript" src="js/m_reallyLogout.js"></script>
		<!-- scroll up -->
		<script type="text/javascript" language="javascript" src="js/m_scrollUp.js"></script>
		<!-- m_accordionToc -->
		<script type="text/javascript" language="javascript" src="js/m_accordionToc.js"></script>
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
				"iDisplayLength": 50,					/* default rows */
				"bLengthChange": false,
				"bPaginate": true , 					/* pagination  - BREAKS SELECTED ROW - copy content function right now*/
				"aaSorting": [[ 3, "desc" ]],				/* sorting */
				"aoColumns"   : [					/* visible columns */
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
		<?php include 'header.php';  ?>

		<div id="noteContentCo">
			<?php
					include 'config.php';
					if($s_enable_toc == true)
					{
			?>
						<h2><a name="desc">info</a></h2>
						<div class="accordion">
							<h3>welcome [<a href="#welcome">...</a>]</h3>
							<p><img src="images/info_icon.png" width="40" align="right">the <a href="#welcome">welcome</a> section displays a server-wide welcomemessage configured by the monoto-admin.</p>
							<h3>about [<a href="#about">...</a>]</h3>
							<p><img src="images/info_icon.png" width="40" align="right">the <a href="#about">about</a> section lists a short description of monoto.</p>
							<h3>version [<a href="#version">...</a>]</h3>
							<p><img src="images/info_icon.png" width="40" align="right">the <a href="#version">version</a> section displays the current milestone, the build-version and in addition an online update-check-function. In addition it features the monoto changelog (listing all important milestone changes).</p>
							<h3>stats [<a href="#stats">...</a>]</h3>
							<p><img src="images/info_icon.png" width="40" align="right">the <a href="#stats">stats</a> section offers a small list of stats about your notes.</p>
							<h3>keyboard shortcuts [<a href="#keyboard">...</a>]</h3>
							<p><img src="images/info_icon.png" width="40" align="right">the <a href="#keyboard">keyboard shortcuts </a>section lists all existing keyboard shortcuts.</p>
							<h3>log [<a href="#log">...</a>]</h3>
							<p><img src="images/info_icon.png" width="40" align="right">the <a href="#log">log</a> section allows you to  search all your events. This includes notes creation, editing, deleting. Importing and exporting, the usage of the eraser and last but not least logins and logouts.</p>
						</div>
			<?php
					}
			?>

			<!-- SPACER -->
			<div class="spacer">&nbsp;</div>

			<!-- WELCOME MESSAGE -->
			<h2><a name="welcome">welcome</a></h2>
			<?php 
				if($s_enable_welcome_message == true)								// check if welcome message is configured 
				{
					if (strlen($s_welcome_message_to_all_users) > 0) 				// is a text defined as well?		
					{ 
						echo "<pre>".$s_welcome_message_to_all_users."</pre>"; 
					}
					else 														// admin has fucked it up
					{ 
						echo "<i>Welcome message is enabled but not defined by admin. Shame on him.</i>";  
					}
				}
			?>

			<!-- SPACER -->
			<div class="spacer">&nbsp;</div>

			<!-- ABOUT -->
			<?php
				if($s_enable_info_about_section == true)
				{
			?>
					<h2><a name="about">about</a></h2>
			<?php 
					include 'conf/config.php';
					echo "<b>".$m_name."</b>"; 
			?>
				is an open-source web-based notes-keeper. You can create, edit, rename, delete text-based notes - but the most important function is for sure the search.				
				The basic workflow is somehow inspired by <a href="http://notational.net/" target="_new">Notational Velocity</a>.
				Wanna know <a href="https://github.com/macfidelity/monoto/wiki/About-the-monoto-history">more</a>?

			<!-- SPACER -->
			<div class="spacer">&nbsp;</div>

			<?php
				}
				else
				{	
					echo "<pre>The About section was disabled in the admin-settings.</pre>";		
				} 
			?>

			<!-- VERSION -->
			<h2><a name="version">version</a></h2>
			<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
				<table width="100%">
					<tr><td rowspan="6"><img src="images/logo/icon_big.png" alt="monoto logo" align="center"></td></tr>
					<tr>
						<td>build:</td>
						<td><?php echo $m_build; ?></td>
						<td><?php if($m_stable == false) { echo "<font color='red'>Development Version</font>"; } ?></td>
					</tr>
					<tr>
						<td>milestone:</td>
						<td><?php echo $m_milestone." <i>aka</i> ".$m_milestone_title.""; ?></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td colspan="2">&nbsp;</td>
						<td><input type="submit" name="doUpdateCheck" value="Software Update" title="checks online for monoto updates" /></td>
					</tr>
				</table>
			</form>

			<!-- CHANGELOG-->
			<textarea name="changes" style="width:100%" rows=20 disabled>
			<?=file_get_contents ('doc/CHANGELOG.txt');?>	
			</textarea>
				
			<!-- SPACER -->
			<div class="spacer">&nbsp;</div>

			<!-- STATS -->
			<?php
					echo '<h2><a name="stats">stats</a></h2>';
					include ('scripts/db.php');  	// connect to db
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
								echo "- You have <span>".$row[0]." notes</span> in your monoto database<br>"; 	// output amount of notes

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
								// amount of import-events
								$result = mysql_query("SELECT count(*) FROM m_log WHERE event='import' and owner='".$owner."' "); 
								while($row = mysql_fetch_array($result)) 					
								{ $stats_amount_of_imports = $row[0]; }
								// amount of edits-events
								$result = mysql_query("SELECT count(*) FROM m_log WHERE event='save' and owner='".$owner."' "); 
								while($row = mysql_fetch_array($result)) 					
								{ $stats_amount_of_changes = $row[0]; }
								// amount of renames-events
								$result = mysql_query("SELECT count(*) FROM m_log WHERE event='rename' and owner='".$owner."' "); 
								while($row = mysql_fetch_array($result)) 					
								{ $stats_amount_of_renames = $row[0]; }
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
								$result = mysql_query("SELECT DATEDIFF(CURDATE(), date_create) AS intval, date_create, save_count, title, id FROM m_notes WHERE save_count = '0' and owner='".$owner."' ORDER BY date_create DESC LIMIT 1"); 
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
								//  entire db size
								//$result = mysql_query("SELECT sum( data_length + index_length ) /1024 /1024 FROM information_schema.TABLES WHERE table_schema = 'monoto'"); 
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
								//		- $stats_amount_of_imports
								//		- $stats_amount_of_changes
								//		- $stats_amount_of_renames
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
								// DB-SIZE
								// - $stats_entire_monoto_db_size
								//
								// Use our variables to create some kind of LOG text - should be informative but still funny if possible.
								echo "- The personal event log has recorded <span>".$stats_events_of_current_user." events</span> for this account.<br>";
								echo "- Those can be devided into <span>".$stats_amount_of_creates." notes creations</span>, <span>".$stats_amount_of_changes." note-editings</span>, <span>".$stats_amount_of_renames." rename-events</span> and <span>".$stats_amount_of_deletes." notes-deletions</span>.<br>";
								echo "- In addition to those numbers your account has <span>".$stats_amount_of_imports." note-import events</span> logged. But keep in mind that 1 import event can contain more then 1 note.<br>";
								echo "- Your highest note id is currently <span>".$stats_highest_note_version_id."</span>, with the title <span>".$stats_highest_note_version_title."</span>. This specific note has <span>revision number ".$stats_highest_note_version_versions."</span>.<br>";
								echo "- Well in case numbers still dont match up - add <span>".$stats_amount_of_logins." logins</span> and <span>".$stats_amount_of_logouts." logouts</span>.<br>";
								echo "- Your shortest note so far is note <span>number ".$stats_note_with_shortest_content_id."</span>, it is <span>using ".$stats_note_with_shortest_content_chars." chars</span> for its entire content.<br>";
								echo "- Lets compare that with your longest note which has the <span>id ".$stats_note_with_longest_content_id."</span> and is <span>".$stats_note_with_longest_content_chars." long</span>.<br>";
								echo "- Looking for dates? Let's face it: your oldest note has an <span>age of ".$stats_oldest_created_note_age." days</span>. It was created <span>".$stats_oldest_created_note_date."</span> with the <span>id ".$stats_oldest_created_note_id."</span>.<br>";
								echo "- In comparison - your latest created note has the <span>age of ".$stats_latest_created_note_age." days</span>, has the <span>id ".$stats_latest_created_note_id."</span>, the title <span>".$stats_latest_created_note_title."</span> and a creation date of <span>".$stats_latest_created_note_date."</span>.<br>";
								echo "- The last note you actually edited was note <span>".$stats_last_edited_note_id."</span> with the title <span>".$stats_last_edited_note_title."</span>. This edit is <span>".$stats_last_edited_note_age." days</span> old - from <span>".$stats_last_edited_note_date."</span> in case you bother.<br>";
								echo "- Lets come to the end - the entire monoto db has a size of <span>".$stats_entire_monoto_db_size."  MB</span>.<br>";
							}
					}
					echo "<br><font color='#808080'>Missing something? Please feel free to send me your sql-queries per mail</font>";		
			?>

			<!-- SPACER -->
			<div class="spacer">&nbsp;</div>

			<!-- KEYBOARD SHORTCUT -->
			<h2><a name="keyboard">keyboard shortcuts</a></h2>
			<table width="100%">
				<thead><tr><th align="left" width="20%">key</th><th align="left" width="60%">function</th><th align="left" width="10%">valid for</th><th align="left" width="10%">status</th></tr></thead>
				<tbody>
					<tr><td>ESC</td><td>Reset input fields and sets the focus back to the main search field.</td><td>home</td><td>works</td></tr>
					<tr><td>F1</td><td>Open the online help / documentation</td><td>all pages</td><td>only for home</td></tr>
					<tr><td>F2</td><td>Rename a selected note</td><td>home</td><td>works</td></tr>
					<tr><td>F5</td><td>Reloads all notes from db.</td><td>all pages</td><td>only for home</td></tr>
					<tr><td>F9</td><td>Save a selected note</td><td>home</td><td>works</td></tr>
					<tr><td>Del</td><td>Delete selected note.</td><td>home</td><td>works</td></tr>
					<tr><td>Arrow Down</td><td>row-selection</td><td>home</td><td>DUMMY</td></tr>
					<tr><td>Arrow Up</td><td>row-selection</td><td>home</td><td>DUMMY</td></tr>
					<tr><td>(Shift) + Alt + n</td><td>Loads notes page per accesskey</td><td>all pages</td><td>should work</td></tr>
					<tr><td>(Shift) + Alt + s</td><td>Loads settings page per accesskey</td><td>all pages</td><td>should work</td></tr>
					<tr><td>(Shift) + Alt + i</td><td>Loads info page per accesskey</td><td>all pages</td><td>should work</td></tr>
					<tr><td>(Shift) + Alt + d</td><td>Loads admin page per accesskey</td><td>all pages</td><td>should work</td></tr>
				</tbody>
			</table>

			<!-- SPACER -->
			<div class="spacer">&nbsp;</div>
			
			<!-- LOG -->
			<h2><a name="log">log</a></h2>
				<table width="100%">
				<thead><tr><th align="left" width="20%">event</th><th align="left" width="60%">description</th><th align="left" width="20%">count</th></tr></thead>
				<tbody>
					<tr>
						<td bgcolor=#84FF00>create</td>
						<td>Note was created, version counter = 0, date created and modified set</td>
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
						<td bgcolor=#00CDCD>import</td>
						<td>Note was imported using the importer, version counter = 0, date created and modified set</td>
						<td>
							<?php
								$result = mysql_query("SELECT count(event) FROM m_log WHERE event = 'import' and owner='".$owner."' "); 
								while($row = mysql_fetch_array($result))
								{ echo $row[0]; }
						?>
						</td>
					</tr>
					<tr>
						<td bgcolor=#FFB914>save</td>
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
						<td bgcolor=#D77D00>rename</td>
						<td>Title (and maybe content) was changed, version counter +1, date modified set</td>
						<td>
							<?php
								$result = mysql_query("SELECT count(event) FROM m_log WHERE event = 'rename' and owner='".$owner."' "); 
								while($row = mysql_fetch_array($result))
								{ echo $row[0]; }
						?>
						</td>
					</tr>
					<tr>
						<td bgcolor=#CD0000>delete</td>
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
						<td bgcolor=#666666>login</td>
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
						<td bgcolor=#CCCCCC>logout</td>
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
						<td bgcolor=#CCCCCC>notes eraser</td>
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
						<td bgcolor=#CCCCCC>events eraser</td>
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
				<table cellpadding="0" cellspacing="0" class="display" id="example" width="100%">
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

				<!-- back to top -->
				<div id="message"><a href="#container">scroll to top</a></div>

			<!-- SPACER -->
			<div class="spacer">&nbsp;</div>
		</div>
	</div>
	<!--  FOOTER -->
	<?php include 'footer.php'; ?>
	</body>
</html>

<?php
	}
	else //session is NOT valid
	{
		header('Location: redirect.php');
	}
?>


<?php
// UpdateCheck
//
// http://wuxiaotian.com/2009/09/php-check-for-updates-script/
if ( isset($_POST["doUpdateCheck"]) ) 
{
	session_start();
	include 'config.php';
	
	// assume everything is good
	$critical = FALSE;
	$update = FALSE;

	$url = "https://raw.github.com/macfidelity/monoto/master/vStable.csv";
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
		echo '<script type="text/javascript">alert("There is an update available.");</script>';
	}
	else // uptodate
	{
		echo '<script type="text/javascript">alert("You are using the latest version. Well done.");</script>';
	}

	//
	// check for unstable versions as well
	//
	if($s_enable_UnstableSources == true)
	{
		// assume everything is good
		$critical = FALSE;
		$update = FALSE;

		$url = "https://raw.github.com/macfidelity/monoto/master/vDev.csv";
		$fp = @fopen ($url, 'r') or print ('UPDATE SERVER OFFLINE');
		$read = fgetcsv ($fp);
		fclose ($fp); //always a good idea to close the file connection

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
			echo '<script type="text/javascript">alert("There is an dev update available.");</script>'; 
		}
		else // uptodate
		{ 
			echo '<script type="text/javascript">alert("You are using the latest dev version. Thanks for testing.");</script>'; 
		}
	}
}
?>
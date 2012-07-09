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

		<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>

		<!--  m_reallyLogout-->
		<script type="text/javascript" language="javascript" src="js/m_reallyLogout.js"></script>

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
				"iDisplayLength": 20,					/* default rows */
				"bLengthChange": false,
				"bPaginate": true , 					/* pagination  - BREAKS SELECTED ROW - copy content function right now*/
				"aaSorting": [[ 4, "desc" ]],				/* sorting */
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
						echo '<h2>info toc</h2>';
						echo '<small>';
						echo '<ul>';
							echo '<li><a href="#about">about</a></li>';
							echo '<li><a href="#version">version</a></li>';
							echo '<li><a href="#stats">stats</a></li>';
							echo '<li><a href="#keyboard">keyboard shortcuts</a></li>';
							echo '<li><a href="#log">log</a></li>';
						echo '</ul>';
						echo '</small>';	
					}
			?>

			<!-- SPACER -->
			<div id="spacer">&nbsp;</div>

			<!-- ABOUT -->
			<?php
				if($enable_info_about_section == true)
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
			<div id="spacer">&nbsp;</div>

			<?php
				}
				else
				{
					echo "<font color='silver'><i>The About section was disabled in the settings.</i></font>";
				} 
			?>

			<!-- VERSION -->
			<h2><a name="version">version</a></h2>

			<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
			<table border="0" width="100%">
				<tr>
					<td width="10%">your milestone:</td>
					<td width="70%"><?php echo $m_milestone." <i>aka</i> ".$m_milestone_title.""; ?></td>
					<td width="10%"></td>
					<td width=""><input type="submit" name="doUpdateCheck" value="Software Update" title="checks online for monoto updates" /></td>
				</tr>
				<tr>
					<td>build:</td>
					<td>
						<?php 
							echo $m_build; 
							if($m_stable == false)
							{
								echo " - <font color='red'>Development Version</font>";
							}
						?>
					</td>
					<td></td>
					<td></td>
				</tr>
			</table>
			</form>

			<?php
				if($enable_info_version_changelog_section == true)
				{
					?>
						<textarea name="changes" style="width:100%" rows=20 disabled>
						<?=file_get_contents ('doc/CHANGELOG.txt');?>	
						</textarea>
					<?php 
				}
				else
				{
					echo "<font color='silver'><br><i>The Changelog section was disabled in the settings.</i></font>";
				} 
			?>

			<!-- SPACER -->
			<div id="spacer">&nbsp;</div>

			<!-- STATS -->
			<?php
				if($enable_info_stats_section == true)
				{
					echo '<h2><a name="stats">stats</a></h2>';

					// connect to mysql
					$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);
					if (!$con)
					{
						die('Could not connect: ' . mysql_error());
						echo "Unable to connect to defined database - please check your credentials.";	
					}
					else
					{
						mysql_select_db($mysql_db, $con);						// do the mysql connect
						
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
									{
										echo " Even worse ... there is not a single note by any other user.<br>";
									}
									else
									{
										echo " But at least other users seem to save notes to the database. Give it some love dude.<br>";
									}
								}
							}
							else
							{

								echo "- You have ".$row[0]." notes in your monoto database<br>"; 	// output amount of notes


								//
								// SQL-SECTION
								//
								// amount of activity-events
								$result = mysql_query("SELECT count(*) FROM m_log WHERE owner='".$owner."' "); 
								while($row = mysql_fetch_array($result)) 					
								{
									//echo "- According to your log you fired already ".$row[0]." event(s) to your database.<br>"; 
									$stats_events_of_current_user = $row[0];
								}

								// amount of create-events
								$result = mysql_query("SELECT count(*) FROM m_log WHERE event='create' and owner='".$owner."' "); 
								while($row = mysql_fetch_array($result)) 					
								{
									//echo "- Those can be separated into ".$row[0]." note-creations,"; 
									$stats_amount_of_creates = $row[0];
								}

								// amount of import-events
								$result = mysql_query("SELECT count(*) FROM m_log WHERE event='import' and owner='".$owner."' "); 
								while($row = mysql_fetch_array($result)) 					
								{
									//echo " ".$row[0]." note imports,"; 
									$stats_amount_of_imports = $row[0];
								}

								// amount of edits-events
								$result = mysql_query("SELECT count(*) FROM m_log WHERE event='save' and owner='".$owner."' "); 
								while($row = mysql_fetch_array($result)) 					
								{
									//echo " ".$row[0]." times saving changes,"; // amount of notes
									$stats_amount_of_canges = $row[0];
								}

								// amount of renames-events
								$result = mysql_query("SELECT count(*) FROM m_log WHERE event='rename' and owner='".$owner."' "); 
								while($row = mysql_fetch_array($result)) 					
								{
									//echo " ".$row[0]." renamings"; // amount of notes
									$stats_amount_of_renames = $row[0];
								}

								// amount of delete-events
								$result = mysql_query("SELECT count(*) FROM m_log WHERE event='delete' and owner='".$owner."' "); 
								while($row = mysql_fetch_array($result)) 					
								{
									//echo " and last but not least ".$row[0]." note-deletions.<br>"; 
									$stats_amount_of_deletes = $row[0];
								}

								//  version: highest note-version (most used note)
								$result = mysql_query("SELECT id, title, save_count FROM m_notes WHERE owner='".$owner."'ORDER BY save_count DESC LIMIT 1"); 
								while($row = mysql_fetch_array($result)) 					
								{
									//echo "- You seem to use note ".$row[0]." with title <i>".$row[1]."</i> a lot - it has ".$row[2]." versions, which means it is the most edited note.<br>";
									$stats_highest_note_version_id = $row[0];
									$stats_highest_note_version_title = $row[1];
									$stats_highest_note_version_versions = $row[2];
								}



								//  shortest and longest note-content
								$result = mysql_query("SELECT MIN( LENGTH( content ) ) AS shortest, id FROM m_notes WHERE owner='".$owner."'"); 
								while($row = mysql_fetch_array($result)) 					
								{
									//echo "- Need more? Note number: ".$row[1]." is your shortest note. It is using ".$row[0]." chars for its entire note-content.<br>";
									$stats_note_with_shortest_content_id = $row[1];
									$stats_note_with_shortest_content_chars = $row[0];
								}


								//  longest note-content
								$result = mysql_query("SELECT ( LENGTH( content ) ) AS longest, id FROM m_notes WHERE owner='".$owner."' ORDER BY longest DESC LIMIT 1"); 
								while($row = mysql_fetch_array($result)) 					
								{
									//echo "- While your longest (number ".$row[1]." ) is using ".$row[0]." characters. Well thats some kind of difference.<br>";
									$stats_note_with_longest_content_id = $row[1];
									$stats_note_with_longest_content_chars = $row[0];
								}


								//  oldest created note
								$result = mysql_query("SELECT DATEDIFF(CURDATE(), date_create) AS intval, date_create, id, title FROM m_notes WHERE owner='".$owner."' ORDER BY date_create ASC LIMIT 1"); 
								while($row = mysql_fetch_array($result)) 					
								{
									//echo "- The oldest existing note has an age of ".$row[0]." days. It was created <i>".$row[1]."</i> with number ".$row[2].". Wow that means you are using monoto since ".$row[0]." days - hope you love it.<br>";
									$stats_oldest_created_note_age = $row[0];
									$stats_oldest_created_note_date = $row[1];
									$stats_oldest_created_note_id = $row[2];
								}


								//  newest/latest created note
								$result = mysql_query("SELECT DATEDIFF(CURDATE(), date_create) AS intval, date_create, save_count, title, id FROM m_notes WHERE save_count = '0' and owner='".$owner."' ORDER BY date_create DESC LIMIT 1"); 
								while($row = mysql_fetch_array($result)) 					
								{
									//echo "- The latest created and still unedited note has an age of ".$row[0]." days. It was created <i>".$row[1]."</i> with number ".$row[4]." and the title <i>".$row[3]."</i>.<br>";
									$stats_latest_created_note_age = $row[0];
									$stats_latest_created_note_date =  $row[1];
									$stats_latest_created_note_id = $row[4];
									$stats_latest_created_note_title = $row[3];
								}

								//  latest edited note
								$result = mysql_query("SELECT DATEDIFF(CURDATE(), date_create) AS intval, date_mod, save_count, title, id FROM m_notes ORDER BY date_create DESC LIMIT 1"); 
								while($row = mysql_fetch_array($result)) 					
								{
									//echo "- The latest edited note has an age of ".$row[0]." days. It is note number ".$row[4].", title <i>".$row[3]."</i> and was edited at ".$row[1].".<br>";
									$stats_last_edited_note_age = $row[0];
									$stats_last_edited_note_id = $row[4];
									$stats_last_edited_note_title = $row[3];
									$stats_last_edited_note_date = $row[1];
								}

								//  entire db size
								$result = mysql_query("SELECT sum( data_length + index_length ) /1024 /1024 FROM information_schema.TABLES WHERE table_schema = 'monoto'"); 
								while($row = mysql_fetch_array($result)) 					
								{
									//echo "- The entire monoto mysql db has the size of ".$row[0]." MB. Hell yeah thats small. This contains notes, the activity log and the user table.<br>";
									$stats_entire_monoto_db_size = $row[0];
								}



								//
								// DISPLAY  stats
								//
								// we can use:
								//
								// EVENTS
								// - $stats_events_of_current_user
								// 		- $stats_amount_of_creates
								//		- $stats_amount_of_imports
								//		- $stats_amount_of_canges
								//		- $stats_amount_of_renames
								//		- $stats_amount_of_deletes
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





								// Use our variables to create some kind of LOG text - should be informative but still funny if possible.

								
							}
						}
					}
					mysql_close($con);													// close sql connection

					echo "<br><small>Missing something? Please feel free to send me your sql-queries per mail</small>";
				}
				else
				{
					echo "<font color='silver'><i>The Stats section was disabled in the settings.</i></font>";
				}    			
			?>


			<!-- SPACER -->
			<div id="spacer">&nbsp;</div>

			<!-- KEYBOARD SHORTCUT -->
			<?php
				if($enable_info_keyboard_section == true)
				{
					?>

					<h2><a name="keyboard">keyboard shortcuts</a></h2>
			<table width="100%" border="0">
				<thead>
					<tr>
						<th align="left" width="20%">key</th>
						<th align="left" width="60%">function</th>
						<th align="left" width="10%">valid for</th>
						<th align="left" width="10%">status</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>ESC</td>
						<td>Reset input fields and sets the focus back to the main search field.</td>
						<td>home</td>
						<td>works</td>
					</tr>
					<tr>
						<td>F2</td>
						<td>Rename a selected note</td>
						<td>home</td>
						<td>works</td>
					</tr>
					<tr>
						<td>F9</td>
						<td>Save a selected note</td>
						<td>home</td>
						<td>works</td>
					</tr>
					<tr>
						<td>Del</td>
						<td>Delete selected note.</td>
						<td>home</td>
						<td>works</td>
					</tr>
					<tr>
						<td>Arrow Down</td>
						<td>row-selection</td>
						<td>home</td>
						<td>DUMMY</td>
					</tr>
					<tr>
						<td>Arrow Up</td>
						<td>row-selection</td>
						<td>home</td>
						<td>DUMMY</td>
					</tr>
					<tr>
						<td>F1</td>
						<td>Open the online help / documentation</td>
						<td>all pages</td>
						<td>only for home</td>
					</tr>
					<tr>
						<td>F5</td>
						<td>Reloads all notes from db.</td>
						<td>all pages</td>
						<td>only for home</td>
					</tr>
					<tr>
						<td>(Shift) + Alt + y</td>
						<td>Loads home page per accesskey</td>
						<td>all pages</td>
						<td>should work</td>
					</tr>
					<tr>
						<td>(Shift) + Alt + x</td>
						<td>Loads settings page per accesskey</td>
						<td>all pages</td>
						<td>should work</td>
					</tr>
					<tr>
						<td>(Shift) + Alt + c</td>
						<td>Loads info page per accesskey</td>
						<td>all pages</td>
						<td>should work</td>
					</tr>
				</tbody>
			</table>

			<?php
				}
				else
				{
					echo "<font color='silver'><i>The Keyboard section was disabled in the settings.</i></font>";
				} 
			?>


			<!-- SPACER -->
			<div id="spacer">&nbsp;</div>
			
			<!-- LOG -->
			<h2><a name="log">log</a></h2>
				<table width="100%" border="0">
				<thead>
					<tr>
						<th align="left" width="20%">event</th>
						<th align="left" width="60%">description</th>
						<th align="left" width="20%">count</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td bgcolor=#84FF00>create</td>
						<td>Note was created, version counter = 0, date created and modified set</td>
						<td>
						<?php
							$owner = $_SESSION['username'];

							// connect to mysql db and fetch all notes  
							$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);
							if (!$con)
					  		{
					  			die('Could not connect: ' . mysql_error());
								echo "Unable to connect to defined database - please check your credentials.";	
					  		}
							else
							{
								// do the mysql connect
								mysql_select_db($mysql_db, $con);
								// run the mysql query
								$result = mysql_query("SELECT count(event) FROM m_log WHERE event = 'create'  and owner='".$owner."'   "); 
								// fetch data and file table as a second step later on
								while($row = mysql_fetch_array($result))
								{
									echo $row[0];
								}
							}
							mysql_close($con);													// close sql connection

						?>
						</td>
					</tr>
					<tr>
						<td bgcolor=#00CDCD>import</td>
						<td>Note was imported using the importer, version counter = 0, date created and modified set</td>
						<td>
							<?php
							// connect to mysql db and fetch all notes  
							$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);
							if (!$con)
					  		{
					  			die('Could not connect: ' . mysql_error());
								echo "Unable to connect to defined database - please check your credentials.";	
					  		}
							else
							{
								// do the mysql connect
								mysql_select_db($mysql_db, $con);
								// run the mysql query
								$result = mysql_query("SELECT count(event) FROM m_log WHERE event = 'import' and owner='".$owner."' "); 
								// fetch data and file table as a second step later on
								while($row = mysql_fetch_array($result))
								{
									echo $row[0];
								}
							}
							mysql_close($con);													// close sql connection
						?>
						</td>
					</tr>
					<tr>
						<td bgcolor=#FFB914>save</td>
						<td>Content was changed, note saved, version counter +1, date modified set</td>
						<td>
							<?php
							// connect to mysql db and fetch all notes  
							$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);
							if (!$con)
					  		{
					  			die('Could not connect: ' . mysql_error());
								echo "Unable to connect to defined database - please check your credentials.";	
					  		}
							else
							{
								// do the mysql connect
								mysql_select_db($mysql_db, $con);
								// run the mysql query
								$result = mysql_query("SELECT count(event) FROM m_log WHERE event = 'save' and owner='".$owner."' "); 
								// fetch data and file table as a second step later on
								while($row = mysql_fetch_array($result))
								{
									echo $row[0];
								}
							}
							mysql_close($con);													// close sql connection
						?>
						</td>
					</tr>
					<tr>
						<td bgcolor=#D77D00>rename</td>
						<td>Title (and maybe content) was changed, version counter +1, date modified set</td>
						<td>
							<?php
							// connect to mysql db and fetch all notes  
							$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);
							if (!$con)
					  		{
					  			die('Could not connect: ' . mysql_error());
								echo "Unable to connect to defined database - please check your credentials.";	
					  		}
							else
							{
								// do the mysql connect
								mysql_select_db($mysql_db, $con);
								// run the mysql query
								$result = mysql_query("SELECT count(event) FROM m_log WHERE event = 'rename' and owner='".$owner."' "); 
								// fetch data and file table as a second step later on
								while($row = mysql_fetch_array($result))
								{
									echo $row[0];
								}
							}
							mysql_close($con);													// close sql connection
						?>
						</td>
					</tr>
					<tr>
						<td bgcolor=#CD0000>delete</td>
						<td>Note was deleted, id/number is doomed forever.</td>
						<td>
							<?php
							// connect to mysql db and fetch all notes  
							$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);
							if (!$con)
					  		{
					  			die('Could not connect: ' . mysql_error());
								echo "Unable to connect to defined database - please check your credentials.";	
					  		}
							else
							{
								// do the mysql connect
								mysql_select_db($mysql_db, $con);
								// run the mysql query
								$result = mysql_query("SELECT count(event) FROM m_log WHERE event = 'delete' and owner='".$owner."' "); 
								// fetch data and file table as a second step later on
								while($row = mysql_fetch_array($result))
								{
									echo $row[0];
								}
							}
							mysql_close($con);													// close sql connection
						?>
						</td>
					</tr>
					<tr>
						<td bgcolor=#666666>login</td>
						<td>User login - logincounter +1</td>
						<td>
							<?php
							// connect to mysql db and fetch all notes  
							$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);
							if (!$con)
					  		{
					  			die('Could not connect: ' . mysql_error());
								echo "Unable to connect to defined database - please check your credentials.";	
					  		}
							else
							{
								// do the mysql connect
								mysql_select_db($mysql_db, $con);
								// run the mysql query
								$result = mysql_query("SELECT count(event) FROM m_log WHERE event = 'login' and owner='".$owner."' "); 
								// fetch data and file table as a second step later on
								while($row = mysql_fetch_array($result))
								{
									echo $row[0];
								}
							}
							mysql_close($con);													// close sql connection
						?>
						</td>
					</tr>
					<tr>
						<td bgcolor=#CCCCCC>logout</td>
						<td>User logout - logoutcounter +1</td>
						<td>
							<?php
							// connect to mysql db and fetch all notes  
							$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);
							if (!$con)
					  		{
					  			die('Could not connect: ' . mysql_error());
								echo "Unable to connect to defined database - please check your credentials.";	
					  		}
							else
							{
								// do the mysql connect
								mysql_select_db($mysql_db, $con);
								// run the mysql query
								$result = mysql_query("SELECT count(event) FROM m_log WHERE event = 'logout' and owner='".$owner."' "); 
								// fetch data and file table as a second step later on
								while($row = mysql_fetch_array($result))
								{
									echo $row[0];
								}
							}
							mysql_close($con);													// close sql connection
						?>
						</td>
					</tr>
					<tr>
						<td bgcolor=#CCCCCC>notes eraser</td>
						<td>All user notes deleted</td>
						<td>
							<?php
							// connect to mysql db and fetch all notes  
							$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);
							if (!$con)
					  		{
					  			die('Could not connect: ' . mysql_error());
								echo "Unable to connect to defined database - please check your credentials.";	
					  		}
							else
							{
								// do the mysql connect
								mysql_select_db($mysql_db, $con);
								// run the mysql query
								$result = mysql_query("SELECT count(event) FROM m_log WHERE event = 'notes eraser' and owner='".$owner."' "); 
								// fetch data and file table as a second step later on
								while($row = mysql_fetch_array($result))
								{
									echo $row[0];
								}
							}
							mysql_close($con);													// close sql connection
						?>
						</td>
					</tr>
					<tr>
						<td bgcolor=#CCCCCC>events eraser</td>
						<td>All user events deleted</td>
						<td>
							<?php
							// connect to mysql db and fetch all notes  
							$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);
							if (!$con)
					  		{
					  			die('Could not connect: ' . mysql_error());
								echo "Unable to connect to defined database - please check your credentials.";	
					  		}
							else
							{
								// do the mysql connect
								mysql_select_db($mysql_db, $con);
								// run the mysql query
								$result = mysql_query("SELECT count(event) FROM m_log WHERE event = 'events eraser' and owner='".$owner."' "); 
								// fetch data and file table as a second step later on
								while($row = mysql_fetch_array($result))
								{
									echo $row[0];
								}
							}
							mysql_close($con);													// close sql connection
						?>
						</td>
					</tr>
				</tbody>
				</table>
				<br>

				<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
					<thead>
						<tr>
							<th>id</th>
							<th>event</th>
							<th>details</th>
							<th>timestamp</th>
						</tr>
					</thead>
					<tbody>

					<?php
						// connect to mysql db and fetch all notes  
						$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);
						if (!$con)
					  	{
					  		die('Could not connect: ' . mysql_error());
							echo "Unable to connect to defined database - please check your credentials.";	
					  	}
						else
						{
							// do the mysql connect
							mysql_select_db($mysql_db, $con);

							$owner= $_SESSION['username'];

							// run the mysql query
							//$result = mysql_query("SELECT * FROM m_log"); // m_log
							$result = mysql_query("SELECT * FROM m_log WHERE owner='$owner' "); // m_log

							// fetch data and file table as a second step later on
							while($row = mysql_fetch_array($result))
							{
								echo '<tr class="odd gradeU">';
									echo '<td>'.$row[0].'</td>';
									echo '<td>'.$row[1].'</td>';		// event - colorize?
									echo '<td>'.$row[2].'</td>';
									echo '<td>'.$row[3].'</td>';
								echo '</tr>';
							}
						}
						mysql_close($con);													// close sql connection
					?>

					</tbody>
					<tfoot>
						<tr>
							<th>id</th>
							<th>event</th>
							<th>details</th>
							<th>timestamp</th>
						</tr>
					</tfoot>
				</table>

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
//
// UpdateCheck
//
// http://wuxiaotian.com/2009/09/php-check-for-updates-script/
if ( isset($_POST["doUpdateCheck"]) ) 
{
	session_start();

	// assume everything is good
	$critical = FALSE;
	$update = FALSE;

	$url = "https://raw.github.com/macfidelity/monoto/master/vStable.csv";
	$fp = @fopen ($url, 'r') or print ('UPDATE SERVER OFFLINE');
	$read = fgetcsv ($fp);
	fclose ($fp); //always a good idea to close the file connection

	// its critical
	if (($read[0] > $m_build) && ($read[2] == "1")) 
	{ 
		$critical = TRUE; 
	}
		
	// normal update
	if ($read[0] > $m_build) 
	{ 
		$update = TRUE; 
	}

	if ($critical) 
	{ 
			// print '<p>'.$read[1].'</p>';   													// aka buildbeschreibung
			// print '<p>You can get it at <a href="'.$read[3].'">'.$read[3].'</a></p>';		// aka DL-url

   		echo '<script type="text/javascript">
   				var r=confirm("There is a critical update available. Should i download the latest version?")
				if (r==true)
  				{
  					window.location = "https://raw.github.com/macfidelity/monoto/master/versionCheck.csv","_blank"; 
  				}
   		</script>';

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
	include 'config.php';

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
		{ 
			$critical = TRUE; 
		}
			
		// normal update
		if ($read[0] > $m_build) 
		{ 
			$update = TRUE; 
		}

		if ($critical) 
		{ 
				// print '<p>'.$read[1].'</p>';   													// aka buildbeschreibung
				// print '<p>You can get it at <a href="'.$read[3].'">'.$read[3].'</a></p>';		// aka DL-url

	   		echo '<script type="text/javascript">
	   				var r=confirm("There is a critical dev update available. Should i download the latest version?")
					if (r==true)
	  				{
	  					window.location = "https://raw.github.com/macfidelity/monoto/master/versionCheck.csv","_blank"; 
	  				}
	   		</script>';

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
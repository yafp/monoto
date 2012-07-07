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
				The basic workflow is inspired by <a href="http://notational.net/" target="_new">Notational Velocity</a>.
				Wanna know <a href="https://github.com/macfidelity/monoto/wiki/About-the-monoto-history">more</a>?

			<!-- SPACER -->
			<div id="spacer">&nbsp;</div>

			<?php
				}
				else
				{
					echo "NOTICE: <i>About section was disabled in the settings.</i>";
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
					<td><?php echo $m_build; ?></td>
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
					echo "NOTICE: <i>Changelog section was disabled in the settings.</i>";
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

						// amount of notes
						$result = mysql_query("SELECT count(*) FROM m_notes"); 					// run the mysql query
						while($row = mysql_fetch_array($result)) 								// fetch data and file table as a second step later on
						{
							// is it worth displayin g the stats at all?
							if($row[0] == 0)
							{
								echo "Lazy ass award goes to you as you havent created a single note .....erm yes ... ".$row[0]." notes in your monoto database i am not willing to display any note-stats here. Do your work first my friend.<br>"; 	// output amount of notes
							}
							else
							{
								echo "- You have ".$row[0]." notes in your monoto database<br>"; 	// output amount of notes	

								// run other sqml commands
								//
								// amount of activity-events
								$result = mysql_query("SELECT count(*) FROM m_log"); 
								while($row = mysql_fetch_array($result)) 					
								{
									echo "- According to your log you fired already ".$row[0]." event(s) to your database.<br>"; 
								}

								// amount of create-events
								$result = mysql_query("SELECT count(*) FROM m_log WHERE event='create'"); 
								while($row = mysql_fetch_array($result)) 					
								{
									echo "- Those can be separated into ".$row[0]." note-creations,"; 
								}

								// amount of import-events
								$result = mysql_query("SELECT count(*) FROM m_log WHERE event='import'"); 
								while($row = mysql_fetch_array($result)) 					
								{
									echo " ".$row[0]." note imports,"; 
								}

								// amount of edits-events
								$result = mysql_query("SELECT count(*) FROM m_log WHERE event='change'"); 
								while($row = mysql_fetch_array($result)) 					
								{
									echo " ".$row[0]." modifings,"; // amount of notes
								}

								// amount of renames-events
								$result = mysql_query("SELECT count(*) FROM m_log WHERE event='rename'"); 
								while($row = mysql_fetch_array($result)) 					
								{
									echo " ".$row[0]." renamings"; // amount of notes
								}

								// amount of delete-events
								$result = mysql_query("SELECT count(*) FROM m_log WHERE event='delete'"); 
								while($row = mysql_fetch_array($result)) 					
								{
									echo " and last but not least ".$row[0]." note-deletions.<br>"; 
								}

								//  highest note-version (most used note)
								$result = mysql_query("SELECT id, title, save_count FROM m_notes ORDER BY save_count DESC LIMIT 1"); 
								while($row = mysql_fetch_array($result)) 					
								{
									echo "- You seem to use note ".$row[0]." with title <i>".$row[1]."</i> a lot - it has ".$row[2]." versions, which means it is the most edited note.<br>";
								}

								//  shortest and longest note-content
								$result = mysql_query("SELECT MIN( LENGTH( content ) ) AS shortest, MAX( LENGTH( title ) ) AS longest FROM m_notes"); 
								while($row = mysql_fetch_array($result)) 					
								{
									echo "- Shortest note content is ".$row[0]." chars small - while longest is using ".$row[1]." characters. Well thats some kind of difference.<br>";
								}

								//  oldest created note
								$result = mysql_query("SELECT DATEDIFF(CURDATE(), date_create) AS intval, date_create, id, title FROM m_notes ORDER BY date_create ASC LIMIT 1"); 
								while($row = mysql_fetch_array($result)) 					
								{
									echo "- The oldest and still existing note has an age of ".$row[0]." days. It was created <i>".$row[1]."</i> with number ".$row[2].". Wow that means you are using monoto since ".$row[0]." days - hope you love it.<br>";
								}

								//  newest/latest created note
								$result = mysql_query("SELECT DATEDIFF(CURDATE(), date_create) AS intval, date_create, save_count, title, id FROM m_notes WHERE save_count = '0' ORDER BY date_create DESC LIMIT 1"); 
								while($row = mysql_fetch_array($result)) 					
								{
									echo "- The latest created and still unedited note has an age of ".$row[0]." days. It was created <i>".$row[1]."</i> with number ".$row[4]." and the title <i>".$row[3]."</i>.<br>";
								}

								//  latest edited note
								$result = mysql_query("SELECT DATEDIFF(CURDATE(), date_create) AS intval, date_mod, save_count, title, id FROM m_notes ORDER BY date_create DESC LIMIT 1"); 
								while($row = mysql_fetch_array($result)) 					
								{
									echo "- The latest edited note has an age of ".$row[0]." days. It is note number ".$row[4].", title <i>".$row[3]."</i> and was edited at ".$row[1].".<br>";
								}

								//  entire db size
								$result = mysql_query("SELECT sum( data_length + index_length ) /1024 /1024 FROM information_schema.TABLES WHERE table_schema = 'monoto'"); 
								while($row = mysql_fetch_array($result)) 					
								{
									echo "- The entire monoto mysql db has the size of ".$row[0]." MB. Hell yeah thats small. This contains notes, the activity log and your user table.<br>";
								}
							}
						}
					}
					mysql_close($con);													// close sql connection

					echo "<br><small>Missing something? Please feel free to send me your sql-queries per mail</small>";
				}
				else
				{
					echo "NOTICE: <i>Stats section was disabled in the settings.</i>";
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
					echo "NOTICE: <i>Keyboard section was disabled in the settings.</i>";
				} 
			?>


			<!-- SPACER -->
			<div id="spacer">&nbsp;</div>
			
			<!-- LOG -->
			<h2><a name="log">log</a></h2>
				The log offers a full list of all relevant events, see the following table for details.
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
						<td></td>
					</tr>
					<tr>
						<td bgcolor=#00CDCD>import</td>
						<td>Note was imported using the importer, version counter = 0, date created and modified set</td>
						<td></td>
					</tr>
					<tr>
						<td bgcolor=#FFB914>change</td>
						<td>Content was changed, version counter +1, date modified set</td>
						<td></td>
					</tr>
					<tr>
						<td bgcolor=#D77D00>rename</td>
						<td>Title (and maybe content) was changed, version counter +1, date modified set</td>
						<td></td>
					</tr>
					
					<tr>
						<td bgcolor=#CD0000>delete</td>
						<td>Note was deleted, id/number is doomed forever.</td>
						<td></td>
					</tr>
				</tbody>
				</table>

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

							// run the mysql query
							$result = mysql_query("SELECT * FROM m_log"); // m_log

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

	if (!$_SESSION['updateCheck']) 
	{
		$url = "http://localhost/monoto/versionCheck.csv";
		$fp = @fopen ($url, 'r') or print ('UPDATE SERVER OFFLINE');
		$read = fgetcsv ($fp);
		fclose ($fp); //always a good idea to close the file connection
		$_SESSION['updateCheck'] = TRUE;

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
  					window.location = "https://github.com/macfidelity/monoto/blob/master/versionCheck.csv","_blank"; 
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
	} // session 
}
?>
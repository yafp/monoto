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

		<!-- SHOW/HIDE DIV -->
		<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="js/m_showHide.js"></script>

		<!--  m_reallyLogout-->
		<script type="text/javascript" language="javascript" src="js/m_reallyLogout.js"></script>

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
						echo '<h2>settings toc</h2>';
						echo '<small>';
						echo '<ul>';
							echo '<li><a href="#basic">basic-settings</a></li>';
							echo '<li><a href="#profile">profile</a></li>';
							echo '<li><a href="#importer">importer</a></li>';
							echo '<li><a href="#exporter">exporter</a></li>';
							echo '<li><a href="#eraser">eraser</a></li>';
							echo '<li><a href="#brainstorm">brainstorm</a></li>';
						echo '</ul>';
						echo '</small>';
						
					}
				?>

				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>

				<!-- CORE SETTINGS -->
				<h2><a name="basic">basic settings</a></h2>
				<table width="100%" border="0">
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
				</tbody>
				</table>


				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>

				<!-- PROFILE -->
				<h2><a name="profile">profile</a></h2>


				<!-- Login & logout counter-->
				<?php
				// connect to mysql
				$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);		
				if (!$con)
				{
					die('Could not connect: ' . mysql_error());
				}	
				mysql_select_db($mysql_db, $con);



				// display user image - hardcoded dummy image
				//
				echo "<a href=''>";
				echo "<img src='images/default_user_icon_trans.png' align='right' border='1'>";
				echo "</a>";






				$sql="SELECT login_counter, logout_counter FROM m_users WHERE username='".$_SESSION['username']."' ";
				$result = mysql_query($sql);
				while($row = mysql_fetch_array($result)) 					
				{
					echo "<b>User</b><br> ".$_SESSION['username']."<br>";
					echo "<small>(".$row[0]." logins and ".$row[1]." logouts)</small><br><br>";
				}


				echo "<b>Changing password</b><br>";
				?>



				<!-- CHANGE USER PASSWORD BUTTON -->
				<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
						<tr>Please enter your new password twice and press the UPDATE button.<br>
							<input type="password" name="newPassword1" placeholder="Password" />
							<input type="password" name="newPassword2" placeholder="Please enter the new password again" />


							<td><input type="submit" name="doChangeUserPW" value="Update" /></td>
						</tr>					
				</form>











				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>

				<!-- IMPORTER -->
				<h2><a name="importer">importer</a></h2>
				<table width="100%" border="0">
				<thead>
					<tr>
						<th align="left" width="45%">Theory: What importer does</th>
						<th align="left" width="10%"></th>
						<th align="left" width="45%">Practice: Step by Step</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>The importer can import plain-text files into monoto.</td>
						<td></td>
						<td>Press the <i>Browse...</i> button</td>
					</tr>
					<tr>
						<td>- create a note with the title of filename (cutting the extension)</td>
						<td></td>
						<td>Select one or multiple text-files in the file-selection-dialog</td>
					</tr>
					<tr>
						<td>- each new note contains the related file-content</td>
						<td></td>
						<td>Confirm the file-selection dialog</td>
					</tr>
					<tr>
						<td>- add a creation date to each new created note</td>
						<td></td>
						<td>Press the <i>Import</i> button</td>
					</tr>
					<tr>
						<td>- define a version number for the new notes (0)</td>
						<td></td>
						<td>Wait and pray - the current approach is crappy</td>
					</tr>
				</tbody>
				</table>
				<br>

				<!-- the real importer -->
				<!-- http://stackoverflow.com/questions/5593473/how-to-upload-and-parse-a-csv-file-in-php -->
				<table width="100%" border="0">
					<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
						<tr>
							<td  align="right" width="55%"><input type="file" name="file[]" multiple id="file[]" /></td>
							<td><input type="submit" name="doImport" value="Import" /></td>
						</tr>					
					</form>
				</table>


				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>

				<!-- EXPORTER -->
				<h2><a name="exporter">exporter</a></h2>
				<table width="100%" border="0">
				<thead>
					<tr>
						<th align="left" width="45%">Theory: What exporter does</th>
						<th align="left" width="10%"></th>
						<th align="left" width="45%">Practice: Step by Step</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>- export some kind of mysql dump ..not yet fully working.</td>
						<td></td>
						<td>Press the export button</td>
					</tr>
				</tbody>
				</table>

				<!-- the real exporter -->
				<table width="100%" border="0">
					<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
						<tr>
							<td width="55%"></td>
							<td><input type="submit" name="doExport" value="Export" /></td>
						</tr>					
					</form>
				</table>

				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>

				<!-- ERASER -->
				<h2><a name="eraser">eraser</a></h2>
				<table width="100%" border="0">
				<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
				<tbody>
					<tr>
						<td width="30%"><input type="submit" name="doDelAllNotes" value="Delete Notes" /></td>
						<td>Delete all your notes. There is no way back.</td>
					</tr>
					<tr>
						<td><input type="submit" name="doDelAllEvents" value="Delete Events" /></td>
						<td>Delete all your log events. There is no way back.</td>
					</tr>
				</tbody>
				</form>
				</table>

				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>


				<!-- OTHER -->
				<h2><a name="brainstorm">brainstorm</a></h2>
				<a href="#" class="show_hide">show</a>
				<div class="slidingDiv">
					<b>possible settings for this page:</b><br >
					<ul>
						<li>searchable columns (als checkboxen?)</li>
						<li>visible columns (als checkboxen?)</li>
							<ul>
								<li>ID</li>
								<li>Title</li>
								<li>Content</li>
								<li>Tags</li>
								<li>Date modified</li>
								<li>Date created</li>
								<li>Version</li>
							</ul>
						<li>pagination</li>
						<li>default rows per page</li>
						<li>default search text?</li>
					</ul>

					<!-- BRAINSTORM -->
					<b>OTHER GENERAL FUNCTIONS:</b><br >
					<b>almost sure to come:</b><br >
					- enable and disable buttons based on field-status
					- ...<br><br>

					<b>maybe:</b><br>
					- regexp search?<br>
					- check if new note title exists already (maybe via sync to search field)?<br>
					- tags? <br>
					- tagcloud?<br>
					- generate graph for log?<br>
					- colorize log (create = green, edit = yellow, delete = red)
					- activate and deactive buttons: create, edit, delete and rename
					<br>
					<a href="#" class="show_hide">hide</a>
				</div>
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
	$owner = $_SESSION['username'];

	echo "change pw.................";

	$newPassword1 = $_POST['newPassword1'];
	$newPassword2 = $_POST['newPassword2'];




	$password = $newPassword1;
	$username = $owner;



	echo $newPassword1;

	// Check if user entered two times the same new password
	if($newPassword1 == $newPassword2)
	{
		echo "lets change the pw";

		// playing with hash
		$hash = hash('sha256', $password);
		// playing with salt - creates a 3 character sequence
		function createSalt()
		{
    		$string = md5(uniqid(rand(), true));
    		return substr($string, 0, 3);
		}
		$salt = createSalt();
		$hash = hash('sha256', $salt . $hash);

    	// connect to mysql
		$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);		
		if (!$con)
		{
			die('Could not connect: ' . mysql_error());
		}

		mysql_select_db($mysql_db, $con);				// select db

		// change pw
		$query = "UPDATE m_users SET  password='$hash', salt='$salt' WHERE username='$owner'";
		mysql_query($query);

		mysql_close($con); 								// close sql connection
	}
	else // User entered 2 different password - cant change pw like that.
	{
		echo "cant change";
	}
}








//
// delAllNotes - button was pressed
//                 
if ( isset($_POST["doDelAllNotes"]) ) 
{	
	// connect to mysql
	$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);		
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}	
	mysql_select_db($mysql_db, $con);				// select db

	$owner = $_SESSION['username'];

	// update m_notes
	$sql="DELETE FROM m_notes WHERE owner='$owner'";
	$result = mysql_query($sql);

	// update m_log
	$event = "notes eraser";
	$details = "All user notes deleted with eraser.";
	$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(), '$owner' )";
	$result = mysql_query($sql);

	mysql_close($con); 								// close sql connection
}



//
// delAllEvents - button was pressed
//                 
if ( isset($_POST["doDelAllEvents"]) ) 
{	
	// connect to mysql
	$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);		
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}	
	mysql_select_db($mysql_db, $con);				// select db

	$owner = $_SESSION['username'];

	// update m_notes
	$sql="DELETE FROM m_log WHERE owner='$owner'";
	$result = mysql_query($sql);

	// update m_log
	$event = "events eraser";
	$details = "All user events deleted with eraser.";
	$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(), '$owner' )";
	$result = mysql_query($sql);

	mysql_close($con); 								// close sql connection
}



//
// exporter - submit button was pressed
//
if ( isset($_POST["doExport"]) ) 
{
	echo "try export and download of mysqldata<br>";

	echo '<script type="text/javascript" language="javascript">
	window.open("scripts/dump.php", "width=400,height=500,top=50,left=280,resizable,toolbar,scrollbars,menubar,");
	</script>';

}



//
// importer submit button was pressed
//
if ( isset($_POST["doImport"]) ) 
{
	//print_r($_FILES['file']['tmp_name']);
	//print_r($_FILES['file']['name']);
	// means: we got an array of files

    // connect to mysql
	$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);		
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}	
	mysql_select_db($mysql_db, $con);				// select db

	$owner = $_SESSION['username'];

	// loop it for each note
	foreach($_FILES['file']['name'] as $key => $value)
	{
		echo "Importing: ".$newNoteTitle = $_FILES["file"]["name"][$key]."<br>";

		//if file already exists
       	if (file_exists("upload/" . $_FILES["file"]["name"])) 
        {
        	echo $_FILES["file"]["name"] . " already exists. ";
      	}
        else 
        {
           	//Print file details
           	//echo "Upload: " . $_FILES["file"]["name"] . "<br />";
           	//echo "Type: " . $_FILES["file"]["type"] . "<br />";
           	//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
           	//echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
			//echo "File content: ".file_get_contents($_FILES['file']['tmp_name']); 

			// define insert vars
			$newNoteTitle = $_FILES["file"]["name"][$key];
			// we need to cut the extension from filename - ugly hack
			$newNoteTitle = preg_replace("/\\.[^.\\s]{3,4}$/", "", $newNoteTitle);
			$newNoteContent = file_get_contents($_FILES['file']['tmp_name'][$key]);

			// check if there is already a note with this title - as we dislike having > 1 note with the same title ...yes we do
			if(mysql_num_rows(mysql_query("SELECT title FROM m_notes WHERE title = '$newNoteTitle'")))
			{
				echo "Error - there is already a note with the title:" .$newNoteTitle."<br>";
			}
			else
			{
				// we can create it
				// update notes: m_notes
				//$sql="INSERT INTO m_notes (title, content, date_create, date_mod) VALUES ('$newNoteTitle', '$newNoteContent', now(), now() )";
				$sql="INSERT INTO m_notes (title, content, date_create, date_mod, owner) VALUES ('$newNoteTitle', '$newNoteContent', now(), now(), '$owner' )";

				$result = mysql_query($sql);
				if (!$result) 
				{
   					die('Error: ' . mysql_error());
				}
				else 
				{
					// update event-log: m_log
					$newNoteContentSummary = substr($newNoteContent, 0, 10);
					$event = "import";
					$details = "Note: <b>".$newNoteTitle."</b> with content: <b>".$newNoteContentSummary."...</b>";
					//$sql="INSERT INTO m_log (event, details, activity_date) VALUES ('$event','$details', now() )";
					$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event','$details', now(), '$owner' )";

					$result = mysql_query($sql);

					echo "Note: ".$newNoteTitle = $_FILES["file"]["name"][$key]." imported.<br><br>";
				}					
			} 	
      	}

	}
	$amount_of_import_files = $key +1;
	echo "Finished import - handling ".$amount_of_import_files." files";
	
	mysql_close($con);									// close sql connection
} 


mysql_close($con);									// close sql connection

?>
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
		<link rel="shortcut icon" type="image/ico" href="images/favicon.ico" />
		<title>monoto-notes</title>
		<style type="text/css" title="currentStyle">
			@import "css/page.css";
			@import "css/table.css";
		</style>
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
						echo '<small><ul>';
							echo '<li><a href="#profile">profile</a></li>';
							echo '<li><a href="#importer">importer</a></li>';
							echo '<li><a href="#exporter">exporter</a></li>';
							echo '<li><a href="#eraser">eraser</a></li>';
						echo '</ul></small>';
					}
				?>

				
				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>

				<!-- PROFILE -->
				<h2><a name="profile">profile</a></h2>
				<?php
					include ('scripts/db.php');						// connect to db
					connectToDB();

					/*
					echo "<a href=''><img src='images/default_user_icon_trans.png' align='right' border='1'></a>";			// display user image - hardcoded dummy image
					*/

					// display user icon from db
					/*
					$sql="SELECT user_icon FROM m_users WHERE username='".$_SESSION['username']."' ";
					$row = mysql_fetch_array($sql);
					$content = $row['user_icon'];
					*/

					// Login & logout counter
					$sql="SELECT login_counter, logout_counter FROM m_users WHERE username='".$_SESSION['username']."' ";
					$result = mysql_query($sql);
					while($row = mysql_fetch_array($result)) 					
					{
						echo "<b>User</b><br> ".$_SESSION['username']." - <small>(".$row[0]." logins and ".$row[1]." logouts)</small><br><br>";
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

				<!-- CHANGE USER ICON BUTTON -->
				<br><b>Changing the user icon:</b><br>This will store your image in the users-table - but the image itself is not used so far ;)
				<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
						<tr>
							<input name="MAX_FILE_SIZE" value="102400" type="hidden">
							<input name="image" accept="image/jpeg" type="file">
							<input value="Change Icon" type="submit" name="doChangeUserIcon" >	
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
					<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data" name="importerForm">
						<tr>
							<td  align="right" width="55%"><input type="file" name="file[]" multiple id="file[]" /></td>
							<td><input type="submit" name="doImport" value="Import" /></td>
						</tr>
						<tr>
							<td colspan="2"><textarea class="database" disabled="disabled" id="importLog" style="width:100%" name="importLog" cols="110" rows="5" placeholder="Output of impoter will be displayed here."></textarea></td>
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
						<td>Export all your notes (id, title, content) to a single csv file (tab separated).</td>
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

		$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw); 		// connect to mysql		
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
		//echo "cant change";
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

	// update m_notes = delete notes
	$sql="DELETE FROM m_notes WHERE owner='$owner'";
	$result = mysql_query($sql);

	// update m_log
	$event = "notes eraser";
	$details = "All user notes deleted with eraser.";
	$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(), '$owner' )";
	$result = mysql_query($sql);
	disconnectFromDB();
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
	disconnectFromDB();
}


//
// exporter - submit button was pressed
//
if ( isset($_POST["doExport"]) ) 
{
	echo '<script type="text/javascript" language="javascript">
window.open("scripts/expNotes.php", "width=400,height=500,top=50,left=280,resizable,toolbar,scrollbars,menubar,");
</script>';				
}



//
// importer submit button was pressed
//
if ( isset($_POST["doImport"]) ) 
{
	// connect to db
	//include ('scripts/db.php');
	connectToDB();

	$owner = $_SESSION['username'];
	$good_counter =0;

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
				?>
				<script type="text/javascript">
					var newtext = '<?php echo "Error - there is already a note with the title: ".$newNoteTitle.". Import of that specificnote was skipped."; ?>';
					document.importerForm.importLog.value += newtext;
				</script>
				<?php

			}
			else // we can create it - update notes: m_notes
				{
					$sql="INSERT INTO m_notes (title, content, save_count,  date_create, date_mod, owner) VALUES ('$newNoteTitle', '$newNoteContent', '1',now(), now(), '$owner' )";
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
						$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event','$details', now(), '$owner' )";
						$result = mysql_query($sql);

						?>
							<script type="text/javascript">
								var newtext = '<?php echo "Note: ".$newNoteTitle." successfully imported."; ?>';
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
		disconnectFromDB();
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
          
		// update user record     
        $query = "UPDATE m_users SET  user_icon='$data' WHERE username='$owner'";
		mysql_query($query);
	}
	else // no image defined.
	{
   		echo "No image selected/uploaded";
	}
}

?>
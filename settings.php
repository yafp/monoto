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
							echo '<li><a href="#welcome">welcome</a></li>';
							echo '<li><a href="#profile">profile</a></li>';
							echo '<li><a href="#importer">importer</a></li>';
							echo '<li><a href="#exporter">exporter</a></li>';
							echo '<li><a href="#eraser">eraser</a></li>';
						echo '</ul></small>';
					}
				?>

				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>

				<!-- WELCOME MESSAGE -->
				<h2><a name="welcome">welcome</a></h2>
				<?php 
					if($enable_welcome_message == true)
					{
						if (strlen($welcome_message_to_all_users) > 0) // check if welcome message is configured or just empty
						{
							echo $welcome_message_to_all_users;
						}
						else
						{
							echo "<i>Welcome message is enabled but not defined by admin. Shame on him.</i>";
						}
					}

				?>

				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>

				<!-- PROFILE -->
				<h2><a name="profile">profile</a></h2>
				<?php
					// connect to db
					include ('scripts/db.php');
					connectToDB();

					// display user image - hardcoded dummy image
					echo "<a href=''><img src='images/default_user_icon_trans.png' align='right' border='1'></a>";

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

				<!-- CHANGE USER ICON BUTTON -->
				<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
						<tr>
							<input name="MAX_FILE_SIZE" value="102400" type="hidden">
							<input name="image" accept="image/jpeg" type="file">
							<input value="Change Icon" type="submit" name="doChangeUserIcon" >Dummy	
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
		//echo "cant change";
	}
}



//
// delAllNotes - button was pressed
//                 
if ( isset($_POST["doDelAllNotes"]) ) 
{	
	// connect to db
	include ('conf/config.php');
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
	//print_r($_FILES['file']['tmp_name']);
	//print_r($_FILES['file']['name']);
	// means: we got an array of files

	// connect to db
	//include ('scripts/db.php');
	connectToDB();

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
				// we can create it - update notes: m_notes
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
	
	disconnectFromDB();
} 



//
// Changing User icon
//
if ( isset($_POST["doChangeUserIcon"]) ) 
{
	// connect to db
	//include ('scripts/db.php');
	connectToDB();

	$owner = $_SESSION['username'];

	// is there a new file at all?
	if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) 
	{ 
		// Temporary file name stored on the server
        $tmpName  = $_FILES['image']['tmp_name'];  
           
        // Read the file 
        $fp     = fopen($tmpName, 'r');
        $data = fread($fp, filesize($tmpName));
        $data = addslashes($data);
        fclose($fp);
          
		// update user record     
        $query = "UPDATE m_users SET  user_icon='$data' WHERE username='$owner'";
		mysql_query($query);

        //print "Thanks, the new user icon has been uploaded.";
	}
	else 
	{
   		print "No image selected/uploaded";
	}
}

?>
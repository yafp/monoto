<?php
	session_start();
	if($_SESSION['valid'] == 1)		// check if the user-session is valid or not
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
		<!-- jquery -->
		<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
		<!--  m_reallyLogout-->
		<script type="text/javascript" language="javascript" src="js/m_reallyLogout.js"></script>
		<!--  m_disableRightClick-->
		<script type="text/javascript" language="javascript" src="js/m_disableRightClick.js"></script>
		<!-- scroll up -->
		<script type="text/javascript">
		$(function () 
		{
			/* set variables locally for increased performance */
			var scroll_timer;
			var displayed = false;
			var $message = $('#message a');
			var $window = $(window);
			var top = $(document.body).children(0).position().top;

			/* react to scroll event on window */
			$window.scroll(function () 
			{
			   	window.clearTimeout(scroll_timer);
			    scroll_timer = window.setTimeout(function () 
			    {
			       	if($window.scrollTop() <= top)
			        {
			            displayed = false;
			            $message.fadeOut(500);
			        }
			        else if(displayed == false)
			        {
			            displayed = true;
			            $message.stop(true, true).show().click(function () { $message.fadeOut(500); });
			        }
			    }, 100);
			});
		});
		</script>
		<!-- toc/info/help -->
		<script type="text/javascript">
			$(document).ready(function()
			{
				//$(".accordion h3:first").addClass("active");
				//$(".accordion p:not(:first)").hide();
				$(".accordion p").hide();
				$(".accordion h3").click(function(){
					$(this).next("p").slideToggle("slow")
					.siblings("p:visible").slideUp("slow");
					$(this).toggleClass("active");
					$(this).siblings("h3").removeClass("active");
				});
			});
		</script>
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
						?>
							<h2><a name="desc">settings</a></h2>
							<div class="accordion">
							<h3>profile [<a href="#profile">...</a>]</h3>
							<p><img src="images/info_icon.png" width="40" align="right">the <a href="#profile">profile</a> section displays a quick overview about your user account. You can change your monoto password here and upload an user image.</p>
							<h3>importer [<a href="#importer">...</a>]</h3>
							<p><img src="images/info_icon.png" width="40" align="right">the <a href="#importer">importer</a> section allows you to import single or multiple text notes.</p>
							<h3>exporter [<a href="#exporter">...</a>]</h3>
							<p><img src="images/info_icon.png" width="40" align="right">the <a href="#exporter">exporter</a> section allows you to export your notes to a single, tab-separated csv-file. This included only the note-ids, -titles and content.</p>
							<h3>eraser [<a href="#eraser">...</a>]</h3>
							<p><img src="images/info_icon.png" width="40" align="right">the <a href="#eraser">eraser</a> section allows you to delete your notes and your log events. The eraser event itself will be your first new log entry.</p>
							</div>
						<?php
					}
				?>

				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>

				<!-- PROFILE -->
				<h2><a name="profile">profile</a></h2>
				<?php
					include ('scripts/db.php');						// connect to db
					connectToDB();

					echo "<a href=''><img src='images/user_icons/user-14.png' alt='user icon' align='right' border='1'></a>";			// display user image - hardcoded dummy image
					
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
				Please enter your new password twice and press the UPDATE button.
				<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
							<input type="password" name="newPassword1" placeholder="Password" />
							<input type="password" name="newPassword2" placeholder="Please enter the new password again" /><br>
							<input type="submit" name="doChangeUserPW" value="Update" style="width:140px" />					
				</form>

				<!-- CHANGE USER ICON BUTTON -->
				<br><b>Changing the user icon:</b><br>This will store your image in the users-table - but the image itself is not used so far.
				<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
							<input name="MAX_FILE_SIZE" value="102400" type="hidden">
							<input name="image" accept="image/jpeg" type="file"><br>
							<input value="Change Icon" type="submit" name="doChangeUserIcon" style="width:140px" >					
				</form>
				
				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>

				<!-- IMPORTER - http://stackoverflow.com/questions/5593473/how-to-upload-and-parse-a-csv-file-in-php -->
				<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data" name="importerForm">
					<h2><a name="importer">importer</a></h2>
						<input type="file" name="file[]" multiple id="file[]" /><br>
						<input type="submit" name="doImport" value="Import"  style="width:140px"/>
						<textarea class="database" disabled="disabled" id="importLog" style="width:100%" name="importLog" cols="110" rows="5" placeholder="Output of impoter will be displayed here."></textarea>
				</form>
				
				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>

				<!-- EXPORTER -->
				<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
					<h2><a name="exporter">exporter</a></h2>
						<input type="submit" name="doExport" value="Export" style="width:140px" />
				</form>

				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>

				<!-- ERASER -->
				<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
					<h2><a name="eraser">eraser</a></h2>
					<input type="submit" name="doDelAllNotes" value="Delete Notes" style="width:140px" /><br>
					<input type="submit" name="doDelAllEvents" value="Delete Events" style="width:140px" />
				</form>
			
				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>
			</div>
		</div>

		<!-- back to top -->
		<div id="message"><a href="#noteContentCo">Scroll to top</a></div>
		
		<!--  FOOTER -->
		<?php include 'footer.php'; ?>
	</body>
</html>


<?php
	}
	else 				//session is NOT valid - redirect to login
	{	header('Location: redirect.php'); 	}
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

		$query = "UPDATE m_users SET  password='$hash', salt='$salt' WHERE username='$owner'";			// change pw
		mysql_query($query);
		mysql_close($con); 								// close sql connection
	}
	else // User entered 2 different password - cant change pw like that.
	{
		echo '<script type="text/javascript">alert("Error - Password mismatch while trying to change.");</script>';
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
	echo '<script type="text/javascript" language="javascript">window.open("scripts/expNotes.php", "width=400,height=500,top=50,left=280,resizable,toolbar,scrollbars,menubar,");</script>';				
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
	        //echo "Upload: " . $_FILES["file"]["name"] . "<br />";
	        //echo "Type: " . $_FILES["file"]["type"] . "<br />";
	        //echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
	        //echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
			//echo "File content: ".file_get_contents($_FILES['file']['tmp_name']); 

			// define insert vars
			$newNoteTitle = $_FILES["file"]["name"][$key];
			$newNoteTitle = preg_replace("/\\.[^.\\s]{3,4}$/", "", $newNoteTitle);					// we need to cut the extension from filename - ugly hack
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
					{	die('Error: ' . mysql_error());		}
					else  // update event-log: m_log
					{
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
   		echo '<script type="text/javascript">alert("Error - no image defined");</script>';
	}
}

?>
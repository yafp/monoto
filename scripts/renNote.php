<?php
	session_start();

	if($_SESSION['valid'] == 1)	// check if the user-session is valid or not
	{
		// get data for rename note
		$renameNoteID = $_POST['renameNoteID'];
		$renameNoteTitle = $_POST['renameNoteTitle'];
		$renameNoteContent = $_POST['renameNoteContent'];
		// $renameNoteEditCount +1
		$renameNoteCounter = $_POST['renameNoteCounter'];
		$renameNoteCounter = $renameNoteCounter+1;

		include '../conf/config.php';

		$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);	// connect to mysql
		if (!$con)
		{
			die('Could not connect: ' . mysql_error());
		}
		mysql_select_db($mysql_db, $con);									// select db

		$owner = $_SESSION['username'];

		// check if the new title is in use already by this user
		$sql = "SELECT title from m_notes where owner='".$owner."' AND  title='".$renameNoteTitle."' ";
		$result = mysql_query($sql);
		if(mysql_num_rows($result)>0) 
		{
			
		}
		else // do  rename note and do log it
		{
			// update m_notes
			$sql="UPDATE m_notes SET title='$renameNoteTitle', content='$renameNoteContent', save_count='$renameNoteCounter' WHERE id='$renameNoteID'"; 
			$result = mysql_query($sql);																
			
			// update m_log
			$renameNoteContentSummary = substr($renameNoteContent, 0, 10);
			$event = "rename";
			$details = "Note: <b>".$renameNoteTitle."</b> with content: <b>".$renameNoteContentSummary."...</b>";
			//$sql="INSERT INTO m_log (event, details, activity_date) VALUES ('$event', '$details', now() )";
			$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(), '$owner' )";
			$result = mysql_query($sql);

			mysql_close($con);		// close sql connection
		}		
	}
	else
	{
		echo "File is not designed for manual call.";
	}
?> 
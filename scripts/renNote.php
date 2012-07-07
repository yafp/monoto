<?php
	session_start();

	// check if the user-session is valid or not
	if($_SESSION['valid'] == 1)
	{
		// get data for rename note
		$renameNoteID = $_POST['renameNoteID'];
		$renameNoteTitle = $_POST['renameNoteTitle'];
		$renameNoteContent = $_POST['renameNoteContent'];
		// $renameNoteEditCount +1
		$renameNoteCounter = $_POST['renameNoteCounter'];
		$renameNoteCounter = $renameNoteCounter+1;

		include '../conf/config.php';

	    // connect to mysql
		$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);
		if (!$con)
		{
			die('Could not connect: ' . mysql_error());
		}

		mysql_select_db($mysql_db, $con);									// select db

		// update m_notes
		$sql="UPDATE m_notes SET title='$renameNoteTitle', content='$renameNoteContent', save_count='$renameNoteCounter' WHERE id='$renameNoteID'"; 
		$result = mysql_query($sql);																
		if (!$result) 
		{
	    	die('Error: ' . mysql_error());
		}
		else /* d.h. es gab keine Fehler beim insert - daher protokollieren wir auch*/
		{
			// update m_log
			$renameNoteContentSummary = substr($renameNoteContent, 0, 10);
			$event = "rename";
			$details = "Note: <b>".$renameNoteTitle."</b> with content: <b>".$renameNoteContentSummary."...</b>";
			$sql="INSERT INTO m_log (event, details, activity_date) VALUES ('$event', '$details', now() )";
			$result = mysql_query($sql);
		}
		mysql_close($con);													// close sql connection
	}
	else
	{
		echo "File is not designed for manual call.";
	}
?> 
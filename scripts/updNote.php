<?php
	session_start();

	// check if the user-session is valid or not
	if($_SESSION['valid'] == 1)
	{
		// get data for modified note
		$modifiedNoteID = $_POST['modifiedNoteID'];
		$modifiedNoteTitle = $_POST['modifiedNoteTitle'];
		$modifiedNoteContent = $_POST['modifiedNoteContent'];
		// $modifiedNoteEditCount +1
		$modifiedNoteCounter = $_POST['modifiedNoteCounter'];
		$modifiedNoteCounter = $modifiedNoteCounter+1;

		include '../conf/config.php';

	    // connect to mysql
		$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);
		if (!$con)
		{
			die('Could not connect: ' . mysql_error());
		}

		mysql_select_db($mysql_db, $con);									// select db

		// update m_notes
		$sql="UPDATE m_notes SET content='$modifiedNoteContent', save_count='$modifiedNoteCounter' WHERE id='$modifiedNoteID'"; 
		$result = mysql_query($sql);																
		if (!$result) 
		{
	    	die('Error: ' . mysql_error());
		}
		else /* d.h. es gab keine Fehler beim insert - daher protokollieren wir auch*/
		{
			// update m_log
			$modifiedNoteContentSummary = substr($modifiedNoteContent, 0, 10);
			$event = "save";
			$details = "Note: <b>".$modifiedNoteTitle."</b> with content: <b>".$modifiedNoteContentSummary."...</b>";
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
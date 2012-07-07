<?php
	session_start();

	// check if the user-session is valid or not
	if($_SESSION['valid'] == 1)
	{
		// get data for new note
		$newNoteTitle = $_POST['newNoteTitle'];
		$newNoteContent = $_POST['newNoteContent'];		

		include '../conf/config.php';

	    // connect to mysql
		$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);
		if (!$con)
		{
			die('Could not connect: ' . mysql_error());
		}
		mysql_select_db($mysql_db, $con);						// select db


		// update m_notes
		$sql="INSERT INTO m_notes (title, content, date_create, date_mod) VALUES ('$newNoteTitle', '$newNoteContent', now(), now() )";
		$result = mysql_query($sql);
		if (!$result) 
		{
	    	die('Error: ' . mysql_error());
		}
		else /* d.h. es gab keine Fehler beim insert - daher protokollieren wir auch*/
		{
			// update m_notes
			$newNoteContentSummary = substr($newNoteContent, 0, 10);
			$event = "create";
			$details = "Note: <b>".$newNoteTitle."</b> with content: <b>".$newNoteContentSummary."...</b>";
			$sql="INSERT INTO m_log (event, details, activity_date) VALUES ('$event','$details', now() )";
			$result = mysql_query($sql);
		}

		mysql_close($con);									// close sql connection
	}
	else
	{
		echo "File is not designed for manual call.";
	}
	
?> 
<?php
	session_start();

	if($_SESSION['valid'] == 1)	// check if the user-session is valid or not
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

		$owner = $_SESSION['username'];

		// check if the new title is in use already by this user
		$sql = "SELECT title from m_notes where owner='".$owner."' AND  title='".$newNoteTitle."' ";
		$result = mysql_query($sql);
		if(mysql_num_rows($result)>0) 
		{
			
		}
		else // do create note and do log it
		{
			// insert into m_notes
			$sql="INSERT INTO m_notes (title, content, date_create, date_mod, owner) VALUES ('$newNoteTitle', '$newNoteContent', now(), now(), '$owner' )";
			//$sql="INSERT INTO m_notes (title, content, date_create, date_mod, owner) VALUES ('$newNoteTitle', '$newNoteContent', now(), now(), 'monoto' )";
			$result = mysql_query($sql);
			if (!$result) 
			{
		    	die('Error: ' . mysql_error());
			}
			else
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
	}
	else
	{
		echo "File is not designed for manual call.";
	}
?> 
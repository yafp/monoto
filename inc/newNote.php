<?php
	session_start();
	if($_SESSION['valid'] == 1)	// check if the user-session is valid or not
	{
		// get data for new note
		$newNoteTitle = $_POST['newNoteTitle'];
		$newNoteContent = $_POST['newNoteContent'];		

		include '../conf/config.php';
 
		$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);		// connect to mysql
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
			// cant create - as this title is already in use - update m_log with error entry
			$newNoteContentSummary = substr($newNoteContent, 0, 10);
			$event = "create error";
			$details = "Note: <b>".$newNoteTitle."</b> with content: <b>".$newNoteContentSummary."...</b> failed to create as there was already a note with this title.</b>";
			$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event','$details', now(), '$owner' )";
			$result = mysql_query($sql);

		}
		else // do create note and do log it
		{
			// insert into m_notes
			$sql="INSERT INTO m_notes (title, content, date_create, date_mod, owner, save_count) VALUES ('$newNoteTitle', '$newNoteContent', now(), now(), '$owner', '1' )";
			$result = mysql_query($sql);
			if (!$result) 
			{
		    	die('Error: ' . mysql_error());
		    	// display error output
			}
			else // update m_log
			{
				$event = "create";
				$details = "Note: <b>".$newNoteTitle."</b>";
				$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event','$details', now(), '$owner' )";
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

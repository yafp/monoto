<?php
	session_start();

	if($_SESSION['valid'] == 1)	// check if the user-session is valid or not
	{
		// get data for modified note
		$modifiedNoteID = $_POST['modifiedNoteID'];
		$modifiedNoteTitle = $_POST['modifiedNoteTitle'];
		$modifiedNoteContent = $_POST['modifiedNoteContent'];
		$modifiedNoteCounter = $_POST['modifiedNoteCounter'];
		$modifiedNoteCounter = $modifiedNoteCounter+1;

		include '../conf/config.php';

		$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);	// connect to mysql
		if (!$con)
		{
			die('Could not connect: ' . mysql_error());
		}
		mysql_select_db($mysql_db, $con);									// select db
		$owner = $_SESSION['username'];
		
		$sql="UPDATE m_notes SET content='$modifiedNoteContent', save_count='$modifiedNoteCounter' WHERE id='$modifiedNoteID'"; 		// update m_notes
		$result = mysql_query($sql);																
		if (!$result) 
		{
	    	die('Error: ' . mysql_error());
		}
		else
		{
			// update m_log
			$modifiedNoteContentSummary = substr($modifiedNoteContent, 0, 10);
			$event = "save";
			$details = "Note: <b>".$modifiedNoteTitle."</b> with content: <b>".$modifiedNoteContentSummary."...</b>";
			$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now() , '$owner')";
			$result = mysql_query($sql);
		}
		mysql_close($con);													// close sql connection
	}
	else
	{
		echo "File is not designed for manual call.";
	}
?> 
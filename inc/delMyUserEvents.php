<?php
/*
About:			This file acts as script in monoto to delete all events in the event log of a single user.
*/

	session_start();
	
	// check if the user-session is valid or not
	if($_SESSION['valid'] == 1)
	{
		include '../conf/config.php';

	    // connect to mysql	 
		$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);		
		if (!$con)
		{
			die('Could not connect: ' . mysql_error());
		}

		mysql_select_db($mysql_db, $con);				// select db
		
		$owner = $_SESSION['username'];
		
		
		// update m_notes = delete events
		$sql="DELETE FROM m_log WHERE owner='".$owner."' ";
		$result = mysql_query($sql);

		// update m_log
		$event = "events eraser";
		$details = "All user events deleted with eraser.";
		$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(), '$owner' )";
		$result = mysql_query($sql);
		
		mysql_close($con); 								// close sql connection
	}
	else
	{	
		echo "File is not designed for manual call.";
	}
?> 

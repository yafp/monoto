<?php
	session_start();

	// check if the user-session is valid or not
	if($_SESSION['valid'] == 1)
	{
		$deleteID = $_POST['deleteID'];
		$deleteTitle = $_POST['deleteTitle'];
		$deleteContent = $_POST['deleteContent'];

		include '../conf/config.php';

	    // connect to mysql	   
		$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);		
		if (!$con)
		{
			die('Could not connect: ' . mysql_error());
		}

		mysql_select_db($mysql_db, $con);				// select db
		
		// update m_notes
		$sql="DELETE FROM m_notes WHERE id='$deleteID'";
		$result = mysql_query($sql);
		if (!$result) 
		{
	    	die('Error: ' . mysql_error());
		}
		else  // update m_log
		{
			$event = "delete";
			$details = "Note: <b>".$deleteTitle."</b> with content: <b>: ".$deleteContent."...</b>. ID: <b>".$deleteID." </b>is now gone.";
			$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(), '$owner' )";
			$result = mysql_query($sql);
		}
		mysql_close($con); 								// close sql connection
	}
	else
	{	
		echo "File is not designed for manual call.";
	}
?> 

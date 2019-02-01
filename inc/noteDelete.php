<?php
// Name:		noteDelete.php
// Function:	This file acts as script in monoto to delete single/selected notes.
//
session_start();

// check if the user-session is valid or not
if($_SESSION['valid'] == 1)
{
	require '../conf/config.php';

	// get post data
	$deleteID = $_POST['deleteID'];
	$deleteTitle = $_POST['deleteTitle'];
	$deleteContent = $_POST['deleteContent'];

	// get session data
	$owner = $_SESSION['username'];

	// connect to mysql
	$con = mysqli_connect($mysql_server, $mysql_user, $mysql_pw, $mysql_db); // connect to mysql
	if (!$con)
	{
		die('Could not connect: ' . mysqli_connect_error());
	}

	// select db
	mysqli_select_db($mysql_db, $con);

	// update m_notes
	$sql="DELETE FROM m_notes WHERE id='$deleteID'";
	$result = mysqli_query($con, $sql);
	if (!$result)
	{
		die('Error: ' . mysqli_connect_error());
	}
	else  // update m_log
	{
		$event = "delete";
		$details = "Note: <b>".$deleteTitle."</b>. ID: <b>".$deleteID." </b>is now gone.";
		$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(), '$owner' )";
		$result = mysqli_query($sql);
	}

	// close sql connection
	mysqli_close($con);
}
?>

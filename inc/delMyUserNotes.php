<?php
// Name:		delMyUserNotes.php
// Function:	This file acts as script in monoto to delete all notes of a single user.
//
session_start();

// check if the user-session is valid or not
if($_SESSION['valid'] == 1)
{
	require '../conf/config.php';

	// connect to mysql
	$con = mysqli_connect($mysql_server, $mysql_user, $mysql_pw, $mysql_db);
	if (!$con)
	{
		die('Could not connect: ' . mysqli_connect_error());
	}

	// select db
	mysqli_select_db($con, $mysql_db);

	$owner = $_SESSION['username'];

	$sql="DELETE FROM m_notes WHERE owner='$owner'"; // update m_notes = delete notes
	$result = mysqli_query($con, $sql);

	// update m_log
	$event = "notes eraser";
	$details = "All user notes deleted with eraser.";
	$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(), '$owner' )";
	$result = mysqli_query($con, $sql);

	// close sql connection
	mysqli_close($con);
}
?>

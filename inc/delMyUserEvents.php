<?php
// -----------------------------------------------------------------------------
// Name:		delMyUserEvents.php
// Function:	This file acts as script in monoto to delete all events in the event log of a single user.
// -----------------------------------------------------------------------------
header('Content-type: text/xml');

session_start();

// check if the user-session is valid or not
if ( $_SESSION['valid'] == 1 )
{
	require '../config/config.php';

	// connect to mysql
    $con = new mysqli($databaseServer, $databaseUser, $databasePW, $databaseDB);
	if (!$con)
	{
		die('Could not connect: ' . mysqli_connect_error());
	}

	$owner = $_SESSION['username'];

	// update m_notes = delete events
	$sql="DELETE FROM m_log WHERE owner='".$owner."' ";
	$result = mysqli_query($con, $sql);

	// update m_log
	$event = "events eraser";
	$details = "All user events deleted with eraser.";
	$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(), '$owner' )";
	$result = mysqli_query($con, $sql);

	// close sql connection
	mysqli_close($con);
}
?>

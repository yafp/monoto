<?php
// -----------------------------------------------------------------------------
// Name:		noteDelete.php
// Function:	This file acts as script in monoto to delete single/selected notes.
// -----------------------------------------------------------------------------
header('Content-type: text/xml');

session_start();

// check if the user-session is valid or not
if ( $_SESSION['valid'] == 1 )
{
	require '../config/config.php';

	// get post data
    $deleteID= filter_input(INPUT_POST, "deleteID", FILTER_SANITIZE_STRING);
    $deleteTitle= filter_input(INPUT_POST, "deleteTitle", FILTER_SANITIZE_STRING);
    $deleteContent= filter_input(INPUT_POST, "deleteContent", FILTER_SANITIZE_STRING);

	// get session data
	$owner = $_SESSION['username'];

	// connect to mysql
    $con = new mysqli($databaseServer, $databaseUser, $databasePW, $databaseDB);
	if ( !$con )
	{
		die('Could not connect: ' . mysqli_connect_error());
	}

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

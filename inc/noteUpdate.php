<?php
// -----------------------------------------------------------------------------
// Name:		noteUpdate.php
// Function:	used to update an existing note from n.php
// -----------------------------------------------------------------------------
header('Content-type: text/xml');

session_start();

if($_SESSION['valid'] == 1)	// check if the user-session is valid or not
{
	// get data for modified note
	//$modifiedNoteID = $_POST['modifiedNoteID'];
    $modifiedNoteID= filter_input(INPUT_POST, "modifiedNoteID", FILTER_SANITIZE_STRING);

	//$modifiedNoteTitle = $_POST['modifiedNoteTitle'];
    $modifiedNoteTitle= filter_input(INPUT_POST, "modifiedNoteTitle", FILTER_SANITIZE_STRING);

	//$modifiedNoteContent = $_POST['modifiedNoteContent'];
    $modifiedNoteContent= filter_input(INPUT_POST, "modifiedNoteContent", FILTER_SANITIZE_STRING);

	//$modifiedNoteCounter = $_POST['modifiedNoteCounter'];
    $modifiedNoteCounter= filter_input(INPUT_POST, "modifiedNoteCounter", FILTER_SANITIZE_STRING);

	$modifiedNoteCounter = $modifiedNoteCounter+1;

	// Fix for issue: #191 - eating backslashes
	$modifiedNoteContent = str_replace('\\', '\\\\', $modifiedNoteContent);

	require '../config/config.php';

    $con = new mysqli($database_server, $database_user, $database_pw, $database_db);
	if (!$con)
	{
		die('Could not connect: ' . mysqli_connect_error());
	}

	$owner = $_SESSION['username'];

	// check if the new title is in use already by this user
	$sql = "SELECT title from m_notes where owner='".$owner."' AND  title='".$modifiedNoteTitle."' and id != ".$modifiedNoteID."' ";
	$result = mysqli_query($con, $sql);
	if(mysqli_num_rows($result)>0)
	{
		$current_timestamp = date('Ymd-his');
		$modifiedNoteTitle = $modifiedNoteTitle."___".$current_timestamp;
	}

	// update the actual note
	//
	$sql="UPDATE m_notes SET title='$modifiedNoteTitle', content='$modifiedNoteContent', save_count='$modifiedNoteCounter' WHERE id='$modifiedNoteID'"; // update m_notes
	$result = mysqli_query($con, $sql);
	if (!$result)
	{
		die('Error: ' . mysqli_connect_error());
	}
	else
	{
		// update m_log
		$event = "save";
		$details = "Note: <b>".$modifiedNoteTitle."</b>";
		$sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now() , '$owner')";
		$result = mysqli_query($con, $sql);
	}
	mysqli_close($con); // close sql connection
}
?>

<?php
// -----------------------------------------------------------------------------
// Name:		noteNew.php
// Function:	used for new note creation from n.php
// -----------------------------------------------------------------------------
header('Content-type: text/xml');

session_start();
if($_SESSION['valid'] == 1)	// check if the user-session is valid or not
{
    require '../config/config.php';

    // note title
    $newNoteTitle= filter_input(INPUT_POST, "newNoteTitle", FILTER_SANITIZE_STRING);

    // note content
    $newNoteContent = $_POST['newNoteContent'];
    //$newNoteContent= filter_input(INPUT_POST, "newNoteContent", FILTER_SANITIZE_STRING); // filter breaks html code

    // Fix for issue: #191 - eating backslashes
    $newNoteContent = str_replace('\\', '\\\\', $newNoteContent);

    $con = new mysqli($database_server, $database_user, $database_pw, $database_db);
    if (!$con)
    {
        die('Could not connect: ' . mysqli_connect_error());
    }

    $owner = $_SESSION['username'];

    // check if the new title is in use already by this user
    $sql = "SELECT title from m_notes where owner='".$owner."' AND  title='".$newNoteTitle."' ";
    $result = mysqli_query($con, $sql);
    if(mysqli_num_rows($result)>0)
    {
        // adjust Title - as it is already in use
        $current_timestamp = date('Ymd-his');
        $newNoteTitle = $newNoteTitle."___".$current_timestamp;
    }

    // do create note and do log it
    //
    // insert into m_notes
    $sql="INSERT INTO m_notes (title, content, date_create, date_mod, owner, save_count) VALUES ('$newNoteTitle', '$newNoteContent', now(), now(), '$owner', '1' )";
    $result = mysqli_query($con, $sql);
    if (!$result)
    {
        die('Error: ' . mysqli_connect_error()); // display error output
    }
    else // update m_log
    {
        $event = "create";
        $details = "Note: <b>".$newNoteTitle."</b>";
        $sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event','$details', now(), '$owner' )";
        $result = mysqli_query($con, $sql);

        return(true);
    }
    mysqli_close($con);	// close sql connection
}
?>

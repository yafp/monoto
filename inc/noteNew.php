<?php
// Name:		noteNew.php
// Function:	used for new note creation from n.php
//
session_start();
if($_SESSION['valid'] == 1)	// check if the user-session is valid or not
{
    // get data for new note
    $newNoteTitle = $_POST['newNoteTitle'];
    $newNoteContent = $_POST['newNoteContent'];

    // Fix for issue: #191 - eating backslashes
    $newNoteContent = str_replace('\\', '\\\\', $newNoteContent);

    require '../conf/config.php';

    //$con = mysqli_connect($mysql_server, $mysql_user, $mysql_pw, $mysql_db); // connect to mysql
    $con = new mysqli($mysql_server, $mysql_user, $mysql_pw, $mysql_db);
    if (!$con)
    {
        die('Could not connect: ' . mysqli_connect_error());
    }
    //mysqli_select_db($con, $mysql_db); // select db

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
    }
    mysqli_close($con);	// close sql connection
}
?>

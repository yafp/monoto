<?php
// -----------------------------------------------------------------------------
// noteNew.php
// used for new note creation from n.php
// -----------------------------------------------------------------------------

// prevent direct call of this script
//if (strpos($_SERVER['SCRIPT_FILENAME'], 'noteNew.php') !== false)
//if (strpos(filter_var($_SERVER['SCRIPT_FILENAME'], FILTER_SANITIZE_STRING), 'noteNew.php') !== false)
//{
//    header('Location: ../index.php'); // back to login page
//    die();
//}


//header('Content-type: text/xml');
header('Content-type: application/xml');

session_start();
if ( $_SESSION[ 'monoto' ][ 'valid' ] == 1 ) // check if the user-session is valid or not
{
    require '../config/config.php';

    // note title
    $newNoteTitle= filter_input(INPUT_POST, "newNoteTitle", FILTER_SANITIZE_STRING);

    // note content
    $newNoteContent = $_POST[ 'newNoteContent' ]; // dont filter content

    // Fix for issue: #191 - eating backslashes
    $newNoteContent = str_replace('\\', '\\\\', $newNoteContent);

    $con = new mysqli($databaseServer, $databaseUser, $databasePW, $databaseDB);
    if (!$con)
    {
        die('Could not connect: ' . mysqli_connect_error());
    }

    $owner = $_SESSION[ 'monoto' ][ 'username' ];

    // check if the new title is in use already by this user
    $sql = "SELECT title from m_notes where owner='".$owner."' AND  title='".$newNoteTitle."' ";
    $result = mysqli_query($con, $sql);
    if ( mysqli_num_rows ( $result ) > 0 )
    {
        // adjust Title - as it is already in use
        $current_timestamp = date('Ymd-his');
        $newNoteTitle = $newNoteTitle."___".$current_timestamp;
    }

    // do create note and do log it
    //
    // insert into m_notes
    $sql = "INSERT INTO m_notes (title, content, date_create, date_mod, owner, save_count) VALUES ('$newNoteTitle', '$newNoteContent', now(), now(), '$owner', 1 )";
    $result = mysqli_query ( $con, $sql );
    if (!$result)
    {
        die('Error: ' . mysqli_connect_error()); // display error output
    }
    else // update m_log
    {
        $event = "create";
        $details = "Note: <b>".$newNoteTitle."</b>";
        $sql = "INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event','$details', now(), '$owner' )";
        $result = mysqli_query($con, $sql);

        //return ( true );
    }
    mysqli_close( $con ); // close sql connection
}
?>

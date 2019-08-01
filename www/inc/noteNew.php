<?php
// -----------------------------------------------------------------------------
// noteNew.php
// used for new note creation from notes.php
// -----------------------------------------------------------------------------

// prevent direct call of this script
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) )
{
    include "helperFunctions.php";
    handleBadAccessToIncScripts();
}


//header('Content-type: text/xml');
header('Content-type: application/xml');

session_start();
if ( $_SESSION[ 'monoto' ][ 'valid' ] == 1 ) // check if the user-session is valid or not
{
    require '../config/databaseConfig.php';
    require 'helperFunctions.php'; // to access writeNewLogentry

    // note title
    $newNoteTitle = filter_input(INPUT_POST, "newNoteTitle", FILTER_SANITIZE_STRING);

    // note content
    $newNoteContent = $_POST[ 'newNoteContent' ]; // dont filter content

    // Fix for issue: #191 - eating backslashes
    $newNoteContent = str_replace('\\', '\\\\', $newNoteContent);

    $con = new mysqli($databaseServer, $databaseUser, $databasePW, $databaseDB);
    if ( !$con )
    {
        die ('Could not connect: ' . mysqli_connect_error() );
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
        writeNewLogEntry("Note creation", "Created new note: <b>".$newNoteTitle."</b>.");
    }
    mysqli_close( $con ); // close sql connection
}
?>

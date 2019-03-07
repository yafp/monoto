<?php
// -----------------------------------------------------------------------------
// profileDeleteMyUserNotes.php
// This file acts as script in monoto to delete all notes of a single user.
// -----------------------------------------------------------------------------

// prevent direct call of this script
if (strpos($_SERVER['SCRIPT_FILENAME'], 'profileDeleteMyUserNotes.php') !== false)
{
    header('Location: ../index.php'); // back to login page
    die();
}

header('Content-type: text/xml');

session_start();

// check if the user-session is valid or not
if ( $_SESSION[ 'monoto' ][ 'valid' ] == 1 )
{
    require '../config/config.php';

    // connect to mysql
    $con = new mysqli ( $databaseServer, $databaseUser, $databasePW, $databaseDB );
    if ( !$con )
    {
        die('Could not connect: ' . mysqli_connect_error());
    }

    $owner = $_SESSION[ 'monoto' ][ 'username' ];

    $sql = "DELETE FROM m_notes WHERE owner='$owner'"; // update m_notes = delete notes
    $result = mysqli_query ( $con, $sql );

    // update m_log
    $event = "notes eraser";
    $details = "All user notes deleted with eraser.";
    $sql = "INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(), '$owner' )";
    $result = mysqli_query ( $con, $sql );

    // close sql connection
    mysqli_close( $con );
}
?>

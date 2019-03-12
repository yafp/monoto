<?php
// -----------------------------------------------------------------------------
// profileDeleteMyUserEvents.php
// This file acts as script in monoto to delete all events in the event log of a single user.
// -----------------------------------------------------------------------------

// prevent direct call of this script
//if (strpos($_SERVER['SCRIPT_FILENAME'], 'profileDeleteMyUserEvents.php') !== false)
/*
if (strpos(filter_var($_SERVER['SCRIPT_FILENAME'], FILTER_SANITIZE_STRING), 'profileDeleteMyUserEvents.php') !== false)
{
    header('Location: ../index.php'); // back to login page
    die();
}
*/

header('Content-type: text/xml');

session_start();

// check if the user-session is valid or not
if ( $_SESSION[ 'monoto' ][ 'valid' ] == 1 )
{
    require "../config/config.php";

    // connect to mysql
    $con = new mysqli ( $databaseServer, $databaseUser, $databasePW, $databaseDB );
    if ( !$con )
    {
        die('Could not connect: ' . mysqli_connect_error());
    }

    $owner = $_SESSION[ 'monoto' ][ 'username' ];

    // delete events in m_log
    $sql = "DELETE FROM m_log WHERE owner='".$owner."' ";
    $result = mysqli_query( $con, $sql );

    // update m_log
    writeNewLogEntry("events eraser", "All user events deleted with eraser.");

    // close sql connection
    mysqli_close( $con );
}
?>

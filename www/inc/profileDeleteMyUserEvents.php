<?php
// -----------------------------------------------------------------------------
// profileDeleteMyUserEvents.php
// This file acts as script in monoto to delete all events in the event log of a single user.
// -----------------------------------------------------------------------------

// prevent direct call of this script
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) )
{
    // Up to you which header to send, some prefer 404 even if
    // the files does exist for security
    header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );

    // choose the appropriate page to redirect users
    die( header( 'location: ../404.php' ) );
}

header('Content-type: text/xml');

session_start();

// check if the user-session is valid or not
if ( $_SESSION[ 'monoto' ][ 'valid' ] == 1 )
{
    require "../config/config.php";
    require 'helperFunctions.php'; // to access writeNewLogentry

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
    writeNewLogEntry("Eraser user events", "All user events deleted with eraser.");

    // close sql connection
    mysqli_close( $con );
}
?>

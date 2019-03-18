<?php
// -----------------------------------------------------------------------------
// profileDeleteMyUserNotes.php
// This file acts as script in monoto to delete all notes of a single user.
// -----------------------------------------------------------------------------

// prevent direct call of this script
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) )
{
    include "helperFunctions.php";
    handleBadAccessToIncScripts();
}

header('Content-type: text/xml');

session_start();

// check if the user-session is valid or not
if ( $_SESSION[ 'monoto' ][ 'valid' ] == 1 )
{
    require '../config/config.php';
    require 'helperFunctions.php'; // to access writeNewLogentry

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
    writeNewLogEntry("Eraser user notes", "All user notes deleted with eraser.");

    // close sql connection
    mysqli_close( $con );
}
?>

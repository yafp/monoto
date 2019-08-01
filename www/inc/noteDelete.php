<?php
// -----------------------------------------------------------------------------
// noteDelete.php
// This file acts as script in monoto to delete single/selected notes.
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
    require '../config/databaseConfig.php';
    require 'helperFunctions.php'; // to access writeNewLogentry

    // get post data
    $deleteID = filter_input ( INPUT_POST, "deleteID", FILTER_SANITIZE_STRING );
    $deleteTitle = filter_input ( INPUT_POST, "deleteTitle", FILTER_SANITIZE_STRING );

    // get session data
    $owner = $_SESSION[ 'monoto' ][ 'username' ];

    // connect to mysql
    $con = new mysqli ( $databaseServer, $databaseUser, $databasePW, $databaseDB );
    if ( !$con )
    {
        die('Could not connect: ' . mysqli_connect_error());
    }

    // update m_notes
    $sql = "DELETE FROM m_notes WHERE id='$deleteID'";
    $result = mysqli_query( $con, $sql );
    if ( !$result )
    {
        die('Error: ' . mysqli_connect_error());
    }
    else // update m_log
    {
        writeNewLogEntry("Note delete", "Deleted note <b>".$deleteID."</b> with title <b>".$deleteTitle."</b>.");
    }

    // close sql connection
    mysqli_close( $con );
}
?>

<?php
// -----------------------------------------------------------------------------
// noteDelete.php
// This file acts as script in monoto to delete single/selected notes.
// -----------------------------------------------------------------------------

// prevent direct call of this script
//if (strpos($_SERVER['SCRIPT_FILENAME'], 'noteDelete.php') !== false)
/*
if (strpos(filter_var($_SERVER['SCRIPT_FILENAME'], FILTER_SANITIZE_STRING), 'noteDelete.php') !== false)
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
    require '../config/config.php';
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
        writeNewLogEntry("delete", "Note: <b>".$deleteTitle."</b>. (ID: ".$deleteID.") deleted.");
    }

    // close sql connection
    mysqli_close( $con );
}
?>

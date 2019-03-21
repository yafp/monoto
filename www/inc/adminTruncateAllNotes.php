<?php
// -----------------------------------------------------------------------------
// adminTruncateAllNotes.php
// This file acts as script in monoto to truncate the mnotes table.
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
    require "../config/databaseConfig.php";

    // connect to mysql
    $con = new mysqli ( $databaseServer, $databaseUser, $databasePW, $databaseDB );
    if ( !$con )
    {
        die('Could not connect: ' . mysqli_connect_error());
    }

    // truncate log-/events-table
    mysqli_query($con, 'TRUNCATE TABLE m_notes');

    // close sql connection
    mysqli_close( $con );
}
?>

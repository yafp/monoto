<?php
// -----------------------------------------------------------------------------
// adminFillUserDeleteSelector.php
// This file acts as script in monoto to fill the user-delete <select> element.
// -----------------------------------------------------------------------------

header('Content-type: application/json');

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

    $myLockedUsers = []; // array
    $counterX = 0;
    $counterY = 0;

    // fetch all non-admin users (id and username)
    $result = mysqli_query($con, "SELECT id, username  FROM m_users WHERE failed_logins_in_a_row > 2 ORDER by id");
    while ( $row = mysqli_fetch_array ( $result ) )
    {
        $counterY = 0;
        $myLockedUsers[$counterX][$counterY] = $row[0];
        $counterY = $counterY+1;
        $myLockedUsers[$counterX][$counterY] = $row[1];
        $counterX = $counterX +1;
    }

    // return the user informations
    echo json_encode($myLockedUsers);

    // close sql connection
    mysqli_close( $con );
}
?>

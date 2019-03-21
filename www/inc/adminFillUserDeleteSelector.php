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
    require "../config/databaseConfig.php";
    require 'helperFunctions.php'; // to access writeNewLogentry

    // connect to mysql
    $con = new mysqli ( $databaseServer, $databaseUser, $databasePW, $databaseDB );
    if ( !$con )
    {
        die('Could not connect: ' . mysqli_connect_error());
    }

    $myUsers = []; // array
    $counterX = 0;
    $counterY = 0;

    // fetch all non-admin users (id and username)
    $result = mysqli_query($con, "SELECT id, username  FROM m_users WHERE is_admin is NULL ORDER by id ");
    while ( $row = mysqli_fetch_array ( $result ) )
    {
        $counterY = 0;
        $myUsers[$counterX][$counterY] = $row[0];
        $counterY = $counterY+1;
        $myUsers[$counterX][$counterY] = $row[1];
        $counterX = $counterX +1;
    }

    // return the user informations
    echo json_encode($myUsers);

    // close sql connection
    mysqli_close( $con );
}
?>

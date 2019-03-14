<?php
// -----------------------------------------------------------------------------
// adminFillUserDeleteSelector.php
// This file acts as script in monoto to truncate the m_log table (events).
// -----------------------------------------------------------------------------


// TODO:
// not yet in use


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

    $myUsers = array();

    // fetch all non-admin users (id and username)
    $result = mysqli_query($con, "SELECT id, username  FROM m_users WHERE is_admin is NULL ORDER by id ");
    while ( $row = mysqli_fetch_array ( $result ) ) // fill user-select box
    {
        //echo '<option value="'.$row[ 0 ].'">'.$row[ 1 ].'</option>';
        array_push($myUsers, $row[ 0 ]);
    }

    echo($myUsers);

    // close sql connection
    mysqli_close( $con );
}
?>

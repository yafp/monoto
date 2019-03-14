<?php

// -----------------------------------------------------------------------------
// checkSession.php
// Checks for valid sesstion and redirects back to login page if needed
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


// Start a session if it isnt already started
//
if ( session_status() == PHP_SESSION_NONE )
{
    session_start();
}

// Check if session is valid, otherwise back to login
if ( $_SESSION[ 'monoto' ][ 'valid' ] != 1 )
{
    header('Location: index.php'); // back to login page
    die();
}


?>

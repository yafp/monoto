<?php

// -----------------------------------------------------------------------------
// checkSession.php
// Checks for valid sesstion and redirects back to login page if needed
// -----------------------------------------------------------------------------

// prevent direct call of this script
if (strpos($_SERVER['SCRIPT_FILENAME'], 'checkSession.php') !== false)
{
    header('Location: ../index.php'); // back to login page
    die();
}

// Start a session if it isnt already started
//
if ( session_status() == PHP_SESSION_NONE)
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

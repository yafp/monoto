<?php
// -----------------------------------------------------------------------------
// adminUserUnlock.php
// used for account unlocking from admin.php
// -----------------------------------------------------------------------------

// prevent direct call of this script
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) )
{
    include "helperFunctions.php";
    handleBadAccessToIncScripts();
}


header('Content-type: application/xml');

session_start();
if ( $_SESSION[ 'monoto' ][ 'valid' ] == 1 ) // check if the user-session is valid or not
{
    require '../config/config.php';

    // post values
    $existingUserID = filter_input(INPUT_POST, "existingUserID", FILTER_SANITIZE_STRING);

    $con = new mysqli($databaseServer, $databaseUser, $databasePW, $databaseDB);
    if (!$con)
    {
        die('Could not connect: ' . mysqli_connect_error());
    }

    // reset login-lock
    //
    $sql = "UPDATE m_users SET failed_logins_in_a_row = 0 WHERE id='$existingUserID'";
    $result = mysqli_query( $con, $sql );

    // Close sql connection
    //
    mysqli_close( $con ); // close sql connection
}
?>

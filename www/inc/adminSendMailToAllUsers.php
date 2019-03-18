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
    $mailSubject = filter_input(INPUT_POST, "mailSubject", FILTER_SANITIZE_STRING);
    $mailText = filter_input(INPUT_POST, "mailText", FILTER_SANITIZE_STRING);

    $con = new mysqli($databaseServer, $databaseUser, $databasePW, $databaseDB);
    if (!$con)
    {
        die('Could not connect: ' . mysqli_connect_error());
    }

    // select all users & email-data
    $query = "SELECT username, email FROM m_users;";
    $result = mysqli_query ( $con, $query );
    while ( $row = mysqli_fetch_array ( $result ) )
    {
        $username = $row[ 0 ];
        $email = $row[ 1 ];
        if ( @mail ( $email, $messageSubject, $messageText ) ) // try to send notification email
        {
            // sending
        }
        else
        {
            // unable to send
        }
    }

    // Close sql connection
    mysqli_close( $con );
}
?>

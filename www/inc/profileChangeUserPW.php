<?php
// -----------------------------------------------------------------------------
// profileChangeUserPW.php
// used for password changes from profile.php
// -----------------------------------------------------------------------------

// prevent direct call of this script
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) )
{
    include "helperFunctions.php";
    handleBadAccessToIncScripts();
}


//header('Content-type: text/xml');
header('Content-type: application/xml');

session_start();
if ( $_SESSION[ 'monoto' ][ 'valid' ] == 1 ) // check if the user-session is valid or not
{
    require '../config/config.php';
    require 'helperFunctions.php'; // to access writeNewLogentry

    $newPassword = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
    $username = $_SESSION[ 'monoto' ][ 'username' ];

    $hash = hash('sha256', $newPassword); // playing with hash
    function createSalt() // playing with salt - creates a 3 character sequence
    {
        $string = md5(uniqid(rand(), true));
        return substr($string, 0, 3);
    }
    $salt = createSalt();
    $hash = hash('sha256', $salt . $hash);

    $con = new mysqli($databaseServer, $databaseUser, $databasePW, $databaseDB);
    if (!$con)
    {
        die('Could not connect: ' . mysqli_connect_error());
    }

    // update user password
    $query = "UPDATE m_users SET  password='$hash', salt='$salt' WHERE username='$username'";
    mysqli_query ( $con, $query );

    // update m_log
    writeNewLogEntry("Password update", "Changed user password.");

    // close sql connection
    mysqli_close( $con );
}
?>

<?php
// -----------------------------------------------------------------------------
// profileChangeUserLanguage.php
// used for profil language changes from profile.php
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


//header('Content-type: text/xml');
header('Content-type: application/xml');

session_start();
if ( $_SESSION[ 'monoto' ][ 'valid' ] == 1 ) // check if the user-session is valid or not
{
    require '../config/config.php';

    $newLanguage = filter_input(INPUT_POST, "language", FILTER_SANITIZE_STRING);
    $username = $_SESSION[ 'monoto' ][ 'username' ];

    $con = new mysqli($databaseServer, $databaseUser, $databasePW, $databaseDB);
    if (!$con)
    {
        die('Could not connect: ' . mysqli_connect_error());
    }

    // update users language setting
    $query = "UPDATE m_users SET language='$newLanguage' WHERE username='$username'";
    mysqli_query($con, $query);

     // update session variable
    $_SESSION[ 'monoto' ][ 'lang' ] = $newLanguage;

    // close sql connection
    mysqli_close( $con );
}
?>

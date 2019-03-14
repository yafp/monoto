<?php
// -----------------------------------------------------------------------------
// adminUserDelete.php
// used for account deletion from admin.php
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

    // get username to this user id
    //
    $query = "SELECT username FROM m_users WHERE id = '$existingUserID';";
    $result = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($result))
    {
        $usernameToDelete = $row[ 0 ];
    }

    // Delete user account
    //
    $sql = "DELETE FROM m_users WHERE id='$existingUserID'";
    $result = mysqli_query( $con, $sql );
    if ( !$result )
    {
        die('Error: ' . mysqli_connect_error());
    }
    else
    {
        // update m_log
        writeNewLogEntry("Eraser user account", "Deleted account: <b>".$userID." </b>.");

        // delete his notes as well
        $sql = "DELETE FROM m_notes WHERE owner='$usernameToDelete'";
        $result = mysqli_query( $con, $sql );

        // delete his log as well
        $sql = "DELETE FROM m_log WHERE owner='$usernameToDelete'";
        $result = mysqli_query( $con, $sql );
    }

    // Close sql connection
    mysqli_close( $con );
}
?>

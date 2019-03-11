<?php
// -----------------------------------------------------------------------------
// adminUserDelete.php
// used for account deletion from a.php
// -----------------------------------------------------------------------------

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
    else  // update m_log
    {
        $event = "User delete";
        $details = "User: <b>".$userID." </b>is now gone.";
        $sql = "INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(), '$owner' )";
        $result = mysqli_query( $con, $sql );

        // delete his notes as well
        $sql = "DELETE FROM m_notes WHERE owner='$usernameToDelete'";
        $result = mysqli_query( $con, $sql );

        // delete his log as well
        $sql = "DELETE FROM m_log WHERE owner='$usernameToDelete'";
        $result = mysqli_query( $con, $sql );
    }

    // Close sql connection
    //
    mysqli_close( $con ); // close sql connection
}
?>

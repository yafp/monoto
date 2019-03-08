<?php
// -----------------------------------------------------------------------------
// profileChangeUserPW.php
// used for password changes from p.php
// -----------------------------------------------------------------------------

// prevent direct call of this script
//if (strpos($_SERVER['SCRIPT_FILENAME'], 'noteNew.php') !== false)
//if (strpos(filter_var($_SERVER['SCRIPT_FILENAME'], FILTER_SANITIZE_STRING), 'noteNew.php') !== false)
//{
//    header('Location: ../index.php'); // back to login page
//    die();
//}


//header('Content-type: text/xml');
header('Content-type: application/xml');

session_start();
if ( $_SESSION[ 'monoto' ][ 'valid' ] == 1 ) // check if the user-session is valid or not
{
    require '../config/config.php';

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


    // TODO
    // update m_log
    /*
    {
        $event = "create";
        $details = "Note: <b>".$newNoteTitle."</b>";
        $sql = "INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event','$details', now(), '$owner' )";
        $result = mysqli_query($con, $sql);

        //return ( true );
    }
    */
    mysqli_close( $con ); // close sql connection
}
?>

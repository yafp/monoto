<?php
// -----------------------------------------------------------------------------
// adminUserNew.php
// used for new account creation from admin.php
// in additional an example note gets created for the new account
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
    require '../config/databaseConfig.php';

    // post values
    $newUsername = filter_input(INPUT_POST, "newUsername", FILTER_SANITIZE_STRING);
    $newUserMail = filter_input(INPUT_POST, "newUserMail", FILTER_SANITIZE_STRING);
    $newPassword = filter_input(INPUT_POST, "newPassword", FILTER_SANITIZE_STRING);
    $sendNotification = filter_input(INPUT_POST, "sendNotification", FILTER_SANITIZE_STRING);
    $newUserNote = filter_input(INPUT_POST, "newUserNote", FILTER_SANITIZE_STRING);

    $con = new mysqli($databaseServer, $databaseUser, $databasePW, $databaseDB);
    if (!$con)
    {
        die('Could not connect: ' . mysqli_connect_error());
    }

    // Create user
    //
    $hash = hash('sha256', $newPassword); // playing with hash
    function createSalt() // playing with salt - creates a 3 character sequence
    {
        $string = md5(uniqid(rand(), true));
        return substr($string, 0, 3);
    }
    $salt = createSalt();
    $hash = hash('sha256', $salt . $hash);

    $sql = "INSERT INTO m_users ( username, password, salt, date_invite, email, admin_note ) VALUES ( '$newUsername' , '$hash' , '$salt' , now() , '$newUserMail', '$newUserNote');";
    mysqli_query($con, $sql);


    // create a dummy note for the new user
    //
    $newNoteTitle = "This is a first example note";
    $newNoteContent = "This is the example note content. Feel free to delete me.";
    $sql = "INSERT INTO m_notes (title, content, date_create, date_mod, owner, save_count) VALUES ('$newNoteTitle', '$newNoteContent', now(), now(), '$newUsername', 1 )";
    mysqli_query($con, $sql);


    // Send notification if needed
    //
    if( $sendNotification == true )
    {
        $serverName = $_SERVER["SERVER_NAME"];
        $serverPort = $_SERVER["SERVER_PORT"];
        //$serverRequestURI = $_SERVER["REQUEST_URI"];
        //$serverRequestURI = filter_input(INPUT_SERVER, 'SCRIPT_URL', FILTER_SANITIZE_URL);
        $serverRequestURI = htmlspecialchars($_SERVER['REQUEST_URI']);

        // need full page url for link in the invite mail
        $pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
        if ( ( $serverPort != "80" ) and ( $serverPort != "443" ) )
        {
            $pageURL .= $serverName.":".$serverPort.$serverRequestURI;
        }
        else
        {
            $pageURL .= $serverName.$serverRequestURI;
        }

        $invite_target = $pageURL;
        $invite_from = $_SESSION[ 'monoto' ][ 'username' ];

        $to = $newUserMail;
        $subject = "monoto-notes invite";
        $body = "Hi,
                \n".$invite_from." invited you to monoto - his web-based notes solution.
                \nFeel free to use it as your personal notes keeper as well.
                \n\nYou can get some general informations about monoto here: https://github.com/macfidelity/monoto/wiki.
                \n\n\n\nThe login credentials are as follows:
                \nUsername: ".$newUsername."
                \nPassword: ".$newPassword."
                \n\n\nPlease change your password after your first visit at:
                \n".$invite_target."
                \n\nHave fun.";
        if (mail($to, $subject, $body))
        {
        }
        else
        {
        }
    }

    // Close sql connection
    //
    mysqli_close( $con ); // close sql connection
}
?>

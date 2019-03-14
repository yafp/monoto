<?php
// -----------------------------------------------------------------------------
// index.php
// handles the login
// -----------------------------------------------------------------------------
session_start();
if ( isset( $_SESSION[ 'monoto' ][ 'valid' ] ) )
{
    header('Location: notes.php'); // if session is valid - redirect to main-notes interface.
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'inc/genericIncludes.php'; ?>

    <!-- specific -->
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/monoto/index.css" >
</head>
<body class="text-center">

    <!-- LOGIN -->
    <form class="form-signin" name="login" action="index.php" method="post" enctype="multipart/form-data">

        <a href="index.php"><img class="mb-4" src="images/logo/monotoLogoBlack.png" alt="" width="300"></a>

        <!-- username -->
        <label for="username" class="sr-only">username</label>
        <input type="text" id="username" name="username" class="form-control" placeholder="Username" autocomplete="username" required autofocus>
        <!-- /username -->

        <!-- Password -->
        <label for="password" class="sr-only">Password</label>
        <input type="password" id="password" name="password" autocomplete="new-password" class="form-control" placeholder="Password" required>
        <!-- /Password -->

        <!-- button login -->
        <button class="btn btn-lg btn-primary btn-block" name="doLogin" id="doLogin" type="submit">Login</button>
        <!-- /button login -->

        <!-- FOOTER -->
        <p class="mt-5 mb-3 text-muted"><?php require 'inc/genericFooter.php'; ?></p>
        <!-- /FOOTER -->
    </form>
    <!-- /form -->
</body>



<?php
//
// try to login
//
if ( isset ( $_POST[ "doLogin" ] ) )
{
    $con = connectToDatabase();

    // input validation and sanitize post/get values
    //
    // examples:
    //$name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
    //$email= filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $username= filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $password= filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // check if there is a user with matching data
    $query = "SELECT password, salt, email FROM m_users WHERE username = '$username';";
    $result = mysqli_query($con, $query);
    if(mysqli_num_rows($result) < 1) // no such user exists
    {
        displayNoty("Login failed.", "error");
    }
    else // user exists
    {
        $userData = mysqli_fetch_array($result);
        $hash = hash('sha256', $userData[ 'salt' ] . hash('sha256', $password) );
        $email = $userData[ 'email' ];

        // check if user-account is locked already cause it had 3 failed logins in a row
        $sql = "SELECT failed_logins_in_a_row FROM m_users WHERE username='".$username."'  ";
        $result = mysqli_query($con, $sql);
        while ( $row = mysqli_fetch_array ($result) )
        {
            // get amount of failed-logins-in-a-row of this account
            $failCounterInARow = $row[0];
        }

        if( $failCounterInARow < 3 ) // Account is not locked -> try to login
        {
            //check for incorrect password
            if ( $hash != $userData[ 'password' ] )
            {
                // log incorrect login attempt - date
                $sql = "UPDATE m_users SET date_last_login_fail = now() WHERE username='".$username."' ";
                $result = mysqli_query($con, $sql);

                // get current fail-login-count
                $sql = "SELECT failed_logins FROM m_users WHERE username='".$username."'  ";
                $result = mysqli_query($con, $sql);
                while($row = mysqli_fetch_array($result))
                {
                    $failCounter = $row[0];
                }
                $failCounter = $failCounter +1;
                $failCounterInARow = $failCounterInARow +1;

                // update failcounter & failcounterInARow
                $sql = "UPDATE m_users SET failed_logins='".$failCounter."', failed_logins_in_a_row='".$failCounterInARow."' WHERE username='".$username."' ";
                $result = mysqli_query ( $con, $sql );

                // can not use 'writeNewLogEntry' here - this function can only be used from within inc/
                //writeNewLogEntry("Login error", "User: <b>".$username."</b> failed to login.");
                //
                $event = "Login error";
                $details = "User: <b>".$username."</b> failed to login.";
                $sql = "INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(), '$username' )";
                $result = mysqli_query ( $con, $sql );

                // #287 - send notification about locking account
                if ( $failCounterInARow == 3 )
                {
                    $messageSubject = "Your monoto account is now locked";
                    $messageText = "Your monoto account is now locked, after 3 wrong login attempts in a row.";
                    if ( @mail ( $email, $messageSubject, $messageText ) ) // try to send notification email
                    {
                        displayNoty("Lock-notification email sent.", "success");
                    }
                    else
                    {
                        displayNoty("Unable to sent lock-notification email.", "error");
                    }
                    displayNoty("Login failed, account is now locked.", "error");
                }
                else
                {
                    displayNoty("Login failed.", "error");
                }
            } // /incorrect password
            else
            {
                //login successful

                // init most relevant session-info
                //
                $_SESSION[ 'monoto' ][ 'username' ] = $username;
                $_SESSION[ 'monoto' ][ 'valid' ] = 1;

                // if user is admin - add the info to our session
                $query = "SELECT is_admin FROM m_users WHERE username = '$username';";
                $result = mysqli_query($con, $query);
                while($row = mysqli_fetch_array($result))
                {
                    if($row[0] == 1)
                    {
                        $_SESSION[ 'monoto' ][ 'admin' ] = 1;
                    }
                }

                // store language setting in session variable (#210)
                $query = "SELECT language FROM m_users WHERE username = '$username';";
                $result = mysqli_query($con, $query);
                while($row = mysqli_fetch_array($result))
                {
                    $_SESSION[ 'monoto' ][ 'lang' ] = $row[ 0 ];
                }

                // store servers getText situation in session variable for later usage (#211)
                if ( !function_exists( "get_text" ) ) // gettext is not installed - fallback
                {
                    $_SESSION[ 'monoto' ][ 'getText' ] = 0;
                }
                else // gettext is installed - translate
                {
                    $_SESSION[ 'monoto' ][ 'getText' ] = 1;
                }

                // get current login-count
                $sql = "SELECT login_counter FROM m_users WHERE username='".$username."'";
                $result = mysqli_query($con, $sql);
                while ( $row = mysqli_fetch_array ( $result ) )
                {
                    $loginCounter = $row[0];
                }
                $loginCounter = $loginCounter +1;

                // check if its first login - if so: save the first login date to db
                if($loginCounter == 1)
                {
                    $sql = "UPDATE m_users SET date_first_login= now() WHERE username='".$username."' ";
                    $result = mysqli_query ( $con, $sql );
                }

                // update last login date & logincounter
                $sql = "UPDATE m_users SET date_last_login= now(), login_counter='".$loginCounter."' WHERE username='".$username."' ";
                $result = mysqli_query ( $con, $sql );

                // get user-browser
                $userBrowser = $_SERVER['HTTP_USER_AGENT'];
                // get user-ip
                $userIp = $_SERVER['REMOTE_ADDR'];

                // can not use 'writeNewLogEntry' here - this function can only be used from within inc/
                //writeNewLogEntry("Login", "User: <b>".$username."</b> logged in successfully.");
                //
                $event = "Login";
                $details = "User: <b>".$username."</b> logged in successfully<br><small>from ".$userIp."<br>using ".$userBrowser.".</small>";
                $sql = "INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(),'$username' )";
                $result = mysqli_query($con, $sql);

                // reset failedLoginsInARow entry in database
                $sql = "UPDATE m_users SET failed_logins_in_a_row='0' WHERE username='".$username."' ";
                $result = mysqli_query($con, $sql);

                echo '<script type="text/javascript">window.location="notes.php"</script>';
            }
        }
        else // login is not possible anymore - admin must remove the login lock
        {
            // #287
            displayNoty("Account is locked", "error");
        }
    }
}
?>

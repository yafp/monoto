<?php
// -----------------------------------------------------------------------------
// Name:		index.php
// Function:	handles the login
// -----------------------------------------------------------------------------

session_start();
if (isset($_SESSION['valid']))
{
    header('Location: n.php'); // if session is valid - redirect to main-notes interface.
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'inc/coreIncludes.php'; ?>

    <!-- specific -->
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/monoto/index.css" >
</head>
<body class="text-center">

    <!-- LOGIN -->
    <form class="form-signin" name="login" action="index.php" method="post" enctype="multipart/form-data">

        <a href="index.php"><img class="mb-4" src="images/logo/monoto_logo_black.png" alt="" width="300"></a>

        <!-- username -->
        <label for="username" class="sr-only">username</label>
        <input type="text" id="username" name="username" class="form-control" placeholder="Username" autocomplete="username" required autofocus>

        <!-- Password -->
        <label for="password" class="sr-only">Password</label>
        <input type="password" id="password" name="password" autocomplete="new-password" class="form-control" placeholder="Password" required>

        <!-- button -->
        <button class="btn btn-lg btn-primary btn-block" name="doLogin" id="doLogin" type="submit">Login</button>

        <!-- FOOTER -->
        <p class="mt-5 mb-3 text-muted"><?php require 'inc/footer.php'; ?></p>
    </form>
</body>



<?php
//
// try to login
//
if (isset($_POST["doLogin"]) )
{
    //var_dump($_POST);
    $con = connectToDB();

    // input validation and sanitize post/get values
    //
    // examples:
    //$name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
    //$email= filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    //$search = filter_input(INPUT_GET, "s", FILTER_SANITIZE_STRING);

    // get data
    //$username = $_POST['username'];
    //$username = sanitize_text_field( $_POST['username'] );
    $username= filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);

    //$password = $_POST['password'];
    //$password = sanitize_text_field( $_POST['password'] );
    $password= filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    //$username = mysqli_real_escape_string($con, $username);
    $_SESSION['username'] = $username; // add session-info
    //$owner = $_SESSION['username'];

    // check if there is a user with matching data
    $query = "SELECT password, salt FROM m_users WHERE username = '$username';";
    $result = mysqli_query($con, $query);
    if(mysqli_num_rows($result) < 1) // no such user exists
    {
        displayNoty("Login failed.","error");
    }
    else // user exists
    {
        $userData = mysqli_fetch_array($result);
        $hash = hash('sha256', $userData['salt'] . hash('sha256', $password) );

        // check if user-account is locked already cause it had 3 failed logins in a row
        $sql="SELECT failed_logins_in_a_row FROM m_users WHERE username='".$username."'  ";
        $result = mysqli_query($con, $sql);
        while($row = mysqli_fetch_array($result))
        {
            // get amount of failed-logins-in-a-row of this account
            $failCounterInARow = $row[0];
        }

        if($failCounterInARow < 3) // try to login
        {
            //check for incorrect password
            if($hash != $userData['password'])
            {
                // log incorrect login attempt - date
                $sql="UPDATE m_users SET date_last_login_fail = now() WHERE username='".$username."' ";
                $result = mysqli_query($con, $sql);

                // get current fail-login-count
                $sql="SELECT failed_logins FROM m_users WHERE username='".$username."'  ";
                $result = mysqli_query($con, $sql);
                while($row = mysqli_fetch_array($result))
                {
                    $failCounter = $row[0];
                }
                $failCounter = $failCounter +1;
                $failCounterInARow = $failCounterInARow +1;

                // update failcounter & failcounterInARow
                $sql="UPDATE m_users SET failed_logins='".$failCounter."', failed_logins_in_a_row='".$failCounterInARow."' WHERE username='".$username."' ";
                $result = mysqli_query($con, $sql);

                // record to log - that we had a successfull user login
                $event = "login error";
                $details = "User: <b>".$username."</b> failed to login.";
                $sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(), '$username' )";
                $result = mysqli_query($con, $sql);

                displayNoty("Login failed.","error");
            }
            else //login successful
            {
                $_SESSION['valid'] = 1;

                // if user is admin - add the info to our session
                $query = "SELECT is_admin FROM m_users WHERE username = '$username';";
                $result = mysqli_query($con, $query);
                while($row = mysqli_fetch_array($result))
                {
                    if($row[0] == 1)
                    {
                        $_SESSION['admin'] = 1;
                    }
                }

                // store language setting in session variable (#210)
                $query = "SELECT language FROM m_users WHERE username = '$username';";
                $result = mysqli_query($con, $query);
                while($row = mysqli_fetch_array($result))
                {
                    $_SESSION['lang'] = $row[0];
                }

                // store servers getText situation in session variable for later usage (#211)
                if (!function_exists("gettext")) // gettext is not installed - fallback
                {
                    $_SESSION['getText'] = 0;
                }
                else // gettext is installed - translate
                {
                    $_SESSION['getText'] = 1;
                }

                // get current login-count
                $sql="SELECT login_counter FROM m_users WHERE username='".$username."'  ";
                $result = mysqli_query($con, $sql);
                while($row = mysqli_fetch_array($result))
                {
                    $loginCounter = $row[0];
                }
                $loginCounter = $loginCounter +1;

                // check if its first login - if so: save the first login date to db
                if($loginCounter == 1)
                {
                    $sql="UPDATE m_users SET date_first_login= now() WHERE username='".$username."' ";
                    $result = mysqli_query($con, $sql);
                }

                // update last login date & logincounter
                $sql="UPDATE m_users SET date_last_login= now(), login_counter='".$loginCounter."' WHERE username='".$username."' ";
                $result = mysqli_query($con, $sql);

                // record to log - that we had a successfull user login
                $event = "login";
                $details = "User: <b>".$username."</b> logged in successfully.";
                $sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(),'$username' )";
                $result = mysqli_query($con, $sql);

                // reset failedLoginsInARow entry in database
                $sql="UPDATE m_users SET failed_logins_in_a_row='0' WHERE username='".$username."' ";
                $result = mysqli_query($con, $sql);

                echo '<script type="text/javascript">window.location="n.php"</script>';
            }
        }
        else         // login is not possible anymore - admin must remove the login lock
        {
            displayNoty("Account is locked","error");
        }
    }
}
?>

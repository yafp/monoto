<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'inc/coreIncludes.php'; ?>
</head>

<body role="document">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
        <div class="container">
            <a class="navbar-brand" href="index.php"><img src="images/logo/monoto_logo_white.png" height="26"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">

            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container theme-showcase" role="main">
        <div id="container">
            <div id="noteContentCo">
                <h3>Password reset</h3>
                <p>Please enter your email-address and trigger the reset-process. This will generate a new random password which you will receive by email.</p>
                <form name="reset" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
                    <table border="0"><tr><td><input type="email" name="email" placeholder="Email" required="required" /></td><td><input type="submit" value="Reset" name="doPWReset" /></td></tr></table>
                </form>
            </div>

            <!-- footer -->
            <?php require 'inc/footer.php'; ?>

        </div>
    </div> <!-- /container -->

</body>
</html>



<?php
require 'inc/helperFunctions.php';

// creating the initial admin-account
if ( isset($_POST["doPWReset"]) )
{
    displayNoty("Processing reset","notification");

    $resetEmail = $_POST['email'];

    require 'conf/config.php';
    require 'inc/db.php'; // connect to db
    $con = connectToDB();

    $query = "SELECT id FROM m_users WHERE email='$resetEmail' ";

    $result = mysqli_query($con, $query); // run the mysql query
    if (mysqli_num_rows($result)==0) // found no useraccount for mail-address
    {
        displayNoty("Unknown email, cancelling reset","error");
    }
    else // found a matching user-account
    {
        while($row = mysqli_fetch_array($result))
        {
            // remember affected account id
            $affectedAccount=$row[0];
        }

        // generate a random password
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $generatedPW = substr(str_shuffle($chars),0,16);

        // generate hash and salt based on password
        $hash = hash('sha256', $generatedPW);
        function createSalt()
        {
            $string = md5(uniqid(rand(), true));
            return substr($string, 0, 3);
        }
        $salt = createSalt();
        $hash = hash('sha256', $salt . $hash);

        // update db record for this user
        $query = "UPDATE m_users SET password='$hash', salt='$salt' WHERE id='$affectedAccount'";
        mysqli_query($con, $query);

        // prepare notification email
        $to = $resetEmail;
        $subject = "monoto-notes password reset";
        $body = "Hi,
        \nSomeone triggered a password reset for your account at:
            \n".$invite_target."
            \n\n\n\nThe new generated password is as follows:
            \nPassword: ".$generatedPW."
            \n\n\nPlease change your password after your first visit.
            \n\nHave fun.";

            if(@mail($to, $subject, $body))        // try to send notification email
            {
                displayNoty("Notification email has been sent.","success");
            }
            else
            {
                displayNoty("Unable to sent notification mail.","error");
            }
            displayNoty("Password reset finished","notification");
        }
    }
    ?>

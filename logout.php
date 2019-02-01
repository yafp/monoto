<?php
session_start();
if($_SESSION['valid'] != 1) // check if the user-session is valid or not
{
    header('Location: redirect.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'inc/coreIncludes.php'; ?>
</head>

<body role="document">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
        <div class="container">
            <a class="navbar-brand" href="n.php"><img src="images/logo/monoto_logo_white.png" height="26"></a>
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
                <h3><i class="fas fa-sign-out-alt"></i> <?php echo translateString("Logout"); ?></h3>
                <?php

                // update logout conter
                $con = connectToDB();
                $result = mysqli_query($con, "UPDATE m_users SET logout_counter = logout_counter + 1 WHERE username='".$_SESSION['username']."'");

                // Define lgout image
                $logoutImage = "images/content/logout.gif";

                // destroy the user session
                session_destroy();
                ?>

                <!-- logout image -->
                <div class="text-center">
                    <img class="center-block" src="<?php echo $logoutImage; ?>">
                </div>

                <script>
                // fade out the container after some time
                //$('#container').delay(3000).fadeOut(5000);

                // Redirect to login
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 1000);

                </script>

            </div>
        </div>

        <!-- footer -->
        <?php require 'inc/footer.php'; ?>

    </div> <!-- /container -->

    <!-- JS-->
    <script type="text/javascript" src="js/jquery.cookie.js"></script>
</body>
</html>

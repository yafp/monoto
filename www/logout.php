<?php include 'inc/checkSession.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'inc/genericIncludes.php'; ?>
</head>

<body role="document">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
        <div class="container">
            <a class="navbar-brand" href="notes.php"><img src="images/logo/monotoLogoWhite.png" height="26"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
            </div>
        </div>
    </nav>
    <!-- /Navigation -->

    <!-- Page Content -->
    <div class="container theme-showcase" role="main">
        <div id="container">
            <?php
                // create database connection
                $con = connectToDatabase();

                $username = $_SESSION[ 'monoto' ][ 'username' ];

                // update logout conter
                $result = mysqli_query($con, "UPDATE m_users SET logout_counter = logout_counter + 1 WHERE username='".$username."'");

                // update activity log
                $event = "Logout";
                $details = "User: <b>".$username."</b> logged out successfully.";
                $sql = "INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(),'$username' )";
                mysqli_query( $con, $sql );

                // destroy the user session
                session_destroy();
            ?>

            <!-- redirect -->
            <script>
                window.location.replace("index.php");
            </script>
        </div>
        <!-- /container -->

        <!-- footer -->
        <?php require 'inc/genericFooter.php'; ?>
        <!-- /footer -->

    </div>
    <!-- /container theme-->
</body>
</html>

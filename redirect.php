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

                <h3><i class="fas fa-forward"></i> <?php echo translateString("Redirect"); ?></h3>

                <!-- redirect image -->
                <div class="text-center">
                    <img src="images/content/redirect.gif">
                </div>

                <!-- refresh -->
                <?php header( "refresh:1;url=index.php" ); ?>


                <script>
                    console.log("Triggering redirect.");
                    window.location.href = "index.php"
                </script>


                </script>
            </div>
        </div>

        <!-- footer -->
        <?php require 'inc/footer.php'; ?>

    </div> <!-- /container -->
</body>
</html>

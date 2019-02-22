<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        include 'inc/coreIncludes.php';
    ?>

</head>

<body role="document">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
        <div class="container">
            <a class="navbar-brand" href="index.php"><img src="images/logo/monotoLogoWhite.png" height="26"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">

            </div>
        </div>
    </nav>

    <!-- container theme-->
    <div class="container theme-showcase" role="main">

        <!-- container -->
        <div id="container">
            <h3><i class="fas fa-exclamation-triangle"></i> <?php echo translateString("Error"); ?> 404</h3>

            <!-- error image -->
            <div class="text-center">
                <img src="images/404/error.gif">
            </div>
            <!-- /error image -->

            <br>

            <div class="alert alert-info" role="alert">
                <?php echo translateString("Click the logo in the header to continue"); ?>
            </div>
        </div>
        <!-- /container -->

        <!-- footer -->
        <?php require 'inc/footer.php'; ?>
        <!-- /footer -->

    </div>
    <!-- /container theme -->

</body>
</html>

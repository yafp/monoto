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
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="n.php"><i class="fas fa-edit"></i> <?php echo translateString("Notes") ?><span class="sr-only">(current)</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="m.php"><i class="fas fa-user"></i> <?php echo translateString("MyMonoto") ?></a></li>
                    <li class="nav-item active"><a class="nav-link" href="k.php"><i class="fas fa-keyboard"></i> <?php echo translateString("Keyboard") ?></a></li>
                    <?php
                    if($_SESSION['admin'] == 1) // show admin-section
                    {
                        echo '<li class="nav-item"><a class="nav-link" href="a.php"><i class="fas fa-cog"></i>';
                        echo translateString("Admin");
                        echo '</a></li>';
                    }
                    ?>
                    <li class="nav-item"><a class="nav-link" href="#" onclick="showLogoutDialog();"><i class="fas fa-sign-out-alt"></i> <?php echo translateString("Logout") ?></a></li>
                </ul>
            </div>
        </div>
    </nav>


    <!-- Page Content -->
    <div class="container theme-showcase" role="main">
        <div id="container">

            <h3><?php echo translateString("notes-page"); ?></h3>
            <table class="tableWithBorders" style="width:100%">
                <tr><th width="25%"><?php echo translateString("Key"); ?></th><th><?php echo translateString("Function"); ?></th></tr>
                <tr><td>ESC</td><td><?php echo translateString("Resets all input fields and sets focus to search"); ?></td></tr>
                <tr><td>F2</td><td><?php echo translateString("Toggle maximize of editor"); ?></td></tr>
                <tr><td>F5</td><td><?php echo translateString("Reloads all notes from db"); ?></td></tr>
                <tr><td>F9</td><td><?php echo translateString("Saves a selected note"); ?></td></tr>
                <tr><td><?php echo translateString("Del"); ?></td><td><?php echo translateString("Deletes the selected note"); ?></td></tr>
                <tr><td><i class="fas fa-arrow-down"></i> <?php echo translateString("(in search)"); ?></td><td><?php echo translateString("Selects the top record"); ?></td></tr>
                <tr><td><i class="fas fa-arrow-down"></i> <?php echo translateString("(with selected note)"); ?></td><td><?php echo translateString("Selects the next record"); ?></td></tr>
                <tr><td><i class="fas fa-arrow-up"></i> <?php echo translateString("(in search)"); ?></td><td><?php echo translateString("Selects the last record"); ?></td></tr>
                <tr><td><i class="fas fa-arrow-up"></i> <?php echo translateString("(with selected note)"); ?></td><td><?php echo translateString("Selects the previous record"); ?></td></tr>
            </table>

            <!-- footer -->
            <?php require 'inc/footer.php'; ?>

        </div>
    </div>


    <!-- JS-->
</body>
</html>

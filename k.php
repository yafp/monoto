<?php include 'inc/checkSession.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'inc/coreIncludes.php'; ?>
    <!-- specific -->
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/monoto/keyboard.css" title="default" />
</head>

<body role="document">

    <!-- Navigation -->
    <?php include 'inc/navigation.php'; ?>
    <!-- /Navigation -->

    <!-- container theme -->
    <div class="container theme-showcase" role="main">
        <!-- container -->
        <div id="container">
            <h3><?php echo translateString("notes-page"); ?></h3>

            <!-- table -->
            <table class="tableWithBorders" style="width:100%">
                <tr><th width="25%"><?php echo translateString("Key"); ?></th><th><?php echo translateString("Function"); ?></th></tr>
                <tr><td><div class="key special"><span>ESC</span></div></td><td><?php echo translateString("Resets all input fields and sets focus to search"); ?></td></tr>
                <tr><td><div class="key special"><span>F2</span></div></td><td><?php echo translateString("Toggle maximize of editor"); ?></td></tr>
                <tr><td><div class="key special"><span>F5</span></div></td><td><?php echo translateString("Reloads all notes from db"); ?></td></tr>
                <tr><td><div class="key special"><span>F9</span></div></td><td><?php echo translateString("Saves a selected note"); ?></td></tr>
                <tr><td><div class="key special"><span><?php echo translateString("Del"); ?></span></div></td><td><?php echo translateString("Deletes the selected note"); ?></td></tr>
                <tr><td><div class="key special"><span><i class="fas fa-arrow-down"></i></span></div> <?php echo translateString("(in search)"); ?></td><td><?php echo translateString("Selects the top record"); ?></td></tr>
                <tr><td><div class="key special"><span><i class="fas fa-arrow-down"></i></span></div> <?php echo translateString("(with selected note)"); ?></td><td><?php echo translateString("Selects the next record"); ?></td></tr>
                <tr><td><div class="key special"><span><i class="fas fa-arrow-up"></i></span></div> <?php echo translateString("(in search)"); ?></td><td><?php echo translateString("Selects the last record"); ?></td></tr>
                <tr><td><div class="key special"><span><i class="fas fa-arrow-up"></i></span></div> <?php echo translateString("(with selected note)"); ?></td><td><?php echo translateString("Selects the previous record"); ?></td></tr>
            </table>
            <!-- /table -->

            <!-- footer -->
            <?php require 'inc/footer.php'; ?>
            <!-- /footer -->

        </div>
        <!-- /container -->
    </div>
    <!-- /container theme -->
</body>
</html>

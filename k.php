<?php include 'inc/checkSession.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'inc/coreIncludes.php'; ?>
</head>

<body role="document">

    <!-- Navigation -->
    <?php include 'inc/navigation.php'; ?>

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
</body>
</html>

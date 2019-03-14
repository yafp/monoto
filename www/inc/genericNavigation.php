<?php

// -----------------------------------------------------------------------------
// genericNavigation.php
// Contains the main navigation which is used after login
// -----------------------------------------------------------------------------

// prevent direct call of this script
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) )
{
    // Up to you which header to send, some prefer 404 even if
    // the files does exist for security
    header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );

    // choose the appropriate page to redirect users
    die( header( 'location: ../404.php' ) );
}

?>

<!--  NAVIGATION -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
    <div class="container">
        <a class="navbar-brand" href="notes.php"><img src="images/logo/monotoLogoWhite.png" height="26"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item" id="navNotes"><a class="nav-link" href="notes.php"><i class="fas fa-edit"></i> <?php echo translateString("Notes") ?><span class="sr-only">(current)</span></a></li>
                <li class="nav-item" id="navProfile"><a class="nav-link" href="profile.php"><i class="fas fa-user"></i> <?php echo translateString("Profile") ?></a></li>
                <li class="nav-item" id="navKeyboard"><a class="nav-link" href="keyboard.php"><i class="fas fa-keyboard"></i> <?php echo translateString("Keyboard") ?></a></li>
                <?php
                include 'inc/checkSession.php';
                if ( $_SESSION[ 'monoto' ][ 'admin' ] == 1 ) // show admin-section
                {
                    echo '<li class="nav-item" id="navAdmin"><a class="nav-link" href="admin.php"><i class="fas fa-cog"></i>';
                    echo translateString("Admin");
                    echo '</a></li>';
                }
                ?>
                <li class="nav-item"><a class="nav-link" href="#" onclick="showLogoutDialog();"><i class="fas fa-sign-out-alt"></i> <?php echo translateString("Logout") ?></a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- set active list item -->
<script type="text/javascript" src="js/monoto/navigation.js"></script>

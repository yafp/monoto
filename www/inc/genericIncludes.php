<?php
// -----------------------------------------------------------------------------
// Name:		genericIncludes.php
// Function:	Gets included in main files, includes most relevant files itself
// -----------------------------------------------------------------------------

    // HTML Head
    include 'inc/genericHeaderHTML.php';

    // CSS
    include 'inc/genericHeaderCSS.php';

    // JS
    include 'inc/genericHeaderJS.php';

    // specials
    require 'inc/helperFunctions.php'; // logging, translation etc
    require 'inc/db.php'; // connect to db
    require 'config/config.php'; // some settings
    require 'config/build.php'; // version informations

?>

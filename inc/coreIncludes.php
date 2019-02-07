<?php
// -----------------------------------------------------------------------------
// Name:		coreIncludes.php
// Function:	Gets included in main files, includes most relevant files itself
// -----------------------------------------------------------------------------

    // HTML Head
    include 'inc/coreIncludesHTMLHead.php';

    // CSS
    include 'inc/coreIncludesCSS.php';

    // JS
    include 'inc/coreIncludesJS.php';

    // specials
    require 'inc/helperFunctions.php'; // logging, translation etc
    require 'inc/db.php'; // connect to db
    require 'config/config.php'; // some settings
    require 'config/build.php'; // version informations

?>

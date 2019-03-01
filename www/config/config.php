<?php
    // WARNING - this is a critical file.
    // Make sure it is not worldwide-readable ;)

    // #################################################################################
    // SECTION 1 - you should mess with these
    // #################################################################################
    //
    $databaseServer = "localhost"; // define your mysql server here
    $databaseDB = "monoto"; // define your mysql db here
    $databaseUser = "monoto"; // define your mysql user here
    $databasePW	= "monoto"; // define the mysql user pw here


    // #################################################################################
    // SECTION 2- you might mess with these
    // #################################################################################
    //
    // for installation specific admin settings


    // #################################################################################
    // SECTION 3 - Configure debug output for developers
    // #################################################################################
    //
    //define("DEVELOPMENT", true);
    if (!defined('DEVELOPMENT'))
    {
        define('DEVELOPMENT', true);
    }

    if (DEVELOPMENT === true)
    {
        ini_set('display_errors', 1);
    }
?>

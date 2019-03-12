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
    $databasePW = "monoto"; // define the mysql user pw here


    // #################################################################################
    // SECTION 2- you might mess with these
    // #################################################################################
    //
    // for installation specific admin settings
    //
    // project name
    $m_name = "monoto";


    // #################################################################################
    // SECTION 3 - Configure debug output for developers
    // #################################################################################
    //
    if ( !defined ('DEVELOPMENT') )
    {
        define('DEVELOPMENT', true); // to enable php 'display_errors'
        //define('DEVELOPMENT', false); // to disable php 'display_errors'

        if ( DEVELOPMENT === true )
        {
            // display_errors
            //
            // This determines whether errors should be printed to the screen
            // as part of the output or if they should be hidden from the user.
            ini_set('display_errors', 1);
        }
    }

?>

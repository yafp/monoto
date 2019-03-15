<?php

// -----------------------------------------------------------------------------
// helperFunctions.php
// This file contains several php methods used in this project
// -----------------------------------------------------------------------------


/**
 * Creates a noty notification popup
 *
 * @param {string} notyText - the actual notification text
 * @param {string} notyType - the notification type
 */
function displayNoty($notyText, $notyType)
{
    // NOTY-TYPES:
    //
    // alert
    // information
    // error
    // warning
    // notification
    // success

    // display notification
    echo "<script>var n = noty({text: '".$notyText."', type: '".$notyType."'});</script>";
}


/**
 * Write to JavaScript console (via javascript)
 *
 * @param {string} message - The message which should be written to JS console.
 */
function writeToConsoleLog( $message )
{
    echo "<script>console.log('[PHP]".$message."')</script>"; // write to js console
}


/**
 * Check if php gettext is supported
 * and display information according to the install-state
 */
function checkGetTextSupport()
{
    if ( !function_exists( "gettext" ) ) // gettext is not installed
    {
        echo "<i class='fas fa-times'></i>&nbsp;<span class='badge badge-secondary'>PHP: gettext</span> is not installed. Translations will fail";
        return;
    }

    // gettext is installed
    echo "<i class='fas fa-thumbs-up'></i>&nbsp;<span class='badge badge-secondary'>PHP: gettext</span> is installed.";
}


/*
 * Translating the UserInterface (#210)
 *
 * @param {string} $textForTranslation - The text which should be translated if needed
 * @returns {string} $translation - A string, which might be translated if one was found
 */
function translateString( $textForTranslation )
{
    if ( $_SESSION[ 'monoto' ][ 'getText' ] == 0 ) // gettext is not installed - fallback
    {
        //writeToConsoleLog("translateString ::: getText is not installed. Unable to translate.");
        $translation = $textForTranslation;
        return $translation;
    }

    // otherwise: gettext is installed -> try to translate
    //writeToConsoleLog("translateString ::: Trying to translate...");

    // I18N support information here
    $language = $_SESSION[ 'monoto' ][ 'lang' ];
    if ( $language == "" ) // Fallback to english
    {
        $language = "en_US";
    }

    putenv("LANG=$language");
    setlocale(LC_ALL, $language);

    // Set the text domain as 'messages'
    $domain = 'monoto';
    bindtextdomain($domain, "./locale");
    textdomain($domain);

    $translation = gettext($textForTranslation);

    // report non-translated texts for debugging
    if ( $translation == $textForTranslation )
    {
        if ( $language != "en_US" )
        {
            writeToConsoleLog("translateString ::: Translation-issue, found no translation for: _".$textForTranslation."_.");
        }
    }

    return $translation;
}


/*
 * Create a new record in the log table m_log
 * Be aware: can only be used from within inc/ becasue of 'require'
 *
 * @param {string} $eventType - The log type
 * @param {string} $eventMessage - The log message
 */
function writeNewLogEntry( $eventType, $eventMessage )
{
    if ( $_SESSION[ 'monoto' ][ 'valid' ] == 1 )
    {
        require '../config/config.php';

        // validate $eventType
        switch ( $eventType )
        {
            case "Note creation":
            case "Note update":
            case "Note delete":
            case "Login":
            case "Login error":
            case "Eraser user events":
            case "Eraser user notes":
            case "Eraser user account":
            case "Password update":
            case "Database Optimizer":
                // = expected events
                break;
            default:
                $eventType = "Undefined";
        }

        // open database connection
        $con = new mysqli($databaseServer, $databaseUser, $databasePW, $databaseDB);
        if ( !$con )
        {
            exit('Could not connect: ' . mysqli_connect_error());
        }

        $owner = $_SESSION[ 'monoto' ][ 'username' ];

        // create event entry in datatabase
        $sql = "INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$eventType','$eventMessage', now(), '$owner' )";
        mysqli_query( $con, $sql );
    }
}


/*
 * Playground for phpunit
 *
 * @param {number} $num - a number
 * @returns {number} $num/2
 */
function oddOrEven( $num )
{
    return $num%2; // Returns 0 for odd and 1 for even
}


?>

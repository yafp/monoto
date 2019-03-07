<?php

// -----------------------------------------------------------------------------
// getLang.php
// Gets the language from the session variable and returns it
// -----------------------------------------------------------------------------

header('Content: application/json', 1);

// Start a session if it isnt already started
//
if ( session_status() == PHP_SESSION_NONE)
{
    session_start();
}

// check if the user-session is valid or not
if ( $_SESSION[ 'monoto' ][ 'valid' ] == 1 )
{
    // get selected language from session
    $selectedLang = $_SESSION['monoto']['lang'];

    $returnVal = array('myVar', $selectedLang);

    echo json_encode($returnVal);
}

?>

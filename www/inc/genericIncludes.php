<?php

// -----------------------------------------------------------------------------
// genericIncludes.php
// Gets included in main files, includes most relevant files itself
// -----------------------------------------------------------------------------

// prevent direct call of this script
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) )
{
    include "helperFunctions.php";
    handleBadAccessToIncScripts();
}

// HTML Head
include 'inc/genericHeaderHTML.php';

// CSS
include 'inc/genericHeaderCSS.php';

// JS
include 'inc/genericHeaderJS.php';

// specials
require 'inc/helperFunctions.php'; // logging, translation etc
require 'inc/database.php'; // connect to db
require 'config/databaseConfig.php'; // some settings
require 'config/build.php'; // version informations
require 'config/constants.php'; // constant strings

?>

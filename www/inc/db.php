<?php

// prevent direct call of this script
if (strpos($_SERVER['SCRIPT_FILENAME'], 'db.php') !== false)
{
    header('Location: ../index.php'); // back to login page
    die();
}


/**
 * Connects to a mysql database
 *
 * @author yafp
 * @return the connection variable $con
 */
function connectToDatabase()
{
    require 'config/config.php';

    $con = new mysqli ( $databaseServer, $databaseUser, $databasePW, $databaseDB );

    if ($con->connect_errno) // Error-Case
    {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "ErrNo: " . mysqli_connect_errno() . PHP_EOL;
        echo "Error: " . mysqli_connect_error() . PHP_EOL;
        return;
    }

    // connection worked
    return $con;
}
?>

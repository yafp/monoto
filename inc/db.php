<?php
// -----------------------------------------------------------------------------
// Name:		db.php
// Function:	connect to a mySQL database
// -----------------------------------------------------------------------------

function connectToDB()
{
	require 'config/config.php';

    $con = new mysqli($databaseServer, $databaseUser, $databasePW, $databaseDB);

    if ($conn->connect_errno)
    {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
    	echo "ErrNo: " . mysqli_connect_errno() . PHP_EOL;
    	echo "Error: " . mysqli_connect_error() . PHP_EOL;
    }
    else // connection worked
    {
        return $con;
    }
}
?>

<?php
// -----------------------------------------------------------------------------
// adminOptimizeDatabaseTables.php
// This file acts as script in monoto to optimize the entire monoto database.
// -----------------------------------------------------------------------------

header('Content-type: text/xml');

session_start();

// check if the user-session is valid or not
if ( $_SESSION[ 'monoto' ][ 'valid' ] == 1 )
{
    require "../config/databaseConfig.php";
    require 'helperFunctions.php'; // to access writeNewLogentry

    // connect to mysql
    $con = new mysqli ( $databaseServer, $databaseUser, $databasePW, $databaseDB );
    if ( !$con )
    {
        die('Could not connect: ' . mysqli_connect_error());
    }

    // update m_log
    writeNewLogEntry("Database Optimizer", "Started optimizing database.");

    // select all table with (> 10% overhead) AND at (least > 100k free space)
    $res = mysqli_query($con, 'SHOW TABLE STATUS WHERE Data_free / Data_length > 0.1 AND Data_free > 102400');
    while($row = mysqli_fetch_assoc($res))
    {
        mysqli_query($con, 'OPTIMIZE TABLE ' . $row[ 'Name' ]);

        writeNewLogEntry("Database Optimizer", "Optimized table: " . $row[ 'Name' ]);
    }

    // update m_log
    writeNewLogEntry("Database Optimizer", "Finished optimizing database.");

    // close sql connection
    mysqli_close( $con );
}
?>

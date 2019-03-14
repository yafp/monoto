<?php
// -----------------------------------------------------------------------------
// noteExport.php
// This file acts as script in monoto to export notes from a user.
// -----------------------------------------------------------------------------

// prevent direct call of this script
/*
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) )
{
    // Up to you which header to send, some prefer 404 even if
    // the files does exist for security
    header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );

    // choose the appropriate page to redirect users
    die( header( 'location: ../404.php' ) );
}
*/


session_start();

// check if the user-session is valid or not
if ( $_SESSION[ 'monoto' ][ 'valid' ] == 1 )
{
    require '../config/config.php';

    $owner = $_SESSION[ 'monoto' ][ 'username' ];

    // database table
    $db_record = 'm_notes';

    // optional where query
    $where = "WHERE owner='".$owner."'";

    // filename for export
    $exportDate = date("Ymd-his");
    $csv_filename = $exportDate."-monoto-export.csv";

    // connect to db
    $con = new mysqli ( $databaseServer, $databaseUser, $databasePW, $databaseDB );
    if ( mysqli_connect_errno () )
    {
        die("Failed to connect to MySQL: " . mysqli_connect_error());
    }

    // create empty variable to be filled with export data
    $csv_export = "";

    // query to get data from database
    $query = mysqli_query($con, "SELECT * FROM ".$db_record." ".$where);
    $field = mysqli_field_count($con);

    // create line with field names
    for ( $i = 0; $i < $field; $i++ )
    {
        //$csv_export.= mysqli_fetch_field_direct($query, $i)->name.';';
    }

    // newline after header (seems to work both on Linux & Windows servers)
    //$csv_export.= '
    //';

    // loop through database query and fill export variable
    while ( $row = mysqli_fetch_array ( $query ) )
    {
        // create line with field values
        for ( $i = 0; $i < $field; $i++ )
        {
            $csv_export.= '"'.$row[mysqli_fetch_field_direct($query, $i)->name].'";';
        }
        $csv_export.= '
    ';
    }

    // Export the data and prompt a csv file for download
    header("Content-type: text/x-csv");
    header("Content-Disposition: attachment; filename=".$csv_filename."");
    echo($csv_export);
}

?>

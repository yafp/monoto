<?php
// -----------------------------------------------------------------------------
// profileGetAllUserEvents.php
// Fetches all user accounts - server-side processing
// -----------------------------------------------------------------------------

session_start();

// check if the user-session is valid or not
if ( $_SESSION[ 'monoto' ][ 'valid' ] == 1 )
{
    // for database informations
    require '../config/databaseConfig.php';

    // for datatable server-side-processing
    require 'ssp.class.php';

    // current username
    $owner = $_SESSION[ 'monoto' ][ 'username' ];

    // build wqhere clause for data query
    $where = "owner = '".$owner."'";

    // DB table to use
    $table = 'm_log';

    // Table's primary key
    $primaryKey = 'id';

    // Array of database columns which should be read and sent back to DataTables.
    // The `db` parameter represents the column name in the database, while the `dt`
    // parameter represents the DataTables column identifier. In this case simple
    // indexes
    $columns = array(
        array( 'db' => 'id', 'dt' => 0 ),
        array( 'db' => 'event', 'dt' => 1 ),
        array( 'db' => 'details', 'dt' => 2 ),
        array( 'db' => 'activity_date', 'dt' => 3 )
    );

    // SQL server connection information
    $sql_details = array(
        'user' => $databaseUser,
        'pass' => $databasePW,
        'db'   => $databaseDB,
        'host' => $databaseServer
    );

    echo json_encode(

        // simple
        //SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )

        // complex with where
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, null, $where )
    );
}

?>

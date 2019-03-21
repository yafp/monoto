<?php
// -----------------------------------------------------------------------------
// adminGetAllUserAccounts.php
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

    // DB table to use
    $table = 'm_users';

    // Table's primary key
    $primaryKey = 'id';

    // Array of database columns which should be read and sent back to DataTables.
    // The `db` parameter represents the column name in the database, while the `dt`
    // parameter represents the DataTables column identifier. In this case simple
    // indexes
    $columns = array(
        array( 'db' => 'id', 'dt' => 0 ),
        array( 'db' => 'username', 'dt' => 1 ),
        array( 'db' => 'login_counter', 'dt' => 2 ),
        array( 'db' => 'failed_logins_in_a_row', 'dt' => 3 ),
        array( 'db' => 'email', 'dt' => 4 ),
        array( 'db' => 'is_admin', 'dt' => 5 ),
        array( 'db' => 'admin_note', 'dt' => 6 )
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
        SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
    );
}

?>

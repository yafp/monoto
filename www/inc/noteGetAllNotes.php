<?php
// -----------------------------------------------------------------------------
// noteGetAllNotes.php
// Fetches all notes from a single user - server-side processing
// -----------------------------------------------------------------------------


/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
session_start();

// check if the user-session is valid or not
if ( $_SESSION[ 'monoto' ][ 'valid' ] == 1 )
{
    // for database informations
    require '../config/config.php';

    // for datatable server-side-processing
    require 'ssp.class.php';

    // current username
    $owner = $_SESSION[ 'monoto' ][ 'username' ];

    // build wqhere clause for data query
    $where = "owner = '".$owner."'";

    // DB table to use
    $table = 'm_notes';

    // Table's primary key
    $primaryKey = 'id';

    // Array of database columns which should be read and sent back to DataTables.
    // The `db` parameter represents the column name in the database, while the `dt`
    // parameter represents the DataTables column identifier. In this case simple
    // indexes
    $columns = array(
        array( 'db' => 'id', 'dt' => 0 ),
        array( 'db' => 'title', 'dt' => 1 ),
        array( 'db' => 'content', 'dt' => 2 ),
        array( 'db' => 'date_mod', 'dt' => 3 ),
        array( 'db' => 'date_create', 'dt' => 4 ),
        array( 'db' => 'save_count', 'dt' => 5 ),
        array( 'db' => 'owner', 'dt' => 6 )
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

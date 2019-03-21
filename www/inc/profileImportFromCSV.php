<?php
// -----------------------------------------------------------------------------
// profileDeleteMyUserNotes.php
// This file acts as script in monoto to delete all notes of a single user.
// -----------------------------------------------------------------------------


// TODO:
// - this is not in use so far

header('Content-type: text/xml');

session_start();

// check if the user-session is valid or not
if ( $_SESSION[ 'monoto' ][ 'valid' ] == 1 )
{
    require '../config/databaseConfig.php';
    require 'helperFunctions.php'; // to access writeNewLogentry


    $importCSV = $_POST['importCSV'];

    $username = $_SESSION[ 'monoto' ][ 'username' ];


    // connect to mysql
    $con = new mysqli ( $databaseServer, $databaseUser, $databasePW, $databaseDB );
    if ( !$con )
    {
        die('Could not connect: ' . mysqli_connect_error());
    }


    /*
    if(($handle = fopen($importCSV, "r")) !== FALSE)
    {
        echo "<hr>";
        set_time_limit(0);
        $row = 0;
        while(($data = fgetcsv($handle, 1000, ";")) !== FALSE)
        {
            $col_count = count($data); // number of fields in the csv

            // get the values from the csv
            $csv[$row]['col1'] = $data[0]; // id
            $csv[$row]['col2'] = $data[1]; // title
            $csv[$row]['col3'] = $data[2]; // content

            $newNoteTitle = $data[1];
            $newNoteContent = $data[2];

            // check if the new title is in use already by this user
            $sql = "SELECT title from m_notes where owner='".$username."' AND  title='".$newNoteTitle."' ";
            $result = mysqli_query($con, $sql);
            if ( mysqli_num_rows($result) > 0 )
            {
                // adjust Title
                $current_timestamp = date('Ymd-his');
                $newNoteTitle = $newNoteTitle."___".$current_timestamp;
            }

            // create single note
            $sql = "INSERT INTO m_notes (title, content, date_create, date_mod, owner, save_count) VALUES ('$newNoteTitle', '$newNoteContent', now(), now(), '$username', '1' )";
            $result = mysqli_query($con, $sql);
            if ( !$result )
            {
                die('Error: ' . mysqli_connect_error()); // display error output
            }
            else
            {
                // write text to textarea
                echo '<script type="text/javascript">$("#importLogCSV").append("Imported: '.$newNoteTitle.'.\n"); </script>';
            }
            // inc the row
            $row++;
        }
        fclose($handle);
    }
    else
    {
        displayNoty("Unable to open the file.", "error");
    }
    */







    // update m_log
    //writeNewLogEntry("Eraser user notes", "All user notes deleted with eraser.");

    // close sql connection
    mysqli_close( $con );
}
?>

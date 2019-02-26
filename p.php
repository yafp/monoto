<?php include 'inc/checkSession.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'inc/coreIncludes.php'; ?>

    <!-- specific -->
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/monoto/profile.css" title="default" />
    <!-- JS -->
    <script type="text/javascript" src="js/monoto/profile.js"></script>
    <script type="text/javascript" charset="utf-8">
    $(document).ready( function ()
    {
        // init
        $('#myDataTable').DataTable( {
            "bSort": false, // dont sort - trust the sql-select and its sort-order
            "sPaginationType": "simple_numbers",
            "iDisplayLength" : 10,
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
        } );


        console.log("p.php ::: Finished intializing DataTable");

        // #281
        // compare input in password fields
        // and enable or disable the 'update password' button
        $('#newPassword, #newPasswordConfirm').on('keyup', function ()
        {
            validatePasswordChangeInput();
        });

    } );
    </script>
</head>

<body role="document">

    <!-- Navigation -->
    <?php include 'inc/navigation.php'; ?>

    <!-- Page Content -->
    <div class="container theme-showcase" role="main">
        <div id="container">

            <!-- Sub-Navigation -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"><a class="active nav-link" href="#account" role="tab" data-toggle="tab"><?php echo translateString("Account"); ?></a></li>
                <li class="nav-item"><a class="nav-link" href="#statistics" role="tab" data-toggle="tab"><?php echo translateString("Statistics"); ?></a></li>
                <li class="nav-item"><a class="nav-link" href="#activity" role="tab" data-toggle="tab"><?php echo translateString("Activity Log"); ?></a> </li>
                <li class="nav-item"><a class="nav-link" href="#importer_t" role="tab" data-toggle="tab"><?php echo translateString("Importer (Textfiles)"); ?></a> </li>
                <li class="nav-item"><a class="nav-link" href="#importer_c" role="tab" data-toggle="tab"><?php echo translateString("Importer (.csv)"); ?></a></li>
                <li class="nav-item"><a class="nav-link" href="#exporter_c" role="tab" data-toggle="tab"><?php echo translateString("Exporter (.csv)"); ?></a></li>
                <li class="nav-item"><a class="nav-link" href="#eraser" role="tab" data-toggle="tab"><?php echo translateString("Eraser"); ?></a></li>
            </ul>
            <!-- /SubNavigation -->

            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Tab: Account -->
                <div role="tabpanel" class="tab-pane active in" id="account">
                    <h3><i class="fas fa-address-card"></i> <?php echo translateString("Account"); ?></h3>
                    <?php
                    // get email for this account
                    $con = connectToDB();
                    $sql = "SELECT email FROM m_users WHERE username='".$_SESSION[ 'monoto' ][ 'username']."' "; // mail
                    $result = mysqli_query($con, $sql);
                    while ( $row = mysqli_fetch_array ( $result) )
                    {
                        $account_email = $row[ 0 ];
                    }

                    // get amounf of logins
                    $sql = "SELECT login_counter FROM m_users WHERE username='".$_SESSION[ 'monoto' ][ 'username' ]."' "; // login_counter
                    $result = mysqli_query($con, $sql);
                    while ( $row = mysqli_fetch_array ( $result ) )
                    {
                        $account_logins = $row[ 0 ];
                    }

                    // since:
                    $sql = "SELECT date_first_login FROM m_users WHERE username='".$_SESSION[ 'monoto' ][ 'username' ]."' "; // date first login
                    $result = mysqli_query($con, $sql);
                    while ( $row = mysqli_fetch_array ( $result ) )
                    {
                        $account_since = $row[ 0 ];
                    }

                    ?>

                    <!-- username -->
                    <div class="row">
                        <div class="col-sm">
                            <?php echo translateString( "username" ); ?>
                        </div>
                        <div class="col-sm">
                            <?php echo "<span class='badge badge-secondary'>".$_SESSION[ 'monoto' ][ 'username' ]."</span>" ?>
                        </div>
                        <div class="col-sm">
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="row">
                        <div class="col-sm">
                            <?php echo translateString( "mail" ); ?>
                        </div>
                        <div class="col-sm">
                            <?php echo "<span class='badge badge-secondary'>".$account_email."</span>" ?>
                        </div>
                        <div class="col-sm">
                        </div>
                    </div>

                    <!-- Logins -->
                    <div class="row">
                        <div class="col-sm">
                            <?php echo translateString( "logins" ); ?>
                        </div>
                        <div class="col-sm">
                            <?php echo "<span class='badge badge-secondary'>".$account_logins."</span>" ?>
                        </div>
                        <div class="col-sm">
                        </div>
                    </div>

                    <!-- since -->
                    <div class="row">
                        <div class="col-sm">
                            <?php echo translateString( "since" ); ?>
                        </div>
                        <div class="col-sm">
                            <?php echo "<span class='badge badge-secondary'>".$account_since."</span>" ?>
                        </div>
                        <div class="col-sm">
                        </div>
                    </div>

                    <!-- spacer -->
                    <div class="row">&nbsp;</div>

                    <!-- Password change -->
                    <div class="row">
                        <div class="col-sm">
                            <b><?php echo translateString( "Changing password" ); ?></b><br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm">
                            <form id="changePassword" name="changePassword" action="p.php" method="post" enctype="multipart/form-data">
                                <input type="password" id="newPassword" name="newPassword" pattern=".{8,}" placeholder="Password (min 8 chars)" onkeyup="passwordStrength()" required="required" autocomplete="off" />
                                <input type="password" id="newPasswordConfirm" name="newPasswordConfirm" pattern=".{8,}" placeholder="Confirm new password" required="required" autocomplete="off" />
                                <span id="passwordDiff"></span>
                                <button type="submit" class="btn btn-primary buttonDefault" id="bt_continue" name="bt_continue"  title="Starts the change password function if the user provided the new password twice." disabled=disabled><i class="fas fa-save"></i> <?php echo translateString("update"); ?></button>
                                <span id="passstrength"></span>
                            </form>
                        </div>
                    </div>

                    <!-- spacer -->
                    <div class="row">&nbsp;</div>

                    <!-- Language change -->
                    <div class="row">
                        <div class="col-sm">
                            <b><?php echo translateString( "Language" ); ?></b>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm">
                            <form id="changeLanguage" action="p.php" method="post" enctype="multipart/form-data">
                                <select name="s_languageSelector" id="s_languageSelector">
                                    <option value="de_DE.UTF-8">de_DE.UTF-8</option>
                                    <option value="en_US">en_US</option>
                                </select>
                                <button type="submit" class="btn btn-primary buttonDefault" name="doChangeUserLanguage" title="Starts the change language function if the user provided the new language selection."><i class="fas fa-save"></i> <?php echo translateString("update"); ?></button>
                            </form>
                        </div>
                    </div>
                </div> <!-- /tab: account -->

                <!-- Tab: Statistics -->
                <div role="tabpanel" class="tab-pane fade" id="statistics">
                    <h3><i class="fas fa-brain"></i> <?php echo translateString( "Statistics" ); ?></h3>
                    <?php
                    $con = connectToDB();
                    $username = $_SESSION[ 'monoto' ][ 'username' ];
                    // User: amount of notes
                    $result = mysqli_query ( $con, "SELECT count(*) FROM m_notes WHERE owner='".$username."' "); // run the mysql query
                    while ( $row = mysqli_fetch_array ( $result ) ) // fetch data and file table as a second step later on
                    {
                        echo "<ul>";

                        // If current User < 1 note - is it worth displaying the stats at all?
                        if ( $row[ 0 ] == 0 )
                        {
                            echo "<li>Lazy ass award goes to you as you havent created a single note .....erm yes ... ".$row[0]." notes in your monoto database.</li>";     // blame user that he has no notes
                        }
                        else
                        {
                            echo "<li>You have <span class='badge badge-secondary'>".$row[0]." </span> personal notes</li>"; // output amount of notes

                            // amount of activity-events
                            $result = mysqli_query( $con, "SELECT count(*) FROM m_log WHERE owner='".$username."' ");
                            while ( $row = mysqli_fetch_array ( $result ) )
                            {
                                $stats_events_of_current_user = $row[ 0 ];
                            }

                            // amount of create-events
                            $result = mysqli_query( $con, "SELECT count(*) FROM m_log WHERE event='create' and owner='".$username."' ");
                            while ( $row = mysqli_fetch_array ( $result ) )
                            {
                                $stats_amount_of_creates = $row[ 0 ];
                            }

                            // amount of create-error events
                            $result = mysqli_query( $con, "SELECT count(*) FROM m_log WHERE event='create error' and owner='".$username."' ");
                            while ( $row = mysqli_fetch_array ( $result ) )
                            {
                                $stats_amount_of_creates_errors = $row[ 0 ];
                            }

                            // amount of import-events
                            $result = mysqli_query( $con, "SELECT count(*) FROM m_log WHERE event='import' and owner='".$username."' ");
                            while ( $row = mysqli_fetch_array ( $result ) )
                            {
                                $stats_amount_of_imports = $row[ 0 ];
                            }

                            // amount of edits-events
                            $result = mysqli_query( $con, "SELECT count(*) FROM m_log WHERE event='save' and owner='".$username."' ");
                            while ( $row = mysqli_fetch_array ( $result ) )
                            {
                                $stats_amount_of_changes = $row[ 0 ];
                            }

                            // amount of delete-events
                            $result = mysqli_query($con, "SELECT count(*) FROM m_log WHERE event='delete' and owner='".$username."' ");
                            while ( $row = mysqli_fetch_array ( $result ) )
                            {
                                $stats_amount_of_deletes = $row[ 0 ];
                            }

                            // amount of logins and logouts
                            $result = mysqli_query($con, "SELECT login_counter, logout_counter FROM m_users WHERE username='".$_SESSION[ 'monoto' ][ 'username' ]."' ");
                            while ( $row = mysqli_fetch_array ( $result ) )
                            {
                                $stats_amount_of_logins = $row[ 0 ];
                                $stats_amount_of_logouts = $row[ 1 ];
                            }

                            // version: highest note-version (most used note)
                            $result = mysqli_query($con, "SELECT id, title, save_count FROM m_notes WHERE owner='".$username."' ORDER BY save_count DESC LIMIT 1");
                            while ( $row = mysqli_fetch_array ( $result ) )
                            {
                                $stats_highest_note_version_id = $row[ 0 ];
                                $stats_highest_note_version_title = $row[ 1 ];
                                $stats_highest_note_version_versions = $row[ 2 ];
                            }

                            // shortest and longest note-content
                            $result = mysqli_query($con, "SELECT MIN( LENGTH( content ) ) AS shortest, id FROM m_notes WHERE owner='".$username."' GROUP BY(id) LIMIT 1");
                            while ( $row = mysqli_fetch_array ( $result ) )
                            {
                                $stats_note_with_shortest_content_id = $row[ 1 ];
                                $stats_note_with_shortest_content_chars = $row[ 0 ];

                            }

                            // longest note-content
                            $result = mysqli_query($con, "SELECT ( LENGTH( content ) ) AS longest, id FROM m_notes WHERE owner='".$username."' ORDER BY longest DESC LIMIT 1");
                            while ( $row = mysqli_fetch_array ( $result ) )
                            {
                                $stats_note_with_longest_content_chars = $row[ 0 ];
                                $stats_note_with_longest_content_id = $row[ 1 ];
                            }

                            // oldest created note
                            $result = mysqli_query($con, "SELECT DATEDIFF(CURDATE(), date_create) AS intval, date_create, id, title FROM m_notes WHERE owner='".$username."' ORDER BY date_create ASC LIMIT 1");
                            while ( $row = mysqli_fetch_array ( $result ) )
                            {
                                $stats_oldest_created_note_age = $row[ 0 ];
                                $stats_oldest_created_note_date = $row[ 1 ];
                                $stats_oldest_created_note_id = $row[ 2 ];
                            }
                            // newest/latest created note
                            $result = mysqli_query($con, "SELECT DATEDIFF(CURDATE(), date_create) AS intval, date_create, save_count, title, id FROM m_notes WHERE save_count = '1' and owner='".$username."' ORDER BY date_create DESC LIMIT 1");
                            while ( $row = mysqli_fetch_array ( $result ) )
                            {
                                $stats_latest_created_note_age = $row[ 0 ];
                                $stats_latest_created_note_date =  $row[ 1 ];
                                $stats_latest_created_note_id = $row[ 4 ];
                                $stats_latest_created_note_title = $row[ 3 ];
                            }

                            // latest edited note
                            $result = mysqli_query($con, "SELECT DATEDIFF(CURDATE(), date_create) AS intval, date_mod, save_count, title, id FROM m_notes ORDER BY date_create DESC LIMIT 1");
                            while($row = mysqli_fetch_array($result))
                            {
                                $stats_last_edited_note_age = $row[ 0 ];
                                $stats_last_edited_note_date = $row[ 1 ];
                                $stats_last_edited_note_title = $row[ 3 ];
                                $stats_last_edited_note_id = $row[ 4 ];
                            }

                            // overall_note_content_words
                            $result = mysqli_query($con, "SELECT SUM( LENGTH( content ) - LENGTH( REPLACE( content, ' ', '' ) ) +1 ) FROM m_notes WHERE owner='".$username."' ");
                            while ( $row = mysqli_fetch_array ( $result ) )
                            {
                                $stats_overall_content_words = $row[ 0 ];
                            }

                            // overall_note_title_words
                            $result = mysqli_query($con, "SELECT SUM( LENGTH( title ) - LENGTH( REPLACE( title, ' ', '' ) ) +1 ) FROM m_notes WHERE owner='".$username."' ");
                            while ( $row = mysqli_fetch_array ( $result ) )
                            {
                                $stats_overall_title_words = $row[ 0 ];
                            }

                            echo "<li>Those notes are using overall <span class='badge badge-secondary'>".$stats_overall_title_words."</span> words for titles and overall <span class='badge badge-secondary'>".$stats_overall_content_words."</span> words for the content.</li>";
                            echo "<li>Your event log contains <span class='badge badge-secondary'>".$stats_events_of_current_user." events</span> right now.</li>";
                            echo "<li>Those can be devided into <span class='badge badge-secondary'>".$stats_amount_of_creates."</span> notes creations, <span class='badge badge-secondary'>".$stats_amount_of_changes."</span> note-editings and <span class='badge badge-secondary'>".$stats_amount_of_deletes." </span> notes-deletions.</li>";
                            echo "<li>In addition to those numbers your account has <span class='badge badge-secondary'>".$stats_amount_of_imports." note-import events</span> logged. But keep in mind that 1 import event can contain more then 1 note.</li>";
                            echo "<li>Plus <span class='badge badge-secondary'>".$stats_amount_of_creates_errors."</span> failed create errors.</li>";
                            echo "<li>Well in case numbers still dont match up - add <span class='badge badge-secondary'>".$stats_amount_of_logins." logins</span> and <span class='badge badge-secondary'>".$stats_amount_of_logouts." logouts</span>.</li>";
                            echo "<li>Your note id <span class='badge badge-secondary'>".$stats_highest_note_version_id."</span>, with the title <span class='badge badge-secondary'>".$stats_highest_note_version_title."</span> has the highest revision number <span class='badge badge-secondary'>".$stats_highest_note_version_versions."</span>.</li>";
                            echo "<li>Your shortest note so far is note number <span class='badge badge-secondary'>".$stats_note_with_shortest_content_id."</span>, it is <span class='badge badge-secondary'>using ".$stats_note_with_shortest_content_chars." chars</span> for its entire content.</li>";
                            echo "<li>Lets compare that with your longest note which has the <span class='badge badge-secondary'>id ".$stats_note_with_longest_content_id."</span> and is <span class='badge badge-secondary'>".$stats_note_with_longest_content_chars." long</span>.</li>";
                            echo "<li>Looking for dates? Let's face it: your oldest note has an <span class='badge badge-secondary'>age of ".$stats_oldest_created_note_age." days</span>. It was created <span class='badge badge-secondary'>".$stats_oldest_created_note_date."</span> with the <span class='badge badge-secondary'>id ".$stats_oldest_created_note_id."</span>.</li>";
                            echo "<li>In comparison - your latest created note has the <span class='badge badge-secondary'>age of ".$stats_latest_created_note_age." days</span>, has the <span class='badge badge-secondary'>id ".$stats_latest_created_note_id."</span>, the title <span class='badge badge-secondary'>".$stats_latest_created_note_title."</span> and a creation date of <span class='badge badge-secondary'>".$stats_latest_created_note_date."</span>.</li>";
                            echo "<li>The last note you actually edited was note <span class='badge badge-secondary'>".$stats_last_edited_note_id."</span> with the title <span class='badge badge-secondary'>".$stats_last_edited_note_title."</span>. This edit is <span class='badge badge-secondary'>".$stats_last_edited_note_age." days</span> old - from <span class='badge badge-secondary'>".$stats_last_edited_note_date."</span>.</li>";                        }
                            echo "</ul>";
                        }
                        ?>
                </div> <!-- /tab: statistics -->

                <!-- Tab: activity log -->
                <div role="tabpanel" class="tab-pane fade" id="activity">
                        <h3><i class="fas fa-clipboard-list"></i> <?php echo translateString("Activity Log"); ?></h3>
                        <table cellpadding="0" cellspacing="0" class="display" id="myDataTable" style="width:100%">
                            <thead>
                                <tr><th>id</th><th>event</th><th>details</th><th>timestamp</th></tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = mysqli_query($con, "SELECT * FROM m_log WHERE owner='".$username."' ORDER BY activity_date DESC"); // m_log
                                while ( $row = mysqli_fetch_array ( $result ) )   // fill DataTable
                                {
                                    // colorize table
                                    switch ($row[ 1 ])
                                    {
                                        case "login":
                                            echo '<tr class="odd gradeU"><td>'.$row[0].'</td><td bgcolor="#3D9970">'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td></tr>';
                                            break;

                                        case "login error":
                                            echo '<tr class="odd gradeU"><td>'.$row[0].'</td><td bgcolor="#FF851B">'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td></tr>';
                                            break;

                                        case "save":
                                            echo '<tr class="odd gradeU"><td>'.$row[0].'</td><td bgcolor="#2ECC40">'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td></tr>';
                                            break;

                                        case "create":
                                            echo '<tr class="odd gradeU"><td>'.$row[0].'</td><td bgcolor="#01FF70">'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td></tr>';
                                            break;

                                        case "create error":
                                            echo '<tr class="odd gradeU"><td>'.$row[0].'</td><td bgcolor="#FF4136">'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td></tr>';
                                            break;

                                        case "import":
                                            echo '<tr class="odd gradeU"><td>'.$row[0].'</td><td bgcolor="#FFDC00">'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td></tr>';
                                            break;

                                        case "events eraser":
                                            echo '<tr class="odd gradeU"><td>'.$row[0].'</td><td bgcolor="#7FDBFF">'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td></tr>';
                                            break;

                                        case "notes eraser":
                                            echo '<tr class="odd gradeU"><td>'.$row[0].'</td><td bgcolor="#39CCCC ">'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td></tr>';
                                            break;

                                        default:
                                            echo '<tr class="odd gradeU"><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td></tr>';

                                        // colors: https://clrs.cc/
                                        //
                                        // used:
                                        // #7FDBFF = AQUA = events eraser
                                        // #FF851B = ORANGE = login error
                                        // #2ECC40 = GREEN = save
                                        // #01FF70 = LIME = create
                                        // #FF4136 = RED = create error (shouldnt happen anymore)
                                        // #FFDC00 = YELLOW = import
                                        // #39CCCC = TEAL = notes eraser
                                        // #3D9970 = OLIVE = login
                                        //
                                        // reserved:
                                        // #F012BE= FUCHSIA
                                    }
                                    //echo '<tr class="odd gradeU"><td>'.$row[0].'</td><td bgcolor="#FF0000">'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td></tr>';
                                }
                                ?>
                            </tbody>
                            <tfoot><tr><th>id</th><th>event</th><th>details</th><th>timestamp</th></tr></tfoot>
                        </table>
                </div> <!-- /tab: activity_log -->

                <!-- Tab: importer textfiles -->
                <div role="tabpanel" class="tab-pane fade" id="importer_t">
                        <h3><i class="fas fa-file-import"></i> <?php echo translateString("Importer (Textfiles)"); ?></h3>
                        <!-- IMPORTER - http://stackoverflow.com/questions/5593473/how-to-upload-and-parse-a-csv-file-in-php -->
                        <p><?php echo translateString("You can import plain-text files. Select a folder and press the 'Import' button."); ?></p>
                        <form action="p.php" method="post" enctype="multipart/form-data" name="importerFormT">
                            <input type="file" multiple="multiple " name="impFilesT[]" id="impFilesT[]" accept="text/plain" />
                            <br>
                            <button type="submit" class="btn btn-primary buttonDefault" name="doImport" id="doImport" title="Starts the import function if the user provided a valid selection of files. Might break with bigger amount of text-notes." ><i class="fas fa-file-import"></i> <?php echo translateString("import"); ?></button>
                            <textarea class="database" disabled="disabled" id="importLog" style="width:100%" name="importLog" cols="110" rows="5" placeholder="Output of importer will be displayed here"></textarea>
                        </form>
                </div> <!-- /tab: import textfiles -->

                <!-- Tab: importer csv -->
                <div role="tabpanel" class="tab-pane fade" id="importer_c">
                    <h3><i class="fas fa-file-import"></i> <?php echo translateString("Importer (.csv)"); ?></h3>
                    <p><?php echo translateString("You can import notes in .csv format (coming from the exporter)."); ?></p>
                    <form action="p.php" method="post" enctype="multipart/form-data" name="importerForm">
                        <input type="file" name="impFile" id="impFile" accept=".csv"/>
                        <br>
                        <button type="submit" class="btn btn-primary buttonDefault" name="doImportCSV" id="doImportCSV" title="Starts the import function if the user provided a valid .csv files. Might break with bigger amount of text-notes."><i class="fas fa-file-import"></i> <?php echo translateString("import"); ?></button>
                        <textarea class="database" disabled="disabled" id="importLogCSV" style="width:100%" name="importLogCSV" cols="110" rows="5" placeholder="Output of impoter will be displayed here."></textarea>
                        </form>
                        <span class="badge badge-secondary"><?php echo translateString("References"); ?></span>
                        <div class="alert alert-info" role="alert">
                            <?php echo translateString("You can select multiple files here at once."); ?>
                        </div>
                    </div> <!-- /tab: import csv -->

                    <!-- Tab: Exporter CSV -->
                    <div role="tabpanel" class="tab-pane fade" id="exporter_c">
                        <h3><i class="fas fa-file-export"></i> <?php echo translateString("Exporter (.csv)"); ?></h3>
                        <p><?php echo translateString("You can export your notes in .csv format by pressing the 'Export' button."); ?></p>
                        <form action="p.php" method="post" enctype="multipart/form-data">
                            <button type="submit" class="btn btn-primary buttonDefault" name="doExport" id="doExport"  title="Exports all your notes into a .csv file which might be useful" ><i class="fas fa-file-export"></i> <?php echo translateString("export"); ?></button>
                        </form>
                        <span class="badge badge-secondary"><?php echo translateString("References"); ?></span>
                        <div class="alert alert-info" role="alert">
                            <?php echo translateString("The export file is using semicolon (;) as separator."); ?>
                        </div>
                        <div class="alert alert-warning" role="alert">
                            <?php echo translateString("Active popup-blocker settings might prevent the download of the exported .csv file."); ?>
                        </div>
                    </div> <!-- /tab: exporter -->


                    <!-- Tab: Eraser -->
                    <div role="tabpanel" class="tab-pane fade" id="eraser">
                        <h3><i class="fas fa-eraser"></i> <?php echo translateString("Eraser"); ?></h3>
                        <p><?php echo translateString("You can delete your notes and events here. Keep in mind: there is no restore option."); ?></p>
                        <button type="button" class="btn btn-default btn-danger buttonDefault" title="Deletes all your user events from the db" name="delete" id="delete" onClick="deleteAllMyUserEvents();"><i class="fas fa-trash-alt"></i> <?php echo translateString("Delete events"); ?></button>
                        <br>
                        <button type="button" class="btn btn-default btn-danger buttonDefault" title="Deletes all your user notes from the db" name="delete" id="delete" onClick="deleteAllMyUserNotes();"><i class="fas fa-trash-alt"></i> <?php echo translateString("Delete notes"); ?></button>
                    </div> <!-- /tab: eraser -->

                </div> <!-- /tabs -->

                <!-- footer -->
                <?php require 'inc/footer.php'; ?>

            </div> <!-- /container -->


            <!-- JS-->
            <script type="text/javascript" charset="utf-8">
            $(document).ready(function() {
                var lang = '<?php echo $_SESSION[ 'monoto' ]["lang"]; ?>';
                $('#s_languageSelector').val(lang); // selects "Two"
            });
            </script>
        </body>
        </html>




        <?php

        if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' )
        {
            // CASES
            //
            // - Do Change Userpassword
            // - Do Change Language
            // - Do Import (Textfiles)
            // - Do Import (csv)
            // - Do Export (csv)
            // - Do Delete all Notes
            // - Do Delete all Events

            // -----------------------------------------------------------------------
            // bt_doChangeUserPW (START)
            // -----------------------------------------------------------------------
            if ( isset ( $_POST[ "bt_doChangeUserPW" ] ) )
            {
                // get values
                $username = $_SESSION[ 'monoto' ][ 'username' ];
                $newPassword = filter_input(INPUT_POST, "newPassword", FILTER_SANITIZE_STRING);
                $newPasswordConfirm = filter_input(INPUT_POST, "newPasswordConfirm", FILTER_SANITIZE_STRING);

                // Check if user entered two times the same new password
                if ( $newPassword == $newPasswordConfirm )
                {
                    $hash = hash('sha256', $newPassword); // playing with hash
                    function createSalt() // playing with salt - creates a 3 character sequence
                    {
                        $string = md5(uniqid(rand(), true));
                        return substr($string, 0, 3);
                    }
                    $salt = createSalt();
                    $hash = hash('sha256', $salt . $hash);

                    // update user password
                    $query = "UPDATE m_users SET  password='$hash', salt='$salt' WHERE username='$username'";
                    mysqli_query ( $con, $query );

                    displayNoty('Changed password','success');
                }
                else // User entered 2 different password - cant change pw like that.
                {
                    displayNoty('Password mismatch','error');
                }
            }



            // -----------------------------------------------------------------------
            // doChangeUserLanguage (START)
            // -----------------------------------------------------------------------
            if ( isset($_POST[ "doChangeUserLanguage" ] ) )
            {
                $selectedLang= filter_input(INPUT_POST, "s_languageSelector", FILTER_SANITIZE_STRING);

                // update users language setting
                $query = "UPDATE m_users SET language='$selectedLang' WHERE username='$username'";
                mysqli_query($con, $query);

                $_SESSION[ 'monoto' ][ 'lang' ] = $selectedLang; // store as session variable
                displayNoty('Language set to: '.$selectedLang,'notification');
            }


            // -----------------------------------------------------------------------
            // doImport Textfiles (START)
            // -----------------------------------------------------------------------
            //
            if ( isset($_POST[ "doImport" ] ) )
            {
                require 'config/config.php';

                ?>

                <script>
                /* jump back to importer_c tab */
                $('[href="#importer_t"]').tab('show');
                </script>

                <?php

                $username = $_SESSION[ 'monoto' ][ 'username' ];

                $con = new mysqli($databaseServer, $databaseUser, $databasePW, $databaseDB);
                if ( !$con )
                {
                    die('Could not connect: ' . mysqli_connect_error());
                }

                $total = count($_FILES['impFilesT']['name']);
                echo "Amount of files: " . $total;

                // Loop through each file
                for ( $i=0; $i < $total; $i++ )
                {
                    //Get the temp file path
                    $tmpFilePath = $_FILES['impFilesT']['tmp_name'][$i];

                    //Make sure we have a file path
                    if ( $tmpFilePath != "" )
                    {
                        // define insert vars
                        $newNoteTitle = $_FILES["impFilesT"]["name"][$i];
                        $newNoteTitle = preg_replace("/\\.[^.\\s]{3,4}$/", "", $newNoteTitle); // we need to cut the extension from filename - ugly hack
                        $newNoteContent = file_get_contents($_FILES['impFilesT']['tmp_name'][$i]);

                        // check if the new title is in use already by this user - if so modify the title
                        $sql = "SELECT title from m_notes where owner='".$username."' AND  title='".$newNoteTitle."' ";
                        $result = mysqli_query($con, $sql);
                        if( mysqli_num_rows ( $result ) > 0 )
                        {
                            // adjust Title
                            $current_timestamp = date('Ymd-his');
                            $newNoteTitle = $newNoteTitle."___".$current_timestamp;
                        }

                        // do create note and do log it - aka: insert into m_notes
                        $sql = "INSERT INTO m_notes (title, content, date_create, date_mod, owner, save_count) VALUES ('$newNoteTitle', '$newNoteContent', now(), now(), '$username', '1' )";
                        $result = mysqli_query($con, $sql);
                        if ( !$result )
                        {
                            die('Error: ' . mysqli_connect_error()); // display error output
                        }
                        else // update m_log
                        {
                            $event = "create";
                            $details = "Note: <b>".$newNoteTitle."</b>";
                            $sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event','$details', now(), '$username' )";
                            $result = mysqli_query($con, $sql);

                            ?>
                            <script type="text/javascript">
                                var newtext = '<?php echo "Note: ".$newNoteTitle." successfully imported. "; ?>';
                                document.importerFormT.importLog.value += newtext;
                            </script>
                            <?php
                        }
                    }
                }
            }



            // -----------------------------------------------------------------------
            // doImportCSV (START)
            // -----------------------------------------------------------------------
            if ( isset( $_POST[ "doImportCSV" ] ) )
            {
                ?>

                <script>
                /* jump back to importer_c tab */
                $('[href="#importer_c"]').tab('show');
                </script>

                <?php

                if ( is_uploaded_file($_FILES['impFile']['tmp_name']))
                {
                    $con = new mysqli($databaseServer, $databaseUser, $databasePW, $databaseDB);
                    if ( !$con )
                    {
                        die('Could not connect: ' . mysqli_connect_error());
                    }

                    $username = $_SESSION[ 'monoto' ][ 'username' ];
                    $target_dir = "";
                    $target_file = $target_dir . basename($_FILES["impFile"]["tmp_name"]);
                    $uploadOk = 1;

                    // read linewise and import if note doesnt exist already
                    if(($handle = fopen($_FILES["impFile"]["tmp_name"], "r")) !== FALSE)
                    {
                        echo "<hr>";
                        set_time_limit(0);
                        $row = 0;
                        while(($data = fgetcsv($handle, 1000, ";")) !== FALSE)
                        //while(($data = fgetcsv($handle, ";")) !== FALSE)
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
                        displayNoty("Unable to open the file.","error");
                    }

                    // write text to textarea
                    echo '<script type="text/javascript">$("#importLogCSV").append("\n\nFinished importing notes.");</script>';

                }
                else
                {
                    displayNoty("No file selected for import.","error");
                }
            }



            // -----------------------------------------------------------------------
            // doExport (START)
            // -----------------------------------------------------------------------
            if ( isset ($_POST[ "doExport" ] ) )
            {
                echo '<script type="text/javascript" language="javascript">window.open("inc/noteExport.php", "width=400,height=500,top=50,left=280,resizable,toolbar,scrollbars,menubar,");</script>';
            }

        } // End: Check if request method was POST
?>

<?php include 'inc/checkSession.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'inc/genericIncludes.php'; ?>

    <!-- specific -->
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/monoto/profile.css" title="default" />
    <!-- JS -->
    <script type="text/javascript" src="js/monoto/profile.js"></script>
    <script type="text/javascript" charset="utf-8">
    $(document).ready( function ()
    {
        initProfileEventsDataTable();

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
    <?php include 'inc/genericNavigation.php'; ?>

    <!-- Page Content -->
    <div class="container theme-showcase" role="main">
        <div id="container">

            <!-- Sub-Navigation -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"><a class="active nav-link" href="#account" role="tab" data-toggle="tab"><?php echo translateString("Account"); ?></a></li>
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
                    $con = connectToDatabase();
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
                    <!-- /Logins -->

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
                    <!-- /since -->

                    <!-- spacer -->
                    <div class="row">&nbsp;</div>

                    <!-- Password change -->
                    <div class="row">
                        <div class="col-sm">
                            <b><i class="fas fa-key"></i>&nbsp;<?php echo translateString( "Changing password" ); ?></b><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <input type="password" id="newPassword" name="newPassword" pattern=".{8,}" placeholder="Password (min 8 chars)" onkeyup="calculatePasswordStrength()" required="required" autocomplete="off" />
                            <input type="password" id="newPasswordConfirm" name="newPasswordConfirm" pattern=".{8,}" placeholder="Confirm new password" required="required" autocomplete="off" />
                            <span id="passwordDiff"></span>
                            <button type="submit" class="btn btn-primary buttonDefault" id="bt_continue" name="bt_continue" onClick="doChangeProfilePassword();" title="Starts the change password function if the user provided the new password twice." disabled=disabled><i class="fas fa-save"></i> <?php echo translateString("update"); ?></button>
                            <span id="passstrength"></span>
                        </div>
                    </div>
                    <!-- /Password change -->

                    <!-- spacer -->
                    <div class="row">&nbsp;</div>

                    <!-- Language change -->
                    <div class="row">
                        <div class="col-sm">
                            <b><i class="fas fa-globe-europe"></i>&nbsp;<?php echo translateString( "Language" ); ?></b>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm">
                                <select class="selectpicker" name="s_languageSelector" id="s_languageSelector" onChange="enableUpdateUserProfileLanguageButton();">
                                    <option value="de_DE.UTF-8">de_DE.UTF-8</option>
                                    <option value="en_US">en_US</option>
                                </select>
                                <button type="submit" class="btn btn-primary buttonDefault" id="doChangeUserLanguage" name="doChangeUserLanguage" onClick="doChangeProfileLanguage();" disabled="disabled" title="Starts the change language function if the user provided the new language selection."><i class="fas fa-save"></i> <?php echo translateString("update"); ?></button>
                        </div>
                    </div>
                    <!-- /Language change -->

                </div> <!-- /tab: account -->

                <!-- Tab: activity log -->
                <div role="tabpanel" class="tab-pane fade" id="activity">
                        <h3><i class="fas fa-clipboard-list"></i> <?php echo translateString("Activity Log"); ?></h3>
                        <table cellpadding="0" cellspacing="0" class="display" id="myEventsDataTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th><?php echo translateString("ID"); ?></th>
                                    <th><?php echo translateString("Type"); ?></th>
                                    <th><?php echo translateString("Message"); ?></th>
                                    <th><?php echo translateString("Date"); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- gets filled via inc/profileGetAllUserEvents.php -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th><?php echo translateString("ID"); ?></th>
                                    <th><?php echo translateString("Type"); ?></th>
                                    <th><?php echo translateString("Message"); ?></th>
                                    <th><?php echo translateString("Date"); ?></th>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- show all known colors -->
                        <div class="boxes m_greenLight" title="Note creation"></div> <!-- create -->
                        <div class="boxes m_orangeLight" title="Note delete"></div> <!-- delete -->
                        <div class="boxes m_yellowLight" title="Note update"></div> <!-- save -->
                        <div class="boxes m_blueLight" title="Login"></div> <!-- login -->
                        <div class="boxes m_blueDark" title="Logout"></div> <!-- logout -->
                        <div class="boxes m_redLight" title="Login error"></div> <!-- login_error -->
                        <div class="boxes m_pinkLight" title="Eraser user events"></div> <!-- events eraser -->
                        <div class="boxes m_pinkDark" title="Eraser user notes"></div> <!-- notes eraser -->
                        <div class="boxes m_orangeDark" title="Eraser user account"></div> <!-- account eraser -->
                        <div class="boxes m_greenDark" title="Database Optimizer"></div> <!-- admin: database optimize-->
                        <div class="boxes m_redDark" title="Undefined"></div> <!-- Undefined -->

                </div> <!-- /tab: activity_log -->

                <!-- Tab: importer textfiles -->
                <div role="tabpanel" class="tab-pane fade" id="importer_t">
                        <h3><i class="fas fa-file-import"></i> <?php echo translateString("Importer (Textfiles)"); ?></h3>
                        <!-- IMPORTER - http://stackoverflow.com/questions/5593473/how-to-upload-and-parse-a-csv-file-in-php -->
                        <p><?php echo translateString("You can import plain-text files. Select a folder and press the 'Import' button."); ?></p>
                        <form action="profile.php" method="post" enctype="multipart/form-data" name="importerFormT">
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

                    <form action="profile.php" method="post" enctype="multipart/form-data" name="importerForm">

                        <input type="file" name="impFile" id="impFile" onChange="toggleImportNotesFromCSVButton();" accept=".csv" />
                        <br>
                        <button type="submit" class="btn btn-primary buttonDefault" name="doImportCSV" id="doImportCSV" disabled="disabled" title="Starts the import function if the user provided a valid .csv files. Might break with bigger amount of text-notes."><i class="fas fa-file-import"></i> <?php echo translateString("import"); ?></button>
                        <textarea class="database" disabled="disabled" id="importLogCSV" style="width:100%" name="importLogCSV" cols="110" rows="5" placeholder="Output of impoter will be displayed here."></textarea>

                        <!-- progress bar for csv upload -->
                        <!--
                        <div id="progress-wrp">
                            <div class="progress-bar"></div>
                            <div class="status">0%</div>
                        </div>
                        -->

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
                        <button type="submit" class="btn btn-primary buttonDefault" name="doExport" id="doExport" onClick="exportAllNotesFromUserAccount();"  title="Exports all your notes into a .csv file which might be useful" ><i class="fas fa-file-export"></i> <?php echo translateString("export"); ?></button>
                        <br>
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
                        <button type="button" class="btn btn-default btn-warning buttonDefault" title="Deletes all your user events from the db" name="delete" id="delete" onClick="deleteAllMyUserEvents();"><i class="fas fa-trash-alt"></i> <?php echo translateString("Delete events"); ?></button>
                        <br>
                        <button type="button" class="btn btn-default btn-danger buttonDefault" title="Deletes all your user notes from the db" name="delete" id="delete" onClick="deleteAllMyUserNotes();"><i class="fas fa-trash-alt"></i> <?php echo translateString("Delete notes"); ?></button>
                    </div> <!-- /tab: eraser -->

                </div> <!-- /tabs -->

                <!-- footer -->
                <?php require 'inc/genericFooter.php'; ?>

            </div> <!-- /container -->


            <!-- JS-->
            <script type="text/javascript" charset="utf-8">
            $(document).ready(function()
            {
                // init the language select
                var lang = '<?php echo $_SESSION[ 'monoto' ]["lang"]; ?>';
                $('#s_languageSelector').val(lang);

                onProfilePageReady();
            });
            </script>
</body>
</html>


<?php

    if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' )
    {
        // CASES
        //
        // - Do Import (Textfiles)
        // - Do Import (csv)
        //
        // TODO:
        // ajax them all

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
                            writeNewLogEntry("Note creation", "Note: <b>".$newNoteTitle."</b> created.");

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
                $inputFile = $_FILES['impFile']['tmp_name'];
                //if ( is_uploaded_file($_FILES['impFile']['tmp_name']))
                if ( is_uploaded_file ( $inputFile ) )
                {
                    $con = new mysqli($databaseServer, $databaseUser, $databasePW, $databaseDB);
                    if ( !$con )
                    {
                        die('Could not connect: ' . mysqli_connect_error());
                    }

                    $username = $_SESSION[ 'monoto' ][ 'username' ];
                    $target_dir = "";
                    //$target_file = $target_dir . basename($_FILES["impFile"]["tmp_name"]);
                    $target_file = $target_dir . basename($inputFile);
                    $uploadOk = 1;

                    // read linewise and import if note doesnt exist already
                    //if(($handle = fopen($_FILES["impFile"]["tmp_name"], "r")) !== FALSE)
                    if(($handle = fopen($inputFile, "r")) !== FALSE)
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

                            if ( ($newNoteTitle != null ) && ( $newNoteContent != null) )
                            {
                                // TODO: should use inc/noteNew.php here


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
                        }
                        fclose($handle);
                    }
                    else
                    {
                        displayNoty("Unable to open the file.", "error");
                    }

                    // write text to textarea
                    echo '<script type="text/javascript">$("#importLogCSV").append("\n\nFinished importing notes.");</script>';

                }
                else
                {
                    displayNoty("No file selected for import.", "error");
                }
        }


    } // End: Check if request method was POST
?>

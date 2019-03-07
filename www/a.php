<?php

include 'inc/checkSession.php';

if( $_SESSION[ 'monoto' ][ 'admin' ] != 1 ) // check if the user-session is valid or not
{
    header('Location: index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        include 'inc/genericIncludes.php';
        $con = connectToDatabase();
    ?>

    <!-- specific -->

    <!-- JS -->
    <script type="text/javascript" src="js/monoto/admin.js"></script>
    <!-- ckeditor-->
    <!-- needed to show library version in UI-->
    <!-- 4.11.3 -->
    <script type="text/javascript" src="js/ckeditor/4.11.3/ckeditor.js"></script>

    <!-- DataTable -->
    <script type="text/javascript" charset="utf-8">
    $(document).ready(function() {

        getJavaScriptVersions();

        initMonotoUsersDataTable();

    } );
    </script>

</head>

<body role="document">

    <!-- Navigation -->
    <?php include 'inc/genericNavigation.php'; ?>
    <!-- /Navigation -->

    <!-- container theme-->
    <div class="container theme-showcase" role="main">

        <!-- container -->
        <div id="container">

            <!-- tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="#general" role="tab" data-toggle="tab"><?php echo translateString("General information"); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tasks" role="tab" data-toggle="tab"><?php echo translateString("Tasks"); ?></a>
                </li>
            </ul>
            <!-- /tabs -->

            <!-- Tab panes -->
            <div class="tab-content">

                <!-- Tab: general -->
                <div role="tabpanel" class="tab-pane active in" id="general">

                    <br>

                    <?php
                    // check if setup.php still exists - if so - display a warning
                    if (file_exists('setup.php'))
                    {
                        echo '<div class="alert alert-danger">';
                        echo '<strong><i class="fas fa-skull-crossbones"></i> Warning:</strong><br>';
                        echo translateString("Please delete <i>setup.php</i>. It is a risk to keep that file.");
                        echo '</div>';
                    }
                    ?>

                    <!-- Requirements -->
                    <h3><i class="fas fa-puzzle-piece"></i> <?php echo translateString("Requirements"); ?></h3>
                    <?php
                        checkGetTextSupport();
                    ?>
                    <!-- /Requirements -->

                    <!-- spacer -->
                    <div class="row">&nbsp;</div>

                    <!-- Database informations -->
                    <h3><i class="fas fa-database"></i> <?php echo translateString("Database"); ?></h3>
                    <?php
                        // get entire database size
                        $sqlCommand = "SELECT sum( data_length + index_length ) /1024 /1024 FROM information_schema.TABLES WHERE table_schema = '".$databaseDB."' ";
                        $result = mysqli_query($con, $sqlCommand);
                        //$entireDBSize = mysqli_fetch_object($result);
                        while($row = mysqli_fetch_array($result))
                        {
                            $entireDBSize = $row[ 0 ];
                            $entireDBSize = round($entireDBSize, 2); // round db size
                        }
                        echo $entireDBSize." MB";
                    ?>
                    <!-- /Database informations -->

                    <!-- spacer -->
                    <div class="row">&nbsp;</div>

                    <!-- Users -->
                    <h3><i class="fas fa-users"></i> <?php echo translateString("Users"); ?></h3>
                    <table cellpadding="0" cellspacing="0" class="display" id="myMonotoUserDataTable" style="width: 100%">
                        <thead><tr><th>id</th><th><?php echo translateString("username"); ?></th><th><?php echo translateString("Notes"); ?></th><th><?php echo translateString("logins"); ?></th><th>current failed logins</th><th><?php echo translateString("mail"); ?></th><th>admin</th><th><?php echo translateString("comment"); ?></th></tr></thead>
                        <tbody>
                            <?php
                            // FIXME:
                            // shows only accounts with > 0 notes
                            $sqlCommand = "SELECT u.id, username, count(*), login_counter, failed_logins_in_a_row, email, is_admin, admin_note FROM `m_users` as u, `m_notes` as n WHERE n.owner = u.username GROUP BY username";
                            $result = mysqli_query($con, $sqlCommand);

                            // fill DataTable
                            while($row = mysqli_fetch_array($result))
                            {
                                echo '<tr class="odd gradeU">';

                                // id
                                echo '<td>'.$row[ 0 ].'</td>';

                                // username
                                echo '<td>'.$row[ 1 ].'</td>';

                                // notes
                                echo '<td>'.$row[ 2 ].'</td>';

                                // login count
                                echo '<td>'.$row[ 3 ].'</td>';

                                // current failed logins
                                switch ( $row[ 4 ] )
                                {
                                    case 0:
                                        echo '<td bgcolor="#2ECC40">'.$row[ 4 ].'</td>';
                                        break;
                                    case 1:
                                        echo '<td bgcolor="#FFDC00">'.$row[ 4 ].'</td>';
                                        break;
                                    case 2:
                                        echo '<td bgcolor="#FF851B">'.$row[ 4 ].'</td>';
                                        break;
                                    default:
                                        echo '<td bgcolor="#FF4136">'.$row[ 4 ].' (login locked)</td>';
                                        break;
                                }

                                // mail
                                echo '<td>'.$row[ 5 ].'</td>';

                                // admin
                                switch ( $row[ 6 ] )
                                {
                                    case 1:
                                        echo '<td bgcolor="#7FDBFF">'.$row[ 6 ].'</td>';
                                        break;
                                    default:
                                        echo '<td>'.$row[ 6 ].'</td>';
                                        break;
                                }

                                // comment
                                echo '<td>'.$row[ 7 ].'</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                    <!-- /Users -->

                    <!-- spacer -->
                    <div class="row">&nbsp;</div>

                    <!-- PHP -->
                    <!-- #286 -->
                    <h3><i class="fab fa-php"></i> <?php echo translateString("PHP"); ?></h3>
                    <div class="form-group">
                        <label for="libVersionJQuery">PHP Version</label>
                        <input type="text" class="form-control" aria-describedby="jqueryHelp" placeholder="php version" value="<?php echo phpversion(); ?>" disabled>
                    </div>
                    <!-- /PHP -->

                    <!-- spacer -->
                    <div class="row">&nbsp;</div>

                    <!-- JS Libraries -->
                    <h3><i class="fab fa-js"></i> <?php echo translateString("Libraries"); ?></h3>

                    <!-- JS: Bootstrap -->
                    <div class="form-group">
                        <label for="libVersionBootstrap">Bootstrap</label>
                        <input type="text" class="form-control" id="libVersionBootstrap" aria-describedby="bootstrapHelp" placeholder="bootstrap version" disabled>
                        <small class="form-text text-muted">Required monoto-wide</small>
                    </div>

                    <!-- JS: CKEditor -->
                    <div class="form-group">
                        <label for="libVersionCKEditor">CKEditor</label>
                        <input type="text" class="form-control" id="libVersionCKEditor" aria-describedby="ckeditorHelp" placeholder="ckeditor version" disabled>
                        <small class="form-text text-muted">Required for notes UI</small>
                    </div>

                    <!-- JS: DataTable -->
                    <div class="form-group">
                        <label for="libVersionDataTables">DataTable</label>
                        <input type="text" class="form-control" id="libVersionDataTable" aria-describedby="datatableHelp" placeholder="datatable version" disabled>
                        <small class="form-text text-muted">Required for notes UI and activity log</small>
                    </div>

                    <!-- JS: jQuery -->
                    <div class="form-group">
                        <label for="libVersionJQuery">jQuery</label>
                        <input type="text" class="form-control" id="libVersionJQuery" aria-describedby="jqueryHelp" placeholder="jquery version" disabled>
                        <small class="form-text text-muted">Required monoto-wide</small>
                    </div>

                    <!-- /Libraries -->

                </div><!-- /tab -->
                <!-- /Tab: general -->


                <!-- Tab: tasks -->
                <div role="tabpanel" class="tab-pane fade" id="tasks">

                    <!-- spacer -->
                    <div class="row">&nbsp;</div>

                    <!-- invite user -->
                    <h3><i class="fas fa-user-plus"></i> <?php echo translateString("Invite new user"); ?></h3>
                    <form id="inviteForm" action="a.php" method="post" enctype="multipart/form-data">
                        <table style="width: 100%">
                            <tr>
                                <td width='30%'>Username</td>
                                <td><input type="text" name="newUsername" placeholder="Username" required="required" /></td>
                                <td><small id="usernameHelp" class="form-text text-muted">The login name</small></td>
                            </tr>
                            <tr>
                                <td>Mail</td>
                                <td><input type="email" name="newUserMail" placeholder="Email" required="required" /></td>
                                <td><small id="emailHelp" class="form-text text-muted">Email adress of the user</small></td>
                            </tr>
                            <tr>
                                <td>Password</td>
                                <td><input type="password" name="password" pattern=".{8,}" placeholder="Password" required="required" autocomplete="off" /></td>
                                <td><small id="passwordHelp" class="form-text text-muted">min length is 8 characters</small></td>
                            </tr>
                            <tr>
                                <td>Repeat Password</td>
                                <td><input type="password" name="password_confirm" pattern=".{8,}" placeholder="Repeat password" required="required" autocomplete="off" /></td>
                            </tr>
                            <tr>
                                <td>Notification mail</td>
                                <td><input type="checkbox" name="sendNotification" value="sendNotification" /></td>
                                <td><small id="emailNotificationHelp" class="form-text text-muted">Optional: sends an email to the new user</small></td>
                            </tr>
                            <tr>
                                <td>Admin note</td>
                                <td><input type="text" name="newUserNote" placeholder="Comment" /></td>
                                <td><small id="adminNoteHelp" class="form-text text-muted">Optional: information for the monoto admin about this user-account</small></td>
                            </tr>
                            <tr>
                                <td><button type="submit" class="btn btn-primary buttonDefault" name="doCreateNewUserAccount" value="Invite" title="Starts the add user function if all informations are provided."><i class="fas fa-envelope"></i> <?php echo translateString("invite"); ?></button></td>
                                <td>&nbsp;</td>
                            </tr>
                        </table>
                    </form>

                    <!-- spacer -->
                    <div class="row">&nbsp;</div>

                    <!-- delete user -->
                    <form action="a.php" method="post" enctype="multipart/form-data">
                        <h3><i class="fas fa-user-minus"></i> <?php echo translateString("Delete account"); ?></h3>
                        <table style="width: 100%">
                            <tr>
                                <td width='30%'><?php echo translateString("Account"); ?></td>
                                <td>
                                    <select class="selectpicker" name="userDeleteSelector" required>
                                        <option value="" disabled selected>Username</option>
                                        <?php
                                        $result = mysqli_query($con, "SELECT id, username  FROM m_users WHERE is_admin is NULL ORDER by id ");
                                        while ( $row = mysqli_fetch_array ( $result ) ) // fill user-select box
                                        {
                                            echo '<option value="'.$row[ 0 ].'">'.$row[ 1 ].'</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td><small id="deleteUserSelectionHelp" class="form-text text-muted">Select an existing account which should be deleted.</small></td>
                            </tr>
                            <tr>
                                <td>Enter CONFIRM</td>
                                <td><input type="text" name="confirmDeleteUser" placeholder="no" required></td>
                                <td><small id="confirmHelp" class="form-text text-muted">For security reasons</small></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><button type="submit" class="btn btn-danger buttonDefault" name="doDeleteUser"><i class="fas fa-trash-alt"></i> <?php echo translateString("delete"); ?></button> </td>
                                <td><small id="deleteButtonHelp" class="form-text text-muted">Deletes the user and all his notes plus all user-related events in the log</small></td>
                            </tr>
                        </table>
                    </form>


                    <!-- spacer -->
                    <div class="row">&nbsp;</div>


                    <!-- reset login-lock (#288) -->
                    <form action="a.php" method="post" enctype="multipart/form-data">
                        <h3><i class="fas fa-unlock-alt"></i> <?php echo translateString("Unlock account"); ?></h3>
                        <table style="width: 100%">
                            <tr>
                                <td width='30%'><?php echo translateString("Account"); ?></td>
                                <td>
                                    <!--
                                    <div class="dropdown">
                                         <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Username</button>
                                         <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                             <a class="dropdown-item" href="#">Action</a>
                                             <a class="dropdown-item" href="#">Another action</a>
                                             <a class="dropdown-item" href="#">Something else here</a>
                                         </div>
                                    </div>
                                    -->

                                    <select class="selectpicker" name="userResetSelector" required>
                                        <option value="" disabled selected>Username</option>
                                        <?php
                                        $result = mysqli_query($con, "SELECT id, username  FROM m_users  WHERE failed_logins_in_a_row > 2 ORDER by id");
                                        while ( $row = mysqli_fetch_array ( $result ) ) // fill user-select box
                                        {
                                            echo '<option value="'.$row[ 0 ].'">'.$row[ 1 ].'</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td><small id="resetLoginLockAccountSelectionHelp" class="form-text text-muted">Select an existing account which should get unlocked. Only locked accounts are shown.</small></td>
                            </tr>
                            <tr>
                                <td>Enter CONFIRM</td>
                                <td><input type="text" name="confirmResetFailedLoginCount" placeholder="no" required></td>
                                <td><small id="confirmHelp" class="form-text text-muted">For security reasons</small></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><button type="submit" class="btn btn-warning buttonDefault" name="doResetFailedLoginCount"><i class="fas fa-door-open"></i> <?php echo translateString("reset"); ?></button> </td>
                                <td><small id="resetLoginLockButtonHelp" class="form-text text-muted">Press the reset button to reset the failed-login count. This unlocks the account again.</small></td>
                            </tr>
                        </table>
                    </form>


                    <!-- spacer -->
                    <div class="row">&nbsp;</div>


                    <h3><i class="fas fa-envelope"></i> <?php echo translateString("Broadcast message"); ?></h3>
                    <div class="panel-body">
                        Send an email to all monoto-accounts.
                        <form action="a.php" method="post" enctype="multipart/form-data">
                            <input type="text" placeholder="Subject" name="broadcastSubject" style="width:100%"><br>
                            <textarea rows="4" cols="50" style="width:100%" placeholder="Insert your broadcast message text here" name="broadcastMessage"></textarea><br>
                            <button type="submit" class="btn btn-primary buttonDefault" name="doSendBroastcast" value="Send" style="width:200px" title="Sends a broadcast email to all users." /><i class="fas fa-envelope"></i> send</button>
                        </form>
                    </div>

                    <h3><?php echo translateString("Misc"); ?></h3>
                    <form action="a.php" method="post" enctype="multipart/form-data">
                        <button type="submit" class="btn btn-info" name="doOptimize" value="Optimize" title="Starts MySQL Optimize tables command.">Optimize tables</button> This will optimize your entire monoto mysql database.<br><br>
                        <button type="submit" class="btn btn-warning" name="doTruncateEvents" value="Truncate events" title="Deletes the entire content of the event-table. Affects all users. Be careful with that.">Truncate events</button> Warning: This will delete <b>ALL events</b> from the table: m_log.<br><br>
                        <button type="submit" class="btn btn-danger" name="doTruncateNotes" value="Truncate notes" title="Deletes the entire content of the notes-table. Affects all users. Be careful with that too.">Truncate notes</button> Warning: This will delete <b>ALL notes</b> from the table: m_notes.
                    </form>

                </div>
                <!-- /Tab: tasks -->

                <!-- footer -->
                <?php require 'inc/genericFooter.php'; ?>

            </div>
            <!-- /Tab panes -->

        </div>
        <!-- /container -->

    </div>
    <!-- /container theme -->

</body>
</html>


<?php

if ($_SERVER[ 'REQUEST_METHOD' ] === 'POST')
{
    require "config/config.php";

    // ---------------------------------------------------------------------
    // Send broastcast to all users (email)
    // ---------------------------------------------------------------------
    if ( isset( $_POST[ "doSendBroastcast" ] ) )
    {
        $messageSubject= filter_input(INPUT_POST, "broadcastSubject", FILTER_SANITIZE_STRING);
        $messageText= filter_input(INPUT_POST, "broadcastMessage", FILTER_SANITIZE_STRING);

        if ( ( $messageText != "" ) && ( $messageSubject != "" ) )
        {
            // select all users & email-data
            $query = "SELECT username, email FROM m_users;";
            $result = mysqli_query($con, $query);
            while ( $row = mysqli_fetch_array ( $result ) )
            {
                $username = $row[ 0 ];
                $email = $row[ 1 ];
                if ( @mail ( $email, $messageSubject, $messageText ) ) // try to send notification email
                {
                    displayNoty("Notification emails sent.", "success");
                }
                else
                {
                    displayNoty("Unable to sent notification mails.", "error");
                }
            }
        }
        else
        {
            displayNoty("Error: Please enter a message text", "error");
        }
    }
    // END: BROADCAST MAIL


    // ---------------------------------------------------------------------
    // DELETE USER
    // ---------------------------------------------------------------------
    if ( isset ( $_POST[ "doDeleteUser" ] ) )
    {
        $userID= filter_input(INPUT_POST, "userDeleteSelector", FILTER_SANITIZE_STRING);
        $confirmText= filter_input(INPUT_POST, "confirmDeleteUser", FILTER_SANITIZE_STRING);

        if ( $userID != "" )
        {
            if ( $confirmText == "CONFIRM" )
            {
                // get username of selected ID
                $query = "SELECT username FROM m_users WHERE id = '$userID';";
                $result = mysqli_query($con, $query);
                while($row = mysqli_fetch_array($result))
                {
                    $usernameToDelete = $row[ 0 ];
                }

                // delete user
                $sql = "DELETE FROM m_users WHERE id='$userID'";
                $result = mysqli_query( $con, $sql );
                if ( !$result )
                {
                    die('Error: ' . mysqli_connect_error());
                }
                else  // update m_log
                {
                    $event = "User delete";
                    $details = "User: <b>".$userID." </b>is now gone.";
                    $sql = "INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(), '$owner' )";
                    $result = mysqli_query( $con, $sql );

                    // delete his notes as well
                    $sql = "DELETE FROM m_notes WHERE owner='$usernameToDelete'";
                    $result = mysqli_query( $con, $sql );

                    // delete his log as well
                    $sql = "DELETE FROM m_log WHERE owner='$usernameToDelete'";
                    $result = mysqli_query( $con, $sql );

                    displayNoty("Deleted user, his notes and the related log entries", "notification");
                }

                mysqli_close($con); // close sql connection
            }
            else // user hasnt entered CONFIRM
            {
                displayNoty("Please enter CONFIRM in the related field and try it again", "error");
                return;
            }
        }
        else
        {
            displayNoty("Please select a user first", "error");
            return;
        }
    }
    // END: DELETE USER




    // ---------------------------------------------------------------------
    // RESET LOGIN-LOCK FROM USER
    // ---------------------------------------------------------------------
    if ( isset ( $_POST[ "doResetFailedLoginCount" ] ) )
    {
        $userID= filter_input(INPUT_POST, "userResetSelector", FILTER_SANITIZE_STRING);
        $confirmText= filter_input(INPUT_POST, "confirmResetFailedLoginCount", FILTER_SANITIZE_STRING);

        if ( $userID != "" )
        {
            if ( $confirmText == "CONFIRM" )
            {
                // get username of selected ID
                $query = "SELECT username FROM m_users WHERE id = '$userID';";
                $result = mysqli_query($con, $query);
                while($row = mysqli_fetch_array($result))
                {
                    $usernameToDelete = $row[ 0 ];
                }

                // reset login-lock
                $sql = "UPDATE m_users SET failed_logins_in_a_row = 0 WHERE id='$userID'";
                $result = mysqli_query( $con, $sql );
                if ( !$result )
                {
                    die('Error: ' . mysqli_connect_error());
                }
                else  // reset did work
                {
                    displayNoty("Reseted login-lock.", "notification");
                }

                mysqli_close($con); // close sql connection
            }
            else // user hasnt entered CONFIRM
            {
                displayNoty("Please enter CONFIRM in the related field and try it again", "error");
                return;
            }
        }
        else
        {
            displayNoty("Please select a user first", "error");
            return;
        }
    }


    // ---------------------------------------------------------------------
    // OPTIMIZE MYSQL TABLES
    // ---------------------------------------------------------------------
    if ( isset($_POST["doOptimize"]) )
    {
        connectToDatabase();  // connect to mysql

        // select all table with (> 10% overhead) AND at (least > 100k free space)
        $res = mysqli_query($con, 'SHOW TABLE STATUS WHERE Data_free / Data_length > 0.1 AND Data_free > 102400');
        while($row = mysqli_fetch_assoc($res))
        {
            mysqli_query($con, 'OPTIMIZE TABLE ' . $row[ 'Name' ]);
        }
        displayNoty("Database optimized", "notification");
    }
    // END: OPTIMIZE MYSQL TABLES


    // ---------------------------------------------------------------------
    // TRUNCATE EVENTS
    // ---------------------------------------------------------------------
    if ( isset($_POST["doTruncateEvents"]) )
    {
        $con = connectToDatabase(); // connect to mysql
        mysqli_query($con, 'TRUNCATE TABLE m_log'); // truncate log-/events-table
        displayNoty("Truncated all eventlog entries", "notification");
    }
    // END: TRUNCATE EVENTS


    // ---------------------------------------------------------------------
    // TRUNCATE NOTES
    // ---------------------------------------------------------------------
    if ( isset($_POST["doTruncateNotes"]) )
    {
        $con = connectToDatabase(); // connect to mysql
        mysqli_query($con, 'TRUNCATE TABLE m_notes'); // truncate notes-table
        displayNoty("Truncated all user notes", "notification");
    }
    // END: TRUNCATE NOTES


    // ---------------------------------------------------------------------
    // CREATE NEW USER
    // ---------------------------------------------------------------------
    if ( isset($_POST["doCreateNewUserAccount"]) )
    {
        writeToConsoleLog("doCreateNewUserAccount ::: Trying to create new user account");

        $con = connectToDatabase();  // connect to mysql

        $invite_from = $_SESSION[ 'monoto' ][ 'username' ];

        // need  full page url for link in the invite mail
        $pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
        if ($_SERVER["SERVER_PORT"] != "80")
        {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        }
        else
        {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }

        writeToConsoleLog("doCreateNewUserAccount ::: pageURL = ".$pageURL);

        $invite_target = $pageURL;

        // store values on vars
        $newPassword= filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
        $newPasswordConfirm= filter_input(INPUT_POST, "password_confirm", FILTER_SANITIZE_STRING);
        $newUsername= filter_input(INPUT_POST, "newUsername", FILTER_SANITIZE_STRING);
        $newUserMail= filter_input(INPUT_POST, "newUserMail", FILTER_SANITIZE_STRING);

        // optional
        $sendNotification= filter_input(INPUT_POST, "sendNotification", FILTER_SANITIZE_STRING);

        // optional
        $newUserNote= filter_input(INPUT_POST, "newUserNote", FILTER_SANITIZE_STRING);

        // check if password is ok
        if($newPassword == $newPasswordConfirm) //& passwords match - we can continue trying to create this user
        {
            // check if account-name is available
            $result = mysqli_query($con, "SELECT count(username) FROM m_users WHERE username='$newUsername' "); // run the mysql query
            while($row = mysqli_fetch_array($result)) // fetch data and file table as a second step later on
            {
                if($row[0] == 0)  // username is free
                {
                    // check if we got an emailaddress
                    if(strlen($newUserMail) > 0)
                    {
                        // create the new user account
                        $username = $newUsername;
                        $password = $newPassword;
                        $hash = hash('sha256', $password); // playing with hash
                        function createSalt() // playing with salt - creates a 3 character sequence
                        {
                            $string = md5(uniqid(rand(), true));
                            return substr($string, 0, 3);
                        }
                        $salt = createSalt();
                        $hash = hash('sha256', $salt . $hash);

                        $query = "INSERT INTO m_users ( username, password, salt, date_invite, email, admin_note ) VALUES ( '$username' , '$hash' , '$salt' , now() , '$newUserMail', '$newUserNote');";

                        mysqli_query($con, $query);

                        displayNoty("Created new user account '" . $username . "'.", "notification");

                        // we should log that to m_notes -> admin only.
                        // check if we should send a notification as well
                        if($sendNotification == "sendNotification" )
                        {
                            if($newUserMail != "")
                            {
                                $to = $newUserMail;
                                $subject = "monoto-notes invite";
                                $body = "Hi,
                                    \n".$invite_from." invited you to monoto - his web-based notes solution.
                                    \nFeel free to use it as your personal notes keeper as well.
                                    \n\nYou can get some general informations about monoto here: https://github.com/macfidelity/monoto/wiki.
                                    \n\n\n\nThe login credentials are as follows:
                                    \nUsername: ".$username."
                                    \nPassword: ".$password."
                                    \n\n\nPlease change your password after your first visit at:
                                    \n".$invite_target."
                                    \n\nHave fun.";
                                if (mail($to, $subject, $body))
                                {
                                }
                                else
                                {
                                }
                                displayNoty("Notification mail send", "notification");
                            }
                        }
                    }
                    else // no usermail-adress defined while trying to create new account
                    {
                        displayNoty("No mail address defined.", "error");
                    }
                }
                else // username already in use - cancel and inform the admin
                {
                    displayNoty("This mail-adress is already in use", "error");
                }
            }
        }
        else // passwords not matching
        {
            displayNoty("Passwords are not matching", "error");
        }
    }
    // END: creating new user

} // End: check if request was POST

?>

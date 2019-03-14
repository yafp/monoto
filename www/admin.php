<?php

include 'inc/checkSession.php';

if ( $_SESSION[ 'monoto' ][ 'admin' ] != 1 ) // check if the user-session is valid or not
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

        initCKEditor();

        updateTaskSelectorDeleteAccount();

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

                    <!-- setup.php: check if it still exists -->
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
                    <!-- /setup.php: check if it still exists -->

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
                        <thead><tr><th>id</th><th><?php echo translateString("username"); ?></th><th><?php echo translateString("logins"); ?></th><th>current failed logins</th><th><?php echo translateString("mail"); ?></th><th>admin</th><th><?php echo translateString("comment"); ?></th></tr></thead>
                        <tbody>
                            <!-- gets filled via inc/adminGetAllUserAccounts.php -->
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
                    <table style="width: 100%">
                        <tr>
                            <td width='30%'>Username</td>
                            <td><input type="text" id="newUsername" name="newUsername" placeholder="Username" required="required" /></td>
                            <td><small id="usernameHelp" class="form-text text-muted">The login name</small></td>
                        </tr>
                        <tr>
                            <td>Mail</td>
                            <td><input type="email" id="newUserMail" name="newUserMail" placeholder="Email" required="required" /></td>
                            <td><small id="emailHelp" class="form-text text-muted">Email adress of the user</small></td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td><input type="password" id="newPassword" name="newPassword" pattern=".{8,}" placeholder="Password" required="required" autocomplete="off" onkeyup="calculatePasswordStrength()" /><span id="passstrength"></span></td>
                            <td><small id="passwordHelp" class="form-text text-muted">min length is 8 characters</small></td>
                        </tr>
                        <tr>
                            <td>Repeat Password</td>
                            <td><input type="password" id="newPasswordConfirm" name="newPasswordConfirm" pattern=".{8,}" placeholder="Repeat password" required="required" autocomplete="off" />&nbsp;<span id="passwordDiff"></span></td>
                        </tr>
                        <tr>
                            <td>Notification mail</td>
                            <td><input type="checkbox" id="sendNotification" name="sendNotification" value="sendNotification" /></td>
                            <td><small id="emailNotificationHelp" class="form-text text-muted">Optional: sends an email to the new user</small></td>
                        </tr>
                        <tr>
                            <td>Admin note</td>
                            <td><input type="text" id="newUserNote" name="newUserNote" placeholder="Comment" /></td>
                            <td><small id="adminNoteHelp" class="form-text text-muted">Optional: information for the monoto admin about this user-account</small></td>
                        </tr>
                        <tr>
                            <td><button type="submit" class="btn btn-primary buttonDefault" name="bt_continue" id="bt_continue" onClick="userAccountCreateNew();" value="Invite" title="Starts the add user function if all informations are provided." disabled=disabled><i class="fas fa-envelope"></i> <?php echo translateString("invite"); ?></button></td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>

                    <!-- spacer -->
                    <div class="row">&nbsp;</div>

                    <!-- delete user -->
                    <h3><i class="fas fa-user-minus"></i> <?php echo translateString("Delete account"); ?></h3>
                    <table style="width: 100%">
                        <tr>
                            <td width='30%'><?php echo translateString("Account"); ?></td>
                            <td>
                                <select class="selectpicker" id="userDeleteSelector" name="userDeleteSelector" onChange="enableUserAccountDeleteButton();" required>
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
                            <td><input type="text" id="confirmDeleteUser" name="confirmDeleteUser" onChange="enableUserAccountDeleteButton();" placeholder="no" required></td>
                            <td><small id="confirmHelp" class="form-text text-muted">For security reasons</small></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><button type="submit" class="btn btn-danger buttonDefault" id="doDeleteUser" name="doDeleteUser" onClick="userAccountDelete();" disabled="disabled"><i class="fas fa-trash-alt"></i> <?php echo translateString("delete"); ?></button> </td>
                            <td><small id="deleteButtonHelp" class="form-text text-muted">Deletes the user and all his notes plus all user-related events in the log</small></td>
                        </tr>
                    </table>

                    <!-- spacer -->
                    <div class="row">&nbsp;</div>

                    <!-- reset login-lock (#288) -->
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

                                    <select class="selectpicker" id="userUnlockSelector" name="userUnlockSelector" required>
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
                                <td><input type="text" id="confirmResetFailedLoginCount" name="confirmResetFailedLoginCount" placeholder="no" required></td>
                                <td><small id="confirmHelp" class="form-text text-muted">For security reasons</small></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><button type="submit" class="btn btn-warning buttonDefault" id="doResetFailedLoginCount" name="doResetFailedLoginCount" onClick="userAccountUnlock();"><i class="fas fa-door-open"></i> <?php echo translateString("reset"); ?></button> </td>
                                <td><small id="resetLoginLockButtonHelp" class="form-text text-muted">Press the reset button to reset the failed-login count. This unlocks the account again.</small></td>
                            </tr>
                        </table>


                    <!-- spacer -->
                    <div class="row">&nbsp;</div>


                    <h3><i class="fas fa-envelope"></i> <?php echo translateString("Broadcast message"); ?></h3>
                    <div class="panel-body">
                        Send an email to all monoto-accounts.
                            <input type="text" placeholder="Subject" id="broadcastSubject" name="broadcastSubject" style="width:100%"><br>
                            <textarea rows="4" cols="50" style="width:100%" placeholder="Insert your broadcast message text here" id="editor1" name="editor1"></textarea><br>
                            <button type="submit" class="btn btn-primary buttonDefault" id="doSendBroadcast" name="doSendBroastcast" onClick="sendMailToAllUsers();" value="Send" style="width:200px" title="Sends a broadcast email to all users." /><i class="fas fa-envelope"></i> send</button>
                    </div>

                    <h3><?php echo translateString("Misc"); ?></h3>
                        <button type="submit" class="btn btn-info" id="doOptimize" name="doOptimize" onClick="optimizeDatabaseTables();" value="Optimize" title="Starts MySQL Optimize tables command.">Optimize tables</button> This will optimize your entire monoto mysql database.<br><br>

                        <?php
                            if ( $m_stableRelease == false )
                            {
                                echo '<button type="submit" class="btn btn-warning" id="doTruncateEvents" name="doTruncateEvents" onClick="truncateAllEvents();" value="Truncate events" title="Deletes the entire content of the event-table. Affects all users. Be careful with that.">Truncate events</button> Warning: This will delete <b>ALL events</b> from the table: m_log. (Developers ONLY)<br><br>';
                                echo '<button type="submit" class="btn btn-danger" id="doTruncateNotes" name="doTruncateNotes" onClick="truncateAllNotes();" value="Truncate notes" title="Deletes the entire content of the notes-table. Affects all users. Be careful with that too.">Truncate notes</button> Warning: This will delete <b>ALL notes</b> from the table: m_notes. (Developers ONLY)';
                            }

                        ?>

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

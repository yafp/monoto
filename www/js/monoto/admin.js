/** @namespace */
 var admin = {};


/**
 * @name getJavaScriptVersions
 * @summary Reads the version numbers from the most relevant JS libs and displays them in admin view.
 * @description Reads the version numbers from the major JavaScript Libraries (Bootstrap, ckEditor, DataTables and jQuery) and displays them in admin view
 * @memberof admin
 */
function getJavaScriptVersions()
{
    console.debug("getJavaScriptVersions ::: Start");

    // BootStrap
    //
    // get bootstrap version
    var bootstrapVersion = ($().modal||$().tab).Constructor.VERSION.split(",");
    // update gui
    $("#libVersionBootstrap").val(bootstrapVersion);

    // ckeditor
    //
    // update gui
    $("#libVersionCKEditor").val(CKEDITOR.version);

    // DataTables
    //
    // get DataTables version
    var datatableVersion = $.fn.dataTable.version;
    // update gui
    $("#libVersionDataTable").val(datatableVersion);

    // jquery
    //
    if (typeof jQuery !== "undefined")
    {
        // update gui
        $("#libVersionJQuery").val(jQuery.fn.jquery);
    }

    console.debug("getJavaScriptVersions ::: End");
}


/**
 * @name initMonotoUsersDataTable
 * @summary creates the monoto user table in admin view.
 * @description creates the monoto user table (using DataTable) in admin view. Colorizes some cells under specific conditions
 * @memberof admin
 */
function initMonotoUsersDataTable()
{
    console.debug("initMonotoUsersDataTable ::: Start");

    console.log("initMonotoUsersDataTable ::: Initializing Monoto Users DataTable");

    $("#myMonotoUserDataTable").DataTable( {
        "select": {
            "style": "single"
        },
        "bSort": false, // dont sort - trust the sql-select and its sort-order
        "sPaginationType": "full_numbers",
        "iDisplayLength" : 25,
        "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
        "paging": false,

        "processing": true,
        //"serverSide": true, // might conflict with .search in datatable
        "ajax": "inc/adminGetAllUserAccounts.php",

        // colorize single cells
        "rowCallback": function( row, data )
        {
            // overall logins
            if ( data[2] === "0" ) {
                $("td:eq(2)", row).addClass("m_redLight");
            }

            // failed failed_logins_in_a_row
            if ( data[3] === "0" ) {
                $("td:eq(3)", row).addClass("m_greenLight");
            }

            if ( data[3] === "1" ) {
                $("td:eq(3)", row).addClass("m_yellowLight");
            }

            if ( data[3] === "2" ) {
                $("td:eq(3)", row).addClass("m_orangeLight");
            }

            if ( data[3] === "3" ) {
                $("td:eq(3)", row).addClass("m_redLight");
            }

            // is_admin
            if ( data[5] === "1" ) {
                $("td:eq(5)", row).addClass("m_blueLight");
                $("td:eq(5)", row).html( "<i class='fas fa-clipboard-check'></i> yes" );
            }
        }
    } );

    console.log("initMonotoUsersDataTable ::: Finished initializing Monoto Users DataTable");

    console.debug("initMonotoUsersDataTable ::: End");
}


/**
 * @name reInitMonotoUsersDataTable
 * @summary Destroys and re-inits the monoto user table.
 * @description Destroys and re-inits the monoto user table (using DataTable) in admin view. This is needed after new user accounts got created or existing accounts got unlocked or deleted.
 * @memberof admin
 */
function reInitMonotoUsersDataTable()
{
    console.debug("reInitMonotoUsersDataTable ::: Start");

    console.log("reInitMonotoUsersDataTable ::: Starting to re-init the Monoto Users DataTable");

    // Destroy datatable
    $( "#myMonotoUserDataTable" ).DataTable().destroy();
    $( "myMonotoUserDataTable" ).empty();

    // reload datatable
    initMonotoUsersDataTable();

    console.debug("reInitMonotoUsersDataTable ::: End");
}


/**
 * @name updateTaskSelectorDeleteAccount
 * @summary resets and refills the select element for the user delete function
 * @description resets and refills the <select> element which contains all existing non-admin user accounts (deletion)
 * @requires inc/adminFillUserDeleteSelector.php
 * @memberof admin
 */
function updateTaskSelectorDeleteAccount()
{
    console.debug("updateTaskSelectorDeleteAccount ::: Start");

    // delete all items from <select>
    $("#userDeleteSelector").children("option:not(:first)").remove();

    var jqxhr = $.post( "inc/adminFillUserDeleteSelector.php", { }, function(msg)
    {
        console.log("updateTaskSelectorDeleteAccount ::: Successfully fetched all users for delete-selector");

        // walk over the user array ...
        var arrayLength = msg.length;
        for (var i = 0; i < arrayLength; i++)
        {
            userID = msg[i][0];
            userName = msg[i][1];

            // add current user to select
            $("#userDeleteSelector").append(new Option(userName, userID));
        }
        console.log("updateTaskSelectorDeleteAccount ::: Finished filling the user-delete select element");
    })
    .done(function()
    {
        console.log("updateTaskSelectorDeleteAccount ::: done");
    })
    .fail(function(jqxhr, textStatus, errorThrown)
    {
        console.error("updateTaskSelectorDeleteAccount ::: $.post failed");
        console.log(jqxhr);
        console.log(textStatus);
        console.log(errorThrown);
    })
    .always(function()
    {
        // doing nothing so far
    });

    console.debug("updateTaskSelectorDeleteAccount ::: End");
}


/**
 * @name updateTaskSelectorUnlockAccount
 * @summary resets and refills the select element for the user unlock function
 * @description resets and refills the <select> element which contains all user accounts which are locked
 * @requires inc/adminFillUserUnlockSelector.php
 * @memberof admin
 */
function updateTaskSelectorUnlockAccount()
{
    console.debug("updateTaskSelectorUnlockAccount ::: Start");

    // delete all items from <select>
    $("#userUnlockSelector").children("option:not(:first)").remove();

    var jqxhr = $.post( "inc/adminFillUserUnlockSelector.php", { }, function(msg)
    {
        console.log("updateTaskSelectorUnlockAccount ::: Successfully fetched all users for unlock-selector");

        // walk over the user array ...
        var arrayLength = msg.length;
        for (var i = 0; i < arrayLength; i++)
        {
            userID = msg[i][0];
            userName = msg[i][1];

            // add current user to select
            $("#userUnlockSelector").append(new Option(userName, userID));
        }
        console.log("updateTaskSelectorUnlockAccount ::: Finished filling the user-unlock select element");

    })
    .done(function()
    {
        console.log("updateTaskSelectorUnlockAccount ::: done");
    })
    .fail(function(jqxhr, textStatus, errorThrown)
    {
        console.error("updateTaskSelectorUnlockAccount ::: $.post failed");
        console.log(jqxhr);
        console.log(textStatus);
        console.log(errorThrown);
    })
    .always(function()
    {
        // doing nothing so far
    });

    console.debug("updateTaskSelectorUnlockAccount ::: End");
}


/**
 * @name userAccountCreateNew
 * @summary Creates a new user account
 * @description Creates a new monoto user account and optional sends an invite mail to the new user. Using inc/adminUserNew.php
 * @requires inc/adminUserNew.php
 * @memberof admin
 */
function userAccountCreateNew()
{
    console.debug("userAccountCreateNew ::: Start");

    console.log("userAccountCreateNew ::: Start user creation");

    // get values
    var newUsername = $("#newUsername").val();
    var newUserMail = $("#newUserMail").val();
    var newPassword = $("#newPassword").val();
    var newUserNote = $("#newUserNote").val();
    // checkbox
    //
    var sendNotification;
    if ( $("#sendNotification").is(":checked") )
    {
        sendNotification = true;
    }
    else
    {
        sendNotification = false;
    }

    var jqxhr = $.post( "inc/adminUserNew.php", { newUsername: newUsername, newUserMail: newUserMail, newPassword: newPassword, sendNotification: sendNotification, newUserNote: newUserNote }, function()
    {
        console.log("userAccountCreateNew ::: Successfully created a new user account");
    })
    .done(function()
    {
        console.log("userAccountCreateNew ::: done");
        createNoty("Successfully creeated a new user account","success");

        // reset invite fields
        $("#newUsername").val("");
        $("#newUserMail").val("");
        $("#newPassword").val("");
        $("#newPasswordConfirm").val("");
        $("#sendNotification").val("");
        $("#newUserNote").val("");

        // disable button
        $("#bt_continue").prop("disabled",true);

        // reload the DataTable
        reInitMonotoUsersDataTable();

        // update select for Account deletion
        updateTaskSelectorDeleteAccount();

        // update select for Account unlocking
        updateTaskSelectorUnlockAccount();
    })
    .fail(function(jqxhr, textStatus, errorThrown)
    {
        console.error("userAccountCreateNew ::: $.post failed");
        console.log(jqxhr);
        console.log(textStatus);
        console.log(errorThrown);

        createNoty("Creating a new user account failed", "error");
    })
    .always(function()
    {
        // doing nothing so far
    });

    console.debug("userAccountCreateNew ::: End");
}


/**
 * @name enableUserAccountDeleteButton
 * @summary Enables the delete user button
 * @description enables the user account delete button if the requirements (selected user & CONFIRM text) are fulfilled
 * @memberof admin
 */
function enableUserAccountDeleteButton()
{
    console.debug("enableUserAccountDeleteButton ::: Start");

    var existingUserID = $("#userDeleteSelector").val();
    var confirmText = $("#confirmDeleteUser").val();

    // if a user is selected and confirm is entered -> enable the button
    if ( (confirmText === "CONFIRM") && (existingUserID !== null ) )
    {
        // enable the user-delete button
        $("#doDeleteUser").prop("disabled", false);
        console.log("enableUserAccountDeleteButton ::: Enabled the user account delete button.");
    }
    else
    {
        // disable the user-delete button
        $("#doDeleteUser").prop("disabled", false);
        console.log("enableUserAccountDeleteButton ::: Disabled the user account delete button.");
    }

    console.debug("enableUserAccountDeleteButton ::: End");
}


/**
 * @name enableUserAccountUnlockButton
 * @summary Unlock a locked user accounts
 * @description enables the user account unlock button if the requirements (selected user & CONFIRM text) are fulfilled
 * @memberof admin
 */
function enableUserAccountUnlockButton()
{
    console.debug("enableUserAccountUnlockButton ::: Start");

    var existingUserID = $("#userUnlockSelector").val();
    var confirmText = $("#confirmResetFailedLoginCount").val();

    // if a user is selected and confirm is entered -> enable the button
    if ( (confirmText === "CONFIRM") && (existingUserID !== null ) )
    {
        // enable the user-unlock button
        $("#doResetFailedLoginCount").prop("disabled", false);
        console.log("enableUserAccountUnlockButton ::: Enabled the user account unlock button.");
    }
    else
    {
        // disable the user-unlock button
        $("#doResetFailedLoginCount").prop("disabled", true);
        console.log("enableUserAccountUnlockButton ::: Disabled the user account unlock button.");
    }

    console.debug("enableUserAccountUnlockButton ::: End");
}


/**
 * @name userAccountDelete
 * @summary Deletes an existing user account
 * @description Deletes an existing monoto user account using inc/adminUserDelete.php
 * @requires inc/adminUserDelete.php
 * @memberof admin
 */
function userAccountDelete()
{
    console.debug("userAccountDelete ::: Start");

    console.log("userAccountDelete ::: Start user creation");

    // get values
    var existingUserID = $("#userDeleteSelector").val();
    var confirmText = $("#confirmDeleteUser").val();

    if ( confirmText === "CONFIRM")
    {
        console.log("userAccountDelete ::: Trying to delete user account id: " + existingUserID);

        var jqxhr = $.post( "inc/adminUserDelete.php", { existingUserID: existingUserID }, function()
        {
            console.log("userAccountDelete ::: Successfully deleted an existing user account");
            createNoty("Deleted user, his notes and the related log entries", "success");

            // disable the user-delete button
            $("#doDeleteUser").prop("disabled", true);

            // re-do user-table
            reInitMonotoUsersDataTable();

            // update the <select>
            updateTaskSelectorDeleteAccount();

            // disable the button
            enableUserAccountDeleteButton();
        })
        .done(function()
        {
            console.log("userAccountDelete ::: done");

            // reset fields
            //
            $("#userDeleteSelector").val($(this).find("option:first").val());
            $("#confirmDeleteUser").val("");

            // reload the DataTable
            reInitMonotoUsersDataTable();
        })
        .fail(function(jqxhr, textStatus, errorThrown)
        {
            console.error("userAccountDelete ::: $.post failed");
            console.log(jqxhr);
            console.log(textStatus);
            console.log(errorThrown);

            createNoty("Deleting an existing user account failed", "error");
        })
        .always(function()
        {
            // doing nothing so far
        });
    }
    else
    {
        createNoty("Failed deleting account, as confirm text is not correct", "error");
    }

    console.debug("userAccountDelete ::: End");
}


/**
 * @name userAccountUnlock
 * @summary Unlock a locked user account
 * @description Unlocks an existing monoto user account (login-lock after 3 failed login attempts in a row) using inc/adminUserUnlock.php
 * @requires inc/adminUserUnlock.php
 * @memberof admin
 */
function userAccountUnlock()
{
    console.debug("userAccountUnlock ::: Start");

    console.log("userAccountUnlock ::: Start user unlocking");

    // get values
    var existingUserID = $("#userUnlockSelector").val();
    var confirmText = $("#confirmResetFailedLoginCount").val();

    if ( confirmText === "CONFIRM")
    {
        console.log("userAccountUnlock ::: Trying to unlock user account id: " + existingUserID);

        var jqxhr = $.post( "inc/adminUserUnlock.php", { existingUserID: existingUserID }, function()
        {
            console.log("userAccountUnlock ::: Successfully unlocked an existing user account");
            createNoty("Unlocked user", "success");
        })
        .done(function()
        {
            console.log("userAccountUnlock ::: done");

            // reset unlock-ui items
            //
            $("#userUnlockSelector").val($(this).find("option:first").val());
            $("#confirmResetFailedLoginCount").val("");

            // re-do user-table
            reInitMonotoUsersDataTable();

            // update the <select>
            updateTaskSelectorUnlockAccount();

            // disable the button
            enableUserAccountUnlockButton();
        })
        .fail(function(jqxhr, textStatus, errorThrown)
        {
            console.error("userAccountUnlock ::: $.post failed");
            console.log(jqxhr);
            console.log(textStatus);
            console.log(errorThrown);

            createNoty("Unlocking an existing user account failed", "error");
        })
        .always(function()
        {
            // doing nothing so far
        });
    }
    else
    {
        createNoty("Failed to unlock account, as confirm text is not correct", "error");
    }

    console.debug("userAccountUnlock ::: End");
}


/**
 * @name optimizeDatabaseTables
 * @summary Optimizes the mysql tables
 * @description Runs optimize on all monoto mysql database tables.
 * @requires inc/adminOptimizeDatabaseTables.php
 * @memberof admin
 */
function optimizeDatabaseTables()
{
    console.debug("optimizeDatabaseTables ::: Start");

    console.log("optimizeDatabaseTables ::: Ask user if he wants to run optimize on all tables");

    var x = noty({
        text: "Really optimize all datatabase tables?",
        type: "confirm",
        dismissQueue: false,
        layout: "topRight",
        theme: "defaultTheme",
        buttons: [
            {
                addClass: "btn btn-primary", text: "Ok", onClick: function($noty)
                {
                    $noty.close();
                    console.log("optimizeDatabaseTables ::: User confirmed to optimize tables. Starting now ...");
                    $.post("inc/adminOptimizeDatabaseTables.php");
                    createNoty("Executed optimize tasks","success");
                }
            },
            {
                addClass: "btn btn-danger", text: "Cancel", onClick: function($noty)
                {
                    $noty.close();
                    console.log("optimizeDatabaseTables ::: User cancelled the optimize tasks");
                    createNoty("Aborted","information");
                }
            }
        ]
    });

    console.debug("optimizeDatabaseTables ::: End");
}


/**
 * @name truncateAllEvents
 * @summary Truncates the table m_log
 * @description Truncates the table m_log. This affects all accounts (useful for developers only)
 * @requires inc/adminTruncateAllEvents.php
 * @memberof admin
 */
function truncateAllEvents()
{
    console.debug("truncateAllEvents ::: Start");

    console.log("truncateAllEvents ::: Ask user if he wants to truncate the table m_log");

    var x = noty({
        text: "Really truncate the entire log/events table?",
        type: "confirm",
        dismissQueue: false,
        layout: "topRight",
        theme: "defaultTheme",
        buttons: [
            {
                addClass: "btn btn-primary", text: "Ok", onClick: function($noty)
                {
                    $noty.close();
                    console.log("truncateAllEvents ::: User confirmed to truncate log/events table. Starting now ...");
                    $.post("inc/adminTruncateAllEvents.php");
                    createNoty("Executed truncate of all events","success");
                }
            },
            {
                addClass: "btn btn-danger", text: "Cancel", onClick: function($noty)
                {
                    $noty.close();
                    console.log("truncateAllEvents ::: User cancelled the truncate of all events");
                    createNoty("Aborted","information");
                }
            }
        ]
    });

    console.debug("truncateAllEvents ::: End");
}


/**
 * @name truncateAllNotes
 * @summary Truncates the table m_notes
 * @description truncates the table m_notes. This affects all accounts (useful for developers only)
 * @requires inc/adminTruncateAllNotes.php
 * @memberof admin
 */
function truncateAllNotes()
{
    console.debug("truncateAllNotes ::: Start");

    console.log("truncateAllNotes ::: Ask user if he wants to truncate the table m_notes");

    var x = noty({
        text: "Really truncate the entire notes table?",
        type: "confirm",
        dismissQueue: false,
        layout: "topRight",
        theme: "defaultTheme",
        buttons: [
            {
                addClass: "btn btn-primary", text: "Ok", onClick: function($noty)
                {
                    $noty.close();
                    console.log("truncateAllNotes ::: User confirmed to truncate notess table. Starting now ...");
                    $.post("inc/adminTruncateAllNotes.php");
                    createNoty("Executed truncate of all notes","success");
                }
            },
            {
                addClass: "btn btn-danger", text: "Cancel", onClick: function($noty)
                {
                    $noty.close();
                    console.log("truncateAllNotes ::: User cancelled the truncate of all notes");
                    createNoty("Aborted","information");
                }
            }
        ]
    });

    console.debug("truncateAllNotes ::: End");
}


/**
 * @name sendMailToAllUsers
 * @summary Send broadcast email to all user accounts
 * @description Sends an email to all existing user accounts.
 * @requires inc/adminSendMailToAllUsers.php
 * @memberof admin
 */
function sendMailToAllUsers()
{
    console.debug("sendMailToAllUsers ::: Start");

    // get values
    var mailSubject = $("#broadcastSubject").val();
    var mailText = CKEDITOR.instances.editor1.getData();

    if ( mailSubject && mailText ) // if both variables are set
    {
        console.log("sendMailToAllUsers ::: Ask user if he wants to send an email to all user accounts");

        var x = noty({
            text: "Really send an email to all users?",
            type: "confirm",
            dismissQueue: false,
            layout: "topRight",
            theme: "defaultTheme",
            buttons: [
                {
                    addClass: "btn btn-primary", text: "Ok", onClick: function($noty)
                    {
                        $noty.close();
                        console.log("sendMailToAllUsers ::: User confirmed to send a broadcast mail to all users. Starting now ...");

                        var jqxhr = $.post( "inc/adminSendMailToAllUsers.php", { mailSubject: mailSubject, mailText: mailText }, function()
                        {
                            console.log("sendMailToAllUsers ::: Successfully send email to all user accounts");
                            createNoty("Finished sending broadcast mail to all users", "success");
                        })
                        .done(function()
                        {
                            console.log("sendMailToAllUsers ::: done");

                            // reset fields
                            //
                            $("#broadcastSubject").val("");
                            $("#broadcastMessage").val("");

                        })
                        .fail(function(jqxhr, textStatus, errorThrown)
                        {
                            console.error("sendMailToAllUsers ::: $.post failed");
                            console.log(jqxhr);
                            console.log(textStatus);
                            console.log(errorThrown);

                            createNoty("Sending broadcast mail to all user accounts failed", "error");
                        })
                        .always(function()
                        {
                            // doing nothing so far
                        });
                    }
                },
                {
                    addClass: "btn btn-danger", text: "Cancel", onClick: function($noty)
                    {
                        $noty.close();
                        console.log("sendMailToAllUsers ::: User cancelled the broadcast mail to all users");
                        createNoty("Aborted","information");
                    }
                }
            ]
        });
    }
    else {
        createNoty("Unable to send mails. Please fill both Subject and Message", "error");
        console.warn("sendMailToAllUsers ::: Either subject or message or both are empty.");
        console.warn("Subject: " + mailSubject);
        console.warn("Text: " + mailText);

    }

    console.debug("sendMailToAllUsers ::: End");
}


/**
 * @name initCKEditor
 * @summary Init CKEditor
 * @description Initialize the textarea with CKEditor. Editor can be used to compse email messages to all existing user accounts.
 * @memberof admin
 */
function initCKEditor()
{
    console.debug("initCKEditor ::: Start");

    // START CKEDITOR
    CKEDITOR.replace( "editor1",
    {
        // key press handling
        on:
        {
            instanceReady: function()
            {
                //CKEDITOR.instances.editor1.setReadOnly(false); // set RO mode
                CKEDITOR.config.toolbarCanCollapse = false; /* Enable collapse function for toolbar */
                return;
            },
        }, // end: on

        // other things
        //
        enterMode: CKEDITOR.ENTER_BR,
        toolbarCanCollapse: true, // enable collapse option
        toolbarStartupExpanded : true,  // define collapsed as default
        //removePlugins: 'elementspath',
        toolbar:
        [
            { name: "tools",       items : [ "Maximize" ] },
            { name: "basicstyles", items : [ "Bold","Italic","Strike","RemoveFormat" ] },
            { name: "paragraph",   items : [ "NumberedList","BulletedList","-","Outdent","Indent","Blockquote" ] },
            { name: "insert",      items : [ "Link","Image","Table","HorizontalRule","SpecialChar" ] },
            { name: "styles",      items : [ "Styles","Format" ] },
            { name: "document",    items : [ "Source" ] }
        ]
    });

    console.debug("initCKEditor ::: End");
}


/**
 * @name onAdminPageReady
 * @summary executes several init functions for the admin section
 * @description executes several initializing functions for the admin section after loading admin.php.
 * @memberof admin
 */
function onAdminPageReady()
{
    console.debug("onAdminPageReady ::: Start");

    // Javascript libraries
    getJavaScriptVersions();

    // Init the user table
    initMonotoUsersDataTable();

    // Init CKEditor for Broadcast mails
    initCKEditor();

    // Fill user-delete <select> element
    updateTaskSelectorDeleteAccount();

    // Fill user-unlock <select> element
    updateTaskSelectorUnlockAccount();

    console.debug("onAdminPageReady ::: End");
}

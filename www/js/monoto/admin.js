/** @namespace */
 var admin = {};

/**
 * @name getJavaScriptVersions
 * @description gets the version numbers of the major JavaScript Libraries and displays them in admin view
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
    if (typeof jQuery != "undefined")
    {
        // update gui
        $("#libVersionJQuery").val(jQuery.fn.jquery);
    }

    console.debug("getJavaScriptVersions ::: Stop");
}


/**
 * @name initMonotoUsersDataTable
 * @description init the monoto user DataTable in admin view
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
        "ajax": "inc/noteGetAllUserAccounts.php",

        // colorize single cells
        "rowCallback": function( row, data )
        {
            // overall logins
            if ( data[2] === "0" ) {
              $("td:eq(3)", row).addClass("m_red");
            }

            // failed failed_logins_in_a_row
            if ( data[3] === "0" ) {
              $("td:eq(3)", row).addClass("m_green");
            }

            if ( data[3] === "1" ) {
              $("td:eq(3)", row).addClass("m_yellow");
            }

            if ( data[3] === "2" ) {
              $("td:eq(3)", row).addClass("m_orange");
            }

            if ( data[3] === "3" ) {
              $("td:eq(3)", row).addClass("m_red");
            }

            // is_admin
            if ( data[5] === "1" ) {
              $("td:eq(5)", row).addClass("m_blue");
            }
        }

    } );

    console.debug("initMonotoUsersDataTable ::: Stop");
}


/**
 * @name reInitMonotoUsersDataTable
 * @description destroy and re-init the monoto user DataTable in admin view
 * @memberof admin
 */
function reInitMonotoUsersDataTable()
{
    // Destroy datatable
    $( "#myMonotoUserDataTable" ).DataTable().destroy();
    $( "myMonotoUserDataTable" ).empty();

    // reload datatable
    initMonotoUsersDataTable();
}



/**
 * @name userAccountCreateNew
 * @description creates a new monoto user account
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
    if ($('#sendNotification').is(":checked"))
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

    console.debug("userAccountCreateNew ::: Stop");
}


/**
 * @name userAccountDelete
 * @description deletes an existing monoto user account
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
        })
        .done(function()
        {
            console.log("userAccountDelete ::: done");

            // reset fields
            //
            //$("#userDeleteSelector").val("");
            $('#userDeleteSelector').val($(this).find('option:first').val());
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

    console.debug("userAccountDelete ::: Stop");
}


/**
 * @name userAccountUnlock
 * @description unlocks an existing monoto user account
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
        console.log("userAccountUnlock ::: Trying to delete user account id: " + existingUserID);

        var jqxhr = $.post( "inc/adminUserUnlock.php", { existingUserID: existingUserID }, function()
        {
            console.log("userAccountUnlock ::: Successfully unlocked an existing user account");
            createNoty("Unlocked user", "success");
        })
        .done(function()
        {
            console.log("userAccountUnlock ::: done");

            // reset fields
            //
            //$("#userDeleteSelector").val("");
            $('#userUnlockSelector').val($(this).find('option:first').val());
            $("#confirmDeleteUser").val("");

            // reload the DataTable
            reInitMonotoUsersDataTable();
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

    console.debug("userAccountUnlock ::: Stop");
}

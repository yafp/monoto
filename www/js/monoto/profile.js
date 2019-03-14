/** @namespace */
 var profile = {};


/**
 * @name initProfileEventsDataTable
 * @description initializes the profile-events DataTable
 * @memberof profile
 */
function initProfileEventsDataTable()
{
    console.debug("initProfileEventsDataTable ::: Start");

     // init
     $("#myEventsDataTable").DataTable( {
         "order": [[ 0, "desc" ]], // sort by ID (new to old)
         "sPaginationType": "simple_numbers",
         "iDisplayLength" : 10,
         "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
         "processing": true,
         //"serverSide": true, // might conflict with .search in datatable
         "ajax": "inc/profileGetAllUserEvents.php",

         // colorize the different event-types
         //
         "rowCallback": function( row, data )
         {
            // event = create
            if ( data[1] === "Note creation" ) {
               $("td:eq(1)", row).addClass("m_greenLight");
            }

            // event = delete
            if ( data[1] === "Note delete" ) {
               $("td:eq(1)", row).addClass("m_orangeLight");
            }

            // event = save
            if ( data[1] === "Note update" ) {
               $("td:eq(1)", row).addClass("m_yellowLight");
            }

            // event = login
            if ( data[1] === "Login" ) {
               $("td:eq(1)", row).addClass("m_blueLight");
            }

            // event = logout
            if ( data[1] === "Logout" ) {
               $("td:eq(1)", row).addClass("m_blueDark");
            }

            // event = login error
            if ( data[1] === "Login error" ) {
               $("td:eq(1)", row).addClass("m_redLight");
            }

            // event = event eraser
            if ( data[1] === "Eraser user events" ) {
               $("td:eq(1)", row).addClass("m_pinkLight");
            }

            // event = event eraser
            if ( data[1] === "Eraser user notes" ) {
               $("td:eq(1)", row).addClass("m_pinkDark");
            }

            // event = account eraser
            if ( data[1] === "Eraser user account" ) {
               $("td:eq(1)", row).addClass("m_orangeDark");
            }

            // event = database optimizer
            if ( data[1] === "Database Optimizer" ) {
               $("td:eq(1)", row).addClass("m_greenDark");
            }

            // event = Undefined
            if ( data[1] === "Undefined" ) {
               $("td:eq(1)", row).addClass("m_redDark");
            }
        },
        // end colorize

        // add dropdown to eventType
        //
        initComplete: function () {
            //this.api().columns().every( function () {
            this.api().columns(":eq(1)").every(function () {
                var column = this;
                var select = $("<select><option value=''></option></select>")
                    .appendTo( $(column.footer()).empty() )
                    .on( "change", function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search( val ? "^"+val+"$" : "", true, false )
                            .draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    //select.append( '<option value="'+d+'">'+d+'</option>' );
                    select.append( "<option value='"+d+"'>"+d+"</option>" );
                } );
            } );
        }
        // end dropdown

     } );

     console.log("initProfileEventsDataTable ::: Finished initializing the events DataTable");

     console.debug("initProfileEventsDataTable ::: Stop");
}



/**
 * @name reInitProfileEventsDataTable
 * @description destroy and re-init the monoto events DataTable in profile view
 * @memberof profile
 */
function reInitProfileEventsDataTable()
{
    console.debug("reInitProfileEventsDataTable ::: Start");

    console.log("reInitProfileEventsDataTable ::: Starting to re-init the Monoto Users DataTable");

    // Destroy datatable
    $( "#myEventsDataTable" ).DataTable().destroy();
    $( "myEventsDataTable" ).empty();

    // reload datatable
    initProfileEventsDataTable();

    console.debug("reInitProfileEventsDataTable ::: Stop");
}




/**
 * @name doChangeProfilePassword
 * @description realizes a password change
 * @memberof profile
 */
function doChangeProfilePassword()
{
    console.debug("doChangeProfilePassword ::: Start");

    var password = $("#newPassword").val();

    var jqxhr = $.post( "inc/profileChangeUserPW.php", { password: password }, function()
    {
        console.log("doChangeProfilePassword ::: successfully updated user password");
    })
    .done(function()
    {
        console.log("doChangeProfilePassword ::: done");
        createNoty("Successfully changed password","success");

        // reset password fields
        $("#newPassword").val("");
        $("#newPasswordConfirm").val("");

        // disable button
        $("#bt_continue").prop("disabled",true);
    })
    .fail(function(jqxhr, textStatus, errorThrown)
    {
        console.error("doChangeProfilePassword ::: $.post failed");
        console.log(jqxhr);
        console.log(textStatus);
        console.log(errorThrown);

        createNoty("Updating password failed", "error");
    })
    .always(function()
    {
        // doing nothing so far
    });

    console.debug("doChangeProfilePassword ::: Stop");
}


/**
 * @name enableUpdateUserProfileLanguageButton
 * @description enables the update button for the language selection in a users profile
 * @memberof profile
 */
function enableUpdateUserProfileLanguageButton()
{
    console.debug("enableUpdateUserProfileLanguageButton ::: Start.");

    // enable the update profile language button
    $("#doChangeUserLanguage").prop("disabled", false);

    console.log("enableUpdateUserProfileLanguageButton ::: Enabled the profile language update button.");

    console.debug("enableUpdateUserProfileLanguageButton ::: Stop.");
}



/**
 * @name doChangeProfileLanguage
 * @description realizes a profile language change
 * @memberof profile
 */
function doChangeProfileLanguage()
{
    console.debug("doChangeProfileLanguage ::: Start");

    var language = $( "#s_languageSelector option:selected" ).text();
    console.log("doChangeProfileLanguage ::: User selected new language:" + language);

    var jqxhr = $.post( "inc/profileChangeUserLanguage.php", { language: language }, function()
    {
        console.log("doChangeProfileLanguage ::: successfully updated user password");
    })
    .done(function()
    {
        console.log("doChangeProfileLanguage ::: done");
        createNoty("Successfully changed language to "+ language,"success");

        // enable the update profile language button
        $("#doChangeUserLanguage").prop("disabled", true);
    })
    .fail(function(jqxhr, textStatus, errorThrown)
    {
        console.error("doChangeProfileLanguage ::: $.post failed");
        console.log(jqxhr);
        console.log(textStatus);
        console.log(errorThrown);

        createNoty("Changing language failed", "error");
    })
    .always(function()
    {
        // doing nothing so far
    });

    console.debug("doChangeProfileLanguage ::: Stop");
}


/**
 * @name deleteAllMyUserEvents
 * @description deletes all events from the current user account
 * @memberof profile
 */
function deleteAllMyUserEvents()
{
    console.debug("deleteAllMyUserEvents ::: Start Delete-All-My-User-Events-Dialog.");

    console.log("deleteAllMyUserEvents ::: Ask user if he wants to delete all his events from table m_log");

    var x = noty({
        text: "Really delete all your events from log?",
        type: "confirm",
        dismissQueue: false,
        layout: "topRight",
        theme: "defaultTheme",
        buttons: [
            {
                addClass: "btn btn-primary", text: "Ok", onClick: function($noty)
                {
                    $noty.close();
                    console.log("deleteAllMyUserEvents ::: User confirmed the event eraser. Starting now ...");
                    $.post("inc/profileDeleteMyUserEvents.php");
                    createNoty("Deleted all events from log","success");

                    // re-init events table
                    reInitProfileEventsDataTable();
                }
            },
            {
                addClass: "btn btn-danger", text: "Cancel", onClick: function($noty)
                {
                    $noty.close();
                    console.log("deleteAllMyUserEvents ::: User cancelled the event eraser");
                    createNoty("Aborted","information");
                }
            }
        ]
    });

    console.debug("deleteAllMyUserEvents ::: Finished Delete-All-My-User-Events-Dialog.");
}



/**
 * @name deleteAllMyUserNotes
 * @description deletes all notes from the current user account
 * @memberof profile
 */
function deleteAllMyUserNotes()
{
    console.debug("deleteAllMyUserNotes ::: Start Delete-All-My-User-Notes-Dialog.");

    console.log("deleteAllMyUserNotes ::: Ask user if he wants to delete all his notes from table m_notes");

    var x = noty({
        text: "Really delete all your notes?",
        type: "confirm",
        dismissQueue: false,
        layout: "topRight",
        theme: "defaultTheme",
        buttons: [
        {
            addClass: "btn btn-primary", text: "Ok", onClick: function($noty)
            {
                $noty.close();
                console.log("deleteAllMyUserNotes ::: User confirmed the notes eraser. Starting now ...");
                $.post("inc/profileDeleteMyUserNotes.php");
                createNoty("Deleted all notes","success");
            }
        },
        {
            addClass: "btn btn-danger", text: "Cancel", onClick: function($noty)
            {
                $noty.close();
                console.log("deleteAllMyUserNotes ::: User cancelled the notes eraser");
                createNoty("Aborted","information");
            }
        }
    ]
    });
    console.debug("deleteAllMyUserNotes ::: Finished Delete-All-My-User-Notes-Dialog.");
}




/**
 * @name exportAllNotesFromUserAccount
 * @description exports all notes from a single user account
 * @memberof profile
 */
function exportAllNotesFromUserAccount()
{
    console.debug("exportAllNotesFromUserAccount ::: Start exporting notes from this account.");

    console.log("exportAllNotesFromUserAccount ::: Ask user if he wants to export all his notes");

    var x = noty({
        text: "Do you really want to export all your notes?",
        type: "confirm",
        dismissQueue: false,
        layout: "topRight",
        theme: "defaultTheme",
        buttons: [
        {
            addClass: "btn btn-primary", text: "Ok", onClick: function($noty)
            {
                $noty.close();
                console.log("exportAllNotesFromUserAccount ::: User confirmed to export all notes. Starting now ...");

                // start generation and download of export as csv
                window.open("inc/noteExport.php", "width=400,height=500,top=50,left=280,resizable,toolbar,scrollbars,menubar,");
            }
        },
        {
            addClass: "btn btn-danger", text: "Cancel", onClick: function($noty)
            {
                $noty.close();
                console.log("exportAllNotesFromUserAccount ::: User cancelled the exporter");
                createNoty("Aborted","information");
            }
        }
    ]
    });
    console.debug("exportAllNotesFromUserAccount ::: Finished exporting all notes from current account.");
}



/**
 * @name toggleImportNotesFromCSVButton
 * @description toggles the import-from-csv-startbutton. If a csv file is selected, it enables it, otherwise it disables it.
 * @memberof profile
 */
function toggleImportNotesFromCSVButton()
{
    console.debug("toggleImportNotesFromCSVButton ::: Start");
    var selectedCSVFileToImport = $("#impFile").val();
    if (selectedCSVFileToImport === "")
    {
        // disable import CSV button
        $("#doImportCSV").prop("disabled",true);
    }
    else
    {
        // enable import CSV button
        $("#doImportCSV").prop("disabled",false);
    }
    console.debug("toggleImportNotesFromCSVButton ::: End");
}









/**
 * @name importNotesFromCSV
 * @description imports notes from a single CSV file (semicolon as separator)
 * @memberof profile
 */
function importNotesFromCSV()
{
    console.debug("importNotesFromCSV ::: Start exporting notes from this account.");

    console.log("importNotesFromCSV ::: Ask user if he wants to import notes from csv file");

    var x = noty({
        text: "Do you really want to import notes from the selected csv file?",
        type: "confirm",
        dismissQueue: false,
        layout: "topRight",
        theme: "defaultTheme",
        buttons: [
        {
            addClass: "btn btn-primary", text: "Ok", onClick: function($noty)
            {
                $noty.close();
                console.log("importNotesFromCSV ::: User confirmed to import notes from csv. Starting now ...");






                /*
                var jqxhr = $.post( "inc/profileImportFromCSV.php", { importCSV: importCSV }, function()
                {
                    console.log("importNotesFromCSV ::: successfully imported notes from csv");
                })
                .done(function()
                {
                    console.log("importNotesFromCSV ::: done");
                    createNoty("Successfully imported notes from csv","success");
                })
                .fail(function(jqxhr, textStatus, errorThrown)
                {
                    console.error("importNotesFromCSV ::: $.post failed");
                    console.log(jqxhr);
                    console.log(textStatus);
                    console.log(errorThrown);

                    createNoty("Importing notes from csv failed", "error");
                })
                .always(function()
                {
                    // doing nothing so far
                });
                */





            }
        },
        {
            addClass: "btn btn-danger", text: "Cancel", onClick: function($noty)
            {
                $noty.close();
                console.log("importNotesFromCSV ::: User cancelled the csv importer");
                createNoty("Aborted","information");
            }
        }
    ]
    });
    console.debug("importNotesFromCSV ::: Finished exporting all notes from current account.");
}









/**
 * @name onProfilePageReady
 * @description init the notes view
 * @memberof profile
 */
function onProfilePageReady()
{
    console.debug("onProfilePageReady ::: Start");

    console.log("onProfilePageReady ::: Profile is ready");

    // prepare the csv importer
    /*
    $("#impFile").on("change", function (e)
    {
        var file = $(this)[0].files[0];
        var upload = new Upload(file);

        // maby check size or type here with upload.getSize() and upload.getType()
        console.log("onProfilePageReady ::: Starting upload of csv file for importer");

        // execute upload
        upload.doUpload();

    });
    */

    console.debug("onProfilePageReady ::: End");
}









// https://stackoverflow.com/questions/2320069/jquery-ajax-file-upload




var Upload = function (file) {
    this.file = file;
};

Upload.prototype.getType = function() {
    return this.file.type;
};
Upload.prototype.getSize = function() {
    return this.file.size;
};
Upload.prototype.getName = function() {
    return this.file.name;
};
Upload.prototype.doUpload = function () {
    var that = this;
    var formData = new FormData();

    // add assoc key values, this will be posts values
    formData.append("file", this.file, this.getName());
    formData.append("upload_file", true);

    $.ajax({
        type: "POST",
        url: "script",
        xhr: function () {
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload)
            {
                myXhr.upload.addEventListener("progress", that.progressHandling, false);
            }
            return myXhr;
        },
        success: function (data)
        {
            console.warn("upload worked...");

            // console.warn(that);
            importCSV = that;

            console.error(importCSV);

            var jqxhr = $.post( "inc/profileImportFromCSV.php", { importCSV: importCSV }, function()
            {
                console.log("importNotesFromCSV ::: successfully imported notes from csv");
            })
            .done(function()
            {
                console.log("importNotesFromCSV ::: done");
                createNoty("Successfully imported notes from csv","success");
            })
            .fail(function(jqxhr, textStatus, errorThrown)
            {
                console.error("importNotesFromCSV ::: $.post failed");
                console.log(jqxhr);
                console.log(textStatus);
                console.log(errorThrown);

                createNoty("Importing notes from csv failed", "error");
            })
            .always(function()
            {
                // doing nothing so far
            });

        },
        error: function (error)
        {
            // handle error
            console.error("upload failed...");
        },
        async: true,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        timeout: 60000
    });
};

Upload.prototype.progressHandling = function (event) {
    var percent = 0;
    var position = event.loaded || event.position;
    var total = event.total;
    var progress_bar_id = "#progress-wrp";
    if (event.lengthComputable) {
        percent = Math.ceil(position / total * 100);
    }
    // update progressbars classes so it fits your code
    $(progress_bar_id + " .progress-bar").css("width", +percent + "%");
    $(progress_bar_id + " .status").text(percent + "%");
};

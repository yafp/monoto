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
            if ( data[1] === "create" ) {
               $("td:eq(1)", row).addClass("m_greenLight");
            }

            // event = delete
            if ( data[1] === "delete" ) {
               $("td:eq(1)", row).addClass("m_orangeLight");
            }

            // event = save
            if ( data[1] === "save" ) {
               $("td:eq(1)", row).addClass("m_yellowLight");
            }

            // event = login
            if ( data[1] === "login" ) {
               $("td:eq(1)", row).addClass("m_blueLight");
            }

            // event = logout
            if ( data[1] === "logout" ) {
               $("td:eq(1)", row).addClass("m_blueDark");
            }

            // event = login error
            if ( data[1] === "login error" ) {
               $("td:eq(1)", row).addClass("m_redLight");
            }

            // event = event eraser
            if ( data[1] === "events eraser" ) {
               $("td:eq(1)", row).addClass("m_pinkLight");
            }

            // event = event eraser
            if ( data[1] === "notes eraser" ) {
               $("td:eq(1)", row).addClass("m_pinkDark");
            }

            // event = account eraser
            if ( data[1] === "account eraser" ) {
               $("td:eq(1)", row).addClass("m_orangeDark");
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
            this.api().columns(':eq(1)').every(function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' );
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
        createNoty("Successfully changed language","success");
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

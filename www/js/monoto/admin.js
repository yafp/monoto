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
    } );

    console.debug("initMonotoUsersDataTable ::: Stop");
}

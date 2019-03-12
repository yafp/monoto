// -----------------------------------------------------------------------------
// Navigation
// detects the current file & sets the related navigation as active
// -----------------------------------------------------------------------------

/**
 * @description detects the current displayed page & sets the related navigation entry as active
 */
// get name of current html/php file
var path = window.location.pathname;
var page = path.split("/").pop();
console.log("navigation.js ::: Current page is: '" + page + "'.");

// set current nav list item active
switch(page)
{
    case "notes.php":
        $("#navNotes").addClass("active");
        console.log("navigation.js ::: Set " + page + " as active navigation entry.");
        break;

    case "profile.php":
        $("#navProfile").addClass("active");
        console.log("navigation.js ::: Set " + page + " as active navigation entry.");
        break;

    case "keyboard.php":
        $("#navKeyboard").addClass("active");
        console.log("navigation.js ::: Set " + page + " as active navigation entry.");
        break;

    case "admin.php":
        $("#navAdmin").addClass("active");
        console.log("navigation.js ::: Set " + page + " as active navigation entry.");
        break;

    default:
        console.error("navigation.js ::: ERROR - Realizing issues while trying to set active navigation entry.");
}

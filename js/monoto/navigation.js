// -----------------------------------------------------------------------------
// Navigation
// detects the current file & sets the related navigation as active
// -----------------------------------------------------------------------------

// get name of current html/php file
var path = window.location.pathname;
var page = path.split("/").pop();
console.log("navigation.js ::: Current page is: '" + page + "'.");

// set current nav list item active
switch(page)
{
    case "n.php":
        $("#n").addClass("active");
        console.log("navigation.js ::: Set " + page + " as active navigation entry.");
        break;

    case "p.php":
        $("#p").addClass("active");
        console.log("navigation.js ::: Set " + page + " as active navigation entry.");
        break;

    case "k.php":
        $("#k").addClass("active");
        console.log("navigation.js ::: Set " + page + " as active navigation entry.");
        break;

    case "a.php":
        $("#a").addClass("active");
        console.log("navigation.js ::: Set " + page + " as active navigation entry.");
        break;

    default:
        console.error("navigation.js ::: ERROR - Realizing issues while trying to set active navigation entry.");
}

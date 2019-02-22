// -----------------------------------------------------------------------------
// DISABLING the right click - used for the entire monoto userinterface
// -----------------------------------------------------------------------------
$(document).bind("contextmenu",function(e)
{
    return false;
});


// -----------------------------------------------------------------------------
// Calculates password strength
// -----------------------------------------------------------------------------
function passwordStrength()
{
    console.debug("passwordStrength ::: Start");

    var strongRegex = new RegExp("^(?=.{10,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
    var mediumRegex = new RegExp("^(?=.{9,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
    var enoughRegex = new RegExp("(?=.{8,}).*", "g");

    curPasswordString = $("#newPassword").val();

    if (false === enoughRegex.test(curPasswordString)) // < 8 chars
    {
        console.log("passwordStrength ::: More characters");
        $("#passstrength").html('More Characters');
    }
    else if (strongRegex.test(curPasswordString))
    {
        console.log("passwordStrength ::: OK");
        $("#passstrength").className = "ok";
        $("#passstrength").html("Strong");
     }
     else if (mediumRegex.test(curPasswordString))
     {
        console.log("passwordStrength ::: alert");
        $("#passstrength").className = "alert";
        $("#passstrength").html("Medium");
     }
     else
     {
        console.log("passwordStrength ::: error");
        $("#passstrength").className = "error";
        $("#passstrength").html("Weak");
     }

    console.debug("passwordStrength ::: End");

    //return true;
}


// -----------------------------------------------------------------------------
// Compare password change fields and enable or disable the update button
// -----------------------------------------------------------------------------
function validatePasswordChangeInput()
{
    console.debug("validatePasswordChangeInput ::: Started validating user input");

    if ($("#newPassword").val() === $("#newPasswordConfirm").val())
    {
            // password & passwordConfirm do match

            // now check if min length is reached
        if($("#newPassword").val().length > 7)
        {
                console.log("p.php ::: Passwords do match and min length is reached");
                // enable button
                $("#bt_continue").prop("disabled",false);
                // update status icon
                $("#passwordDiff").text("");
                $("#passwordDiff").append("<i class='fas fa-smile'></i>");
        }
        else
        {
            console.warn("p.php ::: Passwords do match but min length is not reached");
            // disable button
            $("#bt_continue").prop("disabled",true);
            // update status icon
            $("#passwordDiff").text("");
            $("#passwordDiff").append('<i class="far fa-frown"></i>');
        }
    }
    else
    {
        console.warn("p.php ::: Passwords do not match");
        // disable button
        $("#bt_continue").prop("disabled",true);
        // update status icon
        $("#passwordDiff").text("");
        $("#passwordDiff").append('<i class="far fa-frown"></i>');
    }

    console.debug("validatePasswordChangeInput ::: Finished validating user input");
}


// -----------------------------------------------------------------------------
// init noty notification
// -----------------------------------------------------------------------------
$.noty.defaults = {
    layout: "topRight",
    theme: "defaultTheme",
    type: "alert",
    text: "",
    dismissQueue: true, // If you want to use queue feature set this true
    template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
    animation:
    {
        open: {
            //animation: 'animated fadeInRight', // ?
            height: "toggle"
        },
        close: {
            height: "toggle"
        },
        easing: "swing",
        speed: 500 // opening & closing animation speed
    },
    timeout: 5000, // delay for closing event. Set false for sticky notifications
    force: false, // adds notification to the beginning of queue when set to true
    modal: false,
    closeWith: ["click"], // ['click', 'button', 'hover']
    callback: {
        /*
        onShow: function() {},
        afterShow: function() {},
        onClose: function() {},
        afterClose: function() {}
        */

        onShow() {},
        afterShow() {},
        onClose() {},
        afterClose() {}
    },
    buttons: false // an array of buttons
};


// -----------------------------------------------------------------------------
// Displays a noty logout dialog
// -----------------------------------------------------------------------------
function showLogoutDialog()
{
    console.debug("showLogoutDialog ::: Start Display-Logout-Dialog");

    var x = noty({
        text: "Do you really want to logout?",
        type: "confirm",
        dismissQueue: false,
        layout: "topRight",
        theme: "defaultTheme",
        buttons: [
        {
            addClass: "btn btn-primary", text: "Ok", onClick: function($noty)
            {
                $noty.close();
                window.location.href = "logout.php";
            }
        },
        {
            addClass: "btn btn-danger", text: "Cancel", onClick: function($noty)
            {
                $noty.close();
            }
        }
        ]
    });

    console.debug("showLogoutDialog ::: Finished Display-Logout-Dialog");
}


// -----------------------------------------------------------------------------
// Displays a noty notification
// -----------------------------------------------------------------------------
function createNoty(text, type)
{
    //console.log("createNoty ::: Display a noty notification");

    // NOTY-TYPES:
	//
	// alert
	// information
	// error
	// warning
	// notification
	// success
    var n = noty({text: text, type: type});
}

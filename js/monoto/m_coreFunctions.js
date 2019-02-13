// -----------------------------------------------------------------------------
// DISABLING the right click - used for the entire monoto userinterface
// -----------------------------------------------------------------------------
$(document).bind("contextmenu",function(e)
{
    return false;
});


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
    console.log("showLogoutDialog ::: Display a logout dialog");

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
}


// -----------------------------------------------------------------------------
// Displays a noty notification
// -----------------------------------------------------------------------------
function createNoty(text, type)
{
    console.log("createNoty ::: Display a noty notification");
    
    var n = noty({text: text, type: type});
}

// -----------------------------------------------------------------------------
// Displays a logout dialog
// -----------------------------------------------------------------------------
function showLogoutDialog()
{
    functionName = "showLogoutDialog";
    logToConsole(functionName, "Show a logout dialog");

    var x = noty({
        text: 'Do you really want to logout?',
        //text: '<?php echo "foo"; ?>Do you really want to logout?',
        type: "confirm",
        dismissQueue: false,
        layout: "topRight",
        theme: "defaultTheme",
        buttons: [
            {addClass: "btn btn-primary", text: "Ok", onClick: function($noty) {
                $noty.close();
                window.location.href = "logout.php";
            }
        },
        {addClass: "btn btn-danger", text: "Cancel", onClick: function($noty) {
            $noty.close();
        }
    }
]
})
}

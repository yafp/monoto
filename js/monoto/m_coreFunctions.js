// --------------------------------------------
// display a desktop notification - if possible
// --------------------------------------------
//
function displayDesktopNotification(notificationText)
{
	if (!("Notification" in window)) // Let's check if the browser supports notifications
	{
		console.log("Warning: This browser does not support desktop notification");
	}
	else if (Notification.permission === "granted") // Let's check if the user is okay to get some notification
	{
		var notification = new Notification(notificationText);		// If it's okay let's create a notification
	}
	// Otherwise, we need to ask the user for permission
	else if (Notification.permission !== 'denied') 
	{
		Notification.requestPermission(function (permission) 
		{
			if (permission === "granted") 	// If the user is okay, let's create a notification
			{
				var notification = new Notification(notificationText);
			}
		});
	}
	// At last, if the user already denied any notification, and you want to be respectful there is no need to bother them any more.
}


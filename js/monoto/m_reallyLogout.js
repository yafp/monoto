function reallyLogout()
{
	var x = noty({
		text: 'Do you really want to logout?',
		type: 'confirm',
		dismissQueue: false,
		layout: 'topRight',
		theme: 'defaultTheme',
		buttons: [
			{addClass: 'btn btn-primary', text: 'Ok', onClick: function($noty) {
					$noty.close();
					window.location.href = 'logout.php';
				}
			},
			{addClass: 'btn btn-danger', text: 'Cancel', onClick: function($noty) {
				$noty.close();
				noty({text: 'Cancelled logout', type: 'error'});
				}
			}
		]
	})
}

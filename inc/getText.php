<?php

//
// Translating the UserInterface (#210)
//
function translateString($textForTranslation) 
{
	if ($_SESSION['getText'] == 0)
	//if (!function_exists("gettext")) // gettext is not installed - fallback
	{
		$translation = $textForTranslation;
	}
	else // gettext is installed - translate
	{
		// I18N support information here
		$language = $_SESSION['lang'];
		if($language == "") // Fallback to english
		{
			$language = "en_US";
		}

		putenv("LANG=$language"); 
		setlocale(LC_ALL, $language);

		// Set the text domain as 'messages'
		$domain = 'monoto';
		bindtextdomain($domain, "./locale"); 
		textdomain($domain);

		$translation = gettext($textForTranslation);
	}
	return $translation;
}
?>

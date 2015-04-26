<?php

function translateString($textForTranslation) 
{
	if (!function_exists("gettext")) // gettext is not installed - fallback
	{
		$translation = $textForTranslation;
	}
	else // gettext is installed - translate
	{
		// I18N support information here
		//
		//$language = 'en_US';
		//$language = 'de_DE';
		//$language = 'fr_FR';
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

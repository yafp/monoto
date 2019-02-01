<?php

// -----------------------------------------------------------------------------
// Creates a noty notification popup
// -----------------------------------------------------------------------------
function displayNoty($notyText, $notyType)
{
	// NOTY-TYPES:
	//
	// alert
	// information
	// error
	// warning
	// notification
	// success
	echo "<script>var n = noty({text: '".$notyText."', type: '".$notyType."'});</script>";	// display notification
}



// -----------------------------------------------------------------------------
// Write to JavaScript developer console
// -----------------------------------------------------------------------------
function writeToConsoleLog($message)
{
	echo "<script>console.log('".$message."')</script>"; // write to js console
}



// -----------------------------------------------------------------------------
// Translating the UserInterface (#210)
// -----------------------------------------------------------------------------
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

		// report non-translated texts for debugging
		if($translation == $textForTranslation)
		{
			if($language != "en_US")
			{
				writeToConsoleLog("Translation-issue: _".$textForTranslation."_");
			}
		}
	}
	return $translation;
}

?>

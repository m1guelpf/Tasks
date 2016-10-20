<?php
	// Settings
	error_reporting(0);													// Error Logging OFF in Production Environments
	ini_set('display_errors', '0');

	// Sets the default timezone used by all date/time functions.
	// List of Supported Timezones: http://php.net/manual/en/timezones.php
	date_default_timezone_set('Europe/Madrid');

	// Globals
	$installDate	= 'September 2016';					// Date Installed
	$siteName		= 'MPTasks Demo';				// Site Name
	$siteUrl		= 'https://demo.miguelpiedrafita.com/tasks';	// Site URL, where you have uploaded Simple Tasks to. Include the trailing slash
	$siteEmail		= 'soy@miguelpiedrafita.com';			// Site Email. Used in all email notifications
	$language		= 'english';					// Site Language (options: english or custom). Language files are located in the "language" folder
	$signupstatus		= true;							// Signup Status (options: true or false). Set it to false to disable signup.

	define('PEPPER', 'LeRzpnVqmrDyZ$q6fZb#Sv2!7MqP3CPn');			// !IMPORTANT! Do NOT change this value once you have started using Tasks.
?>

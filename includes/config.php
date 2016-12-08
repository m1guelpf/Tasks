<?php
    // Settings
    error_reporting(0);                                                    // Error Logging OFF in Production Environments
    ini_set('display_errors', '0');

    // Sets the default timezone used by all date/time functions.
    // List of Supported Timezones: http://php.net/manual/en/timezones.php
    date_default_timezone_set('Europe/Madrid');

    // Globals
    $installDate = 'September 2016';                    // Date Installed
    $siteName = getenv('SITENAME');                // Site Name
    $siteUrl = getenv('APPURL').'.herokuapp.com/';    // Site URL, where you have uploaded Simple Tasks to. Include the trailing slash
    $siteEmail = getenv('EMAIL');            // Site Email. Used in all email notifications
    $language = getenv('LANG');                    // Site Language (options: english or custom). Language files are located in the "language" folder
    $signupstatus = getenv('SIGNUP');                            // Signup Status (options: true or false). Set it to false to disable signup.

    define('PEPPER', getenv('PEPPER'));            // !IMPORTANT! Do NOT change this value once you have started using Tasks.;

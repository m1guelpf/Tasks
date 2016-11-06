<?php
    ini_set('session.cookie_httponly', true);            // Helps mitigate xss
    ini_set('session.session.use_only_cookies', true);    // Prevents session fixation
    ini_set('session.cookie_lifetime', false);            // Smaller exploitation window for xss/csrf/clickjacking...
    ini_set('session.cookie_secure', true);                // Owasp a9 violations

    // Start Sessions
    if (!isset($_SESSION)) {
        session_start();
    }                // Session Start

    // Set Localization (Set this value in the includes/config.php file)
    switch ($language) {
        case 'custom':    include 'language/custom.php'; break;
        case 'english':    include 'language/english.php'; break;
    }

    // Session Data
    if ((isset($_SESSION['st']['userId'])) && ($_SESSION['st']['userId'] != '')) {
        // Keep some User data available
        $st_userId = $_SESSION['st']['userId'];
        $st_username = $_SESSION['st']['userName'];
        $st_useremail = $_SESSION['st']['userEmail'];
    } else {
        $st_userId = $st_username = $st_useremail = '';
    }

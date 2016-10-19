<?php
	if(!isset($_SESSION)) session_start();

	// Signed In User check
	if (!isset($_SESSION['st']['userId'])) {
		header ('Location: sign-in.php');
		exit;
	}

	// Include Settings
	include('includes/config.php');

	// Include Functions
	include('includes/functions.php');

	// Include Sessions & Localizations
	include('includes/sessions.php');

	// Account Sign Out
	if (isset($_GET['action']) && $_GET['action'] == 'signout') {
		session_destroy();
		header ('Location: sign-in.php');
	}

	// Link to the Page
	if (isset($_GET['page']) && $_GET['page'] == 'newTask') {			$page = 'newTask';
	} else if (isset($_GET['page']) && $_GET['page'] == 'viewTask') {	$page = 'viewTask';
	} else if (isset($_GET['page']) && $_GET['page'] == 'notes') {		$page = 'notes';
	} else if (isset($_GET['page']) && $_GET['page'] == 'newNote') {	$page = 'newNote';
	} else if (isset($_GET['page']) && $_GET['page'] == 'viewNote') {	$page = 'viewNote';
	} else if (isset($_GET['page']) && $_GET['page'] == 'profile') {	$page = 'profile';
	} else {															$page = 'dashboard';}

	if (file_exists('pages/'.$page.'.php')) {
		// Load the Page
		include('pages/'.$page.'.php');
	} else {
		echo '<h3>Error &mdash; Page Not Found.</h3>';
	}

	include('includes/footer.php');
?>
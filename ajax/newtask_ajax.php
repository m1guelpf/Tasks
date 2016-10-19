<?php
	if(!isset($_SESSION)) session_start();

	require('../includes/config.php');
	require('../includes/functions.php');
	require('../includes/flatfile.php');

	$db = new Flatfile();
	$db->datadir = '../data/tasks/';

	define('USER_ID',		0);
	define('TASK_ID',		1);
	define('TASK_TITLE',	2);
	define('TASK_DATE',		3);
	define('DATE_DUE',		4);
	define('TASK_TYPE',		5);
	define('REFERENCE',		6);
	define('PERC_COMPLETE',	7);
	define('DATE_COMPLTED',	8);
	define('TASK_DESC',		9);
	define('TASK_NOTES',	10);
	define('TASK_STATUS',	11);
	define('UPDATE_DATE',	12);

	// Generate a RANDOM Hash
	$randomHash = uniqid(rand());
	// Take the first 8 hash digits and use it as part of the Task's ID
	$randHash = substr($randomHash, 0, 8);

	$uid = $_SESSION['st']['userId'];
	$uname = $_SESSION['st']['userName'];

	$newtask[USER_ID] 			= $uid;
	$newtask[TASK_ID] 			= $uname.'-'.$randHash;
	$newtask[TASK_TITLE] 		= htmlspecialchars($_POST['taskTitle']);
	$newtask[TASK_DATE] 		= htmlspecialchars($_POST['dateAssigned']);
	$newtask[DATE_DUE] 			= htmlspecialchars($_POST['dateDue']);
	$newtask[TASK_TYPE] 		= encodeIt($_POST['taskType']);
	$newtask[REFERENCE] 		= encodeIt($_POST['taskRef']);
	$newtask[PERC_COMPLETE] 	= '0';
	$newtask[DATE_COMPLTED]		= NULL;
	$newtask[TASK_DESC]			= NULL;
	$newtask[TASK_NOTES]		= NULL;
	$newtask[TASK_STATUS]		= NULL;
	$newtask[UPDATE_DATE]		= date("Y-m-d H:i:s");

	$taskdata[USER_ID] 			= $uid;
	$taskdata[TASK_ID] 			= $uname.'-'.$randHash;
	$taskdata[TASK_TITLE] 		= NULL;
	$taskdata[TASK_DATE] 		= NULL;
	$taskdata[DATE_DUE] 		= NULL;
	$taskdata[TASK_TYPE] 		= NULL;
	$taskdata[REFERENCE] 		= NULL;
	$taskdata[PERC_COMPLETE] 	= NULL;
	$taskdata[DATE_COMPLTED]	= NULL;
	$taskdata[TASK_DESC]		= encodeIt($_POST['taskDesc']);
	$taskdata[TASK_NOTES]		= NULL;
	$taskdata[TASK_STATUS]		= NULL;
	$taskdata[UPDATE_DATE]		= date("Y-m-d H:i:s");

	// Create the Task File (username-hash.txt)
	$taskFile = $uname.'-'.$randHash;

	// Add the Task to the tasks.txt file
	$new_task = $db->insert(
		'tasks.txt',
		$newtask
	);

	// Create the Task file
	$task_data = $db->insert(
		$taskFile.'.txt',
		$taskdata
	);

	// Check if the file was created
	$checkFile = '../data/tasks/'.$taskFile.'.txt';

	if (file_exists($checkFile)) {
		echo '1';	// All is good!
	} else {
		echo '0';	// Nope, error...
	}
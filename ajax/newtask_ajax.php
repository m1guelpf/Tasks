<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    require '../includes/config.php';
    require '../includes/functions.php';
    require '../includes/flatfile.php';

    $db = new Flatfile();
    $db->datadir = '../data/tasks/';

    define('USER_ID', 0);
    define('TASK_ID', 1);
    define('TASK_TITLE', 2);
    define('TASK_DATE', 3);
    define('DATE_DUE', 4);
    define('TASK_TYPE', 5);
    define('REFERENCE', 6);
    define('PERC_COMPLETE', 7);
    define('DATE_COMPLTED', 8);
    define('TASK_DESC', 9);
    define('TASK_NOTES', 10);
    define('TASK_STATUS', 11);
    define('UPDATE_DATE', 12);

    // Generate a RANDOM Hash
    $randomHash = uniqid(rand());
    // Take the first 8 hash digits and use it as part of the Task's ID
    $randHash = substr($randomHash, 0, 8);

    $uid = $_SESSION['st']['userId'];
    $uname = $_SESSION['st']['userName'];

    $newtask[USER_ID] = $uid;
    $newtask[TASK_ID] = $uname.'-'.$randHash;
    $newtask[TASK_TITLE] = htmlspecialchars($_POST['taskTitle']);
    $newtask[TASK_DATE] = htmlspecialchars($_POST['dateAssigned']);
    $newtask[DATE_DUE] = htmlspecialchars($_POST['dateDue']);
    $newtask[TASK_TYPE] = encodeIt($_POST['taskType']);
    $newtask[REFERENCE] = encodeIt($_POST['taskRef']);
    $newtask[PERC_COMPLETE] = '0';
    $newtask[DATE_COMPLTED] = null;
    $newtask[TASK_DESC] = null;
    $newtask[TASK_NOTES] = null;
    $newtask[TASK_STATUS] = null;
    $newtask[UPDATE_DATE] = date('Y-m-d H:i:s');

    $taskdata[USER_ID] = $uid;
    $taskdata[TASK_ID] = $uname.'-'.$randHash;
    $taskdata[TASK_TITLE] = null;
    $taskdata[TASK_DATE] = null;
    $taskdata[DATE_DUE] = null;
    $taskdata[TASK_TYPE] = null;
    $taskdata[REFERENCE] = null;
    $taskdata[PERC_COMPLETE] = null;
    $taskdata[DATE_COMPLTED] = null;
    $taskdata[TASK_DESC] = encodeIt($_POST['taskDesc']);
    $taskdata[TASK_NOTES] = null;
    $taskdata[TASK_STATUS] = null;
    $taskdata[UPDATE_DATE] = date('Y-m-d H:i:s');

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
        echo '1';    // All is good!
    } else {
        echo '0';    // Nope, error...
    }

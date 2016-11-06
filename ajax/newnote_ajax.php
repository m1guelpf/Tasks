<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    require '../includes/config.php';
    require '../includes/functions.php';
    require '../includes/flatfile.php';

    $db = new Flatfile();
    $db->datadir = '../data/notes/';

    define('USER_ID', 0);
    define('NOTE_ID', 1);
    define('NOTE_TITLE', 2);
    define('NOTE_DATE', 3);
    define('NOTE_TEXT', 4);
    define('UPDATE_DATE', 5);

    // Generate a RANDOM Hash
    $randomHash = uniqid(rand());
    // Take the first 8 hash digits and use it as part of the Note's ID
    $randHash = substr($randomHash, 0, 8);

    $uid = $_SESSION['st']['userId'];
    $uname = $_SESSION['st']['userName'];

    $newnote[USER_ID] = $uid;
    $newnote[NOTE_ID] = $uname.'-'.$randHash;
    $newnote[NOTE_TITLE] = htmlspecialchars($_POST['noteTitle']);
    $newnote[NOTE_DATE] = date('Y-m-d H:i:s');
    $newnote[NOTE_TEXT] = null;
    $newnote[UPDATE_DATE] = date('Y-m-d H:i:s');

    $notedata[USER_ID] = $uid;
    $notedata[NOTE_ID] = $uname.'-'.$randHash;
    $notedata[NOTE_TITLE] = null;
    $notedata[NOTE_DATE] = null;
    $notedata[NOTE_TEXT] = encodeIt($_POST['notesText']);
    $notedata[UPDATE_DATE] = date('Y-m-d H:i:s');

    // Create the Note File (username-hash.txt)
    $noteFile = $uname.'-'.$randHash;

    // Add the Note to the notes.txt file
    $new_task = $db->insert(
        'notes.txt',
        $newnote
    );

    // Create the Note file
    $task_data = $db->insert(
        $noteFile.'.txt',
        $notedata
    );

    // Check if the file was created
    $checkFile = '../data/notes/'.$noteFile.'.txt';

    if (file_exists($checkFile)) {
        echo '1';    // All is good!
    } else {
        echo '0';    // Nope, error...
    }

<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    require '../includes/config.php';
    require '../includes/functions.php';
    require '../includes/flatfile.php';

    $db = new Flatfile();
    $db->datadir = '../data/notes/';

    $noteid = htmlspecialchars($_POST['noteid']);

    define('USER_ID', 0);
    define('NOTE_ID', 1);
    define('NOTE_TITLE', 2);
    define('NOTE_DATE', 3);
    define('NOTE_TEXT', 4);
    define('UPDATE_DATE', 5);

    $db->deleteWhere('notes.txt', new AndWhereClause(new SimpleWhereClause(NOTE_ID, '=', $noteid, STRING_COMPARISON)));

    // Delete the Note File
    unlink('../data/notes/'.$noteid.'.txt');
    unlink('../data/notes/'.$noteid.'.txt.lock');

    // Check if the file was deleted
    $checkFile = '../data/notes/'.$noteid.'.txt';

    if (file_exists($checkFile)) {
        echo '0';    // All is good!
    } else {
        echo '1';    // Nope, error...
    }

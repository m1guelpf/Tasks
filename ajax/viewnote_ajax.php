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

    $noteTitle = htmlspecialchars($_POST['noteTitle']);
    $notesText = encodeIt($_POST['notesText']);
    $noteId = htmlspecialchars($_POST['noteId']);
    $updatDate = htmlspecialchars($_POST['updatDate']);
    $now = date('Y-m-d H:i:s');

    $db->updateSetWhere(
        'notes.txt', [
            NOTE_TITLE  => $noteTitle,
            UPDATE_DATE => $now,
        ],
        new SimpleWhereClause(
            NOTE_ID, '=', $noteId
        )
    );

    $db->updateSetWhere(
        $noteId.'.txt', [
            NOTE_TEXT   => $notesText,
            UPDATE_DATE => $now,
        ],
        new SimpleWhereClause(
            NOTE_ID, '=', $noteId
        )
    );

    $checkDate = strtotime($now);

    if ($checkDate > $updatDate) {
        echo '1';    // All is good!
    } else {
        echo '0';    // Nope, error...
    }

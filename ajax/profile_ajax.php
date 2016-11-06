<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    require '../includes/config.php';
    require '../includes/functions.php';
    require '../includes/flatfile.php';

    $db = new Flatfile();
    $db->datadir = '../data/';

    define('USER_ID', 0);
    define('USERNAME', 1);
    define('PASSWORD', 2);
    define('USER_EMAIL', 3);
    define('DATE_CREATED', 4);

    $uid = $_SESSION['st']['userId'];
    $userEmail = htmlspecialchars($_POST['userEmail']);
    if (isset($_POST['password1']) && $_POST['password1'] != '') {
        $password = encodeIt($_POST['password1']);
    } else {
        $password = $_POST['old'];
    }
    $nowstamp = $_POST['now'];
    $now = date('Y-m-d H:i:s');

    $db->updateSetWhere(
        'users.txt', [
            PASSWORD   => $password,
            USER_EMAIL => $userEmail,
        ],
        new SimpleWhereClause(
            USER_ID, '=', $uid
        )
    );

    $checkDate = strtotime($now);

    if ($checkDate > $nowstamp) {
        echo '1';    // All is good!
    } else {
        echo '0';    // Nope, error...
    }

<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    require '../includes/config.php';
    require '../includes/functions.php';
    require '../includes/flatfile.php';

    $db = new Flatfile();
    $db->datadir = '../data/tasks/';

    $mode = $_POST['requestType'];
    switch ($mode) {
        case 'updateData':
            updateData($db);
        break;
        case 'checkStatus':
            checkStatus(
                $db,
                $_POST['taskId']
            );
        break;
    }

    function updateData($db)
    {
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

        $taskTitle = htmlspecialchars($_POST['taskTitle']);
        $dateAssigned = htmlspecialchars($_POST['dateAssigned']);
        $dateDue = htmlspecialchars($_POST['dateDue']);
        $taskType = encodeIt($_POST['taskType']);
        $dateComp = htmlspecialchars($_POST['dateComp']);
        $taskStatus = encodeIt($_POST['taskStatus']);
        $taskRef = encodeIt($_POST['taskRef']);
        $taskDesc = encodeIt($_POST['taskDesc']);
        $taskNotes = encodeIt($_POST['taskNotes']);
        $taskId = htmlspecialchars($_POST['taskId']);
        $updatDate = htmlspecialchars($_POST['updatDate']);
        $now = date('Y-m-d H:i:s');

        $db->updateSetWhere(
            'tasks.txt', [
                TASK_TITLE    => $taskTitle,
                TASK_DATE     => $dateAssigned,
                DATE_DUE      => $dateDue,
                TASK_TYPE     => $taskType,
                REFERENCE     => $taskRef,
                DATE_COMPLTED => $dateComp,
                TASK_STATUS   => $taskStatus,
                UPDATE_DATE   => $now,
            ],
            new SimpleWhereClause(
                TASK_ID, '=', $taskId
            )
        );

        $db->updateSetWhere(
            $taskId.'.txt', [
                TASK_DESC   => $taskDesc,
                TASK_NOTES  => $taskNotes,
                TASK_STATUS => $taskStatus,
                UPDATE_DATE => $now,
            ],
            new SimpleWhereClause(
                TASK_ID, '=', $taskId
            )
        );

        $checkDate = strtotime($now);

        if ($checkDate > $updatDate) {
            echo '1';    // All is good!
        } else {
            echo '0';    // Nope, error...
        }
    }

    function checkStatus($db, $taskId)
    {
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

        $taskdata = $db->selectWhere(
            'tasks.txt',
            new SimpleWhereClause(TASK_ID, '=', $taskId)
        );

        echo json_encode($taskdata);
    }

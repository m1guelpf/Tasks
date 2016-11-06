<?php
    $taskId = $_GET['taskId'];

    require 'includes/flatfile.php';

    $db = new Flatfile();
    $db->datadir = 'data/tasks/';

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

    // Get the Task Data
    $task = $db->selectWhere(
        'tasks.txt',
        new SimpleWhereClause(TASK_ID, '=', $taskId)
    );
    $taskdata = $db->selectAll($taskId.'.txt');

    // Set some variables to empty
    $taskTitle = $dateAssigned = $dateDue = $taskType = $taskRef = $percComp = $dateComp = $taskDesc = $taskNotes = $taskStatus = $updatDate = '';

    foreach ($task as $k => $v) {
        $taskTitle = $v[2];
        if ($v[3] != '') {
            $dateAssigned = dbDateFormat($v[3]);
        } else {
            $dateAssigned = '';
        }
        if ($v[4] != '') {
            $dateDue = dbDateFormat($v[4]);
        } else {
            $dateDue = '';
        }
        if ($v[5] != '') {
            $taskType = decodeIt($v[5]);
        } else {
            $taskType = '';
        }
        if ($v[6] != '') {
            $taskRef = decodeIt($v[6]);
        } else {
            $taskRef = '';
        }
        if ($v[8] != '') {
            $dateComp = dbDateFormat($v[8]);
        } else {
            $dateComp = '';
        }
    }

    foreach ($taskdata as $k => $v) {
        if ($v[9] != '') {
            $taskDesc = decodeIt($v[9]);
        } else {
            $taskDesc = '';
        }
        if ($v[10] != '') {
            $taskNotes = decodeIt($v[10]);
        } else {
            $taskNotes = '';
        }
        if ($v[11] != '') {
            $taskStatus = decodeIt($v[11]);
        } else {
            $taskStatus = '';
        }
        if ($v[12] != '') {
            $updatDate = strtotime($v[12]);
        } else {
            $updatDate = '';
        }
    }

    $pageTitle = $viewTaskPageTitle;
    $addCss = '<link href="css/datetimepicker.css" rel="stylesheet">';
    $datePicker = 'true';
    $jsFile = 'viewTask';
    include 'includes/header.php';
?>
	<div class="wrapper">
		<?php include 'includes/navigation.php'; ?>

		<div class="content content-is-open">
			<div class="top-block">
				<div class="row">
					<div class="col-md-10">
						<p><?php echo $pageTitle; ?></p>
					</div>
					<div class="col-md-2 text-right">
						<span class="side-panel-toggle" data-toggle="tooltip" data-placement="left"title="<?php echo $openCloseNavText; ?>"><i class="fa fa-bars"></i></span>
					</div>
				</div>
			</div>

			<div class="page-content mt-30">
				<div id="msgDiv"></div>

				<form action="" method="post" class="mb-20">
					<div class="row">
						<div class="col-md-9">
							<div class="form-group">
								<label for="taskTitle"><?php echo $taskTitleText; ?></label>
								<input type="text" class="form-control" name="taskTitle" id="taskTitle" required="required" value="<?php echo $taskTitle; ?>" />
								<span class="help-block"><?php echo $textOnlySpan; ?></span>
							</div>
						</div>
						<div class="col-md-3 pt-10">
							<p class="text-right">
								<a href="#" id="pageBottom" class="btn btn-sm btn-info mt-20" data-toggle="tooltip" data-placement="top" title="<?php echo $pageBottomText; ?>"><i class="fa fa-angle-down"></i></a>
								<button type="input" name="submit" value="saveTask" id="taskSave" class="btn btn-sm btn-success btn-icon mt-20"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
							</p>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dateAssigned"><?php echo $dateAssignedText; ?></label>
								<input type="text" class="form-control" name="dateAssigned" id="dateAssigned" required="required" value="<?php echo $dateAssigned; ?>" />
								<span class="help-block"><?php echo $dateFormatSpan; ?></span>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="dateDue"><?php echo $dateDueText; ?></label>
								<input type="text" class="form-control" name="dateDue" id="dateDue" required="required" value="<?php echo $dateDue; ?>" />
								<span class="help-block"><?php echo $dateFormatSpan; ?></span>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="taskType"><?php echo $typeOfTaskText; ?></label>
								<input type="text" class="form-control" name="taskType" id="taskType" value="<?php echo $taskType; ?>" />
								<span class="help-block"><?php echo $notRequiredSpan; ?></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dateComp"><?php echo $dateCompletedText; ?></label>
								<input type="text" class="form-control" name="dateComp" id="dateComp" value="<?php echo $dateComp; ?>" />
								<span class="help-block"><?php echo $notRequiredSpan.' '.$dateFormatSpan; ?></span>
							</div>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<label for="taskStatus"><?php echo $taskStatusText; ?></label>
								<input type="text" class="form-control" name="taskStatus" id="taskStatus" value="<?php echo $taskStatus; ?>" />
								<span class="help-block"><?php echo $notRequiredSpan; ?></span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="taskRef"><?php echo $taskReferenceText; ?></label>
						<input type="text" class="form-control" name="taskRef" id="taskRef" value="<?php echo $taskRef; ?>" />
						<span class="help-block"><?php echo $notRequiredSpan; ?></span>
					</div>
					<div class="form-group">
						<label for="taskDesc"><?php echo $taskDescText; ?></label>
						<textarea class="form-control autosize" name="taskDesc" id="taskDesc" required="required" rows="5"><?php echo $taskDesc; ?></textarea>
					</div>
					<div class="form-group">
						<label for="taskNotes"><?php echo $taskNotesText; ?></label>
						<textarea class="form-control autosize" name="taskNotes" id="taskNotes" rows="5"><?php echo $taskNotes; ?></textarea>
					</div>

					<div class="row">
						<div class="col-md-6">
							<input type="hidden" name="tid" id="tid" value="<?php echo $taskId; ?>" />
							<input type="hidden" name="updatDate" id="updatDate" value="<?php echo $updatDate; ?>" />
							<button type="input" name="submit" value="saveTask" id="saveTask" class="btn btn-sm btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
						</div>
						<div class="col-md-6">
							<p class="text-right mb-0">
								<a href="#" id="pageTop" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="left" title="<?php echo $pageTopText; ?>"><i class="fa fa-angle-up"></i></a>
							</p>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
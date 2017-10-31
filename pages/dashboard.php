<?php
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

    // Ge the Logged In User's Task Data
    $res = $db->selectWhere('tasks.txt', new SimpleWhereClause(USER_ID, '=', $st_userId));

    $pageTitle = $myTasksText;
    $home = 'true';
    $addCss = '<link href="css/dataTables.css" rel="stylesheet">';
    $dataTables = 'true';
    $jsFile = 'dashboard';
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
						<span class="side-panel-toggle" data-toggle="tooltip" data-placement="left" title="<?php echo $openCloseNavText; ?>"><i class="fa fa-bars"></i></span>
					</div>
				</div>
			</div>

			<div class="page-content mt-30">
				<?php if (!empty($res)) {
    ?>
					<table id="tasks" class="display" cellspacing="0">
						<thead>
							<tr>
								<th><?php echo $taskTitleText; ?></th>
								<th class="text-center"><?php echo $typeText; ?></th>
								<th class="text-center"><?php echo $dateAssignedText; ?></th>
								<th class="text-center"><?php echo $dateDueText; ?></th>
								<th class="text-center"><?php echo $updatedText; ?></th>
								<th class="text-center"><?php echo $statusText; ?></th>
								<th class="text-center"><?php echo $completedText; ?></th>
								<th class="text-center"></th>
							</tr>
						</thead>
						<tbody>
							<?php
                                foreach ($res as $k => $v) {
                                    $taskBg = '';

                                    $taskId = $v[1];
                                    $taskTitle = $v[2];
                                    $taskDate = shortMonthFormat($v[3]);
                                    $dateDue = shortMonthFormat($v[4]);
                                    if ($v[5] != '') {
                                        $taskType = decodeIt($v[5]);
                                    } else {
                                        $taskType = '';
                                    }
                                    $taskRef = $v[6];
                                    $percComp = $v[7];
                                    if ($v[8] != '') {
                                        $dateComp = shortMonthFormat($v[8]);
                                    } else {
                                        $dateComp = '';
                                        $taskBg = 'indev';
                                    }
                                    if ($v[11] != '') {
                                        $taskStatus = decodeIt($v[11]);
                                    } else {
                                        $taskStatus = '';
                                    }
                                    $updDate = shortMonthTimeFormat($v[12]); ?>
									<tr>
										<td class="<?php echo $taskBg; ?>">
											<a href="index.php?page=viewTask&taskId=<?php echo $taskId; ?>" data-toggle="tooltip" data-placement="right" title="<?php echo $viewUpdateTaskText; ?>">
												<?php echo $taskTitle; ?>
											</a>
										</td>
										<td class="text-center <?php echo $taskBg; ?>"><small><?php echo $taskType; ?></small></td>
										<td class="text-center <?php echo $taskBg; ?>"><small><?php echo $taskDate; ?></small></td>
										<td class="text-center <?php echo $taskBg; ?>"><small><?php echo $dateDue; ?></small></td>
										<td class="text-center <?php echo $taskBg; ?>"><small><?php echo $updDate; ?></small></td>
										<td class="text-center <?php echo $taskBg; ?>"><small><?php echo $taskStatus; ?></small></td>
										<td class="text-center <?php echo $taskBg; ?>"><small><?php echo $dateComp; ?></small></td>
										<td class="text-right <?php echo $taskBg; ?>">
											<input type="hidden" class="tid" value="<?php echo $taskId; ?>" />
											<a href="" class="deleteTask" data-toggle="tooltip" data-placement="left" title="<?php echo $deleteText; ?>">
												<i class="fa fa-trash text-danger"></i>
											</a>
										</td>
									</tr>
							<?php
                                } ?>
						</tbody>
					</table>
				<?php
} else {
                                    ?>
					<div class="alertMsg warning mt-30 mb-10">
						<div class="msgIcon pull-left">
							<i class="fa fa-warning"></i>
						</div>
						<?php echo $noTasksText; ?>
					</div>
					<p><a href="index.php?page=newTask" class="btn btn-lg btn-info"><?php echo $addOneNowText; ?></a></p>
				<?php
                                } ?>
			</div>
		</div>
	</div>
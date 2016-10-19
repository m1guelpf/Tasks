<?php
	$pageTitle = $newTaskPageTitle;
	$new = 'true';
	$addCss = '<link href="css/datetimepicker.css" rel="stylesheet">';
	$datePicker = 'true';
	$jsFile = 'newTask';
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
				<form action="" method="post" class="mb-20">
					<div class="form-group">
						<label for="taskTitle"><?php echo $taskTitleText; ?></label>
						<input type="text" class="form-control" name="taskTitle" id="taskTitle" required="required" value="" />
						<span class="help-block"><?php echo $textOnlySpan; ?></span>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dateAssigned"><?php echo $dateAssignedText; ?></label>
								<input type="text" class="form-control" name="dateAssigned" id="dateAssigned" required="required" value="" />
								<span class="help-block"><?php echo $dateFormatSpan; ?></span>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="dateDue"><?php echo $dateDueText; ?></label>
								<input type="text" class="form-control" name="dateDue" id="dateDue" required="required" value="" />
								<span class="help-block"><?php echo $dateFormatSpan; ?></span>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="taskType"><?php echo $typeOfTaskText; ?></label>
								<input type="text" class="form-control" name="taskType" id="taskType" value="" />
								<span class="help-block"><?php echo $notRequiredSpan; ?></span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="taskRef"><?php echo $taskReferenceText; ?></label>
						<input type="text" class="form-control" name="taskRef" id="taskRef" value="" />
						<span class="help-block"><?php echo $notRequiredSpan; ?></span>
					</div>
					<div class="form-group">
						<label for="taskDesc"><?php echo $taskDescText; ?></label>
						<textarea class="form-control autosize" name="taskDesc" id="taskDesc" required="required" rows="15"></textarea>
					</div>

					<button type="input" name="submit" value="newTask" id="newTask" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveNewTaskText; ?></button>
				</form>
			</div>
		</div>
	</div>
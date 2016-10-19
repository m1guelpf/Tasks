<?php
	$pageTitle = $newNotePageTitle;
	$newnote = 'true';
	$jsFile = 'newNote';
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
						<label for="noteTitle"><?php echo $noteTitleText; ?></label>
						<input type="text" class="form-control" name="noteTitle" id="noteTitle" required="required" value="" />
						<span class="help-block"><?php echo $textOnlySpan; ?></span>
					</div>
					<div class="form-group">
						<label for="notesText"><?php echo $notesFieldText; ?></label>
						<textarea class="form-control autosize" name="notesText" id="notesText" required="required" rows="20"></textarea>
					</div>

					<button type="input" name="submit" value="newNote" id="newNote" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveNewNoteText; ?></button>
				</form>
			</div>
		</div>
	</div>
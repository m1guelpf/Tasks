<?php
    $noteId = $_GET['noteId'];

    require 'includes/flatfile.php';

    $db = new Flatfile();
    $db->datadir = 'data/notes/';

    define('USER_ID', 0);
    define('NOTE_ID', 1);
    define('NOTE_TITLE', 2);
    define('NOTE_DATE', 3);
    define('NOTE_TEXT', 4);
    define('UPDATE_DATE', 5);

    // Get the Note Data
    $note = $db->selectWhere(
        'notes.txt',
        new SimpleWhereClause(NOTE_ID, '=', $noteId)
    );
    $notedata = $db->selectAll($noteId.'.txt');

    // Set some variables to empty
    $noteTitle = $noteDate = $noteText = $lastUpd = '';

    foreach ($note as $k => $v) {
        $noteTitle = $v[2];
        if ($v[3] != '') {
            $noteDate = dateFormat($v[3]);
        } else {
            $dateFormat = '';
        }
        if ($v[5] != '') {
            $updated = dateFormat($v[5]);
        } else {
            $updated = '';
        }
        if ($v[5] != '') {
            $lastUpd = dbDateFormat($v[5]);
        } else {
            $lastUpd = '';
        }
    }

    foreach ($notedata as $k => $v) {
        if ($v[4] != '') {
            $noteText = decodeIt($v[4]);
        } else {
            $noteText = '';
        }
    }

    $pageTitle = $viewNotePageTitle;
    $jsFile = 'viewNote';
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
					<div class="row">
						<div class="col-md-9">
							<div class="form-group">
								<label for="noteTitle"><?php echo $noteTitleText; ?></label>
								<input type="text" class="form-control" name="noteTitle" id="noteTitle" required="required" value="<?php echo $noteTitle; ?>" />
								<span class="help-block"><?php echo $textOnlySpan; ?></span>
							</div>
						</div>
						<div class="col-md-3 pt-10">
							<p class="text-right">
								<a href="#" id="pageBottom" class="btn btn-sm btn-info mt-20" data-toggle="tooltip" data-placement="top" title="<?php echo $pageBottomText; ?>"><i class="fa fa-angle-down"></i></a>
								<button type="input" name="submit" value="saveNote" id="noteSave" class="btn btn-sm btn-success btn-icon mt-20"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
							</p>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="noteDate"><?php echo $dateCreatedText; ?></label>
								<input type="text" class="form-control" name="noteDate" id="noteDate" readonly="" value="<?php echo $noteDate; ?>" />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="updated"><?php echo $lastUpdatedText; ?></label>
								<input type="text" class="form-control" name="updated" id="updated" readonly="" value="<?php echo $updated; ?>" />
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="notesText"><?php echo $notesFieldText; ?></label>
						<textarea class="form-control autosize" name="notesText" id="notesText" required="required" rows="20"><?php echo $noteText; ?></textarea>
					</div>

					<div class="row">
						<div class="col-md-6">
							<input type="hidden" name="nid" id="nid" value="<?php echo $noteId; ?>" />
							<input type="hidden" name="updatDate" id="updatDate" value="<?php echo $lastUpd; ?>" />
							<button type="input" name="submit" value="saveNote" id="saveNote" class="btn btn-sm btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
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
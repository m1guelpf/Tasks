<?php
    require 'includes/flatfile.php';

    $db = new Flatfile();
    $db->datadir = 'data/notes/';

    define('USER_ID', 0);
    define('NOTE_ID', 1);
    define('NOTE_TITLE', 2);
    define('NOTE_DATE', 3);
    define('NOTE_TEXT', 4);
    define('UPDATE_DATE', 5);

    // Ge the Logged In User's Note Data
    $res = $db->selectWhere('notes.txt', new SimpleWhereClause(USER_ID, '=', $st_userId));

    $pageTitle = $notesFieldText;
    $notes = 'true';
    $addCss = '<link href="css/dataTables.css" rel="stylesheet">';
    $dataTables = 'true';
    $jsFile = 'notes';
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
				<?php if (!empty($res)) {
    ?>
					<table id="notes" class="display" cellspacing="0">
						<thead>
							<tr>
								<th><?php echo $noteTitleText; ?></th>
								<th class="text-center"><?php echo $dateSavedText; ?></th>
								<th class="text-center"><?php echo $lastUpdatedText; ?></th>
								<th class="text-center"></th>
							</tr>
						</thead>
						<tbody>
							<?php
                                foreach ($res as $k => $v) {
                                    $noteId = $v[1];
                                    $noteTitle = $v[2];
                                    $noteDate = shortMonthFormat($v[3]);
                                    $updDate = shortMonthTimeFormat($v[5]); ?>
									<tr>
										<td>
											<a href="index.php?page=viewNote&noteId=<?php echo $noteId; ?>" data-toggle="tooltip" data-placement="right" title="<?php echo $viewUpdateNoteText; ?>">
												<?php echo $noteTitle; ?>
											</a>
										</td>
										<td class="text-center"><small><?php echo $noteDate; ?></small></td>
										<td class="text-center"><small><?php echo $updDate; ?></small></td>
										<td class="text-right <?php echo $taskBg; ?>">
											<input type="hidden" class="nid" value="<?php echo $noteId; ?>" />
											<a href="" class="deleteNote" data-toggle="tooltip" data-placement="left" title="<?php echo $deleteText; ?>">
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
						<?php echo $noNotesText; ?>
					</div>
					<p><a href="index.php?page=newNote" class="btn btn-lg btn-info"><?php echo $addOneNowText; ?></a></p>
				<?php
                                } ?>
			</div>
		</div>
	</div>
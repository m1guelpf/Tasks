/**
 * Localizations
 * Only translate the text between the single quotes on lines 7 through 10.
 * Do NOT modify any code below line 10.
 **/

var noteTitleReq		= "The Note's Title can not be empty.";
var noteReq				= 'The Note can not be empty.';
var noteSavedText		= 'Cheer! The Note has been updated.';
var noteError			= 'Whoops, looks like an unexpected error was encountered, and the Note could not be updated at this time.';

/** END Localizations **/

jQuery(document).ready(function($) {

	$('#pageBottom').on('click', function(e) {
		e.preventDefault();
		$('html,body').animate({
			scrollTop: $(document).height()-$(window).height()
		}, 500);
	});

	$('#pageTop').on('click', function(e) {
		e.preventDefault();
		$('html,body').animate({
			scrollTop: 0
		}, 500);
	});

	$('#noteSave, #saveNote').click(function(e) {
		e.preventDefault();

		var noteTitle		= $("#noteTitle").val();
		var notesText		= $("#notesText").val();
		var nid				= $("#nid").val();
		var updatDate		= $("#updatDate").val();

		if (noteTitle == '') {
			Meowsa.addNotification({
				color: 'warning',
				text: noteTitleReq,
				icon: '<i class="fa fa-warning"></i>',
				timeout: 10000
			});
			$("#noteTitle").focus();
			return false;
		}

		if (notesText == '') {
			Meowsa.addNotification({
				color: 'warning',
				text: noteReq,
				icon: '<i class="fa fa-warning"></i>'
			});
			$("#notesText").focus();
			return false;
		}

		// Start the AJAX
		post_data = {
			'noteTitle':noteTitle,
			'notesText':notesText,
			'noteId':nid,
			'updatDate':updatDate
		};
		$.post('ajax/viewnote_ajax.php', post_data, function(data) {
			if (data == '1') {
				// All is good!
				Meowsa.addNotification({
					color: 'success',
					text: noteSavedText,
					icon: '<i class="fa fa-check"></i>',
					timeout: 12000
				});

				// Resize the Text Boxes to fit the updated content
				$(".autosize").each(function () {
					resizeTextArea($(this));
				});

				$('html,body').animate({
					scrollTop: 0
				}, 100);
			} else {
				// Unknown error
				Meowsa.addNotification({
					color: 'danger',
					text: noteError,
					icon: '<i class="fa fa-warning"></i>',
					timeout: 12000
				});

				$('html,body').animate({
					scrollTop: 0
				}, 100);
			}
		});

	});
});
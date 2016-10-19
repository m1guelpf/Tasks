/**
 * Localizations
 * Only translate the text between the single quotes on lines 7 through 10.
 * Do NOT modify any code below line 10.
 **/

var newNoteTitle 	= 'The New Note will need a Title.';
var newNoteEmpty 	= 'The Note can not be empty.';
var newNoteText 	= 'The New Note has been saved.';
var errorText 		= 'Looks like an unexpected error was encountered, and the New Note could not be saved at this time.';

/** END Localizations **/

jQuery(document).ready(function($) {

	$('#newNote').click(function(e) {
		e.preventDefault();

		var noteTitle		= $("#noteTitle").val();
		var notesText		= $("#notesText").val();

		if (noteTitle == '') {
			Meowsa.addNotification({
				color: 'warning',
				text: newNoteTitle,
				icon: '<i class="fa fa-warning"></i>',
				timeout: 10000
			});
			$("#noteTitle").focus();
			return false;
		}

		if (notesText == '') {
			Meowsa.addNotification({
				color: 'warning',
				text: newNoteEmpty,
				icon: '<i class="fa fa-warning"></i>'
			});
			$("#notesText").focus();
			return false;
		}

		// Start the AJAX
		post_data = {
			'noteTitle':noteTitle,
			'notesText':notesText
		};
		$.post('ajax/newnote_ajax.php', post_data, function(data) {
			if (data == '1') {
				// All is good!
				Meowsa.addNotification({
					color: 'success',
					text: newNoteText,
					icon: '<i class="fa fa-check"></i>',
					timeout: 12000
				});
				$("#noteTitle, #notesText").val('');
			} else {
				// Unknown error
				Meowsa.addNotification({
					color: 'danger',
					text: errorText,
					icon: '<i class="fa fa-warning"></i>',
					timeout: 12000
				});
			}
		});

	});

});
/**
 * Localizations
 * Only translate the text between the single quotes on lines 7 through 11.
 * Do NOT modify any code below line 11.
 **/

var deleteNoteTitle			= 'Delete Note Confirmation';
var deleteNoteQuip			= 'Are you sure you want to permentently DELETE this Note?';
var delYesOption			= 'Yes, Delete It';
var cancelDelOption			= 'Cancel';
var deleteError				= 'An Error was encountered, and the Note could not be deleted at this time.';

/** END Localizations **/

jQuery(document).ready(function($) {

	$('#notes').dataTable({
		"order": [1, 'desc'],
		"pageLength": 25
	});

	$('#notes_wrapper').addClass('pt-5 pb-20');
	$('#notes').addClass('pt-15 pb-10');

	$('.deleteNote').click(function(e) {
		e.preventDefault();
		
		var nid = $(this).closest("td").find("input").val();

		var delNoteNotification = null;
		if (Meowsa.isDismissed(delNoteNotification)) {
			delNoteNotification = Meowsa.addNotification({
				color: 'inverse',
				title: deleteNoteTitle,
				text: deleteNoteQuip,
				icon: '<i class="fa fa-sign-out fa-lg"></i>',
				button: '<input type="hidden" value="'+nid+'" /><a href="" class="btn btn-success btn-meowsa noteDelete btn-close-notification">'+delYesOption+'</a> <span id="cancel-signout" class="btn btn-warning btn-meowsa btn-close-notification">'+cancelDelOption+'</span>',
				timeout: null
			});
		}

		$('.noteDelete').click(function(e) {
			e.preventDefault();
			
			var noteid = $(this).closest("p").find("input").val();
			
			// Start the AJAX
			post_data = {
				'noteid':noteid
			};
			$.post('ajax/notes_ajax.php', post_data, function(data) {
				if (data == '1') {
					// All is good!
					setTimeout(function(){
						location.reload();
					}, 250);
				} else {
					// Unknown error
					Meowsa.addNotification({
						color: 'danger',
						text: deleteError,
						icon: '<i class="fa fa-warning"></i>',
						timeout: 12000
					});
				}
			});
		});
	});

});
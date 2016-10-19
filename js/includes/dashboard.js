/**
 * Localizations
 * Only translate the text between the single quotes on lines 7 through 11.
 * Do NOT modify any code below line 11.
 **/

var deleteTaskTitle			= 'Delete Task Confirmation';
var deleteTaskQuip			= 'Are you sure you want to permentently DELETE this Task?';
var delYesOption			= 'Yes, Delete It';
var cancelDelOption			= 'Cancel';
var deleteError				= 'An Error was encountered, and the Task could not be deleted at this time.';

/** END Localizations **/

jQuery(document).ready(function($) {

	$('#tasks').dataTable({
		"order": [3, 'desc'],
		"pageLength": 25
	});

	$('#tasks_wrapper').addClass('pt-5 pb-20');
	$('#tasks').addClass('pt-15 pb-10');

	$('.deleteTask').click(function(e) {
		e.preventDefault();
		
		var tid = $(this).closest("td").find("input").val();

		var delTaskNotification = null;
		if (Meowsa.isDismissed(delTaskNotification)) {
			delTaskNotification = Meowsa.addNotification({
				color: 'inverse',
				title: deleteTaskTitle,
				text: deleteTaskQuip,
				icon: '<i class="fa fa-sign-out fa-lg"></i>',
				button: '<input type="hidden" value="'+tid+'" /><a href="" class="btn btn-success btn-meowsa taskDelete btn-close-notification">'+delYesOption+'</a> <span id="cancel-signout" class="btn btn-warning btn-meowsa btn-close-notification">'+cancelDelOption+'</span>',
				timeout: null
			});
		}

		$('.taskDelete').click(function(e) {
			e.preventDefault();
			
			var taskid = $(this).closest("p").find("input").val();
			
			// Start the AJAX
			post_data = {
				'taskid':taskid
			};
			$.post('ajax/tasks_ajax.php', post_data, function(data) {
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
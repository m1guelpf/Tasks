/**
 * Localizations
 * Only translate the text between the single quotes on lines 7 through 12.
 * Do NOT modify any code below line 12.
 **/

var taskReqText 		= 'The New Task will need a Title.';
var taskDateAssigned 	= 'The Task needs the Date Assigned.';
var taskDateDue			= 'The Task needs the Date Due.';
var taskDescText 		= 'The Task Description is required.';
var newTaskSaved 		= 'The New Task has been successfully created.';
var errorText 			= 'Looks like an unexpected error was encountered, and the New Task could not be created at this time.';

/** END Localizations **/

jQuery(document).ready(function($) {

	$('#dateAssigned').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  0,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0
	});

	$('#dateDue').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  0,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0
	});

	$('#newTask').click(function(e) {
		e.preventDefault();

		var taskTitle		= $("#taskTitle").val();
		var dateAssigned	= $("#dateAssigned").val();
		var dateDue			= $("#dateDue").val();
		var taskType		= $("#taskType").val();
		var taskRef			= $("#taskRef").val();
		var taskDesc		= $("#taskDesc").val();

		if (taskTitle == '') {
			Meowsa.addNotification({
				color: 'warning',
				text: taskReqText,
				icon: '<i class="fa fa-warning"></i>',
				timeout: 10000
			});
			$("#taskTitle").focus();
			return false;
		}

		if (dateAssigned == '') {
			Meowsa.addNotification({
				color: 'warning',
				text: taskDateAssigned,
				icon: '<i class="fa fa-warning"></i>'
			});
			$("#dateAssigned").focus();
			return false;
		}

		if (dateDue == '') {
			Meowsa.addNotification({
				color: 'warning',
				text: taskDateDue,
				icon: '<i class="fa fa-warning"></i>'
			});
			$("#dateDue").focus();
			return false;
		}

		if (taskDesc == '') {
			Meowsa.addNotification({
				color: 'warning',
				text: taskDescText,
				icon: '<i class="fa fa-warning"></i>'
			});
			$("#taskDesc").focus();
			return false;
		}

		// Start the AJAX
		post_data = {
			'taskTitle':taskTitle,
			'dateAssigned':dateAssigned,
			'dateDue':dateDue,
			'taskType':taskType,
			'taskRef':taskRef,
			'taskDesc':taskDesc
		};
		$.post('ajax/newtask_ajax.php', post_data, function(data) {
			if (data == '1') {
				// All is good!
				Meowsa.addNotification({
					color: 'success',
					text: newTaskSaved,
					icon: '<i class="fa fa-check"></i>',
					timeout: 12000
				});
				$("#taskTitle, #dateAssigned, #dateDue, #taskType, #taskRef, #taskDesc").val('');
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
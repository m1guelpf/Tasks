/**
 * Localizations
 * Only translate the text between the single quotes on lines 7 through 10.
 * Do NOT modify any code below line 10.
 **/

var taskTitleReq		= "The Task's Title can not be empty.";
var assignedDateReq		= "The Task's Assigned Date can not be empty.";
var dueDateReq			= "The Task's Due Date can not be empty.";
var taskDescReq			= "The Task's Description can not be empty.";
var taskUpdatedMsg		= 'Cheer! The Task has been updated.';
var updateError			= 'Whoops, looks like an unexpected error was encountered, and the Task could not be updated at this time.';
var taskCompOnText		= 'Completed Task on';

/** END Localizations **/

jQuery(document).ready(function($) {

	loadStatus();

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

	$('#dateComp').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  0,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0
	});

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

	$('#taskSave, #saveTask').click(function(e) {
		e.preventDefault();

		var taskTitle		= $("#taskTitle").val();
		var dateAssigned	= $("#dateAssigned").val();
		var dateDue			= $("#dateDue").val();
		var taskType		= $("#taskType").val();
		var dateComp		= $("#dateComp").val();
		var taskStatus		= $("#taskStatus").val();
		var taskRef			= $("#taskRef").val();
		var taskDesc		= $("#taskDesc").val();
		var taskNotes		= $("#taskNotes").val();
		var tid				= $("#tid").val();
		var updatDate		= $("#updatDate").val();

		if (taskTitle == '') {
			Meowsa.addNotification({
				color: 'warning',
				text: taskTitleReq,
				icon: '<i class="fa fa-warning"></i>',
				timeout: 10000
			});
			$("#taskTitle").focus();
			return false;
		}

		if (dateAssigned == '') {
			Meowsa.addNotification({
				color: 'warning',
				text: assignedDateReq,
				icon: '<i class="fa fa-warning"></i>'
			});
			$("#dateAssigned").focus();
			return false;
		}

		if (dateDue == '') {
			Meowsa.addNotification({
				color: 'warning',
				text: dueDateReq,
				icon: '<i class="fa fa-warning"></i>'
			});
			$("#dateDue").focus();
			return false;
		}

		if (taskDesc == '') {
			Meowsa.addNotification({
				color: 'warning',
				text: taskDescReq,
				icon: '<i class="fa fa-warning"></i>'
			});
			$("#taskDesc").focus();
			return false;
		}

		// Start the AJAX
		post_data = {
			'requestType':'updateData',
			'taskTitle':taskTitle,
			'dateAssigned':dateAssigned,
			'dateDue':dateDue,
			'taskType':taskType,
			'dateComp':dateComp,
			'taskStatus':taskStatus,
			'taskRef':taskRef,
			'taskDesc':taskDesc,
			'taskNotes':taskNotes,
			'taskId':tid,
			'updatDate':updatDate
		};
		$.post('ajax/viewtask_ajax.php', post_data, function(data) {
			if (data == '1') {
				// All is good!
				Meowsa.addNotification({
					color: 'success',
					text: taskUpdatedMsg,
					icon: '<i class="fa fa-check"></i>',
					timeout: 12000
				});

				loadStatus();

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
					text: updateError,
					icon: '<i class="fa fa-warning"></i>',
					timeout: 12000
				});

				loadStatus();

				$('html,body').animate({
					scrollTop: 0
				}, 100);
			}
		});

	});
});

function loadStatus() {
	var tid = $("#tid").val();
	var msgDiv = $("#msgDiv");

	post_data = {
		'requestType':'checkStatus',
		'taskId':tid
	};
	$.post('ajax/viewtask_ajax.php', post_data, function(resdata) {
		$.each($.parseJSON(resdata), function(idx, obj) {
			var compDate = formatDate(new Date(obj[8]), "M d, y");

			if (obj[8] != '') {
				msgDiv.html('<div class="alertMsg success"><div class="msgIcon pull-left"><i class="fa fa-check"></i></div>'+taskCompOnText+' '+compDate+'</div>');}
			if (obj[8] == '') {
				msgDiv.html('');
			}
		});
	});
}

var formatDate = function(date, format) {
	date = convertDate(date);

	var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
	getPaddedComp = function(comp) {
		return ((parseInt(comp) < 10) ? ('0' + comp) : comp)
	},
	formattedDate = format,
	o = {
		"y+": date.getFullYear(),
		"M+": months[date.getMonth()],
		"d+": getPaddedComp(date.getDate())
	};

	for (var k in o) {
		if (new RegExp("("+k+")").test(format)) {
			formattedDate = formattedDate.replace(RegExp.$1, o[k]);
		}
	}
	return formattedDate;
};

var convertDate = function(date) {
	var theDate = new Date(date);
	var addDate = 1;
	theDate.setDate(theDate.getDate() + addDate);
	return theDate;
};
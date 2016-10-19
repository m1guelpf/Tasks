/**
 * Localizations
 * Only translate the text between the single quotes on lines 7 through 10.
 * Do NOT modify any code below line 10.
 **/

var accountSignOutTitle 	= 'Account Sign Out';
var accountSignOutText 		= 'Are you sure you want to sign out of your account?';
var yesOption 				= 'Yes';
var cancelOption 			= 'Cancel';

/** END Localizations **/

function resizeTextArea($element) {
    $element.height("auto");
    $element.height($element[0].scrollHeight);
}

jQuery(document).ready(function($) {
	/** ******************************
	* Side Panel
	****************************** **/
	$('.side-panel-toggle').on('click', function() {
		$('.content').toggleClass('content-is-open');
	});

	/** ******************************
	* Activate Tool-tips
	****************************** **/
    $("[data-toggle='tooltip']").tooltip();

	/** ******************************
	* Activate Popovers
	****************************** **/
	$("[data-toggle='popover']").popover();

	/** ******************************
	* Required Fields
	****************************** **/
	$("form :input[required='required']").blur(function() {
		if (!$(this).val()) {
			$(this).addClass('hasError');
		} else {
			if ($(this).hasClass('hasError')) {
				$(this).removeClass('hasError');
			}
		}
	});
	$("form :input[required='required']").change(function() {
		if ($(this).hasClass('hasError')) {
			$(this).removeClass('hasError');
		}
	});

	/** ******************************
	* Textarea Resize
	****************************** **/
	if ($(".autosize").length > 0) {
		$(".autosize").each(function () {
			resizeTextArea($(this));
		});
	}

	var signoutNotification = null;
	$('#signout').click(function(e) {
		e.preventDefault();
		if (Meowsa.isDismissed(signoutNotification)) {
			signoutNotification = Meowsa.addNotification({
				color: 'default',
				title: accountSignOutTitle,
				text: accountSignOutText,
				icon: '<i class="fa fa-sign-out fa-lg"></i>',
				button: '<a href="index.php?action=signout" class="btn btn-success btn-meowsa">'+yesOption+'</a> <span id="cancel-signout" class="btn btn-warning btn-meowsa btn-close-notification">'+cancelOption+'</span>',
				timeout: null
			});
		}
	});
});
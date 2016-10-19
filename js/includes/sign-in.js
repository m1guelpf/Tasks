/**
 * Localizations
 * Only translate the text between the single quotes on lines 7 through 24.
 * Do NOT modify any code below line 24.
 **/

var dupUsername1 		= 'Whoops, Looks like there is all ready an account registered with the Username';
var dupUsername2 		= 'Please select something different.';
var usernameQuip 		= 'Usernames can contain upper and lower case letters, numbers and dashes only. Duplicate usernames are not allowed.';
var dupEmail 			= 'Whoops, Looks like there is all ready an account registered with that Email Address.';
var usernameReq 		= 'Your Account Username is required.';
var passReq 			= 'Your Account Password is required.';
var invalidSignin 		= 'Whoops, Invalid Sign In. Please check your Username and/or Password and try again.';
var signinSuccess 		= 'Cheer! Sign In Successfull';
var signinError 		= 'Uh oh, Looks like an unexpected error was encountered, and you were not Signed In.';
var newusernameReq 		= 'Your New Account will need a Username.';
var validEmailReq 		= 'Your New Account will need a valid Email Address.';
var newpassReq 			= 'Your New Account will need a Password.';
var newAccCreated 		= 'Your New Account has been successfully created.';
var newAccError 		= 'Looks like an unexpected error was encountered, and your New Account was unable to be created.';
var accountEmailReq 	= 'Your Account Email Address is required.';
var passResetSuccess 	= 'Your Account Password has been reset, and an email has been sent with the new password.';
var noAccError 			= 'Hmmm, An Account with that Email Address could not be found.';
var resetPassError 		= 'Looks like an unexpected error was encountered, and your Account Password could not be reset.';

/** END Localizations **/

$(document).ready(
	// Toggle Sign In and Sign Up forms
	function() {
		$('#signup').on("click", function() {
			var x = this.id;
			$("#" + x).removeClass("s-atbottom");
			$("#" + x).addClass("s-attop");
			$("#login").removeClass("l-attop");
			$("#login").addClass("l-atbottom");
		});

		$('#login').on("click", function() {
			var x = this.id;
			$("#" + x).removeClass("l-atbottom");
			$("#" + x).addClass("l-attop");
			$("#signup").removeClass("s-attop");
			$("#signup").addClass("s-atbottom");
		});
	}
);

jQuery(document).ready(function($) {
	var focused = 0;

	// Check for Duplicate username
	$('#newusername').blur(function() {
		var username = $("#newusername").val();

		if (username != '' ) {
			// Start the AJAX
			post_data = {
				'username':username,
				'requestType':'usercheck'
			};
			$.post('ajax/signin_ajax.php', post_data, function(data) {
				if (data == '1') {
					// Duplicate Username found
					Meowsa.addNotification({
						color: 'warning',
						text: dupUsername1+' "'+username+'". '+dupUsername2,
						icon: '<i class="fa fa-warning"></i>',
						timeout: 12000
					});

					// Reset the form fields
					$("#newusername").val('');
				}
			});
		}
	});

	// Show Username restictions on first focus
	$('#newusername').focus(function() {
		if (focused === 0) {
			Meowsa.addNotification({
				color: 'info',
				text: usernameQuip,
				icon: '<i class="fa fa-info-circle"></i>',
				timeout: 8000
			});
			focused++;
		}
	});

	// Check for Duplicate Email
	$('#newemail').blur(function() {
		var useremail = $("#newemail").val();

		if (useremail != '') {
			// Start the AJAX
			post_data = {
				'useremail':useremail,
				'requestType':'emailcheck'
			};
			$.post('ajax/signin_ajax.php', post_data, function(data) {
				if (data == '1') {
					// Duplicate Email found
					Meowsa.addNotification({
						color: 'warning',
						text: dupEmail,
						icon: '<i class="fa fa-warning"></i>',
						timeout: 12000
					});

					// Reset the form fields
					$("#newemail").val('');
				}
			});
		}
	});

	// Sign the user in
	$('#signin-btn').click(function(e) {
		e.preventDefault();

		var username		= $("#username").val();
		var password		= $("#password").val();

		if (username == '') {
			Meowsa.addNotification({
				color: 'danger',
				text: usernameReq,
				icon: '<i class="fa fa-warning"></i>',
				timeout: 10000
			});
			$("#username").focus();
			return false;
		}

		if (password == '') {
			Meowsa.addNotification({
				color: 'danger',
				text: passReq,
				icon: '<i class="fa fa-warning"></i>',
				timeout: 10000
			});
			$("#password").focus();
			return false;
		}

		// Start the AJAX
		post_data = {
			'username':username,
			'password':password,
			'requestType':'signin'
		};
		$.post('ajax/signin_ajax.php', post_data, function(resdata) {
			var datacheck = $.parseJSON(resdata).length;
			if (datacheck === 0) {
				// Unknown error
				Meowsa.addNotification({
					color: 'warning',
					text: invalidSignin,
					icon: '<i class="fa fa-warning"></i>',
					timeout: 12000
				});

				// Reset the form fields
				$("#username, #password").val('');
			} else {
				$.each($.parseJSON(resdata), function(idx, obj) {
					if (obj[0] != '') {
						// All is good!
						Meowsa.addNotification({
							color: 'success',
							text: signinSuccess,
							icon: '<i class="fa fa-check"></i>',
							timeout: 10000
						});

						// Reset the form fields
						$("#username, #password").val('');

						// Redirect after 1.5 Seconds
						window.setTimeout(function () { location.href = "index.php"; }, 1500);
					} else {
						// Unknown error
						Meowsa.addNotification({
							color: 'danger',
							text: signinError,
							icon: '<i class="fa fa-warning"></i>',
							timeout: 12000
						});
					}
				});
			}
		});
	});

	// Create a new account
	$('#signup-btn').click(function(e) {
		e.preventDefault();

		var username		= $("#newusername").val();
		var useremail		= $("#newemail").val();
		var password		= $("#newpass").val();
		var newacc			= $("#newacc").val();

		if (newacc == '') {
			if (username == '') {
				Meowsa.addNotification({
					color: 'danger',
					text: newusernameReq,
					icon: '<i class="fa fa-warning"></i>',
					timeout: 10000
				});
				$("#newusername").focus();
				return false;
			}

			if (useremail == '') {
				Meowsa.addNotification({
					color: 'danger',
					text: validEmailReq,
					icon: '<i class="fa fa-warning"></i>',
					timeout: 10000
				});
				$("#newemail").focus();
				return false;
			}

			if (password == '') {
				Meowsa.addNotification({
					color: 'danger',
					text: newpassReq,
					icon: '<i class="fa fa-warning"></i>',
					timeout: 10000
				});
				$("#newpass").focus();
				return false;
			}

			// Start the AJAX
			post_data = {
				'username':username,
				'useremail':useremail,
				'password':password,
				'requestType':'signup'
			};
			$.post('ajax/signin_ajax.php', post_data, function(data) {
				if (data == '1') {
					// All is good!
					Meowsa.addNotification({
						color: 'success',
						text: newAccCreated,
						icon: '<i class="fa fa-check"></i>',
						timeout: 10000
					});

					// Reset the form fields
					$("#newusername, #newemail, #newpass").val('');
				} else {
					// Unknown error
					Meowsa.addNotification({
						color: 'danger',
						text: newAccError,
						icon: '<i class="fa fa-warning"></i>',
						timeout: 12000
					});
				}
			});
		} else {
			$("#newusername, #newemail, #newpass").val('');
			return false;
		}
	});

	// Reset account password
	$('#resetPass').click(function(e) {
		e.preventDefault();

		var useremail = $("#accountEmail").val();

		if (useremail == '') {
			Meowsa.addNotification({
				color: 'danger',
				text: accountEmailReq,
				icon: '<i class="fa fa-warning"></i>',
				timeout: 10000
			});
			$("#newemail").focus();
			return false;
		}

		// Start the AJAX
		post_data = {
			'useremail':useremail,
			'requestType':'resetpass'
		};
		$.post('ajax/signin_ajax.php', post_data, function(data) {
			if (data == '1') {
				// All is good!
				Meowsa.addNotification({
					color: 'success',
					text: passResetSuccess,
					icon: '<i class="fa fa-check"></i>',
					timeout: 10000
				});

				// Reset the form fields
				$("#accountEmail").val('');
			} else if (data == '0') {
				// Unknown error
				Meowsa.addNotification({
					color: 'danger',
					text: noAccError,
					icon: '<i class="fa fa-warning"></i>',
					timeout: 12000
				});

				// Reset the form fields
				$("#accountEmail").val('');
			} else {
				// Unknown error
				Meowsa.addNotification({
					color: 'danger',
					text: resetPassError,
					icon: '<i class="fa fa-warning"></i>',
					timeout: 12000
				});

				// Reset the form fields
				$("#accountEmail").val('');
			}
		});
	});

});
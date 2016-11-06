<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    // Signed In User check
    if (isset($_SESSION['st']['userId'])) {
        header('Location: index.php');
        exit;
    }

    // Include Settings
    include 'includes/config.php';

    // Include Functions
    include 'includes/functions.php';

    // Include Sessions & Localizations
    include 'includes/sessions.php';
?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="<?php echo $metaDesc; ?>">
		<meta name="author" content="<?php echo $metaAuthor; ?>">

		<title><?php echo $siteName; ?> &middot; <?php echo $signInPageTitle; ?></title>

		<link href="css/font-awesome.css" rel="stylesheet" type="text/css" />
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/meowsa.min.css" rel="stylesheet">
		<link href="css/custom.css" rel="stylesheet">
		<link href="css/sign-in.css" rel="stylesheet">

		<!--[if lt IE 9]>
			<script src="js/html5shiv.min.js"></script>
			<script src="js/respond.min.js"></script>
		<![endif]-->
	</head>

	<body>

		<div class="login-signup l-attop" id="login">
			<div class="login-signup-title"><?php echo $signInText; ?></div>
			<?php if ($signupstatus) {
    echo '<div class="login-signup-content">';
} ?>
				<form action="" method="post">
					<div class="input-name"><h2><?php echo $usernameText; ?></h2></div>
					<input type="text" name="username" id="username" placeholder="username" value="" class="field-input" />

					<div class="input-name input-margin"><h2><?php echo $passwordText; ?></h2></div>
					<input type="password" name="password" id="password" placeholder="password" value="" class="field-input" />

					<button class="submit-btn" id="signin-btn"><?php echo $signInText; ?></button>

					<div class="forgot-pass">
						<a data-toggle="modal" href="#resetPassword"><?php echo $forgotPassLink; ?></a>
					</div>
				</form>
			</div>
		<?php if ($signupstatus) {
    echo '</div>';
} ?>

		<?php if ($signupstatus) {
    echo '<div class="login-signup s-atbottom" id="signup">
			<div class="login-signup-title"><?php echo $signUpText; ?></div>
			<div class="login-signup-content">
				<form action="" method="post">
					<div class="input-name"><h2><?php echo $usernameText; ?></h2></div>
					<input type="text" name="username" id="newusername" placeholder="username" value="" class="field-input" />

					<div class="input-name input-margin"><h2><?php echo $emailText; ?></h2></div>
					<input type="email" name="newemail" id="newemail" placeholder="email" value="" class="field-input" />

					<div class="input-name input-margin"><h2><?php echo $passwordText; ?></h2></div>
					<input type="password" name="newpass" id="newpass" placeholder="password" value="" class="field-input" />

					<input type="hidden" name="newacc" id="newacc" value="" />
					<button class="submit-btn" id="signup-btn"><?php echo $createAccText; ?></button>
				</form>
			</div>
		</div>';
} ?>

		<div class="modal fade" id="resetPassword" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
						<h4 class="modal-title"><?php echo $resetAccPassText; ?></h3>
					</div>
					<form action="" method="post">
						<div class="modal-body">
							<div class="form-group">
								<label for="accountEmail"><?php echo $accEmailText; ?></label>
								<input type="email" class="form-control" required="required" name="accountEmail" id="accountEmail" placeholder="email" value="" />
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default btn-sm btn-icon" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $closeBtn; ?></button>
							<button type="input" name="submit" value="resetPass" id="resetPass" class="btn btn-success btn-sm btn-icon" data-dismiss="modal"><i class="fa fa-check-square-o"></i> <?php echo $resetPassText; ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/meowsa.min.js"></script>
		<script type="text/javascript" src="js/includes/sign-in.js"></script>

	</body>
	</html>

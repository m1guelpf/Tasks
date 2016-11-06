<?php
    require 'includes/flatfile.php';

    $db = new Flatfile();
    $db->datadir = 'data/';

    define('USER_ID', 0);
    define('USERNAME', 1);
    define('PASSWORD', 2);
    define('USER_EMAIL', 3);
    define('DATE_CREATED', 4);

    // Get the User's Account Data
    $user = $db->selectWhere(
        'users.txt',
        new SimpleWhereClause(USER_ID, '=', $st_userId)
    );

    // Set some variables to empty
    $uname = $old = $uemail = $createdate = '';

    foreach ($user as $k => $v) {
        $uname = $v[1];
        $old = $v[2];
        $uemail = $v[3];
        if ($v[4] != '') {
            $createdate = dateFormat($v[4]);
        } else {
            $createdate = '';
        }
    }

    $pageTitle = $profilePageTitle;
    $profile = 'true';
    $jsFile = 'profile';
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
				<form action="" method="post" class="mb-20">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="username"><?php echo $usernameText; ?></label>
								<input type="text" class="form-control" name="username" id="username" readonly="" value="<?php echo $uname; ?>" />
								<span class="help-block"><?php echo $usernameSpan; ?></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="createDate"><?php echo $memberSinceText; ?></label>
								<input type="text" class="form-control" name="createDate" id="createDate" readonly="" value="<?php echo $createdate; ?>" />
								<span class="help-block"><?php echo $memberSinceSpan; ?></span>
							</div>
						</div>
					</div>

					<p class="lead mt-20 mb-0"><strong><?php echo $updateAccountEmailH4; ?></strong></p>
					<div class="form-group">
						<label for="userEmail"><?php echo $emailAddressText; ?></label>
						<input type="text" class="form-control" name="userEmail" id="userEmail" required="required" value="<?php echo $uemail; ?>" />
						<span class="help-block"><?php echo $validEmailText; ?></span>
					</div>

					<p class="lead mt-20 mb-0"><strong><?php echo $changeAccPassH4; ?></strong></p>
					<p><?php echo $changeAccPassQuip; ?></p>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="password1"><?php echo $newPassText; ?></label>
								<input type="text" class="form-control" name="password1" id="password1" value="" />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="password2"><?php echo $repeatPassText; ?></label>
								<input type="text" class="form-control" name="password2" id="password2" value="" />
							</div>
						</div>
					</div>

					<input type="hidden" name="old" id="old" value="<?php echo $old; ?>" />
					<input type="hidden" name="now" id="now" value="<?php echo date('Y-m-d H:i:s'); ?>" />
					<button type="input" name="submit" value="updProfile" id="updProfile" class="btn btn-sm btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $updateProfileText; ?></button>
				</form>
			</div>
		</div>
	</div>
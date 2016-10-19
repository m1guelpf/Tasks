<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?php echo $metaDesc; ?>">
	<meta name="author" content="<?php echo $metaAuthor; ?>">

	<title><?php echo $siteName; ?> &middot; <?php echo $pageTitle; ?></title>

	<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="css/font-awesome.css" />
	<?php if (isset($addCss)) { echo $addCss; } ?>
	<link rel="stylesheet" type="text/css" href="css/meowsa.min.css" />
	<link rel="stylesheet" type="text/css" href="css/custom.css" />

	<!--[if lt IE 9]>
		<script src="js/html5shiv.min.js"></script>
		<script src="js/respond.min.js"></script>
	<![endif]-->
</head>
<body>
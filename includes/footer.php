	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<?php if (isset($dataTables)) {
    echo '<script type="text/javascript" src="js/dataTables.js"></script>';
} ?>
	<?php if (isset($datePicker)) {
    echo '<script type="text/javascript" src="js/datetimepicker.js"></script>';
} ?>
	<script type="text/javascript" src="js/meowsa.min.js"></script>
	<?php if (isset($jsFile)) {
    echo '<script type="text/javascript" src="js/includes/'.$jsFile.'.js"></script>';
} ?>
	<script type="text/javascript" src="js/custom.js"></script>

</body>
</html>
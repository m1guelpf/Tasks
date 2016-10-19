<?php
	// Set the Active State on the Navigation
	$homeNav = $newNav = $notesNav = $newnotesNav = $profileNav = '';
	if (isset($home)) { $homeNav = 'active'; } else { $homeNav = ''; }
	if (isset($new)) { $newNav = 'active'; } else { $newNav = ''; }
	if (isset($notes)) { $notesNav = 'active'; } else { $notesNav = ''; }
	if (isset($newnote)) { $newnotesNav = 'active'; } else { $newnotesNav = ''; }
	if (isset($profile)) { $profileNav = 'active'; } else { $profileNav = ''; }
?>
	<div class="sidebar">
		<div class="title"><?php echo $siteName; ?></div>
		<ul class="nav">
			<li><a href="index.php" class="<?php echo $homeNav; ?>"><?php echo $myTasksText; ?></a></li>
			<li><a href="index.php?page=newTask" class="<?php echo $newNav; ?>"><?php echo $newTaskText; ?></a></li>
			<li><a href="index.php?page=notes" class="<?php echo $notesNav; ?>"><?php echo $myNotesText; ?></a></li>
			<li><a href="index.php?page=newNote" class="<?php echo $newnotesNav; ?>"><?php echo $newNoteText; ?></a></li>
			<li><a href="index.php?page=profile" class="<?php echo $profileNav; ?>"><?php echo $profileText; ?></a></li>
			<li><a href="" id="signout"><?php echo $signOutText; ?></a></li>
		</ul>
	</div>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container">
	<h1>Your profile</h1>
	<h2>Personal details</h2>
	<?= anchor('user/edit_profile', "Edit"); ?>
	<div class="basic">
		<p>Name: <?= $user->first_name." ".$user->last_name; ?></p>
		<p>Username: <?= $user->username; ?></p>
		<p>Email: <?= $user->email; ?></p>
	</div>

	<div class="details">
		<!-- user profile -->
		<p>Profile picture</p>
		<?php if ($user->profile->picture != "") { ?>
			<img src="<?=base_url('uploads/profile_pictures/') ?><?= $user->profile->picture; ?>" />
		<?php } else { ?>
			<img src="<?=base_url('public/images/no_pic.jpg') ?>"
		<?php } ?>
	</div>

	<hr />
	<h2>Bank accounts</h2>
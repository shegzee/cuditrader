<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container">
	<h1>Your profile</h1>
	<h2>Personal details</h2>
	<div class="basic">
		<p>Name: <?= $user->first_name." ".$user->last_name; ?></p>
		<p>Username: <?= $user->username; ?></p>
		<p>Email: <?= $user->email; ?></p>
	</div>

	<div class="details">
		<?php if ($user_profile) { ?>
			<!-- user profile -->
			<p>Profile picture</p>
			<img src="profile_pictures/<?= $user_profile->picture; ?>" />
		<?php } ?>
	</div>

	<hr />
	<h2>Bank accounts</h2>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container">
	<?= isset($_SESSION['message']) ? $_SESSION['message'] : FALSE ?>
	<h1>Edit Profile</h1>
	<?php
	echo form_open();
	echo form_label('First name:', 'first_name').'<br />';
	echo form_error('first_name');
	echo form_input('first_name', set_value('first_name', $user->first_name)).'<br />';
	echo form_label('Last name:', 'last_name').'<br />';
	echo form_error('last_name');
	echo form_input('last_name', set_value('last_name', $user->last_name)).'<br />';
	echo form_label('Email:', 'email').'<br />';
	echo form_error('email');
	echo form_input('email', set_value('email', $user->email)).'<br />';
	echo form_submit('save', 'Save');
	echo form_close();
	?>
	<?= form_open_multipart('user/upload_profile_picture'); ?>
	<?php if ($user->profile) { ?>
		<img src="<?=base_url('uploads/profile_pictures/') ?><?= $user->profile->picture; ?>" /><br />
	<?php } ?>
	<input type="file" name="new_picture" size="20" />
	<?php
	echo form_submit('change', 'Change profile picture');
	echo form_close();
	?>

</div>
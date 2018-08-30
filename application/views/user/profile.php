<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container">
	<h1>Your profile</h1>
	<h2>Personal details</h2>
	<?= anchor('user/edit_profile', "Edit"); ?>
	<div id="basic">
		<p>Name: <?= $user->first_name." ".$user->last_name; ?></p>
		<p>Username: <?= $user->username; ?></p>
		<p>Email: <?= $user->email; ?></p>
	</div>

	<div id="details">
		<!-- user profile -->
		<p>Profile picture</p>
		<?php if ($user->profile->picture != "") { ?>
			<img src="<?=base_url('uploads/profile_pictures/') ?><?= $user->profile->picture; ?>" />
		<?php } else { ?>
			<img src="<?=base_url('public/images/no_pic.jpg') ?>" />
		<?php } ?>
	</div>

	<hr />
	<h2>Bank accounts</h2>
	<div id="accounts">
		<?= anchor("bank/add", "Add account") ?>
		<table border="1">
			<tr>
				<td>Bank</td>
				<td>Account number</td>
				<td>Account name</td>
				<td>Account type</td>
				<td>Is Primary?</td>
				<td>Description</td>
			</tr>
			<?php foreach ($bank_details as $bank_account): ?>
				<tr>
					<td><?= $banks[$bank_account['bank_id']]["name"] ?></td>
					<td><?= $bank_account['account_number'] ?></td>
					<td><?= $bank_account['account_name'] ?></td>
					<td><?= $account_types[$bank_account['account_type_id']]['name'] ?></td>
					<td><?= $bank_account['is_primary'] ? "Yes": "No" ?></td><!-- will replace with toggle switch; which will also alter previous primary -->
					<td><?= $bank_account['description'] ?></td>
				</tr>
			<?php endforeach ?>
		</table>
	</div>
</div>
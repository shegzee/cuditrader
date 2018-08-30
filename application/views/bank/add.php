<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container">
	<?= isset($_SESSION['auth_message']) ? $_SESSION['auth_message'] : FALSE ?>
	<h1>Register</h1>
	<?php
	echo form_open();
	echo form_label('Bank:', 'bank_id').'<br />';
	echo form_error('bank_id');
	echo form_dropdown('bank_id', $banks, set_select('bank_id')).'<br />'; // $banks = array('id' => 'name')
	echo form_label('Account number:', 'account_number').'<br />';
	echo form_error('account_number');
	echo form_input('account_number', set_value('account_number')).'<br />';
	echo form_label('Account Name:', 'account_name').'<br />';
	echo form_error('account_name');
	echo form_input('account_name', set_value('account_name')).'<br />';
	echo form_label('Account type:', 'account_type_id').'<br />';
	echo form_error('account_type_id');
	echo form_dropdown('account_type_id', $account_types, set_select('account_type_id')).'<br />';
	echo form_label('Primary account:', 'is_primary').'<br />';
	echo form_error('is_primary');
	echo form_checkbox('is_primary', set_checkbox('is_primary')).'<br />'; // use check box here
	echo form_label('Description:', 'description').'<br />';
	echo form_error('description');
	echo form_textarea('description').'<br /><br />'; // use TEXTAREA here
	echo form_submit('Add bank account', 'Add bank account');
	echo form_close();
	?>
</div>

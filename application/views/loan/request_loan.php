<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style type="text/css">
	.status {
		color: rgb(0,0,0);
	}
	.status-pending {
		background-color: rgb(255,255,0);
 	}
	.status-approved {
		background-color: rgb(0,127,0);
	}
	.status-cleared {
		background-color: rgb(0,0,255);
	}
	.status-denied {
		background-color: rgb(255,0,0);
	}
	.status-cancelled {
		background-color: rgba(0,0,0,0.5);
	}
</style>
<div class="container">
	<?= isset($_SESSION['message']) ? $_SESSION['message'] : FALSE ?>
	<h1>Request a Loan</h1>
	<p><a href="<?= base_url('loan/request'); ?>">Request a new loan</a></p>

	<p><a href="<?= base_url('loan/'); ?>"><span class="status">ALL</span></a>
		<?php foreach ($statuses as $this_status) { ?>
		<?php if ($this_status == "GRANTED") {continue;} ?>
		<a href="<?= base_url('loan/status/'.strtolower($this_status)); ?>"><span class="status status-<?= strtolower($this_status); ?>"><?= $this_status; ?></span></a>
	<?php } ?>
	</p>
	<?php
	echo validation_errors();
	echo form_open();
	echo form_label('Loan currency:', 'loan_unit_id').'<br />';
	echo form_error('loan_unit_id');
	echo form_dropdown('loan_unit_id', $loan_currencies, set_select('loan_unit_id')).'<br />';
	echo form_label('Loan amount:', 'loan_amount').'<br />';
	echo form_error('loan_amount');
	echo form_input('loan_amount', set_value('loan_amount')).'<br />';
	echo form_label('Cryptocurrency:', 'collateral_unit_id').'<br />';
	echo form_error('collateral_unit_id');
	echo form_dropdown('collateral_unit_id', $cryptocurrencies, set_value('collateral_unit_id')).'<br />';
	echo form_label('Cryptocurrency amount:', 'collateral_amount').'<br />';
	echo form_error('collateral_amount');
	echo form_input('collateral_amount', set_value('collateral_amount')).'<br />';
	echo form_label('Loan Duration (months):', 'loan_duration').'<br />';
	echo form_error('loan_duration');
	echo form_input('loan_duration', set_value('loan_duration'), array("type"=>"number")).'<br />';

	echo form_submit('request', 'Request');
	echo form_close();
	?>
</div>

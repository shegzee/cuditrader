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
	<h1>Loan</h1>
	<p><a href="<?= base_url('loan/request'); ?>">Request a new loan</a></p>

	<p><a href="<?= base_url('loan/'); ?>"><span class="status">ALL</span></a>
		<?php foreach ($statuses as $this_status) { ?>
		<?php if ($this_status == "GRANTED") {continue;} ?>
		<a href="<?= base_url('loan/status/'.strtolower($this_status)); ?>"><span class="status status-<?= strtolower($this_status); ?>"><?= $this_status; ?></span></a>
	<?php } ?>
	</p>
	<p>Amount: <?= $loan->loan_amount; ?></p>
	<p>Loan Unit: <?= $loan_currencies[$loan->loan_unit_id]; ?></p>
	<p>Collateral: <?= $loan->collateral_amount; ?></p>
	<p>Collateral Unit: <?= $cryptocurrencies[$loan->collateral_unit_id]; ?></p>
	<p>Duration: <?= $loan->loan_duration; ?></p>
	<p>Status: <a href="<?= base_url('loan/status/'.strtolower($statuses[$loan->status_number])); ?>"><span class="status status-<?= strtolower($statuses[$loan->status_number]); ?>"><?= $statuses[$loan->status_number]; ?></span></a></p>
	<?php if ($loan->status_number == $status_ids['DENIED']) { ?>
	<?php } else if ($loan->status_number >= $status_ids['APPROVED']) { ?>
	<p>Approved on: <?= $loan->approved_on; ?></p>
	<?php } ?>
	<?php if ($loan->status_number == $status_ids['CLEARED']) { ?>
	<p>Cleared on: <?= $loan->cleared_on; ?></p>
	<?php } ?>

</div>
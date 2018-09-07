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
	<h1><?= ucfirst($status) ?> Loans</h1>

	<p><a href="<?= base_url('loan/request'); ?>">Request a new loan</a></p>

	<p><a href="<?= base_url('loan/'); ?>"><span class="status">ALL</span></a>
		<?php foreach ($statuses as $this_status) { ?>
		<?php if ($this_status == "GRANTED") {continue;} ?>
		<a href="<?= base_url('loan/status/'.strtolower($this_status)); ?>"><span class="status status-<?= strtolower($this_status); ?>"><?= $this_status; ?></span></a>
	<?php } ?>
	</p>
	<?php if($loans):?>
		<table border="1">
			<tr>
				<th>#</th>
				<th>Amount</th>
				<th>Unit</th>
				<th>Collateral amount</th>
				<th>Collateral unit</th>
				<th>Loan duration</th>
				<th>Status</th>
				<th>Date requested</th>
				<?php if (strcasecmp($status, "approved") == 0) { ?>
					<th>Date approved</th>
				<?php } ?>
				<?php if (strcasecmp($status, "denied") == 0) { ?>
					<th>Date denied</th>
				<?php } ?>
				<?php if (strcasecmp($status, "cleared") == 0) { ?>
					<th>Date approved</th>
					<th>Date cleared</th>
				<?php } ?>
				<?php if (strcasecmp($status, "cancelled") == 0) { ?>
					<th>Date cancelled</th>
				<?php } ?>
					<th>Actions</th>
			</tr>
			<?php $sno = 1; ?>
			<?php foreach ($loans as $loan) { ?>
			<tr>
				<td><a href="<?=base_url('loan/'.$loan->id); ?>"><?= $sno++; ?></a></td>
				<td><?= $loan->loan_amount; ?></td>
				<td><?= $loan_currencies[$loan->loan_unit_id]; ?></td>
				<td><?= $loan->collateral_amount; ?></td>
				<td><?= $cryptocurrencies[$loan->collateral_unit_id]; ?></td>
				<td><?= $loan->loan_duration; ?></td>
				<td><a href="<?= base_url('loan/status/'.strtolower($statuses[$loan->status_number])); ?>"><span class="status status-<?= strtolower($statuses[$loan->status_number]); ?>"><?= $statuses[$loan->status_number]; ?></span></a></td>
				<td><?= $loan->requested_on; ?></td>

				<?php if (strcasecmp($status, "approved") == 0 || strcasecmp($status, "denied") == 0 || strcasecmp($status, "cleared") == 0) { ?>
					<td><?= $loan->approved_on; ?></td>
				<?php } ?>
				<?php if (strcasecmp($status, "cleared") == 0) { ?>
					<td><?= $loan->cleared_on; ?></td>
				<?php } ?>
				<?php if (strcasecmp($status, "cancelled") == 0) { ?>
					<td><?= $loan->approved_on; ?></td>
				<?php } ?>
				<th><?php if (strcasecmp(strtolower($statuses[$loan->status_number]), "pending") == 0) { ?>
					<a href="<?= base_url('loan/cancel/'.$loan->id); ?>" title="Cancel Loan Request">x</a>
				<?php } ?></th>
			</tr>
			<?php } ?>
		</table>
	<?php else:?>
		<p>No loans found</p>
	<?php endif;?>

</div>
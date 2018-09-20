<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <p class="header-description">View <?= $status ?> loan status and history here.</p>
      <p>
        <a href="<?= base_url('user/loans/'); ?>"><span class="status">ALL</span></a>
    <?php foreach ($statuses as $this_status) { ?>
    <?php if ($this_status == "GRANTED") {continue;} ?>
    <a href="<?= base_url('user/loans/'.strtolower($this_status)); ?>"><span class="loan-status loan-status-<?= strtolower($this_status); ?>"><?= $this_status; ?></span></a>
    <?php } ?>
    <a href="<?= base_url('loan/request'); ?>"><span class="status">REQUEST</span></a></p>
    <?= isset($_SESSION['message']) ? "<p>".$_SESSION['message']."</p>" : FALSE ?>
      <?php if($loans): ?>
      <table id="keywords" cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <th>
                <span>Amount</span>
              </th>
              <th>
                <span>Collateral Amount</span>
              </th>
              <th>
                <span>Collateral Unit</span>
              </th>
              <th>
                <span>Loan Duration</span>
              </th>
              <?php if ($status = "APPROVED"): ?>
              <th>
                <span>Due Date</span>
              </th>
              <?php endif; ?>
              <th>
                <span>Status</span>
              </th>
              <th>
                <span>Action</span>
              </th>
            </tr>
          </thead>
          <?php $sno = 1; ?>
          <?php foreach ($loans as $loan) { ?>
          <tbody>
            <tr>
              <!-- <td><a href="<?=base_url('loan/'.$loan->id); ?>"><?= $sno++; ?></a></td> -->
              <td class="lalign"><?= html_entity_decode($loan_unit_icons[$loan->loan_unit_id])?><?= $loan->loan_amount; ?></td>
              <td><?= $loan->collateral_amount; ?> <?=html_entity_decode($collateral_unit_icons[$loan->collateral_unit_id])?></td>
              <td><?= $cryptocurrencies[$loan->collateral_unit_id]; ?></td>
              <td><?= $loan->loan_duration; ?> Months</td>
              <?php if ($status = "APPROVED"): ?>
              <td><?= date('jS F, Y', strtotime("+".$loan->loan_duration." months", strtotime($loan->approved_on))) ?></td>
              <?php endif; ?>
              <td><a href="<?= base_url('user/loans/'.strtolower($statuses[$loan->status_number])); ?>"><span class="status status-<?= strtolower($statuses[$loan->status_number]); ?>"><?= $statuses[$loan->status_number]; ?></span></a></td>
              <td><a href="<?=base_url('loan/'.$loan->id); ?>">View</a><?php if (strcasecmp(strtolower($statuses[$loan->status_number]), "pending") == 0) { ?> | <a href="<?= base_url('loan/cancel/'.$loan->id); ?>" title="Cancel Loan Request">x</a>
              <?php } ?></a></td>
            </tr>
          </tbody>
          <?php } ?>
          <?php else:?>
          <p>No loans found</p>
          <?php endif; ?>
          <!-- <tbody>
            <tr>
              <td class="lalign">50,000</td>
              <td>0.02345</td>
              <td>Bitcoin</td>
              <td>3 Months</td>
              <td>Approved</td>
              <td class="text-info">View</td>
            </tr>
          </tbody>
          <tbody>
            <tr>
              <td class="lalign">100,000</td>
              <td>0.04690</td>
              <td>Ethereum</td>
              <td>6 Months</td>
              <td>Denied</td>
              <td class="text-info">View</td>
            </tr>
          </tbody> -->
        </table>
    </section>
  </div>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<p class="header-description">Manage your bank accounts here.</p>
<?= isset($_SESSION['message']) ? "<p>".$_SESSION['message']."</p>" : FALSE ?>

<table id="keywords" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th>
              <span>Bank</span>
            </th>
            <th>
              <span>Account Number</span>
            </th>
            <th>
              <span>Account Name</span>
            </th>
            <th>
              <span>Account Type</span>
            </th>
            <th>
              <span>Primary Account</span>
            </th>
            <th>
              <span>Description</span>
            </th>
            <th>
              <span>Action</span>
            </th>
          </tr>
        </thead>
        <?php foreach ($bank_details as $bank_account): ?>
        <tbody>
          <tr>
            <td class="lalign"><?= $banks[$bank_account['bank_id']]["name"] ?></td>
            <td><?= $bank_account['account_number'] ?></td>
            <td><?= $bank_account['account_name'] ?></td>
            <td><?= $account_types[$bank_account['account_type_id']]['name'] ?></td>
            <td><?= $bank_account['is_primary'] ? '<span class="text-success">YES</span>': "NO" ?></td>
            <td><?= $bank_account['description'] ?></td>
            <td><?= !$bank_account['is_primary'] ? '<a href="'.base_url('bank/set_primary/'.$bank_account['id']).'"><span class="fa fa-check text-primary">Set as Primary</span></a>': "" ?><br>
            	<a href="<?=base_url('bank/delete/'.$bank_account['id'])?>"><span class="fa fa-remove text-danger" title="Remove account"><span>Remove</span></span></a></td>
          </tr>
        </tbody>
        <?php endforeach ?>
        <!-- <tbody>
          <tr>
            <td class="lalign">Diamond bank</td>
            <td>0070180179</td>
            <td>Theophilus Ajayi</td>
            <td>Savings</td>
            <td>No</td>
            <td>My savings account</td>
            <td class="text-danger">Remove</td>
          </tr>
        </tbody> -->
        <tfoot>
          <tr>
            <td colspan="4">
              <a data-toggle="modal" data-target="#bankModal">
                  <span class="fa fa-plus-circle" id="add-icon">
                      <span> Add new bank</span>
                    </span>
              </a>
            </td>
          </tr>
        </tfoot>
      </table>
      <!-- Bank Modal -->
      <div class="modal" id="bankModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
              	<?= form_open(); ?>
                  <div class="modal-header">
                      <h5><strong>Add New Bank Account: </strong></h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <div class="input-group">
                          <?php
							echo form_label('Bank Name:', 'bank_id');
							echo form_error('bank_id');
							echo form_dropdown('bank_id', $banks_dropdown, set_value('bank_id'), "required='required'");
                          ?>
                        </div>
                      <div class="input-group">
                          <?php
							echo form_label('Account number:', 'account_number');
							echo form_error('account_number');
							echo form_input('account_number', set_value('account_number'), "required='required'");
                          ?>
                        </div>
                        <div class="input-group">
                        	<?php
                        		echo form_label('Account Name:', 'account_name');
								echo form_error('account_name');
								echo form_input('account_name', set_value('account_name'));
                        	?>
                        </div>
                        <div class="input-group">
                            <?php
                            	echo form_label('Account type:', 'account_type_id');
								echo form_error('account_type_id');
								echo form_dropdown('account_type_id', $account_types_dropdown, set_value('account_type_id'), "required='required'");
                            ?>
                          </div>
                          <div class="input-group">
                              <?php
                              	echo form_label('Set as primary account:', 'is_primary');
								echo form_error('is_primary');
								echo form_checkbox('is_primary', "1");
                              ?>
                            </div>
                          <div class="input-group">
                              <?php
                              	echo form_label('Description:', 'description');
								echo form_error('description');
								echo form_textarea('description');
                              ?>
                            </div>
                  </div>
                  <div class="modal-footer">
                      <!-- <input ng-click="vm.CreateEvent(vm.bid)" ng-disabled="" type="button" class="btn btn-primary" data-dismiss="modal" onclick="form.submit()" value="OK" /> -->
                      <?= form_submit('OK', 'OK', 'class="btn btn-primary"'); ?>
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                  </div>
                <?= form_close(); ?>
              </div>
          </div>
      </div>


    </section>
  </div>